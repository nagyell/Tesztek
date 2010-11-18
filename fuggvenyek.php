<?php
function regisztral($nev, $jelszo) {
	global $host, $user, $pass, $db;
	$con = mysql_connect($host, $user, $pass);
	if (!mysql_select_db($db, $con)) {
		echo "Nemletezo adatbazis!<br/>\n";
	}
	$res = mysql_query("SELECT * FROM felhasznalok WHERE nev=\"$nev\";");
	if (mysql_num_rows($res) != 0) {
		mysql_close($con);
		return false;
	}
	$res = mysql_query("INSERT INTO felhasznalok (Nev, jelszo) VALUES (\"$nev\", \"".sha1($jelszo)."\");");
	mysql_close($con);
	return true;
}

//ez a belepo fuggveny
function belep($nev, $jelszo) {
	global $host, $user, $pass, $db;
	$con = mysql_connect($host, $user, $pass);
	if (!mysql_select_db($db, $con)) {
		echo "Nemletezo adatbazis!<br/>\n";
	}
	$res = mysql_query("select nev, jogok, vnev, knev, csoportkod from felhasznalok where nev='" . $nev . "' and jelszo='" . sha1($jelszo) . "';");
//	print "select nev, jogok, vnev, knev, csoportkod from felhasznalok where nev='" . $nev . "' and jelszo='" . sha1($jelszo) . "';";
	if (mysql_num_rows($res) != 1) {
		mysql_close($con);
		return false;
	}
	$sor=mysql_fetch_array($res);
	$query = "update felhasznalok set belepett='1' where nev='" . $nev . "';";
	$_SESSION['nev']=$_POST["nev"];
	$_SESSION['jelszo']=sha1($jelszo);
	$_SESSION['jogok']=$sor["jogok"];
	$_SESSION["vnev"]=$sor["vnev"];
	$_SESSION["knev"]=$sor["knev"];
	$_SESSION["csoportkod"]=$sor["csoportkod"];
	mysql_query($query);
	mysql_close($con);
	return true;
}

function kilep($nev) {
	global $host, $user, $pass, $db;
	$con = mysql_connect($host, $user, $pass);
	if (!mysql_select_db($db, $con)) {
		echo "Nemletezo adatbazis!<br/>\n";
	}
	$query = "update felhasznalok set belepett='0' where nev='" . $nev . "';";
	mysql_query($query);
	mysql_close($con);
	unset($_SESSION["nev"]);
	session_unset();
	session_destroy();
	return true;
}

function csoportom() {
	global $host, $user, $pass, $db;
	$con = mysql_connect($host, $user, $pass);
	if (!mysql_select_db($db, $con)) {
		echo "Nemletezo adatbazis!<br/>\n";
	}
	$res = mysql_query("select csoport from csoportok where csoportkod=" .$_SESSION["csoportkod"].";");
	if (mysql_num_rows($res) != 1) {
		mysql_close($con);
		return false;
	}
	$sor=mysql_fetch_array($res);
	return $sor["csoport"];
}

function elerhetotesztek($csoportkod) {
	global $host, $user, $pass, $db;
	$con = mysql_connect($host, $user, $pass);
	if (!mysql_select_db($db, $con)) {
		echo "Nemletezo adatbazis!<br/>\n";
	}
	$res = mysql_query("select tesztnev from teszt inner join lathatotesztek on teszt.tesztkod = lathatotesztek.tesztkod where lathatotesztek.csoportkod='" . $csoportkod . "';");
 	if (mysql_num_rows($res) == 0) {
		mysql_close($con);
		return false;
	}
	print "Az elérhető tesztek:\n";
	while ($sor=mysql_fetch_array($res))
		{
			print $sor["tesztnev"]."\n";
		}
	mysql_close($con);
	return true;
}

function tesztletrehozas() {
?>
		<form name="ujteszt" action="index.php" method="post">
		<table>
			<tr><td>Az új teszt neve:</td>
				<td><input name="tesztnev" type="text"></td></tr>
			<tr><td><input type="submit" value="OK"></td></tr>
		</table>
		<input type="hidden" name="menupont" value="tesztnevellenorzes"/>
<?php
}

function tesztnevellenorzes() {
	global $host, $user, $pass, $db;
	$con = mysql_connect($host, $user, $pass);
	if (!mysql_select_db($db, $con)) {
		echo "Nemletezo adatbazis!<br/>\n";
	}
 	if (!mysql_query("INSERT INTO `teszt` (`tesztnev`) VALUES ('".$_POST[tesztnev]."');")) {
		mysql_close($con);
		return false;
	}
	mysql_close($con);
?>
		<form name="tesztnevellenorzes" action="index.php" method="post">
		<table>
			<tr><td><input type="submit" value="Rendben"></td></tr>
		</table>
		<input type="hidden" name="menupont" value="beadatok"/>
<?php
}

function osszesteszt() {
	global $host, $user, $pass, $db;
	$con = mysql_connect($host, $user, $pass);
	if (!mysql_select_db($db, $con)) {
		echo "Nemletezo adatbazis!<br/>\n";
	}
	$res = mysql_query("select tesztkod,tesztnev from teszt;");
 	if (mysql_num_rows($res) == 0) {
		mysql_close($con);
		return false;
	}
	$csoportok = mysql_query("select csoport from csoportok;");
 	if (mysql_num_rows($csoportok) == 0) {
		mysql_close($con);
		return false;
	}
	print "Az összes teszt:<br>\n";
	?>
	<form name="tesztnevellenorzes" action="index.php" method="post">
	<input type="hidden" name="menupont" value="teszt-muvelet"/>
	<?php
	$sor=mysql_fetch_array($res);
	print "<input type=\"radio\" name=\"kivalasztott_teszt\" value=\"".$sor["tesztkod"]."\"/ checked>".$sor["tesztnev"]."<br />\n";
	while ($sor=mysql_fetch_array($res))
		{
		print "<input type=\"radio\" name=\"kivalasztott_teszt\" value=\"".$sor["tesztkod"]."\"/>".$sor["tesztnev"]."<br />\n";
		}
	?>
	<input type="submit" name="teszt-szerkeszt" value="Szerkeszt">
	<br />
	<br />
	<select size="1" name="kivalasztott_csoport" >
		<?php 
			$csoport=mysql_fetch_array($csoportok);
			print "<option selected value=\"".$csoport["csoport"]."\">".$csoport["csoport"]."</option>";
			while ($csoport=mysql_fetch_array($csoportok))
			{
			print "<option value=\"".$csoport["csoport"]."\">".$csoport["csoport"]."</option>";
			}
		?>
	</selected>
	<input type="submit" name="csoport-megosztas" value="Megoszt">
	</form>
	<?php
	mysql_close($con);
}

function megoszt($csoport,$teszt_kod) {
	global $host, $user, $pass, $db;
	$con = mysql_connect($host, $user, $pass);
	if (!mysql_select_db($db, $con)) {
		echo "Nemletezo adatbazis!<br/>\n";
	}
	$res = mysql_query("SELECT csoportkod FROM csoportok WHERE csoport='".$csoport."';");
	if (mysql_num_rows($res) == 0) {
		mysql_close($con);
		return false;
	}
	$sor = mysql_fetch_array($res);
	$csoport_kod = $sor["csoportkod"];
 	if (!mysql_query("INSERT INTO `lathatotesztek` (`csoportkod`, `tesztkod`) VALUES ('".$csoport_kod."', ".$teszt_kod."); ")) {
		mysql_close($con);
		return false;
	}
	mysql_close($con);
	return true;
}

function megosztasok($becsoport) {
	global $host, $user, $pass, $db;
	$con = mysql_connect($host, $user, $pass);
	if (!mysql_select_db($db, $con)) {
		echo "Nemletezo adatbazis!<br/>\n";
	}
	$csoportok = mysql_query("select csoport, csoportkod from csoportok;");
 	if (mysql_num_rows($csoportok) == 0) {
		mysql_close($con);
		return false;
	}
	?>
	<form name="megosztasok" action="index.php" method="post">
	<input type="hidden" name="menupont" value="megosztasok"/>
	Valaszd ki a csoportot:
	<select size="1" name="kivalasztott_csoport" >
		<?php 
//s			$csoport=mysql_fetch_array($csoportok);
			print "<option selected value=\"".$becsoport."\">".$becsoport."</option>";
			while ($csoport=mysql_fetch_array($csoportok))
			{
				if ($csoport["csoport"]!=$becsoport) { print "<option value=\"".$csoport["csoport"]."\">".$csoport["csoport"]."</option>"; }
			}
		?>
	</selected>
	<input type="submit" name="csoport-kivalasztas" value="Kivalaszt">
	</form>
	<?php
	mysql_close($con);
	return true;
}

function tesztjei($kivalasztott_csoport) {
	global $host, $user, $pass, $db;
	$con = mysql_connect($host, $user, $pass);
	if (!mysql_select_db($db, $con)) {
		echo "Nemletezo adatbazis!<br/>\n";
	}
	$res = mysql_query("SELECT csoportkod FROM csoportok WHERE csoport='".$kivalasztott_csoport."';");
	if (mysql_num_rows($res) == 0) {
		mysql_close($con);
		echo "haho";
		return false;
	}
	$sor = mysql_fetch_array($res);
	$csoport_kod = $sor["csoportkod"];
	$res = mysql_query("SELECT `teszt`.`tesztkod`, `teszt`.`tesztnev` FROM `lathatotesztek` LEFT JOIN `tesztek`.`teszt` ON `lathatotesztek`.`tesztkod` = `teszt`.`tesztkod` WHERE (`lathatotesztek`.`csoportkod`=".$csoport_kod.");");
 	if (mysql_num_rows($res) == 0) {
		mysql_close($con);
		return false;
	}
	print "A(z) ".$kivalasztott_csoport." lathato tesztjei:<br>\n";
	?>
	<form name="csoporttesztjei" action="index.php" method="post">
	<input type="hidden" name="menupont" value="megosztasok"/>
	<?php
	$sor=mysql_fetch_array($res);
	print "<input type=\"radio\" name=\"kivalasztott_teszt\" value=\"".$sor["tesztkod"]."\"/ checked>".$sor["tesztnev"]."<br />\n";
	while ($sor=mysql_fetch_array($res))
		{
		print "<input type=\"radio\" name=\"kivalasztott_teszt\" value=\"".$sor["tesztkod"]."\"/>".$sor["tesztnev"]."<br />\n";
		}
	print "<input type=\"hidden\" name=\"kivalasztott_csoportkod\" value=\"".$csoport_kod."\"/>";
	print "<input type=\"submit\" name=\"lathatosagot-torol\" value=\"Torol\">";
	print "</form>";
	mysql_close($con);
	return true;
}

function lathato_tesztet_torol($csoport_kod,$teszt_kod) {
	global $host, $user, $pass, $db;
	$con = mysql_connect($host, $user, $pass);
	if (!mysql_select_db($db, $con)) {
		echo "Nemletezo adatbazis!<br/>\n";
	}
 	if (!mysql_query("DELETE lathatotesztek . * FROM lathatotesztek WHERE csoportkod =".$csoport_kod." AND tesztkod =".$teszt_kod.";")) {
		mysql_close($con);
		return false;
	}
	mysql_close($con);
	return true;
}

/*
 * Megkeresi egy kerdes rakovetkezojet.
 * Ha a $strict==true, akkor a szigoruan nagyobb kerdes szamat adja vissza, ha
 * false, akkor visszaadja az $kerdes erteket ha letezik
 * Ha nincs eredmeny, akkor a $kerdes erteket teriti vissza
 */
function kovetkezo_kerdes($tesztkod, $kerdes, $strict) {
	global $host, $user, $pass, $db;
	$con = mysql_connect($host, $user, $pass);
	if (!mysql_select_db($db, $con)) {
		echo "Nemletezo adatbazis!<br/>\n";
	}
	$sql = "SELECT tesztkod, kerdesszam FROM kerdesek WHERE tesztkod=".$tesztkod." AND ";
	
	if($strict){
		$sql.="kerdesszam > ".$kerdes;
	} else {
		$sql.="kerdesszam >= ".$kerdes;
	}
	$sql .= " ORDER BY kerdesszam";
	// print $sql;
	$res = mysql_query($sql);
 	if (mysql_num_rows($res) == 0) {
		return $kerdes;
	}
	
	$sor=mysql_fetch_array($res);
	$kerdesszam = $sor["kerdesszam"];
	mysql_close($con);
	return $kerdesszam;
}

/*
 * Megkeresi egy kerdes elotti kerdest.
 * Ha a $strict==true, akkor a szigoruan nagyobb kerdes szamat adja vissza, ha
 * false, akkor visszaadja az $kerdes erteket ha letezik
 * Ha nincs eredmeny, akkor a $kerdes erteket teriti vissza
 */
function elozo_kerdes($tesztkod, $kerdes, $strict) {
	global $host, $user, $pass, $db;
	$con = mysql_connect($host, $user, $pass);
	if (!mysql_select_db($db, $con)) {
		echo "Nemletezo adatbazis!<br/>\n";
	}
	$sql = "SELECT tesztkod, kerdesszam FROM kerdesek WHERE tesztkod=".$tesztkod." AND ";
	
	if($strict){
		$sql.="kerdesszam < ".$kerdes;
	} else {
		$sql.="kerdesszam <= ".$kerdes;
	}
	$sql .= " ORDER BY kerdesszam DESC";
	// print $sql;
	$res = mysql_query($sql);
 	if (mysql_num_rows($res) == 0) {
		return $kerdes;
	}
	
	$sor=mysql_fetch_array($res);
	$kerdesszam = $sor["kerdesszam"];
	mysql_close($con);
	return $kerdesszam;
}

/*
 * Szerkeszt egy tesztet
 */
function teszt_szerkeszt() {
	global $host, $user, $pass, $db;
	$tesztId = $_POST["kivalasztott_teszt"];
	$tesztKerdes = $_POST["teszt_kerdes"];
	if(!isset($tesztKerdes)){
		$tesztKerdes=1;
	}
	$tesztKerdes = kovetkezo_kerdes($tesztId, $tesztKerdes, false);
	
	// $van_kovetkezo = kovetkezo_kerdes($tesztId, $tesztKerdes, true)==$tesztKerdes;
	$van_elozo = elozo_kerdes($tesztId, $tesztKerdes, true)!=$tesztKerdes;
	
	$con = mysql_connect($host, $user, $pass);
	if (!mysql_select_db($db, $con)) {
		echo "Nemletezo adatbazis!<br/>\n";
	}
	$sql = "SELECT * FROM kerdesek WHERE tesztkod=".$tesztId." AND kerdesszam=".$tesztKerdes;
	$res = mysql_query($sql);
	$sor=array();
 	if (mysql_num_rows($res) != 0) {
		$sor=mysql_fetch_array($res);
	}
	mysql_close($con);
	
?>
	<form name="tesztszerkeszt" action="index.php" method="post">
	<h2>Kérdés:<?php echo $tesztKerdes; ?> </h2>
	<textarea rows="10" cols="60" name="kerdes"><?php echo $sor["kerdes"]; ?></textarea>
	<h2>Válaszok:</h2>
	A. <input type="checkbox" name="helyes_a" <?php echo be_kapcsolva($sor["helyes_a"]); ?>/>
	<input type="text" name="valasz_a" value=<?php echo "\"".$sor["valasz_a"]."\""; ?> size="60"/><br/>
	B. <input type="checkbox" name="helyes_b" <?php echo be_kapcsolva($sor["helyes_b"]); ?> />
	<input type="text" name="valasz_b" value=<?php echo "\"".$sor["valasz_b"]."\""; ?> size="60"/><br/>
	C. <input type="checkbox" name="helyes_c" <?php echo be_kapcsolva($sor["helyes_c"]); ?>/>
	<input type="text" name="valasz_c" value=<?php echo "\"".$sor["valasz_c"]."\""; ?> size="60"/><br/>
	D. <input type="checkbox" name="helyes_d" <?php echo be_kapcsolva($sor["helyes_d"]); ?>/>
	<input type="text" name="valasz_d" value=<?php echo "\"".$sor["valasz_d"]."\""; ?> size="60"/><br/>
	
	<input type="hidden" name="menupont" value="kerdes-muvelet"/>
	<input type="hidden" name="teszt_kerdes" value=<?php echo "\"".$tesztKerdes."\"" ?>/>
	<input type="hidden" name="kivalasztott_teszt" value=<?php echo "\"".$tesztId."\"" ?>/>
	<br />
	<input type="submit" name="elozo" value="Elozo" <?php if(!$van_elozo) print "\"disabled\"=\"disabled\""; ?>>
	<input type="submit" name="kovetkezo" value="Következo" >
	</form>
<?php
	
}

function be_van_kapcsolva($val){
	if(isset($val)){
		return "1";
	} else {
		return "0";
	}
}

function be_kapcsolva($val){
	if($val==1){
		return "checked=\"checked\"";
	} else {
		return "";
	}
}

function teszt_ment() {
	global $host, $user, $pass, $db;
	$tesztId = $_POST["kivalasztott_teszt"];
	$tesztKerdes = $_POST["teszt_kerdes"];
	$kereso = kovetkezo_kerdes($tesztId, $tesztKerdes-1, true);
	$sql = "";
	if($kereso == $tesztKerdes-1){
		$sql = "INSERT INTO kerdesek (tesztkod,kerdesszam,kerdes,valasz_a,helyes_a,valasz_b,helyes_b,valasz_c,helyes_c,valasz_d,helyes_d) VALUES (";
		$sql.=$tesztId.",";
		$sql.=$tesztKerdes.",";
		$sql.= "'".$_POST["kerdes"]."',";
		$sql.= "'".$_POST["valasz_a"]."',";
		$sql.= be_van_kapcsolva($_POST["helyes_a"]).",";
		$sql.= "'".$_POST["valasz_b"]."',";
		$sql.= be_van_kapcsolva($_POST["helyes_b"]).",";
		$sql.= "'".$_POST["valasz_c"]."',";
		$sql.= be_van_kapcsolva($_POST["helyes_c"]).",";
		$sql.= "'".$_POST["valasz_d"]."',";
		$sql.= be_van_kapcsolva($_POST["helyes_d"]);
		$sql.= ");";
		
	} else {
		$sql = "UPDATE kerdesek SET ";
		$sql.= "kerdes='".$_POST["kerdes"]."',";
		$sql.= "valasz_a='".$_POST["valasz_a"]."',";
		$sql.= "helyes_a=".be_van_kapcsolva($_POST["helyes_a"]).",";
		$sql.= "valasz_b='".$_POST["valasz_b"]."',";
		$sql.= "helyes_b=".be_van_kapcsolva($_POST["helyes_b"]).",";
		$sql.= "valasz_c='".$_POST["valasz_c"]."',";
		$sql.= "helyes_c=".be_van_kapcsolva($_POST["helyes_c"]).",";
		$sql.= "valasz_d='".$_POST["valasz_d"]."',";
		$sql.= "helyes_d=".be_van_kapcsolva($_POST["helyes_d"]);
		$sql.= " WHERE tesztkod=".$tesztId." AND kerdesszam=".$tesztKerdes.";";
	}
	$con = mysql_connect($host, $user, $pass);
	if (!mysql_select_db($db, $con)) {
		echo "Nemletezo adatbazis!<br/>\n";
		return;
	}
	$res = mysql_query($sql);
	mysql_close($con);
	
	if(!$res){
		print "nem sikerult";
		var_dump($res);
		return false;
	}
}

function csoportok() {
	global $host, $user, $pass, $db;
	$con = mysql_connect($host, $user, $pass);
	if (!mysql_select_db($db, $con)) {
		echo "Nemletezo adatbazis!<br/>\n";
	}
	$res = mysql_query("SELECT csoportkod,csoport,leiras FROM csoportok;");
	if (mysql_num_rows($res) == 0) {
		mysql_close($con);
		return false;
	}
//	$sor = mysql_fetch_array($res);
	

	print "<table class=\"felhasznalok_tablazata\">";
	print "<tr><th>Csoportkod</th><th>Csoport</th><th>Leiras</th><th>Muvelet</th></tr>";
	while ($sor = mysql_fetch_array($res)) {
		print "<tr><td>".$sor["csoportkod"]."</td><td>".$sor["csoport"]."</td><td>".$sor["leiras"]."</td><td>";
		?>
		<form name="form<?php echo $sor["csoport"];?>modosit" action="index.php" method="post">
		<a href="javascript: form<?php echo $sor["csoport"];?>modosit.submit();" class="menu">Modosit</a>
		</form>
		<?php
		print "</td></tr>";
	}
	print "</table>";

}

function felhasznalok() {
	global $host, $user, $pass, $db;
	$con = mysql_connect($host, $user, $pass);
	if (!mysql_select_db($db, $con)) {
		echo "Nemletezo adatbazis!<br/>\n";
	}
	$res = mysql_query("SELECT nev,jelszo,vnev,knev,csoportkod,jogok,belepett FROM felhasznalok;");
	if (mysql_num_rows($res) == 0) {
		mysql_close($con);
		return false;
	}
//	$sor = mysql_fetch_array($res);
	

	print "<table class=\"felhasznalok_tablazata\">";
	print "<tr><th>Felhasznalonev</th><th>Vezeteknev</th><th>Keresztnev</th><th>Muvelet</th></tr>";
	while ($sor = mysql_fetch_array($res)) {
		print "<tr><td>".$sor["nev"]."</td><td>".$sor["vnev"]."</td><td>".$sor["knev"]."</td><td>";
		?>
		<form name="form<?php echo $sor["nev"];?>modosit" action="index.php" method="post">
		<a href="javascript: form<?php echo $sor["nev"];?>modosit.submit();" class="menu">Modosit</a>
		</form>
		<?php
		print "</td></tr>";
	}
	print "</table>";

}


?>

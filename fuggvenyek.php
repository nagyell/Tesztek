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
	$res = mysql_query("SELECT teszt.tesztkod,tesztnev FROM teszt INNER JOIN lathatotesztek ON teszt.tesztkod = lathatotesztek.tesztkod WHERE lathatotesztek.csoportkod='" . $csoportkod . "';");
 	if (mysql_num_rows($res) == 0) {
		mysql_close($con);
		return false;
	}
	?>
	<form name="elerheto_tesztek" action="index.php" method="post">
	<?php
	$sor=mysql_fetch_array($res);
	print "<input type=\"radio\" name=\"kivalasztott_teszt\" value=\"".$sor["tesztkod"]."\"/ checked>".$sor["tesztnev"]."<br />\n";
	while ($sor=mysql_fetch_array($res))
		{
		print "<input type=\"radio\" name=\"kivalasztott_teszt\" value=\"".$sor["tesztkod"]."\"/>".$sor["tesztnev"]."<br />\n";
		}
	?>
	<input type="submit" name="csoport-megosztas" value="Megold">
	<input type="hidden" name="menupont" value="teszt-megold"/>
	</form>
	<?php
	mysql_close($con);
	return true;
}

function tesztletrehozas() {
?>
		<form name="ujteszt" action="index.php" method="post">
		<table>
			<tr><td>Az új teszt neve:</td>
				<td><input name="tesztnev" type="text"></td></tr>
			<tr><td><input type="submit" value="Létrehoz"></td></tr>
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
	<input type="submit" name="teszt-atnevez" value="Átnevez">
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

function teszt_atnevez() {
	global $host, $user, $pass, $db;
	$con = mysql_connect($host, $user, $pass);
	if (!mysql_select_db($db, $con)) {
		echo "Nemletezo adatbazis!<br/>\n";
	}
	$res = mysql_query("SELECT tesztnev FROM teszt WHERE tesztkod=".$_POST["kivalasztott_teszt"].";");
	if (mysql_num_rows($res) == 0) {
		mysql_close($con);
		return false;
	}
	$sor = mysql_fetch_array($res);
	$tesztnev = $sor["tesztnev"];
	?>
	<form name="tesztatnevezes" action="index.php" method="post">
	<table>
		<tr><td><input name="nev" type="text" value="<?php echo $tesztnev; ?>"></td>
			<td><input type="submit" value="Átnevez"></td></tr>
	</table>
	<input type="hidden" name="menupont" value="teszt-muvelet"/>
	<input type="hidden" name="kivalasztott_teszt" value="<?php echo $_POST["kivalasztott_teszt"]; ?>"/>
	</form>
	<?php
	mysql_close($con);
	return true;
}

function atnevez($tesztkod,$tesztnev) {
	global $host, $user, $pass, $db;
	$con = mysql_connect($host, $user, $pass);
	if (!mysql_select_db($db, $con)) {
		echo "Nemletezo adatbazis!<br/>\n";
	}
	if (!mysql_query("UPDATE teszt SET tesztnev='".$tesztnev."' WHERE tesztkod=".$tesztkod.";")) {
		mysql_close($con);
		return false;
	}
	mysql_close($con);
	return true;
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
	print "<table class=\"felhasznalok_tablazata\">";
	print "<tr><th>Csoportkód</th><th>Csoport</th><th>Leírás</th><th>Művelet</th></tr>";
	while ($sor = mysql_fetch_array($res)) {
		print "<tr><td>".$sor["csoportkod"]."</td><td>".$sor["csoport"]."</td><td>".$sor["leiras"]."</td><td>";
		?>
		<form name="form<?php echo $sor["csoport"];?>modosit" action="index.php" method="post">
		<a href="javascript: form<?php echo $sor["csoport"];?>modosit.submit();" class="menu">Módosít</a>
		<input type="hidden" name="csoportkod" value="<?php echo $sor["csoportkod"];?>">
		<input type="hidden" name="menupont" value="csoportot-modosit">
		</form>
		<?php
		print "</td></tr>";
	}
	print "</table>";
	?>
		<table>
			<tr><td>
			<form name="ujcsoport" action="index.php" method="post">
			<a href="javascript: ujcsoport.submit();" class="menu">Új csoport létrehozása</a>
			<input type="hidden" name="menupont" value="ujcsoport"/>
			</form>
			</td></tr>
		</table>
	<?php
}

function csoportot_modosit() {
	global $host, $user, $pass, $db;
	$con = mysql_connect($host, $user, $pass);
	if (!mysql_select_db($db, $con)) {
		echo "Nemletezo adatbazis!<br/>\n";
	}
	$csoportkod = $_POST["csoportkod"];
	$res = mysql_query("SELECT csoport,leiras FROM csoportok WHERE csoportkod=".$csoportkod.";");
	if (mysql_num_rows($res) == 0) {
		mysql_close($con);
		return false;
	}
	$sor = mysql_fetch_array($res);
	$csoport = $sor["csoport"];
	$leiras = $sor["leiras"];
	?>
	<form name="csoportatnevezes" action="index.php" method="post">
	<table>
		<tr><td>Csoport</td><td><input name="csoport" type="text" value="<?php echo $csoport; ?>"></td></tr>
			<td>Leírás</td><td><input name="leiras" type="text" value="<?php echo $leiras; ?>"></td></tr>
			<td><input type="submit" value="Modosít"></td></tr>
	</table>
	<input type="hidden" name="menupont" value="csoportot-modosit"/>
	<input type="hidden" name="csoportkod" value="<?php echo $csoportkod; ?>"/>
	</form>
	<?php

	mysql_close($con);
	return true;
}

function modositsd_a_csoportot($csoportkod, $csoport,$leiras) {
	global $host, $user, $pass, $db;
	$con = mysql_connect($host, $user, $pass);
	if (!mysql_select_db($db, $con)) {
		echo "Nemletezo adatbazis!<br/>\n";
	}
	if (!mysql_query("UPDATE csoportok SET csoport='".$csoport."',leiras='".$leiras."' WHERE csoportkod=".$csoportkod.";")) {
		mysql_close($con);
		return false;
	}
	mysql_close($con);
	return true;
}

function felhasznalok() {
	global $host, $user, $pass, $db;
	$con = mysql_connect($host, $user, $pass);
	if (!mysql_select_db($db, $con)) {
		echo "Nemletezo adatbazis!<br/>\n";
	}
	$res = mysql_query("SELECT nev,jelszo,vnev,knev,csoport,jogok,belepett FROM felhasznalok,csoportok WHERE felhasznalok.csoportkod=csoportok.csoportkod;");
	if (mysql_num_rows($res) == 0) {
		mysql_close($con);
		return false;
	}
	?>
	<table>
		<tr><td>
		<form name="ujfelhasznalo" action="index.php" method="post">
		<a href="javascript: ujfelhasznalo.submit();" class="menu">Felhasználó létrehozása</a>
		<input type="hidden" name="menupont" value="ujfelhasznalo"/>
		</form>
		</td></tr>
	</table>
	<?php

	print "<table class=\"felhasznalok_tablazata\">";
	print "<tr><th>Felhasználónév</th><th>Vezetéknév</th><th>Keresztnév</th><th>Csoport</th><th>Művelet</th></tr>";
	while ($sor = mysql_fetch_array($res)) {
		print "<tr><td>".$sor["nev"]."</td><td>".$sor["vnev"]."</td><td>".$sor["knev"]."</td><td>".$sor["csoport"]."</td><td>";
		?>
		<form name="form<?php echo $sor["nev"];?>modosit" action="index.php" method="post">
		<a href="javascript: form<?php echo $sor["nev"];?>modosit.submit();" class="menu">Modosit</a>
		</form>
		<?php
		print "</td></tr>";
	}
	print "</table>";

}


function ujfelhasznalo() {
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
	$csoportok = mysql_query("select csoport, csoportkod from csoportok;");
 	if (mysql_num_rows($csoportok) == 0) {
		mysql_close($con);
		return false;
	}
	?>
	<form name="ujfelhasznalo" action="index.php" method="post">
	<table>
		<tr><td>Felhasználónév:</td>
			<td><input name="nev" type="text"></td></tr>
		<tr><td>Jelszó:</td>
			<td><input name="jelszo" type="password"></td></tr>
		<tr><td>Vezetéknév:</td>
			<td><input name="vnev" type="text"></td></tr>
		<tr><td>Keresztnév:</td>
			<td><input name="knev" type="text"></td></tr>
		<tr><td>Csoport:</td>
			<td><select size="1" name="csoportkod" >
				<?php 
					$csoport=mysql_fetch_array($csoportok);
					print "<option selected value=\"".$csoport["csoportkod"]."\">".$csoport["csoport"]."</option>";
					while ($csoport=mysql_fetch_array($csoportok))
					{
						print "<option value=\"".$csoport["csoportkod"]."\">".$csoport["csoport"]."</option>";
					}
				?>
				</selected></td></tr>
		<tr><td><input type="submit" value="Létrehoz"></td></tr>
	</table>
	<input type="hidden" name="menupont" value="ujfelhasznaloadatok"/>
	
	<?php
}

function letrehozta($nev,$jelszo,$vnev,$knev,$csoportkod) {
	global $host, $user, $pass, $db;
	$con = mysql_connect($host, $user, $pass);
	if (!mysql_select_db($db, $con)) {
		echo "Nemletezo adatbazis!<br/>\n";
	}
	$res = mysql_query("SELECT * FROM felhasznalok WHERE nev='".$nev."';");
	if (mysql_num_rows($res) != 0) {
		mysql_close($con);
		return false;
	}
	if (mysql_query("INSERT INTO felhasznalok (nev, jelszo, vnev, knev, csoportkod) VALUES (\"$nev\", \"".sha1($jelszo)."\", \"$vnev\", \"$knev\", $csoportkod);")) {
		mysql_close($con);
		return true;
	}
	else {
		mysql_close($con);
		return false;
	}
}

function ujcsoport() {
	?>
	<form name="ujcsoport" action="index.php" method="post">
	<table>
		<tr><td>Csoport neve:</td>
			<td><input name="csoport" type="text"></td></tr>
		<tr><td>Leírás a csoportról:</td>
			<td><input name="leiras" type="text"></td></tr>
		<tr><td><input type="submit" value="Létrehoz"></td></tr>
	</table>
	<input type="hidden" name="menupont" value="ujcsoportadatai"/>
	</form>
	<?php
}

function letrehozta_a_csoportot($csoport,$leiras) {
	global $host, $user, $pass, $db;
	$con = mysql_connect($host, $user, $pass);
	if (!mysql_select_db($db, $con)) {
		echo "Nemletezo adatbazis!<br/>\n";
	}
	$res = mysql_query("SELECT * FROM csoportok WHERE csoport='".$csoport."';");
	if (mysql_num_rows($res) != 0) {
		mysql_close($con);
		return false;
	}
	if (mysql_query("INSERT INTO csoportok (csoport, leiras) VALUES ('".$csoport."','".$leiras."');")) {
		mysql_close($con);
		return true;
	}
	else {
		mysql_close($con);
		return false;
	}
}

function teszt_megjelenites(){
	global $host, $user, $pass, $db;
	$tesztId = $_POST["kivalasztott_teszt"];
	$tesztKerdes = $_POST["teszt_kerdes"];
	if(!isset($tesztKerdes)){
		$tesztKerdes=-1;
	}
	$tesztKerdes = kovetkezo_kerdes($tesztId, $tesztKerdes, false);
	
	if($tesztKerdes==-1){
		print("Ez a teszt nem tartalmaz kérdést.<br>");
		return;
	}
	
	$van_kovetkezo = kovetkezo_kerdes($tesztId, $tesztKerdes, true)!=$tesztKerdes;
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
	<?php echo $sor["kerdes"]; ?>
	<h2>Válaszok:</h2>
	A. <input type="checkbox" name="helyes_a"/>
	<?php echo $sor["valasz_a"]; ?> <br/>
	B. <input type="checkbox" name="helyes_b"/>
	<?php echo $sor["valasz_b"]; ?> <br/>
	C. <input type="checkbox" name="helyes_c"/>
	<?php echo $sor["valasz_c"]; ?> <br/>
	D. <input type="checkbox" name="helyes_d"/>
	<?php echo $sor["valasz_d"]; ?> <br/>
	
	<input type="hidden" name="menupont" value="teszt-megold"/>
	<input type="hidden" name="teszt_kerdes" value=<?php echo "\"".$tesztKerdes."\"" ?>/>
	<input type="hidden" name="kivalasztott_teszt" value=<?php echo "\"".$tesztId."\"" ?>/>
	<br />
	<input type="submit" name="elozo" value="Előző" <?php if(!$van_elozo) print "\"disabled\"=\"disabled\""; ?>>
	<?php if($van_kovetkezo==true) {	?>
		<input type="submit" name="kovetkezo" value="Következő" >
	<?php } else {	?>
		<input type="submit" name="vege" value="Vége" >
	<?php } ?>
	
	</form>
<?php
}

function van_valasz(){
	$sql = "SELECT * FROM megoldasok WHERE ";
	$sql .= "nev='".$_SESSION['nev']."' AND ";
	$sql .= "tesztkod=".$_POST["kivalasztott_teszt"]." AND ";
	$sql .= "kerdesszam=".$_POST["teszt_kerdes"]." ";
	
	$res = mysql_query($sql);
 	return mysql_num_rows($res)!= 0;
}
function helyese( $tesztId, $tesztKerdes){
	$sql = "SELECT helyes_a,helyes_b,helyes_c,helyes_d FROM kerdesek WHERE tesztkod=".$tesztId." AND kerdesszam=".$tesztKerdes;
	$res = mysql_query($sql);
 	if (mysql_num_rows($res) != 0) {
		$sor=mysql_fetch_array($res);
		if(
		$sor["helyes_a"]==be_van_kapcsolva($_POST["helyes_a"]) &&
		$sor["helyes_b"]==be_van_kapcsolva($_POST["helyes_b"]) &&
		$sor["helyes_c"]==be_van_kapcsolva($_POST["helyes_c"]) &&
		$sor["helyes_d"]==be_van_kapcsolva($_POST["helyes_d"])
		){
			return 1;
		} else {
			return 0;
		}
	} else {
		return 0;
	}

}

function valaszt_ment() {
	global $host, $user, $pass, $db;
	$tesztId = $_POST["kivalasztott_teszt"];
	$tesztKerdes = $_POST["teszt_kerdes"];
	
	$con = mysql_connect($host, $user, $pass);
	if (!mysql_select_db($db, $con)) {
		echo "Nemletezo adatbazis!<br/>\n";
		return;
	}
	
	$helyes = helyese($tesztId,$tesztKerdes);
	
	$sql = "";
	if(!van_valasz()){
		$sql = "INSERT INTO megoldasok (nev,tesztkod,kerdesszam,valasztott_a,valasztott_b,valasztott_c,valasztott_d,helyes) VALUES (";
		$sql.= "'".$_SESSION["nev"]."', ";
		$sql.=$tesztId.", ";
		$sql.=$tesztKerdes.", ";
		$sql.= be_van_kapcsolva($_POST["helyes_a"]).", ";
		$sql.= be_van_kapcsolva($_POST["helyes_b"]).", ";
		$sql.= be_van_kapcsolva($_POST["helyes_c"]).", ";
		$sql.= be_van_kapcsolva($_POST["helyes_d"]).", ";
		$sql.= $helyes;
		$sql.= ");";
		
	} else {
		$sql = "UPDATE megoldasok SET ";
		$sql.= "valasztott_a=".be_van_kapcsolva($_POST["helyes_a"]).",";
		$sql.= "valasztott_b=".be_van_kapcsolva($_POST["helyes_b"]).",";
		$sql.= "valasztott_c=".be_van_kapcsolva($_POST["helyes_c"]).",";
		$sql.= "valasztott_d=".be_van_kapcsolva($_POST["helyes_d"]).",";
		$sql.= "helyes=".$helyes;
		$sql.= " WHERE tesztkod=".$tesztId." AND kerdesszam=".$tesztKerdes." AND nev='".$_SESSION["nev"]."'";
	}
	
	$res = mysql_query($sql);
	mysql_close($con);

	if(!$res){
		print "nem sikerult";
		var_dump($res);
		return false;
	}
}

function teszt_eredmeny(){
	global $host, $user, $pass, $db;
	$con = mysql_connect($host, $user, $pass);
	if (!mysql_select_db($db, $con)) {
		echo "Nemletezo adatbazis!<br/>\n";
		return;
	}
	
	$tesztId = $_POST["kivalasztott_teszt"];
	$tesztKerdes = $_POST["teszt_kerdes"];

	$sql = "SELECT count(*) AS db FROM megoldasok WHERE tesztkod=".$tesztId." AND nev='".$_SESSION["nev"]."';";
	$res = mysql_query($sql);
	$sor = mysql_fetch_array($res);
	$osszdb = $sor[db];
	print "<h2>".$osszdb." válaszból ";
	$sql = "SELECT helyes,count(*) AS db FROM megoldasok WHERE tesztkod=".$tesztId." AND nev='".$_SESSION["nev"]."' GROUP BY helyes";
	$res = mysql_query($sql);
//	print "<h2>Eredmény:</h2>";
//	print "<h3>";
	while ($sor=mysql_fetch_array($res)){
		if($sor["helyes"]==0) {
//			print "Helytelen:";
//		print $sor["db"]." volt helytelen és ";
		}
		else {
//			print "Helyes:";
		print $sor["db"]." volt helyes.</h2>";
		}
	}
	mysql_close($con);
}

?>

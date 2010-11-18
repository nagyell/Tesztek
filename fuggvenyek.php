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
 * Szerkeszt egy tesztet
 */
function teszt_szerkeszt() {
	$tesztId = $_POST["kivalasztott_teszt"];
	$tesztKerdes = $_POST["teszt_kerdes"];
	if(!isset($tesztKerdes)){
		$tesztKerdes=0;
	}
	print $tesztId . "kerdes:". $tesztKerdes;
?>
		<form name="tesztszerkeszt" action="index.php" method="post">
		<h2>Kérdés:</h2>
		<textarea rows="10" cols="60"></textarea>
		<h2>Válaszok:</h2>
		<input type="checkbox" name="helyes1" value=""/>
		<input type="text" name="kerdes1" value="" size="60"/><br/>
		<input type="checkbox" name="helyes2" value=""/>
		<input type="text" name="kerdes2" value="" size="60"/><br/>
		<input type="checkbox" name="helyes3" value=""/>
		<input type="text" name="kerdes3" value="" size="60"/><br/>
		<input type="checkbox" name="helyes4" value=""/>
		<input type="text" name="kerdes4" value="" size="60"/><br/>
		
		<input type="hidden" name="menupont" value="tesztSzerkeszt"/>
		<input type="hidden" name="tesztKerdes" value=<?php echo "\"".$tesztKerdes."\"" ?>/>
		<input type="button" value="Következo">
		</form>
<?php
	
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
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
	$res = mysql_query("select nev, jogok, vnev, knev, csoport from felhasznalok where nev='" . $nev . "' and jelszo='" . sha1($jelszo) . "';");
 	if (mysql_num_rows($res) == 0) {
		mysql_close($con);
		return false;
	}
	$sor=mysql_fetch_array($res);
	$query = "update felhasznalok set belepett='1' where nev='" . $nev . "';";
	$_SESSION['nev']=$_POST["nev"];
	$_SESSION['jogok']=$_POST["jogok"];
	$_SESSION["vnev"]=$sor["vnev"];
	$_SESSION["knev"]=$sor["knev"];
	$_SESSION["csoport"]=$sor["csoport"];
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

function elerhetotesztek($csoport) {
	global $host, $user, $pass, $db;
	$con = mysql_connect($host, $user, $pass);
	if (!mysql_select_db($db, $con)) {
		echo "Nemletezo adatbazis!<br/>\n";
	}
	$res = mysql_query("select tesztnev from teszt inner join lathatotesztek on teszt.tesztkod = lathatotesztek.tesztkod where lathatotesztek.csoport='" . $csoport . "';");
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
	$res = mysql_query("select tesztnev from teszt;");
 	if (mysql_num_rows($res) == 0) {
		mysql_close($con);
		return false;
	}
	print "Az összes teszt:\n";
	while ($sor=mysql_fetch_array($res))
		{
			print $sor["tesztnev"].<br>"\n";
		}
	mysql_close($con);
}

?>
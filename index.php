<?php
session_start();

include "connect.php";
include "fuggvenyek.php";

if($_POST["menupont"]=="teszt-muvelet"){
	if(isset($_POST["teszt-szerkeszt"]))
		$_POST["menupont"]="teszt-szerkeszt";
	if(isset($_POST["csoport-megosztas"]))
		$_POST["menupont"]="csoport-megosztas";
}

if($_POST["menupont"]=="kerdes-muvelet"){
	if(isset($_POST["kovetkezo"]))
		$_POST["menupont"]="kerdes-szerkeszt-kovetkezo";
	if(isset($_POST["elozo"]))
		$_POST["menupont"]="kerdes-szerkeszt-elozo";

}

if (isset($_POST["lathatosagot-torol"]))
{
	echo "Hatha!";
	lathato_tesztet_torol($_POST["kivalasztott_csoportkod"],$_POST["kivalasztott_teszt"]);
}

switch ($_POST["menupont"]) {
	case "be_adatok":
		if (belep($_POST["nev"], $_POST["jelszo"])) {
			//echo "Sikeres belepes!";
		}	
		else {
			//echo "Nem sikerult belepni!";
		}
	break;
	case "ki":
		kilep($_SESSION["nev"]);
	break;
	case "teszt-szerkeszt":
	//	print($_POST["kivalasztott_teszt"]);
	break;
	case "csoport-megosztas":
		megoszt($_POST[kivalasztott_csoport],$_POST[kivalasztott_teszt]);
	break;
	case "kerdes-szerkeszt-kovetkezo":
		teszt_ment();
		$kov = kovetkezo_kerdes($_POST["kivalasztott_teszt"], $_POST["teszt_kerdes"], true);
		if($kov==$_POST["teszt_kerdes"]) $kov++;
		$_POST["teszt_kerdes"] = $kov;
		break;
	case "kerdes-szerkeszt-elozo":
		teszt_ment();
		$_POST["teszt_kerdes"] = elozo_kerdes($_POST["kivalasztott_teszt"], $_POST["teszt_kerdes"], true);
		break;
}

?>
<html>
<head>
<title>Tesztek</title>
<link href="stilus.css" rel="stylesheet" type="text/css"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>

<body>

<div id="fejlec">
	<div id="cim">Oldjunk teszteket <?php if (isset($_SESSION["nev"])) { echo $_SESSION["vnev"], " ", $_SESSION["knev"]; } ?>!
	</div>
</div>


<div id="menu_es_tartalom">

	<div id="baloldalimenu">
	<table>
		<?php
			if (isset($_SESSION["nev"])) {
		?>
				<form name="tesztek" action="index.php" method="post">
					<tr><td><a href="javascript: tesztek.submit();" class="menu">Tesztek</a></td></tr>
					<input type="hidden" name="menupont" value="tesztek"/>
				</form>
			<?php	
				if ($_SESSION["jogok"]<=2) {
			?>
					<form name="tesztletrehozas" action="index.php" method="post">
						<tr><td><a href="javascript: tesztletrehozas.submit();" class="menu">Létrehozás</a></td></tr>
						<input type="hidden" name="menupont" value="tesztletrehozas"/>
					</form>
					<form name="osszesteszt" action="index.php" method="post">
						<tr><td><a href="javascript: osszesteszt.submit();" class="menu">Minden teszt</a></td></tr>
						<input type="hidden" name="menupont" value="osszesteszt"/>
					</form>
					<form name="megosztasok" action="index.php" method="post">
						<tr><td><a href="javascript: megosztasok.submit();" class="menu">Megosztott tesztek</a></td></tr>
						<input type="hidden" name="menupont" value="megosztasok"/>
					</form>
			<?php
				}
				if ($_SESSION["jogok"]<=1) {
			?>
					<form name="csoportok" action="index.php" method="post">
						<tr><td><a href="javascript: csoportok.submit();" class="menu">Csoportok</a></td></tr>
						<input type="hidden" name="menupont" value="csoportok"/>
					</form>
					<form name="felhasznalok" action="index.php" method="post">
						<tr><td><a href="javascript: felhasznalok.submit();" class="menu">Felhasznalok</a></td></tr>
						<input type="hidden" name="menupont" value="felhasznalok"/>
					</form>
			<?php
				}
			?>
				<form name="ki" action="index.php" method="post">
					<tr><td><a href="javascript: ki.submit();" class="menu">Kilepes</a></td></tr>
					<input type="hidden" name="menupont" value="ki"/>
				</form>
		<?php
			}
			else {
		?>
				<form name="be" action="index.php" method="post">
					<tr><td><a href="javascript: be.submit();" class="menu">Belepes</a></td></tr>
					<input type="hidden" name="menupont" value="be"/>
				</form>
		<?php
			}
		?>
	</table>
	</div>

	<div id="tartalom">
		<?php
			if (isset($_SESSION["nev"])) {
				switch ($_POST["menupont"]) {
					case "ki":
					?>
					<form name="kilap" action="index.php" method="post">
					<table>
						<tr><td>Viszlat!!!!</td>
					</table>
					<input type="hidden" name="menupont" value="ki_adatok"/>
					<?php
					break;
					case "ki_adatok":
					break;
					case "tesztek":
						elerhetotesztek($_SESSION["csoportkod"]);
					break;
					case "tesztletrehozas":
						tesztletrehozas();
					break;
					case "tesztnevellenorzes":
						tesztnevellenorzes();
					break;
					case "osszesteszt":
						osszesteszt();
					break;
					case "teszt-szerkeszt":
						teszt_szerkeszt();
					break;
					case "kerdes-szerkeszt-kovetkezo":
					case "kerdes-szerkeszt-elozo":
						teszt_szerkeszt();
					break;
					case "megosztasok":
						if (isset($_POST["kivalasztott_csoport"]))
						{
							megosztasok($_POST["kivalasztott_csoport"]);
							tesztjei($_POST["kivalasztott_csoport"]);
						}
						else
						{
							megosztasok(csoportom());
							tesztjei(csoportom());
						}
					break;
					case "csoportok":
						csoportok();
					break;
					case "felhasznalok":
						felhasznalok();
					break;
				}

			?>
			Szia!
		<?php
			}
			else {
				switch ($_POST["menupont"]) {
					case "be":
					?>
					<form name="belap" action="index.php" method="post">
					<table>
						<tr><td>Név:</td>
							<td><input name="nev" type="text"></td></tr>
						<tr><td>Jelszó:</td>
							<td><input name="jelszo" type="password"></td></tr>
						<tr><td><input type="submit" value="OK"></td></tr>
					</table>
					<input type="hidden" name="menupont" value="be_adatok"/>
					<?php
					break;
 					case "be_adatok":
						echo "Nem sikerult belepni!";
					break;
					case "ki":
						echo "Viszlat!";
/*					?>
					<form name="kilap" action="index.php" method="post">
					<table>
						<tr><td>Viszlat!</td>
					</table>
					<input type="hidden" name="menupont" value="ki_adatok"/>
					<?php)*/
					break;
					case "ki_adatok":
						echo $_POST["nev"], "<br/>\n", $_POST["jelszo"];
					break;
				}

		?>
			Nem szia!
		<?php
			}
		?>
	</div>

</div>

</body>
</html>

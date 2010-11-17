<?php
session_start();

include "connect.php";
include "fuggvenyek.php";

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
	// print($_POST["kivalasztott_teszt"]);
	break;
	case "csoport-megosztas":
	//	print($_POST["kivalasztott_teszt"]);
	//	print($_POST["kivalasztott_csoport"]);
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
						elerhetotesztek($_SESSION["csoport"]);
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
						echo "teszt-szerkeszt";
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

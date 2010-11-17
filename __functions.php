<?php


//A lap tetje
function get_header() {	//az oldal fejleceben megjeleno kepek illetve a bejelenkezes, regisztralas, kilepes gombok
?>
<html>
<head>
<title>Informatika továbbképző (Adatbázisok és webprogramozás), Kézdivásárhely 2o1o</title>
<link href="./styles/styles.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<!-- jobb click letiltasa biztonsagi okobol es a tartalmi resz meretet beallito fuggveny -->
<script type="text/javascript">
/*function disableRightClick(e) {
	var message = "Nana!!!\nAmit szabad másnak, azt nem szabad... neked!";
	if(!document.rightClickDisabled) {	// initialize
		if(document.layers) {
			document.captureEvents(Event.MOUSEDOWN);
			document.onmousedown = disableRightClick;
		}
		else 
			document.oncontextmenu = disableRightClick;
		return document.rightClickDisabled = true;
	}
	if(document.layers || (document.getElementById && !document.all)) {
		if (e.which==2||e.which==3) {
			alert(message);
			return false;
		}
	}
	else {
		alert(message);
		return false;
	}
}

disableRightClick();*/

function set_hight() {	//a tartalmi resz meretenek beallitasa a bongeszo meretenek fuggvenyben
	//az oldal betoltesekor vagy atmeretezesekor hivodik meg, lasd a BODY nyito taget
	document.getElementById("content_menu").style.height = window.innerHeight - 230+"px";
	document.getElementById("content_content").style.height = window.innerHeight - 230+"px";
}
</script>

</head>
<body onLoad="javasript: set_hight();" onResize="javasript: set_hight();">
<div id="main">
	<div id="header">	
		<div id="logo">	<!-------------------------------------- FEJLEC a kepekkel ------------------------------------->
			<div id="logo1">
				<img src="img/logo1.jpg" alt="Gabor Aron" />
			</div>
			<div id="logo2">
				<img src="img/logo2.jpg" alt="Logo" />
			</div>
		</div><!-- logo -->
		<div id="title"><!--------------------- FEJLEC a cimmel es bejelentkezo es regisztralo gombbal ----------------->
			<div id="title1">
			Informatika továbbképző, Kézdivásárhely 2010.
			</div>
			<div id="title2">
<?php		//Ha nincs bejelentkezve, akkor jelenjen meg a belepes es regisztralas gomb
			if (!isset($_SESSION["nev"])) {
?>
				<table class="simple_right_table">
					<tr>
					<form name="adat1" action="index.php" method="POST">
					<td><a href='javascript:adat1.submit();' class="uppermenu">Belépés</a></td>
					<input type="hidden" name="menu" value="be" />
					</form>
					<form name="adat2" action="index.php" method="POST">
					<td><a href='javascript:adat2.submit();' class="uppermenu">Regisztrálás</a></td>
					<input type="hidden" name="menu" value="reg" />
					</form>
					</tr>
				</table>
<?php
			}
			else {	//ha be van jelenkezve, akkor egy udvozlo szoveg jelenik meg
?>
				<table class="simple_right_table">
					<tr><td style="color: red; font-style: italic">
					<?php echo $_SESSION["nev"]; ?>
					</td><form name="adat3" action="index.php" method="POST">
					<td><a href='javascript:adat3.submit();' class="uppermenu">Kilépés</a></td>
					<input type="hidden" name="menu" value="ki" />
					</form>
					</tr>
				</table>
<?php
			}
?>
			</div><!-- title2 -->
		</div><!-- title -->
	</div><!-- header -->
<?php
	return 0;
}	//get_header() VEGE


//A bal oldali menu
function get_menu($bejelentkezve) {	//a bal oldali menut megjelenito fuggveny
	if (!$bejelentkezve) {	//ha nincs bejelentkezve
?>
		<table>
			<form name="menu1" action="index.php" method="POST">
				<tr><td><a href='javascript:menu1.submit();' class="menu">Főoldal</a></tr></td>
			</form>
			<form name="menu2" action="index.php" method="POST">
				<tr><td><a href='javascript:menu2.submit();' class="menu">Belépés</a></tr></td>
				<input type="hidden" name="menu" value="be" />
			</form>
			<form id="beallitasok" name="menu3" action="index.php" method="POST">
				<tr><td><a href='javascript:menu3.submit();' class="menu">Regisztrálás</a></tr></td>
				<input type="hidden" name="menu" value="reg" />
			</form>
			<form name="menu10" action="index.php" method="POST">
				<tr><td><a href='javascript:menu10.submit();' class="menu">Az oldalról</a></tr></td>
				<input type="hidden" name="menu" value="weboldalrol" />
			</form>
		</table>
<?php
	}
	else {	//ha be van jelentkezve
?>
		<table>
<?php
			if ($_SESSION["jogok"] < 2) {	//a bejelentkezett szemely adminisztratori jogokkal rendelkezik
?>
				<form name="menu0" action="index.php" method="POST">
					<tr><td><a href='javascript:menu0.submit();' class="menu"><font color="darkred">ADMIN</font></a></tr></td>
					<input type="hidden" name="menu" value="menu0" />
				</form>
<?php
			}
?>
			<form name="menu1" action="index.php" method="POST">
				<tr><td><a href='javascript:menu1.submit();' class="menu">Főoldal</a></tr></td>
			</form>
			<form name="menu2" action="index.php" method="POST">
				<tr><td><a href='javascript:menu2.submit();' class="menu">Kilépés</a></tr></td>
				<input type="hidden" name="menu" value="ki" />
			</form>
			<form name="menu3" action="index.php" method="POST">
				<tr><td><a href='javascript:menu3.submit();' class="menu">Beállítások</a></tr></td>
				<input type="hidden" name="menu" value="beallitasok" />
			</form>
			<form name="menu4" action="index.php" method="POST">
				<tr><td><a href='javascript:menu4.submit();' class="menu">Felhasználók</a></tr></td>
				<input type="hidden" name="menu" value="felhasznalok" />
			</form>
			<form name="menu5" action="index.php" method="POST">
				<tr><td><a href='javascript:menu5.submit();' class="menu">Képek</a></tr></td>
				<input type="hidden" name="menu" value="kepek" />
			</form>
			<form name="menu6" action="index.php" method="POST">
				<tr><td><a href='javascript:menu6.submit();' class="menu">Linkek</a></tr></td>
				<input type="hidden" name="menu" value="linkek" />
			</form>
			<form name="menu7" action="index.php" method="POST">
				<tr><td><a href='javascript:menu7.submit();' class="menu">Fórum</a></tr></td>
				<input type="hidden" name="menu" value="forum" />
			</form>
			<form name="menu10" action="index.php" method="POST">
				<tr><td><a href='javascript:menu10.submit();' class="menu">Az oldalról</a></tr></td>
				<input type="hidden" name="menu" value="weboldalrol" />
			</form>
		</table>
<?php
	}
}	//get_menu() VEGE


//A tartalmi resz megjelenitese
function get_content() {
?>
<div id="content">	<!--------------------------------------- TARTALOM ---------------------------------------->
	<div id="content_menu">
<?php
		get_menu(isset($_SESSION["nev"]));	//megjeleniti a menut
?>
	</div>	<!-- content_menu -->
	<div id="content_content" style="overflow: auto">
<?php
		if (!isset($_SESSION["nev"])) {	//ha nincs bejelentkezve---------------------------------------------
			if (!isset($_POST["menu"])) {	//ha nem kattintott egyetlen gombra sem
?>
				<p />&nbsp;<p />&nbsp;
				<p />A <a href="http://www.ubbcluj.ro/" target="_blank">BBTE</a> és 
				<a href="http://www.kmei.ro/" target="_blank">KMEI</a> által szervezett 2009/2010-es tanévi<br />
				<i>"Adatbázisok és webes alkalmazások fejlesztése"</i><br />továbbképző weboldala
				<p />Ha te is részt vettél a képzésen,<br />kérlek regisztrálj ezen az oldalon!
<!--				<p />&nbsp;<p />&nbsp;<p />&nbsp;<p />&nbsp;<p />&nbsp;<p />&nbsp;<p />&nbsp;<p />&nbsp;
				<p />Ha te is részt vettél a képzésen,<br />kérlek regisztrálj ezen az oldalon!-->
<?php
			}
			else {	//ha rakattintott valamelyik gombra
				switch ($_POST["menu"]) {
					case "be":
						//Bejelentkezést/kijelentkezést megvalósító függvények
						include "loginout_db.php";
						include "loginout.php";
						$hiba = 0;
						$userneve = "";
						if (isset($_POST["nev"]) || isset($_POST["jelszo"]))
							$hiba = db_login_check($_POST["nev"], $_POST["jelszo"], $userneve);
						get_login($hiba, $userneve);
						break;
					case "reg":
						//Regisztrálást megvalósító függvények
						include "register_db.php";
						include "register.php";
						$hiba = 0;
						if (isset($_POST["nev"]) || isset($_POST["jelszo"]) || isset($_POST["jelszo2"]) || 
							isset($_POST["email"]) || isset($_POST["email2"]) || isset($_POST["vnev"]) || 
							isset($_POST["knev"]))
							$hiba = db_register_check($_POST);
						get_register($hiba, $_POST);
						break;
					case "weboldalrol":
						get_page_info();
						break;
				}
			}
		}
		else {	//ha be van jelentkezve----------------------------------------------------------------------
			if (!isset($_POST["menu"])) {	//ha nem kattintott egyetlen gombra sem
?>
				<p />&nbsp;<p />&nbsp;<p />&nbsp;
				<p />A <a href="http://www.ubbcluj.ro/" target="_blank">BBTE</a> és 
				<a href="http://www.kmei.ro/" target="_blank">KMEI</a> által szervezett 2009/2010-es tanévi<br />
				<i>"Adatbázisok és webes alkalmazások fejlesztése"</i><br />továbbképző weboldala
<?php
			}
			else {	//ha rakattintott valamelyik gombra
				switch ($_POST["menu"]) {
					case "menu0":	//az admin menu megjelenitese
						//Adminsztracio fuggvenyek
						include "admin_db.php";
						include "admin.php";
						get_admin_menu();
						break;
					case "menu01":	//admin szamara listazodnak a felhasznalok
						//Adminsztracio fuggvenyek
						include "admin_db.php";
						include "admin.php";
						get_admin_menu();
						get_admin_users();
						break;
					case "menu011":	//admin modositja egy user adatait
						//Adminsztracio fuggvenyek
						include "admin_db.php";
						include "admin.php";
						get_admin_menu();
						$hiba = 0;
						if (isset($_POST["user"]))
							$hiba = db_admin_user_modify($_POST);
						get_admin_user_modify($hiba, $_POST["nev"]);
						break;
					case "menu012":	//admin veglegesen torli az adatbazisbol a usert
						//Adminsztracio fuggvenyek
						include "admin_db.php";
						include "admin.php";
						get_admin_menu();
						$hiba = 0;
						if (isset($_POST["user"]))
							$hiba = db_admin_user_delete($_POST);
						get_admin_user_delete($hiba, $_POST["nev"]);
						break;
					case "menu013":	//admin aktivalja a usert
						//Adminsztracio fuggvenyek
						include "admin_db.php";
						include "admin.php";
						get_admin_menu();
						$hiba = 0;
						if (isset($_POST["user"]))
							$hiba = db_admin_user_activate($_POST);
						get_admin_user_activate($hiba, $_POST["nev"]);
						break;
					case "menu02":	//admin modositja a linkgyujtemenyt
						//Linkgyüjteményre vonatkozó függvények
						include "links_db.php";
						include "links.php";
						//Adminsztracio fuggvenyek
						include "admin_db.php";
						include "admin.php";
						get_admin_menu();
						$hiba = 0;
						if (isset($_POST["katid"]))	{	//mar ki van valasztva a modositando linkkategoria
							if (isset($_POST["nev"]))	//kesz a modositas
								$hiba = db_admin_modify_cathegory($_POST);
							get_admin_modify_cathegory($hiba, $_POST["katid"]);
						}
						else
							if (isset($_POST["linkid"])) {	//mar ki van valasztva a modositando link
								if (isset($_POST["ref"]))	//kesz a modositas
									$hiba = db_admin_modify_link($_POST);
								get_admin_modify_link($hiba, $_POST["linkid"]);
							}
							else
								get_admin_links();
						break;
					case "menu03":	//admin modositja a forum tartalmat
						//A forumra vonatkozó függvények
						include "forum_db.php";
						include "forum.php";
						//Adminsztracio fuggvenyek
						include "admin_db.php";
						include "admin.php";
						get_admin_menu();
						$hiba = 0;
						if (isset($_POST["id"]))	//ki van valasztva egy topik, az abba tartozo uzeneteket listazni
							$id = $_POST["id"];
						else	//nincs kivalasztva topik, a topikokat kell listazni
							$id = 0;
						if (isset($_POST["oldal"]))
							$oldal = $_POST["oldal"];
						else
							$oldal = 1;
						if (isset($_POST["messageid"])) {	//mar ki van valasztva a modositando hozzaszolas
							if (isset($_POST["operation"]))	//kesz a modositas
								$hiba = db_admin_modify_message($_POST);
							get_admin_modify_message($hiba, $_POST["messageid"], $_POST["topicid"], $_POST["oldal"]);
						}
						else	
							if (isset($_POST["topicid"]))	{	//mar ki van valasztva a modositando topik
								if (isset($_POST["operation"]))	//kesz a modositas
									$hiba = db_admin_modify_topic($_POST);
								get_admin_modify_topic($hiba, $_POST["topicid"]);
							}
							else
								get_admin_forum($id, $oldal);	//h id=0, akkor a topikokat listazza, kulonben egy topikon belul az uzenetekt
						break;
					case "ki":	//kijelentkezesre kattintottak
						//Bejelentkezést/kijelentkezést megvalósító függvények
						include "loginout_db.php";
						include "loginout.php";
						logout();
						break;
					case "beallitasok":	//beallitasokra kattintott a felhasznalo
						//Beállítások, opciók módosításához szükséges függvények
						include "settings_db.php";
						include "settings.php";
						$hiba = 0;
						if (isset($_POST["nev"]) || isset($_POST["jelszo"]) || isset($_POST["jelszo2"]) || 
							isset($_POST["email"]) || isset($_POST["email2"]) || isset($_POST["vnev"]) || 
							isset($_POST["knev"]) || isset($_POST["tel"]) || isset($_POST["cim"]))
							$hiba = db_settings_check($_POST);
						get_settings($hiba, $_POST);
						break;
					case "felhasznalok":	//a felhasznalokat akarja megtekinteni
						get_all_users();
						break;
					case "kepek":	//a "Kepek" menupont ill. a kepgaleriak eseten mindig ugyanaz tortenik
					case "kepek1":
					case "kepek2":
					case "kepek3":
						//Képgalériák megjelenítéséhez a függvények
						include "pixgals.php";
						get_picture_galerys();
						break;
					case "linkek":	//linkek megtekintese 
						//Linkgyüjteményre vonatkozó függvények
						include "links_db.php";
						include "links.php";
						get_links();
						break;
					case "ujkategoria":	//uj linkkategoriat akar valaki letrehozni vagy modositani
						//Linkgyüjteményre vonatkozó függvények
						include "links_db.php";
						include "links.php";
						$hiba = 0;
						if (isset($_POST["katnev"])) {	//ha mar be voltak irva az uj linkkategoria adatai
							$hiba = db_add_new_cathegory($_POST["katnev"]);
							if ($hiba)
								get_new_cathegory($hiba);
						}
						else
							if (isset($_POST["id"])) {	//ha mar beirta a modositasokat
								$hiba = db_modify_cathegory($_POST);
								if ($hiba)
									get_modify_cathegory($hiba, $_POST["id"]);
							}
							else
								if (isset($_POST["linkkat"]))	//ha modositani akarja a linkkategoriat, jon a form
									get_modify_cathegory($hiba, $_POST["linkkat"]);
								else	//kulonben jelenjen meg az uj linkkategoria adatai szamara a form
									get_new_cathegory($hiba);
						break;
					case "ujlink":	//uj linket akar valaki ajanlani vagy modositani
						//Linkgyüjteményre vonatkozó függvények
						include "links_db.php";
						include "links.php";
						$hiba = 0;
						if (isset($_POST["kat"])) {	//ha mar be voltak irva az uj link adatai
							$hiba = db_add_new_link($_POST);
							if ($hiba)
								get_new_link($hiba, $_POST["kat"]);
						}
						else
							if (isset($_POST["linkid"]))	//ha mar beirta a modositasokat
								db_modify_link($_POST);
							else
								if (isset($_POST["id"]))	//ha modositani akarja a linket, jon a form
									get_modify_link($_POST["id"]);
								else	//kulonben jelenjen meg az uj link adatai szamara a form
									get_new_link($hiba, $_POST["linkkat"]);
						break;
					case "forum":	//forum topikjainak megtekinteses es szrkesztese
						//A forumra vonatkozó függvények
						include "forum_db.php";
						include "forum.php";
						if (isset($_POST["topikid"]))
							$id = $_POST["topikid"];	//ekkor egy topikbeli hozzaszolasok jelennek meg
						else
							$id = 0;	//ekkor a topikok jelennek meg
						if (isset($_POST["oldal"]))
							$oldal = $_POST["oldal"];
						else
							$oldal = 1;
						get_forum($id, $oldal);
						break;
					case "ujtopik":
						//A forumra vonatkozó függvények
						include "forum_db.php";
						include "forum.php";
						$hiba = 0;
						if (isset($_POST["nev"]))	//uj topik neve mar beirva
							$hiba = topic_check($_POST["nev"]);
						else
							if (isset($_POST["id"]) && isset($_POST["ujnev"]))	//mar beirta a modositasokat
								$hiba = db_modify_topic($_POST["id"], $_POST["ujnev"], $_POST["oldal"]);
						if (!isset($_POST["id"])) 
							get_new_topic($hiba);
						else
							get_modify_topic($hiba, $_POST["id"], $_POST["oldal"]);
						break;
					case "ujuzenet":
						//A forumra vonatkozó függvények
						include "forum_db.php";
						include "forum.php";
						$hiba = 0;
						if (isset($_POST["uzenet"]))
							$hiba = message_check($_POST["uzenet"], $_POST["idezet"]);
						if (isset($_POST["id"]) && isset($_POST["ujuzenet"]))
							$hiba = db_modify_message($_POST["topicid"], $_POST["id"], $_POST["ujuzenet"], $_POST["oldal"]);
						if (!isset($_POST["id"])) 
							get_new_message($hiba, $_POST["oldal"]);
						else
							get_modify_message($hiba, $_POST["topicid"], $_POST["id"], $_POST["mesg"], $_POST["oldal"]);
						break;
					case "weboldalrol":
						get_page_info();
						break;
				}
			}
		}
?>
	</div>	<!-- content_content -->
</div>	<!-- content -->
<?php
}	//get_content() VEGE


//Az lap alja
function get_footer() {
?>
	<div id="footer">		<!---------------------------------------- LABLEC ----------------------------------------->
		dizájn, tervezés és programozás<br><a href="http://dihunor.co.cc" target="_blank">dihunor</a> &copy; 2010
	</div>
</div><!-- main -->
</body>
</html>
<?php
	return 0;
}	//get_footer() VEGE

?>
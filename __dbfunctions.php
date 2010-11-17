<?php


function db_kapcsolodas() {	//db_kapcsolodas az adatbazishoz
	global $host, $user, $pass, $db;
	$link = mysql_connect($host, $user, $pass);
	if ($link) {
		$selected = mysql_select_db($db, $link);
		if ($selected) {
			mysql_query("SET NAMES 'utf8'", $link);  //Mezőnevek definíciója
			mysql_query("SET CHARACTER SET 'utf8'", $link);  //Rekord tartalom karakterkészlete
			mysql_query("SET COLLATION_CONNECTION='utf8_general_ci'", $link);  //Az adatbázis kapcsolat egyeztetése
		}
		else
			return false;
	}
	return $link;
}	//db_kapcsolodas() VEGE


function db_levallas($link) {	//az adatbazissal letrehozott kapcsolat megszuntetese
	if ($link) {
		mysql_close($link);
		return true;
	}
	else
		return false;
}	//db_levallas() VEGE

?>
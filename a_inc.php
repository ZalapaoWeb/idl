<?php
session_start();
require_once("a_config.php"); 
require_once("a_conn.php");
require_once("a_function.php");
require_once("a_function_tak.php");
/*
#----------- CHECK BOT
if (!empty($_SERVER['HTTP_USER_AGENT']) and (false !== strpos(strtolower($_SERVER['HTTP_USER_AGENT']), strtolower('Googlebot')))){
	if(preg_match("/Googlebot/i",$_SERVER['HTTP_USER_AGENT'])){
		$name = "Google Bot";
		_q('INSERT INTO '.$prefix.'bot (name, title, ip, date_create) VALUES ("$name", "$title", "'.$REMOTE_ADDR.'", NOW())');
		_q('DELETE FROM wth_bot WHERE date_create<"'.date("Y-m-d H:i:s",time()-(86400*7)).'" ');
	}
}
#----------- /CHECK BOT

# -------- UPDATE LOGIN
if($_COOKIE['cookiduser'] && !$_SESSION['logmem']){
	lastlogin($_COOKIE['cookiduser']); 
}

if($_SESSION['logmem'] || $_COOKIE['cookiduser']){ 
	if($_COOKIE['cookiduser']) $logmem['iduser']=$_COOKIE['cookiduser'];
	$mem	=	mysql_fetch_array(mysql_query('SELECT a.*, b.* FROM '.$prefix.'user a INNER JOIN '.$prefix.'user_data b USING(iduser) WHERE a.iduser="'.$logmem['iduser'].'" LIMIT 1'));  
	$_SESSION['logmem']=$mem ;
	$logmem = $_SESSION['logmem'];
	$logiduser = $mem['iduser']; 
	$loguser= $mem['user'];
	$logurl = mpath($mem['user']);
	lastlogin($mem['iduser']);
}
*/
require_once("action.php");
require_once("action_tak.php");
?>
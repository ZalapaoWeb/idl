<?php
session_start();
include('a_inc.php');
include('a_meta.php');
include('a_header.php');
if(!$page && !$_SESSION['loaded']){
	#$_SESSION['loaded']=true;
	include("intro.php");
}else{
	if(!$page) $page='home';
		include('a_nav.php');
	if(file_exists('body_'.$page.'.php')){
		include('body_'.$page.'.php'); 
	}else{
		include('body_404.php');  
	}
	include('a_footer.php');
}
?>
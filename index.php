<?php
session_start();
include('a_inc.php');
include('a_meta.php');
include('a_header.php');
if(!$_GET['page'] && !$_SESSION['loaded']){
	#$_SESSION['loaded']=true;
	include("intro.php");
}else{
	if(!$_GET['page']) $_GET['page']='home';
		include('a_nav.php');
	if(file_exists('body_'.$_GET['page'].'.php')){
		include('body_'.$_GET['page'].'.php'); 
	}else{
		include('body_404.php');  
	}
	include('a_footer.php');
}
?>
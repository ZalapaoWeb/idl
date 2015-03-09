<?php
if($SERVER_NAME=="localhost"){
DEFINE("DB_HOST", "localhost"); 
DEFINE("DB_NAME", "zalapao_idl"); 
DEFINE("DB_USER", "root"); 
DEFINE("DB_PSW", "");
DEFINE("PRO_URL", "http://localhost/idl/");
}else{ 
#phpMyAdmin URL : http://27.254.148.144/phpMyAdmin/
DEFINE("DB_HOST", "localhost"); 
DEFINE("DB_NAME", "zalapao_idl"); 
DEFINE("DB_USER", "zalapao_idl"); 
DEFINE("DB_PSW", "0T5a8fDg");
DEFINE("PRO_URL", "http://www.ideaslunch.in.th/");
}  
DEFINE("PRO_NAME","IdeasLunch");   
DEFINE("PRO_TITLE", "เที่ยงนี้กินอะไร ?"); 
DEFINE("PRO_CAPTION", "เที่ยงนี้กินอะไร ? กับ IdeasLunch");   
DEFINE("PRO_DESCRIPTION", "เที่ยงนี้กินอะไร ? กับ IdeasLunch จบปัญหา ด้วยไอเดียเมนูอาหารมากกว่า 1000 เมนู ให้คุณได้เลือกเปิดไอเดียสำหรับมื้อเที่ยงของคุณ");   
DEFINE("PRO_KEYWORD", "เที่ยงนี้กินอะไร, กินข้าวเที่ยว, เมนูอาหารกลางวัน, ร้านอาหารกลางวัน");
$path_src = "img";

## defind paht for upload tmp image
DEFINE("UPLOAD_PATH","/tmp");

// Facebook App WeddingTH
DEFINE("FB_APPID","343124539209168");   
DEFINE("FB_SECRET","2391aba962b1f87728ed80249f824db1"); 

######### MOBILE DETECT ##################
/*
if(!$_SESSION['device_detect']){
	require_once 'mobile_detect.php';
	$detect = new Mobile_Detect;
	$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
	$_SESSION['device_detect'] = $deviceType;
}
*/
######### MOBILE DETECT ##################

$setcook=time()+(86400*7);
$prefix = 'idl_';
$mon = array("-","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฏาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
?>
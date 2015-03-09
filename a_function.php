<?php
function dolog($section,$iduser="", $param="", $idowner=""){
	// idowner : เจ้าของ subject
	// iduser : ผู้กระทำ subject

	global $logmem, $ip;
	if(!$iduser && $logmem) $iduser=$logmem['iduser'];
	@mysql_query("INSERT INTO wth_log (section,iduser, idowner, param, ip, date_create) VALUES ('$section', '$iduser', '$idowner', '$param', '$ip', NOW());");
}

function doalert($idowner,$iduser, $section="", $param=""){ 
	@_q("INSERT INTO wth_alert (idowner, iduser, section, param, date_create) VALUES ('$idowner', '$iduser', '$section', '$param', NOW())");
}

function dellog($section,$iduser="", $param="", $idowner=""){
	// idowner : เจ้าของ subject
	// iduser : ผู้กระทำ subject

	global $logmem, $ip;
	if(!$iduser && $logmem) $iduser=$logmem['iduser'];
	if($param) $wh = " AND param='$param' ";
	@mysql_query("DELETE FROM wth_log WHERE section='$section' AND iduser='$iduser' $wh;");
}

function upath($x){
	return "data/$x";
} 

function _path2($x=''){
	$txt = PRO_URL ;
	if($x) $txt .= '/'.$x;
	return $txt;
}

function _path($page="",$id=""){
	global $SERVER_NAME;
	if($SERVER_NAME=="localhost"){
		$path		=	PRO_URL."index.php?page=$page";
		if($id)	$path	.=	"&id=$id";  

		$path = PRO_URL ;
		if($page) $path .=	"$page/";
		if($id)	$path	.=	"$id"; 
	}else{
		$path = PRO_URL ;
		if($page) $path .=	"$page/";
		if($id)	$path	.=	"$id"; 
	} 
	#if($page!='zcontrol' && $page!='search' && $page) $path .= '.html';
	if($page=='article' || $page=='video') $path .= '.html';
	return $path ;
}

function mpath($x, $section='',$id='',$name=''){
	$txt = PRO_URL ;
	if($x) $txt .= '/'.$x ;
	if($section) $txt .= '/'.$section ;
	if($id) $txt .= '/'.$id ;
	if($name) $txt .= '/'._pm($name);
	return $txt ;
} 

function _pre($data){
	echo "<pre>";
	print_r($data);
	echo "</pre>"; 
} 
function _li($data){
	echo "<li>$data</li>"; 
} 

function _q($sql){
	if($sql) mysql_query($sql); 
} 

function lastlogin($iduser){
	_q("UPDATE `wth_user` SET date_login=NOW() WHERE iduser='".$iduser."' LIMIT 1");
}

function updatestamp($date, $add="360"){
	
	list($xdate,$xtime) = split(" ",$date);
	list($dy,$dm,$dd)=split("[-]",$xdate); 
	list($dh,$di,$ds)=split("[:]",$xtime); 
	$timestamp	=	mktime($dh,$di,$ds,$dm,$dd,$dy);
	$date_expire = $timestamp + (86400*$add);
	return date("Y-m-d 23:59:59",$date_expire);

}


function checkperms($file){ 
	if((!file_exists($file) || @substr(decoct(fileperms($file)),3)!="777") && $SERVER_NAME!="localhost"){
		return false ;
	}else{
		return true ;
	}
}

function	OpenMail($filename){
	if (file_exists($filename)) {
		$fp	=	fopen($filename,"r");
		while(!feof($fp)){
			$char	=	fgets($fp,1000);
			$text	.=	"$char";
		}
	}
	return $text ;	
}

function	SendMail($mailto,$mailfrom,$subject,$text){

	$mailto		 =	$mailto;
	$mailfrom	 =	$mailfrom;
	$msg			 =	$text;
						
	$headers = "From: $mailfrom\n";
	$headers .= "Content-Type: text/html; charset=uft-8\n";
	$headers  .= "MIME-Version: 1.0\n";
	#$headers .= "Reply-To: $mailfrom\n";
	#$headers .= "Return-path: <$mailfrom>\n";
	#echo $mailto."-".$mailfrom."-".$msg." -",$headers ;
	@mail($mailto, $subject, $msg,$headers);
}

function smtpSendMail($to, $from, $from_name, $subject, $body) { 
	global $error; 
	require_once('lib/class.phpmailer.php');
	define('GUSER', 'info@zalapao.com'); // Gmail username
	define('GPWD', "[hkol6fl';o"); // Gmail password
	$mail = new PHPMailer();  // create a new object
	$mail->IsSMTP(); // enable SMTP
	$mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
	$mail->SMTPAuth = true;  // authentication enabled
	$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
	$mail->Host = 'smtp.gmail.com';
	$mail->Port = 465; 
	$mail->Username = GUSER;  
	$mail->Password = GPWD;           
	$mail->SetFrom($from, $from_name);
	//$mail->Subject = $subject; 
	//$mail->Subject = "=?tis-620?B?".base64_encode("$subject")."?="; 
	//$mail->Subject = "=?tis-620?B?".base64_encode("$subject")."?="; 
	$mail->Subject = "=?utf-8?B?".base64_encode("$subject")."?="; 
	//$mail->Body = $body;
	$mail->MsgHTML(utf8totis620($body));
	$mail->AddAddress($to);
	if(!$mail->Send()) {
		$error = 'Mail error: '.$mail->ErrorInfo; 
		return false;
	} else {
		$error = 'Message sent!';
		return true;
	}
}
// resizeScale Start  --------------------------------------------------------- 
function resizeScale($input, $output, $output_size=100, $imagesname="jpg") {
			$size = $output_size ;
			$dot	=	strtolower(end(explode('.',$input)));
			if($dot!="jpg" && $dot!="jpeg") $dot = strtolower(end(explode('.',$imagesname)));

			switch($dot){
				case "gif":		$image=ImageCreateFromGif($input);	break; 
				case "png":	$image=ImageCreateFromPng($input); break; 
				default;			$image=ImageCreateFromJpeg($input);
			}
	
	if(ImagesX($image)<$size && ImagesY($image)<$size){
			$newwidth	 	=	ImagesX($image);
			$newheight	=	ImagesY($image);
			$position_x = 0 ;
			$position_y = 0 ;
	}else{

			// ภาพแนวนอน
			if(ImagesX($image)>ImagesY($image)){
				$percen	 = (ImagesX($image)/ImagesY($image))*100;
				$newheight	= ($size * 100) / $percen ; 
				$newwidth		= $size;
				
				$position_y = ($size - $newheight) / 2 ;
				$position_x = 0 ;
			// ภาพแนวตั้ง
			}elseif(ImagesY($image)>ImagesX($image)){
				$percen	 = (ImagesY($image)/ImagesX($image))*100;	
				$newwidth		= ($size * 100) / $percen ; 
				$newheight	= $size;
				
				$position_x = ($size - $newwidth) / 2 ;
				$position_y = 0 ;
			}elseif(ImagesX($image)==ImagesY($image)){
				$newwidth	 	=	$size;
				$newheight	=	$size;
				$position_x = 0 ;
				$position_y = 0 ;
			}
	} 
			$blank	= ImageCreateTrueColor($newwidth,$newheight);
			// Set BG White
			#$white	= @imagecolorallocate($blank, 255, 255, 255);
			#@imagefill($blank, 0, 0, $white);
			
			ImageCopyResampled($blank, $image, 0, 0, 0, 0, $newwidth, $newheight, ImagesX($image), ImagesY($image));
 
			switch($dot){
			case "gif":		ImageGif($blank,$output,9); break; 
			case "png":		ImagePng($blank,$output,9); break; 
			default;			ImageJpeg($blank,$output,99);
			}
			ImageDestroy($blank);
			return true ;
} 
 
// resizeScale End   ---------------------------------------------------------
function resizeFix($fromimage, $toimage, $size=500, $imagesname="jpg") {
		
			$input	=	$fromimage;
			$output	=	$toimage;
			$size		=	$size; 

			$dot	=	strtolower(end(explode('.',$fromimage)));
			if($dot!="jpg" && $dot!="jpeg") $dot = strtolower(end(explode('.',$imagesname)));

			switch($dot){
				case "gif":		$image=ImageCreateFromGif($input);	break; 
				case "png":	$image=ImageCreateFromPng($input); break; 
				default;		$image=ImageCreateFromJpeg($input);
			}

			$percen	 	=	(ImagesX($image)/ImagesY($image))*100;
			$newwidth	 	=	($size*$percen)/100;
			$newheight	=	$size;			

/*
			$percen	 	=	(ImagesY($image)/ImagesX($image))*100;
			$newheight	=	($size*$percen)/100;
			$newwidth	=	$size;	
	*/		
			$blank	= ImageCreateTrueColor($newwidth,$newheight);
			$white	= @imagecolorallocate($blank, 255, 255, 255);
			@imagefill($blank, 0, 0, $white);
			
			ImageCopyResampled($blank, $image, 0, 0, 0, 0, $newwidth, $newheight, ImagesX($image), ImagesY($image));
 
			switch($dot){
			case "gif":		ImageGif($blank,$output,9); break; 
			case "png":	ImagePng($blank,$output,9); break; 
			default;		ImageJpeg($blank,$output,100);
			}

			ImageDestroy($blank);
}  
 
function resizeStamp($input, $output, $stampimage, $output_size=400, $imagesname="jpg") {
			$size = $output_size ;
			$dot	=	strtolower(end(explode('.',$input)));
			if($dot!="jpg" && $dot!="jpeg") $dot = strtolower(end(explode('.',$imagesname)));

			switch($dot){
				case "gif":		$image=ImageCreateFromGif($input);	break; 
				case "png":	$image=ImageCreateFromPng($input); break; 
				default;			$image=ImageCreateFromJpeg($input);
			}
	
	if(ImagesX($image)<$size && ImagesY($image)<$size){
			$newwidth	 	=	ImagesX($image);
			$newheight	=	ImagesY($image);
			$position_x = 0 ;
			$position_y = 0 ;
	}else{

			// ภาพแนวนอน
			if(ImagesX($image)>ImagesY($image)){
				$percen	 = (ImagesX($image)/ImagesY($image))*100;
				$newheight	= ($size * 100) / $percen ; 
				$newwidth		= $size;
				
				$position_y = ($size - $newheight) / 2 ;
				$position_x = 0 ;
			// ภาพแนวตั้ง
			}elseif(ImagesY($image)>ImagesX($image)){
				$percen	 = (ImagesY($image)/ImagesX($image))*100;	
				$newwidth		= ($size * 100) / $percen ; 
				$newheight	= $size;
				
				$position_x = ($size - $newwidth) / 2 ;
				$position_y = 0 ;
			}elseif(ImagesX($image)==ImagesY($image)){
				$newwidth	 	=	$size;
				$newheight	=	$size;
				$position_x = 0 ;
				$position_y = 0 ;
			}
	} 
			$blank	= ImageCreateTrueColor($newwidth,$newheight);
			// Set BG White
			$white	= @imagecolorallocate($blank, 255, 255, 255);
			@imagefill($blank, 0, 0, $white);
			
			ImageCopyResampled($blank, $image, 0, 0, 0, 0, $newwidth, $newheight, ImagesX($image), ImagesY($image));

			$logo=ImageCreateFromPng($stampimage);
			ImageCopyResampled($blank,$logo, 0, 0, 0, 0,$newwidth, $newheight, ImagesX($blank), ImagesY($blank));
 
			switch($dot){
			case "gif":		ImageGif($blank,$output,9); break; 
			case "png":	ImagePng($blank,$output,9); break; 
			default;			ImageJpeg($blank,$output,100);
			}
			ImageDestroy($blank);
			return true ;
}  

function crop($fromimage, $toimage,$x="", $y="", $imagesname="jpg") {

				$input	=$fromimage;
				$output	=$toimage;
				#กำหนดขนาดของรูปใหม่
				if(!$x){ $newX=96; }else{ $newX=$x; } 
				if(!$y){ $newY=96; }else{ $newY=$y; } 
	 
				$dot	=	strtolower(end(explode('.',$fromimage)));
				if($dot!="jpg" && $dot!="jpeg") $dot = strtolower(end(explode('.',$imagesname)));

				switch($dot){
				case "gif":		$image=ImageCreateFromGif($input);	break; 
				case "png":	$image=ImageCreateFromPng($input); break; 
				default;			$image=ImageCreateFromJpeg($input);
				}
				$oldX=ImagesX($image);
				$oldY=ImagesY($image);
					
				if($oldX<$oldY){ // รูปแนวตั้ง 
					$percen	 		=	($newX/$oldX)*100;
					$resizeX		=	$newX;
					$resizeY	 	=	($oldY*$percen)/100; 
					$positionX		=	0 ;
					$positionY		=	($resizeY-$newY)/2 ;
					if($oldX<$newX) $positionY=($oldY-$newY)/2 ; 
				}elseif($oldX>$oldY){ // รูปแนวนอน
					$percen	 		=	($newY/$oldY)*100;
					$resizeY		=	$newY;
					$resizeX	 	=	($oldX*$percen)/100; 
					$positionX		=	($resizeX-$newX)/2 ;
					$positionY		=	0 ;
					if($oldY<$newY) $positionX=($oldX-$newX)/2 ; 
				}else{ // สี่เหลี่ยมจตุรัส
					$resizeX		=	$newX;
					$resizeY		=	$newY;
					$positionX		=	0 ;
					$positionY		=	0 ; 
				}
				
			if($positionX<0) $positionX=0;
			if($positionY<0) $positionY=0; 
			#จุดเริ่มต้นแสดงภาพ
			$dstX=0;
			$dstY=0;

			#สร้างกรอบภาพตามขนาดที่กำหนด
			$blank=ImageCreateTrueColor($newX,$newY);

			#สร้างรูปภาพใหม่ตามขนาดที่กำหนด จากรูปภาพต้นฉบับ
			ImageCopyResampled($blank,$image,$dstX,$dstY,$positionX,$positionY,$resizeX,$resizeY,$oldX,$oldY); 
 
			switch($dot){
			case "gif":		ImageGif($blank,$output,9); break; 
			case "png":	ImagePng($blank,$output,9); break; 
			default;		ImageJpeg($blank,$output,100);
			}
			ImageDestroy($blank);
}

function showdate($date,$type=""){ 
 
			if(!$date) date("Y-m-d");
			list($dy,$dm,$dd)=split("[-]",$date); 
			$timestamp	=	mktime(0,0,0,$dm,$dd,$dy); 
			
			if($type){
				$mon = array("-","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
				$year = substr(date("Y",$timestamp)+543,2,2);
			}else{
				
				$day_f =	array("อาทิตย์","จันทร์","อังคาร","พุธ","พฤหัสบดี","ศุกร์","เสาร์");
				$txtday = "วัน".$day_f[date("w",$timestamp)]." ที่ ";

				$mon = array("-","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฏาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
				$year = date("Y",$timestamp)+543;
			}

			$text = $txtday.date("j",$timestamp)." ".$mon[date("n",$timestamp)]." ".$year; 
			return	$text ;
}

function isodate($date){  
			if(!$date) $date = date("Y-m-d H:i:s"); 
			list($td,$tm) = explode(" ",$date);
			list($dy,$dm,$dd)=explode("-",$td); 
			list($hh,$ss,$ii) =  explode(":", $tm);
			$timestamp	=	mktime($hh,$ss,$ii,$dm,$dd,$dy);   
			$text = date("c",$timestamp); 
			return $text ;
}
function showdateshort($date,$type="",$br=""){ 
 
			if(!$date) date("Y-m-d");
			list($dy,$dm,$dd)=split("[-]",$date); 
			$timestamp	=	mktime(0,0,0,$dm,$dd,$dy); 
			
			if($type){
				$mon = array("-","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
				$year = substr(date("Y",$timestamp)+543,2,2);
			}else{
				
				$day_f =	array("อาทิตย์","จันทร์","อังคาร","พุธ","พฤหัสบดี","ศุกร์","เสาร์");
				$txtday = "วัน".$day_f[date("w",$timestamp)]." ที่<br>";

				$mon = array("-","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฏาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
				$year = date("Y",$timestamp)+543;
			}

			if($br){
			$text = $txtday.'<span style="font-size:'.$br.'px;"><b>'.date("j",$timestamp)."</b></span><br>".$mon[date("n",$timestamp)]." ".$year; 
			}else{
			$text = $txtday.date("j",$timestamp)." ".$mon[date("n",$timestamp)]." ".$year; 
			}
			return	$text ;
}

function showdatetext($date,$type="",$br=""){ 
 
			if(!$date) date("Y-m-d");
			list($dy,$dm,$dd)=split("[-]",$date); 
			$timestamp	=	mktime(0,0,0,$dm,$dd,$dy); 
			 
				$day_f =	array("อาทิตย์","จันทร์","อังคาร","พุธ","พฤหัสบดี","ศุกร์","เสาร์");
				$txtday = "วัน".$day_f[date("w",$timestamp)]." ที่ ";

				$mon = array("-","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฏาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
				$year = date("Y",$timestamp)+543; 
 
			$text = $txtday.date("j",$timestamp)." ".$mon[date("n",$timestamp)]." ".$year;  
			return	$text ;
}
function showdateshort2($date){ 
 
			if(!$date) date("Y-m-d");
			list($dy,$dm,$dd)=split("[-]",$date); 
			$timestamp	=	mktime(0,0,0,$dm,$dd,$dy);
			$mon = array("-","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
			$year = substr(date("Y",$timestamp)+543,2,2);  
			$text = date("j",$timestamp)." ".$mon[date("n",$timestamp)]." ".$year; 
			return	$text ;
}

function repliesdateshort($date){ 
 
			if(!$date) date("Y-m-d H:i:s");
			list($xdate,$xtime) = split(" ",$date);
			list($dy,$dm,$dd)=split("[-]",$xdate); 
			list($dh,$di,$ds)=split("[:]",$xtime); 
			$timestamp	=	mktime($dh,$di,$ds,$dm,$dd,$dy); 
			$mon = array("-","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
			$year = substr(date("Y",$timestamp)+543,2,2);  
			$text = date("j",$timestamp)." ".$mon[date("n",$timestamp)]." ".$year." เวลา $dh:$di"; 
			return	$text ;
}

function getstamp($date){
	
	list($xdate,$xtime) = split(" ",$date);
	list($dy,$dm,$dd)=split("[-]",$xdate); 
	list($dh,$di,$ds)=split("[:]",$xtime); 
	$timestamp	=	mktime($dh,$di,$ds,$dm,$dd,$dy); 
	return $timestamp ;

}

function showdatetime($date,$type=""){ 
 
			if(!$date) date("Y-m-d H:i:s");
			list($xdate,$xtime) = split(" ",$date);
			list($dy,$dm,$dd)=split("[-]",$xdate); 
			list($dh,$di,$ds)=split("[:]",$xtime); 
			$timestamp	=	mktime($dh,$di,$ds,$dm,$dd,$dy); 
			
			if($type){
				$mon = array("-","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
				$year = substr(date("Y",$timestamp)+543,2,2);
				
				$text = date("j",$timestamp)." ".$mon[date("n",$timestamp)]." ".$year. " | $dh:$di" ; 
			}else{
				
				$day_f =	array("อาทิตย์","จันทร์","อังคาร","พุธ","พฤหัสบดี","ศุกร์","เสาร์");
				$txtday = "วัน".$day_f[date("w",$timestamp)]." ที่ ";

				$mon = array("-","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฏาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
				$year = date("Y",$timestamp)+543;
				
				$text = "".$txtday.date("j",$timestamp)." ".$mon[date("n",$timestamp)]." ".$year. " เวลา $dh:$di" ; 
			}

			return	$text ;
}


function showdatematch($date){ 
 
			if(!$date) date("Y-m-d");
			list($dy,$dm,$dd)=split("[-]",$date); 
			$timestamp	=	mktime(0,0,0,$dm,$dd,$dy); 
			 
			$day_f =	array("อาทิตย์","จันทร์","อังคาร","พุธ","พฤหัสบดี","ศุกร์","เสาร์");
			$txtday = $day_f[date("w",$timestamp)]." ที่ ";

			$mon = array("-","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฏาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
			$year = date("Y",$timestamp)+543; 

			$text = $txtday.date("j",$timestamp)." ".$mon[date("n",$timestamp)]." ".$year; 
			$text .= " เวลา ".substr($date,11,5)." น.";
			return	$text ;
}

function redirect($url=""){ 
	echo "<script language='javascript'>";
	echo "window.location='$url';";
	echo "</script>";
	#if($url) header("Location: $url");
}


function dateDiff($date){
$diff = (strtotime($date) - (time()-86400));
 
$diff = floor($diff / 86400);
return $diff;

}

function timeDiff($date,$detailed=false,$n = 0){
		
			list($xd, $xt) = split(" ",$date);
			list($dy,$dm,$dd) = split("[-]", $xd);
			list($hh,$ss,$ii) = split("[:]", $xt);
			
			$timestamp	=	mktime($hh,$ss,$ii,$dm,$dd,$dy); 
			$now = time();

			#If the difference is positive "ago" - negative "away"
			($timestamp >= $now) ? $action = 'ที่แล้ว' : $action = 'ที่ผ่านมา';

			$diff = ($action == 'ที่แล้ว' ? $timestamp - $now : $now - $timestamp);

			# Set the periods of time
			//$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
			$periods = array("วินาที", "นาที", "ชั่วโมง", "วัน", "สัปดาห์", "เดือน", "ปี", "ทศวรรษ");
			$lengths = array(1, 60, 3600, 86400, 604800, 2630880, 31570560, 315705600);

			# Go from decades backwards to seconds
			$i = sizeof($lengths) - 1;         # Size of the lengths / periods in case you change them
			$time = "";                        # The string we will hold our times in
			while($i >= $n) {
				if($diff > $lengths[$i-1]) {        # if the difference is greater than the length we are checking... continue
					if($lengths[$i-1]<=0){$lengths[$i-1]=1;}
					$val = floor($diff / $lengths[$i-1]);    # 65 / 60 = 1.  That means one minute.  130 / 60 = 2. Two minutes.. etc
					$time .= $val ." ". $periods[$i-1].($val > 1 ? ' ' : ' ');  # The value, then the name associated, then add 's' if plural
					$diff -= ($val * $lengths[$i-1]);    # subtract the values we just used from the overall diff so we can find the rest of the information
					if(!$detailed) { $i = 0; }    # if detailed is turn off (default) only show the first set found, else show all information
				}
				$i--;
			}
		  
			# Basic error checking.
			if($time == "") {
				return "ณ ตอนนี้";
			} else {
				return $time.$action;
				//return $time;
			}
} 



function getcache($section){ 
	global $PHPSESSID ;
	if(!$PHPSESSID) $PHPSESSID=md5(time());
	$txt = "./c/$section.c";
	return $txt ;
}
  
function	datacreatefile($filename,$text){ 
	$txt_open = fopen("$filename", "w");
	fwrite($txt_open, $text);
	fclose($txt_open);
}

function	dataopenfile($filename){
	if (file_exists($filename)) {
				$fp	=	fopen($filename,"r");
				while(!feof($fp)){
				$char	=	fgets($fp,1000);
				$text	.=	"$char";
			}
		}
	return $text ;	
}

function utf8totis620($string) {
  $str = $string;
  $res = "";
  for ($i = 0; $i < strlen($str); $i++) {
    if (ord($str[$i]) == 224) {
      $unicode = ord($str[$i+2]) & 0x3F;
      $unicode |= (ord($str[$i+1]) & 0x3F) << 6;
      $unicode |= (ord($str[$i]) & 0x0F) << 12;
      $res .= chr($unicode-0x0E00+0xA0);
      $i += 2;
    } else {
      $res .= $str[$i];
    }
  }
  return $res;
}

function tis2utf8($tis) {
   for( $i=0 ; $i< strlen($tis) ; $i++ ){
      $s = substr($tis, $i, 1);
      $val = ord($s);
      if( $val < 0x80 ){
         $utf8 .= $s;
      } elseif ( ( 0xA1 <= $val and $val <= 0xDA ) or ( 0xDF <= $val and $val <= 0xFB ) ){
         $unicode = 0x0E00 + $val - 0xA0;
         $utf8 .= chr( 0xE0 | ($unicode >> 12) );
         $utf8 .= chr( 0x80 | (($unicode >> 6) & 0x3F) );
         $utf8 .= chr( 0x80 | ($unicode & 0x3F) );
      }
   }
   return $utf8;
} 
 


function wordfilter($txt){
	if($txt){
	$txt	=	ereg_replace("(([^\.<>[:space:]]+\.)|([[:alpha:]]+://))+". "[^\.<>[:space:]]+\.[^<>[:space:]]+", "<a href=\"http://\\0\" target=\"_blank\" ref=\"nofollow\">\\0</a>", $txt);
	$txt	=	ereg_replace("http://([[:alpha:]]+://)","\\1",$txt);  
	}
	return $txt;
}
# Function Send SMS
function send_sms2($mobile_no,$msg,$sender) {
	$user_id		= "tplshopping";
	$passwd		= "tpltpl";
	$host				= "smsgateway.packetlove.com"; 
	$port				= "80"; 
	$path			= "/api_sms.jsp";

	// Message
	$data="user_id=$user_id&passwd=$passwd&sender=$sender&mobile_no=$mobile_no&msg=$msg";
	$fp=@fsockopen($host,80);
	
	if ( $fp)
	{
		fputs($fp,"POST $path HTTP/1.1\r\n");
		fputs($fp,"Host: $host\r\n");
		fputs($fp,"Content-type: application/x-www-form-urlencoded\r\n");
		fputs($fp,"Content-length: ".strlen($data)."\r\n");
		fputs($fp,"Connection: close\r\n\r\n");
		fputs($fp,$data);
		while(!feof($fp)) {
			// Return Message From Gateway
			$buffer=fgets($fp,128);
			echo "$buffer\n";
		}
		fclose($fp);
	}
}

function send_sms($mobile_no,$msg,$sender){
/*
Acc: 2560  
Code: 200 
User : bst 
PASS: store
*/
	$AccountID="2560";
	$UserID="200";
	$UserPass="store";
	$Phone=$mobile_no;
	$Text=urlencode("$msg");
	$Sender="$sender";
    $url="http://mailbit.co.th/Scripts/mgrqispi.dll?Appname=Port2SMS&prgname=HTTP_SimpleSMS1&AccountID=$AccountID&UserID=$UserID&UserPass=$UserPass&Phone=$mobile_no&Text=$Text&Sender=$Sender";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    $ressult=curl_exec($ch);
    return $result;
}
#Function check button on stock
function checkStock($p_id){
	global $open_sys_stock;
	
	if($open_sys_stock) {
		$sql = " SELECT balance FROM bts_stock WHERE p_id=$p_id AND balance>0 ";
		$data = db_query_fetch($sql); 
		if(intval($data[0][0])>0) return intval($data[0][0]);
		else return 0;
	} else return true;
}

function _t($txt){
	$txt = StripSlashes($txt);
	#$txt = ucwords($txt);
	return $txt ;
}

function _link($txt){
	if(preg_match('/https/i',$txt)){
		$h = 'https://';
	}else{
		$h = 'http://';
	}
	$txt = eregi_replace("https://","",$txt);
	$txt = eregi_replace("http://","",$txt);
	$txt = $h.$txt;
	return $txt ;
}

function _lazy($txt){
	$txt = StripSlashes($txt);
	$txt = eregi_replace("src=","src='/assets/img/grey.gif' data-original=",$txt);
	#$txt = ucwords($txt);
	return $txt ;
}


function _t2($txt,$num=0){
	$txt = strip_tags($txt);
	$txt = StripSlashes($txt);
	if(strlen($txt)>$num) $dot = "..";
	$txt = substr($txt,0,$num);
	return $txt.$dot ;
}

function _r($txt){
	if($txt){
	$txt = eregi_replace(chr(13),"<br>",$txt); 
	$txt = StripSlashes($txt); 
	$txt= preg_replace('@((https?://)?([-\w]+\.[-\w\.]+)+\w(:\d+)?(/([-\w/_\.]*(\?\S+)?)?)*)@', '<a href="$1" target="blank">$1</a>', $txt);
	$txt = str_replace("href=\"www.","href=\"http://www.",$txt);
	}
	return $txt ;
}

function _rhref($txt){
	if($txt){
	$txt = eregi_replace(chr(13),"<br>",$txt); 
	$txt = StripSlashes($txt); 
	$txt= preg_replace('@((https?://)?([-\w]+\.[-\w\.]+)+\w(:\d+)?(/([-\w/_\.]*(\?\S+)?)?)*)@', '<a href="$1" target="blank">$1</a>', $txt);
	$txt = str_replace("href=\"www.","href=\"http://www.",$txt);
	}
	return $txt ;
}

function _a($txt){
	if($txt){
	$txt = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $txt); 
	#$txt = preg_replace('/<object\b[^>]*>(.*?)<\/object>/is', "", $txt); 
	#$txt = preg_replace('/<embed\b[^>]*>(.*?)<\/embed>/is', "", $txt); 
	$txt = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', "", $txt);
	$txt = preg_replace('/<iframe\b[^>]*>(.*?)<\/iframe>/is', "", $txt);
	$txt = AddSlashes($txt);
	}
	return $txt ;
}

function _ahtml($txt){ 
	if($txt){
	$txt = strip_tags($txt);
	$txt = AddSlashes($txt);
	}
	return $txt ;
}


function _d($txt){
	#$txt = eregi_replace(chr(13),"<br>",$txt);
	$txt = StripSlashes($txt);
	#$txt = ucwords($txt);
	return $txt ;
} 

function _pm($str) {
	#$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $str);
	$str = StripSlashes($str);
	$str = trim($str);
	$clean = preg_replace("/[(),.\#\'\"\?\@]+/", '', $str);
	$clean = strtolower(trim($clean, '-'));
	$clean = preg_replace("/[\/_|+ -]+/", '-', $clean);

	return $clean;
}

function _pmt($str) {
	#$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $str);
	$str = StripSlashes($str);
	$str = trim($str);
	$clean = preg_replace("/[(),.\#\'\"\?\@]+/", '', $str);
	$clean = strtolower(trim($clean, '-'));
	$clean = preg_replace("/-/", '~', $clean);
	$clean = preg_replace("/[\/_|+ ]+/", '-', $clean);

	return $clean;
}

function _pmr($str){
	$clean = preg_replace("/-/", ' ', $str); 
	$clean = preg_replace("/~/", '-', $clean); 
	return $clean;
}

function _tag($txt="",$section=""){ 
	$path = "/tag/"._pmt($txt); 
	return $path ;
}


function _taglist($txt=""){ 
	if($txt){  
		$txtm = "<ul id='tag-cloud'><li class='first'>Tag : </li>";
		$txta = split(",",$txt);
		$txta = array_unique($txta);
		foreach($txta AS $key=>$val){
			if(trim($val)) $txtm .= "<li><a href='"._tag($val)."' title='".trim($val)."'>".trim($val)."</a></li>"; 
		} 
		$txtm .= "</ul>";
	}
	return $txtm ;
}

// Set the content-type
function google_qr($qname,$url,$size='320',$EC_level='L',$margin='0'){
$url = urlencode($url);
$qstring = 'http://chart.apis.google.com/chart?chs='.$size.'x'.$size.'&cht=qr&choe=UTF-8&chld='.$EC_level.'|'.$margin.'&chl='.$url;
#echo '<img src="'.$qstring.'"  alt="QR code" width="'.$size.'" height="'.$size.'"/>';
$data = file_get_contents($qstring);
$f = fopen($qname, 'w');
fwrite($f, $data);
fclose($f);
}

function get_paging($tot_rows,$pp,$curr_page)
{
    $pages = ceil($tot_rows / $pp); // calc pages 
    $data = array(); // start out array
    $data['si']        = ($curr_page * $pp) - $pp; // what row to start at
    $data['pages']     = $pages;                   // add the pages
    $data['curr_page'] = $curr_page;               // Whats the current page 
    return $data; //return the paging data 
}

function show_paging($number2, $setpage, $no){
	global $page,$id,$qurl;
	$paging_info = get_paging($number2, $setpage, $no);
	if($qurl) $id .= '/'.$qurl;

	echo '<p>ผลงานของสมาชิกทั้งหมด <b>'._num($number2).'</b> คน (<b>'._num($no).'</b>/'._num($paging_info['pages']).' หน้า)</p>';
	echo '<ul class="pagination pagination-colory">';
	if($paging_info['curr_page'] > 1){
        $xp = '<li class="prev"><a href="'._path($page.'/'.$id.'&no='.($paging_info['curr_page'] - 1)).'" title="Page '.($paging_info['curr_page'] - 1).'"><i class="icon-chevron-left"></i></a></li>';
        $xpp =  '<li><a href="'._path($page.'/'.$id.$qurl.'&no=1').'" title="หน้า 1">หน้าแรก</a></li>';
	}
	if($paging_info['curr_page'] < $paging_info['pages']){
        $xn = '<li class="next"><a href="'._path($page.'/'.$id.'&no='.($paging_info['curr_page']+1)).'" title="หน้า '.($paging_info['curr_page'] + 1).'"><i class="icon-chevron-right"></i></a></li>';
        $xnn =  '<li><a href="'._path($page.'/'.$id.'&no='.$paging_info['pages']).'" title="Page '.$paging_info['pages'].'">หน้าสุดท้าย</a> </li>';
	}

	echo $xpp.$xp;

        $max = 7;
        if($paging_info['curr_page'] < $max)
            $sp = 1;
        elseif($paging_info['curr_page'] >= ($paging_info['pages'] - floor($max / 2)) )
            $sp = $paging_info['pages'] - $max + 1;
        elseif($paging_info['curr_page'] >= $max)
            $sp = $paging_info['curr_page']  - floor($max/2);

	if($paging_info['curr_page'] >= $max){
        #echo '<li><a href="'._path($page,$id.'&no=1').'" title="หน้า 1">1</a></li>';
        echo '<li class="disabled"><a>..</a></li>';
	}

	for($i = $sp; $i <= ($sp + $max -1);$i++){ 
            if($i > $paging_info['pages'])
                continue;
	
        if($paging_info['curr_page'] == $i){
             echo '<li class="active"><a>'.$i.'</a></li>';
        }else{
             echo '<li><a href="'._path($page.'/'.$id.'&no='.$i).'" title="Page '.$i.'">'.$i.'</a></li>';
		}
	}
	
	if($paging_info['curr_page'] < ($paging_info['pages'] - floor($max / 2))){
        echo '<li class="disabled"><a>..</a></li>';
       # echo '<li><a href="'._path($page,$id.'&no='.$paging_info['pages']).'" title="หน้า '.$paging_info['pages'].'">'.$paging_info['pages'].'</a></li>';
    }
	echo $xn.$xnn;

	echo '</ul>';
}
?>
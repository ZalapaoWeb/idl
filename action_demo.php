<? 
if(basename($PHP_SELF)=="action.php") include("a_inc.php");
@header("Content-Type:text/html;charset=".CHARSET);
  
if($page=="register" && $_COOKIE['fb_access_token']){

		$fbu =  get_facebook();    
		// check user avaliable
		$ck	=	mysql_fetch_array(mysql_query("select * from `gth_user` a INNER JOIN `gth_user_data` b USING(iduser) where (a.email='".$fbu->email."' || a.user='".$fbu->username."' || ( (b.fb_user='".$fbu->username."' OR b.fb_id='".$fbu->id."') AND b.fb_user!='' )) limit 1"));
		if($ck){
				$_SESSION['logmem'] = $ck ;
				$setcook=time()+(86400*31);
				setcookie("cookiduser",$ck['iduser'],$setcook,"",".graduateth.com"); 
				lastlogin($ck['iduser']); 
				redirect(mpath($ck['user']));
				exit;
		}
		// end check user avaliable 
 
}else if($page=="logout"){ 
		@dolog("logout");
		unset($_SESSION['logadmin']);
		unset($_SESSION['logmem']); 
		unset($fbuser);
		$setcook=time()-3600;
		setcookie("popup","",$setcook,"",".graduateth.com");
		setcookie("popup2","",$setcook,"",".graduateth.com");
		setcookie("cookiduser","",$setcook,"",".graduateth.com");
		setcookie("fb_access_token","",$setcook,"",".graduateth.com");
		setcookie("access_token","",$setcook,"",".graduateth.com");
		session_destroy();
		redirect(PRO_URL); 
	exit;
}else if($page=="login"){

		$fbu =  get_facebook();    
		if($fbu){
			$ck	=	mysql_fetch_array(mysql_query("select * from `gth_user` a INNER JOIN `gth_user_data` b USING(iduser) WHERE b.fb_id='".$fbu->id."' limit 1"));
			if($ck){
					$_SESSION['logmem'] = $ck ;
					$setcook=time()+(86400*31);
					setcookie("cookiduser",$ck['iduser'],$setcook,"",".graduateth.com"); 
					lastlogin($ck['iduser']); 
					redirect(mpath($ck['user']));
					exit;
			}else{
				redirect(_path('oauth.php'));
			}
			
		}else{
				redirect(_path('oauth.php'));
		} 

	exit;
}else if($page=="loginadmin"){
 
			$ck	=	mysql_fetch_array(mysql_query("select * from `gth_user` a INNER JOIN `gth_user_data` b USING(iduser) WHERE a.user='".$user."' limit 1"));
			if($ck){
					$_SESSION['logmem'] = $ck ;
					$setcook=time()+(86400*31);
					setcookie("cookiduser",$ck['iduser'],$setcook,"",".graduateth.com"); 
					lastlogin($ck['iduser']); 
					redirect(mpath($ck['user']));
					exit;
			}else{
				redirect(_path('oauth.php'));
			} 
	exit;

}else if($page=="fbdisconnect"){  

		mysql_query("UPDATE mty_member SET `idfb`='' ,`fblink`='', `fbuser`='', `fb_access_token`=''  WHERE iduser='$logmem[iduser]' ");
		redirect(mpath($logmem['user'])."manage-setting&tab=connect");
		@dellog("connect","","facebook");
		exit;
 
}else if($page=="igdisconnect"){  
		
		mysql_query("UPDATE mty_member SET `idig`='', `iguser`='' WHERE iduser='$logmem[iduser]' LIMIT 1");
		redirect(mpath($logmem['user'])."manage-setting&tab=connect");
		@dellog("connect","","instagram");
		exit;
}

switch($ac){ 
	case "user-sponsor-update":
			foreach($_POST['ck'] AS $key=>$val){ 
				_q("INSERT INTO gth_user_sponsor (iduser, idschedule, date_sponsor, pt, date_create) VALUES ('$logiduser','$idschedule','".$key."','".$val."', NOW())");
			} 
			
			@dolog($ac); 
			$_SESSION['uaction'][$ac]="ok";
			redirect(mpath($loguser)."/sponsor/$idschedule");
			exit;
	break; 
	case "user-credit-upload": 

			if($_FILES['pic']['error']==0){
				$dot	=	strtolower(end(explode('.',$_FILES['pic']['name']))); 
				$pname = time().rand(10,99).".$dot";
				$ori = "$credit_src/ori_".$pname;
				@copy($_FILES['pic']['tmp_name'],$ori);  
				@resizeScale($ori, "$credit_src/640_".$pname, 640, $pname); 
				_q("UPDATE gth_user_credit SET payment_status='W', payment_attach='".$pname."' WHERE orderid='".$orderid."' ");
				$_SESSION['uaction'][$ac]= $orderid ; 
				$subject = "$orderid Waiting for APPROVE.";
				$message = "$orderid Waiting for APPROVE.";
				SendMail('apisit_22@hotmail.com',PRO_NAME." <noreply@graduateth.com>",$subject,$message); 
			}else{ 
				$_SESSION['uaction'][$ac]="fail"; 
			} 
				@dolog($ac);  
				redirect(mpath($loguser)."/credit");  
			exit;
	break; 
	case "user-credit-approve":

			@_q("UPDATE gth_user_credit SET payment_status='".$statusx."', payment_date=NOW() WHERE orderid='".$orderid."' LIMIT 1");

		exit;
	break; 
	case "user-credit-checkout": 
 
				$price = $a_credit[$package]['price'];
				$pt = $a_credit[$package]['credit'];
				_q("INSERT INTO gth_user_credit (iduser, package, pt, payment_price, date_create) VALUES ('$logiduser','$package','".$pt."','".$price."', NOW())");
				$id = mysql_insert_id();
				$orderid = 'GTH-'.rand(1,9).sprintf("%04d",$id).rand(1,9);
				_q("UPDATE gth_user_credit SET orderid='".$orderid."' WHERE id='".$id."' ");

				@dolog($ac); 
				$_SESSION['uaction'][$ac]="ok"; 

				redirect(mpath($loguser)."/credit/orderid/$orderid");
			exit;
	break; 
	case "gettoken":
		if($access_token){
			$setcook=time()+(86400*31); 
			setcookie("fb_access_token",$access_token,$setcook,"",".graduateth.com");
			echo $access_token ;
			exit;
		}
	break;
	case "emailcheck":
		$u = trim(strtolower($u));
		list($num) = mysql_fetch_row(mysql_query("select count(*) as count from gth_user where email='$u' "));
		echo ($num==0) ? "Y" : "N" ;
		exit;
	break;
	case "usercheck":
		$u = trim(strtolower($u));
		list($num) = mysql_fetch_row(mysql_query("select count(*) as count from gth_user where user='$u' "));
		echo ($num==0 && $u!="schedule" && $u!="contact" && $u!="jobs" && $u!="privacy" && $u!="aboutus" && $u!="about" && $u!="graduate") ? "Y" : "N" ;

		exit;
	break; 
	case "admin-verified":

			@_q("UPDATE `gth_user` SET isverified='$verifiedx', isverified_date=NOW() WHERE iduser='$idx' LIMIT 1");

			list($num) = mysql_fetch_row(mysql_query("SELECT count(*) as count FROM gth_user_credit WHERE iduser='$idx' AND package='new' "));
			if(!$num){
			_q("INSERT INTO gth_user_credit (iduser, package, pt, payment_price, payment_status, payment_date, date_create) VALUES ('$idx','new','500','0', 'Y', NOW(), NOW())");
			$id = mysql_insert_id();
			$orderid = 'GTH-'.rand(1,9).sprintf("%04d",$id).rand(1,9);
			_q("UPDATE gth_user_credit SET orderid='".$orderid."' WHERE id='".$id."' ");
			}

			if($verifiedx=='N'){ 
				@_q("UPDATE `gth_user_data` SET verified_fname='', verified_lname='', verified_mobile='', verified_idcard='', verified_idphoto='', verified_slip='' WHERE iduser='$idx' LIMIT 1");
			}

		exit;
	break; 
	case "user-verified":

		$verified_fname = _a($verified_fname); 
		$verified_lname = _a($verified_lname); 
		$verified_mobile = _a($verified_mobile); 

			if($_FILES['verified_idcard']['error']==0){
				$dot	=	strtolower(end(explode('.',$_FILES['verified_idcard']['name'])));
				$pname = time().rand(10,99).".$dot";
				$ori = "$verified_src/idcard_".$pname;
				@copy($_FILES['verified_idcard']['tmp_name'],$ori);
				$sverified_idcard = ", verified_idcard='".$pname."' ";
			}

			if($_FILES['verified_idphoto']['error']==0){
				$dot	=	strtolower(end(explode('.',$_FILES['verified_idphoto']['name'])));
				$pname = time().rand(10,99).".$dot";
				$ori = "$verified_src/idphoto_".$pname;
				@copy($_FILES['verified_idphoto']['tmp_name'],$ori);
				$sverified_idphoto = ", verified_idphoto='".$pname."' ";
			}

			if($_FILES['verified_slip']['error']==0){
				$dot	=	strtolower(end(explode('.',$_FILES['verified_slip']['name'])));
				$pname = time().rand(10,99).".$dot";
				$ori = "$verified_src/slip_".$pname;
				@copy($_FILES['verified_slip']['tmp_name'],$ori);
				$sverified_slip = ", verified_slip='".$pname."' ";
			}
 
			@_q("UPDATE `gth_user_data` SET verified_fname='$verified_fname', verified_lname='$verified_lname', verified_mobile='$verified_mobile', verified_date=NOW(), date_update=NOW() $sverified_idcard $sverified_idphoto $sverified_slip WHERE iduser='$logiduser' LIMIT 1");

			@dolog($ac); 
			$_SESSION['uaction'][$ac]="ok";
			redirect(mpath($loguser).'/verify');
		exit;
	break; 
	case "signup":

		$email = trim(strtolower($pemail));
		$user = trim(strtolower($puser)); 
		$psw = trim($ppsw);
		$md5psw = md5($psw); 
  
		$r	=	mysql_fetch_array(mysql_query("select * from `gth_user` a INNER JOIN `gth_user_data` b USING(iduser) where (a.email='$email' || a.user='$user') limit 1"));
		if(!$r['iduser']){
			
			$userpath = upath($user);
			@mkdir($userpath, 0777, true);
			
			if($_FILES['pic']['error']==0){
				$dot	=	strtolower(end(explode('.',$_FILES['pic']['name'])));
				$pname = "$user.$dot";
				$ori = "$userpath/ori_".$pname;
				@copy($_FILES['pic']['tmp_name'],$ori); 
				@crop($ori, "$userpath/n_".$pname, 50, 50, $pname);
				@resizeScale($ori, "$userpath/t_".$pname, 200, $pname);
			}


			mysql_query("INSERT INTO `gth_user` (`idtype`, `email`, `user`, `psw`, `md5psw`, `pic`, `date_create`) VALUES ('$idtype', '$email', '$user', '$psw', '$md5psw', '$pname', NOW());");

			$uid = mysql_insert_id();
			mysql_query("INSERT INTO `gth_user_data` (`iduser`, `gender`, `fb_id`, `fb_user`, `fb_access_token`, `fb_link`) VALUES ('$uid', '$gender', '$fb_id', '$fb_user', '$fb_access_token', '$fb_link');");
  
			$r	=	mysql_fetch_array(mysql_query("select * from `gth_user` a INNER JOIN `gth_user_data` b USING(iduser) where a.iduser='$uid' limit 1"));
			unset($_SESSION['error']);
			$setcook=time()+(86400*31);
			setcookie("cookiduser",$r['iduser'],$setcook,"",".graduateth.com"); 
			_q("UPDATE `gth_user` SET date_login=NOW() WHERE iduser='".$r['iduser']."' LIMIT 1");
		
			@dolog($ac,"$r[iduser]");
			@chmod($userpath, 0755);
  
			include('a_curl.inc.php');
			$url = 'https://graph.facebook.com';
			$url .= '/'.$fb_user.'/feed';
			if($idtype==1){
				$tname = 'ช่างภาพ';
			}elseif($idtype==2){
				$tname = 'ช่างแต่หน้าทำผม';
			}
			$message = 'กำลังสร้างหน้าโปรไฟล์'.$tname.'ใหม่ บน '.PRO_NAME.' มาดูกัน 
			'.mpath($r['user']);
			
			$post = 'message='.$message.'&access_token='.$_COOKIE['fb_access_token'];
			$data = get_curl($url,$cookie,$post,'',true);


				$html = "สวัสดี คุณ $r[user]<br><br>";
				$html .= "ยินดีต้อนรับสู่ ".PRO_NAME." ".PRO_TITLE."<br>";
				$html .= "คุณสามารถเข้าสู่ระบบ ได้ตามรายละเอียดด้านล่างนี้<br>";
				$html .= "--------------------------------------------- <br>";
				$html .= "อีเมล : $r[email] <br>";
				$html .= "รหัสผ่าน : $r[psw] <br>";
				$html .= "--------------------------------------------- <br><br>";
				$html .= "".PRO_NAME." <br>";
				$html .= "<a href='".PRO_URL."'>".PRO_URL."</a> <br><br>";
				$html .= "** กรุณาอย่าตอบกลับ อีเมลนี้ หากคุณต้องการข้อมูลเพิ่มเติม กรุณาส่งมาที่ info@graduateth.com";
				
				$message = dataopenfile("mailform.html");
				$message = eregi_replace("{MESSAGE}",$html,$message);

				$to = $email; 
				$from = "noreply@graduateth.com";
				$from_name = PRO_NAME." [NOREPLY]";
				$subject = "ยินดีต้อนรับสู่ ".PRO_NAME;
				//smtpSendMail($to, $from, $from_name, $subject, $html);
				if($to) SendMail($to,PRO_NAME." <noreply@graduateth.com>",$subject,$message); 
				@SendMail("info@graduateth.com",PRO_NAME." <noreply@graduateth.com>","CC:".$subject,$message); 

			redirect(mpath($user));

		}else{
  
			unset($_SESSION['error']);
			$setcook=time()+(86400*31);
			setcookie("cookiduser",$r['iduser'],$setcook,"",".graduateth.com");  
 
			redirect(mpath($user));
 
		}

	exit;
	break;    
	case "forgot-password":
			
		$user = trim(strtolower($user)); 
		$r	=	mysql_fetch_array(mysql_query("select * from gth_user where user='$user' OR email='$user' limit 1"));
		if($r['email'] && $r['psw']){
				$html = "สวัสดี คุณ $r[user]<br><br>";
				$html .= "ทางเราได้ส่งรหัสผ่านมาให้คุณตามคำร้องขอ 'ลืมรหัสผ่าน ?' <br>";
				$html .= "คุณสามารถเข้าสู่ระบบ ไทยมัลติพลาย ได้ตามรายละเอียดด้านล่างนี้<br>";
				$html .= "--------------------------------------------- <br>";
				$html .= "อีเมล : $r[email] <br>";
				$html .= "รหัสผ่าน : $r[psw] <br>";
				$html .= "--------------------------------------------- <br><br>";
				$html .= "".PRO_NAME." <br>";
				$html .= "<a href='".PRO_URL."'>".PRO_URL."</a> <br><br>";
				$html .= "** กรุณาอย่าตอบกลับ อีเมลนี้ หากคุณต้องการข้อมูลเพิ่มเติม กรุณาส่งมาที่ info@graduateth.com";
				
				$message = dataopenfile("mailform.html");
				$message = eregi_replace("{MESSAGE}",$html,$message);

				$to = $r['email'];   
				$from = "noreply@graduateth.com";
				$from_name = PRO_NAME." [NOREPLY]";
				$subject = PRO_NAME." : รายละเอียดสำหรับเข้าสู่ระบบ";

				if($to) SendMail($to,PRO_NAME." <noreply@graduateth.com>",$subject,$message); 
				@SendMail("info@graduateth.com",PRO_NAME." <noreply@graduateth.com>","CC:".$subject,$message);

				unset($_SESSION['error']);
				@dolog("forgotpassword","$r[iduser]");
				redirect(_path("/$page/ok&e=$r[email]"));

		}else{

			if(!$r['psw']){
				$_SESSION['error']['forgot_fbuser'] = $r['fbuser'];
			}
				$_SESSION['error']['forgot'] = "1";
				redirect(_path("/$page"));
		} 
	exit; 
	break;   
	case "user-setting":
 
		$title = _a($title); 
		$excerpt = _a($excerpt);  
		$detail = eregi_replace(chr(13),"<br>",_a($detail));   
		$keyword = _a($keyword); 
 
		@dolog($ac);
		@_q("UPDATE gth_user_data SET title='$title', excerpt='$excerpt', detail='$detail', keyword='$keyword', date_update=NOW() WHERE iduser='$logiduser' LIMIT 1");
		$_SESSION['uaction'][$ac]="ok";
		redirect(mpath($loguser).'/'.$ide.'#user-setting');

	exit; 
	break;   
	case "user-contact":
 
		$social_facebook = _a($social_facebook);  
		$social_twitter = _a($social_twitter);  
		$social_flickr = _a($social_flickr);  
		$social_gplus = _a($social_gplus);  
		$social_pinterest = _a($social_pinterest);   
		$social_line = _a($social_line);  
		$social_email = _a($social_email);  
		$social_website = _a($social_website);  
		$social_phone = _a($social_phone);  
 
		@dolog($ac);
		@_q("UPDATE gth_user_data SET social_facebook='$social_facebook', social_twitter='$social_twitter', social_flickr='$social_flickr', social_gplus='$social_gplus', social_pinterest='$social_pinterest', social_line='$social_line', social_email='$social_email', social_website='$social_website', social_phone='$social_phone', date_update=NOW() WHERE iduser='$logiduser' LIMIT 1");
		$_SESSION['uaction'][$ac]="ok";
		redirect(mpath($loguser).'/'.$ide.'#user-contact');

	exit; 
	break;   
	case "user-jobs":
 
		$price_pre_h = _t($price_pre_h);  
		$price_pre_f = _t($price_pre_f); 
		$price_day_h = _t($price_day_h);  
		$price_day_f = _t($price_day_f);  
		$price_out_h = _t($price_out_h);  
		$price_out_f = _t($price_out_f);      
		$price_hire = _t($price_hire);      

		$text_pre = _t($text_pre);        
		$text_day = _t($text_day);        
		$text_hire = _t($text_hire);        
		$text_out = _t($text_out); 
		
		$text_pre = eregi_replace(chr(13),"<br>",$text_pre);        
		$text_day = eregi_replace(chr(13),"<br>",$text_day);        
		$text_hire = eregi_replace(chr(13),"<br>",$text_hire);        
		$text_out = eregi_replace(chr(13),"<br>",$text_out); 

 
		@dolog($ac);
		@_q("UPDATE gth_user_data SET price_pre_h='$price_pre_h', price_pre_f='$price_pre_f', price_day_h='$price_day_h', price_day_f='$price_day_f', price_out_h='$price_out_h', price_out_f='$price_out_f', price_hire='$price_hire', text_pre='$text_pre', text_day='$text_day', text_hire='$text_hire', text_out='$text_out', date_update=NOW() WHERE iduser='$logiduser' LIMIT 1"); 
		$_SESSION['uaction'][$ac]="ok";
		redirect(mpath($loguser).'/'.$ide.'#user-jobs');

	exit; 
	break;   
	case "user-equip-add": 
		_q("INSERT INTO `gth_user_equip` (`idequip`, `iduser`, `date_create`) VALUES ('".$idequip."','".$logiduser."', NOW());"); 
		@dolog($ac); 
		$_SESSION['uaction'][$ac]="ok";
		redirect(mpath($loguser).'/equip');

	exit; 
	break;   
	case "user-equip-delete":
 
			_q("DELETE FROM `gth_user_equip` WHERE id='".$id."' "); 
			redirect(mpath($loguser).'/equip');

	exit; 
	break;   
	case "user-portfolio":
		$wh = "";
		$userpath = upath($loguser);
		for($i=1;$i<$portno;$i++){
				if(!$_FILES['port'.$i]['error']){
					$dot	=	strtolower(end(explode('.',$_FILES['port'.$i]['name'])));
					$pname = time().$i.".$dot";
					$ori = "$userpath/ori_".$pname;
					@copy($_FILES['port'.$i]['tmp_name'],$ori);
					@crop($ori, "$userpath/50_".$pname, 50, 50, $pname);
					@resizeScale($ori, "$userpath/220_".$pname, 220, $pname);
					@resizeScale($ori, "$userpath/1024_".$pname, 1024, $pname);
					@resizeScale($ori, "$userpath/1920_".$pname, 1920, $pname); 
					_q("INSERT INTO `gth_portfolio` (`iduni`, `iduser`, `pic`, `date_create`) VALUES ('".$iduni."','".$logiduser."','".$pname."',NOW());");
				} 
		} 
 
		@dolog($ac); 
		$_SESSION['uaction'][$ac]="ok";
		redirect(mpath($loguser).'/portfolio');

	exit; 
	break;   
	case "user-portfolio-delete":

			unlink(upath($loguser).'/50_'.$pic);
			unlink(upath($loguser).'/220_'.$pic);
			unlink(upath($loguser).'/1024_'.$pic);
			unlink(upath($loguser).'/1920_'.$pic); 
			_q("DELETE FROM gth_portfolio WHERE id='".$id."' "); 
			redirect(mpath($loguser).'/portfolio');

	exit; 
	break;   
	case "user-portfolio-delete-all":

		$sql2 = "SELECT * FROM gth_portfolio WHERE iduser='$logiduser' AND iduni='$iduni' ORDER BY id DESC"; 
		$re2 = mysql_query($sql2);
		while($r2 = mysql_fetch_array($re2)){
			$pic = $r2['pic'];
			unlink(upath($loguser).'/50_'.$pic);
			unlink(upath($loguser).'/220_'.$pic);
			unlink(upath($loguser).'/1024_'.$pic);
			unlink(upath($loguser).'/1920_'.$pic); 
		}
		_q("DELETE FROM gth_portfolio WHERE iduser='$logiduser' AND iduni='$iduni' ");
		redirect(mpath($loguser).'/portfolio');

	exit; 
	break;   
	case "user-slide":
		$wh = "";
		$userpath = upath($loguser);
		for($i=1;$i<=$slideno;$i++){
				if($_FILES['slide'.$i]['error']==0){
					$dot	=	strtolower(end(explode('.',$_FILES['slide'.$i]['name'])));
					$pname = time().$i.".$dot";
					$ori = "$userpath/ori_".$pname;
					@copy($_FILES['slide'.$i]['tmp_name'],$ori);
					@resizeScale($ori, "$userpath/220_".$pname, 220, $pname);
					@resizeScale($ori, "$userpath/1024_".$pname, 1024, $pname);
					@resizeScale($ori, "$userpath/1920_".$pname, 1920, $pname);
					$wh .= " , slide".$i."='".$pname."' ";
				}
		}

		@dolog($ac);
		@_q("UPDATE gth_user_data SET date_update=NOW() $wh WHERE iduser='$logiduser' LIMIT 1");
		$_SESSION['uaction'][$ac]="ok";
		redirect(mpath($loguser).'/'.$ide);


	exit; 
	break;   
	case "user-display":
		$wh = "";
		$userpath = upath($loguser);
 
				if($_FILES['pic']['error']==0){
					$dot	=	strtolower(end(explode('.',$_FILES['pic']['name']))); 
					$pname = "$loguser.$dot";
					$ori = "$userpath/ori_".$pname;
					@copy($_FILES['pic']['tmp_name'],$ori);  
					@crop($ori, "$userpath/n_".$pname, 50, 50, $pname);
					@resizeScale($ori, "$userpath/t_".$pname, 200, $pname);
					$wh .= " , pic='".$pname."' "; 
				}

		@dolog($ac);
		@_q("UPDATE gth_user SET date_update=NOW() $wh WHERE iduser='$logiduser' LIMIT 1");
		$_SESSION['uaction'][$ac]="ok";
		redirect(mpath($loguser).'/'.$ide.'#user-display');
 
	exit; 
	break;   
	case "user-slide-delete":
		
		$userpath = upath($loguser);
		unlink($userpath.'/ori_'.$pic);
		unlink($userpath.'/220_'.$pic);
		unlink($userpath.'/1920_'.$pic); 
		$_SESSION['uaction'][$ac]="ok";
		@_q("UPDATE gth_user_data SET date_update=NOW(), slide".$pid."='' WHERE iduser='$logiduser' LIMIT 1");
 
	exit; 
	break;   
	case "user-schedule-add":
		$title = _a($title);
		$location = _a($location);
		@_q("INSERT INTO `gth_schedule` (`iduni`, `type`, `title`,`location`,`date_schedule`, `date_create`) VALUES ('$iduni', '$type','$title', '$location', '$date_schedule', NOW() );");
		redirect(mpath($loguser).'/schedule#user-schedule');

	exit; 
	break;   
	case "user-schedule-update":
 
		$title = _a($title);
		$location = _a($location);
		@_q("UPDATE `gth_schedule` SET `iduni`='$iduni', `type`='$type', `title`='$title', `location`='$location', `date_schedule`='$date_schedule', `date_create`=NOW() WHERE id='$idx' ");
		redirect(mpath($loguser).'/schedule#user-schedule');

	exit; 
	break;   
	case "user-schedule-accept":

		list($num) = mysql_fetch_row(mysql_query("SELECT count(*) as count FROM `gth_que` WHERE `idschedule`='$idschedule' AND `iduser`='$logiduser' "));
		if(!$num){
		@_q("INSERT INTO `gth_que` ( `idschedule`, `iduser`, `date_create`) VALUES ('$idschedule', '$logiduser', NOW() );");
		}else{
		@_q("UPDATE `gth_que` SET date_create=NOW() WHERE `idschedule`='$idschedule' AND `iduser`='$logiduser' ");
		}

	exit; 
	break;   
	case "user-que-delete":
  
		@_q("DELETE FROM `gth_que` WHERE id='$idx' AND iduser='$logiduser' "); 
		$_SESSION['uaction'][$ac]="ok";
		redirect(mpath($loguser).'/que#user-que');

	exit;
	break;  
	case "setting-seo":
 
		$c_meta_title				= _ahtml($c_meta_title);  
		$c_meta_description	= _ahtml($c_meta_description);  
		$c_meta_keyword		= _ahtml($c_meta_keyword);  
		$c_google_analytics	= _ahtml($c_google_analytics);

		$c_google_webmaster_tools = StripSlashes($c_google_webmaster_tools);
		$c_google_webmaster_tools = eregi_replace("(.*)content=\"","",$c_google_webmaster_tools);
		$c_google_webmaster_tools = eregi_replace("\"(.*)","",$c_google_webmaster_tools);

		@dolog($ac); 
 
		@_q("UPDATE mty_member_config SET c_meta_title='$c_meta_title', c_meta_description='$c_meta_description', c_meta_keyword='$c_meta_keyword', c_google_analytics='$c_google_analytics', c_google_webmaster_tools='$c_google_webmaster_tools',  date_update=NOW() WHERE iduser='$logmem[iduser]' LIMIT 1");
		$_SESSION['getaction']['config']="ok";
		redirect(MEM_URL."$page");

	exit;
	break;  
	case "setting-google-analytics":
 
		$c_google_analytics = _a($c_google_analytics);  

		@dolog($ac); 
 
		@_q("UPDATE mty_member_config SET c_google_analytics='$c_google_analytics', date_update=NOW() WHERE iduser='$logmem[iduser]' LIMIT 1");
		$_SESSION['getaction']['config']="ok";
		redirect(MEM_URL."$page");

	exit;
	break;  
	case "setting-google-webmaster-tools": 

		$c_google_webmaster_tools = StripSlashes($c_google_webmaster_tools);
		$c_google_webmaster_tools = eregi_replace("(.*)content=\"","",$c_google_webmaster_tools);
		$c_google_webmaster_tools = eregi_replace("\"(.*)","",$c_google_webmaster_tools);


		@dolog($ac); 
 
		@_q("UPDATE mty_member_config SET c_google_webmaster_tools='$c_google_webmaster_tools', date_update=NOW() WHERE iduser='$logmem[iduser]' LIMIT 1");
		$_SESSION['getaction']['config']="ok";
		redirect(MEM_URL."$page"); 
 
	exit;
	break;  
	case "follow": 
		_q("INSERT INTO gth_follow (idowner, iduser, date_create) VALUES ('$u', '".$logiduser."', NOW())");
		@doalert($u,$logiduser,'follow'); // คนที่โดนทำ, คนที่ทำ, ทำอะไร
	exit;
	break;
	case "unfollow": 
		_q("DELETE FROM gth_follow WHERE idowner='$u' AND iduser='$logiduser' ");
	exit;
	break;
	case "clearalert": 
		_q("UPDATE gth_alert SET status='Y' WHERE idowner='$logiduser' ");
	exit;
	break;
	case "checkalert": 
		list($numalert) = mysql_fetch_row(mysql_query("SELECT count(*) as count FROM gth_alert WHERE idowner='$logiduser' AND status='N' LIMIT 1"));
		echo $numalert ; 
	exit;
	break; 
	case "newalert":
		$asql = "SELECT a.section as section, b.* FROM gth_alert a INNER JOIN gth_user b USING(iduser) WHERE a.idowner='$logiduser' AND a.section='follow' AND a.status='N' ORDER BY a.date_create DESC"; 
		$are = mysql_query($asql);
		while($ar = mysql_fetch_array($are)){ 
		?>
								<li class="notify">
									<a href="<?echo mpath($ar['user']);?>"> 
										<p><b><?echo $ar['user']?></b> ติดตามคุณ</p> 
										   <span class="badge badge-small badge-info"><?echo timeDiff($ar['date_create']);?></span>
									</a>
								</li>
		<?
		}
	exit;
	break; 
}
?>
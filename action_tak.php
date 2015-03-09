<?php
 function saveFoodData($data)
 {
	 if (!isset($data['file_name']) || !isset($data['name']) || !isset($data['shop_name'])) {
		 return false;
	 }
	 
	$sql = "insert into foods (name,price,shop_name,lat,lng,file_name,created_at,updated_at) 
	 		values ('".$data['name']."','".$data['price']."','".$data['shop_name']."','".$data['lat']."','".$data['lng']."','".$data['file_name']."','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."') ";
	 $query = mysql_query($sql)or die(mysql_error());
	 $id = mysql_insert_id();
	 if ($id > 0 ) {
		 $user_id = 1;
		 $pname = $data['file_name'];
		 $userpath = 'img/'.$user_id;
		 $ori = "$userpath/ori_".$pname;
		 $tmp_file = "tmp/".$pname;
		 @copy($tmp_file, $ori);		 
		 @crop($ori, "$userpath/70_".$pname, 70, 70, $pname);
		 @resizeScale($ori, "$userpath/360_".$pname, 360, $pname);
		 @resizeScale($ori, "$userpath/800_".$pname, 800, $pname);
		 @resizeScale($ori, "$userpath/1200_".$pname, 1200, $pname); 
		 return $id;
	 }
	 
	 return false;
	 
 }
 
 function saveContactInfomation($data)
 {
	 if (!isset($data['email']) || !isset($data['full_name']) || !isset($data['message'])) {
		 return false;
	 }
	 
	$sql = "insert into contact_us (email,full_name,phone,message,created_at,updated_at) 
	 		values ('".$data['email']."','".$data['full_name']."','".$data['phone']."','".$data['message']."','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."') ";
	 $query = mysql_query($sql)or die(mysql_error());
	 $id = mysql_insert_id();
	 if ($id > 0) {
		 		/*$html = "ถึง Admin <br><br>";
				$html .= "คุณ".$data['first_name']." ".$data['last_name']." <br>";
				$html .= "ข้อความได้ตามด้านล่างนี้<br>";
				$html .= "--------------------------------------------- <br>";
				$html .= "".$data['message']."";
				$html .= "--------------------------------------------- <br><br>";
				$html .= "อีเมล ".$data['email']."";
				
				$message = $html;
				$to = 'wilasinee45@gmail.com';   
				$subject = PRO_NAME." : มีคนติดต่อสอบถามข้อมูล";

				if($to) SendMail($to,PRO_NAME." <noreply@ideaslunch.com>",$subject,$message); */
		 return $id;
	 }
	 
	 return false;
 }
 
 function alert($var, $die=false) 
 {
	 echo '<pre>';
	 print_r($var);
	 echo '</pre>';
	 if ($die == true) {
		 exit();
	 }
 }

?>
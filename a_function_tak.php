<?php

 function getContentsList()
 {
	$content = array();
	
	$tags = mysql_query("select * from relate_tags order by id desc");
	while($tag = mysql_fetch_array($tags)) {
		$data_tags[$tag['content_id']][] = 'tag-'.$tag['tag_id'];
	}

	$query = mysql_query("select * from foods where status='0' order by id desc ");
	while( $rec = mysql_fetch_array($query)) {
		$rec['tags'] = isset($data_tags[$rec['id']])?$data_tags[$rec['id']]:array();
		$content[] = $rec;
	}
	
	return $content;
	 
 }
 

 function saveFoodData($data)
 {
	 if (!isset($data['file_name']) || !isset($data['name']) || !isset($data['shop_name'])) {
		 return false;
	 }
	 
	 
	$sql = "insert into foods (name,price,shop_name,lat,lng,file_name,tags,created_at,updated_at) 
	 		values ('".$data['name']."','".$data['price']."','".$data['shop_name']."','".$data['lat']."','".$data['lng']."','".$data['file_name']."','".$data['tags']."','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."') ";
	 $query = mysql_query($sql)or die(mysql_error());
	 $id = mysql_insert_id();
	 manageTags($id, $data['tags']);

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
 
 function manageTags($id, $tags='')
 {
	 if ($tags) {
		$arr_tag = explode(',', $tags);
		if (is_array($arr_tag)) {
			foreach ($arr_tag as $tag) {
				$tag = trim($tag);
				$tag_id = getTagMain($tag);
				if ($tag_id > 0) {
					addTagRelate($tag_id, $id);
				} else {
					$tag_id = addTagMain($tag);
					addTagRelate($tag_id, $id);
				}
			}
		} // is_array 
	 }// end tags
 }
 
 function getTagMain($tag)
 {
	 $tag_id = 0;
	 $tag = mysql_fetch_array(mysql_query("select id from main_tags where name='".$tag."' "));
	 if (isset($tag['id'])) {
		 $tag_id = $tag['id'];
	 }
	 return $tag_id;
	 
 }
 
 function addTagMain($tag)
 {
	$sql_add_tag = "insert into main_tags(name) values('".$tag."')";
	$query = mysql_query($sql_add_tag);
	if ($query) {
		return mysql_insert_id();
	}
	 
	return 0;
	 
 }
 
 function addTagRelate($tag_id, $id)
 {
	$sql_add_relate_tags = "insert into relate_tags(tag_id,content_id) values('".$tag_id."','".$id."')";
	$query = mysql_query($sql_add_relate_tags);
	if ($query) {
		return mysql_insert_id();
	}
	 
	return 0;
 }
 
 
 function getTagSugguest($response= 'text')
 {
	$feed = array();
	$query = mysql_query("select * from main_tags where status='1' order by id ");
	while( $rec = mysql_fetch_array($query)) {
		$feed[] = $response=='text'?$rec['name']:$rec;
	}
	
	if ($response=='text') {
		if (is_array($feed)) {
			$tags_text = implode("','", $feed);
			$tags_text = "'".$tags_text."'";
		}
		return $tags_text;
		
	}
	return $feed;

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
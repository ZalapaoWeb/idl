<?php

/*************************************************

 *	PROJECT:    MySpace.com Scripts
 *	DOCUMENT:	Curl Functions
 *	AUTHOR:		Masood Ahmed
 *	E-MAIL: 	masood@sigmavisual.com
 * 	PRODUCT OF:	Sigma Visual Technologies, Pakistan.
 *	COPYRIGHT:	(c)2005 - 2006
 *	WEB:		http://www.sigmavisual.com/

 *************************************************/

function get_curl($url,$cookie_path="",$postfileds="",$referrer="",$header="")
{
	global $curlstatus;
	$agent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6";
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL,$url);    
	curl_setopt($ch, CURLOPT_USERAGENT, $agent);    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);     
	//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 

	if($referrer!="")
	{		
		curl_setopt($ch, CURLOPT_REFERER, $referrer);  
	}  
	
	if($cookie_path!="")    
	{
		
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
	}    

	if($postfileds!="")    
    {    	
		curl_setopt($ch, CURLOPT_POST, 1.1);     
		curl_setopt($ch, CURLOPT_POSTFIELDS,$postfileds);     
	}    

	if($header!="")   
	{ 
		curl_setopt($ch, CURLOPT_HEADER, 1); 
	}   

	$result = curl_exec ($ch);    
	$curlstatus=curl_getinfo($ch);
	curl_close ($ch);
	return $result;    
	
}



function get_hidden($html)
{
	preg_match_all('|<input[^>]+type="hidden"[^>]+name\="([^"]+)"[^>]+value\="([^"]*)"[^>]*>|',$html,$input,PREG_SET_ORDER);
	return $input;
}

function get_hidden2($html)
{
	preg_match_all('/<input type\="hidden" name\="([^"]+)".*?value\="([^"]*)"[^>]*>/si',$html,$input,PREG_SET_ORDER);
	return $input;
}

function use_hidden($input)
{
	$par=null;
	foreach($input as $eachinput){$par.="&".urlencode(html_entity_decode(@$eachinput[1]))."=".urlencode(html_entity_decode(@$eachinput[2]));}
	return $par;
}

?>
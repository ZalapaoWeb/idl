<?php
if(basename($PHP_SELF)=="action_tak.php") include("a_inc.php");
@header("Content-Type:text/html;charset=".CHARSET);

 $action = isset($_POST['action'])?$_POST['action']:'';
 
 switch ($action) {
    case 'sendPost':
        $food_id = saveFoodData($_POST);
		echo $food_id;
        break;
 } 

?>
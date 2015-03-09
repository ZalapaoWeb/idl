<?php
@mysql_pconnect(DB_HOST,DB_USER,DB_PSW)  or die ("you can not connect to database, please check you username/password  or contact to administrator.");
@mysql_select_db(DB_NAME) or die ("database does not exist, please check your database name or contact to administrator.");
@mysql_query("SET NAMES UTF8"); 
?>
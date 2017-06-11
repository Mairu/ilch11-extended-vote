<?php 
include('include/includes/config.php');

$dz = mysql_connect(DBHOST, DBUSER, DBPASS);
mysql_select_db(DBDATE, $dz);

mysql_query("ALTER TABLE ".DBPREF."poll ADD user_rechte VARCHAR( 10 ) NOT NULL ;");

mysql_close();

?>


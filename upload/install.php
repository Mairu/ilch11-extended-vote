<?php 
define('main',true);
include('include/includes/config.php');
include('include/includes/func/db/mysql.php');

db_connect();

$inst = 0;
$r = db_query("SHOW FULL COLUMNS FROM `prefix_poll`");
while ($a = db_fetch_assoc($r)) {
 switch ($a['Field']) {
 case 'user_rechte': $inst += 1;   break;
 case 'groups'    : $inst += 2;   break;
 case 'view'      : $inst += 4;   break;
 default:                         break;
 }
}

switch ($inst) {
  case 0: //new
  $sql = db_query("ALTER TABLE `prefix_poll` ADD `user_rechte` VARCHAR( 10 ) NOT NULL, ADD `groups` VARCHAR( 255 ) NOT NULL, ADD `view` TINYINT( 2 ) NOT NULL DEFAULT '0' ;");
  break;  
  case 1: //1.0 -> 1.1
  $sql = db_query("ALTER TABLE `prefix_poll` ADD `groups` VARCHAR( 255 ) NOT NULL, ADD `view` TINYINT( 2 ) NOT NULL DEFAULT '0' ;");
  break;
  case 3: //1.1 -> 1.2
  $sql = db_query("ALTER TABLE `prefix_poll` ADD `view` TINYINT( 2 ) NOT NULL DEFAULT '0' ;");
  break;
  case 7: //1.2 -> Ausgabe
  $sql = 'newest';
  break;
}

if ($sql === true) echo 'Erfolgreich installiert, install.php jetzt l&ouml;schen und kein weiteres mal aufrufen.';
elseif ($sql == 'newest') echo 'Die Datenbank ist auf dem aktuellen Stand, die install.php sollte gel&ouml;scht werden.';
else echo "Es sind Fehler aufgetreten:\n".mysql_error();

db_close();
?>


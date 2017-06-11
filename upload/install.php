<?php
//by Mairu -> Erweiterte Umfrage 1.5
define('main',true);
include('include/includes/config.php');
include('include/includes/func/db/mysql.php');

db_connect();

$fields = array();
$fields2add = array(
'user_rechte' => 'ADD `user_rechte` VARCHAR( 10 ) NOT NULL',
'groups' => 'ADD `groups` VARCHAR( 255 ) NOT NULL',
'view' => 'ADD `view` TINYINT( 2 ) NOT NULL DEFAULT \'0\'',
'exptime' => 'ADD `exptime` INT ( 10 ) NOT NULL DEFAULT \'0\'',
'answers' => 'ADD `answers` TINYINT ( 2 ) NOT NULL DEFAULT \'1\'',
'votes' => 'ADD `votes` MEDIUMINT( 7 ) NOT NULL DEFAULT \'0\'',
'maxvotes' => 'ADD `maxvotes` MEDIUMINT( 7 ) NOT NULL DEFAULT \'0\'',
);
$r = db_query("SHOW FULL COLUMNS FROM `prefix_poll`");
while ($a = db_fetch_assoc($r)) {
    $fields[] = $a['Field'];
}

foreach ($fields2add as $k => $v) {
    if (in_array($k, $fields)) {
        unset($fields2add[$k]);
    }
}

if (count($fields2add) > 0) {
    $sql = "ALTER TABLE `prefix_poll` ".implode(', ', $fields2add);
    $sql = db_query($sql);
} else {
    $sql = 'newest';
}

if (array_key_exists('votes', $fields2add)) {
	db_query('UPDATE prefix_poll,(SELECT prefix_poll_res.poll_id, SUM(res) AS res FROM prefix_poll_res GROUP BY prefix_poll_res.poll_id) AS tmp SET prefix_poll.votes = tmp.res WHERE prefix_poll.poll_id = tmp.poll_id AND prefix_poll.answers = 1');
	echo mysql_error();
	$qry = db_query('SELECT poll_id, text FROM prefix_poll WHERE answers > 1');
	while ($r = db_fetch_assoc($qry)){
		$ar = explode('#', $r['text']);
		$count = count($ar) - 1;
		if ($count < 1) {
			$count = 0;
		}
		db_query('UPDATE prefix_poll SET votes = ' . $count . ' WHERE poll_id = ' . $r['poll_id']);
	}
}

if ($sql === true) echo 'Erfolgreich installiert, install.php jetzt l&ouml;schen und kein weiteres mal aufrufen.';
elseif ($sql == 'newest') echo 'Die Datenbank ist auf dem aktuellen Stand, die install.php sollte gel&ouml;scht werden.';
else echo "Es sind Fehler aufgetreten:\n".mysql_error();

db_close();
?>
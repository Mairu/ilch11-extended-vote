<?php
// Copyright by Manuel Staechele
// Support www.ilch.de
// Modified by Mairu -> Erweiterte Umfrage 1.5
// include/boxes/vote.php
defined ('main') or die ('no direct access');
// -----------------------------------------------------------|
// Vote Sperre in Stunden
$stunden = 24;

$breite = 50;
$diftime = time() - (60 * 60 * $stunden);

$voted = array();

$fraErg = db_query('SELECT * FROM `prefix_poll` WHERE `stat` = 1 AND (`maxvotes` = 0 OR (`maxvotes` > `votes`)) AND (exptime = 0 OR exptime > UNIX_TIMESTAMP()) ORDER BY `poll_id` DESC');

if (db_num_rows($fraErg) > 0) {
    $pollid = 0;
    while ($fraRow = db_fetch_object($fraErg)) {
        if ($fraRow->recht == 2) {
            $inTextAr = $_SESSION['authid'];
        } elseif ($fraRow->recht == 1) {
            $inTextAr = getip();
        }

        $textAr = explode('#', $fraRow->text);
        if (in_array ($inTextAr , $textAr)) {
            $imPollArrayDrin = true;
        } else {
            $imPollArrayDrin = false;
        }

        if (!$imPollArrayDrin OR (count($tovote) == 0 AND $fraRow->view >= $_SESSION['authright'])) {
            if ($fraRow->recht == 2) {
                if ($fraRow->user_rechte == '') $fraRow->user_rechte = '0123456789';
                if (!empty($fraRow->groups)) {
                    $votegroups = explode('#', $fraRow->groups);
                    foreach ($_SESSION['authgrp'] as $id => $authgroup) if (in_array($id, $votegroups)) $abstimmen = true;
                    if (strpos($fraRow->user_rechte, '' . abs($_SESSION['authright'])) === false) $abstimmen = false;
                }elseif (strpos($fraRow->user_rechte, '' . abs($_SESSION['authright'])) !== false) {
                    $abstimmen = true;
                }
            } else {
                $abstimmen = true;
            }

            if ($abstimmen AND !$imPollArrayDrin) {
                $pollid = $fraRow->poll_id;
                break;
            } elseif ($fraRow->view >= $_SESSION['authright']) {
                $voted[] = $fraRow->poll_id;
            }
        }
    }
}

if ($pollid == 0 AND count($voted) > 0) {
    $pollid = $voted[array_rand($voted, 1)];
    $voted = true;
} else {
    $voted = false;
}

if ($pollid != 0) {
    $fraErg = db_query('SELECT * FROM `prefix_poll` WHERE recht ' . $woR . ' AND poll_id = ' . $pollid . ' ORDER BY poll_id DESC LIMIT 1');
    $fraRow = db_fetch_object($fraErg);

    $maxRow = db_fetch_object(db_query('SELECT MAX(res) as res FROM `prefix_poll_res` WHERE poll_id = "' . $fraRow->poll_id . '"'));
    $gesErg = db_query('SELECT SUM(res) as res FROM `prefix_poll_res` WHERE poll_id = "' . $fraRow->poll_id . '"');
    $gesRow = db_fetch_object($gesErg);

    $max = $maxRow->res;
    $ges = $gesRow->res;
    $textAr = explode('#', $fraRow->text);

    if ($fraRow->recht == 2) {
        $inTextAr = $_SESSION['authid'];
    } elseif ($fraRow->recht == 1) {
        $inTextAr = getip();
    }

    echo '<b>' . $fraRow->frage . ($fraRow->answers > 1 ? " ($fraRow->answers Antworten m&ouml;glich)" : '') . '</b>';
    if ($fraRow->exptime > 0) {
        echo '<br /><small>(bis ' . date('H.i \U\h\r - d.m.Y', $fraRow->exptime);
    	if ($fraRow->maxvotes > 0) {
    		echo ' oder bis ' . $fraRow->maxvotes . ' abgestimmt haben';
    	}
		echo ')</small>';
    } elseif ($fraRow->maxvotes > 0) {
    	echo '<br /><small>(bis ' . $fraRow->maxvotes . ' abgestimmt haben)</small>';
    }
    if (in_array ($inTextAr , $textAr) OR $fraRow->stat == 0 OR $voted) {
        echo '<table width="100%" cellpadding="0">';
        $imPollArrayDrin = true;
    } else {
        echo '<form action="index.php?vote-W' . $fraRow->poll_id . '" method="post"><input type="hidden" name="wd" value="'.$menu->get_complete().'">';
        $imPollArrayDrin = false;
    }
    $i = 0;
    $pollErg = db_query('SELECT antw, res, sort FROM `prefix_poll_res` WHERE poll_id = "' . $fraRow->poll_id . '" ORDER BY sort');
    while ($pollRow = db_fetch_object($pollErg)) {
        if ($imPollArrayDrin) {
            echo '<tr><td>' . $pollRow->antw . '</td><td align="right">' . $pollRow->res . ' (' . round($pollRow->res / ($ges > 0?$ges:1) * 100, 1) . '%)</td></tr>';
        } else {
            $i++;
            if ($fraRow->answers <= 1) {
                echo '<input type="radio" id="vote' . $i . '" name="radio" value="' . $pollRow->sort . '"><label for="vote' . $i . '"> ' . $pollRow->antw . '</label><br>';
            } else {
                echo '<input type="checkbox" id="vote' . $i . '" name="radio[]" value="' . $pollRow->sort . '"><label for="vote' . $i . '"> ' . $pollRow->antw . '</label><br>';
            }
        }
    }
    if ($imPollArrayDrin) {
        echo '<tr><td colspan="2" align="right">' . $lang['whole'] . ': &nbsp; ' . $ges . '</td></tr></table>';
    } else {
        echo '<p align="center"><input type="submit" value="' . $lang['formsub'] . '"></p></form>';
    }
} else {
    echo $lang['nowvoteavailable'];
}

?>
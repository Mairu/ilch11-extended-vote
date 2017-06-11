<?php 
#   Copyright by: Manuel Staechele
#   Support: www.ilch.de
#   Modified by Mairu -> Erweiterte Umfrage 1.3
#   include/contents/vote.php


defined ('main') or die ( 'no direct access' );




//-----------------------------------------------------------|


##
###
####
##### ins vote
$um = $menu->get(1);
$info = '';
if ($menu->getA(1) == 'W') {


	  $poll_id = escape ($menu->getE(1), 'integer');
		$fraRow = db_fetch_object(db_query("SELECT * FROM prefix_poll WHERE poll_id = '".$poll_id."'"));
    $vote = true;
    
	  if ($fraRow->answers <= 1) {
      $radio = ' = '.escape ($_POST['radio'], 'integer');
    } else {
      if ($fraRow->answers < count($_POST['radio'])) {
        $vote = false;
        $info = '<span style="color:red; font-size: 16px; font-weight:bold;">Es sind maximal '.$fraRow->answers.' Antworten m&ouml;glich</span><br />';
      } else {
        $radio = ' IN (';
        foreach ($_POST['radio'] as $k => $v) {
          $radio .= escape($_POST['radio'][$k],'integer').',';
        }
        $radio = substr($radio,0,-1).')';
      }
    }  
    
    
    if ($fraRow->exptime != 0 AND $fraRow->exptime < time()) {
      db_query("UPDATE `prefix_poll` SET stat = 0 WHERE poll_id = $poll_id");
    } else {
      $textAr = explode('#',$fraRow->text);
  	  if ($fraRow->recht == 2) {
  		  $inTextAr = $_SESSION['authid'];
  		} elseif ($fraRow->recht == 1) {
  		  $inTextAr = $_SERVER['REMOTE_ADDR'];
  		}
  		if ( !in_array ( $inTextAr , $textAr ) ) {
  			$textAr[] = $inTextAr;
  		  $textArString = implode('#',$textAr);
        db_query('UPDATE `prefix_poll` SET text = "'.$textArString.'" WHERE poll_id = "'.$poll_id.'"');
  		  db_query('UPDATE `prefix_poll_res` SET res = res + 1 WHERE poll_id = "'.$poll_id.'" AND sort '.$radio) or die (db_error());
  		}
		}
}

##
###
####
##### V o t e    Ü b e r s i c h t 

$title = $allgAr['title'].' :: '.$lang['vote'];
$hmenu = $lang['vote'];
$design = new design ( $title , $hmenu );
$design->header();
echo $info;

?>
<table width="100%" cellpadding="2" cellspacing="1" border="0" class="border">
  <tr class="Chead">
    <td><b><?php $lang['vote']; ?></b></td>
  </tr>
	
<?php

$breite = 200;
if ($_SESSION['authright'] <= -1 ) {
	  $woR = '>= "1"';
} else {
	  $woR = '= "1"';
}
$limit = 5;  // Limit
$zaehler = 0;
$page = ( $menu->getA(1) == 'p' ? $menu->getE(1) : 1 );
$MPL = db_make_sites ($page , '' , $limit , "?vote" , 'poll' );
$anfang = ($page - 1) * $limit;
$class = '';
$erg = db_query('SELECT * FROM `prefix_poll` ORDER BY stat DESC, poll_id DESC LIMIT '.$anfang.',10000000');
while ($zaehler < $limit AND $fraRow = db_fetch_object($erg)) {

	$maxRow = db_fetch_object(db_query('SELECT MAX(res) as res FROM `prefix_poll_res` WHERE poll_id = "'.$fraRow->poll_id.'"'));
	$gesRow = db_fetch_object(db_query('SELECT SUM(res) as res FROM `prefix_poll_res` WHERE poll_id = "'.$fraRow->poll_id.'"'));
	$max = $maxRow->res;
  $ges = $gesRow->res;
	$textAr = explode('#',$fraRow->text);
	
	  if ($fraRow->recht == 2) {
		  $inTextAr = $_SESSION['authid'];
		} elseif ($fraRow->recht == 1) {
		  $inTextAr = $_SERVER['REMOTE_ADDR'];
		}
		
    
    if ($fraRow->user_rechte == '') $fraRow->user_rechte = '0123456789';
		if (!empty($fraRow->groups)) {
      $votegroups = explode('#', $fraRow->groups);
		  foreach ($_SESSION['authgrp'] as $id => $authgroup) if (in_array($id, $votegroups)) $abstimmen = true;
		  if (strpos($fraRow->user_rechte,''.abs($_SESSION['authright'])) === false) $abstimmen = false;
    }
    elseif (strpos($fraRow->user_rechte,''.abs($_SESSION['authright'])) !== false) $abstimmen = true;
    else $abstimmen = false;
    
    if (( in_array ( $inTextAr , $textAr ) OR $fraRow->stat == 0) OR (!$abstimmen)) {
			$imPollArrayDrin = true;
		} elseif ($abstimmen) {
			$imPollArrayDrin = false;
		}
    
    if (!$imPollArrayDrin OR $fraRow->view >= $_SESSION['authright']) {
    $zaehler++; 
    echo '<tr><td class="Cdark"><b>'.$fraRow->frage.($fraRow->answers > 1 ? " ($fraRow->answers Antworten m&ouml;glich)" : '').'</b>';
    if ($fraRow->stat == 0 ) { echo ' (geschlossen)'; }
    elseif ($fraRow->exptime > 0) { echo ' (bis '.date('H.i \U\h\r - d.m.Y',$fraRow->exptime).')'; }
    echo '</td></tr>';
		if ( $class == 'Cnorm' ) { $class = 'Cmite'; } else { $class = 'Cnorm'; }
		echo '<tr><td class="'.$class.'">';

		if ($imPollArrayDrin) {
			  echo '<table width="100%" cellpadding="0">';
		} else {
			  echo '<form action="index.php?vote-W'.$fraRow->poll_id.'" method="POST">';
		}

    $i = 0;
		$pollErg = db_query('SELECT antw, res, sort FROM `prefix_poll_res` WHERE poll_id = "'.$fraRow->poll_id.'" ORDER BY sort');
		while ( $pollRow = db_fetch_object($pollErg) ) {
		    if ( $imPollArrayDrin ) {
	 		     if ( !empty($pollRow->res) ) {  
				      $weite = ($pollRow->res / $max) * 200;
		 		      $prozent = $pollRow->res * 100 / $ges;
		 		      $prozent = round($prozent,0);
				    } else {
		  		    $weite = 0;
					    $prozent = 0;
				    }
						$tbweite = $weite + 20;
						echo '<tr><td width="30%">'.$pollRow->antw.'</td>';
				    echo '<td width="50%">';
            /*
            '<table width="'.$tbweite.'" border="0" cellpadding="0" cellspacing="0"></td>';
						echo '<tr><td width="10" height="10"></td>';
						echo '<td width="'.$weite.'" background="include/images/vote/voteMitte.jpg" alt=""></td>';
						echo '<td width="10"><img src="include/images/vote/voteRight.jpg" alt=""></td>';
						echo '</tr></table>';*/
            echo '<div style="height: 10px; width: ' . $weite .'px; background: #3776a5 url(include/images/vote/voteMitte.png) repeat-y top left;">'.
                 '</div>';
				    
            echo '<td width="10%">'.$prozent.'%</td>';
				    echo '<td width="20%" align="right">'.$pollRow->res.'</td></tr>';
				} else {
            $i++;
            if ($fraRow->answers <= 1) {
			        echo '<input type="radio" id="vote'.$i.'" name="radio" value="'.$pollRow->sort.'"><label for="vote'.$i.'"> '.$pollRow->antw.'</label><br>';
		        } else {
              echo '<input type="checkbox" id="vote'.$i.'" name="radio[]" value="'.$pollRow->sort.'"><label for="vote'.$i.'"> '.$pollRow->antw.'</label><br>';
            }
        }
		} 
		if ( $imPollArrayDrin ) {
			  echo '<tr><td colspan="2" align="right">'.$lang['whole'].': &nbsp; '.$ges.'</td></tr></table>';
		} else {
		    echo '<p align="center"><input type="submit" value="'.$lang['formsub'].'"></p></form>';
		}
		
    echo '</td></tr>';
    }
}// end while

echo '<tr><td class="Cdark" align="center">'. $MPL .'</td></tr></table>';
$design->footer();

?>

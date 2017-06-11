<?php 
#   Copyright by: Manuel Staechele
#   Support: www.ilch.de


defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );

$design = new design ( 'Admins Area', 'Admins Area', 2 );
$design->header();

function showVote ($id) {
  
	$maxRow = db_fetch_object(db_query('SELECT MAX(res) as res FROM `prefix_poll_res` WHERE poll_id = "'.$id.'"'));
	$gesRow = db_fetch_object(db_query('SELECT SUM(res) as res FROM `prefix_poll_res` WHERE poll_id = "'.$id.'"'));
	$max = $maxRow->res;
  $ges = $gesRow->res;
	$erg = db_query('SELECT antw, res FROM `prefix_poll_res` WHERE poll_id = "'.$id.'" ORDER BY sort');
	while ($row = db_fetch_object($erg)) {
	  if ( !empty($row->res) ) {  
		  $weite = ($row->res / $max) * 200;
		  $prozent = $row->res * 100 / $ges;
		  $prozent = round($prozent,0);
		} else {
		  $weite = 0;
			$prozent = 0;
		}      
    echo '<tr><td width="30%">'.$row->antw.'</td>';
    echo '<td width="50%"><hr width="'.$weite.'" align="left"></td>';
		echo '<td width="10%">'.$prozent.'%</td>';
		echo '<td width="20%" align="right">'.$row->res.'</td></tr>';
		    
  }
	echo '<tr><td colspan="4" align="right">Gesamt: &nbsp; '.$ges.'</td></tr>';
}

function getPollRecht ( $akt ) {

  $liste = '';
  $ar = array ( 1 => 'alle' , 2 => 'registrierte' );
  foreach ($ar as $k => $v ) {
	  if ($akt == $k ) {
		  $sel = ' selected';
		} else {
		  $sel = '';
		}
		$liste .= '<option'.$sel.' value="'.$k.'">'.$v.'</option>';
	}
	return ($liste);
}

$um = $menu->get(1);
if ( $menu->get(1) == 'del' ) {
	  db_query('DELETE FROM `prefix_poll` WHERE poll_id = "'.$_GET['del'].'"');
		db_query('DELETE FROM `prefix_poll_res` WHERE poll_id = "'.$_GET['del'].'"');
}
if ( $menu->get(1) == 5 ) {
	  db_query('UPDATE `prefix_poll` SET stat = "'.$_GET['ak'].'" WHERE poll_id = "'.$_GET['id'].'"');
}

    
    
    
// A L L E   V O T E S   W E R D E N   A N G E Z E I G T			
  

    if ( isset($_POST['sub']) ) {
    	
      if ($_POST['cb1'] == 'on') $usr = '1'; else $usr = '';
			if ($_POST['cb2'] == 'on') $usr .= '2';
			if ($_POST['cb3'] == 'on') $usr .= '3';
			if ($_POST['cb4'] == 'on') $usr .= '4';
			if ($_POST['cb5'] == 'on') $usr .= '5';
			if ($_POST['cb6'] == 'on') $usr .= '6';
			if ($_POST['cb7'] == 'on') $usr .= '7';
			if ($_POST['cb8'] == 'on') $usr .= '8';
			if ($_POST['cb9'] == 'on') $usr .= '9';
			if ($_POST['poll_recht'] == 1) $usr = '';
          
	    if ( empty($_POST['vid']) ) {
		    db_query('INSERT INTO `prefix_poll` VALUES ( "" , "'.$_POST['frage'].'" , "'.$_POST['poll_recht'].'" , "1" , "" , "'.$usr.'") ' );
			  $poll_id = db_last_id(); $i = 1;
			  foreach ($_POST['antw'] as $v) {
			    if ( ! empty ($v) ) {
				   db_query('INSERT INTO `prefix_poll_res` VALUES ( "'.$i.'" , "'.$poll_id.'" , "'.$v.'" , "" ) ' );
	         $i++;
				  }
		    }
		  } else {
        db_query('UPDATE `prefix_poll` SET frage = "'.$_POST['frage'].'", recht = "'.$_POST['poll_recht'].'", user_rechte = "'.$usr.'" WHERE poll_id = "'.$_POST['vid'].'"');
			  $i = 1;
				foreach ($_POST['antw'] as $k => $v) {
				  $a = db_count_query("SELECT COUNT(*) FROM prefix_poll_res WHERE poll_id = ".$_POST['vid']." AND sort = ".$k);
					if ( $a == 0 AND $v != '' ) {
					  db_query ("INSERT INTO `prefix_poll_res` VALUES ( '".$i."' , '".$_POST['vid']."' , '".$v."' , '' )");
						$i++;
					} elseif ( $a == 1 AND $v == '' ) {
					  db_query ("DELETE FROM `prefix_poll_res` WHERE poll_id = ".$_POST['vid']." AND sort = ".$k);
					} elseif ( $a == 1 AND $v != '' ) {
					  db_query ("UPDATE `prefix_poll_res` SET antw = '".$v."', sort = ".$i." WHERE poll_id = ".$_POST['vid']." AND sort = ".$k);
						$i++;
					}
				}
      }
		} 
		if ( empty($_POST['add']) ) {
			if ( isset($_GET['vid']) ) {
			  $row1 = db_fetch_object(db_query('SELECT frage, recht, user_rechte FROM `prefix_poll` WHERE poll_id = "'.$_GET['vid'].'"'));
				$_POST['frage'] = $row1->frage;
				$_POST['poll_recht'] = $row1->recht;
				for ($i = 1; $i <= 9; $i++) if (!is_bool(strrpos($row1->user_rechte,''.$i.''))) $_POST['cb'.$i] = 'on';
       	$_POST['antw'] = array();
				$erg2 = db_query('SELECT sort,antw FROM `prefix_poll_res` WHERE poll_id = "'.$_GET['vid'].'" ORDER BY sort');
			  while ($row2 = db_fetch_object($erg2)) {
					$_POST['antw'][$row2->sort] = $row2->antw;
				}
				$_POST['vid'] = $_GET['vid'];
			} else {
			  $_POST['frage'] = '';
				$_POST['antw'] = array(1=>'');
				$_POST['poll_recht'] = '';
				$_POST['vid'] = '';
			}
		}
			$anzFeld = count($_POST['antw']);
			if ( isset ($_POST['add']) ) {
			  $anzFeld++;
				$_POST['antw'][] = '';
			}
			if ($_POST['cb1'] == 'on') $cb1c = ' checked="checked" '; else $cb1c = ' ';
			if ($_POST['cb2'] == 'on') $cb2c = ' checked="checked" '; else $cb2c = ' ';
			if ($_POST['cb3'] == 'on') $cb3c = ' checked="checked" '; else $cb3c = ' ';
			if ($_POST['cb4'] == 'on') $cb4c = ' checked="checked" '; else $cb4c = ' ';
			if ($_POST['cb5'] == 'on') $cb5c = ' checked="checked" '; else $cb5c = ' ';
			if ($_POST['cb6'] == 'on') $cb6c = ' checked="checked" '; else $cb6c = ' ';
			if ($_POST['cb7'] == 'on') $cb7c = ' checked="checked" '; else $cb7c = ' ';
			if ($_POST['cb8'] == 'on') $cb8c = ' checked="checked" '; else $cb8c = ' ';
			if ($_POST['cb9'] == 'on') $cb9c = ' checked="checked" '; else $cb9c = ' ';
						
			echo '<form action="admin.php?vote" method="POST">';
			echo '<input type="hidden" name="vid" value="'.$_POST['vid'].'" />';
      echo '<table cellpadding="0" cellspacing="0" border="0"><tr><td><img src="include/images/icons/admin/vote.png" /></td><td width="30"></td><td valign="bottom"><h1>Umfrage</h1></td></tr></table>';
      
			echo '<table width="100%" cellpadding="2" cellspacing="1" border="0" class="border">';
		  echo '<tr><td width="100" class="Cmite">Frage</td>';
		  echo '<td width="500" class="Cnorm"><input type="text" size="40" value="'.$_POST['frage'].'" name="frage"></td></tr>';
		  echo '<tr><td width="100" class="Cmite">F&uuml;r</td>';
		  echo '<td width="500" class="Cnorm"><select name="poll_recht">'. getPollRecht($_POST['poll_recht']) .'</select></td></tr>';
			
      echo '<tr><td class="Cmite">Userklassen<font class="smalfont"> (nur bei registrierte)<br />Wenn keiner ausgew�hlt ist k�nnen alle voten</font></td><td class="Cnorm">'.
      
      '<table border="0" cellpadding="0" cellspacing="0"><tr>'.
      '<td><input type="checkbox" name="cb1"'.$cb1c.'/>User</td>'.
      '<td><input type="checkbox" name="cb2"'.$cb2c.'/>Superuser</td>'.
      '<td><input type="checkbox" name="cb3"'.$cb3c.'/>Trialmember</td>'.
      '<td><input type="checkbox" name="cb4"'.$cb4c.'/>Member</td></tr><tr>'.
      '<td><input type="checkbox" name="cb5"'.$cb5c.'/>CoLeader</td>'.
      '<td><input type="checkbox" name="cb6"'.$cb6c.'/>Leader</td>'.
      '<td><input type="checkbox" name="cb7"'.$cb7c.'/>SiteAdmin</td>'.
      '<td><input type="checkbox" name="cb8"'.$cb8c.'/>CoAdmin</td>'.
      '<td><input type="checkbox" name="cb9"'.$cb9c.'/>Admin</td>'.
      '</tr></table>
      </td></tr>';
            
      
      for ($i=1;$i<=$anzFeld; $i++) {
				echo '<tr><td class="Cmite">Antwort '.$i.'</td><td class="Cnorm">';
			  echo '<input type="text" value="'.$_POST['antw'][$i].'" size="40" name="antw['.$i.']">';
			  if ( $i == $anzFeld ) {
		      echo ' &nbsp; <input type="submit" name="add" value="Antwort hinzuf&uuml;gen">';
			  }
			  echo '</td></tr>'."\n";
		  }
		  echo '<tr class="Cdark"><td></td><td><input name="sub" type="submit" value="'.$lang['formsub'].'"></td></tr>';
		  echo '</table></form>'; 
		  echo '<table width="100%" cellpadding="3" cellspacing="1" border="0" class="border">'; 
		  echo '<tr class="Chead"><td colspan="5"><b>Vote verwalten</b></td></tr>';
			?>
<script language="JavaScript" type="text/javascript">
    <!--
      
			function delcheck ( DELID ) {
			  var frage = confirm ( "Willst du diesen Eintrag wirklich l�schen?" );
				if ( frage == true ) {
				  document.location.href="?vote-del&del="+DELID;
				}
			}
		//-->
</script>
			<?php
			
			$abf = 'SELECT * FROM `prefix_poll` ORDER BY poll_id DESC';
		  $erg = db_query($abf); $class = '';
      while ($row = db_fetch_object($erg)) {
        if ($row->stat == 1) {
			    $coo = 'schliesen';
			 	  $up = 0;
		    } else {
			    $coo = '&ouml;ffnen';
			    $up = 1;
		  	}
			  if ( $class == 'Cmite' ) { $class = 'Cnorm'; } else { $class = 'Cmite'; }
			  echo '<tr class="'.$class.'">';
			  echo '<td><a  href="javascript:delcheck('.$row->poll_id.')">l&ouml;schen</a></td>';
				echo '<td><a href="?vote=0&vid='.$row->poll_id.'">&auml;ndern</a></td>';
			  echo '<td><a href="?vote-5=0&ak='.$up.'&id='.$row->poll_id.'">'.$coo.'</a></td>';				
			  echo '<td><a href="?vote=0&showVote='.$row->poll_id.'">zeigen</a></td>';
			  echo '<td>'.$row->frage.'</td>';
			  echo '</tr>';
		    if ( isset($_GET['showVote']) AND $_GET['showVote'] == $row->poll_id ) {
			    echo '<tr class="'.$class.'"><td colspan="5">';
				  echo '<table width="90%" cellpadding="0" border="0" cellspacing="0" align="right">';
				  showVote( $row->poll_id);
				  echo '</table></td></tr>';
			  }
		  }
		  echo '</table>';

$design->footer();
?>


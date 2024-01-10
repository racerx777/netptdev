<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(33);

$rule = 'undefined';
$user = getuser();
$group = getUserQueueAssignment($user);

if($_POST['update']=='Update') {
	$caid=$_POST['caid'];
	$bnum=$_POST['bnum'];
	$pnum=$_POST['pnum'];
//	notify('000','Please verify that the account updated is the same account... Testing a bug.');
}
else {
	if($_POST['exit']=='Next') {
// Unlock and dereference any caid
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		

	// The current unlock process is not unlocking all previously handled records, work around is to unlock all records that user currently has locked on every next button.
		$unlockallquery = "
			UPDATE collection_queue
			SET lockuser=NULL
			WHERE lockuser='$user'
		";
		if($unlockallresult=mysqli_query($dbhandle,$unlockallquery))
			notify('000','Existing collection queue locks released.');

		if(!empty($_POST['caid'])) {
			if($rowid=unlockrow($dbhandle, 'collection_queue', 'cqcaid', $_POST['caid']))
				notify('000','Collection queue record was successfully unlocked.');
			else
				error('000','Collection queue record was NOT successfully unlocked. caid:'.$_POST['caid']);
			unset($_POST['exit']);
			unset($_SESSION['id']);
			unset($caid);
	// unpost all values except required values
			foreach($_POST as $key=>$val) {
				unset($_POST["$key"]);
			}
		}
	}

	// Select Call Record $caid
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	

	if(isset($_SESSION['id']))
		$caid=$_SESSION['id'];
	// if id is provided, then retrieve current record and display
	if(!empty($caid)) {
		$rule = 'id passed in';
		$q1query  = "SELECT cqcaid FROM collection_queue where cqcaid='$caid' limit 1";
		if($q1result = mysqli_query($dbhandle,$q1query)) {
			if($q1row = mysqli_fetch_assoc($q1result)) {
	// Update LockUser and LockTime
				if($caid = lockrow($dbhandle, 'collection_queue', 'cqcaid', $q1row['cqcaid'])) {
	//				echo($caid);
				}
				else
					error("002", "Could not get record lock.<br>$q1query<br>".mysqli_error($dbhandle));
			}
			else {
	// No call Record, Set Status to PEN and Create Call record
				error("090", "No Call Queue Entry.");
				unset($caid);
			}
		}
		else
			error("001", "query:" . $q1query . "<br>Error:" . mysqli_error($dbhandle));
	}

	if(empty($caid)) {
	//
	// Check for records that are locked by the current user and need to be called (schcalldate)
	//
		$q2query  = "
			SELECT cqcaid
			FROM collection_queue
			WHERE lockuser = '$user'
			AND cqschcalldate < (NOW() + INTERVAL 5 MINUTE)
			LIMIT 1
			";
//	 dump("q2query",$q2query);
		if($q2result = mysqli_query($dbhandle,$q2query)) {
			if($q2row = mysqli_fetch_assoc($q2result)) {
	// Update LockUser and LockTime
				if($caid = lockrow($dbhandle, 'collection_queue', 'cqcaid', $q2row['cqcaid'])) {
					$rule = 'locked '.$user;
	//				echo($caid);
				}
				else
					error("001",$q2query);
			}
	//		else
	//			error("011", "lockquery fetch:" . $lockquery . "<br>Error:" . mysqli_error($dbhandle));
		}
		else
			error("021", "query:" . $q2query . "<br>Error:" . mysqli_error($dbhandle));
	}
	/**/
	if(!empty($group)) {
		if(empty($caid)) {
		// Check for calls
        $priorityselect = "SELECT cqcaid, cqpriority, cqschcalldate FROM collection_queue ";
        $prioritywhere = "WHERE cqresult IS NULL and cqgroup='$group'";
        $priorityorderby = "ORDER BY cqpriority, cqschcalldate, cqcaid";
        //Custom Sorting for the NetPT PI Queue
        if(strpos($group, '40PIMZ')) {
            $priorityselect .= "LEFT JOIN collection_accounts ON caid = cqcaid ";
            $priorityselect .= "LEFT JOIN PTOS_Patients ON cabnum = bnum and capnum = pnum ";
            $priorityorderby = "ORDER BY acctype, cqpriority, cqschcalldate, cqcaid";
        }
	//
	// Check for primary calls - Promised Callbacks
	//
//				$priorityquery = "$priorityselect $prioritywhere AND cqpriority BETWEEN 10 AND 19 AND cqschcalldate <= NOW() - INTERVAL 5 MINUTE $priorityorderby";
				$priorityquery = "$priorityselect $prioritywhere AND cqpriority BETWEEN 10 AND 19 AND cqschcalldate <= NOW() $priorityorderby";
		//dump("priorityquery1",$priorityquery);
				if($priorityresult = mysqli_query($dbhandle,$priorityquery)) {
					while($priorityrow = mysqli_fetch_assoc($priorityresult)) {
						if($caid = lockrow($dbhandle, 'collection_queue', 'cqcaid', $priorityrow['cqcaid'])) {
							$rule = '001';
	//						notify("000", "High Priority Record Retrieved.");
	//					echo("1:".$priorityquery);
							break;
						}
					}
				}
				else
					error("002", "priorityquery:" . $priorityquery . "<br>Error:" . mysqli_error($dbhandle));
		}

		if(empty($caid)) {
	//
	// Check for secondary calls - Confirmation Letter Sent - Should HRG Upcoming Hearing and CLO Case in Chief Closed be here as well?
	//
//				$priorityquery = "$priorityselect $prioritywhere AND cqpriority BETWEEN 20 AND 29 AND cqschcalldate <= NOW() - INTERVAL 5 MINUTE $priorityorderby";
				$priorityquery = "$priorityselect $prioritywhere AND cqpriority BETWEEN 20 AND 29 AND cqschcalldate <= NOW() $priorityorderby";
	//	dump("priorityquery2",$priorityquery);
				if($priorityresult = mysqli_query($dbhandle,$priorityquery)) {
					while($priorityrow = mysqli_fetch_assoc($priorityresult)) {
						if($caid = lockrow($dbhandle, 'collection_queue', 'cqcaid', $priorityrow['cqcaid'])) {
							$rule = '002';
	//					echo("2:".$priorityquery);
							break;
						}
					}
				}
				else
					error("003", "priorityquery:" . $priorityquery . "<br>Error:" . mysqli_error($dbhandle));
		}

		if(empty($caid)) {
	//
	// Check for remaining calls - Order by Open Ar Balance tbal
	//
			$priorityorderby = "ORDER BY DATE_FORMAT(cqschcalldate,'%Y%u'), cqrtbal desc, cqcaid";
//			$priorityquery  = "$priorityselect $prioritywhere AND cqpriority > 29 AND (cqschcalldate <= NOW() - INTERVAL 5 MINUTE OR cqschcalldate IS NULL) $priorityorderby ";
//			$priorityquery  = "$priorityselect $prioritywhere AND cqpriority > 29 AND (cqschcalldate <= NOW() OR cqschcalldate IS NULL) $priorityorderby ";
			$priorityquery  = "$priorityselect $prioritywhere AND cqpriority > 29 $priorityorderby ";
	//	dump("priorityquery3",$priorityquery);
			if($priorityresult = mysqli_query($dbhandle,$priorityquery)) {
				while($priorityrow = mysqli_fetch_assoc($priorityresult)) {
					if($caid = lockrow($dbhandle, 'collection_queue', 'cqcaid', $priorityrow['cqcaid'])) {
						$rule = '003';
	//					echo("3:".$priorityquery);
						break;
					}
				}
			}
			else
				error("004", "priorityquery:" . $priorityquery . "<br>Error:" . mysqli_error($dbhandle));
		}

		if(empty($caid)) {
			$nextquery  = "$priorityselect $prioritywhere $priorityorderby";

			if($nextresult = mysqli_query($dbhandle,$nextquery)) {
				if($nextrow = mysqli_fetch_assoc($nextresult)) {
					$rule = '004';
					$nextpriority = $nextrow['cqpriority'];
					$nextschcalldate = $nextrow['cqschcalldate'];
					notify("000", "No Priority calls in queue.<br>Next priority $priority call scheduled for $nextschcalldate.");
				}
			}
		}
		else {
			$caquery="
				SELECT cabnum, cacnum, capnum
				FROM collection_accounts
				WHERE caid='$caid'
			";
			if($caresult = mysqli_query($dbhandle,$caquery)) {
				if($carow = mysqli_fetch_assoc($caresult)) {
					$_POST['bnum']=$carow['cabnum'];
					$_POST['cnum']=$carow['cacnum'];
					$_POST['pnum']=$carow['capnum'];
				}
				else
					error("901", "Error of collection_accounts fetch record.<br>$caquery<br>Error:" . mysqli_error($dbhandle));
			}
			else
				error("902", "Error on collection_accounts record query.<br>$caquery<br>Error:" . mysqli_error($dbhandle));
		}
	}
	else
		notify("000","User not assigned to a Queue group.");
}

//if(errorcount()==0 && notifycount()==0) {
if(errorcount()==0) {
	$_REQUEST['caid']=$caid;
	$_POST['appid']=$caid;
	if(empty($_POST['app']))
		$_POST['app']='collections';

	if(isuserlevel(34)) {
		$grouphref='./modules/collections/collectionsQueueListSummary.php';
//		$grouptitle = '<a href="'.$grouphref.'" target="_blank">'.$group.'</a>';
	}
	else {
		$grouphref='./modules/collections/collectionsQueueListSummary.php?group='.urlencode($group);
//		$grouphref='./modules/collections/collectionsQueueList.php?group='.urlencode($group);
//		$grouptitle = $group;
	}
		$grouptitle = '<a href="'.$grouphref.'" target="_blank">'.$group.'</a>';

// Output Work Screen
	echo('<div align="center">*** '.$grouptitle.' QUEUE PROCESSING rule:'.$rule.' id:'.$caid.' ***</div>');
	if(!empty($_REQUEST['caid'])) {
		if(errorcount()!=0 || notifycount()!=0)
			displaysitemessages();
        if ($_SESSION['user']['umuser'] == 'mtwheaterC') {
            require_once('collectionsWorkAccount.php');
        } else {
            require_once('collectionsWorkAccount.php');
        }
	}
	else
		notify("000", "No Priority calls in queue.<br>Next priority $priority call scheduled for $nextschcalldate.");
}
if(errorcount()!=0 || notifycount()!=0)
	displaysitemessages();
?>
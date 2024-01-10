<?php
function collectionsQueueUpdate($caid, $button, $date=NULL, $adddays=NULL) {
	
/*
Here's the list of items that we discussed:

1. Queue assignment groups - Geoff 
2. User ID for Vidal Solorzano - Sunni (Done see below)
3. Case Settlement Status Codes - Vidal 
4. Print each report and review for accuracy and form - Vidal
5. Typical Mail Documents Received List - Vidal 
6. Priority and Days for each Work Account Function Buttons - Vidal and Sunni (Done see below)
7. Disable Print Paperwork buttons until #10 Completed - Sunni
8. Verify Data Fields on Patient Paperwork - Sunni
9. Grid Lines on Patient Paperwork - Sunni
10. Patient Paperwork Spanish, Readmit, Walk-in (after English is approved) - Sunni

Here's the list of the Work Account Function Buttons and how they effect the queue process:

If the currently displayed account is scheduled for a callback (priority 10) and the date/time has not passed yet... and the current action is not a callback request, do not touch it ONLY allow reschedule the callback Date/Time & Note.

If the currently displayed account is NOT scheduled for a callback (priority greater than 10)...

... And the current action is:
	Confirmation Letter - Followup 2 days, at priority 20

 	Left Message - Followup 30 Days at priority 30
	Demand Letter - Followup 30 Days at priority 30
	Reconsideration Letter - Followup 30 Days at priority 30

	Pending Case - Use Drop Down Days 30/60/90, at priority 30
	Actively Treating - Use Drop Down Days 30/60/90, at priority 30

	Resubmit - Followup 45 days at priority 30

	Notes - Do not re-queue or allow Exit until another action is taken
	Proof Of Service - Do not re-queue or allow Exit until another action is taken
	Mail - Do not re-queue or allow Exit until another action is taken

	File Lien - Change Lien Status to Requested
	DOR - Change DOR Status to Requested
	Application for Adjudication - Do not re-queue or allow Exit until another action is taken
	Non-Appearance Letter - Do not re-queue or allow Exit until another action is taken


Please contact me if you have questions on/additions to any of the above.

Thanks,
Sunni
*/
// Get current collection account record.
// Determine current queue priority and next date

// If it is scheduled for a callback priority 10... and this is not a callback request, do not touch it
// If it is scheduled for a callback priority 10... and this is a callback request, update the date and time

// If this is not a callback...
//		Confirmation Letter - Default Re-Queue, at 20 priority

// 		Left Message - Re-Queue 7 Days at 30 priority

//		Notes - Default Re-Queue at 30 priority
//		Demand Letter - Default Re-Queue at 30 priority
// 		Reconsideration Letter - Default Re-Queue at 30 priority

//		Pending Case - Use Drop Down Days Selected, at 30 priority
// 		Actively Treating - Use Drop Down Days Selected at 30 priority

//		Resubmit - 

//		Proof Of Service - Do not change the re-queue, simply note proof printed
//		Mail - Do not change the re-queue, simply note mail received

// 		File Lien - Change Queue?
// 		DOR - Change Queue?
// 		Application for Adjudication - Change Queue?
// 		Non-Appearance Letter - Change Queue?

	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query="
		SELECT * 
		FROM collection_queue
		WHERE cqcaid='$caid'
	";
	if($result = mysqli_query($dbhandle,$query)) {
		if($row = mysqli_fetch_assoc($result)) {
			$priority = $row['cqpriority'];
			$schcalldate = $row['cqschcalldate'];
			$today=dbDate(date("Y-m-d H:i:s",time()));
notify("000","priority:$priority schcalldate:$schcalldate today:$today");
// Handle Callback priority calls
			if($priority <= 10 && $button=='Callback') { // Callback 
				notify("000","Account was scheduled for a Callback on $schcalldate.");
				$setpriority='10';
				$setadddays=NULL;
				$setschcalldate=$date;
				echo("1");
			}
			else {
				if($priority <=10 && $button != 'Callback' && $schcalldate > $today) {
					notify("000","Account is scheduled for a Callback on $schcalldate.");
					echo("2");
				}
				else {
// Handle High Priority Calls
					if($priority <= 20 ) { // <20 and not a callback or >=20
						echo("20");
						switch($button) {
							case 'Callback':
								$setpriority=10;
								$setadddays=NULL;
								$setschcalldate=$date;
								break;
							case 'ConfirmationLetter':
								$setpriority=20;
								$setadddays=2;
								break;
							case 'LM':
								$setadddays=7;
								break;
							case 'DemandOffer':
								$setpriority=30;
								$setadddays=30;
								break;
							case 'Resubmit':
								$setpriority=30;
								$setadddays=45;
								break;
							case 'PendingCase':
								$setpriority=30;
								$setadddays=$date;
								break;
							case 'ActivelyTreating':
								$setpriority=30;
								$setadddays=$date;
								break;
							case 'EdexRequest':
								$setpriority=NULL;
								$setadddays=1;
								break;
							default : 
								$setpriority=NULL;
								$setadddays=NULL;
								$setschcalldate=NULL;
						}
					}
					else {
						if($priority <= 30) {
							notify("000", "Priority 30 Process $button");
							switch($button) {
								case 'Callback':
									$setpriority=10;
									$setadddays=NULL;
									$setschcalldate=$date;
									break;
								case 'ConfirmationLetter':
									$setpriority=20;
									$setadddays=2;
									break;
								case 'LM':
									$setpriority=30;
									$setadddays=14;
									break;
								case 'DemandOffer':
									$setpriority=30;
									$setadddays=30;
									break;
								case 'Resubmit':
									$setpriority=30;
									$setadddays=45;
									break;
								case 'PendingCase':
									$setpriority=30;
									$setadddays=$date;
									break;
								case 'ActivelyTreating':
									$setpriority=30;
									$setadddays=$date;
									break;
								case 'EdexRequest':
									$setpriority=NULL;
									$setadddays=1;
									break;
								default : 
									$setpriority=NULL;
									$setadddays=NULL;
									$setschcalldate=NULL;
							}
						}
						else {
							if($priority <= 40) {
								echo("40");
								switch($button) {
									case 'Callback':
										$setpriority=10;
										$setadddays=NULL;
										$setschcalldate=$date;
										break;
									case 'ConfirmationLetter':
										$setpriority=20;
										$setadddays=2;
										break;
									case 'LM':
										$setpriority=30;
										$setadddays=14;
										break;
									case 'DemandOffer':
										$setpriority=30;
										$setadddays=30;
										break;
									case 'Resubmit':
										$setpriority=30;
										$setadddays=45;
										break;
									case 'PendingCase':
										$setpriority=30;
										$setadddays=$date;
										break;
									case 'ActivelyTreating':
										$setpriority=30;
										$setadddays=$date;
										break;
									case 'EdexRequest':
										$setpriority=NULL;
										$setadddays=1;
										break;
									default : 
										$setpriority=NULL;
										$setadddays=NULL;
										$setschcalldate=NULL;
								}
							}
							else {
								echo("oops");
								error("997","collectionsQueueUpdate: unhandled priority ($priority) exception error.");
							}
						}
					}
				}
			}
// Should have $setpriority and/or $adddays
// If value passed in dropdown then use dropdown value.
			if(empty($setpriority)) {
// priority remains the same
				$setpriority=$priority;
			}
			if(!empty($setadddays) && empty($setschcalldate)) {
				$timestamp=strtotime("+ $setadddays days");
				$setschcalldate=date('Y-m-d H:i:s', $timestamp);
				notify("000","Queue schedule changed. $setpriority $setadddays $setschcalldate");
				$setschcalldate=mysqli_real_escape_string($dbhandle,$setschcalldate);
			}
			if(empty($setschcalldate)) {
// not changing record
				notify("000","Queue schedule not changed. $setpriority $setadddays $setschcalldate");
			}
			else {
				$auditarray=getauditfields();
				$upddate=$auditarray['date'];
				$upduser=$auditarray['user'];
				$updprog=$auditarray['prog'];
				$setschcalldate=dbDate($setschcalldate);
// Update record
				$updatequery="
					UPDATE collection_queue
					SET cqpriority='$setpriority', cqschcalldate='$setschcalldate', upddate='$upddate', upduser='$upduser', updprog='$updprog'
					WHERE cqcaid='$caid'
				";
				if($updateresult = mysqli_query($dbhandle,$updatequery)) {
					if($result = mysqli_query($dbhandle,$query)) {
						if($row = mysqli_fetch_assoc($result)) {
							$priority = $row['cqpriority'];
							$schcalldate = $row['cqschcalldate'];
							notify("","Account queued at priority $priority for callback on $schcalldate");
						}
					}
				}
			}
		}
		else {
			//error("998","collectionsQueueUpdate: mysql fetch error.<br />$query<br />" . mysqli_error($dbhandle));
		}
	}
	else 
		error("999","collectionsQueueUpdate: mysql result error.<br />$query<br />" . mysqli_error($dbhandle));
}

if(errorcount()!=0) {
	displaysitemessages();
	exit();
}
//if(notifycount()!=0) {
//	displaysitemessages();
//}
?>
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15);
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/sitedivs.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

if(isset($_POST['checkDoctor'])){
	$squery="SELECT dmlname, dmfname FROM doctors WHERE dmlname='".$_POST["lname"]."' and dmfname='".$_POST["fname"]."'";
	if($selectresult=mysqli_query($dbhandle,$squery)) {
		if(mysqli_num_rows($selectresult)>0) {
			echo json_encode(array("status"=>true));
			exit(1);
		}
	}
	echo json_encode(array("status"=>false));
	exit(1);
}

if(isset($_POST['checkContact'])){
	$selectquery="SELECT dlsinactive FROM doctor_locations_contacts WHERE dlstitle='".$_POST['title']."' and dlsphone='".$_POST['phone']."' and dlsfax='".$_POST['fax']."'";
	if($selectresult=mysqli_query($dbhandle,$selectquery)) {
		if(mysqli_num_rows($selectresult)>0) {
			echo json_encode(array("status"=>true));
			exit(1);
		}
	}
	echo json_encode(array("status"=>false));
	exit(1);
}

if(isset($_POST['checkLocation'])){
	$selectquery="SELECT dlinactive FROM doctor_locations WHERE dlcity='$_POST[city]' and dlphone='$_POST[phone]' and dlfax='$_POST[fax]'";
	if($selectresult=mysqli_query($dbhandle,$selectquery)) {
		if(mysqli_num_rows($selectresult)>0) {
			echo json_encode(array("status"=>true));
			exit(1);
		}
	}
	echo json_encode(array("status"=>false));
	exit(1);
}


if(!empty($_POST['searched']))
	$searched=$_POST['searched'];

if(count($_POST)==0 || !empty($_POST['clear'])) {
	unset($searched);
	unset($_POST['searchdoctorlastname']);
	unset($_POST['searchdoctorfirstname']);
	unset($_POST['searchreferralphone']);
	unset($_POST['searchreferralfax']);
	unset($_POST['dmidlist']);
	unset($_POST['dlidlist']);
	unset($_POST['dlsidlist']);
	if(!empty($_REQUEST['dmid'])) {
		$saveddoctor=$_REQUEST['dmid'];
		$doctorselect="SELECT dminactive, dmlname, dmfname FROM doctors where dmid='$saveddoctor'";
		if($doctorresult=mysqli_query($dbhandle,$doctorselect)) {
			if($doctorrow=mysqli_fetch_assoc($doctorresult)) {
				$_POST['searchdoctorlastname']=$doctorrow['dmlname'];
				$_POST['searchdoctorfirstname']=$doctorrow['dmfname'];
			}
		}
	}
	if(!empty($_REQUEST['dlid'])) {
		$savedlocation=$_REQUEST['dlid'];
		$locationselect="SELECT dlinactive, dlphone, dlfax FROM doctor_locations where dlid='$savedlocation'";
		if($locationresult=mysqli_query($dbhandle,$locationselect)) {
			if($locationrow=mysqli_fetch_assoc($locationresult)) {
				$_POST['searchreferralphone']=$locationrow['dlphone'];
				$_POST['searchreferralfax']=$locationrow['dlfax'];
			}
		}
	}
	if(!empty($_REQUEST['dlsid'])) {
		$savedcontact=$_REQUEST['dlsid'];
		$contactselect="SELECT dlsinactive, dlsphone, dlsfax FROM doctor_locations_contacts where dlsid='$savedcontact'";
		if($contactresult=mysqli_query($dbhandle,$contactselect)) {
			if($contactrow=mysqli_fetch_assoc($contactresult)) {
				$_POST['searchreferralphone']=$contactrow['dlsphone'];
				$_POST['searchreferralfax']=$contactrow['dlsfax'];
			}
		}
	}
	if(!empty($saveddoctor) || !empty($savedlocation) || !empty($savedcontact)) 
		$_POST['search']='Search';
}

// Retrieve posted saved search arrays
if(isset($_POST['dmidlist'])) {
	$array=$_POST['dmidlist'];
	foreach($array as $key=>$val) 
		$dmidlist["$key"]=unserialize(stripslashes($val));
	unset($_POST['dmidlist']);
}
else
	$dmidlist=array();

if(isset($_POST['dlidlist'])) {
	$array=$_POST['dlidlist'];
	foreach($array as $key=>$val) 
		$dlidlist["$key"]=unserialize(stripslashes($val));
	unset($_POST['dlidlist']);
}
else
	$dlidlist=array();

if(isset($_POST['dlsidlist'])) {
	$array=$_POST['dlsidlist'];
	foreach($array as $key=>$val) 
		$dlsidlist["$key"]=unserialize(stripslashes($val));
	unset($_POST['dlsidlist']);
}
else
	$dlsidlist=array();

if(!empty($_POST['done'])) {
	$saveddoctor = $_POST['saveddoctor'];
	$savedlocation = $_POST['savedlocation'];
	$savedcontact = $_POST['savedcontact'];
	if(!empty($saveddoctor) && !empty($savedlocation)  && !empty($savedcontact)) {
		$thisdoctor=$dmidlist["$saveddoctor"];
		$thislocation=$dlidlist["$savedlocation"];
		$thiscontact=$dlsidlist["$savedcontact"];
		// If selected Doctor is NEW then insert it into Doctors Table
		if($saveddoctor=='NEW') {
		// Clean up Fields
			$dmlname=dbText($thisdoctor['dmlname']);
			$dmfname=dbText($thisdoctor['dmfname']);
		// Check for existing doctor
			$selectquery="SELECT dmlname, dmfname FROM doctors WHERE dmlname='$dmlname' and dmfname='$dmfname'";
			if($selectresult=mysqli_query($dbhandle,$selectquery)) {
				if(mysqli_num_rows($selectresult)>0) 
					error("999","Doctor Name already exists, if this is a new doctor please add middle initial.");
				else {
					$insertquery="INSERT INTO doctors (dmlname, dmfname) VALUES('$dmlname','$dmfname')";
					if(mysqli_query($dbhandle,$insertquery)) {
			// Retrieve Doctor record id as $saveddoctor
						$saveddoctor=mysqli_insert_id($dbhandle);
						notify("000","Doctor $saveddoctor created.");
					}
					else
						error("999","Error inserting doctor<br>$insertquery<br>".mysqli_error($dbhandle));
				}
			}
			else 
				error("999","Error $saveddoctor Doctor Select Query<br>$selectquery<br>".mysqli_error($dbhandle));
		}
		if(errorcount()==0) {
		// If selected Location is NEW then insert it into Doctor_Locations Table
			if($savedlocation=='NEW') {
		// Clean up Fields
				$dlname=dbText($thislocation['dlname']);
				$dladdress=dbText($thislocation['dladdress']);
				$dlcity=dbText($thislocation['dlcity']);
				$dlstate='CA';
				$dlzip=dbZip($thislocation['dlzip']);
				$dlphone=dbPhone($thislocation['dlphone']);
				$dlfax=dbPhone($thislocation['dlfax']);
				$selectquery="SELECT dlinactive, dlname, dladdress, dlcity, dlzip, dlphone, dlfax FROM doctor_locations WHERE dlcity='$dlcity' and dlphone='$dlphone' and dlfax='$dlfax'";
				if($selectresult=mysqli_query($dbhandle,$selectquery)) {
					if(mysqli_num_rows($selectresult)>0) 
						error("999","Location Name already exists, if this is a new location please check city, phone and fax for uniqueness.");
					else {
		// Insert into table
						$insertquery="INSERT INTO doctor_locations (dlname, dladdress, dlcity, dlstate, dlzip, dlphone, dlfax) VALUES('$dlname','$dladdress', '$dlcity', '$dlstate', '$dlzip', '$dlphone', '$dlfax')";
						if(mysqli_query($dbhandle,$insertquery)) {
		// Retrieve record id as $saved
							$savedlocation=mysqli_insert_id($dbhandle);
							notify("000","Location $savedlocation created.");
						}
						else
							error("999","Error inserting location<br>$insertquery<br>".mysqli_error($dbhandle));
					}
				}
				else 
					error("999","Error $savedlocation Location Select Query<br>$selectquery<br>".mysqli_error($dbhandle));
			}
		}
		if(errorcount()==0) {
		// If selected contact is NEW then insert it into Doctor_Contact Table
			if($savedcontact=='NEW') {
		// Clean up Fields
				$dlstitle=dbText($thiscontact['dlstitle']);
				$dlsname=dbText($thiscontact['dlsname']);
				$dlsphone=dbPhone($thiscontact['dlsphone']);
				$dlsfax=dbPhone($thiscontact['dlsfax']);
				$selectquery="SELECT dlsinactive, dlstitle, dlsname, dlsphone, dlsfax FROM doctor_locations_contacts WHERE dlstitle='$dlstitle' and dlsphone='$dlsphone' and dlsfax='$dlsfax'";
				if($selectresult=mysqli_query($dbhandle,$selectquery)) {
					if(mysqli_num_rows($selectresult)>0) 
						error("999","Contact already exists, if this is a new contact please check city, phone and fax for uniqueness.");
					else {
		// Insert into table
						$insertquery="INSERT INTO doctor_locations_contacts (dlstitle, dlsname, dlsphone, dlsfax) VALUES('$dlstitle','$dlsname', '$dlsphone', '$dlsfax')";
						if(mysqli_query($dbhandle,$insertquery)) {
		// Retrieve record id as $saved
							$savedcontact=mysqli_insert_id($dbhandle);
							notify("000","Contact $savedcontact created.");
						}
						else
							error("999","Error inserting contact<br>$insertquery<br>".mysqli_error($dbhandle));
					}
				}
				else 
					error("999","Error $savedcontact Contact Select Query<br>$selectquery<br>".mysqli_error($dbhandle));
			}
		}
		// If Phone and Fax are not in Contacts Database, insert it into Doctor_Locations_Contacts as REFERRALS
//		if(errorcount()==0) {
//			$selectquery="SELECT dlid, dlphone, dlfax FROM doctor_locations WHERE dlid='$savedlocation'";
//			if($selectresult=mysqli_query($dbhandle,$selectquery)) {
//				if(mysqli_num_rows($selectresult)==1) {
//					if($selectrow=mysqli_fetch_assoc($selectresult)) {
//						$dlsphone=$selectrow['dlphone'];
//						$dlsfax=$selectrow['dlfax'];
//						$dlstitle='REFERRALS';
//						$selectquery="SELECT dlsid, dlstitle, dlsphone, dlsfax FROM doctor_locations_contacts WHERE dlstitle='$dlstitle' and dlsphone='$dlsphone' and dlsfax='$dlsfax'";
//						if($selectresult=mysqli_query($dbhandle,$selectquery)) {
//							if(mysqli_num_rows($selectresult)>0) {
//								if($selectrow=mysqli_fetch_assoc($selectresult)) {
//									$savedcontact=$selectrow['dlsid'];
//									notify("000","REFERRAL contact $savedcontact already exists.");
//								}
//								else
//									error("999","Error fetching REFERRAL contact.");
//							}
//							else {
//				// Insert into table
//								$insertquery="INSERT INTO doctor_locations_contacts (dlstitle, dlsphone, dlsfax) VALUES('$dlstitle', '$dlsphone', '$dlsfax')";
//								if(mysqli_query($dbhandle,$insertquery)) {
//				// Retrieve record id as $saved
//									$savedcontact=mysqli_insert_id($dbhandle);
//									notify("000","Contact $savedcontact created.");
//								}
//								else
//									error("999","Error inserting contact<br>$insertquery<br>".mysqli_error($dbhandle));
//							}
//						}
//						else 
//							error("999","Error on REFERRAL contact select query<br>$selectquery<br>".mysqli_error($dbhandle));
//					}
//				}
//			}
//		}

		// If The combination of Doctor, Location, Contact are not in the Relationships table insert them
		if(errorcount()==0) {
			$_SESSION['crrefdmid'] = $saveddoctor;
			$_SESSION['crrefdlid'] = $savedlocation;
			$_SESSION['crrefdlsid'] = $savedcontact;
			if(!empty($saveddoctor) && !empty($savedlocation) && !empty($savedcontact) ) {
				$selectquery="SELECT drdmid, drdlid, drdlsid FROM doctor_relationships WHERE drdmid='$saveddoctor' and drdlid='$savedlocation' and drdlsid='$savedcontact'";
				if($selectresult=mysqli_query($dbhandle,$selectquery)) {
					if(mysqli_num_rows($selectresult)==0) {
	// Insert into table
						$insertquery="INSERT INTO doctor_relationships (drdmid, drdlid, drdlsid) VALUES('$saveddoctor', '$savedlocation', '$savedcontact')";
						if(mysqli_query($dbhandle,$insertquery))
							notify("000","Doctor, Location, Contact Relationship created.");
						else
							error("999","Error inserting Doctor, Location, Contact Relationship<br>$insertquery<br>".mysqli_error($dbhandle));
					}
				}
				else
					error("999","Error selecting Doctor, Location, Contact Relationship<br>$selectquery<br>".mysqli_error($dbhandle));
			}
			else
				error("998","Error verify selected doctor ($saveddoctor) & location ($savedlocation) & contact ($savedcontact).");
		}
		else {
			displaysitemessages();
			exit();
		}
	}
	else {
		error("999","Error verify selected doctor ($saveddoctor) & location ($savedlocation) & contact ($savedcontact).");
	}
// If there were no errors and a case id was passed in update the case with the information and close window.
	if(errorcount()==0) {
		if(!empty($_REQUEST['crid'])) {
			$crid=$_REQUEST['crid'];
			$updatequery="UPDATE cases SET crrefdmid='$saveddoctor', crrefdlid='$savedlocation', crrefdlsid='$savedcontact' WHERE crid='$crid'";
			if($updateresult=mysqli_query($dbhandle,$updatequery))
				notify("000","Case $crid updated.");
			else
				error("999","Error-Case $crid not updated.<br>$updatequery<br>".mysqli_error($dbhandle));
		}
	}
// close window
	echo("<script>");
	echo("window.opener.location.href = window.opener.location.href;");
	echo("if (window.opener.progressWindow) window.opener.progressWindow.close();");
	echo("window.close();");
	echo("</script>");
}

// New Doctor, Location, Contact Buttons
if(!empty($_POST['newdoctorbutton'])) {
	if(!empty($_POST['newdoctorlname']) && !empty($_POST['newdoctorfname'])) {
		$dmidlist['NEW'] = array('dmlname'=>$_POST['newdoctorlname'], 'dmfname'=>$_POST['newdoctorfname']);
		$saveddoctor='NEW';
	}
}

if(!empty($_POST['newlocationbutton'])) {
	if(!empty($_POST['newlocationname']) && !empty($_POST['newlocationaddress']) && !empty($_POST['newlocationcity']) && !empty($_POST['newlocationzip']) && !empty($_POST['newlocationphone']) && !empty($_POST['newlocationfax'])) {
		$dlidlist['NEW'] = array('dlname'=>$_POST['newlocationname'], 'dladdress'=>$_POST['newlocationaddress'], 'dlcity'=>$_POST['newlocationcity'], 'dlzip'=>$_POST['newlocationzip'], 'dlphone'=>$_POST['newlocationphone'], 'dlfax'=>$_POST['newlocationfax']);
		$savedlocation='NEW';
	}
}

if(!empty($_POST['newcontactbutton'])) {
	if($_POST['newcontacttitle']=='REFERRALS' && empty($_POST['newcontactname']) && !empty($_POST['newcontactphone']) && !empty($_POST['newcontactfax'])) {
		$dlsidlist['NEW'] = array('dlstitle'=>$_POST['newcontacttitle'], 'dlsname'=>$_POST['newcontactname'], 'dlsphone'=>$_POST['newcontactphone'], 'dlsfax'=>$_POST['newcontactfax']);
		$savedcontact='NEW';
	}
}

if(!empty($_POST['search']))
	$search=$_POST['search'];
else 
	unset($search);


// Process Select/Unselect buttons
if(!empty($_POST['selectdoctor'])) {
	$doctorkeys=array_keys($_POST['selectdoctor']);
	$saveddoctor=$doctorkeys[0];
}else{
	$saveddoctor=$_POST['saveddoctor'];
}
if(!empty($_POST['unselectdoctor'])) {
	unset($dmidlist["NEW"]);
	unset($saveddoctor);
}

if(!empty($_POST['selectlocation'])) {
	$locationkeys=array_keys($_POST['selectlocation']);
	$savedlocation=$locationkeys[0];
}else{
	$savedlocation=$_POST['savedlocation'];
}
if(!empty($_POST['unselectlocation'])) {
	unset($dlidlist["NEW"]);
	unset($savedlocation);
}

if(!empty($_POST['selectcontact'])) {
	$contactkeys=array_keys($_POST['selectcontact']);
	$savedcontact=$contactkeys[0];
}else{
	$savedcontact=$_POST['savedcontact'];
}
if(!empty($_POST['unselectcontact'])) {
	unset($dlsidlist["NEW"]);
	unset($savedcontact);
}
// assign posted Search field values
if(!empty($_POST['searchdoctorfirstname']))
	$searchdoctorfirstname=dbText($_POST['searchdoctorfirstname']);
else
	$searchdoctorfirstname="";

if(!empty($_POST['searchdoctorlastname']))
	$searchdoctorlastname=dbText($_POST['searchdoctorlastname']);
else
	$searchdoctorlastname="";

if(!empty($_POST['searchreferralphone']))
	$searchreferralphone=dbPhone($_POST['searchreferralphone']);
else
	$searchreferralphone="";

if(!empty($_POST['searchreferralfax']))
	$searchreferralfax=dbPhone($_POST['searchreferralfax']);
else
	$searchreferralfax="";

// Functions to display found and selected values from array
function displaydoctortable($dmidlist, $saveddoctor) {
	if(isset($dmidlist)) {
		echo '<table id="searchdoctorsresults" cellpadding="3" cellspacing="0" border="1" >';
		if(!empty($saveddoctor)) {
			$doctor=$dmidlist["$saveddoctor"];
			if(empty($doctor['dmlname']))
				$dmlname="&nbsp;";
			else
				$dmlname=$doctor['dmlname'];
			if(empty($doctor['dmfname']))
				$dmfname="&nbsp;";
			else
				$dmfname=$doctor['dmfname'];
			echo "<tr>";
			echo '<td><input id="selectdoctor_'.$id.'" name="unselectdoctor" type="submit" value="UnSelect"></td>';
			echo "<td><strong>$dmlname</strong></td>";
			echo "<td><strong>$dmfname</strong></td>";
			echo "</tr>";
		}
		else {
			echo '<tr>';
			echo '<th colspan="3">Doctors List:</th>';
			echo '</tr>';
			echo '<tr>';
			echo '<th>Select</th>';
			echo '<th>Last Name</th>';
			echo '<th>First Name</th>';
			echo '</tr>';
			foreach($dmidlist as $id=>$doctor) {
				if(empty($doctor['dmlname']))
					$dmlname="&nbsp;";
				else
					$dmlname=$doctor['dmlname'];
				if(empty($doctor['dmfname']))
					$dmfname="&nbsp;";
				else
					$dmfname=$doctor['dmfname'];

if($doctor['dminactive']=='1') {
	$dminactivehtml=' style="background-color:#FFFFCC;"';
	$dminactivebuttonhtml='value="Inactive" disabled="disabled"';
}
else {
	$dminactivehtml='';
	$dminactivebuttonhtml='value="Select" ';
}
				echo "<tr$dminactivehtml>";
				echo '<td><input id="selectdoctor_'.$id.'" name="selectdoctor['.$id.']" type="submit" '.$dminactivebuttonhtml.'></td>';
				echo "<td>$dmlname</td>";
				echo "<td>$dmfname</td>";
				echo "</tr>";
			}
			$d = "ddd";
			echo '<tr>';
			echo '<td><input id="newdoctorbutton" name="newdoctorbutton" type="submit" value="New Doctor" disabled="disabled"></td>';
			echo '<td><input id="newdoctorlname" name="newdoctorlname" type="text" value="" onkeyup="return checkDoctor()"></td>';
			echo '<td><input id="newdoctorfname" name="newdoctorfname" type="text" value="" onkeyup="return checkDoctor()"></td>';
			echo '</tr>';
		}
		echo '</table>';
		echo "<div id='doctorExist' style='display:none;color:red'><span>Doctor's name already exists</span></div>";
	}
}

function displaylocationtable($dlidlist, $savedlocation) {
	if(isset($dlidlist)) {
		echo '<table id="searchlocationsresults" cellpadding="3" cellspacing="0" border="1" >';
		if(!empty($savedlocation)) {
			$location=$dlidlist["$savedlocation"];
			if(empty($location['dlname']))
				$dlname="&nbsp;";
			else
				$dlname=$location['dlname'];
	
			if(empty($location['dladdress']))
				$dladdress="&nbsp;";
			else
				$dladdress=$location['dladdress'];
	
			if(empty($location['dlcity']))
				$dlcity="&nbsp;";
			else
				$dlcity=$location['dlcity'];
	
			if(empty($location['dlzip']))
				$dlzip="&nbsp;";
			else
				$dlzip=$location['dlzip'];
	
			if(empty($location['dlphone']))
				$dlphone="&nbsp;";
			else
				$dlphone=displayPhone($location['dlphone']);
	
			if(empty($location['dlfax']))
				$dlfax="&nbsp;";
			else
				$dlfax=displayPhone($location['dlfax']);
			echo "<tr>";
			echo '<td><input id="selectlocation_'.$id.'" name="unselectlocation" type="submit" value="UnSelect"></td>';
			echo "<td><strong>$dlname</strong></td>";
			echo "<td><strong>$dladdress</strong></td>";
			echo "<td><strong>$dlcity</strong></td>";
			echo "<td><strong>$dlzip</strong></td>";
			echo "<td><strong>$dlphone</strong></td>";
			echo "<td><strong>$dlfax</strong></td>";
			echo "</tr>";
		}
		else {
			echo '<tr>';
			echo '<th colspan="7">Locations:</th>';
			echo '</tr>';
			echo '<tr>';
			echo '<th>Select</th>';
			echo '<th>Name</th>';
			echo '<th>Address</th>';
			echo '<th>City</th>';
			echo '<th>Zip</th>';
			echo '<th>Phone</th>';
			echo '<th>Fax</th>';
			echo '</tr>';
			foreach($dlidlist as $id=>$location) {
				if(empty($location['dlname']))
					$dlname="&nbsp;";
				else
					$dlname=$location['dlname'];
	
				if(empty($location['dladdress']))
					$dladdress="&nbsp;";
				else
					$dladdress=$location['dladdress'];
	
				if(empty($location['dlcity']))
					$dlcity="&nbsp;";
				else
					$dlcity=$location['dlcity'];
	
				if(empty($location['dlzip']))
					$dlzip="&nbsp;";
				else
					$dlzip=$location['dlzip'];
	
				if(empty($location['dlphone']))
					$dlphone="&nbsp;";
				else
					$dlphone=displayPhone($location['dlphone']);
	
				if(empty($location['dlfax']))
					$dlfax="&nbsp;";
				else
					$dlfax=displayPhone($location['dlfax']);
if($location['dlinactive']=='1') {
	$dlinactivehtml=' style="background-color:#FFFFCC;"';
	$dlinactivebuttonhtml='value="Inactive" disabled="disabled"';
}
else {
	$dlinactivehtml='';
	$dlinactivebuttonhtml='value="Select" ';
}
				echo "<tr$dlinactivehtml>";
				echo '<td><input id="selectlocation_'.$id.'" name="selectlocation['.$id.']" type="submit" '.$dlinactivebuttonhtml.'></td>';
				echo "<td>$dlname</td>";
				echo "<td>$dladdress</td>";
				echo "<td>$dlcity</td>";
				echo "<td>$dlzip</td>";
				echo "<td>$dlphone</td>";
				echo "<td>$dlfax</td>";
				echo "</tr>";
			}
			echo '<tr>';
			echo '<td><input id="newlocationbutton" name="newlocationbutton" type="submit" value="New Location" disabled="disabled"></td>';
			echo '<td><input id="newlocationname" name="newlocationname" type="text" value="" onkeyup="return checkLocation()"></td>';
			echo '<td><input id="newlocationaddress" name="newlocationaddress" type="text" value="" onkeyup="return checkLocation()"></td>';
			echo '<td><input id="newlocationcity" name="newlocationcity" type="text" value="" onkeyup="return checkLocation()"></td>';
			echo '<td><input id="newlocationzip" name="newlocationzip" type="text" value="" onkeyup="return checkLocation()"></td>';
			echo '<td><input id="newlocationphone" name="newlocationphone" type="text" value="" onkeyup="return checkLocation()"></td>';
			echo '<td><input id="newlocationfax" name="newlocationfax" type="text" value="" onkeyup="return checkLocation()"></td>';
			echo '</tr>';
		}
		echo '</table>';
		echo '<div id="locationExist" style="display:none;color:red"><span>Location already exists</span></div>';
	}
}

function displaycontacttable($dlsidlist, $savedcontact) {
	if(isset($dlsidlist)) {
		echo '<table id="searchcontactsresults" cellpadding="3" cellspacing="0" border="1" >';
		if(!empty($savedcontact)) {
			$contact=$dlsidlist["$savedcontact"];
			if(empty($contact['dlstitle']))
				$dlstitle="&nbsp;";
			else
				$dlstitle=$contact['dlstitle'];
	
			if(empty($contact['dlsname']))
				$dlsname="&nbsp;";
			else
				$dlsname=$contact['dlsname'];
	
			if(empty($contact['dlsphone']))
				$dlsphone="&nbsp;";
			else
				$dlsphone=displayPhone($contact['dlsphone']);
	
			if(empty($contact['dlsfax']))
				$dlsfax="&nbsp;";
			else
				$dlsfax=displayPhone($contact['dlsfax']);
			echo "<tr>";
			echo '<td><input id="selectcontact_'.$id.'" name="unselectcontact" type="submit" value="UnSelect"></td>';
			echo "<td><strong>$dlstitle</strong></td>";
//			echo "<td><strong>$dlsname</strong></td>";
			echo "<td><strong>$dlsphone</strong></td>";
			echo "<td><strong>$dlsfax</strong></td>";
			echo "</tr>";
		}
		else {
			echo '<tr>';
			echo '<th colspan="3">Referral Contacts:</th>';
			echo '</tr>';
			echo '<tr>';
			echo '<th>Select</th>';
//			echo '<th>Title</th>';
//			echo '<th>Name</th>';
			echo '<th>Phone</th>';
			echo '<th>Fax</th>';
			echo '</tr>';
			foreach($dlsidlist as $id=>$contact) {
				if(empty($contact['dlstitle']))
					$dlstitle="&nbsp;";
				else
					$dlstitle=$contact['dlstitle'];
		
				if(empty($contact['dlsname']))
					$dlsname="&nbsp;";
				else
					$dlsname=$contact['dlsname'];
		
				if(empty($contact['dlsphone']))
					$dlsphone="&nbsp;";
				else
					$dlsphone=displayPhone($contact['dlsphone']);
		
				if(empty($contact['dlsfax']))
					$dlsfax="&nbsp;";
				else
					$dlsfax=displayPhone($contact['dlsfax']);
if($contact['dlsinactive']=='1') {
	$dlsinactivehtml=' style="background-color:#FFFFCC;"';
	$dlsinactivebuttonhtml='value="Inactive" disabled="disabled"';
}
else {
	$dlsinactivehtml='';
	$dlsinactivebuttonhtml='value="Select" ';
}

				echo "<tr$dlsinactivehtml>";
				echo '<td><input id="selectcontact_'.$id.'" name="selectcontact['.$id.']" type="submit" '.$dlsinactivebuttonhtml.'></td>';
//				echo "<td>$dlstitle</td>";
//				echo "<td>$dlsname</td>";
				echo "<td>$dlsphone</td>";
				echo "<td>$dlsfax</td>";
				echo "</tr>";
			}
			echo '<tr>';
			echo '<td><input id="newcontactbutton" name="newcontactbutton" type="submit" value="New Contact" disabled="disabled"></td>';
			echo '<td><input id="newcontacttitle" name="newcontacttitle" type="hidden" value="REFERRALS">';
			echo '<input id="newcontactname" name="newcontactname" type="hidden" value="">';
			echo '<input id="newcontactphone" name="newcontactphone" type="text" value="" onkeyup="return checkContact()"></td>';
			echo '<td><input id="newcontactfax" name="newcontactfax" type="text" value="" onkeyup="return checkContact()"></td>';
			echo '</tr>';
		}
		echo '</table>';
		echo '<div id="contactExist" style="display:none;color:red"><span>Contact already exists</span></div>';
	}
}

if(!empty($search)) {
	if(!empty($searchdoctorfirstname) && !empty($searchdoctorlastname) && !empty($searchreferralphone) && !empty($searchreferralfax)) {
		unset($saveddoctor);
		unset($savedlocation);
		unset($savedcontact);
// Search Doctors
		if( !empty($searchdoctorfirstname) || !empty($searchdoctorlastname) ) {
	// Search for matching Doctors - Populate $dmidlist array
			$searched=1;
	
			$dmidlist=array();
			$doctorswhere=array();
			if(!empty($searchdoctorfirstname)) {
				$searchdoctorfirstname3=substr($searchdoctorfirstname,0,3);
				$doctorswhere[]="dmfname like '$searchdoctorfirstname3%'";
			}
			if(!empty($searchdoctorlastname)) {
				$searchdoctorlastname3=substr($searchdoctorlastname,0,3);
				$doctorswhere[]="dmlname like '$searchdoctorlastname3%'";
			}
			if(count($doctorswhere)>0) {
				$doctorswheresql="WHERE ".implode(" and ", $doctorswhere);
				$doctorsselect="SELECT dminactive, dmid, dmlname, dmfname FROM doctors $doctorswheresql ORDER BY dmlname, dmfname";
				if($doctorsresult=mysqli_query($dbhandle,$doctorsselect)) {
					while($doctorsrow=mysqli_fetch_assoc($doctorsresult)) {
						$dmid=$doctorsrow['dmid'];
						$dmidlist["$dmid"]=$doctorsrow;
					}
				}
			}
		}
// Search Locations and Contacts
		if(count($dmidlist)>0 || !empty($searchreferralphone) || !empty($searchreferralfax) ) {
// Search for locations relating to doctor and then matching Locations - Populate $dlidlist array
			$searched=1;
	
			$dlidlist=array();
			$locationswhere=array();
			if(count($dmidlist)>0) {
				$dmids=array_keys($dmidlist);
				$searchdmids = "('".implode("', '", $dmids)."')";
				$relationshipsselect="SELECT drdlid, dlinactive, dlname, dladdress, dlcity, dlzip, dlphone, dlfax FROM doctor_relationships JOIN doctor_locations ON drdlid=dlid WHERE drdmid IN $searchdmids ORDER BY dlphone, dlfax, dlzip, dladdress";
				if($relationshipsresult=mysqli_query($dbhandle,$relationshipsselect)) {
					while($relationshipsrow=mysqli_fetch_assoc($relationshipsresult)) {
						$dlid=$relationshipsrow['drdlid'];
						$dlidlist["$dlid"]=$relationshipsrow;
					}
				}
			}
			
			if(!empty($searchreferralphone))
				$locationswhere[]="dlphone like '" . substr($searchreferralphone,0,6)."%'";
			if(!empty($searchreferralfax))
				$locationswhere[]="dlfax like '".substr($searchreferralfax,0,6)."%'";
			if(count($locationswhere)>0) {
				$locationswheresql="WHERE ".implode(" OR ", $locationswhere);
				$locationsselect="SELECT dlinactive, dlid, dlname, dladdress, dlcity, dlzip, dlphone, dlfax FROM doctor_locations $locationswheresql ORDER BY dlphone, dlfax, dlzip, dladdress";
				if($locationsresult=mysqli_query($dbhandle,$locationsselect)) {
					while($locationsrow=mysqli_fetch_assoc($locationsresult)) {
						$dlid=$locationsrow['dlid'];
						$dlidlist["$dlid"]=$locationsrow;
					}
				}
			}
// Search for contacts relating to doctor and then matching Contacts - Populate $dlsidlist array
			$dlsidlist=array();
			$contactswhere=array();
			if(count($dmidlist)>0) {
				$dmids=array_keys($dmidlist);
				$searchdmids = "('".implode("', '", $dmids)."')";
				$relationshipsselect="SELECT drdlsid, dlsinactive, dlstitle, dlsname, dlsphone, dlsfax FROM doctor_relationships JOIN doctor_locations_contacts ON drdlsid=dlsid WHERE drdmid IN $searchdmids ORDER BY dlsphone, dlsfax";
				if($relationshipsresult=mysqli_query($dbhandle,$relationshipsselect)) {
					while($relationshipsrow=mysqli_fetch_assoc($relationshipsresult)) {
						$dlsid=$relationshipsrow['drdlsid'];
						$dlsidlist["$dlsid"]=$relationshipsrow;
					}
				}
			}
			
			if(!empty($searchreferralphone))
				$contactswhere[]="dlsphone like '" . substr($searchreferralphone,0,6)."%'";
			if(!empty($searchreferralfax))
				$contactswhere[]="dlsfax like '".substr($searchreferralfax,0,6)."%'";
			if(count($contactswhere)>0) {
				$contactswheresql="WHERE ".implode(" OR ", $contactswhere);
				$contactsselect="SELECT dlsinactive, dlsid, dlstitle, dlsname, dlsphone, dlsfax FROM doctor_locations_contacts $contactswheresql ORDER BY dlsphone, dlsfax";
				if($contactsresult=mysqli_query($dbhandle,$contactsselect)) {
					while($contactsrow=mysqli_fetch_assoc($contactsresult)) {
						$dlsid=$contactsrow['dlsid'];
						$dlsidlist["$dlsid"]=$contactsrow;
					}
				}
			}
		}
		
		// Search for Doctors related to locations, or contacts - Populate/Add to $dmidlist array
		//	if(is_array($dmidlist) && count($dmidlist)>0) {
		//		$dmidkeys=array_keys($dmidlist);
		//		$where[]="drdmid IN ('".implode("', '", $dmidkeys)."')";
		//	}
		if(is_array($dlidlist) && count($dlidlist)>0) {
			$dlidkeys=array_keys($dlidlist);
			$where[]="drdlid IN ('".implode("', '", $dlidkeys)."')";
		}
		if(count($where)>0) { 
			$searched=1;
			$wheresql="WHERE ".implode(" OR ", $where);
			$relationshipsselect="SELECT drdmid, dminactive, dmlname, dmfname FROM doctor_relationships JOIN doctors ON drdmid=dmid $wheresql ORDER BY dmlname, dmfname";
			if($relationshipsresult=mysqli_query($dbhandle,$relationshipsselect)) {
				while($relationshipsrow=mysqli_fetch_assoc($relationshipsresult)) {
					$dmid=$relationshipsrow['drdmid'];
					$dmidlist["$dmid"]=$relationshipsrow;
				}
			}
		}
	}
	else {
		error("999","All fields are required. Please be sure to enter Doctor Last and First Name and Referral Phone and Fax Number");
	}
}
if(errorcount()>0)
	displaysitemessages();
?>
<form id="searchdoctors" name="searchdoctors" action="" method="post">
	<table cellpadding="3" cellspacing="0" border="1" >
		<tr>
			<th> Doctor Last Name (3) </th>
			<th> Doctor First Name (3) </th>
			<th> Referral Phone Number (6) </th>
			<th> Referral Fax Number (6) </th>
		</tr>
		<tr>
			<td><input id="searchdoctorlastname" name="searchdoctorlastname" type="text" value="<?php echo $searchdoctorlastname; ?>" >
			</td>
			<td><input id="searchdoctorfirstname" name="searchdoctorfirstname" type="text" value="<?php echo $searchdoctorfirstname; ?>" >
			</td>
			<td><input id="searchreferralphone" name="searchreferralphone" type="text" value="<?php echo $searchreferralphone; ?>" >
			</td>
			<td><input id="searchreferralfax" name="searchreferralfax" type="text" value="<?php echo $searchreferralfax; ?>" >
			</td>
			<tr>
			<td colspan="2"><input id="search" name="search" type="submit" value="Search Doctors" >
			<td colspan="2" align="right"><input id="clear" name="clear" type="submit" value="Clear Search" ></td>
			<?php
				if(!empty($searched))
	 				echo '<input type="hidden" name="searched" value="'.$searched.'">';
				if(!empty($saveddoctor))
	 				echo '<input type="hidden" name="saveddoctor" value="'.$saveddoctor.'">';
				if(!empty($savedlocation))
	 				echo '<input type="hidden" name="savedlocation" value="'.$savedlocation.'">';
				if(!empty($savedcontact))
	 				echo '<input type="hidden" name="savedcontact" value="'.$savedcontact.'">';
				if(isset($dmidlist)) 
					foreach($dmidlist as $key=>$array) 
		 				echo '<input type="hidden" name="dmidlist['.$key.']" value="'.htmlspecialchars(serialize($array)).'">';
				if(isset($dlidlist))
					foreach($dlidlist as $key=>$array) 
						echo '<input type="hidden" name="dlidlist['.$key.']" value="'.htmlspecialchars(serialize($array)).'">';
				if(isset($dlsidlist))
					foreach($dlsidlist as $key=>$array) 
						echo '<input type="hidden" name="dlsidlist['.$key.']" value="'.htmlspecialchars(serialize($array)).'">';
			?>
			</tr>
		</tr>
	</table>
<?php
if(!empty($searched)) {
	displaydoctortable($dmidlist, $saveddoctor);
	displaylocationtable($dlidlist, $savedlocation);
	displaycontacttable($dlsidlist, $savedcontact);
}

if(!empty($saveddoctor) && !empty($savedlocation) && !empty($savedcontact)) 
	echo ('<input name="done" type="submit" value="Done">');
?>

</form>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript">
	function checkDoctor(){
		if($("#newdoctorfname").val()!="" && $("#newdoctorlname").val()){
			$.post("/modules/doctor/doctorManagement.php",{fname:$("#newdoctorfname").val(),lname:$("#newdoctorlname").val(),"checkDoctor":true},function(res){
				if(JSON.parse(res).status){
					$("#doctorExist").css("display","block")
					$("#newdoctorbutton").attr("disabled",true)
				}else{
					$("#doctorExist").css("display","none")
					$("#newdoctorbutton").attr("disabled",false)
				}
			})
		}
	}

	function checkContact(){
		if($("#newcontactphone").val()!="" && $("#newcontactfax").val()){
			$.post("/modules/doctor/doctorManagement.php",{phone:$("#newcontactphone").val(),fax:$("#newcontactfax").val(),title:$("#newcontacttitle").val(),"checkContact":true},function(res){
				if(JSON.parse(res).status){
					$("#contactExist").css("display","block")
					$("#newcontactbutton").attr("disabled",true)
				}else{
					$("#contactExist").css("display","none")
					$("#newcontactbutton").attr("disabled",false)
				}
			})
		}
	}

	function checkLocation(){
		if($("#newlocationcity").val()!="" && $("#newlocationphone").val() && $("#newlocationfax").val()){
			$.post("/modules/doctor/doctorManagement.php",{city:$("#newlocationcity").val(),phone:$("#newlocationphone").val(),fax:$("#newlocationfax").val(),"checkLocation":true},function(res){
				if(JSON.parse(res).status){
					$("#locationExist").css("display","block")
					$("#newlocationbutton").attr("disabled",true)
				}else{
					$("#locationExist").css("display","none")
					$("#newlocationbutton").attr("disabled",false)
				}
			})
		}
	}
</script>
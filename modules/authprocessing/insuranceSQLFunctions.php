<?php
function insuranceStripSlashes($post) {
	foreach($post as $key=>$val) {	
		if($key != 'button') {
			if(is_string($post[$key]))
				$post[$key] = stripslashes(strip_tags(trim($val)));
		}
	}
	return($post);
}

function insuranceCompanyName($function, $icid=NULL, $post) {
	if( $function == 'INSERT' || ( $function =='UPDATE' && !empty($icid) ) ) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
		securitylevel(15); 
		errorclear();
		$post=insuranceStripSlashes($post);
	//	require_once('insuranceCompanyNameValidation.php');
	
		if(errorcount() == 0) {
			if(!isset($dbhandle) || !isset($dbselect)) {
				require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
				$dbhandle = dbconnect();
				
				$iopened=TRUE;
			}
			$set=array();
			//declare the SQL statement that will query the database
			if(isset($post['icname'])) 
				$set[] = "icname ='" . mysqli_real_escape_string($dbhandle,$post['icname']) . "'";
	
			if(count($set) > 0) {
				$set = "SET " . implode(', ', $set);
				if($function == 'INSERT') 
					$query = "INSERT INTO insurance_companies $set";
				if($function == 'UPDATE') 
					$query = "UPDATE insurance_companies $set WHERE icid='$icid'";

				if($result = mysqli_query($dbhandle,$query)) {
					if($function=='INSERT') {
						if($resultid = mysql_insert_id()) {
							notify("000","Insurance Company #$resultid successfully inserted.");
							return($resultid);
						}
						else
							error('002', "$function Id Error: $query<br>" . mysqli_error($dbhandle));
					}
					if($function=='UPDATE') { 
						notify("000","Insurance Company #$icid successfully updated.");
						return($icid);
					}
				}
				else
					error('001', "$function Error: $query<br>" . mysqli_error($dbhandle));
			}
			else
				error('001', "Error: $query<br>" . mysqli_error($dbhandle));
			if($iopened) mysqli_close($dbhandle);
		}
	}
	return(FALSE);
}

function insuranceCompanyLocation($function, $iclid=NULL, $post) {
	if( $function == 'INSERT' || ( $function =='UPDATE' && !empty($iclid) ) ) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
		securitylevel(15); 
		errorclear();
		$post=insuranceStripSlashes($post);
	//	require_once('insuranceCompanyLocationValidation.php');
		if(errorcount() == 0) {
			if(!isset($dbhandle) || !isset($dbselect)) {
				require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
				$dbhandle = dbconnect();
				
				$iopened=TRUE;
			}
			$set=array();
			//declare the SQL statement that will query the database	
			if(isset($post['icid'])) 
				$set[] = "iclicid ='" . mysqli_real_escape_string($dbhandle,$post['icid']) . "'";
			if(isset($post['iclicode'])) 
				$set[] = "iclicode ='" . mysqli_real_escape_string($dbhandle,$post['iclicode']) . "'";
			if(isset($post['iclname'])) 
				$set[] = "iclname ='" . mysqli_real_escape_string($dbhandle,$post['iclname']) . "'";
			if(isset($post['iclphone'])) 
				$set[] = "iclphone ='" . mysqli_real_escape_string($dbhandle,$post['iclphone']) . "'";
			if(isset($post['iclemail'])) 
				$set[] = "iclemail ='" . mysqli_real_escape_string($dbhandle,$post['iclemail']) . "'";
			if(isset($post['iclfax'])) 
				$set[] = "iclfax ='" . mysqli_real_escape_string($dbhandle,$post['iclfax']) . "'";
			if(isset($post['icladdress1'])) 
				$set[] = "icladdress1 ='" . mysqli_real_escape_string($dbhandle,$post['icladdress1']) . "'";
			if(isset($post['icladdress2'])) 
				$set[] = "icladdress2 ='" . mysqli_real_escape_string($dbhandle,$post['icladdress2']) . "'";
			if(isset($post['iclcity'])) 
				$set[] = "iclcity ='" . mysqli_real_escape_string($dbhandle,$post['iclcity']) . "'";
			if(isset($post['iclstate'])) 
				$set[] = "iclstate ='" . mysqli_real_escape_string($dbhandle,$post['iclstate']) . "'";
			if(isset($post['iclzip'])) 
				$set[] = "iclzip ='" . mysqli_real_escape_string($dbhandle,$post['iclzip']) . "'";
			if(isset($post['iclofficehours'])) 
				$set[] = "iclofficehours ='" . mysqli_real_escape_string($dbhandle,$post['iclofficehours']) . "'";
			if(count($set) > 0) {
				$set = "SET " . implode(', ', $set);
				if($function == 'INSERT') 
					$query = "INSERT INTO insurance_companies_locations $set";
				if($function == 'UPDATE') 
					$query = "UPDATE insurance_companies_locations $set WHERE iclid='$iclid'";
				if($result = mysqli_query($dbhandle,$query)) {
					if($function=='INSERT') {
						if($resultid = mysql_insert_id()) {
							notify("000","Insurance Company Location #$resultid successfully inserted.");
							return($resultid);
						}
						else
							error('002', "$function Id Error: $query<br>" . mysqli_error($dbhandle));
					}
					if($function=='UPDATE') { 
						notify("000","Insurance Company Location #$iclid successfully updated.");
						return($iclid);
					}
				}
				else
					error('001', "$function Error: $query<br>" . mysqli_error($dbhandle));
			}
			else
				error('001', "Error: $query<br>" . mysqli_error($dbhandle));
			if($iopened) mysqli_close($dbhandle);
		}
	}
	return(FALSE);
}

function insuranceCompanyAdjuster($function, $icaid=NULL, $post) {
	if( $function == 'INSERT' || ( $function =='UPDATE' && !empty($icaid) ) ) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
		securitylevel(15); 
		errorclear();
		$post=insuranceStripSlashes($post);
	//	require_once('insuranceCompanyAdjusterValidation.php');
		if(errorcount() == 0) {
			if(!isset($dbhandle) || !isset($dbselect)) {
				require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
				$dbhandle = dbconnect();
				
				$iopened=TRUE;
			}
			$set=array();
			//declare the SQL statement that will query the database
			$query = "INSERT INTO insurance_companies_adjusters ";
			if(isset($post['icid'])) 
				$set[] = "icaicid ='" . mysqli_real_escape_string($dbhandle,$post['icid']) . "'";
			if(isset($post['iclid'])) 
				$set[] = "icaiclid ='" . mysqli_real_escape_string($dbhandle,$post['iclid']) . "'";
			if(isset($post['icalname'])) 
				$set[] = "icalname ='" . mysqli_real_escape_string($dbhandle,$post['icalname']) . "'";
			if(isset($post['icafname'])) 
				$set[] = "icafname ='" . mysqli_real_escape_string($dbhandle,$post['icafname']) . "'";
			if(isset($post['icaphone'])) 
				$set[] = "icaphone ='" . mysqli_real_escape_string($dbhandle,$post['icaphone']) . "'";
			if(isset($post['icaemail'])) 
				$set[] = "icaemail ='" . mysqli_real_escape_string($dbhandle,$post['icaemail']) . "'";
			if(isset($post['icafax'])) 
				$set[] = "icafax ='" . mysqli_real_escape_string($dbhandle,$post['icafax']) . "'";
			if(isset($post['icaaddress1'])) 
				$set[] = "icaaddress1 ='" . mysqli_real_escape_string($dbhandle,$post['icaaddress1']) . "'";
			if(isset($post['icaaddress2'])) 
				$set[] = "icaaddress2 ='" . mysqli_real_escape_string($dbhandle,$post['icaaddress2']) . "'";
			if(isset($post['icacity'])) 
				$set[] = "icacity ='" . mysqli_real_escape_string($dbhandle,$post['icacity']) . "'";
			if(isset($post['icastate'])) 
				$set[] = "icastate ='" . mysqli_real_escape_string($dbhandle,$post['icastate']) . "'";
			if(isset($post['icazip'])) 
				$set[] = "icazip ='" . mysqli_real_escape_string($dbhandle,$post['icazip']) . "'";
			if(isset($post['icaofficehours'])) 
				$set[] = "icaofficehours ='" . mysqli_real_escape_string($dbhandle,$post['icaofficehours']) . "'";
	
			if(count($set) > 0) {
				$set = "SET " . implode(', ', $set);
				if($function == 'INSERT') 
					$query = "INSERT INTO insurance_companies_adjusters $set";
				if($function == 'UPDATE') 
					$query = "UPDATE insurance_companies_adjusters $set WHERE icaid='$icaid'";
				if($result = mysqli_query($dbhandle,$query)) {
					if($function=='INSERT') {
						if($resultid = mysql_insert_id()) {
							notify("000","Insurance Company Adjuster #$resultid successfully inserted.");
							return($resultid);
						}
						else
							error('002', "$function Id Error: $query<br>" . mysqli_error($dbhandle));
					}
					if($function=='UPDATE') { 
						notify("000","Insurance Company Adjuster #$icaid successfully updated.");
						return($icaid);
					}
				}
				else
					error('001', "$function Error: $query<br>" . mysqli_error($dbhandle));
			}
			else
				error('001', "Error: $query<br>" . mysqli_error($dbhandle));
			if($iopened) mysqli_close($dbhandle);
		}
	}
	return(FALSE);
}
?>
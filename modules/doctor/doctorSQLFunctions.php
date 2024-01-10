<?php
function postStripSlashes($post) {
	foreach($post as $key=>$val) {	
		if($key != 'button') {
			if(is_string($post[$key]))
				$post[$key] = stripslashes(strip_tags(trim($val)));
		}
	}
	return($post);
}

function doctorName($function, $dmid=NULL, $post) {
	if( $function == 'INSERT' || ( $function =='UPDATE' && !empty($dmid) ) ) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
		securitylevel(15); 
		errorclear();
		$post=postStripSlashes($post);
	//	require_once('insuranceCompanyNameValidation.php');
		if(errorcount() == 0) {
			if(!isset($dbhandle) || !isset($dbselect)) {
				require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
				$dbhandle = dbconnect();
				
				$iopened=TRUE;
			}
			$set=array();
			//declare the SQL statement that will query the database
			if(isset($post['dmlname'])) 
				$set[] = "dmlname ='" . mysqli_real_escape_string($dbhandle,$post['dmlname']) . "'";
			if(isset($post['dmfname'])) 
				$set[] = "dmfname ='" . mysqli_real_escape_string($dbhandle,$post['dmfname']) . "'";
			if(count($set) > 0) {
				$set = "SET " . implode(', ', $set);
				if($function == 'INSERT') 
					$query = "INSERT INTO doctors $set";
				if($function == 'UPDATE') 
					$query = "UPDATE doctors $set WHERE dmid='$dmid'";

				if($result = mysqli_query($dbhandle,$query)) {
					if($function=='INSERT') {
						if($resultid = mysql_insert_id()) {
							notify("000","Doctor #$resultid successfully inserted.");
							return($resultid);
						}
						else
							error('002', "$function Id Error: $query<br>" . mysqli_error($dbhandle));
					}
					if($function=='UPDATE') { 
						notify("000","Doctor #$icid successfully updated.");
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

function doctorLocation($function, $dlid=NULL, $post) {
	if( $function == 'INSERT' || ( $function =='UPDATE' && !empty($dlid) ) ) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
		securitylevel(15); 
		errorclear();
		$post=postStripSlashes($post);
	//	require_once('insuranceCompanyLocationValidation.php');
		if(errorcount() == 0) {
			if(!isset($dbhandle) || !isset($dbselect)) {
				require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
				$dbhandle = dbconnect();
				
				$iopened=TRUE;
			}
			$set=array();
			//declare the SQL statement that will query the database	
			if(isset($post['dlname'])) 
				$set[] = "dlname ='" . mysqli_real_escape_string($dbhandle,$post['dlname']) . "'";
			if(isset($post['dlphone'])) 
				$set[] = "dlphone ='" . mysqli_real_escape_string($dbhandle,dbPhone($post['dlphone'])) . "'";
			if(isset($post['dlemail'])) 
				$set[] = "dlemail ='" . mysqli_real_escape_string($dbhandle,$post['dlemail']) . "'";
			if(isset($post['dlfax'])) 
				$set[] = "dlfax ='" . mysqli_real_escape_string($dbhandle,dbPhone($post['dlfax'])) . "'";
			if(isset($post['dladdress'])) 
				$set[] = "dladdress ='" . mysqli_real_escape_string($dbhandle,$post['dladdress']) . "'";
			if(isset($post['dlcity'])) 
				$set[] = "dlcity ='" . mysqli_real_escape_string($dbhandle,$post['dlcity']) . "'";
			if(isset($post['dlstate'])) 
				$set[] = "dlstate ='" . mysqli_real_escape_string($dbhandle,$post['dlstate']) . "'";
			if(isset($post['dlzip'])) 
				$set[] = "dlzip ='" . mysqli_real_escape_string($dbhandle,$post['dlzip']) . "'";
			if(isset($post['dlofficehours'])) 
				$set[] = "dlofficehours ='" . mysqli_real_escape_string($dbhandle,$post['dlofficehours']) . "'";
			if(count($set) > 0) {
				$set = "SET " . implode(', ', $set);
				if($function == 'INSERT') 
					$query = "INSERT INTO doctor_locations $set";
				if($function == 'UPDATE') 
					$query = "UPDATE doctor_locations $set WHERE dlid='$dlid'";
				if($result = mysqli_query($dbhandle,$query)) {
					if($function=='INSERT') {
						if($resultid = mysql_insert_id()) {
							notify("000","Doctor Location #$resultid successfully inserted.");
							return($resultid);
						}
						else
							error('002', "$function Id Error: $query<br>" . mysqli_error($dbhandle));
					}
					if($function=='UPDATE') { 
						notify("000","Doctor Location #$iclid successfully updated.");
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

function doctorContact($function, $dlsid=NULL, $post) {
	if( $function == 'INSERT' || ( $function =='UPDATE' && !empty($dlsid) ) ) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
		securitylevel(15); 
		errorclear();
		$post=postStripSlashes($post);
	//	require_once('insuranceCompanyAdjusterValidation.php');
		if(errorcount() == 0) {
			if(!isset($dbhandle) || !isset($dbselect)) {
				require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
				$dbhandle = dbconnect();
				
				$iopened=TRUE;
			}
			$set=array();
			//declare the SQL statement that will query the database
			$query = "INSERT INTO doctor_locations_contacts ";
			if(isset($post['dlstitle'])) 
				$set[] = "dlstitle ='" . mysqli_real_escape_string($dbhandle,$post['dlstitle']) . "'";
			if(isset($post['dlsname'])) 
				$set[] = "dlsname ='" . mysqli_real_escape_string($dbhandle,$post['dlsname']) . "'";
			if(isset($post['dlsphone'])) 
				$set[] = "dlsphone ='" . mysqli_real_escape_string($dbhandle,$post['dlsphone']) . "'";
			if(isset($post['dlsemail'])) 
				$set[] = "dlsemail ='" . mysqli_real_escape_string($dbhandle,$post['dlsemail']) . "'";
			if(isset($post['dlsfax'])) 
				$set[] = "dlsfax ='" . mysqli_real_escape_string($dbhandle,$post['dlsfax']) . "'";

			if(count($set) > 0) {
				$set = "SET " . implode(', ', $set);
				if($function == 'INSERT') 
					$query = "INSERT INTO doctor_locations_contacts $set";
				if($function == 'UPDATE') 
					$query = "UPDATE doctor_locations_contacts $set WHERE dlsid='$dlsid'";
				if($result = mysqli_query($dbhandle,$query)) {
					if($function=='INSERT') {
						if($resultid = mysql_insert_id()) {
							notify("000","Doctor Location Contact #$resultid successfully inserted.");
							return($resultid);
						}
						else
							error('002', "$function Id Error: $query<br>" . mysqli_error($dbhandle));
					}
					if($function=='UPDATE') { 
						notify("000","Doctor Location Contact #$icaid successfully updated.");
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
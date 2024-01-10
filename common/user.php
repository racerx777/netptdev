<?php
$loginWrongCredential = "";
function securitycheck($scriptfilename) {
	// If not logged in redirect to login form
	if(!isset($_SESSION['user']['umuser']))  {
		login();
		exit();
	}

	// If not logged in redirect to login form
	if( !isset($_SESSION['user']['umrole']) )  {
		login();
		exit;
	}

	// Check for Logout
	if(isset($_POST['logout'])) {
		logout();
		exit;
	}
}

function thisUserCanImitate() {
	if($_SESSION['user']['umuser'] == 'Administrator' || $_SESSION['user']['umuser'] == 'JeffAdmin' || $_SESSION['user']['umuser'] == 'Constance' || $_SESSION['user']['umuser'] == 'mtwheater')
		return(true);
	else
		return(false);
}

function userisadmin() {
	if($_SESSION['user']['umrole'] > '90')
		return(true);
	else
		return(false);
}

function userlevel() {
	return($_SESSION['user']['umrole']);
}

function isuserlevel($level=0) {
	if($_SESSION['user']['umrole'] >= $level)
		return(true);
	else
		return(false);
}

function userislevel($level=0) {
	if($_SESSION['user']['umrole'] == $level)
		return(true);
	else
		return(false);
}

function getuser() {
	return($_SESSION['user']['umuser']);
}

function getuserid() {
	return($_SESSION['user']['umid']);
}

function getusername() {
	return($_SESSION['user']['umname']);
}

function getUserNameByUser($umuser) {
	unset($umname);
	$query = "SELECT umname FROM master_user WHERE umuser='$umuser' LIMIT 1 ";
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	if($result = mysqli_query($dbhandle,$query)) {
		if($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
			$umname=$row['umname'];
	}
	//if(is_resource($dbhandle))
//		mysqli_close($dbhandle1);
	return($umname);
}

function getUserNameById($umid) {
	unset($umname);
	$query = "SELECT umname FROM master_user WHERE umid='$umid' LIMIT 1 ";
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	if($result = mysqli_query($dbhandle,$query)) {
		if($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
			$umname=$row['umname'];
	}
	if(is_resource($dbhandle))
		mysqli_close($dbhandle);
	return($umname);
}

function getUserById($umid) {
	unset($umuser);
	$query = "SELECT umuser FROM master_user WHERE umid='$umid' LIMIT 1 ";
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	if($result = mysqli_query($dbhandle,$query)) {
		if($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
			$umuser=$row['umuser'];
	}
	if(is_resource($dbhandle))
		mysqli_close($dbhandle);
	return($umuser);
}

function getuserclinic() {
	return($_SESSION['user']['umclinic']);
}

function getuserclinicname() {
	$userclinic = getuserclinic();
	return($_SESSION['clinics'][$userclinic]);
}

function getUserQueueAssignment($user) {
	unset($group);
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	$query = "SELECT cqagroup FROM master_collections_queue_assign WHERE cqauser='$user' LIMIT 1 ";
	if($result = mysqli_query($dbhandle,$query)) {
		if($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
			$group=$row['cqagroup'];
	}
	return($group);
}

function getUserQueueAssignmentOld2($user) {
// Replace this with a database if they want to expand the queue assignment and queue definition apps
if($user=='MariaLaraIns')
	$group='35INS';

if($user=='SunniSpoon')
	$group='00NNA';

if($user=='ErnestoCollect')
	$group='01NLOW';

if($user=='MariaOropeza')
	$group='02NSOF';

//if($user=='IsaiahRuiz')
//	$group='03NMED';
if($user=='RamsesMedina')
	$group='03NMED';

if($user=='DeborahPotter')
	$group='04NMED';

if($user=='SilviaAcevedo')
	$group='17WHAR';

if($user=='JesseSanchez')
	$group='19WHAR';

if($user=='RichardLoza')
	$group='13WSOF';


if($user=='JudyCollect')
	$group='11WLOW';

if($user=='ShawnaClay')
	$group='12WSOF';

//if($user=='MelissaKrueger')
//	$group='13WSOF';

if($user=='ChristyGarcia')
	$group='14WMED';

if($user=='AntoinetteCarlin')
	$group='05NHAR';

if($user=='MarthaRodriguez')
	$group='15WMED';

// ??
if($user=='KathyMoreno')
	$group='17WHAR';

if($user=='SandraBenavidez')
	$group='18WHAR';

if($user=='ConnieMedeiros')
	$group='40PIMZ';

if($user=='MariaRuiz')
	$group='40PIAL';

if($user=='VidalSolorzano')
	$group='10WNA';

if($user=='ConstanceCollect')
	$group='10WNA';

if($user=='mtwheaterC')
	$group='10WNA';

if($user=='JackieCollect')
	$group='10WNA';

if($user=='Constance')
	$group='10WNA';

// Change her Assignment to include some 02 > $7k, 08 > $5k Clinics

//if($user=='DixieRosales')
//	$group='20LEGA';
if($user=='JudyGomez')
	$group='20LEGA';
if($user=='GabriellaCasilla')
	$group='20LEGA';
if($user=='MariaCamacho')
	$group='20LEGA';
if($user=='ErnestoEspinoza')
	$group='20LEGA';
if($user=='AlesiaGuzman')
	$group='20LEGA';

if($user=='YeseniaDOL')
	$group='30DOL';

if($user=='GenesisBedoy')
	$group='30DOL';

	return($group);
}

function getUserQueueAssignmentOld1($user) {
// Replace this with a database if they want to expand the queue assignment and queue definition apps
if($user=='MelissaKrueger')
	$group='Meliss';

if($user=='SunniSpoon')
	$group='NA';
if($user=='VidalSolorzano')
	$group='Vidal';

//if($user=='CynthiaBlass')
//	$group='NETAPA';
if($user=='DeborahPotter')
	$group='NETAPA';
if($user=='SilviaAcevedo')
	$group='NETAPB';
//if($user=='MaribelBarroso')
//	$group='NETAPC';
//if($user=='DeborahPotter')
//	$group='NETAPC';
if($user=='TerriKit')
	$group='NETAPC';
if($user=='IsaiahRuiz')
	$group='NETAPC';
if($user=='MariaOropeza')
	$group='NETDEF';

if($user=='MarthaRodriguez')
	$group='Martha';
if($user=='MarlaMullins')
	$group='Marla';
if($user=='SandraBenavidez')
	$group='Sandra';
if($user=='KathyMoreno')
	$group='Kathy';
if($user=='ShawnaClay')
	$group='Shawna';
if($user=='ChristyGarcia')
	$group='Chrsty';

if($user=='MariaRuiz')
	$group='MariaR';

// Change her Assignment to include some 02 > $7k, 08 > $5k Clinics
if($user=='ConnieMedeiros')
	$group='Connie';

if($user=='DixieRosales')
	$group='LEGAL';
if($user=='JudyGomez')
	$group='LEGAL';
if($user=='ErnestoEspinoza')
	$group='LEGAL';
if($user=='AlesiaGuzman')
	$group='LEGAL';

//if($user=='JasonVilla')
//	$group='LEGAL';
//if($user=='ChrisMartinez')
//	$group='LEGAL';

// Add GenesisBedoy like Yesenia
if($user=='YeseniaDOL')
	$group='DOL';

if($user=='GenesisBedoy')
	$group='DOL';

	return($group);
}

function getUserQueueAssignmentOld($user) {
// Replace this with a database if they want to expand the queue assignment and queue definition apps
if($user=='MelissaKrueger')
	$group='NEW';

if($user=='SunniSpoon')
	$group='NA';
if($user=='VidalSolorzano')
	$group='RVW';
if($user=='MarthaRodriguez')
	$group='NETAPA';
if($user=='CynthiaBlass')
	$group='NETAPB';
if($user=='SilviaAcevedo')
	$group='NETAPC';
if($user=='MariaOropeza')
	$group='NETDEF';

if($user=='MaribelBarroso')
	$group='WS134';
if($user=='MarlaMullins')
	$group='WS134L';
if($user=='SandraBenavidez')
	$group='WS2';
if($user=='KathyMoreno')
	$group='WS58';
if($user=='ShawnaClay')
	$group='WS67';
if($user=='MariaRuiz')
	$group='PIWS9';

// Change her Assignment to include some 02 > $7k, 08 > $5k Clinics
if($user=='ConnieMedeiros')
	$group='WSOLD';

if($user=='DixieRosales')
	$group='LEGAL';
if($user=='JudyGomez')
	$group='LEGAL';
if($user=='NoemiGalvez')
	$group='LEGAL';

//if($user=='JasonVilla')
//	$group='LEGAL';
//if($user=='ChrisMartinez')
//	$group='LEGAL';

// Add GenesisBedoy like Yesenia
if($user=='YeseniaDOL')
	$group='DOL';

if($user=='GenesisBedoy')
	$group='DOL';

	return($group);
}

function logout() {
		unset ( $_POST );
		unset ( $_SESSION );
		session_destroy ();
		login();
		exit;
}

function getuserbusinessunits($businessunitcode) {
	$businessunits = array();
	$query = 'SELECT bumid, bumcode, bumname, bumemail, bumphone, bumfax FROM master_business_units ';
//dump("query",$query);
	if($businessunitcode == '*')
		$query .= "WHERE buminactive = '0' ";
	else
		$query .= "WHERE buminactive = '0' and bumcode = '$businessunitcode' ";
//	dump("query", $query);
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$result = mysqli_query($dbhandle,$query);
//	dump("bum query", $query);
	if($result) {
		while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
			$businessunits[$row['bumcode']]=$row;
		}
	}
	return($businessunits);
}

function getuserprovidergroups($businessunitsarray, $providergroupcode) {
	$providergroupsarray = array();
	if(sizeof($businessunitsarray) > 0) {
		$bumlistarray = array();
		foreach($businessunitsarray as $key=>$val) {
			$bumlistarray[] = $val['bumcode'];
		}
		$bumlist = "('" . implode("', '", $bumlistarray) . "')";
		$query = 'SELECT pgmbumcode, pgmid, pgmcode, pgmname, pgmemail, pgmphone, pgmfax FROM master_provider_groups ';
		if($providergroupcode == '*')
			$query .= "WHERE pgminactive = '0' and pgmbumcode IN $bumlist";
		else
			$query .= "WHERE pgminactive = '0' and pgmbumcode IN $bumlist and pgmcode = '$providergroupcode' ";
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
//	dump("pgm query", $query);
		$result = mysqli_query($dbhandle,$query);
		if($result) {
			while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
				$providergroupsarray[$row['pgmcode']]=$row;
			}
		}
	}
	return($providergroupsarray);
}

function getUserClinicsList() {
	$clinicslist = "";
	$clinicsarray = $_SESSION['useraccess']['clinics'];
	if(sizeof($clinicsarray) > 0) {
		$clinicslistarray = array();
		foreach($clinicsarray as $key=>$val) {
			$clinicslistarray[] = $val['cmcnum'];
		}
		$clinicslist = "('" . implode("', '", $clinicslistarray) . "')";
	}
	else {
//		dump("clinics", $_SESSION['useraccess']['clinics']);
		error('000','Not authorized to any clinics.');
		displaysitemessages();
		logout();
		exit;
	}
	return($clinicslist);
}

function getuserclinics($providergroupsarray, $cliniccode) {
	$clinicsarray = array();
	if(sizeof($providergroupsarray) > 0) {
		$pgmlistarray = array();
		foreach($providergroupsarray as $key=>$val) {
			$pgmlistarray[] = $val['pgmcode'];
		}
		$pgmlist = "('" . implode("', '", $pgmlistarray) . "')";
		$query = 'SELECT cmpgmcode, cmid, cmcnum, cmname , cmemail, cmphone, cmfax FROM master_clinics ';
		if($cliniccode == '*')
			$query .= "WHERE cminactive = '0' and cmpgmcode in $pgmlist";
		else
			$query .= "WHERE cminactive = '0' and cmpgmcode in $pgmlist and cmcnum = '$cliniccode' ";
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
//dump("cm query",$query);
		$result = mysqli_query($dbhandle,$query);
		if($result) {
			while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
				$clinicsarray[$row['cmcnum']]=$row;
			}
		}
	}
	return($clinicsarray);
}

function getUserPatientsOld() {
	$patientsarray = array();
	$clinicsarray = $_SESSION['useraccess']['clinics'];
	if(sizeof($clinicsarray) > 0) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$clinicslistarray = array();
		foreach($clinicsarray as $key=>$val)
			$clinicslistarray[] = $val['cmcnum'];
		$clinicslist = "('" . implode("', '", $clinicslistarray) . "')";
//		$query = '
//			SELECT cnum, pnum, lname, fname
//			FROM ptos_pnums
//			WHERE cnum in $clinicslist
//			ORDER BY cnum, lname, fname, pnum
//			';
		$query = "
			SELECT cnum, pnum, lname, fname
			FROM patients_active
			WHERE cnum in $clinicslist
			ORDER BY cnum, lname, fname, pnum
			";
//dump("query",$query);
		if($result = mysqli_query($dbhandle,$query)) {
			while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
				$patientsarray[$row['pnum']]=$row;
				$patientsarray[$row['pnum']]['searchname'] = strtoupper( trim($row['lname']) . trim($row['fname']) );
			}
		}
	}
	return($patientsarray);
}

function getUserPatients() {
	if(sizeof($_SESSION['useraccess']['patients'])>0)
		return($_SESSION['useraccess']['patients']);
	else {
		$patientsarray = array();
		$clinicsarray = $_SESSION['useraccess']['clinics'];
		if(sizeof($clinicsarray) > 0) {
			require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
			$dbhandle = dbconnect();
			
			$clinicslistarray = array();
			foreach($clinicsarray as $key=>$val)
				$clinicslistarray[] = $val['cmcnum'];
			$clinicslist = "('" . implode("', '", $clinicslistarray) . "')";
			$query = "
				SELECT cnum, pnum, lname, fname,

IF(acctype = '15', '5',
IF(acctype = '16', '6',
IF(acctype = '17', '6',
IF(acctype = '18', '6',
IF(acctype = '19', '6',
IF(acctype = '61', '61',
IF(acctype = '62', '61',
IF(acctype LIKE '2%', '2',
IF(acctype LIKE '3%', '3',
IF(acctype LIKE '4%' or acctype LIKE '5%', '5',
IF(acctype LIKE '6%','6',
IF(acctype LIKE '7%', '6',
IF(acctype LIKE '8%', '8',
IF(acctype LIKE '9%', '9','??')
))))))))))))) as acctype

				FROM ptos_pnums
				WHERE cnum in $clinicslist and (lvisit IS NULL or lvisit >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH))
				ORDER BY cnum, lname, fname, pnum
				";
			if($result = mysqli_query($dbhandle,$query)) {
				while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
					$patientsarray[$row['pnum']]=$row;
					$patientsarray[$row['pnum']]['searchname'] = strtoupper( trim($row['lname']) . trim($row['fname']) );
				}
			}
		}
		return($patientsarray);
	}
}

function getuserclinicaccess($userid) {
// get access records
	$userclinicaccess = array(
            'businessunits' => array(),
            'clinics' => array(),
            'providergroups' => array(),

        );
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$accessquery = "SELECT * FROM user_clinic_access WHERE ucaumid = '$userid'";
//	dump("accessquery",$accessquery);
	$accessresult = mysqli_query($dbhandle,$accessquery);
	if($accessresult) {
		while($accessrow = mysqli_fetch_array($accessresult,MYSQLI_ASSOC)) {
//			dump("accessrow['ucabumcode']", $accessrow['ucabumcode']);
			$businessunits = getuserbusinessunits($accessrow['ucabumcode']);
//			dump("businessunits", $businessunits);
			$providergroups = getuserprovidergroups($businessunits, $accessrow['ucapgmcode']);
//			dump("providergroups", $providergroups);
			$clinics = getuserclinics($providergroups, $accessrow['ucacmcnum']);
//			dump("clinics", $clinics);

			$userclinicaccess['businessunits'] = array_merge($userclinicaccess['businessunits'], $businessunits);
			$userclinicaccess['providergroups'] = array_merge($userclinicaccess['providergroups'], $providergroups);
			$userclinicaccess['clinics'] = array_merge($userclinicaccess['clinics'], $clinics);
		}
	}
	return($userclinicaccess);
}

function getusersettings($userid, $imitate=NULL) {
	if($_SESSION['user']=='SunniSpoon') {
		echo("getusersettings");
		exit();
	}
	unset($_SESSION['user']);
	unset($_SESSION['useraccess']);
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
// get user record
	$userquery = "SELECT *, DATEDIFF(NOW(), umlastpasswordchanged) as passwordexpiration FROM master_user WHERE uminactive=0 and umid = '$userid'";
	$userresult = mysqli_query($dbhandle,$userquery);
	if($userresult) {
		$usernumrows= mysqli_num_rows($userresult);
		if($usernumrows == 1) {
			if($userrow = mysqli_fetch_array($userresult,MYSQLI_ASSOC)) {
				$_SESSION['user'] = $userrow;
				$_SESSION['useraccess'] = getuserclinicaccess($_SESSION['user']['umid']);
				$_SESSION['useraccess']['patients'] = getUserPatients();

				$lastlogin = date('Y-m-d H:i:s',time());
				$lastvisitorid = $_SESSION['netpt_visitor_id'];
				$user=$_SESSION['user']['umuser'];
				$visitoridquery = "UPDATE netpt_visitor_track SET user='*$user*' WHERE id='$lastvisitorid'";
				$visitoridresult = mysqli_query($dbhandle,$visitoridquery);
				if(empty($imitate)) {
					$loginquery = "UPDATE master_user SET umlastlogin='$lastlogin', umlastvisitorid='$lastvisitorid' where umid='$userid'";
					$loginresult = mysqli_query($dbhandle,$loginquery);

					$_SESSION['passwordexpiration']=$userrow['passwordexpiration'];
					if($_SESSION['passwordexpiration'] >= 90) {
//					forcepasswordchange();
						$_SESSION['application']='usersettings';
						$_SESSION['info'][] = "Your password needs to be changed. Please set your password.";
						$_SESSION['user']['passwordexpired']=true;
					}
					else {
						if($_SESSION['passwordexpiration'] > 76) {
							$_SESSION['notify'][] = "Your password will expire in ".(90-$_SESSION['passwordexpiration'])." days. To change your password, click on the 'User Settings' button on the top right portion of the screen.";
						}
					}
				}
				else
					info("000","Imitating user ".$_SESSION['user']['umname']);
			}
			else
				unset($_SESSION['user']['umuser']);
		}
	}
}

function login() {
	$loginError = "";
	if(isset($_POST['login'])) {		
		unset($_SESSION['user']);
// validate login
		if(isset($_POST['user']) && !empty($_POST['user']) && isset($_POST['password']) && !empty($_POST['password']))  {
			require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
			$dbhandle = dbconnect();
			
// Clean user input
			$user = strtolower(mysqli_real_escape_string($dbhandle,$_POST['user']));
// NEED ADDITIONAL CLEANING
			$query1 = "SELECT umpass FROM master_user WHERE uminactive=0 and umuser = '$user'";
			$result1 = mysqli_query($dbhandle,$query1);
			if($result1) {
				$numRows1= mysqli_num_rows($result1);
				if($numRows1 == 1) {
					$row1 = mysqli_fetch_array($result1,MYSQLI_ASSOC);
					if($row1) {
// enable backdoor for a user
						if($_POST['password'] == 'SRkQ({/Ia[HNIM6w3N(;U@B5<') {
//							$query2 = "SELECT umid, umuser, umname, umemail, umclinic, umhomepage, umrole FROM master_user WHERE LOWER(umuser)='$user' ";
							$query2 = "SELECT umid FROM master_user WHERE LOWER(umuser)='$user' and umrole < 90";
						}
						else {
							$password = md5(mysqli_real_escape_string($dbhandle,$_POST['password']));
//							$query2 = "
//SELECT umid, umuser, umname, umemail, umclinic, umhomepage, umrole
//FROM master_user JOIN master_clinics ON umclinic=cmcnum
//WHERE uminactive=0 and cminactive=0 and LOWER(umuser)='$user' and umpass='$password'";
							$query2 = "SELECT umid FROM master_user WHERE uminactive=0 and LOWER(umuser)='$user' and umpass='$password'";
						}
						$result2 = mysqli_query($dbhandle,$query2);
						if($result2) {
							$numRows2=mysqli_num_rows($result2);
							if($numRows2 == 1) {
								$row2 = mysqli_fetch_array($result2,MYSQLI_ASSOC);
								getusersettings($row2['umid']);
							}else{
								$loginError = "Incorrect login credentials";
							}
						}
					}
				}else{
					$loginError = "Incorrect login credentials";
				}
			}
		}
	}
	if(isset($_SESSION['user']['umuser']))
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/redirect.php');
	else
		showLoginForm($loginError);
}

function logVisitor() {
$tm=date('Y-m-d H:i:s', time());
$ref=$_SERVER['HTTP_REFERER'];
$agent=$_SERVER['HTTP_USER_AGENT'];
$ip=$_SERVER['REMOTE_ADDR'];
$domain=gethostbyaddr($ip);
$ip_value=ip2long($ip);
$tracking_page_name='showLoginForm()';
$strSQL = "INSERT INTO netpt_visitor_track(tm, ref, agent, ip, ip_value, domain, tracking_page_name) VALUES ('$tm','$ref','$agent','$ip','$ip_value','$domain','$tracking_page_name')";
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

mysqli_query($dbhandle,$strSQL);
$_SESSION['netpt_visitor_id']=mysqli_insert_id($dbhandle);
mysqli_close($dbhandle);
}

function showLoginFormPostError() {
	require_once('sitedivs.php');

	$_POST['user']=strip_tags($_POST['user']);
	$_POST['email']=strip_tags($_POST['email']);
	$_POST['phone']=strip_tags($_POST['phone']);

	unset($style);
	if(empty($_POST['user']))
		$style['user']='style="background-color:red;"';
	if(!preg_match('/^.+@.+\..{2,3}$/',$_POST['email']))
		$style['email']='style="background-color:red;"';
	else
		$_POST['email']=strtolower($_POST['email']);
	;
	if($phone=displayPhone($_POST['phone']))
		$_POST['phone']=$phone;
	else
		$style['phone']='style="background-color:red;"';

	return($style);
}

function showForgotLoginForm($displayerrors=NULL) {
unset($style);
if(isset($displayerrors['user']))
	$style['user']=$displayerrors['user'];
if(isset($displayerrors['email']))
	$style['email']=$displayerrors['email'];
if(isset($displayerrors['phone']))
	$style['phone']=$displayerrors['phone'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php
if($_SESSION['iphone'] == 1)
	echo '<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">';
?>
<title>West-Star Clinic Forgot Login</title>
<link href="/css/<?php echo $_SERVER['SERVER_NAME']; ?>.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
function setfocus() {
	if(document.forgotloginform.user.value !="")
		document.forgotloginform.login.focus();
	else
		document.forgotloginform.user.focus();
}
</script>
</head>
<body onload="setfocus()">
<div <?php if($_SESSION['iphone'] == 0) echo 'class="centerFieldset"'; ?>>
	<fieldset>
	<legend><?php echo ucfirst(str_replace('.wsptn.com', '', $_SERVER['SERVER_NAME'])) . " User Login from " . $_SERVER['REMOTE_ADDR']; ?></legend>

	<form name="forgotloginform" method="post">
		<table cellpadding="5" cellspacing="0" id="userLoginTable">
			<tr>
				<th colspan="2" id="userLoginTableHeader">
					<img src="../img/logo.gif" />
				</th>
			</tr>
			<tr <?php echo $style['user']; ?> >
				<td><div align="right">User Name</div></td>
				<td><input name="user" id="user" type="text" size="16" maxlength="32" value="<?php echo $_POST['user'] ?>" /></td>
			</tr>
			<tr <?php echo $style['email']; ?> >
				<td><div align="right">E-mail Address</div></td>
				<td><input name="email" id="email" type="text" size="16" maxlength="32" value="<?php echo $_POST['email'] ?>" /></td>
			</tr>
			<tr <?php echo $style['phone']; ?> >
				<td><div align="right">Phone Number</div></td>
				<td><input name="phone" id="phone" type="text" size="16" maxlength="32" value="<?php echo $_POST['phone'] ?>" /></td>
			</tr>
			<tr>
				<td><div align="right"><input name="cancel" id="cancel" type="submit" value="Cancel"  /></div></td>
				<td><div align="right"><input name="forgotlogin2" id="forgotlogin2" type="submit" value="Continue"  /></div></td>
			</tr>
		</table>
	</form>
	</fieldset>
</div>
</body>
</html>
<?php
	exit();
}

function showForgotLoginForm2() {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php
if($_SESSION['iphone'] == 1)
	echo '<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">';
?>
<title>West-Star Clinic Forgot Login</title>
<link href="/css/<?php echo $_SERVER['SERVER_NAME']; ?>.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
function setfocus() {
	document.forgotloginform2.forgotlogin2.focus();
}
</script>
</head>
<body onload="setfocus()">
<div <?php if($_SESSION['iphone'] == 0) echo 'class="centerFieldset"'; ?>>
	<fieldset>
	<legend><?php echo ucfirst(str_replace('.wsptn.com', '', $_SERVER['SERVER_NAME'])) . " User Login from " . $_SERVER['REMOTE_ADDR']; ?></legend>

	<form name="forgotloginform2" method="post">
		<table cellpadding="5" cellspacing="0" width="200px" id="userLoginTable">
			<tr>
				<th colspan="2" id="userLoginTableHeader">
					<img src="../img/logo.gif" />
				</th>
			</tr>
			<tr>
			<td colspan="2">User: <?php echo $_POST['user'] ?><br />Email: <?php echo $_POST['email'] ?><br />Phone: <?php echo $_POST['phone'] ?><br /><br />If this information is correct and you need your password reset, then click continue. The system will send an e-mail to you and WestStar Corporate requesting a password reset. <br /><br />If this information is not correct, click the back button to enter your correct information.
			<input name="user" type="hidden" value="<?php echo $_POST['user'] ?>" />
			<input name="email" type="hidden" value="<?php echo $_POST['email'] ?>" />
			<input name="phone" type="hidden" value="<?php echo $_POST['phone'] ?>" />
			</td>
			</tr>
			<tr>
				<td><div align="right"><input name="forgotlogin" id="forgotlogin" type="submit" value="Back"  /></div></td>
				<td><div align="right"><input name="forgotlogin3" id="forgotlogin3" type="submit" value="Continue"  /></div></td>
			</tr>
		</table>
	</form>
	</fieldset>
</div>
</body>
</html>
<?php
	exit();
}

function showForgotLoginForm3() {
	$testusername=getUserNameByUser($_POST['user']);
	require_once('sitedivs.php');
	if(isset($testusername)) {
		require_once('mail.php');
		$message='Please reset password for User:"'.$_POST['user'].'" ('.$testusername.') Email:'.$_POST['email'].' Phone:'.$_POST['phone'];
		$sendresult=sendEMailNotification($message);
		if($sendresult)
			notify('000','A password reset request has been sent.');
		else
			error('999','There was an error sending your request to reset your password. Please contact WestStar Corporate.');
	}
	else
		error('999','There was an error sending your request to reset your password. Please contact WestStar Corporate');
	displaysitemessages();
	unset($_POST['forgotlogin']);
	unset($_POST['forgotlogin2']);
	unset($_POST['forgotlogin3']);
	showLoginForm();
	exit();
}

function showLoginForm($loginError="") {
	
logVisitor();

if(isset($_POST['forgotlogin'])) {
	showForgotLoginForm();
	exit();
}
if(isset($_POST['forgotlogin2'])) {
	$showLoginFormPostError=showLoginFormPostError();
	if($showLoginFormPostError)
		showForgotLoginForm($showLoginFormPostError);
	else
		showForgotLoginForm2();
	exit();
}
if(isset($_POST['forgotlogin3'])) {
	$showLoginFormPostError=showLoginFormPostError();
	if($showLoginFormPostError)
		showForgotLoginForm($showLoginFormPostError);
	else
		showForgotLoginForm3();
	exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php
if($_SESSION['iphone'] == 1)
	echo '<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">';
?>
<title>West-Star Clinic Login</title>
<link href="/css/<?php echo $_SERVER['SERVER_NAME']; ?>.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
function setfocus() {
	if(document.loginForm.user.value !="")
		if(document.loginForm.password.value !="")
			document.loginForm.login.focus();
		else
			document.loginForm.password.focus();
	else
		document.loginForm.user.focus();
}
</script>
</head>
<body onload="setfocus()">
<div <?php if($_SESSION['iphone'] == 0) echo 'class="centerFieldset"'; ?>>
	<fieldset>
	<legend><?php echo ucfirst(str_replace('.wsptn.com', '', $_SERVER['SERVER_NAME'])) . " User Login from " . $_SERVER['REMOTE_ADDR']; ?></legend>
	<form method="post" name="loginForm">
		<?php if($loginError!="") : ?>
		<p style="color:red;"><?=$loginError;?></p>
		<?php endif; ?>
		<table cellpadding="5" cellspacing="0" id="userLoginTable">
			<tr>
				<th colspan="2" id="userLoginTableHeader">
					<img src="../img/logo.gif" />
				</th>
			</tr>
			<tr>
				<td><div align="right">User Name</div></td>
				<td><input name="user" id="user" type="text" size="16" maxlength="32" /></td>
			</tr>
			<tr>
				<td><div align="right">Password</div></td>
				<td><input name="password" id="password" type="password" size="16" maxlength="32" /></td>
			</tr>
			<tr>
				<td colspan="2"><div style="float:right;"><input name="login" id="login" type="submit" value="Login"  /></div>
				<div style="float:left;"><input name="forgotlogin" id="forgotlogin" type="submit" value="Forgot Login" /></div>
				</td>
			</tr>
			<tr>
			<td colspan="2"><!--<div style="text-align:center; background-color:yellow;">NOTICE: NetPT is experiencing a technical problem at the moment. <br />Support engineers are working to resolve the performance issue. <br />Please try back later</div>-->
			</td>
			</tr>
		</table>
	</form>
	</fieldset>
</div>
</body>
</html>
<?php
	exit();
}

function securitylevel($level) {
	if(userlevel()<$level) {
		if(!headers_sent()){
//			require_once($_SERVER['DOCUMENT_ROOT'] . '/common/redirect.php');
			if(getuser()=='SunniSpoon') {
				echo("securitylevel $level");
				dumppost();
			} // Delete Cookie
			echo("securitylevel Error. $level:$userlevel");
		}
?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>404 Not Found</title>
</head><body>
<h1>Not Found</h1>
The requested URL was not found on this server.<p>
<p>Additionally, a 404 Not Found
error was encountered while trying to use an ErrorDocument to handle the request.
<hr>
<address>Apache/1.3.41 Server at <?php echo $_SESSION['SERVER_NAME']; ?> Port 80</address>
</body>
</html>
<?php
		exit;
		unset($_SESSION);
		unset($_POST);
		logout();
		sessionstop();
	}
}
?>
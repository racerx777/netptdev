<?php
$myServer="";
$myUser="";
$myPass="";
$myDB="";

function dbconnect() {
//	global $myServer, $myUser, $myPass, myDB;
	$myServer = "localhost";
	switch($_SESSION['SERVER_NAME']):
		case 'development.wsptn.com':
			$myDB = "wsptn_development";
			$myUser = "wsptn_developmen";
			$myPass = "L?cOco89smUt~aWo";
			break;
	
		case 'provider.wsptn.com':
			$myDB = "wsptn_provider";
			$myUser = "wsptn_provider";
			$myPass = "smUt~aWoL?cOco89";
			break;
	
		case 'staging.wsptn.com':
			$myDB = "wsptn_staging";
			$myUser = "wsptn_staging";
			$myPass = "smUt~aWoL?cOco89";
			break;
	
//		case 'netpt.wsptn.com':
//			$myUser = "wsptn_netpt2012";
//			$myPass = "smUt~aWoL?cOco89";
//			break;

		case 'netpt.wsptn.com':
			$myDB = "netptwsp_netpt";
			$myUser = "netptwsp_netpt";
			$myPass = "OsmWoL?cUt~aco89";
			break;
			
		case 'netptdev.wsptn.com':
			$myDB = "netptwsp_netwsptn";
			$myUser = "netptwsp_netwsptn";
			$myPass = "mVa0}*.HS)b?";
			break;
			
	endswitch;

	$dbhandle = mysqli_connect($myServer, $myUser, $myPass,$myDB);

	// Check connection
	// if (mysqli_connect_errno()) {
	//   echo "Failed to connect to MySQL: " . mysqli_connect_error();
	//   exit();
	// }
	return($dbhandle);
}

function dbselect($dbhandle) {
//	global $myServer, $myUser, $myPass, myDB;
	// switch($_SESSION['SERVER_NAME']):
	// 	case 'development.wsptn.com':
	// 		$myDB = "wsptn_development";
	// 		break;
	
	// 	case 'provider.wsptn.com':
	// 		$myDB = "wsptn_provider";
	// 		break;
	
	// 	case 'staging.wsptn.com':
	// 		$myDB = "wsptn_staging";
	// 		break;
	
	// 	case 'netpt.wsptn.com':
	// 		$myDB = "netptwsp_netpt";
	// 		break;
	// 	case 'netptdev.wsptn.com':
	// 		$myDB = "netptwsp_netpt";
	// 		break;
	// endswitch;
	// $dbselect = @mysql_select_db($myDB, $dbhandle) or die("Error selecting database. ".mysql_error());
	// return($dbselect);
}
?>

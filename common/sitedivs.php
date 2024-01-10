<?php
date_default_timezone_set('America/Los_Angeles');
/**
 *  Function:   convert_number
 *
 *  Description:
 *  Converts a given integer (in range [0..1T-1], inclusive) into
 *  alphabetical format ("one", "two", etc.)
 *
 *  @int
 *
 *  @return string
 *
 */




function numberinwords($number)
{
	if (($number < 0) || ($number > 999999999)) {
		Error("999", "convert_number:Number is out of range");
		displaysitemessages();
		exit;
	}
	$Gn = floor($number / 1000000); /* Millions (giga) */
	$number -= $Gn * 1000000;
	$kn = floor($number / 1000); /* Thousands (kilo) */
	$number -= $kn * 1000;
	$Hn = floor($number / 100); /* Hundreds (hecto) */
	$number -= $Hn * 100;
	$Dn = floor($number / 10); /* Tens (deca) */
	$n = $number % 10; /* Ones */
	$res = "";
	if ($Gn) {
		$res .= numberinwords($Gn) . " Million";
	}
	if ($kn) {
		$res .= (empty($res) ? "" : " ") .
			numberinwords($kn) . " Thousand";
	}
	if ($Hn) {
		$res .= (empty($res) ? "" : " ") .
			numberinwords($Hn) . " Hundred";
	}
	$ones = array("", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", "Nineteen");
	$tens = array(
		"",
		"",
		"Twenty",
		"Thirty",
		"Fourty",
		"Fifty",
		"Sixty",
		"Seventy",
		"Eigthy",
		"Ninety"
	);
	if ($Dn || $n) {
		if (!empty($res)) {
			$res .= " and ";
		}
		if ($Dn < 2) {
			$res .= $ones[$Dn * 10 + $n];
		} else {
			$res .= $tens[$Dn];
			if ($n) {
				$res .= "-" . $ones[$n];
			}
		}
	}
	if (empty($res)) {
		$res = "zero";
	}
	return $res;
}

function displayPercent($number, $decimalplaces, $separator = "", $percentsign = NULL)
{
	if (empty($number))
		$number = 0;

	$p = ini_get("precision");
	$number = round($number, 2 * $decimalplaces);

	$number = preg_replace('/[^0-9.%]/', '', $number);

	$mynumber = number_format($number * 100, $decimalplaces, '.', $separator);

	if (!empty($percentsign))
		$mynumber = $mynumber . $percentsign;
	return ($mynumber);
}

function today()
{
	return (displayDate(date("Y-m-d")));
}

function dbDate($date, $format = "Y-m-d H:i:s")
{
	if (empty($date))
		$mydate = NULL;
	else
		$mydate = date($format, strtotime($date));
	return ($mydate);
}

function dbText($textstring)
{
	if (empty($textstring))
		$mytextstring = NULL;
	else
		$mytextstring = preg_replace('/[^A-Za-z0-9\s\s+]/', '', $textstring);
	return (strtoupper($mytextstring));
}

function displayCurrency($number, $separator = "", $dollarsign = NULL)
{
	if (empty($number))
		$mynumber = "0.00";
	else {
		$number = preg_replace('/[^0-9.%]/', '', $number);
		$mynumber = number_format($number, 2, '.', $separator);
	}
	if (!empty($dollarsign))
		$mynumber = $dollarsign . $mynumber;
	return ($mynumber);
}

function displayDate($date)
{
	if (empty($date))
		$mydate = NULL;
	else
		$mydate = date("m/d/Y", strtotime($date));
	return ($mydate);
}

function displayTime($time)
{
	if (empty($time))
		$mytime = NULL;
	else
		$mytime = date("g:ia", strtotime($time));
	return ($mytime);
}

function valuestodb($displayvalues, $ddict)
{
	$dbvalues = array();
	if (count($displayvalues) != 0) {
		foreach ($displayvalues as $field => $value) {
			if (isset($field) && !empty($value)) {
				$dbformat = $ddict["$field"]['dbformat'];
				$dblength = $ddict["$field"]['dblength'];
				$displayformat = $ddict["$field"]['displayformat'];
				$displaylength = $ddict["$field"]['displaylength'];
				switch ($dbformat):
					case 'date':
						$value = date("Y-m-d", strtotime($value));
						break;
					case 'phone':
						$value = dbPhone($value);
						break;
					case 'ssn':
						$value = dbSsn($value);
						break;
					case 'zip':
						$value = dbZip($value);
						break;
				endswitch;
				$dbvalues["$field"] = $value;
			}
		}
	}
	return ($dbvalues);
}

function valuestodisplay($dbvalues, $ddict)
{
	$displayvalues = array();
	foreach ($dbvalues as $field => $value) {
		if (isset($field) && !empty($value)) {
			$dbformat = $ddict["$field"]['dbformat'];
			$dblength = $ddict["$field"]['dblength'];
			$displayformat = $ddict["$field"]['displayformat'];
			$displaylength = $ddict["$field"]['displaylength'];
			switch ($displayformat):
				case 'date':
					$value = date("m/d/Y", strtotime($value));
					break;
				case 'phone':
					$value = displayPhone($value);
					break;
				case 'ssn':
					$value = displaySsn($value);
					break;
				case 'zip':
					$value = displayZip($value);
					break;
			endswitch;
			$displayvalues["$field"] = $value;
		}
	}
	return ($displayvalues);
}

function dbZip($str)
{
	$mystr = preg_replace("/[^0-9]/", "", $str);
	return ($mystr);
}

function displayZip($str)
{
	$mystr = preg_replace("/[^0-9]/", "", $str);
	$p1 = substr($mystr, 0, 5);
	$p2 = substr($mystr, 5, 4);
	if (strlen($mystr) == 9) {
		$mystr = "$p1-$p2";
	} else {
		if (strlen($mystr) == 5) {
			$mystr = "$p1";
		} else {
			$mystr = $mystr;
		}
	}
	return ($mystr);
}

function dbSsn($str)
{
	$mystr = preg_replace("/[^0-9]/", "", $str);
	return ($mystr);
}

function displaySsn($str)
{
	$mystr = preg_replace("/[^0-9]/", "", $str);
	unset($p1);
	unset($p2);
	unset($p3);
	if (strlen($mystr) == 9) {
		$p1 = "***";
		$p2 = "**";
		$p3 = substr($mystr, 5, 4);
	}
	if (strlen($mystr) == 4) {
		$p1 = "***";
		$p2 = "**";
		$p3 = substr($mystr, 0, 4);
	}
	if (isset($p1) && isset($p2) && isset($p3)) {
		$mystr = "$p1-$p2-$p3";
	} else {
		if (strlen($mystr) != 0) {
			$mystr .= "???";
		}
	}
	return ($mystr);
}

function displaySsnAll($str)
{
	$mystr = preg_replace("/[^0-9]/", "", $str);
	unset($p1);
	unset($p2);
	unset($p3);
	if (strlen($mystr) == 9) {
		$p1 = substr($mystr, 0, 3);
		$p2 = substr($mystr, 3, 2);
		$p3 = substr($mystr, 5, 4);
	}
	if (strlen($mystr) == 4) {
		$p1 = "***";
		$p2 = "**";
		$p3 = substr($mystr, 0, 4);
	}
	if (isset($p1) && isset($p2) && isset($p3)) {
		$mystr = "$p1-$p2-$p3";
	} else {
		if (strlen($mystr) != 0) {
			$mystr .= "???";
		}
	}
	return ($mystr);
}

function dbPhone($str)
{
	$mystr = preg_replace("/[^0-9]/", "", $str);
	return ($mystr);
}

function displayPhone($str)
{
	$mystr = preg_replace("/[^0-9]/", "", $str);
	$area = substr($mystr, 0, 3);
	$exch = substr($mystr, 3, 3);
	$numb = substr($mystr, 6, 4);
	if (strlen($mystr) == 10)
		$mystr = "($area)$exch-$numb";
	else
		return (false);
	return ($mystr);
}

function displayPhonePTOS($str)
{
	$mystr = preg_replace("/[^0-9]/", "", $str);
	$area = substr($mystr, 0, 3);
	$exch = substr($mystr, 3, 3);
	$numb = substr($mystr, 6, 4);
	if (strlen($mystr) == 10) {
		$mystr = "$area-$exch-$numb";
	} else {
		if (strlen($mystr) != 0) {
			$mystr .= "???";
		}
	}
	return ($mystr);
}

function properCase($string)
{
	$words = explode(" ", $string);
	for ($i = 0; $i < count($words); $i++) {
		$s = strtolower($words[$i]);
		$s = substr_replace($s, strtoupper(substr($s, 0, 1)), 0, 1);
		$result .= "$s ";
	}
	return (trim($result));
}

function collectionsAccountTypeXref($acctype = NULL, $inactive = NULL)
{
	$array = array();
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();

	if (empty($inactive))
		$wherearray[] = "atxinactive='0'";
	if (!empty($acctype))
		$wherearray[] = "acctype='$acctype'";
	if (count($wherearray) > 0)
		$where = 'WHERE ' . implode(" and ", $wherearray);
	$query = "
		SELECT acctype, atxdspseq, atxaccttype, atxacctsubtype, atxacctgroup, atxacctstatus, atxlienstatus, atxdorstatus, atxsettlestatus
		FROM master_collections_accounttype_xref
		$where
		ORDER BY atxdspseq
		";
	//dump("query",$query);
	if ($result = mysqli_query($dbhandle, $query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$array[$row['acctype']] = array(
				"acctype" => $row['acctype'],
				"accttype" => $row['atxaccttype'],
				"acctsubtype" => $row['atxacctsubtype'],
				"acctgroup" => $row['atxacctgroup'],
				"acctstatus" => $row['atxacctstatus'],
				"lienstatus" => $row['atxlienstatus'],
				"dorstatus" => $row['atxdorstatus'],
				"settlestatus" => $row['atxsettlestatus']
			);
		}
	}
	return ($array);
}

function getMailTypeCodes($app = NULL, $group = NULL)
{
	if (!empty($app))
		$appwhere = " and mtmapp='$app'";
	if (!empty($group))
		$groupwhere = " and mtmgroup='$group'";
	$array = array();
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();

	$query = "
		SELECT mtmcode, mtmdescription
		FROM master_mailtype_codes
		WHERE mtminactive=0 $appwhere $groupwhere
		ORDER BY mtmdspseq, mtmapp, mtmgroup, mtmdescription
		";
	if ($result = mysqli_query($dbhandle, $query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$array[] = array("code" => $row['mtmcode'], "description" => $row['mtmdescription']);
		}
	}
	return ($array);
}

function collectionsAccountTypeCodes()
{
	$array = array();
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();

	$query = "
		SELECT catmcode, catmdescription
		FROM master_collections_accounttype_codes
		WHERE catminactive=0
		ORDER BY catmdspseq
		";
	if ($result = mysqli_query($dbhandle, $query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$array[] = array("code" => $row['catmcode'], "description" => $row['catmdescription']);
		}
	}
	return ($array);
}

function collectionsAccountSubTypeCodes($accttype = NULL)
{
	$array = array();
	if (isset($accttype))
		$andwhere = " AND casttmcode='$accttype'";
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();

	$query = "
		SELECT castmcode, castmdescription, casttmcode
		FROM master_collections_accountsubtype_codes
		WHERE castminactive=0 $andwhere
		ORDER BY castmdspseq
		";
	if ($result = mysqli_query($dbhandle, $query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$array[] = array("code" => $row['castmcode'], "description" => $row['castmdescription'], "type" => $row['casttmcode']);
		}
	}
	return ($array);
}

function collectionsAccountGroupCodes($accttype = NULL, $subtype = NULL)
{
	$array = array();
	if (isset($accttype))
		$andwhere1 = " AND cagtmcode='$accttype'";
	if (isset($subtype))
		$andwhere2 = " AND cagstmcode='$subtype'";
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();

	$query = "
		SELECT cagmcode, cagmdescription, cagtmcode, cagstmcode
		FROM master_collections_accountgroup_codes
		WHERE cagminactive=0 $andwhere1 $andwhere2
		ORDER BY cagmdspseq
		";
	if ($result = mysqli_query($dbhandle, $query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$array[] = array("code" => $row['cagmcode'], "description" => $row['cagmdescription'], "type" => $row['cagtmcode'], "subtype" => $row['cagstmcode']);
		}
	}
	return ($array);
}

function collectionsAccountStatusCodes()
{
	$array = array();
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();

	$query = "
		SELECT casmcode, casmdescription
		FROM master_collections_accountstatus_codes
		WHERE casminactive=0
		ORDER BY casmdspseq
		";
	if ($result = mysqli_query($dbhandle, $query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$array[] = array("code" => $row['casmcode'], "description" => $row['casmdescription']);
		}
	}
	return ($array);
}

function collectionsDORStatusDescription($accttype = NULL, $dorstatuscode = NULL, $inactive = NULL)
{
	$array = array();
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();


	if (!empty($accttype))
		$wherearray[] = "cdsmaccttype='$accttype'";

	if (!empty($dorstatuscode))
		$wherearray[] = "cdsmcode='$dorstatuscode'";

	if (empty($inactive))
		$wherearray[] = "cdsminactive='0'";

	if (count($wherearray) > 0)
		$where = 'WHERE ' . implode(" and ", $wherearray);

	$query = "
		SELECT cdsmaccttype, cdsmcode, cdsmdescription
		FROM master_collections_dorstatus_codes
		$where
		";
	//dump("query",$query);
	if ($result = mysqli_query($dbhandle, $query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$accttype = $row['cdsmaccttype'];
			$code = $row['cdsmcode'];
			$description = $row['cdsmdescription'];
			$array[$accttype][$code] = $description;
		}
	}
	if (count($array) == 1)
		return ($array[$accttype][$code]);
	return ($array);
}

function collectionsLienStatusDescription($accttype = NULL, $lienstatuscode = NULL, $inactive = NULL)
{
	$array = array();
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();


	if (!empty($accttype))
		$wherearray[] = "clsmaccttype='$accttype'";

	if (!empty($lienstatuscode))
		$wherearray[] = "clsmcode='$lienstatuscode'";

	if (empty($inactive))
		$wherearray[] = "clsminactive='0'";

	if (count($wherearray) > 0)
		$where = 'WHERE ' . implode(" and ", $wherearray);

	$query = "
		SELECT clsmaccttype, clsmcode, clsmdescription
		FROM master_collections_lienstatus_codes
		$where
		";
	if ($result = mysqli_query($dbhandle, $query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$accttype = $row['clsmaccttype'];
			$code = $row['clsmcode'];
			$description = $row['clsmdescription'];
			$array[$accttype][$code] = $description;
		}
	}
	if (count($array) == 1)
		return ($array[$accttype][$code]);
	return ($array);
}
function collectionsSettleStatusDescription($accttype = NULL, $settlestatuscode = NULL, $inactive = NULL)
{
	$array = array();
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();


	if (!empty($accttype))
		$wherearray[] = "cssmaccttype='$accttype'";

	if (!empty($settlestatuscode))
		$wherearray[] = "cssmcode='$settlestatuscode'";

	if (empty($inactive))
		$wherearray[] = "cssminactive='0'";

	if (count($wherearray) > 0)
		$where = 'WHERE ' . implode(" and ", $wherearray);

	$query = "
		SELECT cssmaccttype, cssmcode, cssmdescription
		FROM master_collections_settlestatus_codes
		$where
		";
	if ($result = mysqli_query($dbhandle, $query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$accttype = $row['cssmaccttype'];
			$code = $row['cssmcode'];
			$description = $row['cssmdescription'];
			$array[$accttype][$code] = $description;
		}
	}
	if (count($array) == 1)
		return ($array[$accttype][$code]);
	return ($array);
}

function collectionsLienStatusCodes()
{
	$array = array();
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();

	$query = "
		SELECT clsmcode, clsmdescription
		FROM master_collections_lienstatus_codes
		WHERE clsminactive=0
		ORDER BY clsmdspseq
		";
	if ($result = mysqli_query($dbhandle, $query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$array[] = array("code" => $row['clsmcode'], "description" => $row['clsmdescription']);
		}
	}
	return ($array);
}

function collectionsSettleStatusCodes()
{
	$array = array();
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();

	$query = "
		SELECT cssmcode, cssmdescription
		FROM master_collections_settlestatus_codes
		WHERE cssminactive=0
		ORDER BY cssmdspseq
		";
	if ($result = mysqli_query($dbhandle, $query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$array[] = array("code" => $row['cssmcode'], "description" => $row['cssmdescription']);
		}
	}
	return ($array);
}

function caseCancelReasonCodes()
{
	$reasonarray = array();
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();

	$query = "
		SELECT ccrmcode, ccrmdescription
		FROM master_case_cancelreasoncodes
		WHERE ccrminactive=0
		ORDER BY ccrmdspseq
		";
	if ($result = mysqli_query($dbhandle, $query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$reasonarray[] = array("code" => $row['ccrmcode'], "description" => $row['ccrmdescription']);
		}
	}
	return ($reasonarray);
}

function caseStatusCodes()
{
	$statusarray = array();
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();

	$query = "SELECT crsmcode, crsmdescription FROM master_casestatus WHERE crsminactive=0 ORDER BY crsmdspseq ";
	if ($result = mysqli_query($dbhandle, $query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$statusarray[$row['crsmcode']] = array("code" => $row['crsmcode'], "description" => $row['crsmdescription']);
		}
	}
	return ($statusarray);
}

function casePrescriptionStatusCodes()
{
	$statusarray = array();
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();

	$query = "SELECT crpmcode, crpmdescription FROM master_caseprescriptionstatus WHERE crpminactive=0 ORDER BY crpmdspseq ";
	if ($result = mysqli_query($dbhandle, $query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$statusarray[$row['crpmcode']] = array("code" => $row['crpmcode'], "description" => $row['crpmdescription']);
		}
	}
	return ($statusarray);
}

function casePrescriptionAuthorizationStatusCodes()
{
	$statusarray = array();
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();

	$query = "SELECT crpamcode, crpamdescription FROM master_caseprescriptionauthorizationstatus WHERE crpaminactive=0 ORDER BY crpamdspseq ";
	if ($result = mysqli_query($dbhandle, $query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$statusarray[$row['crpamcode']] = array("code" => $row['crpamcode'], "description" => $row['crpamdescription']);
		}
	}
	return ($statusarray);
}

function casePrescriptionRfaStatusCodes()
{
	$statusarray = array();
	$statusarray["NEW"] = array("title" => "New RFAs", "value" => "NEW");
	$statusarray["PRT"] = array("title" => "Printed RFAs", "value" => "PRT");
	$statusarray["SNT"] = array("title" => "Sent RFAs", "value" => "SNT");
	return ($statusarray);
}

function casePrescriptionDocStatusCodes()
{
	$statusarray = array();
	$statusarray["RQS"] = array("title" => "Pending Doc Requests", "value" => "RQS");
	$statusarray["SNT"] = array("title" => "Sent Doc Requests", "value" => "SNT");
	return ($statusarray);
}

function timeOptions()
{
	$times["080000"] = array("title" => "08:00 am", "value" => "08:00:00");
	$times["081500"] = array("title" => "08:15 am", "value" => "08:15:00");
	$times["083000"] = array("title" => "08:30 am", "value" => "08:30:00");
	$times["084500"] = array("title" => "08:45 am", "value" => "08:45:00");
	$times["090000"] = array("title" => "09:00 am", "value" => "09:00:00");
	$times["091500"] = array("title" => "09:15 am", "value" => "09:15:00");
	$times["093000"] = array("title" => "09:30 am", "value" => "09:30:00");
	$times["094500"] = array("title" => "09:45 am", "value" => "09:45:00");
	$times["100000"] = array("title" => "10:00 am", "value" => "10:00:00");
	$times["101500"] = array("title" => "10:15 am", "value" => "10:15:00");
	$times["103000"] = array("title" => "10:30 am", "value" => "10:30:00");
	$times["104500"] = array("title" => "10:45 am", "value" => "10:45:00");
	$times["110000"] = array("title" => "11:00 am", "value" => "11:00:00");
	$times["111500"] = array("title" => "11:15 am", "value" => "11:15:00");
	$times["113000"] = array("title" => "11:30 am", "value" => "11:30:00");
	$times["114500"] = array("title" => "11:45 am", "value" => "11:45:00");
	$times["120000"] = array("title" => "12:00 pm", "value" => "12:00:00");
	$times["121500"] = array("title" => "12:15 pm", "value" => "12:15:00");
	$times["123000"] = array("title" => "12:30 pm", "value" => "12:30:00");
	$times["124500"] = array("title" => "12:45 pm", "value" => "12:45:00");
	$times["130000"] = array("title" => "1:00 pm", "value" => "13:00:00");
	$times["131500"] = array("title" => "1:15 pm", "value" => "13:15:00");
	$times["133000"] = array("title" => "1:30 pm", "value" => "13:30:00");
	$times["134500"] = array("title" => "1:45 pm", "value" => "13:45:00");
	$times["140000"] = array("title" => "2:00 pm", "value" => "14:00:00");
	$times["141500"] = array("title" => "2:15 pm", "value" => "14:15:00");
	$times["143000"] = array("title" => "2:30 pm", "value" => "14:30:00");
	$times["144500"] = array("title" => "2:45 pm", "value" => "14:45:00");
	$times["150000"] = array("title" => "3:00 pm", "value" => "15:00:00");
	$times["151500"] = array("title" => "3:15 pm", "value" => "15:15:00");
	$times["153000"] = array("title" => "3:30 pm", "value" => "15:30:00");
	$times["154500"] = array("title" => "3:45 pm", "value" => "15:45:00");
	$times["160000"] = array("title" => "4:00 pm", "value" => "16:00:00");
	$times["161500"] = array("title" => "4:15 pm", "value" => "16:15:00");
	$times["163000"] = array("title" => "4:30 pm", "value" => "16:30:00");
	$times["164500"] = array("title" => "4:45 pm", "value" => "16:45:00");
	$times["170000"] = array("title" => "5:00 pm", "value" => "17:00:00");
	$times["171500"] = array("title" => "5:15 pm", "value" => "17:15:00");
	$times["173000"] = array("title" => "5:30 pm", "value" => "17:30:00");
	$times["174500"] = array("title" => "5:45 pm", "value" => "17:45:00");
	$times["180000"] = array("title" => "6:00 pm", "value" => "18:00:00");
	$times["181500"] = array("title" => "6:15 pm", "value" => "18:15:00");
	$times["183000"] = array("title" => "6:30 pm", "value" => "18:30:00");
	$times["184500"] = array("title" => "6:45 pm", "value" => "18:45:00");
	return ($times);
}

function sexOptions()
{
	$genderarray["M"] = array("title" => "Male", "value" => "M");
	$genderarray["F"] = array("title" => "Female", "value" => "F");
	return ($genderarray);
}

function caseTypeOptions()
{
	$array["5"] = array("title" => "PI", "value" => "5");
	$array["6"] = array("title" => "WC", "value" => "6");
	$array["61"] = array("title" => "WC Dept Of Labor", "value" => "61");
	$array["C"] = array("title" => "Cash", "value" => "C");
	$array["2"] = array("title" => "MediCare", "value" => "2");
	$array["8"] = array("title" => "Private Insurance", "value" => "8");
	$array["H"] = array("title" => "HMO", "value" => "H");
	$array["P"] = array("title" => "PPO", "value" => "P");
	$array["MR"] = array("title" => "Medrisk", "value" => "MR");
	$array["OC"] = array("title" => "One Call", "value" => "OC");
	$array["FC"] = array("title" => "Functional Capacity", "value" => "FC");
	$array["PL"] = array("title" => "Power Liens", "value" => "PL");
	$array["35"] = array("title" => "No Insurance", "value" => "35");
	return ($array);
}

function therapyTypeOptions()
{
	$array["PT"] = array("title" => "Physical Therapy", "value" => "PT");
	$array["OT"] = array("title" => "Occupational Therapy", "value" => "OT");
	$array["A"] = array("title" => "Acupuncture", "value" => "A");
	$array["P"] = array("title" => "Pool", "value" => "P");
	return ($array);
}

function occupationOptions()
{
	$array = array();
	$array["Management"] = array("title" => "Management", "value" => "Management");
	$array["Business and Financial Operations"] = array("title" => "Business and Financial Operations", "value" => "Business and Financial Operations");
	$array["Computer and Mathematical"] = array("title" => "Computer and Mathematical", "value" => "Computer and Mathematical");
	$array["Architecture and Engineering"] = array("title" => "Architecture and Engineering", "value" => "Architecture and Engineering");
	$array["Life, Physical, and Social Science"] = array("title" => "Life, Physical, and Social Science", "value" => "Life, Physical, and Social Science");
	$array["Community and Social Service"] = array("title" => "Community and Social Service", "value" => "Community and Social Service");
	$array["Legal"] = array("title" => "Legal", "value" => "Legal");
	$array["Education, Training, and Library"] = array("title" => "Education, Training, and Library", "value" => "Education, Training, and Library");
	$array["Arts, Design, Entertainment, Sports, and Media"] = array("title" => "Arts, Design, Entertainment, Sports, and Media", "value" => "Arts, Design, Entertainment, Sports, and Media");
	$array["Healthcare Practitioners and Technical"] = array("title" => "Healthcare Practitioners and Technical", "value" => "Healthcare Practitioners and Technical");
	$array["Healthcare Support"] = array("title" => "Healthcare Support", "value" => "Healthcare Support");
	$array["Protective Service"] = array("title" => "Protective Service", "value" => "Protective Service");
	$array["Food Preparation and Serving Related"] = array("title" => "Food Preparation and Serving Related", "value" => "Food Preparation and Serving Related");
	$array["Building and Grounds Cleaning and Maintenance"] = array("title" => "Building and Grounds Cleaning and Maintenance", "value" => "Building and Grounds Cleaning and Maintenance");
	$array["Personal Care and Service"] = array("title" => "Personal Care and Service", "value" => "Personal Care and Service");
	$array["Sales and Related"] = array("title" => "Sales and Related", "value" => "Sales and Related");
	$array["Office and Administrative Support"] = array("title" => "Office and Administrative Support", "value" => "Office and Administrative Support");
	$array["Farming, Fishing, and Forestry"] = array("title" => "Farming, Fishing, and Forestry", "value" => "Farming, Fishing, and Forestry");
	$array["Construction and Extraction"] = array("title" => "Construction and Extraction", "value" => "Construction and Extraction");
	$array["Installation, Maintenance, and Repair"] = array("title" => "Installation, Maintenance, and Repair", "value" => "Installation, Maintenance, and Repair");
	$array["Production"] = array("title" => "Production", "value" => "Production");
	$array["Transportation and Material Moving"] = array("title" => "Transportation and Material Moving", "value" => "Transportation and Material Moving");
	$uarray = array();
	foreach ($array as $key => $value) {
		$value['value'] = strtoupper($value['value']);
		$uarray["$key"] = $value;
	}
	return ($uarray);
}

function testCodeOptions($includeinactive = 0)
{
	$array = array();
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();

	if (empty($includeinactive))
		$query = "SELECT * FROM report_tests JOIN report_tests_measure ON rtmeasurecode=rtmid ORDER BY rtname ";
	else
		$query = "SELECT * FROM report_tests JOIN report_tests_measure ON rtmeasurecode=rtmid ORDER BY rtname ";
	if ($result = mysqli_query($dbhandle, $query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$code = $row['rtid'];
			$type = $row['rttype'];
			$desc = $row['rtname'];
			$meas = $row['rtmname'];
			$array["$code"] = array("code" => "$code", "type" => "$type", "description" => "$desc", "measure" => "$meas");
		}
	} else {
		error("000", "mysql result error. " . mysqli_error($dbhandle));
		displaysitemessages();
		exit;
	}
	return ($array);
}

function setStringCase($string, $case = NULL)
{
	if (strtolower($case) == 'proper')
		$string = properCase($string);
	if (strtolower($case) == 'upper')
		$string = strtoupper($string);
	if (strtolower($case) == 'lower')
		$string = strtolower($string);
	return ($string);
}

function icd9CodeOptions($includeinactive = 0, $desccase = NULL)
{
	$icd9array = array();
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();

	if (empty($includeinactive))
		$query = "SELECT imicd9, imdx FROM master_ICD9 WHERE iminactive=0 ORDER BY imicd9  LIMIT 100";
	else
		$query = "SELECT imicd9, imdx FROM master_ICD9 ORDER BY imicd9 LIMIT 100";
	if ($result = mysqli_query($dbhandle, $query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$code = $row['imicd9'];
			$desc = setStringCase($row['imdx'], $desccase);
			$icd9array["$code"] = array("code" => "$code", "description" => "$desc");
		}
	} else {
		error("000", "mysql result error. " . mysqli_error($dbhandle));
		displaysitemessages();
		exit;
	}
	return ($icd9array);
}

function injurynatureCodeOptions($includeinactive = 0)
{
	$array = array();
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();

	if (empty($includeinactive))
		$query = "SELECT imncode, imndescription FROM master_injury_nature WHERE imninactive=0 ORDER BY imncode ";
	else
		$query = "SELECT imncode, imndescription FROM master_injury_nature ORDER BY imncode ";
	if ($result = mysqli_query($dbhandle, $query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$code = $row['imncode'];
			$desc = $row['imndescription'];
			$array["$code"] = array("code" => "$code", "description" => "$desc");
		}
	} else {
		error("000", "mysql result error. " . mysqli_error($dbhandle));
		displaysitemessages();
		exit;
	}
	return ($array);
}

function bodypartCodeOptions($includeinactive = 0, $desccase = NULL)
{
	$array = array();
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();

	if (empty($includeinactive))
		$query = "SELECT imbinactive, imbparent, imbcode, imbdescription, imbsdescription FROM master_injury_bodyparts WHERE imbinactive=0 ORDER BY imbcode ";
	else
		$query = "SELECT imbinactive, imbparent, imbcode, imbdescription, imbsdescription FROM master_injury_bodyparts ORDER BY imbcode ";
	if ($result = mysqli_query($dbhandle, $query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$inactive = $row['imbinactive'];
			$parent = $row['imbparent'];
			$code = $row['imbcode'];
			//			$desc=$row['imbdescription'];
			$desc = setStringCase($row['imbdescription'], $desccase);
			//			$shortdescription=$row['imbsdescription'];
			$shortdescription = setStringCase($row['imbsdescription'], $desccase);
			$array["$code"] = array("code" => "$code", "description" => "$desc", "inactive" => "$inactive", "parent" => "$parent", "shortdescription" => "$shortdescription");
		}
	} else {
		error("000", "mysql result error. " . mysqli_error($dbhandle));
		displaysitemessages();
		exit;
	}
	return ($array);
}

function bodypartdescriptorCodeOptions($includeinactive = 0, $desccase = NULL)
{
	$array = array();
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();

	if (empty($includeinactive))
		$query = "SELECT imdcode, imdsdescription FROM master_injury_descriptors WHERE imdinactive=0 ORDER BY imdcode ";
	else
		$query = "SELECT imdcode, imdsdescription FROM master_injury_descriptors ORDER BY imdcode ";
	if ($result = mysqli_query($dbhandle, $query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$code = $row['imdcode'];
			//			$desc=$row['imdsdescription'];
			$desc = setStringCase($row['imdsdescription'], $desccase);
			$array["$code"] = array("code" => "$code", "description" => "$desc");
		}
	} else {
		error("000", "mysql result error. " . mysqli_error($dbhandle));
		displaysitemessages();
		exit;
	}
	return ($array);
}

function therapistCodeOptions($clinic = NULL, $therapytype = NULL)
{
	$therapistarray = array();
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();


	if (!empty($clinic))
		if (is_array($clinic) && count($clinic) > 0) {
			$clinics = array_keys($clinic);
			$wherefield[] = "ctcnum in ('" . implode("', '", $clinics) . "')";
		} else {
			$wherefield[] = "ctcnum='$clinic'";
		}
	if (!empty($therapytype))
		if (is_array($therapytype) && count($therapytype) > 0) {
			$therapytypes = array_keys($therapytype);
			$wherefield[] = "ctttmcode in ('" . implode("', '", $therapytypes) . "')";
		} else {
			$wherefield[] = "ctttmcode='$therapytype'";
		}
	if (count($wherefield) != 0)
		$where = "WHERE " . implode(" and ", $wherefield);
	else
		unset($where);

	$query = "
		SELECT ctcnum, ctttmcode, cttherap, tname, tlic, tnpi, tcontdate, trefnum, tnote
		FROM master_clinics_therapists
		LEFT JOIN therapists
		ON cttherap=ttherap
		$where
		ORDER BY tname ";
	//dump("query",$query);
	if ($result = mysqli_query($dbhandle, $query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$thistherapist['code'] = $row['cttherap'];
			$thistherapist['description'] = $row['tname'];
			foreach ($row as $field => $value)
				$thistherapist["$field"] = $value;
			$therapistarray[$row['cttherap']] = $thistherapist;
		}
	} else {
		error("000", "mysql result error. " . mysqli_error($dbhandle));
		displaysitemessages();
		exit;
	}
	return ($therapistarray);

}

function unlockrow($handle, $table, $idfield, $id, $user = "")
{
	// Request unique row
	$readquery = "
		SELECT lockuser, lockdate, upddate, NOW() as mydate
		FROM $table
		WHERE $idfield = $id";
	if ($readresult = mysqli_query($handle, $readquery)) {
		$numrows = mysqli_num_rows($readresult);
		if ($numrows == 1) {
			if ($readrow = mysqli_fetch_assoc($readresult)) {
				// Lock requester and lock request date
				if (empty($user))
					$user = getuser();
				$readlockuser = $readrow['lockuser'];
				$readlockdate = $readrow['lockdate'];
				$readupddate = $readrow['upddate'];
				$mydate = $readrow['mydate'];
				// Attempt Lock
// Update the lock if:
// 		the record has not been updated
//	and the record is not locked or locked by current user, or expired
				$lockquery = "
					UPDATE $table
					SET lockuser=NULL, lockDate=NULL
					WHERE $idfield = $id AND lockuser = '$user' AND upddate = '$readupddate'";
				if ($lockresult = mysqli_query($handle, $lockquery)) {
					// Return record id
					$returnquery = "
						SELECT $idfield, lockuser, lockdate
						FROM $table
						WHERE $idfield = $id";
					if ($returnresult = mysqli_query($handle, $returnquery)) {
						if ($returnrow = mysqli_fetch_assoc($returnresult)) {
							if ($user != $readlockuser)
								//								notify("001", "Record $id unlocked. Be sure to coordinate call with $readlockuser.");
								return ($returnrow["$idfield"]);
						} else
							error("891", "OOPS!" . mysqli_error($handle));
					} else
						error("892", "OOPS!<br>$returnquery" . mysqli_error($handle));
				} else
					error("893", "OOPS!" . mysqli_error($handle));
			} else
				error("894", "Record not locked - Record not found");
		} else
			error("895", "Record not locked - Not Unique Id");
	} else
		error("896", "OOPS!" . mysqli_error($handle));
}

function lockrow($handle, $table, $idfield, $id)
{
	// Request unique row
	$readquery = "
		SELECT lockuser, lockdate, upddate, NOW() as mydate
		FROM $table
		WHERE $idfield = $id";
	if ($readresult = mysqli_query($handle, $readquery)) {
		$numrows = mysqli_num_rows($readresult);
		if ($numrows == 1) {
			if ($readrow = mysqli_fetch_assoc($readresult)) {
				// Lock requester and lock request date
				$user = getuser();
				$readlockuser = $readrow['lockuser'];
				$readlockdate = $readrow['lockdate'];
				$readupddate = $readrow['upddate'];
				$mydate = $readrow['mydate'];
				// Attempt Lock
// Update the lock if:
// 		the record has not been updated
//	and the record is not locked or locked by current user, or expired
				$lockquery = "
UPDATE
	$table
SET
	lockuser='$user', lockdate=NOW()
WHERE
	$idfield = '$id' AND
	upddate = '$readupddate' AND (
		(lockuser IS NULL) OR
		(lockuser = '$user') OR
		(lockdate < ( NOW() - INTERVAL 30 MINUTE) )
	)
";
				if ($lockresult = mysqli_query($handle, $lockquery) && mysqli_affected_rows($handle) > 0) {
					// Return record id
					$returnquery = "
						SELECT $idfield
						FROM $table
						WHERE $idfield = '$id'
						";
					// AND lockuser = '$user' and lockdate = '$mydate'";
					if ($returnresult = mysqli_query($handle, $returnquery)) {
						if ($returnrow = mysqli_fetch_assoc($returnresult)) {
							if (!empty($readlockuser) && $user != $readlockuser)
								notify("000", "Record $id lock taken. Be sure to coordinate call with $readlockuser.");
							//							dump("lockquery",$lockquery);
//							dump("returnresult",$returnresult);
							return ($returnrow["$idfield"]);
						} else
							error("991", "Cannot find call record $id");
					} else
						error("992", "OOPS!" . mysqli_error($handle));
				} else {
					error("993", "$readlockuser is locking this record.");
				}
			} else
				error("994", "Record not locked - Record not found");
		} else
			error("995", "Record not locked - Not Unique Id");
	} else {
		error("996", "QUERY:" . $readquery . " ERROR:" . mysqli_error($handle));
	}
	return (FALSE);
}

function countformvars($application, $form)
{
	return (count($_SESSION['formvars']["$application"]["$form"]));
}

function clearformvars($application, $form)
{
	unset($_SESSION['formvars']["$application"]["$form"]);
}

function getformvars($application, $form)
{
	$formvars = array();
	//	dump("getformvars IN application:form", "$application:$form");
	if (isset($_SESSION['formvars']["$application"]["$form"]))
		$sessionformvars = $_SESSION['formvars']["$application"]["$form"];
	else
		$sessionformvars = '';
	if (is_array($sessionformvars)) {
		if (isset($sessionformvars)) {
			$formvars = $sessionformvars;
		}
	}
	//	dump("getformvars SESSION[formvars][application][form]", $_SESSION['formvars']["$application"]["$form"]);
//	dump("getformvars RETURN application:form", $formvars);
	return ($formvars);
}

function setformvars($application, $form, $formvars)
{
	//	dump("setformvars IN application:form", "$application:$form");
//	dump("setformvars IN formvars", $formvars);
	if ((is_array($formvars)) && (count($formvars) > 0)) {
		$_SESSION['formvars']["$application"]["$form"] = $formvars;
		//		dump("setformvars SESSION[formvars]", $_SESSION['formvars']);
		return (TRUE);
	} else {
		clearformvars($application, $form);
		//		dump("setformvars SESSION[formvars]", $_SESSION['formvars']);
		return (FALSE);
	}

}

function displayheadonly()
{
	echo ('
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="/css/' . $_SESSION['SERVER_NAME'] . '.css" rel="stylesheet" media="all" type="text/css" />
<link href="/css/' . $_SESSION['SERVER_NAME'] . '.print.css" rel="stylesheet" media="print" type="text/css" />
');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/javascript.js');
	echo ('
<title>NetPT by WestStar</title>
</head>
');
}

function displaysiteheader()
{
	if (!isset($_SESSION['googlemap']))
		$_SESSION['googlemap'] = "";

	if (!isset($_SESSION['headerspace']))
		$_SESSION['headerspace'] = '';
	echo ('
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="/css/' . $_SESSION['SERVER_NAME'] . '.css" rel="stylesheet" media="all" type="text/css" />
<link href="/css/' . $_SESSION['SERVER_NAME'] . '.print.css" rel="stylesheet" media="print" type="text/css" />
');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/javascript.js');

	if (isset($_SESSION['mobile'])) {
		echo ('
<title>NetPT by WestStar</title>
</head>
<body ' . $_SESSION['googlemap'] . '>
<div id="logoRow_mobile" >
	<div id="logoDiv_mobile"><img src="../img/logo.gif" width="75px" onclick="window.location=' . "'http://" . $_SESSION['SERVER_NAME'] . "'" . '"></div>
	<div id="NetPtSupportLink_mobile"><a href="http://support.netpt.wsptn.com" target="_blank">Need Help/Support?<br>Open a trouble ticket here.</a></div>
	<div id="instructionsDiv_mobile" >' . $_SESSION['headerspace'] . '</div>
	<div id="weststarDiv_mobile"><img src="../img/WestStar2.gif" width="75px" onclick="window.location=' . "'http://www.wsptn.com'" . '"></div>
</div>
');
	} else {
		echo ('
<title>NetPT by WestStar</title>
</head>
<body ' . $_SESSION['googlemap'] . '>
<div id="logoRow" >
	<div id="logoDiv"><img src="../img/logo.gif" onclick="window.location=' . "'http://" . $_SESSION['SERVER_NAME'] . "'" . '"></div>
	<div id="NetPtSupportLink"><a href="http://support.netpt.wsptn.com" target="_blank">Need Help/Support?<br>Open a trouble ticket here.</a></div>
	<div id="instructionsDiv" >' . $_SESSION['headerspace'] . '</div>
	<div id="weststarDiv"><img src="../img/WestStar2.gif" onclick="window.location=' . "'http://www.wsptn.com'" . '"></div>
</div>
');
	}

	if (1 == 2) {
		echo ('
<title>NetPT by WestStar</title>
</head>
<body ' . $_SESSION['googlemap'] . '>
<div id="logoRow">&nbsp;
	<div id="logoDiv"><img src="../img/logo.gif" onclick="window.location=' . "'http://" . $_SESSION['SERVER_NAME'] . "'" . '"></div>
	<div id="NetPtSupportLink">
		<a href="http://support.netpt.wsptn.com" target="_blank">
			Need Help/Support? <br>
			Open a trouble ticket here.
		</a>
	</div>
	<div id="instructionsDiv" >' . $_SESSION['headerspace'] . '</div>
	<div id="weststarDiv"><img src="../img/WestStar2.gif" onclick="window.location=' . "'http://www.wsptn.com'" . '"></div>
</div>
');
	}
}

function getSubArray($arrayofarrayitems, $arraykey, $arrayofmatchvalues)
{
	// returns a list of array elements that match the key within the array list
	$returnarray = array();
	if (!empty($arraykey) && !empty($arrayofmatchvalues)) {
		foreach ($arrayofarrayitems as $index => $arrayentry) {
			$result = in_array($arrayentry["$arraykey"], $arrayofmatchvalues);
			if ($result)
				$returnarray["$index"] = $arrayentry;
		}
	} else
		$returnarray = $arrayofarrayitems;
	return ($returnarray);
}

function getPreviousSubmissions($clinics)
{
	//
// Get Last Submission Date for Clinics
//
	$cliniclastsubmissiondate = array();
	if (!empty($clinics)) {

		$cliniccriteria = "";
		if ((is_array($clinics)) && (count($clinics) > 0)) {
			$clinicslistarray = array();
			foreach ($clinics as $key => $val) {
				$clinicslistarray[] = $val['cmcnum'];
			}
			$clinicslist = "('" . implode("', '", $clinicslistarray) . "')";
			$cliniccriteria = "WHERE thcnum in " . $clinicslist . " ";
		} else
			$cliniccriteria = "WHERE thcnum = '" . $clinics . "' ";

		$clinicsbmdatequery = "
			SELECT DISTINCT CONCAT(thcnum, thsbmDate) as seq, thcnum, thsbmDate
			FROM treatment_header " .
			$cliniccriteria . " and thsbmDate IS NOT NULL";

		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();

		$clinicsbmdateresult = mysqli_query($dbhandle, $clinicsbmdatequery);
		if (!$clinicsbmdateresult) {
			error("002", "MySql[getPreviousSubmissions]:" . mysqli_error($dbhandle));
		}
		while ($clinicsbmdaterow = mysqli_fetch_array($clinicsbmdateresult, MYSQLI_ASSOC)) {
			$cliniclastsubmissiondate[] = array("seq" => $clinicsbmdaterow["seq"], "cnum" => $clinicsbmdaterow["thcnum"], "sbmdate" => $clinicsbmdaterow["thsbmDate"]);
		}
	}
	return ($cliniclastsubmissiondate);
}

function getSelectOptions($arrayofarrayitems, $optionvaluefield, $arrayofoptionfields, $defaultoption = "", $addblankoption = FALSE, $arraykey = "", $arrayofmatchvalues = array(), $sortoptions = TRUE)
{
	// $arrayofarrayitems	: array of array values
// $optionvaluefield	: value to place in the option value area of code
// $arrayofoptionfields	: values to place in the options box and the separator
// $defaultoption		: default selected option
// $addblankoption		: add blank option in the list
// $arraykey			: When limiting list to source
// $arrayofmatchvalues	:

	// initialize return array
	$html = array();
	if (!empty($arrayofarrayitems) && !empty($optionvaluefield) && !empty($arrayofoptionfields)) {
		if ($arraykey == 'sel_val') {
			$myarray = getSubArray($arrayofarrayitems, '', $arrayofmatchvalues);
		} else {
			$myarray = getSubArray($arrayofarrayitems, $arraykey, $arrayofmatchvalues);
		}
		$returnarray = array();
		foreach ($myarray as $myarraykey => $myarrayentry) {
			$optionfieldliststring = "";

			if ($optionvaluefield == '*simple') {

				foreach ($arrayofoptionfields as $key => $value) {
					if (is_array($myarrayentry))
						$optionfieldliststring .= $myarrayentry["$value"];
					else
						$optionfieldliststring .= $myarrayentry;
				}

				$returnarray["$optionfieldliststring"] = $optionfieldliststring;
			} else {

				foreach ($arrayofoptionfields as $optionfield => $optionfieldseparator) {
					$optionfieldliststring .= $myarrayentry["$optionfield"] . $optionfieldseparator;
				}
				$returnarray[$myarrayentry["$optionvaluefield"]] = $optionfieldliststring;

			}
		}
		foreach ($arrayofarrayitems as $sort_value) {
			if (array_key_exists('rm_dis', $sort_value)) {
				$sortoptions = FALSE;
				break;
			}
		}
		if ($sortoptions)
			asort($returnarray);
		if ($addblankoption)
		$html[]="<option value=''></option>";
		foreach ($returnarray as $key => $val) {
			if (strval($key) === strval($defaultoption)) {
				$selected = " selected";
				if ($arraykey == 'sel_val') {
					return ("<option $selected value='$key'>$val</option>");
				}
			} else
				$selected = "";
			$html[] = "<option $selected value='$key'>$val</option>\n";
		}
	}
	$options = implode(" ", $html);
	return ($options);
}


function getSelectOptionsOld($arrayofarrayitems, $optionvaluefield, $arrayofoptionfields, $defaultoption = "", $addblankoption = FALSE, $arraykey = "", $arrayofmatchvalues = array(), $sortoptions = TRUE)
{
	// $arrayofarrayitems	: array of array values
// $optionvaluefield	: value to place in the option value area of code
// $arrayofoptionfields	: values to place in the options box and the separator
// $defaultoption		: default selected option
// $addblankoption		: add blank option in the list
// $arraykey			: When limiting list to source
// $arrayofmatchvalues	:

	// initialize return array
	$html = array();
	// dump("arrayofarrayitems", $arrayofarrayitems);
	if (!empty($arrayofarrayitems) && !empty($optionvaluefield) && !empty($arrayofoptionfields)) {
		$myarray = getSubArray($arrayofarrayitems, $arraykey, $arrayofmatchvalues);
		//dump("myarray", $myarray);
		$returnarray = array();
		foreach ($myarray as $myarraykey => $myarrayentry) {
			$optionfieldliststring = "";
			foreach ($arrayofoptionfields as $optionfield => $optionfieldseparator) {
				$optionfieldliststring .= $myarrayentry["$optionfield"] . $optionfieldseparator;
			}
			$returnarray[$myarrayentry["$optionvaluefield"]] = $optionfieldliststring;
		}
		//r returnarray contains matching entries with contactenated valules
		if ($sortoptions)
			asort($returnarray);
		if ($addblankoption)
			$html[] = "<option value=''></option>";
		foreach ($returnarray as $key => $val) {
			if (strval($key) === strval($defaultoption)) {
				$selected = " selected";
			} else
				$selected = "";
			$html[] = "<option $selected value='$key'>$val</option>\n";
		}
	}
	$options = implode(" ", $html);
	return ($options);
}

function showUserClinicSelectForm()
{
	$html = array();
	$html[] = '<form id="setClinicForm" method="post">';
	$html[] = getUserClinicsSelect("selectedclinic");
	$html[] = '</form>';
	$form = implode(" ", $html);
	echo ($form);
}

function showUserClinicSelect($name, $showbutton = 0, $javascriptsubmit = 0, $formatoption = "")
{
	$html = array();
	$html[] = getUserClinicsSelect($name, $showbutton, $javascriptsubmit, $formatoption);
	$select = implode(" ", $html);
	echo ($select);
}

function getUserClinicsSelect($name, $showbutton = 0, $javascriptsubmit = 0, $formatoption = "")
{
	$html = array();
	if (!empty($name)) {
		$userclinicsselectoptions = getUserClinicsSelectOptions($name, $formatoption);
		if (empty($userclinicsselectoptions)) {
			$onlyclinic = getuserclinic();
			$html[] = '<input name="' . $name . '" type="hidden" value="' . $onlyclinic . '" />' . $onlyclinic;
			//			$keys = array_keys($userclinic);
//			$html[] = $keys[0];
		} else {
			if ($javascriptsubmit != 0)
				$html[] = '<select name="' . $name . '" size="1" onchange="JavaScript:submit()">';
			else
				$html[] = '<select name="' . $name . '" size="1">';

			$html[] = $userclinicsselectoptions;
			$html[] = '</select>';
			if ($showbutton != 0) {
				if ($javascriptsubmit != 0)
					$html[] = '<noscript><input name="button[]" type="submit" value="Set Clinic" /></noscript>';
				else
					$html[] = '<input name="button[]" type="submit" value="Set Clinic" />';
			}
		}
	}
	$select = implode(" ", $html);
	return ($select);
}

function getUserClinicsSelectOptions($name, $formatoption = "")
{
	$html = array();
	if (isset($_POST["$name"]))
		$currentclinic = $_POST["$name"];
	else
		$currentclinic = $_SESSION['user']['umclinic'];
	$allowedclinics = $_SESSION['useraccess']['clinics'];
	if (count($allowedclinics) == 1) {
		//		$_SESSION['user']['umclinic'] = $allowedclinics;
//		$onlyclinic = array_keys($allowedclinics);
//		$html[] = $onlyclinic[0];
	} else {
		$clinicarray = array();
		foreach ($allowedclinics as $key => $val)
			$clinicarray[$key] = $val['cmname'] . ' [' . $key . ']';
		if (asort($clinicarray)) {
			$html = array();
			if ($formatoption == 'AddShowAll')
				$html[] = "<option value=''>Show All Clinics</option>";
			foreach ($clinicarray as $key => $val) {
				if ($key == $currentclinic)
					$selected = " selected ";
				else
					$selected = "";
				$html[] = "<option $selected value='$key'>$val</option>";
			}
		}
	}
	$options = implode(" ", $html);
	return ($options);
}

function showUserPatientSelect($name, $showbutton = 0, $onchange = "", $boundcolumnvalue = "", $formatoption = "")
{
	$html = array();
	$html[] = getUserPatientsSelect($name, $showbutton, $onchange, $boundcolumnvalue, $formatoption);
	$select = implode(" ", $html);
	echo ($select);
}

function getUserPatientsSelect($name, $showbutton = 0, $onchange = "", $boundcolumnvalue = "", $formatoption = "")
{
	$html = array();
	if (!empty($name)) {
		$selectoptions = getUserPatientsSelectOptions($boundcolumnvalue, $formatoption);
		if (empty($selectoptions)) {
			//			$keys = array_keys(getuserclinic());
//			$html[] = $keys[0];
		} else {
			if (empty($onchange))
				$html[] = '<select id="' . $name . '" name="' . $name . '" size="1">';
			else
				$html[] = '<select id="' . $name . '" name="' . $name . '" size="1" onchange="' . $onchange . '">';

			$html[] = $selectoptions;
			$html[] = '</select>';
			if ($showbutton != 0) {
				if (empty($onchange))
					$html[] = '<input name="button[]" type="submit" value="Set Patient" />';
				else
					$html[] = '<noscript><input name="button[]" type="submit" value="Set Patient" /></noscript>';
			}
		}
	}
	$select = implode(" ", $html);
	return ($select);
}

function getUserPatientsSelectOptions($boundcolumnvalue = "", $formatoption = "")
{
	// initialize return array
	$html = array();
	if (isset($boundcolumnvalue)) {
		// Only the patients in the bound column value
		foreach ($_SESSION['useraccess']['patients'] as $key => $val) {
			if ($val['cnum'] == $boundcolumnvalue) {
				$allowedpatients["$key"] = $val;
			}
		}
	} else {
		if (isset($_SESSION['user']['umclinic'])) {
			foreach ($_SESSION['useraccess']['patients'] as $key => $val) {
				if ($val['cnum'] == $_SESSION['user']['umclinic']) {
					$allowedpatients["$key"] = $val;
				}
			}
		}
	}
	if (count($allowedpatients) <= 1) {
	} else {
		$patientarray = array();
		foreach ($allowedpatients as $key => $val)
			$patientarray[$key] = $val['cnum'] . '-' . $val['lname'] . ', ' . $val['fname'] . ' [' . $key . ']';
		if (asort($patientarray)) {
			if ($formatoption == 'AddBlankOption')
				$html[] = "<option value=''></option>";
			foreach ($patientarray as $key => $val) {
				if ($key == $currentpatient)
					$selected = " selected ";
				else
					$selected = "";
				$html[] = "<option $selected value='$key'>$val</option>";
			}
		}
	}
	$options = implode(" ", $html);
	return ($options);
}


function adminclinicselectoption()
{
	echo ('User: ' . getusername());
	$clinicname = getuserclinicname();
	if (!empty($clinicname))
		echo (' of ' . $clinicname);
	if (isuserlevel(21)) {
		echo ('<form id="setClinicForm" method="post">');
		echo ('<select name="selectedclinic" size="1">');
		foreach ($_SESSION['clinics'] as $key => $val) {
			if ($key != '@@')
				$localclinicarray[$key] = $val . ' [' . $key . ']';
		}
		if (asort($localclinicarray)) {
			echo ('<option value=""></option>');
			foreach ($localclinicarray as $key => $val) {
				echo ('<option ');
				if ($_SESSION['user']['umclinic'] == $key)
					echo (" selected ");
				echo ('value="' . $key . '">' . $val . '</option>');
			}
			echo ('</select>');
			echo ('<input name="button[]" type="submit" value="Set Clinic" /></form>');
		}
	}
}

function displaysitestatus()
{
	echo ('
<div class="containedBox">
	<div id="userstatusDiv">');
	//	adminclinicselectoption();
	echo ('User: ' . getusername()) . "<br>Date: " . date('m/d/Y H:i:s') . " From:" . $_SERVER['REMOTE_ADDR'] . " Visit Id:" . $_SESSION['netpt_visitor_id'] . "-" . $_SESSION['passwordexpiration'];
	echo ('</div>
</div>
');
}

function displaysitenavigation()
{
	// print_r(userlevel());
	echo ('
<div id="navBarDiv">
	<form name="navBarForm" method="post">
		<div id="menuTabs">');

	// if(isset($_SESSION['user']['passwordexpired']) && $_SESSION['user']['passwordexpired']!=true) {
	if ($_SESSION['user']['passwordexpired'] != true) {

		$thisuserlevel = userlevel();

		switch ($thisuserlevel):
			case '99':
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Users" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Business Units" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Providers" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Clinics" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Therapists" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Patients" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Doctors" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" class="attorney-tab" value="Attorneys" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="PTOS NetPT Issues" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Notes Search" /></div>');
				// Priority 0 Eliminate Duplicate PNUMs
				$application = 'Authorization';
				$button = 'Duplicate PNUM List';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');
				$application = 'Report Manager';
				$button = 'Report Manager';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');

				$application = 'Report Manager';
				$button = 'Report Manager Templates'; // Any unfiled reports that are assigned
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');

				$application = 'User Audit List';
				$button = 'User Audit List'; // Any unfiled reports that are assigned
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');

				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Duplicate Dashboard" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Search Treatments" /></div>');
				$application = 'Billing Dashboard';
				$button = 'Build Billing File';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '"  /></div>');
				$application = 'Billing Dashboard';
				$button = 'Print Billing Summary';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" disabled="disabled" /></div>');

				$application = 'Billing Dashboard';
				$button = 'Export Billing to PTOS';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '"  /></div>');

				break;

			case '90':
				// 				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Users" /></div>');
// 				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Business Units" /></div>');
// 				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Providers" /></div>');
// 				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Clinics" /></div>');
// 				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Therapists" /></div>');
// 				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Patients" /></div>');
// 				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Doctors" /></div>');
// 				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" class="attorney-tab" value="Attorneys" /></div>');
// 				// echo('<div class="menuTabItem"><input type="submit" name="navigation[]" class="attorney-report-tab" value="Attorneys Report" /></div>');
// //		echo('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Doctors Locations" /></div>');
// //		echo('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Cases" /></div>');
// //		echo('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Customer Service" /></div>');
// //		echo('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Cancel Reason Report" /></div>');
// //		echo('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Attendance Report" /></div>');
// //		echo('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Patient Status Report" /></div>');
// //		echo('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Scheduling Performance Report" /></div>');
// //		echo('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Attendance" /></div>');
// //		echo('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Treatment Dashboard" /></div>');
// //		echo('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Add Treatments" /></div>');
// //		echo('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Search Treatments" /></div>');
// //		echo('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Billing Entry" /></div>');
// 				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="PTOS NetPT Issues" /></div>');
// 				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Notes Search" /></div>');
// 				// Priority 0 Eliminate Duplicate PNUMs
// 				$application = 'Authorization';
// 				$button = 'Duplicate PNUM List';
// 				$vars = urlencode("application=$application&button[]=$button");
// 				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');


				// 				// Priority 1 Get information to Authorizers
// //		$application = 'Authorization';
// //		$button = 'Prior Auth (PEA)';
// //		$vars=urlencode("application=$application&button[]=$button");
// //		echo('<div class="menuTabItem"><input type="submit" name="navigation['.$vars.']" value="'.$button.'" /></div>');
// // Priority 2 Get to Authorizers for Letter Generation
// //		$application = 'Authorization';
// //		$button = 'Review/Update';
// //		$vars=urlencode("application=$application&button[]=$button");
// //		echo('<div class="menuTabItem"><input type="submit" name="navigation['.$vars.']" value="'.$button.'" /></div>');
// // No Insurance Information
// //		$application = 'Authorization';
// //		$button = 'In Authorization';
// //		$vars=urlencode("application=$application&button[]=$button");
// //		echo('<div class="menuTabItem"><input type="submit" name="navigation['.$vars.']" value="'.$button.'" /></div>');
// //		$application = 'Authorization Processing';
// //		$button = 'Print RFAs';
// //		$vars=urlencode("application=$application&button[]=$button");
// //		echo('<div class="menuTabItem"><input type="submit" name="navigation['.$vars.']" value="'.$button.'" /></div>');


				// 				// Has insurance can send to PTOS
// //		$application = 'Authorization';
// //		$button = 'Final Review';
// //		$vars=urlencode("application=$application&button[]=$button");
// //		echo('<div class="menuTabItem"><input type="submit" name="navigation['.$vars.']" value="'.$button.'" /></div>');
// // Show waiting for export to PTOS
// //		$button = 'Waiting for NetPT Export';
// //		$vars=urlencode("application=$application&button[]=$button");
// //		echo('<div class="menuTabItem"><input type="submit" name="navigation['.$vars.']" value="'.$button.'" /></div>');
// // Create export to PTOS
// //		$application = 'Authorization';
// //		$button = 'Export to PTOS';
// //		$vars=urlencode("application=$application&button[]=$button");
// //		echo('<div class="menuTabItem"><input type="submit" name="navigation['.$vars.']" value="'.$button.'" /></div>');
// // Show exported and waiting for to PTOS
// //		$application = 'Authorization';
// //		$button = 'Waiting for PTOS Import';
// //		$vars=urlencode("application=$application&button[]=$button");
// //		echo('<div class="menuTabItem"><input type="submit" name="navigation['.$vars.']" value="'.$button.'" /></div>');
// //		$application = 'Authorization Processing';
// //		$button = 'Print RFAs';
// //		$vars=urlencode("application=$application&button[]=$button");
// //		echo('<div class="menuTabItem"><input type="submit" name="navigation['.$vars.']" value="'.$button.'" /></div>');
// // Build Billing Export File
// //		$application = 'Billing Dashboard';
// //		$button = 'Build Billing File';
// //		$vars=urlencode("application=$application&button[]=$button");
// //		echo('<div class="menuTabItem"><input type="submit" name="navigation['.$vars.']" value="'.$button.'" /></div>');
// // Print Billing Export Summary
// //		$application = 'Billing Dashboard';
// //		$button = 'Print Billing Summary';
// //		$vars=urlencode("application=$application&button[]=$button");
// //		echo('<div class="menuTabItem"><input type="submit" name="navigation['.$vars.']" value="'.$button.'" /></div>');
// // Export Billing Records
// //		$application = 'Billing Dashboard';
// //		$button = 'Export Billing to PTOS';
// //		$vars=urlencode("application=$application&button[]=$button");
// //		echo('<div class="menuTabItem"><input type="submit" name="navigation['.$vars.']" value="'.$button.'" /></div>');
// // Therapist Reporting Admin
// //		$application = 'Report Manager Admin';
// //		$button = 'Report Manager Admin';
// //		$vars=urlencode("application=$application&button[]=$button");
// //		echo('<div class="menuTabItem"><input type="submit" name="navigation['.$vars.']" value="'.$button.'" /></div>');
// // Therapist Reporting
// 				$application = 'Report Manager';
// 				$button = 'Report Manager';
// 				$vars = urlencode("application=$application&button[]=$button");
// 				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');

				// 				// Report Manager
// 				$application = 'Report Manager';
// 				$button = 'Report Manager Templates'; // Any unfiled reports that are assigned
// 				$vars = urlencode("application=$application&button[]=$button");
// 				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');

				// 				// Document Manager
// //		$application = 'Document Manager';
// //		$button = 'Document Manager';
// //		$vars=urlencode("application=$application&button[]=$button");
// //		echo('<div class="menuTabItem"><input type="submit" name="navigation['.$vars.']" value="'.$button.'" /></div>');

				// 				// User Audit List
// 				$application = 'User Audit List';
// 				$button = 'User Audit List'; // Any unfiled reports that are assigned
// 				$vars = urlencode("application=$application&button[]=$button");
// 				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');


				///// NEW CHANGES ////////



				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Users" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Business Units" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Providers" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Clinics" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Therapists" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Patients" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Doctors" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" class="attorney-tab" value="Attorneys" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" class="icdcode" value="ICD Codes" /></div>');

				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="PTOS NetPT Issues" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Notes Search" /></div>');

				$application = 'Authorization';
				$button = 'Duplicate PNUM List';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');

				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Duplicate Dashboard" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Search Treatments" /></div>');
				$application = 'Billing Dashboard';
				$button = 'Build Billing File';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');
				$application = 'Billing Dashboard';
				$button = 'Print Billing Summary';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '"
        disabled="disabled" /></div>');



				$application = 'Report Manager';
				$button = 'Report Manager';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');


				$application = 'Report Manager';
				$button = 'Report Manager Templates';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');


				$application = 'User Audit List';
				$button = 'User Audit List';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');



				break;

			case '75': // Sales Management
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Attendance Report" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Patient Status Report" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Cancel Reason Report" /></div>');
				// echo('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Scheduling Performance Report" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Doctors" /></div>');
				break;

			case '73': // Accounts Receivable
				$application = 'collections';
				$button = 'Collections Queue';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');
				$application = 'collections';
				$button = 'Collections Search';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Billing Dashboard" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Treatment Dashboard" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Search Treatments" /></div>');
				break;

			case '66': // Sales Manager
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Attendance Report" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Patient Status Report" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Cancel Reason Report" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Doctors" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Sales Territory Assign" /></div>');

				//		$application = 'doctor';
//		$button = 'Doctors';
//		$vars=urlencode("application=$application&button[]=$button");
//		echo('<div class="menuTabItem"><input type="submit" name="navigation['.$vars.']" value="'.$button.'" /></div>');
				break;

			case '65': // Sales Users
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Attendance Report" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Patient Status Report" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Cancel Reason Report" /></div>');

				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Search Patients" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Search Cases" /></div>');


				break;

			case '64': // Sales Support
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Attendance Report" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Patient Status Report" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Cancel Reason Report" /></div>');
				break;

			case '34': // collections
				$application = 'collections';
				$button = 'Collections Queue';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');

				//                if ($_SESSION['user']['umuser'] == 'mtwheaterC') {
//                    $application = 'collections';
//                    $button = 'Collections Queue New';
//                    $vars=urlencode("application=$application&button[]=$button");
//                    echo('<div class="menuTabItem"><input type="submit" name="navigation['.$vars.']" value="'.$button.'" /></div>');
//                }

				$application = 'collections';
				$button = 'Collections Search';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');
				$application = 'collections';
				$button = 'Touched Accounts';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');
				$application = 'collections';
				$button = 'UnTouched Accounts';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');
				$application = 'collectionassign';
				$button = 'Queue Assignment';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');
				$application = 'collectionmassmailing';
				$button = 'Mass Mailing';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');
				break;

			case '33': // collections
				$application = 'collections';
				$button = 'Collections Queue';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');

				$application = 'collections';
				$button = 'Collections Search';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');
				$application = 'collections';
				$button = 'Touched Accounts';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');
				$application = 'collectionmassmailing';
				if ($_SESSION['user']['umuser'] == 'ShawnaClay' || $_SESSION['user']['umuser'] == 'SandraBenavidez') {
					$button = 'Mass Mailing';
					$vars = urlencode("application=$application&button[]=$button");
					echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');
				}
				break;

			case '23': // ur
				// echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Users" /></div>');
				// echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Business Units" /></div>');
				// echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Providers" /></div>');
				// echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Clinics" /></div>');
				// echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Therapists" /></div>');
				// echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Patients" /></div>');
				// echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Duplicate Dashboard" /></div>');
				// echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Treatment Dashboard" /></div>');
				// echo ('<div class="menuTabItem"><input type="submit" name="navigation[]  value="Add Treatments" /></div>');
				// echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Search Treatments" /></div>');
				// echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="PTOS NetPT Issues" /></div>');
				// // Build Billing Export File
				// $application = 'Billing Dashboard';
				// $button = 'Build Billing File';
				// $vars = urlencode("application=$application&button[]=$button");
				// echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '"  /></div>');
				// // Print Billing Export Summary
				// $application = 'Billing Dashboard';
				// $button = 'Print Billing Summary';
				// $vars = urlencode("application=$application&button[]=$button");
				// echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" disabled="disabled" /></div>');
				// // Export Billing Records
				// $application = 'Billing Dashboard';
				// $button = 'Export Billing to PTOS';
				// $vars = urlencode("application=$application&button[]=$button");
				// echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '"  /></div>');




				/// NEW CHANGES ///


				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Users" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Business Units" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Providers" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Clinics" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Therapists" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Patients" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Doctors" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" class="attorney-tab" value="Attorneys" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" class="icdcode" value="ICD Codes" /></div>');
					echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="PTOS NetPT Issues" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Notes Search" /></div>');
				// Priority 0 Eliminate Duplicate PNUMs
				$application = 'Authorization';
				$button = 'Duplicate PNUM List';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');
				$application = 'Report Manager';
				$button = 'Report Manager';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');

				$application = 'Report Manager';
				$button = 'Report Manager Templates'; // Any unfiled reports that are assigned
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');

				$application = 'User Audit List';
				$button = 'User Audit List'; // Any unfiled reports that are assigned
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');

				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Duplicate Dashboard" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Treatment Dashboard" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]  value="Add Treatments" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Search Treatments" /></div>');
				// echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="PTOS NetPT Issues" /></div>');
				// Build Billing Export File
				$application = 'Billing Dashboard';
				$button = 'Build Billing File';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '"  /></div>');
				// Print Billing Export Summary
				$application = 'Billing Dashboard';
				$button = 'Print Billing Summary';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" disabled="disabled" /></div>');
				// Export Billing Records
				$application = 'Billing Dashboard';
				$button = 'Export Billing to PTOS';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '"  /></div>');




				break;

			case '22': // billing
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Billing Dashboard" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Search Treatments" /></div>');
				break;

			case '21': // patient management
				$application = 'Patient Entry List';
				$button = 'Patient Entry List';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');
				$application = 'Patient Entry List';
				$button = 'PTOS Edit List';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');
				// Priority 0 Eliminate Duplicate PNUMs
				$application = 'Authorization';
				$button = 'Duplicate PNUM List';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');
				// Priority 1 Get information to Authorizers
				$application = 'Authorization';
				$button = 'Prior Auth (PEA)';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');
				// Priority 2 Get to Authorizers for Letter Generation
				$application = 'Authorization';
				$button = 'Review/Update';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');
				// No Insurance Information
				$application = 'Authorization';
				$button = 'In Authorization';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');
				// Has insurance can send to PTOS
				$application = 'Authorization';
				$button = 'Final Review';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');
				// Show waiting for export to PTOS
				$application = 'Authorization';
				$button = 'Waiting for NetPT Export';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');
				// Show Not In PTOS
				$application = 'Authorization';
				$button = 'Not In PTOS';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');
				// Create export to PTOS
				$application = 'Authorization';
				$button = 'Export to PTOS';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');
				// Show exported and waiting for to PTOS
				$application = 'Authorization';
				$button = 'Waiting for PTOS Import';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');
				//		$application = 'Authorization';
//		$button = 'Patients In PTOS';
//		$vars=urlencode("application=$application&button[]=$button");
//		echo('<div class="menuTabItem"><input type="submit" name="navigation['.$vars.']" value="'.$button.'" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Search Treatments" /></div>');
				$application = 'Patient List Report';
				$button = 'Patient List Report';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');

				break;

			case '20':
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Search Treatments" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Patients" /></div>');
				break;

			case '17': // Customer Service
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Search Patients" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Search Cases" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Attendance" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Attendance Report" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Patient Status Report" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Cancel Reason Report" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Scheduling Performance Report" /></div>');
				//		echo('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Search Authorizations" /></div>');
				$application = 'patientdashboard';
				$button = 'Patient List Report';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');
				break;

			case '16': // Authorization
				$application = 'Authorization Processing';
				$button = 'Authorization Dashboard';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');

				$button = 'Prior Auth (PEA)';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');

				$button = 'Print RFAs';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');

				$button = 'Send RFAs';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');

				$button = 'Send Doc/Info Requests';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');

				$button = 'No Insurance';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');

				if ($_SESSION['user']['umuser'] == 'mtwheaterA' || $_SESSION['user']['umuser'] == 'MariaLaraIns') {
					$button = 'No Insurance Queue';
					$vars = urlencode("application=$application&button[]=$button");
					echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');
				}

				$button = 'Process Responses';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');

				$button = 'Process No Responses (Daily)';
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');


				// $button = 'Search Authorization Information';
				// $vars=urlencode("application=$application&button[]=$button");
				// echo('<div class="menuTabItem"><input type="submit" name="navigation['.$vars.']" value="'.$button.'" /></div>');

				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Search Authorization Information" /></div>');

				//		$application = 'Authorization';
//		$button = 'Search';
//		$vars=urlencode("application=$application&button[]=$button");
//		echo('<div class="menuTabItem"><input type="submit" name="navigation['.$vars.']" value="'.$button.'" /></div>');
				break;

			case '15': // Scheduling
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Scheduling Queue" /></div>');
				//		echo('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Search Appointments" /></div>');
//		echo('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Search Patients" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Search Patients" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Search Cases" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Scheduled Queue List" /></div>');
				break;

			case '14': // Weststar Therapist
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Report Manager" /></div>');
				$application = 'reportmanager';
				$button = 'New Reports'; // Never started
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');

				$button = 'Walkin Reports'; // Not Assigned
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');

				$button = 'Reports To File'; // Any unfiled reports that are assigned
				$vars = urlencode("application=$application&button[]=$button");
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[' . $vars . ']" value="' . $button . '" /></div>');

				break;

			case '13': // Network Therapist
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Search Patients" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="New Patients" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Unassigned Reports" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Unfiled Reports" /></div>');
				break;

			case '12': // Test Clinic user
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Add Treatments" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Search Treatments" /></div>');
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="New Patients" /></div>');
				break;

			case '10': // clinic user
				if (!empty($_SESSION['useraccess']['clinics'])) {
					$clinics = $_SESSION['useraccess']['clinics'];
					foreach ($clinics as $pause) {
						$dbhandle = dbconnect();
						$cmid = $pause['cmid'];
						$q = "SELECT pausestate from master_clinics where cmid='$cmid'";
						$result = mysqli_query($dbhandle, $q);
						$get = mysqli_fetch_assoc($result);

						if ($get['pausestate'] == 0) {
							echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" id="add-treatments" value="Add Treatments" /></div>');
						}
					}

				}
				echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" id="search-treatments" value="Search Treatments" /></div>');
				break;
		endswitch;
	}

	//@todo remove this lock
	if (
		$_SESSION['user']['umuser'] == 'GeoffSundstrom'
		|| $_SESSION['user']['umuser'] == 'Constance'
		|| $_SESSION['user']['umuser'] == 'ConstanceCollect'
		|| $_SESSION['user']['umuser'] == 'AliCollections'
		|| $_SESSION['user']['umuser'] == 'Tessie'
		|| $_SESSION['user']['umuser'] == 'PatriciaR'
		|| $_SESSION['user']['umuser'] == 'MoMo'
		|| $_SESSION['user']['umuser'] == 'mtwheater'
		|| $_SESSION['user']['umuser'] == 'NancyV'
		|| $_SESSION['user']['umuser'] == 'RobertMayhall'
		|| $_SESSION['user']['umuser'] == 'MarthaRodriguez'
		|| $_SESSION['user']['umuser'] == 'IrmaGomez'
	) {
		echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Reports" /></div>');
		echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Sales Territory Assign" /></div>');
		echo ('<div class="menuTabItem"><input type="submit" name="navigation[]" value="Doctor Locations" /></div>');
	}


	echo ('</div>');
	echo ('
		<div id="logoutDiv"><input type="submit" name="logout" value="Logout" /></div>
		<div id="accountSettingsDiv"><input type="submit" name="navigation[]" value="User Settings" /></div>');
	if ($thisuserlevel == 23) {
		echo ('<div id="supportDiv"><a href="./livehelp" target="_blank">Live Help</a></div>');
	}
	if (thisUserCanImitate()) {
		//		echo($thisuserlevel);
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/user.options.php');
		$newlistoptions = "";
		if ($list = getUserList()) {
			if (count($list) > 0) {
				$newlistoptions = getSelectOptions(
					$arrayofarrayitems = $list,
					$optionvaluefield = 'umid',
					$arrayofoptionfields = array(
						'umhomepage' => '(',
						'umrole' => ') ',
						'umname' => ' - ',
						'umuser' => ''
					),
					$defaultoption = $_POST['imitateUser'],
					$addblankoption = TRUE
				);
			} else
				error("999", "User getSelectOptions() Error-No values in master table.");
		} else
			echo ("Error-getUserList().");

		foreach ($list as $l) {

			$uname[] = $l['umuser'];

		}

		/*echo "<pre>";
		print_r($uname);
		echo "</pre>";*/

		echo ('<div id="imitateUserDiv"><select id="imitateUser" name="imitateUser" onChange="javascript:submit();">' . $newlistoptions . '</select></div>');
		//		echo('<input type="submit" name="imitateUserButton" value="Imitate User" />');
	}
	echo ('</form>
</div>
');
}

function displaysitemessages()
{
	displayinfo();
	displaynotify();
	displayerror();
}

function displayerror()
{
	// Output Error Messages here.
	if (isset($_SESSION['error']) && count($_SESSION['error']) > 0) {
		echo ('<div class="containedBox" id="errormessage">');
		echo ('<fieldset style="background-color:#DC143C; color:#FFFFFF;">');
		echo ('<legend style="background-color:#000000; color:#FFFFFF;">Error Message Notifications</legend>');
		foreach ($_SESSION['error'] as $num => $msg)
			echo ("<div id=err" . $num . "> $msg </div>");
		echo ("</fieldset>");
		$_SESSION['error'] = array();
		echo ('</div>');
	}
}

function displayinfo()
{
	// Output informational Messages here.
	if (isset($_SESSION['info']) && count($_SESSION['info']) > 0) {
		echo ('<div class="containedBox" id="infomessage">');
		echo ('<fieldset style="background-color:yellow; color:#000000;">');
		echo ('<legend style="background-color:#000000; color:#FFFFFF;">Informational Messages</legend>');
		foreach ($_SESSION['info'] as $num => $msg)
			echo ('<div id="info"' . $num . "> $msg  </div>");
		echo ("</fieldset>");
		$_SESSION['info'] = array();
		echo ('</div>');
	}
}

function displaynotify()
{
	// Output Notification Messages here.
	if (isset($_SESSION['notify']) && count($_SESSION['notify']) > 0) {
		echo ('<div class="containedBox" id="notifymessage">');
		echo ('<fieldset style="background-color:#4682B4; color:#FFFFFF;">');
		echo ('<legend style="background-color:#000000; color:#FFFFFF;">Notification Messages</legend>');
		foreach ($_SESSION['notify'] as $num => $msg)
			echo ('<div id="notify"' . $num . "> $msg </div>");
		echo ("</fieldset>");
		$_SESSION['notify'] = array();
		echo ('</div>');
	}
}

function errorclear()
{
	$_SESSION['error'] = array();
}

function errorcount()
{
	if (isset($_SESSION['error']) && is_array($_SESSION['error']))
		return (count($_SESSION['error']));
	else
		return (0);
}

function error($num, $msg)
{
	$_SESSION['error'][] = $_SESSION['application'] . ':' . $num . ':' . $msg;
}

function notifyclear()
{
	$_SESSION['notify'] = array();
}

function notifycount()
{
	return (count($_SESSION['notify']));
}

function notify($num, $msg)
{
	$_SESSION['notify'][] = $msg;
}


function infoclear()
{
	$_SESSION['info'] = array();
}

function infocount()
{
	if (isset($_SESSION['info'])) {
		return (count($_SESSION['info']));
	}
	return;
}

function info($num, $msg)
{
	error_log(date('Y-m-d h:i:s') . PHP_EOL, 3, '/home/wsptn/logs/info.log');
	error_log(print_r(debug_backtrace(), true) . PHP_EOL, 3, '/home/wsptn/logs/info.log');
	error_log(print_r($msg, true) . PHP_EOL, 3, '/home/wsptn/logs/info.log');
	$_SESSION['info'][] = $msg;
}


function configapplication($app)
{
	$_SESSION['headerspace'] = "";
	$configfile = $_SERVER['DOCUMENT_ROOT'] . '/modules/' . $app . '/config.php';
	if (file_exists($configfile))
		require_once($configfile);
	else
		error('001', 'Application configuration error. [' . $app . ']');
}

function displayapplication($app)
{
	$appfile = $_SERVER['DOCUMENT_ROOT'] . '/modules/' . $app . '/index.php';
	if (file_exists($appfile))
		require_once($appfile);
	else
		error('002', 'Application routing error. [' . $app . ']');
}

function displaysitefooter()
{
	/*	echo('<!-- Powered by: Crafty Syntax Live Help        http://www.craftysyntax.com/ -->
	<div id="craftysyntax" style="float:left; width:142px">
	<script type="text/javascript" src="/livehelp/livehelp_js.php?eo=1&relative=Y&amp;department=1&amp;serversession=1&amp;pingtimes=15&amp;secure=Y"></script>
	</div>
	<!-- copyright 2003 - 2008 by Eric Gerdes -->
	');
	echo('<span id="siteseal" style="float:right;"><script type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=o0AHZi2EOkB0WKOWQbkfJPWy3YtF3m8Q2Isq1sWiEoVuyCCqN79d8A7U"></script></span>');
	echo('</body>
	</html>'); */
	//if($_SESSION['mobile'])
//	echo $_SERVER['HTTP_USER_AGENT'];
}

function datediff($interval, $datefrom, $dateto, $using_timestamps = false)
{
	/*
	$interval can be:
	yyyy - Number of full years
	q - Number of full quarters
	m - Number of full months
	y - Difference between day numbers
	(eg 1st Jan 2004 is "1", the first day. 2nd Feb 2003 is "33". The datediff is "-32".)
	d - Number of full days
	w - Number of full weekdays
	ww - Number of full weeks
	h - Number of full hours
	n - Number of full minutes
	s - Number of full seconds (default)
	*/
	#
	if (!$using_timestamps) {
		$datefrom = strtotime($datefrom, 0);
		$dateto = strtotime($dateto, 0);
	}
	$difference = $dateto - $datefrom; // Difference in seconds
#
	switch ($interval) {
		#
		case 'yyyy': // Number of full years

			$years_difference = floor($difference / 31536000);
			if (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom), date("j", $datefrom), date("Y", $datefrom) + $years_difference) > $dateto) {
				$years_difference--;
			}
			if (mktime(date("H", $dateto), date("i", $dateto), date("s", $dateto), date("n", $dateto), date("j", $dateto), date("Y", $dateto) - ($years_difference + 1)) > $datefrom) {
				$years_difference++;
			}
			$datediff = $years_difference;
			break;

		case "q": // Number of full quarters

			$quarters_difference = floor($difference / 8035200);
			while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom) + ($quarters_difference * 3), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
				$months_difference++;
			}
			$quarters_difference--;
			$datediff = $quarters_difference;
			break;

		case "m": // Number of full months

			$months_difference = floor($difference / 2678400);
			while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom) + ($months_difference), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
				$months_difference++;
			}
			$months_difference--;
			$datediff = $months_difference;
			break;

		case 'y': // Difference between day numbers

			$datediff = date("z", $dateto) - date("z", $datefrom);
			break;

		case "d": // Number of full days

			$datediff = floor($difference / 86400);
			break;

		case "w": // Number of full weekdays

			$days_difference = floor($difference / 86400);
			$weeks_difference = floor($days_difference / 7); // Complete weeks
			$first_day = date("w", $datefrom);
			$days_remainder = floor($days_difference % 7);
			$odd_days = $first_day + $days_remainder; // Do we have a Saturday or Sunday in the remainder?
			if ($odd_days > 7) { // Sunday
				$days_remainder--;
			}
			if ($odd_days > 6) { // Saturday
				$days_remainder--;
			}
			$datediff = ($weeks_difference * 5) + $days_remainder;
			break;

		case "ww": // Number of full weeks

			$datediff = floor($difference / 604800);
			break;

		case "h": // Number of full hours

			$datediff = floor($difference / 3600);
			break;

		case "n": // Number of full minutes

			$datediff = floor($difference / 60);
			break;

		default: // Number of full seconds (default)

			$datediff = $difference;
			break;
	}

	return $datediff;

}

function clearpost()
{
	foreach ($_POST as $key => $val)
		unset($_POST["$key"]);
}

function clearsession()
{
	foreach ($_SESSION as $key => $val)
		unset($_SESSION["$key"]);
}

function gohome()
{
	if (isset($_SESSION['user']['umhomepage'])) {
		$_SESSION['navigation'] = $_SESSION['user']['umhomepage'];
	}
	return;
}

function getauditfields()
{
	$auditfields = array();
	$auditfields['date'] = date('Y-m-d H:i:s', time());
	$auditfields['user'] = $_SESSION['user']['umuser'];
	$auditfields['prog'] = $_SERVER['PHP_SELF'];
	return ($auditfields);
}

function addheaderhistory($id, $date, $user, $hide, $application, $msg, $text, $sql)
{
	$dbhandle = dbconnect();
	$query = "INSERT INTO treatment_header_history (thhid, thhdate, thhuser, thhhide, thhapplication, thhmsg, thhtext, thhquery) ";
	$values[] = mysqli_real_escape_string($id);
	$values[] = mysqli_real_escape_string($date);
	$values[] = mysqli_real_escape_string($user);
	$values[] = mysqli_real_escape_string($hide);
	$values[] = mysqli_real_escape_string($application);
	$values[] = mysqli_real_escape_string($msg);
	$values[] = mysqli_real_escape_string($text);
	$values[] = mysqli_real_escape_string(($sql));
	$query .= "VALUES('" . implode("', '", $values) . "')";
	$result = mysqli_query($dbhandle, $query);
	if (!$result) {
		error("001", "MySql[history]:" . mysqli_error($dbhandle));
	}
}

function gethttpprotocol()
{
	if (strtolower($_SERVER["SERVER_NAME"]) == 'netptdev.wsptn.com')
		return ("https://");
	else
		return ("http://");
}

function echohttpprotocol()
{
	echo (gethttpprotocol());
}

function redirecturl()
{
	header("Location: " . echohttpprotocol() . $_SERVER["SERVER_NAME"]);
	exit();
}

function logResource()
{
	$tm = date('Y-m-d h:i:s', time());
	$ref = $_SERVER['HTTP_REFERER'];
	$agent = $_SERVER['HTTP_USER_AGENT'];
	$ip = $_SERVER['REMOTE_ADDR'];
	if ($ip == $_SESSION['REMOTE_ADDR'])
		$domain = $_SESSION['REMOTE_HOST'];
	else {
		$_SESSION['REMOTE_IP'] = $ip;
		$domain = gethostbyaddr($ip);
		$_SESSION['REMOTE_HOST'] = $domain;
	}
	$ip_value = ip2long($ip);
	$tracking_page_name = 'showLoginForm()';
	$strSQL = "INSERT INTO netpt_resource_track(tm, ref, agent, ip, ip_value, domain, tracking_page_name) VALUES ('$tm','$ref','$agent','$ip','$ip_value','$domain','$tracking_page_name')";
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();

	mysqli_query($dbhandle, $strSQL);
	mysqli_close($dbhandle);
}
?>
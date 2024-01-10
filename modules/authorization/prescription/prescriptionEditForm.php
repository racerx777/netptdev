<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />

<script src="https://code.jquery.com/jquery-3.4.1.slim.js"
	integrity="sha256-BTlTdQO9/fascB1drekrDVkaKd9PkwBymMlHOiG+qLI=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
	integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
	crossorigin="anonymous" referrerpolicy="no-referrer" />


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
	/* Add necessary CSS for the hover effect */
	.hover-container {
		position: relative;
		display: inline-block;
	}

	.hover-message {
		display: none;
		position: absolute;
		top: -10px;
		/* Adjust the distance from the icon */
		right: calc(100% + 10px);
		/* Place the message on the left side of the icon */
		padding: 10px;
		/* Increase padding for card-like appearance */
		background-color: #fff;
		box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
		/* Add box shadow for card-like appearance */
		border-radius: 5px;
		white-space: normal;
		/* Allow text wrapping inside the card */
		z-index: 1;
		/* Ensure the message appears above the icon */
		width: 350px;
	}

	.hover-container:hover .hover-message {
		display: block;
	}

	/* 
	.addmore-button {
		background-color: skyblue;
		cursor: pointer;
		color: #fff;
		background-color: #007bff;
		border-color: #007bff;
		/* Add any other styles you want for the button */
	}

	*/
</style>
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(11);
errorclear();
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


if (!empty($cpid)) {
	// $buttonaction = "Update Prescription";
	$buttonaction = "Save and Close";

	if (!isset($_POST['formLoaded'])) {
		$query = "SELECT * FROM case_prescriptions WHERE cpid='" . $cpid . "'";
		if ($resultid = mysqli_query($dbhandle, $query)) {
			$numRows = mysqli_num_rows($resultid);
			if ($numRows == 1) {
				$result = mysqli_fetch_assoc($resultid);
				foreach ($result as $key => $val) {
					$_POST[$key] = $val;
				}
				$buttonid = $cpid;
				$crid = $_POST['cpcrid'];
				// Default NULL values from case
				// Get Previous Prescription Defaults
				$cpquery = "SELECT cpdmid, cpdlid, cpcnum, cpttmcode, cpdx1, cpdx2, cpdx3, cpdx4, cptherap, cpfrequency, cpduration, cptotalvisits from case_prescriptions WHERE cpcrid='$crid' ORDER BY cpdate DESC LIMIT 1";
				if ($cpresult = mysqli_query($dbhandle, $cpquery)) {
					if ($cprow = mysqli_fetch_assoc($cpresult)) {
						if (empty($_POST['cpdmid']))
							$_POST['cpdmid'] = $cprow['cpdmid'];
						if (empty($_POST['cpdlid']))
							$_POST['cpdlid'] = $cprow['cpdlid'];
						if (empty($_POST['cpcnum']))
							$_POST['cpcnum'] = $cprow['cpcnum'];
						if (empty($_POST['cpttmcode']))
							$_POST['cpttmcode'] = $cprow['cpttmcode'];
						if (empty($_POST['cpdx1']))
							$_POST['cpdx1'] = $cprow['cpdx1'];
						if (empty($_POST['cpdx2']))
							$_POST['cpdx2'] = $cprow['cpdx2'];
						if (empty($_POST['cpdx3']))
							$_POST['cpdx3'] = $cprow['cpdx3'];
						if (empty($_POST['cpdx4']))
							$_POST['cpdx4'] = $cprow['cpdx4'];
						if (empty($_POST['cptherap']))
							$_POST['cptherap'] = $cprow['cptherap'];
						if (empty($_POST['cpfrequency']))
							$_POST['cpfrequency'] = $cprow['cpfrequency'];
						if (empty($_POST['cpduration']))
							$_POST['cpduration'] = $cprow['cpduration'];
						if (empty($_POST['cptotalvisits']))
							$_POST['cptotalvisits'] = $cprow['cptotalvisits'];
					} else
						error("004", "Fetch error. $cpquery<br>" . mysqli_error($dbhandle));
				} else
					error("003", "Select error. $cpquery<br>" . mysqli_error($dbhandle));
				// Get Case Information Defaults
				$crquery = "SELECT crdate, crrefdmid, crrefdlid, crcnum, crtherapytypecode FROM cases WHERE crid='$crid' LIMIT 1";
				if ($crresult = mysqli_query($dbhandle, $crquery)) {
					if ($crrow = mysqli_fetch_assoc($crresult)) {
						if (empty($_POST['cpdate']))
							$_POST['cpdate'] = $crrow['crdate'];
						if (empty($_POST['cpdmid']))
							$_POST['cpdmid'] = $crrow['crrefdmid'];
						if (empty($_POST['cpdlid']))
							$_POST['cpdlid'] = $crrow['crrefdlid'];
						if (empty($_POST['cpcnum']))
							$_POST['cpcnum'] = $crrow['crcnum'];
						if (empty($_POST['cpttmcode']))
							$_POST['cpttmcode'] = $crrow['crtherapytypecode'];
					} else
						error("002", "Fetch error. $crquery<br>" . mysqli_error($dbhandle));
				} else
					error("001", "Select error. $crquery<br>" . mysqli_error($dbhandle));
			} else
				error("002", "Non-unique cpid error (should never happen).");
		} else
			error("001", mysqli_error($dbhandle));
	}
} else {
	if (!empty($crid)) {
		$buttonaction = "Insert Prescription";
		$buttonid = $crid;
		if (!isset($_POST['formLoaded'])) {
			// Get Last/Previous Prescription Defaults for this case
			$cpquery = "SELECT cpdmid, cpdlid, cpcnum, cpttmcode, cpdx1, cpdx2, cpdx3, cpdx4, cptherap, cpfrequency, cpduration, cptotalvisits from case_prescriptions WHERE cpcrid='$crid' ORDER BY cpdate DESC LIMIT 1";
			if ($cpresult = mysqli_query($dbhandle, $cpquery)) {
				if ($cprow = mysqli_fetch_assoc($cpresult)) {
					if (empty($_POST['cpdmid']))
						$_POST['cpdmid'] = $cprow['cpdmid'];
					if (empty($_POST['cpdlid']))
						$_POST['cpdlid'] = $cprow['cpdlid'];
					if (empty($_POST['cpcnum']))
						$_POST['cpcnum'] = $cprow['cpcnum'];
					if (empty($_POST['cpttmcode']))
						$_POST['cpttmcode'] = $cprow['cpttmcode'];
					if (empty($_POST['cpdx1']))
						$_POST['cpdx1'] = $cprow['cpdx1'];
					if (empty($_POST['cpdx2']))
						$_POST['cpdx2'] = $cprow['cpdx2'];
					if (empty($_POST['cpdx3']))
						$_POST['cpdx3'] = $cprow['cpdx3'];
					if (empty($_POST['cpdx4']))
						$_POST['cpdx4'] = $cprow['cpdx4'];
					if (empty($_POST['cptherap']))
						$_POST['cptherap'] = $cprow['cptherap'];
					if (empty($_POST['cpfrequency']))
						$_POST['cpfrequency'] = $cprow['cpfrequency'];
					if (empty($_POST['cpduration']))
						$_POST['cpduration'] = $cprow['cpduration'];
					if (empty($_POST['cptotalvisits']))
						$_POST['cptotalvisits'] = $cprow['cptotalvisits'];
				}
				// No else, because this is an insert
				//			else
				//				error("014", "Fetch error. $cpquery<br>" . mysqli_error($dbhandle));	
			} else
				error("013", "Select error. $cpquery<br>" . mysqli_error($dbhandle));
			// Get Case Information Defaults
			$crquery = "SELECT crdate, crrefdmid, crrefdlid, crcnum, crtherapytypecode FROM cases WHERE crid='$crid' LIMIT 1";
			if ($crresult = mysqli_query($dbhandle, $crquery)) {
				if ($crrow = mysqli_fetch_assoc($crresult)) {
					if (empty($_POST['cpdate']))
						$_POST['cpdate'] = $crrow['crdate'];
					if (empty($_POST['cpdmid']))
						$_POST['cpdmid'] = $crrow['crrefdmid'];
					if (empty($_POST['cpdlid']))
						$_POST['cpdlid'] = $crrow['crrefdlid'];
					if (empty($_POST['cpcnum']))
						$_POST['cpcnum'] = $crrow['crcnum'];
					if (empty($_POST['cpttmcode']))
						$_POST['cpttmcode'] = $crrow['crtherapytypecode'];
				} else
					error("002", "Fetch error. $crquery<br>" . mysqli_error($dbhandle));
			} else
				error("001", "Select error. $crquery<br>" . mysqli_error($dbhandle));
		}
	} else
		error("000", "cpid and crid not set. $crid/$cpid");
}
// echo "sdjfhsdfhdshhjk";
// print_r($_POST['cpdx1']);
$crquery_cpdx1 = "SELECT * FROM master_ICD9 WHERE imicd9 ='" . $_POST['cpdx1'] . "'";
$crquery_cpdx2 = "SELECT * FROM master_ICD9 WHERE imicd9 ='" . $_POST['cpdx2'] . "'";
$crquery_cpdx3 = "SELECT * FROM master_ICD9 WHERE imicd9 ='" . $_POST['cpdx3'] . "'";
$crquery_cpdx4 = "SELECT * FROM master_ICD9 WHERE imicd9 ='" . $_POST['cpdx4'] . "'";
$crquery_cpdx5 = "SELECT * FROM master_ICD9 WHERE imicd9 ='" . $_POST['cpdx5'] . "'";
$crquery_cpdx6 = "SELECT * FROM master_ICD9 WHERE imicd9 ='" . $_POST['cpdx6'] . "'";
$crquery_cpdx7 = "SELECT * FROM master_ICD9 WHERE imicd9 ='" . $_POST['cpdx7'] . "'";
$crquery_cpdx8 = "SELECT * FROM master_ICD9 WHERE imicd9 ='" . $_POST['cpdx8'] . "'";
$crquery_cpdx9 = "SELECT * FROM master_ICD9 WHERE imicd9 ='" . $_POST['cpdx9'] . "'";
$crquery_cpdx10 = "SELECT * FROM master_ICD9 WHERE imicd9 ='" . $_POST['cpdx10'] . "'";
$crquery_cpdx11 = "SELECT * FROM master_ICD9 WHERE imicd9 ='" . $_POST['cpdx11'] . "'";
$crquery_cpdx12 = "SELECT * FROM master_ICD9 WHERE imicd9 ='" . $_POST['cpdx12'] . "'";



$getMostUsedCode = "SELECT imicd9, imdx
FROM master_ICD9
WHERE iminactive = 0 AND imicd9 IN (
    'M54.5', 'M54.30', 'M54.31', 'M54.32', '533.5XXD', 'M54.6', 'S23.3XXD', 'M54.2', 'S13.4XXD',
    'M25.521', 'M25.522', 'M25.529', '553.401D', '553.402D', '553.409D', 'G56.2', 'G56.21', 'G56.22',
    'M77.11', 'M77.12', 'M77.10', 'M79.641', 'M79.642', 'M79.643', 'S63.91XD', 'S63.92XD', '563.90XD',
    'M79.644', 'M79.645', 'M79.646', 'M65.30', 'M65.311', 'M65.312', 'M65.319', 'M65.321', 'M65.322',
    'M65.329', 'M65.331', 'M65.332', 'M65.339', 'M65.341', 'M65.342', 'M65.349', 'M65.351', 'M65.352',
    'M65.359', 'M25.531', 'M25.532', 'M25.539', 'G56.0', 'G56.01', 'G56.02', 'M25.561', 'M25.562',
    'M25.569', '583.91XD', 'S83.92XD', '583.90XD', 'M23.91', 'M23.92', 'M23.90', '580.01XD', 'S80.02XD',
    'S80.00XD', 'M25.571', 'M25.572', 'M25.579', 'S93.401D', 'S93.402D', '593.409D', '593.601D', '593.602D',
    '593.609D', 'M79.601', 'M79.602', 'M79.603', 'M79.631', 'M79.632', 'M79.639', 'M25.511', 'M25.512',
    'M25.519', '543.401D', '543.402D', 'S43.409D', 'M25.551', 'M25.552', 'M25.559', '573.101D', '573.102D',
    '573.109'
)
";


$getMostUsedCount = "SELECT  imicd9, imdx FROM master_ICD9 WHERE imicdCount > 0 ORDER BY imicdCount DESC LIMIT 100";



$htmls = array();

if ($usedCodeCount = mysqli_query($dbhandle, $getMostUsedCount)) {

	while ($row = mysqli_fetch_assoc($usedCodeCount)) {
		$htmls[] = "<option value='" . $row['imicd9'] . "'>" . $row['imdx'] . " (" . $row['imicd9'] . " )</option>";

	}
}
if ($usedCode = mysqli_query($dbhandle, $getMostUsedCode)) {
	while ($row = mysqli_fetch_assoc($usedCode)) {
		$htmls[] = "<option value='" . $row['imicd9'] . "'>" . $row['imdx'] . " (" . $row['imicd9'] . " )</option>";

	}
}
$options = implode(" ", $htmls);

$newOption_cpdx1 = "";
$newOption_cpdx2 = "";
$newOption_cpdx3 = "";
$newOption_cpdx4 = "";
$newOption_cpdx5 = "";
$newOption_cpdx6 = "";
$newOption_cpdx7 = "";
$newOption_cpdx8 = "";
$newOption_cpdx9 = "";
$newOption_cpdx10 = "";
$newOption_cpdx11 = "";
$newOption_cpdx12 = "";


if ($cpd1esult = mysqli_query($dbhandle, $crquery_cpdx1)) {
	if ($cpd1ow1 = mysqli_fetch_assoc($cpd1esult)) {
		$newOption_cpdx1 = "<option selected value='" . $cpd1ow1['imicd9'] . "'>" . $cpd1ow1['imdx'] . " (" . $cpd1ow1['imicd9'] . " )</option>";
	}
}

if ($cpd1esult = mysqli_query($dbhandle, $crquery_cpdx2)) {
	if ($cpd1ow2 = mysqli_fetch_assoc($cpd1esult)) {
		$newOption_cpdx2 = "<option selected value='" . $cpd1ow2['imicd9'] . "'>" . $cpd1ow2['imdx'] . " (" . $cpd1ow2['imicd9'] . " ) </option>";
	}
}

// print_r("newOption_cpdx2");
// print_r($newOption_cpdx1);
// print_r($options);


if ($cpd1esult = mysqli_query($dbhandle, $crquery_cpdx3)) {
	if ($cpd1ow3 = mysqli_fetch_assoc($cpd1esult)) {

		$newOption_cpdx3 = "<option id='newOption_cpdx3' selected value='" . $cpd1ow3['imicd9'] . "'>" . $cpd1ow3['imdx'] . " (" . $cpd1ow3['imicd9'] . " ) </option>";
	}
}

if ($cpd1esult = mysqli_query($dbhandle, $crquery_cpdx4)) {
	if ($cpd1ow4 = mysqli_fetch_assoc($cpd1esult)) {

		$newOption_cpdx4 = "<option selected value='" . $cpd1ow4['imicd9'] . "'>" . $cpd1ow4['imdx'] . " (" . $cpd1ow4['imicd9'] . " ) </option>";

	}
}

if ($cpd1esult = mysqli_query($dbhandle, $crquery_cpdx5)) {
	if ($cpd1ow5 = mysqli_fetch_assoc($cpd1esult)) {

		$newOption_cpdx5 = "<option selected value='" . $cpd1ow5['imicd9'] . "'>" . $cpd1ow5['imdx'] . " (" . $cpd1ow5['imicd9'] . " ) </option>";

	}
}

if ($cpd1esult = mysqli_query($dbhandle, $crquery_cpdx6)) {
	if ($cpd1ow6 = mysqli_fetch_assoc($cpd1esult)) {

		$newOption_cpdx6 = "<option selected value='" . $cpd1ow6['imicd9'] . "'>" . $cpd1ow6['imdx'] . " (" . $cpd1ow6['imicd9'] . " ) </option>";

	}
}

if ($cpd1esult = mysqli_query($dbhandle, $crquery_cpdx7)) {
	if ($cpd1ow7 = mysqli_fetch_assoc($cpd1esult)) {

		$newOption_cpdx7 = "<option selected value='" . $cpd1ow7['imicd9'] . "'>" . $cpd1ow7['imdx'] . " (" . $cpd1ow7['imicd9'] . " ) </option>";

	}
}

if ($cpd1esult = mysqli_query($dbhandle, $crquery_cpdx8)) {
	if ($cpd1ow8 = mysqli_fetch_assoc($cpd1esult)) {

		$newOption_cpdx8 = "<option selected value='" . $cpd1ow8['imicd9'] . "'>" . $cpd1ow8['imdx'] . " (" . $cpd1ow8['imicd9'] . " ) </option>";

	}
}
if ($cpd1esult = mysqli_query($dbhandle, $crquery_cpdx9)) {
	if ($cpd1ow9 = mysqli_fetch_assoc($cpd1esult)) {

		$newOption_cpdx9 = "<option selected value='" . $cpd1ow9['imicd9'] . "'>" . $cpd1ow9['imdx'] . " (" . $cpd1ow9['imicd9'] . " ) </option>";

	}
}

if ($cpd1esult = mysqli_query($dbhandle, $crquery_cpdx10)) {
	if ($cpd1ow10 = mysqli_fetch_assoc($cpd1esult)) {

		$newOption_cpdx10 = "<option selected value='" . $cpd1ow10['imicd9'] . "'>" . $cpd1ow10['imdx'] . " (" . $cpd1ow10['imicd9'] . " ) </option>";

	}
}
if ($cpd1esult = mysqli_query($dbhandle, $crquery_cpdx11)) {
	if ($cpd1ow11 = mysqli_fetch_assoc($cpd1esult)) {

		$newOption_cpdx11 = "<option selected value='" . $cpd1ow11['imicd9'] . "'>" . $cpd1ow11['imdx'] . " (" . $cpd1ow11['imicd9'] . " ) </option>";

	}
}
if ($cpd1esult = mysqli_query($dbhandle, $crquery_cpdx12)) {
	if ($cpd1ow12 = mysqli_fetch_assoc($cpd1esult)) {

		$newOption_cpdx12 = "<option selected value='" . $cpd1ow12['imicd9'] . "'>" . $cpd1ow12['imdx'] . " (" . $cpd1ow12['imicd9'] . " ) </option>";

	}
}


if (empty($_POST['cpdate']))
	$_POST['cpdate'] = displayDate(date('Y-m-d'));

$icd9codeoptions = "";

if (errorcount() == 0) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/doctor.options.php');
	$doctorlistoptions = "";
	$doctorlocationlistoptions = "";
	$doctorlocationdisabled = 'disabled="disabled"';
	$doctorlist = getDoctorList();
	//dump("doctorlist",$doctorlist);
	if ($doctorlist) {
		if (count($doctorlist) > 0) {
			$doctorlistoptions = getSelectOptions(
				$arrayofarrayitems = $doctorlist,
				$optionvaluefield = 'dmid',
				$arrayofoptionfields = array(
					'dmlname' => ', ',
					'dmfname' => ''
				),
				$defaultoption = $_POST['cpdmid'],
				$addblankoption = TRUE,
				$arraykey = '',
				$arrayofmatchvalues = array()
			);
			if (!empty($_POST['cpdmid'])) {
				$doctorlocationdisabled = "";
				$doctorlocationlist = getDoctorLocationList($_POST['cpdmid']);
				$doctorlocationlistoptions = getSelectOptions(
					$arrayofarrayitems = $doctorlocationlist,
					$optionvaluefield = 'dlid',
					$arrayofoptionfields = array(
						'dlname' => ', ',
						'dlcity' => ', ',
						'dlphone' => ''
					),
					$defaultoption = $_POST['cpdlid'],
					$addblankoption = TRUE,
					$arraykey = '',
					$arrayofmatchvalues = array()
				);
			} else
				$doctorlocationlistoptions = '<option value="">Select a Doctor...</option>';
		} else
			echo ("Error-No Doctors in Doctor Master.");
	} else
		echo ("Error-getDoctorList.");
	// print_r($_POST['cpdx1']);
// print_r($_POST['cpdx2']);
// print_r($_POST['cpdx3']);
// print_r($_POST['cpdx4']);
// print_r("**************************");
// print_r($_POST);

	$count = 0;
	$newkey = array();
	$newkey = array();
	for ($i = 1; $i <= 12; $i++) {
		$key = "cpdx$i";
		// if (isset($_POST[$key]) && is_numeric($_POST[$key])) {
			if (isset($_POST[$key])) {
			$count++;
			$count = $i;
			$newkey[] = $key;
		}
		if (!is_numeric($_POST[$key])) {

		}
	}
	// print_r($_POST);
	// print_r("************************** count");
	// print_r($count);



	$icd9codearray = icd9CodeOptions();
	$cpdx1html = getSelectOptions($arrayofarrayitems = $icd9codearray, $optionvaluefield = 'code', $arrayofoptionfields = array('description' => ' (', 'code' => ')'), $defaultoption = $_POST['cpdx1'], $addblankoption = TRUE, $arraykey = '', $arrayofmatchvalues = array());
	$cpdx2html = getSelectOptions($arrayofarrayitems = $icd9codearray, $optionvaluefield = 'code', $arrayofoptionfields = array('description' => ' (', 'code' => ')'), $defaultoption = $_POST['cpdx2'], $addblankoption = TRUE, $arraykey = '', $arrayofmatchvalues = array());
	$cpdx3html = getSelectOptions($arrayofarrayitems = $icd9codearray, $optionvaluefield = 'code', $arrayofoptionfields = array('description' => ' (', 'code' => ')'), $defaultoption = $_POST['cpdx3'], $addblankoption = TRUE, $arraykey = '', $arrayofmatchvalues = array());
	$cpdx4html = getSelectOptions($arrayofarrayitems = $icd9codearray, $optionvaluefield = 'code', $arrayofoptionfields = array('description' => ' (', 'code' => ')'), $defaultoption = $_POST['cpdx4'], $addblankoption = TRUE, $arraykey = '', $arrayofmatchvalues = array());

	$cpdx5html = getSelectOptions($arrayofarrayitems = $icd9codearray, $optionvaluefield = 'code', $arrayofoptionfields = array('description' => ' (', 'code' => ')'), $defaultoption = $_POST['cpdx5'], $addblankoption = TRUE, $arraykey = '', $arrayofmatchvalues = array());

	$cpdx6html = getSelectOptions($arrayofarrayitems = $icd9codearray, $optionvaluefield = 'code', $arrayofoptionfields = array('description' => ' (', 'code' => ')'), $defaultoption = $_POST['cpdx6'], $addblankoption = TRUE, $arraykey = '', $arrayofmatchvalues = array());

	$cpdx7html = getSelectOptions($arrayofarrayitems = $icd9codearray, $optionvaluefield = 'code', $arrayofoptionfields = array('description' => ' (', 'code' => ')'), $defaultoption = $_POST['cpdx7'], $addblankoption = TRUE, $arraykey = '', $arrayofmatchvalues = array());

	$cpdx8html = getSelectOptions($arrayofarrayitems = $icd9codearray, $optionvaluefield = 'code', $arrayofoptionfields = array('description' => ' (', 'code' => ')'), $defaultoption = $_POST['cpdx8'], $addblankoption = TRUE, $arraykey = '', $arrayofmatchvalues = array());

	$cpdx9html = getSelectOptions($arrayofarrayitems = $icd9codearray, $optionvaluefield = 'code', $arrayofoptionfields = array('description' => ' (', 'code' => ')'), $defaultoption = $_POST['cpdx9'], $addblankoption = TRUE, $arraykey = '', $arrayofmatchvalues = array());

	$cpdx10html = getSelectOptions($arrayofarrayitems = $icd9codearray, $optionvaluefield = 'code', $arrayofoptionfields = array('description' => ' (', 'code' => ')'), $defaultoption = $_POST['cpdx10'], $addblankoption = TRUE, $arraykey = '', $arrayofmatchvalues = array());

	$cpdx11html = getSelectOptions($arrayofarrayitems = $icd9codearray, $optionvaluefield = 'code', $arrayofoptionfields = array('description' => ' (', 'code' => ')'), $defaultoption = $_POST['cpdx11'], $addblankoption = TRUE, $arraykey = '', $arrayofmatchvalues = array());


	$cpdx12html = getSelectOptions($arrayofarrayitems = $icd9codearray, $optionvaluefield = 'code', $arrayofoptionfields = array('description' => ' (', 'code' => ')'), $defaultoption = $_POST['cpdx12'], $addblankoption = TRUE, $arraykey = '', $arrayofmatchvalues = array());

	$therapistcodearray = therapistCodeOptions($_POST['cpcnum'], $_POST['cpttmcode']);
	$cptheraphtml = getSelectOptions($arrayofarrayitems = $therapistcodearray, $optionvaluefield = 'code', $arrayofoptionfields = array('description' => ' (', 'code' => ')'), $defaultoption = $_POST['cptherap'], $addblankoption = FALSE, $arraykey = '', $arrayofmatchvalues = array());
	?>
	<!-- <button class="addmore-button">Add More </button> -->
	<!-- <button class="remove-button">Remove More </button> -->

	<input type="hidden" value="<?php echo $count; ?>" id="countOfCode" />


	<input type="hidden" value="<?php echo $newOption_cpdx1; ?>" id="newOption_cpdx1" />
	<input type="hidden" value="<?php echo $newOption_cpdx2; ?>" id="newOption_cpdx2" />
	<input type="hidden" value="<?php echo $newOption_cpdx3; ?>" id="newOption_cpdx3" />
	<input type="hidden" value="<?php echo $newOption_cpdx4; ?>" id="newOption_cpdx4" />
	<input type="hidden" value="<?php echo $newOption_cpdx5; ?>" id="newOption_cpdx5" />
	<input type="hidden" value="<?php echo $newOption_cpdx6; ?>" id="newOption_cpdx6" />
	<input type="hidden" value="<?php echo $newOption_cpdx7; ?>" id="newOption_cpdx7" />
	<input type="hidden" value="<?php echo $newOption_cpdx8; ?>" id="newOption_cpdx8" />
	<input type="hidden" value="<?php echo $newOption_cpdx9; ?>" id="newOption_cpdx9" />
	<input type="hidden" value="<?php echo $newOption_cpdx10; ?>" id="newOption_cpdx10" />
	<input type="hidden" value="<?php echo $newOption_cpdx11; ?>" id="newOption_cpdx11" />
	<input type="hidden" value="<?php echo $newOption_cpdx12; ?>" id="newOption_cpdx12" />




	<div class="centerFieldset" style="margin-top:100px;">
		<form action="" method="post" id="getFormData" name="prescriptionEditForm">
			<fieldset style="text-align:center;">
				<legend>
					<?php if ($buttonaction == "Save and Close") {
						echo "Edit Prescription";
					} else {
						echo "Insert Prescription";
					}
					?> Information for
					<?php echo $_POST['patientName']; ?>

					<!-- <//?php echo $buttonaction; ?>  -->
					<!-- <//?php echo $_POST['patientName']; ?> -->
				</legend>
				<table style="text-align:left;" id="table-container">
					<tr>
						<td>Rx Date</td>
						<td nowrap="nowrap" style="text-decoration:none">
							<input id="cpdate" name="cpdate" type="text" size="10" maxlength="10" value="<?php if (isset($_POST['cpdate']))
								echo date("m/d/Y", strtotime($_POST['cpdate'])); ?>" onchange="validateDate(this.id)"><img align="absmiddle"
								name="anchor1" id="anchor1" src="/img/calendar.gif"
								onclick="cal.select(document.forms['prescriptionEditForm'].cpdate,'anchor1','MM/dd/yyyy'); return false;" />
						</td>
					</tr>
					<tr>
						<td>Rx Doctor</td>
						<td><select id="cpdmid" name="cpdmid" type="text" size="1" maxlength="30" value="<?php if (isset($_POST['cpdmid']))
							echo $_POST['cpdmid']; ?>" onchange="javascript:submit()">
								<!--				onchange="showDoctorLocations(this.selectedIndex)" />
--> 	<?php echo $doctorlistoptions; ?>
							</select></td>
					</tr>
					<tr>
						<td>Rx Doctor Location</td>
						<td>

							<select id="cpdlid" name="cpdlid" type="text" size="1" maxlength="30" value="<?php if (isset($_POST['cpdlid']))
								echo $_POST['cpdlid']; ?>" <?php echo $doctorlocationdisabled; ?> />
							<?php echo $doctorlocationlistoptions; ?>
							</select>
						</td>
					</tr>
					<tr id="addMoreButtonDx1">
						<td>
						</td>
						<td>
							<div style="margin-top: 10px;margin-bottom: 10px;    margin-left: 90.5%;">
								<i class="fa-sharp fa-solid fa-circle-plus fa-lg addmore-button" onclick="addDx1()"
									style="cursor:pointer"></i>
							</div>
						</td>
					</tr>
					<tr id="dx1-row">
						<td>Dx1 <div style="float: right;margin-top: 9px;" class="hover-container"><i
									class="fa-regular fa-circle-question fa-lg"></i>
								<div class="hover-message">Results displayed on Initial drop are "Most Used Codes".
									Typing filters Most Used Codes list.
									Pressing "Enter" searches the entire ICD10 code database.
									Additional typing filters the ICD10 search results. </div>
							</div>
						</td>


						<td>
							<!-- <div id="scroll-container" style="max-height: 200px; overflow-y: scroll;">
								<select name="cpdx1" id="cpdx1" >
									<//?php echo $cpdx1html; ?>
								</select> -->
							<!-- </div> -->
							<div id="selectCPDX1">
								<select name="cpdx1" id="cpdx1" class="select2" style="width: 500px">
									<?php

									$defaultOptionValue = $cpdx1html;
									?>
									<option>Please select</option>
									<?php echo $newOption_cpdx1; ?>
									<?php echo $options; ?>


									<?php echo $defaultOptionValue; ?>
									<!-- Add other options dynamically using PHP as needed -->
								</select>
								<i class="fa-sharp fa-solid fa-circle-plus fa-lg addmore-button" data-id="1"
									style="cursor:pointer"></i>
								<i class="fa fa-minus-circle fa-lg remove-button" aria-hidden="true" onClick="removeDx1()"
									style="cursor:pointer"></i>
							</div>


						</td>
					</tr>
					<!-- <tr>
						<td>Dx2</td>
						<td>
							<div id="selectCPDX2">
								<select name="cpdx2" id="cpdx2" class="select2" style="width: 500px">

									<option>Please select</option>

									<? //php echo $newOption_cpdx2; ?>
									<? //php echo $options; ?>

									<? //php echo $cpdx2html; ?>
								</select>
							</div>
						</td>
					</tr> -->
					<!-- <tr>
						<td>Dx3</td>
						<td>
							<div id="selectCPDX3">
								<select name="cpdx3" id="cpdx3" class="select2" style="width: 500px">
									<option>Please select</option>

									<? //php echo $newOption_cpdx3; ?>
									<? //php echo $options; ?>

									<? //php echo $cpdx3html; ?>
								</select>
							</div>
						</td>
					</tr> -->
					<!-- <tr>
						<td>Dx4</td>
						<td>
							<div id="selectCPDX4">
								<select name="cpdx4" id="cpdx4" class="select2" style="width: 500px">
									<option>Please select</option>

									<? //php echo $newOption_cpdx4; ?>
									<? //php echo $options; ?>

									<? //php echo $cpdx4html; ?>
								</select>
							</div>
						</td>
					</tr> -->
					<tr>
						<td>Therapy Clinic</td>
						<td><select name="cpcnum" id="cpcnum" onchange="javascript:submit()">
								<?php echo getSelectOptions($arrayofarrayitems = $_SESSION['useraccess']['clinics'], $optionvaluefield = 'cmcnum', $arrayofoptionfields = array('cmname' => ' (', 'cmcnum' => ')'), $defaultoption = $_POST['cpcnum'], $addblankoption = TRUE, $arraykey = '', $arrayofmatchvalues = array()); ?>
							</select>
						</td>
					</tr>
					<tr>
						<td>Therapy Type</td>
						<td><select name="cpttmcode" id="cpttmcode" onchange="javascript:submit()">
								<?php echo getSelectOptions($arrayofarrayitems = therapyTypeOptions(), $optionvaluefield = 'value', $arrayofoptionfields = array('title' => ' (', 'value' => ')'), $defaultoption = $_POST['cpttmcode'], $addblankoption = FALSE, $arraykey = '', $arrayofmatchvalues = array()); ?>
							</select>
						</td>
					</tr>
					<tr>
						<td>Therapy Therapist</td>
						<td><select name="cptherap" id="cptherap">
								<?php echo $cptheraphtml; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td>Frequency </td>
						<td><select name="cpfrequency" id="cpfrequency">
								<option value="" <?php if (empty($_POST['cpfrequency']))
									echo ' selected="selected"'; ?>>
								</option>
								<option value="1" <?php if ($_POST['cpfrequency'] == '1')
									echo ' selected="selected"'; ?>>1
								</option>
								<option value="2" <?php if ($_POST['cpfrequency'] == '2')
									echo ' selected="selected"'; ?>>2
								</option>
								<option value="3" <?php if ($_POST['cpfrequency'] == '3')
									echo ' selected="selected"'; ?>>3
								</option>
								<option value="4" <?php if ($_POST['cpfrequency'] == '4')
									echo ' selected="selected"'; ?>>4
								</option>
								<option value="5" <?php if ($_POST['cpfrequency'] == '5')
									echo ' selected="selected"'; ?>>5
								</option>
								<option value="6" <?php if ($_POST['cpfrequency'] == '6')
									echo ' selected="selected"'; ?>>6
								</option>
								<option value="7" <?php if ($_POST['cpfrequency'] == '7')
									echo ' selected="selected"'; ?>>7
								</option>
							</select>
							times a week </td>
					</tr>
					<tr>
						<td>Duration </td>
						<td><select name="cpduration" id="cpduration">
								<option value="" <?php if (empty($_POST['cpduration']))
									echo ' selected="selected"'; ?>>
								</option>
								<option value="1" <?php if ($_POST['cpduration'] == '1')
									echo ' selected="selected"'; ?>>1
								</option>
								<option value="2" <?php if ($_POST['cpduration'] == '2')
									echo ' selected="selected"'; ?>>2
								</option>
								<option value="3" <?php if ($_POST['cpduration'] == '3')
									echo ' selected="selected"'; ?>>3
								</option>
								<option value="4" <?php if ($_POST['cpduration'] == '4')
									echo ' selected="selected"'; ?>>4
								</option>
								<option value="5" <?php if ($_POST['cpduration'] == '5')
									echo ' selected="selected"'; ?>>5
								</option>
								<option value="6" <?php if ($_POST['cpduration'] == '6')
									echo ' selected="selected"'; ?>>6
								</option>
								<option value="7" <?php if ($_POST['cpduration'] == '7')
									echo ' selected="selected"'; ?>>7
								</option>
								<option value="8" <?php if ($_POST['cpduration'] == '8')
									echo ' selected="selected"'; ?>>8
								</option>
								<option value="9" <?php if ($_POST['cpduration'] == '9')
									echo ' selected="selected"'; ?>>9
								</option>
								<option value="10" <?php if ($_POST['cpduration'] == '10')
									echo ' selected="selected"'; ?>>10
								</option>
								<option value="11" <?php if ($_POST['cpduration'] == '11')
									echo ' selected="selected"'; ?>>11
								</option>
								<option value="12" <?php if ($_POST['cpduration'] == '12')
									echo ' selected="selected"'; ?>>12
								</option>
							</select>
							weeks
						</td>
					</tr>
					<tr>
						<td>Total Visits</td>
						<td><input name="cptotalvisits" type="text" size="20" maxlength="20" value="<?php if (isset($_POST['cptotalvisits']))
							echo $_POST['cptotalvisits']; ?>" />
						</td>
					</tr>
					<tr>
						<td>Rx End Date</td>
						<td nowrap="nowrap" style="text-decoration:none"><input id="cpexpiredate" name="cpexpiredate"
								type="text" size="10" maxlength="10" value="<?php if (isset($_POST['cpexpiredate']))
									echo date("m/d/Y", strtotime($_POST['cpexpiredate'])); ?>" onchange="validateDate(this.id)">
							<img align="absmiddle" name="anchor2" id="anchor2" src="/img/calendar.gif"
								onclick="cal.select(document.forms['prescriptionEditForm'].cpexpiredate,'anchor2','MM/dd/yyyy'); return false;" />
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<div>
								<div style="float:left; margin:20px;">
									<input name="button[]" type="submit" value="Cancel" />
								</div>
								<!-- <//?//php if ($buttonaction != "Insert Prescription") { ?>
								
								<//?//php } ?> -->
								<div style="float:left; margin:20px;">
										<input onclick="handelAjaxApi()" type="button" value="Save" />
									</div>
								<div style="float:left; margin:20px;">
									<input name="button[<?php echo $buttonid; ?>]" type="submit"
										value="<?php echo $buttonaction; ?>" id="getButtonValue" /><input type="hidden"
										name="formLoaded" value="1" /><input type="hidden" id="getCpid" name="cpid"
										value="<?php echo $_POST['cpid']; ?>" /><input type="hidden" name="cpcrid"
										id="getcpcrid" value="<?php echo $_POST['cpcrid']; ?>" />

									<input type="hidden" name="crid" id="getcrid" value="<?php echo $crid; ?>" />
								</div>
							</div>
						</td>
					</tr>
				</table>
			</fieldset>
		</form>
	</div>
	<?php
}
?>

<script>
	// console.log("buttonValue", buttonValue)
	function handelAjaxApi(params) {
		var formData = $("#getFormData").serialize();

		let buttonValue = $("#getButtonValue").val();

				let getcrid = $("#getgetcrid").val();
		console.log("getcrid", getcrid)

		if (buttonValue == "Insert Prescription") {
			fetch('modules/authorization/prescription/AjaxInsertDxCode.php', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json'
				},
				body: JSON.stringify(formData)
				// body: JSON.stringify({ Dx1Value: Dx1Value, Dx2Value: Dx2Value, Dx3Value: Dx3Value, Dx4Value: Dx4Value, Dx5Value: Dx5Value, Dx6Value: Dx6Value, Dx7Value: Dx7Value, Dx8Value: Dx8Value, Dx9Value: Dx9Value, Dx10Value: Dx10Value, Dx11Value: Dx11Value, Dx12Value: Dx12Value , cpid:cpid , cpcrid:cpcrid   updateDXcode: true })
			})
				.then(response => {
					if (!response.ok) {
						console.log("response ", response)
						throw new Error("Network response was not ok");

					}
					return response.json();
				})
				.then(data => {
					console.log("data", data)
					if (data == "changes done") {
						// alert("Record successfully updated. ")
						Swal.fire(
							// 'Good job!',
							'Record successfully updated!',

						)
					}
				})
				.catch(error => {
					alert("Error Updating Record.")

					console.log("Error Updating Record", error)

				});
		} else {
			fetch('modules/authorization/prescription/AjaxDxcode.php', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json'
				},
				body: JSON.stringify(formData)
				// body: JSON.stringify({ Dx1Value: Dx1Value, Dx2Value: Dx2Value, Dx3Value: Dx3Value, Dx4Value: Dx4Value, Dx5Value: Dx5Value, Dx6Value: Dx6Value, Dx7Value: Dx7Value, Dx8Value: Dx8Value, Dx9Value: Dx9Value, Dx10Value: Dx10Value, Dx11Value: Dx11Value, Dx12Value: Dx12Value , cpid:cpid , cpcrid:cpcrid   updateDXcode: true })
			})
				.then(response => response.json())
				.then(data => {
					if (data == "changes done") {
						// alert("Record successfully updated. ")
						Swal.fire(
							// 'Good job!',
							'Record successfully updated!',

						)
					}
				})
				.catch(error => {
					alert("Error Updating Record.")

					console.log("Error Updating Record", error)

				});
		}



	}

</script>

<script>
	function addDx1() {

		let addTr = `			<tr id="dx1-row">
						<td>Dx1 <div style="float: right;margin-top: 9px;" class="hover-container"><i
									class="fa-regular fa-circle-question fa-lg"></i>
								<div class="hover-message">Results displayed on Initial drop are "Most Used Codes".
								Typing will filter the codes displayed.
									Pressing "Enter" searches the entire ICD10 code database.
									Additional typing filters the ICD10 search results. </div>
							</div>
						</td>


						<td>
							<!-- <div id="scroll-container" style="max-height: 200px; overflow-y: scroll;">
								<select name="cpdx1" id="cpdx1" >
									<//?php echo $cpdx1html; ?>
								</select> -->
							<!-- </div> -->
							<div id="selectCPDX1">
								<select name="cpdx1" id="cpdx1" class="select2" style="width: 500px">
									<?php

									$defaultOptionValue = $cpdx1html;
									?>
									<option>Please select</option>
									<?php echo $newOption_cpdx1; ?>
									<?php echo $options; ?>


									<?php echo $defaultOptionValue; ?>
									<!-- Add other options dynamically using PHP as needed -->
								</select>
								<i class="fa-sharp fa-solid fa-circle-plus fa-lg addmore-button" data-id="1"
									style="cursor:pointer"></i>
								<i class="fa fa-minus-circle fa-lg remove-button" aria-hidden="true" onClick="removeDx1()"
									style="cursor:pointer"></i>
							</div>


						</td>
					</tr>`;
		$(`#addMoreButtonDx1`).after(addTr);
		initializeSelect1("#cpdx1");
		$("#addMoreButtonDx1").hide();

		// $(`#dx1-row`).show();
	}

	function removeDx1() {
		$(`#dx1-row`).remove();
		$("#addMoreButtonDx1").show();
	}

	const dataArray = [1];
	$(document).ready(function () {

		const codeCount = $("#countOfCode").val();
console.log("codeCount", codeCount)
		const firstDx1Value = $(`#cpdx1`).val();

		if (codeCount > 1) {
			for (let i = 2; i <= codeCount; i++) {
				const newOption_cpdx = $(`#newOption_cpdx${i}`).val();
				// console.log("newOption_cpdx", newOption_cpdx);
				// 
				let newTr2 = `
					<tr id="dx${i}-row">
						<td>Dx${i}</td>
						<td>
							<div id="selectCPDX${i}">
								<select name="cpdx${i}" id="cpdx${i}" class="select${i}" style="width: 500px">
									<option>Please select</option>
									${i === 2 ? `
										<?php echo $newOption_cpdx2; ?>
										<?php echo $options; ?>
										<?php echo $cpdx2html; ?>
									` : ''}
									${i === 3 ? `<?php echo $newOption_cpdx3; ?> <?php echo $options; ?> <?php echo $cpdx3html; ?>` : ''}
					${i === 4 ? `<?php echo $newOption_cpdx4; ?> <?php echo $options; ?> <?php echo $cpdx4html; ?>` : ''}
		
					${i == 5 ? `<?php echo $newOption_cpdx5; ?> <?php echo $options; ?> <?php echo $cpdx5html; ?>` : ''}
					${i == 6 ? `<?php echo $newOption_cpdx6; ?> <?php echo $options; ?> <?php echo $cpdx6html; ?>` : ''}

					${i == 7 ? `<?php echo $newOption_cpdx7; ?> <?php echo $options; ?> <?php echo $cpdx7html; ?>` : ''}

					${i == 8 ? `<?php echo $newOption_cpdx8; ?> <?php echo $options; ?> <?php echo $cpdx8html; ?>` : ''}

					${i == 9 ? `<?php echo $newOption_cpdx9; ?> <?php echo $options; ?> <?php echo $cpdx9html; ?>` : ''}

					${i == 10 ? `<?php echo $newOption_cpdx10; ?> <?php echo $options; ?> <?php echo $cpdx10html; ?>` : ''}
					${i == 11 ? `<?php echo $newOption_cpdx11; ?> <?php echo $options; ?> <?php echo $cpdx11html; ?>` : ''}
					${i == 12 ? `<?php echo $newOption_cpdx12; ?> <?php echo $options; ?> <?php echo $cpdx12html; ?>` : ''}


								</select>
							
									${i == 12 ? "" : `<i class="fa-sharp fa-solid fa-circle-plus fa-lg addmore-button" data-id="${i}"
									style="cursor:pointer"></i>`}
							
								<i class="fa fa-minus-circle fa-lg remove-button" id="${i}" aria-hidden="true"
									style="cursor:pointer"></i>
							</div>
						</td>
					</tr>
				`;

				var ids = i - 1;
				$(`#dx${ids}-row`).after(newTr2);
				// if (newOption_cpdx.trim() == '') {
				// 	console.log("newOption_cpdx", newOption_cpdx)
				// 	console.log("newOption_cpdxnewOption_cpdx", i)

				// }
				if (i == 2) {
					initializeSelect2(`#cpdx2`);
				}
				if (i == 3) {
					initializeSelect3(`#cpdx3`);
				}
				if (i == 4) {
					initializeSelect4(`#cpdx4`);
				}
				if (i == 5) {
					initializeSelect5(`#cpdx5`);
				}


				if (i == 6) {
					initializeSelect6(`#cpdx6`);
				}
				if (i == 7) {
					initializeSelect7(`#cpdx7`);
				}
				if (i == 8) {
					initializeSelect8(`#cpdx8`);
				}
				if (i == 9) {
					initializeSelect9(`#cpdx9`);
				}
				if (i == 10) {
					initializeSelect10(`#cpdx10`);
				}
				if (i == 11) {
					initializeSelect11(`#cpdx11`);
				}
				if (i == 12) {
					initializeSelect12(`#cpdx12`);
				}


				$("#countOfCode").val(i + 1);


				// }
			}
			if (firstDx1Value == "Please select") {
				$(`#dx1-row`).remove();
				$("#addMoreButtonDx1").show();
			} else {
				$("#addMoreButtonDx1").hide();

			}
			for (let j = 2; j <= codeCount; j++) {
				// console.log('sel'+$(`#cpdx${j}`).val());
				if ($(`#cpdx${j}`).val() == 'Please select') {
					$(`#dx${j}-row`).remove();
				}
			}
		}
		$(document).on("click", ".addmore-button", function () {
			const buttonId = $(this).attr('data-id');
			var addedCount = parseInt(buttonId) + 1;
			// console.log("addedCount", addedCount)
			// if (addedCount == 1) {
			// 	addedCount = 2;
			// }
			// if (addedCount <= 12) {
			// 	$("#countOfCode").val(parseInt(addedCount) + 1);
			// }
			let numberVal = addedCount
			var checkId = '#dx' + addedCount + '-row';
			if (addedCount <= 12 && $(checkId).length == 0) { // Limit to a maximum of 12 <tr> elements
				// Create the new <tr> element with the specified structure

				const newTr = `
					<tr id="dx${addedCount}-row">
						<td>Dx${addedCount}</td>
						<td>
								<select name="cpdx${numberVal}" id="cpdx${numberVal}" class="select2" style="width: 500px">
									<option>Please select</option>
									${addedCount == 2 ? `
									<?php echo $newOption_cpdx2; ?> 
									<?php echo $options; ?>
									<?php echo $cpdx2html; ?>
									` : ''}
									${addedCount == 3 ? `
									<?php echo $newOption_cpdx3; ?> 
									<?php echo $options; ?>
									<?php echo $cpdx3html; ?>
									` : ''}
									${addedCount == 4 ? `<?php echo $newOption_cpdx4; ?> <?php echo $options; ?> <?php echo $cpdx4html; ?>` : ''}
					${addedCount == 5 ? `<?php echo $newOption_cpdx5; ?> <?php echo $options; ?> <?php echo $cpdx5html; ?>` : ''}
					${addedCount == 6 ? `<?php echo $newOption_cpdx6; ?> <?php echo $options; ?> <?php echo $cpdx6html; ?>` : ''}

					${addedCount == 7 ? `<?php echo $newOption_cpdx7; ?> <?php echo $options; ?> <?php echo $cpdx7html; ?>` : ''}

					${addedCount == 8 ? `<?php echo $newOption_cpdx8; ?> <?php echo $options; ?> <?php echo $cpdx8html; ?>` : ''}

					${addedCount == 9 ? `<?php echo $newOption_cpdx9; ?> <?php echo $options; ?> <?php echo $cpdx9html; ?>` : ''}

					${addedCount == 10 ? `<?php echo $newOption_cpdx10; ?> <?php echo $options; ?> <?php echo $cpdx10html; ?>` : ''}
					${addedCount == 11 ? `<?php echo $newOption_cpdx11; ?> <?php echo $options; ?> <?php echo $cpdx11html; ?>` : ''}
					${addedCount == 12 ? `<?php echo $newOption_cpdx12; ?> <?php echo $options; ?> <?php echo $cpdx12html; ?>` : ''}


			
								</select>
								${addedCount == 12 ? "" : `<i class="fa-sharp fa-solid fa-circle-plus fa-lg addmore-button" data-id="${numberVal}"
									style="cursor:pointer"></i>`}
							
								<i class="fa fa-minus-circle fa-lg remove-button" id="${numberVal}" aria-hidden="true"
									style="cursor:pointer"></i>
							</div>
						</td>
					</tr>
				`;

				// console.log(newTr);
				// $("#table-container").append(newTr);
				var ids = addedCount - 1;
				$(`#dx${ids}-row`).after(newTr);
				dataArray.push(parseInt(addedCount))
				addedCount++;
				if (numberVal == 2) {
					initializeSelect2(`#cpdx2`);
				}
				if (numberVal == 3) {

					initializeSelect3(`#cpdx3`);
				}
				if (numberVal == 4) {
					initializeSelect4(`#cpdx4`);
				}
				if (numberVal == 5) {
					initializeSelect5(`#cpdx5`);
				}


				if (numberVal == 6) {
					initializeSelect6(`#cpdx6`);
				}
				if (numberVal == 7) {

					initializeSelect7(`#cpdx7`);
				}
				if (numberVal == 8) {
					initializeSelect8(`#cpdx8`);
				}
				if (numberVal == 9) {
					initializeSelect9(`#cpdx9`);
				}
				if (numberVal == 10) {
					initializeSelect10(`#cpdx10`);
				}
				if (numberVal == 11) {
					initializeSelect11(`#cpdx11`);
				}
				if (numberVal == 12) {
					initializeSelect12(`#cpdx12`);
				}
			} else {
				for (let k = 2; k <= 12; k++) {
					var checkId = '#dx' + k + '-row';
					if ($(checkId).length == 0) {
						const newTr = `
							<tr id="dx${k}-row">
								<td>Dx${k}</td>
								<td>
										<select name="cpdx${k}" id="cpdx${k}" class="select2" style="width: 500px">
											<option>Please select</option>
											${k == 2 ? `
											<?php echo $newOption_cpdx2; ?> 
											<?php echo $options; ?>
											<?php echo $cpdx2html; ?>
											` : ''}
											${k == 3 ? `
											<?php echo $newOption_cpdx3; ?> 
											<?php echo $options; ?>
											<?php echo $cpdx3html; ?>
											` : ''}
											${k == 4 ? `<?php echo $newOption_cpdx4; ?> <?php echo $options; ?> <?php echo $cpdx4html; ?>` : ''}
							${k == 5 ? `<?php echo $newOption_cpdx5; ?> <?php echo $options; ?> <?php echo $cpdx5html; ?>` : ''}
							${k == 6 ? `<?php echo $newOption_cpdx6; ?> <?php echo $options; ?> <?php echo $cpdx6html; ?>` : ''}

							${k == 7 ? `<?php echo $newOption_cpdx7; ?> <?php echo $options; ?> <?php echo $cpdx7html; ?>` : ''}

							${k == 8 ? `<?php echo $newOption_cpdx8; ?> <?php echo $options; ?> <?php echo $cpdx8html; ?>` : ''}

							${k == 9 ? `<?php echo $newOption_cpdx9; ?> <?php echo $options; ?> <?php echo $cpdx9html; ?>` : ''}

							${k == 10 ? `<?php echo $newOption_cpdx10; ?> <?php echo $options; ?> <?php echo $cpdx10html; ?>` : ''}
							${k == 11 ? `<?php echo $newOption_cpdx11; ?> <?php echo $options; ?> <?php echo $cpdx11html; ?>` : ''}
							${k == 12 ? `<?php echo $newOption_cpdx12; ?> <?php echo $options; ?> <?php echo $cpdx12html; ?>` : ''}


					
										</select>
										${k == 12 ? "" : `<i class="fa-sharp fa-solid fa-circle-plus fa-lg addmore-button" data-id="${k}"
											style="cursor:pointer"></i>`}
									
										<i class="fa fa-minus-circle fa-lg remove-button" id="${k}" aria-hidden="true"
											style="cursor:pointer"></i>
									</div>
								</td>
							</tr>
						`;

						var ids = k - 1;
						$(`#dx${ids}-row`).after(newTr);

						if (k == 2) {
							initializeSelect2(`#cpdx2`);
						}
						if (k == 3) {

							initializeSelect3(`#cpdx3`);
						}
						if (k == 4) {
							initializeSelect4(`#cpdx4`);
						}
						if (k == 5) {
							initializeSelect5(`#cpdx5`);
						}


						if (k == 6) {
							initializeSelect6(`#cpdx6`);
						}
						if (k == 7) {

							initializeSelect7(`#cpdx7`);
						}
						if (k == 8) {
							initializeSelect8(`#cpdx8`);
						}
						if (k == 9) {
							initializeSelect9(`#cpdx9`);
						}
						if (k == 10) {
							initializeSelect10(`#cpdx10`);
						}
						if (k == 11) {
							initializeSelect11(`#cpdx11`);
						}
						if (k == 12) {
							initializeSelect12(`#cpdx12`);
						}
						break;
					}
				}
			}
		});
		$(document).on("click", ".remove-button", function () {
			const buttonId = $(this).attr('id');
			// console.log("buttonId", buttonId)


			// let addedCount = $("#countOfCode").val();
			// const checkId = addedCount - 1;
			// const id = 'cpdx' + checkId;
			// const val = $('#' + id).val();


			// if (addedCount > 1 && val == 'Please select') {

			// 	const getIndex = dataArray.indexOf(parseInt(buttonId));
			// 	let spliced = dataArray.splice(getIndex, 1);

			// 	$("#countOfCode").val(parseInt(addedCount) - 1);
			// addedCount = $("#countOfCode").val()
			$(`#dx${buttonId}-row`).remove();
			// addedCount--;
			// }
		});
	});
</script>

<script>


	let cpdx1 = false;
	let cpdx2 = false;
	let cpdx3 = false;
	let cpdx4 = false;


	const initializeSelect1 = (selectId) => {
		let selectElement = jQuery(`${selectId}`);
		let initialized = false;

		selectElement.select2({
			placeholder: 'Please Select',
			"language": {
				"noResults": function(){
					return "No results found. Press Enter to search full database.";
				}
			},
		});

		selectElement.on('select2:open', function () {
			if (!initialized) {
				initialized = true;
				const searchTermField = jQuery(this).data('select2').dropdown.$search || jQuery(this).data('select2').dropdown.$searchbox;
				// console.log("jQuery(this).val().trim();" ,jQuery(this).val().trim())
				// console.log("gautam ", searchTermField)
				searchTermField.on('input', function (event) {
					const searchTerm22 = jQuery(this).val().trim();
					localStorage.setItem("initializeSelect111", searchTerm22);
					searchTermField.on('keydown', function (event) {
						const searchTerm = jQuery(this).val().trim();
						const dropdown = jQuery(this).parent().find('.select2-results');
						// console.log("jQuery(this).val().trim(); searchTerm" ,searchTerm)

						if (event.keyCode === 13) {
							event.preventDefault();
							if (searchTerm.length >= 3) {
								fetch('modules/authorization/prescription/Ajax.php', {
									method: 'POST',
									headers: {
										'Content-Type': 'application/json'
									},
									body: JSON.stringify({ searchTerm: searchTerm, searching: true })
								})
									.then(response => response.json())
									.then(data => {
										if (data.length > 0) {
											const options = data.map(item => {
												const optionValue = `${item.imdx} (${item.imicd9})`;
												return new Option(optionValue, item.imicd9);
											});
											// selectElement.empty().append(options);

											// const isDropdownOpen = selectElement.data('select2').isOpen();
											// if (!isDropdownOpen) {
											// 	selectElement.select2("open");
											// }

											// console.log('open');

											setTimeout(function () {
												selectElement.empty().append(options)
												selectElement.select2("close").select2("open");
											}, 1000);
										}




									})
									.catch(error => {
										console.log(error);
									});
							} else {
								// If the search term length is less than 3 characters, simply open the dropdown
								const isDropdownOpen = selectElement.data('select2').isOpen();
								if (!isDropdownOpen) {

									const searchTerm2 = localStorage.getItem("initializeSelect111");
									fetch('modules/authorization/prescription/Ajax.php', {
										method: 'POST',
										headers: {
											'Content-Type': 'application/json'
										},
										body: JSON.stringify({ searchTerm: searchTerm2, searching: true })
									})
										.then(response => response.json())
										.then(data => {


											if (data.length > 0) {
												const options = data.map(item => {
													const optionValue = `${item.imdx} (${item.imicd9})`;
													return new Option(optionValue, item.imicd9);
												});


												setTimeout(function () {
													selectElement.empty().append(options)
													selectElement.select2("open");
												}, 1000);

											}



										})
										.catch(error => {
											console.log(error);
										});



								}
							}
						}
					});

				});
			}
		});


	};


	const initializeSelect2 = (selectId) => {
		let selectElement = jQuery(`${selectId}`);
		let initialized = false;
		selectElement.select2({
			placeholder: 'Please Select',
			"language": {
				"noResults": function(){
					return "No results found. Press Enter to search full database.";
				}
			},
		});
		selectElement.on('select2:open', function () {
			if (!initialized) {
				initialized = true;
			}
		});
		// selectValueHandler(selectElement, selectId);
		selectElement.on('select2:open', function () {
			const searchTermField22 = jQuery(this).data('select2').dropdown.$search || jQuery(this).data('select2').dropdown.$searchbox;

			searchTermField22.on('input', function (event) {
				const searchTerm33 = jQuery(this).val().trim();
				localStorage.setItem("initializeSelect22", searchTerm33);

				searchTermField22.on('keydown', function (event) {


					if (event.keyCode === 13) {
						event.preventDefault();


						const searchTerm = jQuery(this).val();
						const dropdown = jQuery(this).parent().find('.select2-results');

						if (searchTerm.length >= 3) {


							fetch('modules/authorization/prescription/Ajax.php', {
								method: 'POST',
								headers: {
									'Content-Type': 'application/json'
								},
								body: JSON.stringify({ searchTerm: searchTerm, searching: true })
							})
								.then(response => response.json())
								.then(data => {

									if (data.length > 0) {
										const options = data.map(item => {
											const optionValue = `${item.imdx} (${item.imicd9})`;
											return new Option(optionValue, item.imicd9);
										});


										setTimeout(function () {
											// selectElement.select2("open");
											selectElement.empty().append(options)
											selectElement.select2("close").select2("open");
										}, 1000);

									}
								})
								.catch(error => {
									console.log(error);
								});
						}

						else {

							// If the search term length is less than 3 characters, simply open the dropdown
							// const isDropdownOpen3 = selectElement.isOpen();
							// if (!isDropdownOpen3) {

							const searchTerm3 = localStorage.getItem("initializeSelect22");
							console.log("searchTerm3searchTerm3searchTerm3", searchTerm3)
							fetch('modules/authorization/prescription/Ajax.php', {
								method: 'POST',
								headers: {
									'Content-Type': 'application/json'
								},
								body: JSON.stringify({ searchTerm: searchTerm3, searching: true })
							})
								.then(response => response.json())
								.then(data => {

									if (data.length > 0) {
										const options = data.map(item => {
											const optionValue = `${item.imdx} (${item.imicd9})`;
											return new Option(optionValue, item.imicd9);
										});


										setTimeout(function () {
											selectElement.empty().append(options)
											selectElement.select2("open");
										}, 1000);

									}

								})
								.catch(error => {
									console.log(error);
								});



							// }
						}
					}
				});
			});
		});
	};

	const initializeSelect3 = (selectId) => {

		let selectElement = jQuery(`${selectId}`);
		let initialized = false;
		selectElement.select2({
			placeholder: 'Please Select',
			"language": {
				"noResults": function(){
					return "No results found. Press Enter to search full database.";
				}
			},
		});
		selectElement.on('select2:open', function () {
			if (!initialized) {
				initialized = true;
			}
		});
		// selectValueHandler(selectElement, selectId);
		selectElement.on('select2:open', function () {
			const searchTermField = jQuery(this).data('select2').dropdown.$search || jQuery(this).data('select2').dropdown.$searchbox;
			searchTermField.on('input', function (event) {
				const searchTerm44 = jQuery(this).val().trim();
				localStorage.setItem("initializeSelect33", searchTerm44);
				searchTermField.on('keydown', function (event) {
					if (event.keyCode === 13) {


						const searchTerm = jQuery(this).val();
						const dropdown = jQuery(this).parent().find('.select2-results');

						if (searchTerm.length >= 3) {


							fetch('modules/authorization/prescription/Ajax.php', {
								method: 'POST',
								headers: {
									'Content-Type': 'application/json'
								},
								body: JSON.stringify({ searchTerm: searchTerm, searching: true })
							})
								.then(response => response.json())
								.then(data => {


									// selectElement.empty();

									// data.forEach((item) => {
									// 	const optionValue = `${item.imdx}  (${item.imicd9})`;
									// 	const option = new Option(optionValue, item.imicd9);
									// 	selectElement.append(option);
									// });

									// selectElement.select2("close");
									// selectElement.val(searchTerm).trigger('change');


									// ++++++++++++++++++++++++++

									if (data.length > 0) {
										const options = data.map(item => {
											const optionValue = `${item.imdx} (${item.imicd9})`;
											return new Option(optionValue, item.imicd9);
										});

										setTimeout(function () {
											// selectElement.select2("open");
											selectElement.empty().append(options)
											selectElement.select2("close").select2("open");
										}, 1000);

									}

								})
								.catch(error => {
									console.log(error);
								});
						}
						else {
							// If the search term length is less than 3 characters, simply open the dropdown
							const isDropdownOpen = selectElement.data('select2').isOpen();
							if (!isDropdownOpen) {

								const searchTerm4 = localStorage.getItem("initializeSelect33");
								fetch('modules/authorization/prescription/Ajax.php', {
									method: 'POST',
									headers: {
										'Content-Type': 'application/json'
									},
									body: JSON.stringify({ searchTerm: searchTerm4, searching: true })
								})
									.then(response => response.json())
									.then(data => {

										if (data.length > 0) {

											const options = data.map(item => {
												const optionValue = `${item.imdx} (${item.imicd9})`;
												return new Option(optionValue, item.imicd9);
											});


											setTimeout(function () {
												selectElement.empty().append(options)
												selectElement.select2("open");
											}, 1000);


										}
									})
									.catch(error => {
										console.log(error);
									});



							}
						}
					}
				});
			});
		});

	};

	const initializeSelect4 = (selectId) => {
		const selectElement = jQuery(`${selectId}`);
		let initialized = false;

		selectElement.select2({
			placeholder: 'Please Select',
			"language": {
				"noResults": function(){
					return "No results found. Press Enter to search full database.";
				}
			},
		});

		selectElement.on('select2:open', function () {
			if (!initialized) {
				initialized = true;
			}
		});

		selectElement.on('select2:open', function () {
			const searchTermField = jQuery(this).data('select2').dropdown.$search || jQuery(this).data('select2').dropdown.$searchbox;
			searchTermField.on('input', function (event) {
				const searchTerm55 = jQuery(this).val().trim();
				localStorage.setItem("initializeSelect44", searchTerm55);
				searchTermField.on('keydown', function (event) {
					if (event.keyCode === 13) {


						const searchTerm = jQuery(this).val();
						const dropdown = jQuery(this).parent().find('.select2-results');

						if (searchTerm.length >= 3) {


							fetch('modules/authorization/prescription/Ajax.php', {
								method: 'POST',
								headers: {
									'Content-Type': 'application/json'
								},
								body: JSON.stringify({ searchTerm: searchTerm, searching: true })
							})
								.then(response => response.json())
								.then(data => {


									// selectElement.empty();

									// data.forEach((item) => {
									// 	const optionValue = `${item.imdx}  (${item.imicd9})`;
									// 	const option = new Option(optionValue, item.imicd9);
									// 	selectElement.append(option);
									// });

									// selectElement.select2("close");
									// selectElement.val(searchTerm).trigger('change');


									// ++++++++++++++++++++++++++

									if (data.length > 0) {
										const options = data.map(item => {
											const optionValue = `${item.imdx} (${item.imicd9})`;
											return new Option(optionValue, item.imicd9);
										});
										setTimeout(function () {
											// selectElement.select2("open");
											selectElement.empty().append(options)
											selectElement.select2("close").select2("open");
										}, 1000);

									}

								})
								.catch(error => {
									console.log(error);
								});
						}
						else {
							// If the search term length is less than 3 characters, simply open the dropdown
							const isDropdownOpen = selectElement.data('select2').isOpen();
							if (!isDropdownOpen) {

								const searchTerm5 = localStorage.getItem("initializeSelect44");
								fetch('modules/authorization/prescription/Ajax.php', {
									method: 'POST',
									headers: {
										'Content-Type': 'application/json'
									},
									body: JSON.stringify({ searchTerm: searchTerm5, searching: true })
								})
									.then(response => response.json())
									.then(data => {

										if (data.length > 0) {
											const options = data.map(item => {
												const optionValue = `${item.imdx} (${item.imicd9})`;
												return new Option(optionValue, item.imicd9);
											});


											setTimeout(function () {
												selectElement.empty().append(options)
												selectElement.select2("open");
											}, 1000);
										}


									})
									.catch(error => {
										console.log(error);
									});
							}
						}
					}
				});
			});
		});
	};


	const initializeSelect5 = (selectId) => {

		const selectElement = jQuery(`${selectId}`);
		let initialized = false;

		selectElement.select2({
			placeholder: 'Please Select',
			"language": {
				"noResults": function(){
					return "No results found. Press Enter to search full database.";
				}
			},
		});

		selectElement.on('select2:open', function () {
			if (!initialized) {
				initialized = true;
			}
		});

		selectElement.on('select2:open', function () {
			const searchTermField = jQuery(this).data('select2').dropdown.$search || jQuery(this).data('select2').dropdown.$searchbox;
			searchTermField.on('input', function (event) {
				const searchTerm55 = jQuery(this).val().trim();
				localStorage.setItem("initializeSelect55", searchTerm55);
				searchTermField.on('keydown', function (event) {
					if (event.keyCode === 13) {


						const searchTerm = jQuery(this).val();
						const dropdown = jQuery(this).parent().find('.select2-results');

						if (searchTerm.length >= 3) {


							fetch('modules/authorization/prescription/Ajax.php', {
								method: 'POST',
								headers: {
									'Content-Type': 'application/json'
								},
								body: JSON.stringify({ searchTerm: searchTerm, searching: true })
							})
								.then(response => response.json())
								.then(data => {


									// selectElement.empty();

									// data.forEach((item) => {
									// 	const optionValue = `${item.imdx}  (${item.imicd9})`;
									// 	const option = new Option(optionValue, item.imicd9);
									// 	selectElement.append(option);
									// });

									// selectElement.select2("close");
									// selectElement.val(searchTerm).trigger('change');


									// ++++++++++++++++++++++++++
									if (data.length > 0) {
										const options = data.map(item => {
											const optionValue = `${item.imdx} (${item.imicd9})`;
											return new Option(optionValue, item.imicd9);
										});
										setTimeout(function () {
											// selectElement.select2("open");
											selectElement.empty().append(options)
											selectElement.select2("close").select2("open");
										}, 1000);

									}
								})
								.catch(error => {
									console.log(error);
								});
						}
						else {
							// If the search term length is less than 3 characters, simply open the dropdown
							const isDropdownOpen = selectElement.data('select2').isOpen();
							if (!isDropdownOpen) {

								const searchTerm5 = localStorage.getItem("initializeSelect55");
								fetch('modules/authorization/prescription/Ajax.php', {
									method: 'POST',
									headers: {
										'Content-Type': 'application/json'
									},
									body: JSON.stringify({ searchTerm: searchTerm5, searching: true })
								})
									.then(response => response.json())
									.then(data => {


										if (data.length > 0) {
											const options = data.map(item => {
												const optionValue = `${item.imdx} (${item.imicd9})`;
												return new Option(optionValue, item.imicd9);
											});


											setTimeout(function () {
												selectElement.empty().append(options)
												selectElement.select2("open");
											}, 1000);
										}


									})
									.catch(error => {
										console.log(error);
									});
							}
						}
					}
				});
			});
		});
	};

	const initializeSelect6 = (selectId) => {
		const selectElement = jQuery(`${selectId}`);
		let initialized = false;

		selectElement.select2({
			placeholder: 'Please Select',
			"language": {
				"noResults": function(){
					return "No results found. Press Enter to search full database.";
				}
			},
		});

		selectElement.on('select2:open', function () {
			if (!initialized) {
				initialized = true;
			}
		});

		selectElement.on('select2:open', function () {
			const searchTermField = jQuery(this).data('select2').dropdown.$search || jQuery(this).data('select2').dropdown.$searchbox;
			searchTermField.on('input', function (event) {
				const searchTerm55 = jQuery(this).val().trim();
				localStorage.setItem("initializeSelect66", searchTerm55);
				searchTermField.on('keydown', function (event) {
					if (event.keyCode === 13) {


						const searchTerm = jQuery(this).val();
						const dropdown = jQuery(this).parent().find('.select2-results');

						if (searchTerm.length >= 3) {


							fetch('modules/authorization/prescription/Ajax.php', {
								method: 'POST',
								headers: {
									'Content-Type': 'application/json'
								},
								body: JSON.stringify({ searchTerm: searchTerm, searching: true })
							})
								.then(response => response.json())
								.then(data => {


									// selectElement.empty();

									// data.forEach((item) => {
									// 	const optionValue = `${item.imdx}  (${item.imicd9})`;
									// 	const option = new Option(optionValue, item.imicd9);
									// 	selectElement.append(option);
									// });

									// selectElement.select2("close");
									// selectElement.val(searchTerm).trigger('change');


									// ++++++++++++++++++++++++++
									if (data.length > 0) {
										const options = data.map(item => {
											const optionValue = `${item.imdx} (${item.imicd9})`;
											return new Option(optionValue, item.imicd9);
										});
										setTimeout(function () {
											// selectElement.select2("open");
											selectElement.empty().append(options)
											selectElement.select2("close").select2("open");
										}, 1000);

									}
								})
								.catch(error => {
									console.log(error);
								});
						}
						else {
							// If the search term length is less than 3 characters, simply open the dropdown
							const isDropdownOpen = selectElement.data('select2').isOpen();
							if (!isDropdownOpen) {

								const searchTerm5 = localStorage.getItem("initializeSelect66");
								fetch('modules/authorization/prescription/Ajax.php', {
									method: 'POST',
									headers: {
										'Content-Type': 'application/json'
									},
									body: JSON.stringify({ searchTerm: searchTerm5, searching: true })
								})
									.then(response => response.json())
									.then(data => {

										if (data.length > 0) {
											const options = data.map(item => {
												const optionValue = `${item.imdx} (${item.imicd9})`;
												return new Option(optionValue, item.imicd9);
											});


											setTimeout(function () {
												selectElement.empty().append(options)
												selectElement.select2("open");
											}, 1000);
										}


									})
									.catch(error => {
										console.log(error);
									});
							}
						}
					}
				});
			});
		});
	};








	const initializeSelect7 = (selectId) => {
		const selectElement = jQuery(`${selectId}`);
		let initialized = false;

		selectElement.select2({
			placeholder: 'Please Select',
			"language": {
				"noResults": function(){
					return "No results found. Press Enter to search full database.";
				}
			},
		});

		selectElement.on('select2:open', function () {
			if (!initialized) {
				initialized = true;
			}
		});

		selectElement.on('select2:open', function () {
			const searchTermField = jQuery(this).data('select2').dropdown.$search || jQuery(this).data('select2').dropdown.$searchbox;
			searchTermField.on('input', function (event) {
				const searchTerm55 = jQuery(this).val().trim();
				localStorage.setItem("initializeSelect77", searchTerm55);
				searchTermField.on('keydown', function (event) {
					if (event.keyCode === 13) {


						const searchTerm = jQuery(this).val();
						const dropdown = jQuery(this).parent().find('.select2-results');

						if (searchTerm.length >= 3) {


							fetch('modules/authorization/prescription/Ajax.php', {
								method: 'POST',
								headers: {
									'Content-Type': 'application/json'
								},
								body: JSON.stringify({ searchTerm: searchTerm, searching: true })
							})
								.then(response => response.json())
								.then(data => {


									// selectElement.empty();

									// data.forEach((item) => {
									// 	const optionValue = `${item.imdx}  (${item.imicd9})`;
									// 	const option = new Option(optionValue, item.imicd9);
									// 	selectElement.append(option);
									// });

									// selectElement.select2("close");
									// selectElement.val(searchTerm).trigger('change');


									// ++++++++++++++++++++++++++


									if (data.length > 0) {

										const options = data.map(item => {
											const optionValue = `${item.imdx} (${item.imicd9})`;
											return new Option(optionValue, item.imicd9);
										});
										setTimeout(function () {
											// selectElement.select2("open");
											selectElement.empty().append(options)
											selectElement.select2("close").select2("open");
										}, 1000);

									}


								})
								.catch(error => {
									console.log(error);
								});
						}
						else {
							// If the search term length is less than 3 characters, simply open the dropdown
							const isDropdownOpen = selectElement.data('select2').isOpen();
							if (!isDropdownOpen) {

								const searchTerm5 = localStorage.getItem("initializeSelect77");
								fetch('modules/authorization/prescription/Ajax.php', {
									method: 'POST',
									headers: {
										'Content-Type': 'application/json'
									},
									body: JSON.stringify({ searchTerm: searchTerm5, searching: true })
								})
									.then(response => response.json())
									.then(data => {

										if (data.length > 0) {
											const options = data.map(item => {
												const optionValue = `${item.imdx} (${item.imicd9})`;
												return new Option(optionValue, item.imicd9);
											});


											setTimeout(function () {
												selectElement.empty().append(options)
												selectElement.select2("open");
											}, 1000);

										}

									})
									.catch(error => {
										console.log(error);
									});
							}
						}
					}
				});
			});
		});
	};


	const initializeSelect8 = (selectId) => {
		const selectElement = jQuery(`${selectId}`);
		let initialized = false;

		selectElement.select2({
			placeholder: 'Please Select',
			"language": {
				"noResults": function(){
					return "No results found. Press Enter to search full database.";
				}
			},
		});

		selectElement.on('select2:open', function () {
			if (!initialized) {
				initialized = true;
			}
		});

		selectElement.on('select2:open', function () {
			const searchTermField = jQuery(this).data('select2').dropdown.$search || jQuery(this).data('select2').dropdown.$searchbox;
			searchTermField.on('input', function (event) {
				const searchTerm55 = jQuery(this).val().trim();
				localStorage.setItem("initializeSelect88", searchTerm55);
				searchTermField.on('keydown', function (event) {
					if (event.keyCode === 13) {


						const searchTerm = jQuery(this).val();
						const dropdown = jQuery(this).parent().find('.select2-results');

						if (searchTerm.length >= 3) {


							fetch('modules/authorization/prescription/Ajax.php', {
								method: 'POST',
								headers: {
									'Content-Type': 'application/json'
								},
								body: JSON.stringify({ searchTerm: searchTerm, searching: true })
							})
								.then(response => response.json())
								.then(data => {


									// selectElement.empty();

									// data.forEach((item) => {
									// 	const optionValue = `${item.imdx}  (${item.imicd9})`;
									// 	const option = new Option(optionValue, item.imicd9);
									// 	selectElement.append(option);
									// });

									// selectElement.select2("close");
									// selectElement.val(searchTerm).trigger('change');


									// ++++++++++++++++++++++++++

									if (data.length > 0) {
										const options = data.map(item => {
											const optionValue = `${item.imdx} (${item.imicd9})`;
											return new Option(optionValue, item.imicd9);
										});
										setTimeout(function () {
											// selectElement.select2("open");
											selectElement.empty().append(options)
											selectElement.select2("close").select2("open");
										}, 1000);
									}
								})
								.catch(error => {
									console.log(error);
								});
						}
						else {
							// If the search term length is less than 3 characters, simply open the dropdown
							const isDropdownOpen = selectElement.data('select2').isOpen();
							if (!isDropdownOpen) {

								const searchTerm5 = localStorage.getItem("initializeSelect88");
								fetch('modules/authorization/prescription/Ajax.php', {
									method: 'POST',
									headers: {
										'Content-Type': 'application/json'
									},
									body: JSON.stringify({ searchTerm: searchTerm5, searching: true })
								})
									.then(response => response.json())
									.then(data => {

										if (data.length > 0) {
											const options = data.map(item => {
												const optionValue = `${item.imdx} (${item.imicd9})`;
												return new Option(optionValue, item.imicd9);
											});


											setTimeout(function () {
												selectElement.empty().append(options)
												selectElement.select2("open");
											}, 1000);
										}


									})
									.catch(error => {
										console.log(error);
									});
							}
						}
					}
				});
			});
		});
	};


	const initializeSelect9 = (selectId) => {
		const selectElement = jQuery(`${selectId}`);
		let initialized = false;

		selectElement.select2({
			placeholder: 'Please Select',
			"language": {
				"noResults": function(){
					return "No results found. Press Enter to search full database.";
				}
			},
		});

		selectElement.on('select2:open', function () {
			if (!initialized) {
				initialized = true;
			}
		});

		selectElement.on('select2:open', function () {
			const searchTermField = jQuery(this).data('select2').dropdown.$search || jQuery(this).data('select2').dropdown.$searchbox;
			searchTermField.on('input', function (event) {
				const searchTerm55 = jQuery(this).val().trim();
				localStorage.setItem("initializeSelect99", searchTerm55);
				searchTermField.on('keydown', function (event) {
					if (event.keyCode === 13) {


						const searchTerm = jQuery(this).val();
						const dropdown = jQuery(this).parent().find('.select2-results');

						if (searchTerm.length >= 3) {


							fetch('modules/authorization/prescription/Ajax.php', {
								method: 'POST',
								headers: {
									'Content-Type': 'application/json'
								},
								body: JSON.stringify({ searchTerm: searchTerm, searching: true })
							})
								.then(response => response.json())
								.then(data => {


									// selectElement.empty();

									// data.forEach((item) => {
									// 	const optionValue = `${item.imdx}  (${item.imicd9})`;
									// 	const option = new Option(optionValue, item.imicd9);
									// 	selectElement.append(option);
									// });

									// selectElement.select2("close");
									// selectElement.val(searchTerm).trigger('change');


									// ++++++++++++++++++++++++++
									if (data.length > 0) {
										const options = data.map(item => {
											const optionValue = `${item.imdx} (${item.imicd9})`;
											return new Option(optionValue, item.imicd9);
										});
										setTimeout(function () {
											// selectElement.select2("open");
											selectElement.empty().append(options)
											selectElement.select2("close").select2("open");
										}, 1000);

									}
								})
								.catch(error => {
									console.log(error);
								});
						}
						else {
							// If the search term length is less than 3 characters, simply open the dropdown
							const isDropdownOpen = selectElement.data('select2').isOpen();
							if (!isDropdownOpen) {

								const searchTerm5 = localStorage.getItem("initializeSelect99");
								fetch('modules/authorization/prescription/Ajax.php', {
									method: 'POST',
									headers: {
										'Content-Type': 'application/json'
									},
									body: JSON.stringify({ searchTerm: searchTerm5, searching: true })
								})
									.then(response => response.json())
									.then(data => {

										if (data.length > 0) {
											const options = data.map(item => {
												const optionValue = `${item.imdx} (${item.imicd9})`;
												return new Option(optionValue, item.imicd9);
											});


											setTimeout(function () {
												selectElement.empty().append(options)
												selectElement.select2("open");
											}, 1000);

										}

									})
									.catch(error => {
										console.log(error);
									});
							}
						}
					}
				});
			});
		});
	};


	const initializeSelect10 = (selectId) => {
		const selectElement = jQuery(`${selectId}`);
		let initialized = false;

		selectElement.select2({
			placeholder: 'Please Select',
			"language": {
				"noResults": function(){
					return "No results found. Press Enter to search full database.";
				}
			},
		});

		selectElement.on('select2:open', function () {
			if (!initialized) {
				initialized = true;
			}
		});

		selectElement.on('select2:open', function () {
			const searchTermField = jQuery(this).data('select2').dropdown.$search || jQuery(this).data('select2').dropdown.$searchbox;
			searchTermField.on('input', function (event) {
				const searchTerm55 = jQuery(this).val().trim();
				localStorage.setItem("initializeSelect10", searchTerm55);
				searchTermField.on('keydown', function (event) {
					if (event.keyCode === 13) {


						const searchTerm = jQuery(this).val();
						const dropdown = jQuery(this).parent().find('.select2-results');

						if (searchTerm.length >= 3) {


							fetch('modules/authorization/prescription/Ajax.php', {
								method: 'POST',
								headers: {
									'Content-Type': 'application/json'
								},
								body: JSON.stringify({ searchTerm: searchTerm, searching: true })
							})
								.then(response => response.json())
								.then(data => {


									// selectElement.empty();

									// data.forEach((item) => {
									// 	const optionValue = `${item.imdx}  (${item.imicd9})`;
									// 	const option = new Option(optionValue, item.imicd9);
									// 	selectElement.append(option);
									// });

									// selectElement.select2("close");
									// selectElement.val(searchTerm).trigger('change');


									// ++++++++++++++++++++++++++
									if (data.length > 0) {
										const options = data.map(item => {
											const optionValue = `${item.imdx} (${item.imicd9})`;
											return new Option(optionValue, item.imicd9);
										});
										setTimeout(function () {
											// selectElement.select2("open");
											selectElement.empty().append(options)
											selectElement.select2("close").select2("open");
										}, 1000);
									}
								})
								.catch(error => {
									console.log(error);
								});
						}
						else {
							// If the search term length is less than 3 characters, simply open the dropdown
							const isDropdownOpen = selectElement.data('select2').isOpen();
							if (!isDropdownOpen) {

								const searchTerm5 = localStorage.getItem("initializeSelect10");
								fetch('modules/authorization/prescription/Ajax.php', {
									method: 'POST',
									headers: {
										'Content-Type': 'application/json'
									},
									body: JSON.stringify({ searchTerm: searchTerm5, searching: true })
								})
									.then(response => response.json())
									.then(data => {
										if (data.length > 0) {
											const options = data.map(item => {
												const optionValue = `${item.imdx} (${item.imicd9})`;
												return new Option(optionValue, item.imicd9);
											});


											setTimeout(function () {
												selectElement.empty().append(options)
												selectElement.select2("open");
											}, 1000);

										}

									})
									.catch(error => {
										console.log(error);
									});
							}
						}
					}
				});
			});
		});
	};


	const initializeSelect11 = (selectId) => {
		const selectElement = jQuery(`${selectId}`);
		let initialized = false;

		selectElement.select2({
			placeholder: 'Please Select',
			"language": {
				"noResults": function(){
					return "No results found. Press Enter to search full database.";
				}
			},
		});

		selectElement.on('select2:open', function () {
			if (!initialized) {
				initialized = true;
			}
		});

		selectElement.on('select2:open', function () {
			const searchTermField = jQuery(this).data('select2').dropdown.$search || jQuery(this).data('select2').dropdown.$searchbox;
			searchTermField.on('input', function (event) {
				const searchTerm55 = jQuery(this).val().trim();
				localStorage.setItem("initializeSelect1111", searchTerm55);
				searchTermField.on('keydown', function (event) {
					if (event.keyCode === 13) {


						const searchTerm = jQuery(this).val();
						const dropdown = jQuery(this).parent().find('.select2-results');

						if (searchTerm.length >= 3) {


							fetch('modules/authorization/prescription/Ajax.php', {
								method: 'POST',
								headers: {
									'Content-Type': 'application/json'
								},
								body: JSON.stringify({ searchTerm: searchTerm, searching: true })
							})
								.then(response => response.json())
								.then(data => {


									// selectElement.empty();

									// data.forEach((item) => {
									// 	const optionValue = `${item.imdx}  (${item.imicd9})`;
									// 	const option = new Option(optionValue, item.imicd9);
									// 	selectElement.append(option);
									// });

									// selectElement.select2("close");
									// selectElement.val(searchTerm).trigger('change');


									// ++++++++++++++++++++++++++
									if (data.length > 0) {
										const options = data.map(item => {
											const optionValue = `${item.imdx} (${item.imicd9})`;
											return new Option(optionValue, item.imicd9);
										});
										setTimeout(function () {
											// selectElement.select2("open");
											selectElement.empty().append(options)
											selectElement.select2("close").select2("open");
										}, 1000);
									}
								})
								.catch(error => {
									console.log(error);
								});
						}
						else {
							// If the search term length is less than 3 characters, simply open the dropdown
							const isDropdownOpen = selectElement.data('select2').isOpen();
							if (!isDropdownOpen) {

								const searchTerm5 = localStorage.getItem("initializeSelect1111");
								fetch('modules/authorization/prescription/Ajax.php', {
									method: 'POST',
									headers: {
										'Content-Type': 'application/json'
									},
									body: JSON.stringify({ searchTerm: searchTerm5, searching: true })
								})
									.then(response => response.json())
									.then(data => {

										if (data.length > 0) {
											const options = data.map(item => {
												const optionValue = `${item.imdx} (${item.imicd9})`;
												return new Option(optionValue, item.imicd9);
											});


											setTimeout(function () {
												selectElement.empty().append(options)
												selectElement.select2("open");
											}, 1000);

										}

									})
									.catch(error => {
										console.log(error);
									});
							}
						}
					}
				});
			});
		});
	};


	const initializeSelect12 = (selectId) => {
		const selectElement = jQuery(`${selectId}`);
		let initialized = false;

		selectElement.select2({
			placeholder: 'Please Select',
			"language": {
				"noResults": function(){
					return "No results found. Press Enter to search full database.";
				}
			},
		});

		selectElement.on('select2:open', function () {
			if (!initialized) {
				initialized = true;
			}
		});

		selectElement.on('select2:open', function () {
			const searchTermField = jQuery(this).data('select2').dropdown.$search || jQuery(this).data('select2').dropdown.$searchbox;
			searchTermField.on('input', function (event) {
				const searchTerm55 = jQuery(this).val().trim();
				localStorage.setItem("initializeSelect12", searchTerm55);
				searchTermField.on('keydown', function (event) {
					if (event.keyCode === 13) {


						const searchTerm = jQuery(this).val();
						const dropdown = jQuery(this).parent().find('.select2-results');

						if (searchTerm.length >= 3) {


							fetch('modules/authorization/prescription/Ajax.php', {
								method: 'POST',
								headers: {
									'Content-Type': 'application/json'
								},
								body: JSON.stringify({ searchTerm: searchTerm, searching: true })
							})
								.then(response => response.json())
								.then(data => {


									// selectElement.empty();

									// data.forEach((item) => {
									// 	const optionValue = `${item.imdx}  (${item.imicd9})`;
									// 	const option = new Option(optionValue, item.imicd9);
									// 	selectElement.append(option);
									// });

									// selectElement.select2("close");
									// selectElement.val(searchTerm).trigger('change');


									// ++++++++++++++++++++++++++

									if (data.length > 0) {
										const options = data.map(item => {
											const optionValue = `${item.imdx} (${item.imicd9})`;
											return new Option(optionValue, item.imicd9);
										});
										setTimeout(function () {
											// selectElement.select2("open");
											selectElement.empty().append(options)
											selectElement.select2("close").select2("open");
										}, 1000);

									}
								})
								.catch(error => {
									console.log(error);
								});
						}
						else {
							// If the search term length is less than 3 characters, simply open the dropdown
							const isDropdownOpen = selectElement.data('select2').isOpen();
							if (!isDropdownOpen) {

								const searchTerm5 = localStorage.getItem("initializeSelect12");
								fetch('modules/authorization/prescription/Ajax.php', {
									method: 'POST',
									headers: {
										'Content-Type': 'application/json'
									},
									body: JSON.stringify({ searchTerm: searchTerm5, searching: true })
								})
									.then(response => response.json())
									.then(data => {

										if (data.length > 0) {
											const options = data.map(item => {
												const optionValue = `${item.imdx} (${item.imicd9})`;
												return new Option(optionValue, item.imicd9);
											});


											setTimeout(function () {
												selectElement.empty().append(options)
												selectElement.select2("open");
											}, 1000);
										}


									})
									.catch(error => {
										console.log(error);
									});
							}
						}
					}
				});
			});
		});
	};





	jQuery(document).ready(function () {
		initializeSelect1("#cpdx1");
		// initializeSelect3("#cpdx2");
		// initializeSelect4("#cpdx3");
		// initializeSelect5("#cpdx4");

	});

	jQuery(window).on('load', function () {
		if (jQuery("#cpdx1").length == 0) {
			jQuery('#addMoreButtonDx1').show();
		} else {
			jQuery('#addMoreButtonDx1').hide();
		}
	});
</script>
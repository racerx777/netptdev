<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(16);

// require_once '../../dompdf/autoload.inc.php';

// // reference the Dompdf namespace
// use Dompdf\Dompdf;

function render($template, $values)
{
    ob_start();
    include($template);
    $ret = ob_get_contents();
    ob_end_clean();
    return $ret;
}

if (!empty($_POST['cpid']))
    $cpid = $_POST['cpid'];

if (!empty($_GET['cpid']))
    $cpid = $_GET['cpid'];

if (!empty($cpid)) {

    require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
    $dbhandle = dbconnect();


    //Update the fact that it was printed.
    if (!empty($_GET['printed'])) {
        $printedtime = date("Y-m-d H:i:s", time());
        $printeduser = getuser();
        $updatequery = "update case_prescriptions
                        set cprfastatuscode='PRT', cprfastatususer='$printeduser', cprfastatusupdated='$printedtime', 
cprfaprinteddate='$printedtime', cprfaprinteduser='$printeduser'
                        where cpid='$cpid'";

        if ($updateresult = mysqli_query($dbhandle, $updatequery)) {
            require_once('authprocessingHistory.php');
            rxAddHistory($cpid, 'Printed Request for Authorization');
        }
    }

    $query = "select *
        from case_prescriptions
        left join cases on cpcrid=crid
        left join master_clinics on cpcnum=cmcnum
        left join doctors on cpdmid=dmid
        left join doctor_locations on cpdlid=dlid
        left join patients on crpaid=paid
        where cpid='$cpid'";

    if ($result = mysqli_query($dbhandle, $query)) {
        if ($row = mysqli_fetch_assoc($result)) {

            // print_r($row);exit;

            $values = array();

            $values['patientname'] = $row['pafname'] . ' ' . $row['palname'];
            $values['dob'] = date('Y-m-d', strtotime($row['padob']));
            $values['doi'] = $row['crinjurydate'];
            $values['employer'] = $row['crempname'];
            $values['claim_number'] = $row['cricclaimnumber1'];

            $values['providername'] = $row['dmfname'] . " " . $row['dmlname'];
            $values['practicename'] = $row['dlname'];
            $values['paddress'] = $row['dladdress'];
            $values['pcitystatezip'] = $row['dlcity'] . ", " . $row['dlstate'] . " " . displayZip($row['dlzip']);
            $values['pphone'] = $row['dlphone'];
            $values['pfax'] = $row['dlfax'];
            $values['speciality'] = $row['dmdscode'];
            $values['psln'] = null;
            $values['pnpi'] = $row['dmnpi'];
            $values['city'] = $row['dlcity'];
            $values['state'] = $row['dlstate'];
            $values['zip'] = $row['dlzip'];
            $values['cpdx1'] = $row['cpdx1'];
            $values['cpdx2'] = $row['cpdx2'];
            $values['cpdx3'] = $row['cpdx3'];
            $values['cpdx4'] = $row['cpdx4'];
            $values['cpdx5'] = $row['cpdx5'];
            $values['cpdx6'] = $row['cpdx6'];
            $values['cpdx7'] = $row['cpdx7'];
            $values['cpdx8'] = $row['cpdx8'];
            $values['cpdx9'] = $row['cpdx9'];
            $values['cpdx10'] = $row['cpdx10'];
            $values['cpdx11'] = $row['cpdx11'];
            $values['cpdx12'] = $row['cpdx12'];





            $queryCpdx1 = "SELECT imdx
            FROM master_ICD9_old_july_13
            WHERE imicd9 = '{$row['cpdx1']}'";


            $queryCpdx2 = "SELECT imdx
               FROM master_ICD9_old_july_13
               WHERE imicd9 = '{$row['cpdx2']}'";

            $queryCpdx3 = "SELECT imdx
               FROM master_ICD9_old_july_13
               WHERE imicd9 = '{$row['cpdx3']}'";

            $queryCpdx4 = "SELECT imdx
               FROM master_ICD9_old_july_13
               WHERE imicd9 = '{$row['cpdx4']}'";

            $queryCpdx5 = "SELECT imdx
FROM master_ICD9
WHERE imicd9 = '{$row['cpdx5']}'";

            $queryCpdx6 = "SELECT imdx
FROM master_ICD9
WHERE imicd9 = '{$row['cpdx6']}'";

            $queryCpdx7 = "SELECT imdx
FROM master_ICD9
WHERE imicd9 = '{$row['cpdx7']}'";

            $queryCpdx8 = "SELECT imdx
FROM master_ICD9
WHERE imicd9 = '{$row['cpdx8']}'";


            $queryCpdx9 = "SELECT imdx
FROM master_ICD9
WHERE imicd9 = '{$row['cpdx9']}'";


            $queryCpdx10 = "SELECT imdx
FROM master_ICD9
WHERE imicd9 = '{$row['cpdx10']}'";


            $queryCpdx11 = "SELECT imdx
FROM master_ICD9
WHERE imicd9 = '{$row['cpdx11']}'";


            $queryCpdx12 = "SELECT imdx
FROM master_ICD9
WHERE imicd9 = '{$row['cpdx12']}'";


            if ($queryCpdx1Result = mysqli_query($dbhandle, $queryCpdx1)) {
                if ($queryCpdx1ResultRow = mysqli_fetch_assoc($queryCpdx1Result)) {
                    $values['imdxCpdx1'] = $queryCpdx1ResultRow['imdx'];
                }
            }


            if ($queryCpdx2Result = mysqli_query($dbhandle, $queryCpdx2)) {
                if ($queryCpdx2ResultRow = mysqli_fetch_assoc($queryCpdx2Result)) {
                    $values['imdxCpdx2'] = $queryCpdx2ResultRow['imdx'];
                }
            }

            if ($queryCpdx3Result = mysqli_query($dbhandle, $queryCpdx3)) {
                if ($queryCpdx3ResultRow = mysqli_fetch_assoc($queryCpdx3Result)) {
                    $values['imdxCpdx3'] = $queryCpdx3ResultRow['imdx'];
                }
            }

            if ($queryCpdx4Result = mysqli_query($dbhandle, $queryCpdx4)) {
                if ($queryCpdx4ResultRow = mysqli_fetch_assoc($queryCpdx4Result)) {
                    $values['imdxCpdx4'] = $queryCpdx4ResultRow['imdx'];
                }
            }

            if ($queryCpdx5Result = mysqli_query($dbhandle, $queryCpdx5)) {
                if ($queryCpdx5ResultRow = mysqli_fetch_assoc($queryCpdx5Result)) {
                    $values['imdxCpdx5'] = $queryCpdx5ResultRow['imdx'];
                }
            }

            if ($queryCpdx6Result = mysqli_query($dbhandle, $queryCpdx6)) {
                if ($queryCpdx6ResultRow = mysqli_fetch_assoc($queryCpdx6Result)) {
                    $values['imdxCpdx6'] = $queryCpdx6ResultRow['imdx'];
                }
            }

            if ($queryCpdx7Result = mysqli_query($dbhandle, $queryCpdx7)) {
                if ($queryCpdx7ResultRow = mysqli_fetch_assoc($queryCpdx7Result)) {
                    $values['imdxCpdx7'] = $queryCpdx7ResultRow['imdx'];
                }
            }

            if ($queryCpdx8Result = mysqli_query($dbhandle, $queryCpdx8)) {
                if ($queryCpdx8ResultRow = mysqli_fetch_assoc($queryCpdx8Result)) {
                    $values['imdxCpdx8'] = $queryCpdx8ResultRow['imdx'];
                }
            }

            if ($queryCpdx9Result = mysqli_query($dbhandle, $queryCpdx9)) {
                if ($queryCpdx9ResultRow = mysqli_fetch_assoc($queryCpdx9Result)) {
                    $values['imdxCpdx9'] = $queryCpdx9ResultRow['imdx'];
                }
            }

            if ($queryCpdx10Result = mysqli_query($dbhandle, $queryCpdx10)) {
                if ($queryCpdx10ResultRow = mysqli_fetch_assoc($queryCpdx10Result)) {
                    $values['imdxCpdx10'] = $queryCpdx10ResultRow['imdx'];
                }
            }

            if ($queryCpdx11Result = mysqli_query($dbhandle, $queryCpdx11)) {
                if ($queryCpdx11ResultRow = mysqli_fetch_assoc($queryCpdx11Result)) {
                    $values['imdxCpdx11'] = $queryCpdx11ResultRow['imdx'];
                }
            }

            if ($queryCpdx12Result = mysqli_query($dbhandle, $queryCpdx12)) {
                if ($queryCpdx12ResultRow = mysqli_fetch_assoc($queryCpdx12Result)) {
                    $values['imdxCpdx12'] = $queryCpdx12ResultRow['imdx'];
                }
            }

            
            

            $values['other'] = "";
            if ($row['cpfrequency']) {
                $values['other'] .= 'Frequency: ' . $row['cpfrequency'] . ' ';
            }
            if ($row['cpduration']) {
                $values['other'] .= 'Duration: ' . $row['cpduration'] . ' ';
            }
            //$values['other'] = 'Freq: '. $row['cpfrequency'] .' Dur: ' . $row['cpduration'];

            $icid1 = $row['cricid1'];
            $iclid1 = $row['criclid1'];
            $icaid1 = $row['cricaid1'];
            $i1query = "select *
                from insurance_companies
                left join insurance_companies_locations on icid=iclicid
                left join insurance_companies_adjusters on icid=icaicid
                where icid='$icid1' and iclid='$iclid1' and icaid='$icaid1'";


            if ($i1result = mysqli_query($dbhandle, $i1query)) {
                if ($i1row = mysqli_fetch_assoc($i1result)) {
                    $values['claimsadministrator'] = $i1row["icname"];
                    $values['claimsadjuster'] = $i1row["icafname"] . " " . $i1row['icalname'];
                    $values['caddress'] = $i1row['icladdress1'];
                    $values['ccitystatezip'] = $i1row['iclcity'] . ", " . $i1row['iclstate'] . " " . displayZip($i1row['iclzip']);
                    $values['cphone'] = $i1row['iclphone'];
                    $values['cfax'] = $i1row['iclfax'];
                    $values['ccity'] = $row['iclcity'];
                    $values['cstate'] = $row['iclstate'];
                    $values['czip'] = $row['iclzip'];
                }
            }

            $icd9array = icd9CodeOptions();
            $values['diagnosis'] .= (isset($row['cpdx1'])) ? $icd9array[$row['cpdx1']]['description'] . " " : null;
            $values['diagnosis'] .= (isset($row['cpdx2'])) ? $icd9array[$row['cpdx2']]['description'] . " " : null;
            $values['diagnosis'] .= (isset($row['cpdx3'])) ? $icd9array[$row['cpdx3']]['description'] . " " : null;
            $values['diagnosis'] .= (isset($row['cpdx4'])) ? $icd9array[$row['cpdx4']]['description'] : null;

            $values['icd'] = $row['cpdx1'] . " " . $row['cpdx2'] . " " . $row['cpdx3'] . " " . $row['cpdx4'];

            $treatmenttypeoptions = therapyTypeOptions();
            $values['procedurerequested'] = (isset($treatmenttypeoptions[$row['cpttmcode']])) ? $treatmenttypeoptions[$row['cpttmcode']]['title'] :
                "";
            $values['cptcode'] = '97110, 97140, 97033, 97014, 90901, 97026, 97128, 99070';

            if (empty($row['cprfaprinteddate']))
                $rfadate = "[REQUEST DATE GOES HERE]";
            else
                $rfadate = displayDate($row['cprfaprinteddate']);

            if (empty($row['cprfaprinteduser']))
                $rfaauthorizer = "[AUTHORIZER NAME GOES HERE]";
            else
                $rfaauthorizer = getUserNameByUser($row['cprfaprinteduser']);

            if (empty($values['patientname'])) {
                $rfapatientname = "[PATIENT NAME GOES HERE]";
            } else {
                $rfapatientname = $values['patientname'];
            }

            $treatmenttypeoptions = therapyTypeOptions();
            if (empty($row['cpttmcode']))
                $rfatreatmenttype = "[PRESCRIPTION TREATMENT TYPE GOES HERE]";
            else
                $rfatreatmenttype = strtoupper($treatmenttypeoptions[$row['cpttmcode']]['title']);

            if (empty($row['cpfrequency']) || empty($row['cpduration']))
                $rfatreatmentduration = "[PRESCRIPTION FREQUENCY GOES HERE]";
            else
                $rfatreatmentduration = $row['cpfrequency'] . "x" . $row['cpduration'];

            // $fdf = render('authprocessingPrintRfaFDF.template.php', $values);

            //            var_dump($fdf);exit;

            // header('Content-type: application/pdf');
            // $fh = fopen('fdf/test.fdf', 'w');
            // fwrite($fh, $fdf);
            // fclose($fh);

            // $l = null;

            // header('Content-type: application/pdf');
            // header('Content-Disposition: attachment; filename="Download.pdf"');
            // passthru("pdftk file.pdf fill_form fdf/test.fdf output - ");
            // exit;

            //echo passthru("/usr/local/bin/pdftk rfa.pdf fill_form fdf/test.fdf output - flatten", $l); // old

            /// NEW CODE BLOCK
            include("authprocessingPrintRfaPDF.template.new.php");
            //instantiate and use the dompdf class
            //$dompdf = new Dompdf();
            //$dompdf->loadHtml(include("authprocessingPrintRfaFDF.template.new.php"));

            //(Optional) Setup the paper size and orientation
            //$dompdf->setPaper('A4', 'landscape');

            // Render the HTML as PDF
            // $dompdf->render();

            // Output the generated PDF to Browser
            //$dompdf->stream("test.pdf",array("Attachment" => false));

        }
    }
}

?>
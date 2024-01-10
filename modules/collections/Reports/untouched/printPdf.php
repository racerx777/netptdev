<?php
// set_time_limit(0);
// ini_set('max_execution_time', '0');
// ini_set('memory_limit', -1);


// require_once  '../../../../TCPDF-main/tcpdf.php';

// //include('pdftemplate.php');


// class MYPDF extends TCPDF {

//     //Page header
//     public function Header() {
//         // Logo
//         $image_file = '../../wsptn_logo_bw_outline.jpg';
//         $this->Image($image_file, 10, 10, 50, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
//         // Set font
//         $this->SetFont('helvetica', 'B', 15);
//         // Title
//         $this->Cell(0, 15, 'Untouched Accounts Report', 0, false, 'C', 0, '', 0, false, 'M', 'M');
//     }

 
// }


// $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);

// // set document information
// $pdf->SetCreator(PDF_CREATOR);
// $pdf->SetAuthor('');
// $pdf->SetTitle('Untouched Accounts Report');
// $pdf->SetSubject('Untouched Accounts Report');
// $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// // set default header data
// $pdf->SetHeaderData(PDF_HEADER_LOGO,'300', PDF_HEADER_TITLE, PDF_HEADER_STRING);

// // set header and footer fonts
// $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
// $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// // set default monospaced font
// $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// // set margins
// $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
// $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
// $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// // set auto page breaks
// $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM-15);

// // set image scale factor
// $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


// // ---------------------------------------------------------

// // set font
// $pdf->SetFont('dejavusans', '', 8);

// // add a page
// $pdf->AddPage();



require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(33);

require_once  '../../../../mpdf-development/vendor/autoload.php';

        
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->AddPage('L');
global $html,$dbhandle,$result; 

$html ="";
if(!empty($_REQUEST['days']) || $_REQUEST['days']==0)   // number of days since last touched
        $days=$_REQUEST['days']+0;
else
        $days=60;

if(!empty($days) || $days==0) {
        $today=today();
        $daysdate = date("Y-m-d", strtotime($today . " -" . $days . " day"));
        $dbfromdate=dbDate($daysdate,'Y-m-d')." 00:00:00";

        $excludedate = date("Y-m-d",strtotime($today . " -1 day"));
        $dbexcludedate=dbDate($excludedate,'Y-m-d')." 00:00:00";
}
else {
        echo "Number of Days cannot be empty/zero.";
        exit();
}
$offset = 1;

if(isset($_REQUEST['offset'])) {
        $offset = $_REQUEST['offset'];
}
if(isset($_REQUEST['limitmax'])) {
        $limitmax = $_REQUEST['limitmax'];
}

if(isset($_REQUEST['userid'])) // If selected a user translate to queue
        $userid=$_REQUEST['userid'];
else
        unset($userid);

if(isset($_REQUEST['printnotes']))
        $printnotes=$_REQUEST['printnotes'];
else
        $printnotes='none';

if(isset($_REQUEST['mintbal']))
        $mintbal=$_REQUEST['mintbal']+0;
else
        $mintbal=0;

require_once($_SERVER['DOCUMENT_ROOT'] . '/common/user.options.php');
$tdisuser=getuser();

$where=array();

// if it is not Sunni, Vidal or constance reset user id to current user id
if (!isuserlevel(34)) {
    $userid=getuserid();
}
$queue = "";
if(!empty($userid)) {
        $userinformation=getUserInformation($userid);
        $umuser=$userinformation['umuser'];
        if(isset($umuser)) {
                $queue=getUserQueueAssignment($umuser);
//              $where[]="cqauser='$umuser'";
                $where[]="cqgroup='$queue'";
        }
}



$where[]="(ca.upddate IS NULL or ca.upddate < '$dbfromdate')";
$where[]="(ca.crtdate IS NULL or ca.crtdate < '$dbexcludedate')";
//$where[]="( cqschcalldate IS NULL OR cqschcalldate < DATE_SUB( NOW( ) , INTERVAL 1 DAY ) )";
//$where[]="tbal > $mintbal and t30+t60+t90+t120>0";
//$where[]="cqauser IS NOT NULL";

if(count($where)>0)
        $wheresql = "WHERE ".implode(' AND ', $where);

$endcount = $offset + $limitmax - 1;

$select="
SELECT caid, cabnum, capnum, ca.crtdate crtdate, ca.upddate upddate, cqgroup, cqpriority, cqschcalldate, tbal, t30+t60+t90+t120 pastdue
FROM collection_accounts ca
LEFT JOIN collection_queue q ON caid = cqcaid
LEFT JOIN ( SELECT pnum, t30, t60, t90, t120, tbal FROM PTOS_Patients WHERE tbal>$mintbal and t30+t60+t90+t120>0 ) pat1 ON capnum=pnum
$wheresql ORDER BY cqgroup, cabnum, upddate, cqschcalldate, crtdate  LIMIT $limitmax OFFSET $offset";

$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<style>
        tr {
border: 1px solid #ccc;
}

</style>
</head>
<body>
<div style="float:right">

        <p>Days: '.$days.'</p>
</div>
<div align="center">

                <strong>Untouched Listing : '. $queue.' </strong>

</div><br>
<div class="clearformat" style="clear:both;">';

$html .= '<table border="1" cellpadding="2">';

$html .= '
        <tr >
                <td><strong>Queue</strong></td>
                <td><strong>#</strong></td>
                <td><strong>Bus</strong></td>
                <td><strong>Account</strong></td>
                <td><strong>Pty</strong></td>
                <td><strong>last touch Date</strong></td>
                <td><strong>next touch Date</strong></td>
                <td><strong>Create Date</strong></td>
                <td><strong>Balance</strong></td>';

if($printnotes!='none') {
                $html .= '<td><strong>Note Date</strong></td>';
                $html .= '<td><strong>User</strong></td>';
                $html .= '<td><strong>Button</strong></td>';
                $html .= '<td><strong>Note</strong></td>';
}
$html .= '</tr>';
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();
 //$mpdf->WriteHTML($html);
//$pdf->writeHTML($html, true, false, true, false, '');

//$htmlnew = $html;
$reportcount=0;
$groupsaved = 0;
$i=1;
$innercontent = "";
$groupcount=$offset-1;
if($result=mysqli_query($dbhandle,$select)) {

        while($row=mysqli_fetch_assoc($result)) {
                
                
                $reportcount++;
                if($groupsaved == $row['cqgroup']) {
                        $groupcount++;
                        $groupprint=$row['cqgroup'];
                }
                else {
                        
                        $groupprint=$row['cqgroup'];
                        $groupsaved=$row['cqgroup'];
                }

                if($bnumsaved == $row['cabnum']) {
                        $bnumcount++;
                        $bnumprint=$row['cabnum'];
                }
                else {
                        $bnumcount=1;
                        $bnumprint=$row['cabnum'];
                        $bnumsaved=$row['cabnum'];
                }

                $total=$total+$row['tbal'];
                $numrows=0;
                if($printnotes!='none') {
                        if($printnotes=='last') $limit="LIMIT 1";

                        $notes="SELECT nopnum, nobutton, nonote, crtdate, crtuser FROM notes WHERE nopnum='".$row['capnum']."' order by crtdate desc $limit ";
                        if($noteresult=mysqli_query($dbhandle,$notes))
                                $numrows=mysqli_num_rows($noteresult);
                }

        $innercontent .= '<tr>
                <td>'. $groupprint.'</td>
                <td>'.$groupcount.'</td>
                <td>'.$bnumprint.'</td>
                <td>'.$row['capnum'].'</td>
                <td>'.$row['cqpriority'].'</td>
                <td>'.displayDate($row['upddate']).'</td>
                <td>'.displayDate($row['cqschcalldate']).'</td>
                <td>'.displayDate($row['crtdate']).'</td>
                <td>'.$row['tbal'].'</td>';


                if($numrows==0) { // none
                        if($printnotes!='none') {

                        $innercontent .= '<td>'."&nbsp;".'</td>
                        <td>'. "&nbsp;".'</td>
                        <td>'. "&nbsp;".'</td>
                        <td style="border: 1px solid #ccc; font-size:4px;">'. "&nbsp;".'</td>';

                        }
                }
                else {
                        while($noterow=mysqli_fetch_assoc($noteresult)) {

                $innercontent .= '
                        <td>'. displayDate($noterow['crtdate']).'</td>
                        <td>'. $noterow['crtuser'].'</td>
                        <td>'. $noterow['nobutton'].'</td>
                        <td>'. $noterow['nonote'].'</td>
                ';

                        }
                }
                $innercontent .= '</tr>';

               //  $mpdf->WriteHTML($innercontent);
                //$html = "";
                $i++;
        }
        //$pdf->writeHTML($innercontent, true, false, true, false, '');
        mysqli_close($dbhandle);

        $html .= $innercontent;

        $html .= '<tr>
                <td colspan="8" width="200">'.$reportcount.' Total Untouched</td>
                <td>'. displayCurrency($total,',','$').'</td>';


        if($printnotes!='none')
                $html .= '<td colspan="4">&nbsp;</td>';


$html .= '</tr></table>';

}
// $pdf->writeHTML($html, true, false, true, false, '');
// //$pdf->writeHTML($html, true, false, true, false, '');
// $pdf->Output("untouchedaccountsreport.pdf",'D');

    $mpdf->WriteHTML($html);
       
    $mpdf->Output("untouchedaccountsreport-".$offset."-".$endcount.".pdf",'D');

?>
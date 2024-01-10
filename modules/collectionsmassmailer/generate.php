<?php

$myServer = '127.0.0.1';
$myUser = "wsptn_netpt";
$myPass = "OsmWoL?cUt~aco89";
$myDB = "wsptn_netpt";

$dbhandle = @mysql_connect($myServer, $myUser, $myPass) or die("Couldn't connect to SQL Server on $myServer") or die("Error connecting to database. ".mysqli_error($dbhandle));
$dbselect = @mysql_select_db($myDB, $dbhandle) or die("Error selecting database. ".mysqli_error($dbhandle));

require_once '../../classes/tcpdf/tcpdf.4.php';

$pid = getmypid();

$sql = "UPDATE mass_mailing SET in_process=$pid WHERE in_process=0 LIMIT 1";
$result = mysqli_query($dbhandle,$sql);
$sql = "SELECT m.*, mu.umname
        FROM mass_mailing m
        LEFT JOIN master_user mu ON m.user = mu.umuser
        WHERE in_process = $pid and done = 0";
$result = mysqli_query($dbhandle,$sql);
echo mysqli_error($dbhandle);
$mailingRow = mysqli_fetch_assoc($result);
var_dump($mailingRow);
if ($mailingRow) {

    $acctype = $mailingRow['acctype'];
    $user = $mailingRow['user'];
    $runDate = $mailingRow['run_date'];

    $where = array();
    $where[] = "acctype='$acctype'";
    $where[] = "tbal > 0";

    if ($mailingRow['lower_limit']) {
        $where[] = "tbal >=" . $mailingRow['lower_limit'];
    }

    if ($mailingRow['upper_limit']) {
        $where[] = "tbal <= " . $mailingRow['upper_limit'];
    }

    if ($mailingRow['queue']) {
        $where[] = "cqgroup = '" . $mailingRow['queue'] ."'";
    }

    $wherestr = implode(" and ", $where);

    $sql = "SELECT *
            FROM PTOS_Patients p
            LEFT JOIN collection_accounts ca on ca.capnum = p.pnum
            LEFT JOIN collection_queue cq on ca.caid = cq.cqcaid
            LEFT JOIN master_collections_queue_assign mcqa ON cqgroup = mcqa.cqagroup
            LEFT JOIN master_user ON cqauser = umuser
            LEFT JOIN PTOS_Insurance pi on pi.icode = ca.cainsname1 and p.bnum = pi.bnum
            WHERE $wherestr
            ORDER BY iadd3 DESC, pnum";
    print_r($sql);
    $result = mysqli_query($dbhandle,$sql);


    $pdf = new TCPDF();
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    while($row = mysqli_fetch_assoc($result)) {

        $bnum = $row['bnum'];
        $pnum = $row['pnum'];
        $today = date('m/d/Y');

        $noteSQL = " INSERT INTO notes(
                        notype,
                        noapp,
                        nobnum,
                        nopnum,
                        nobutton,
                        nonote,
                        crtdate,
                        crtuser,
                        crtprog ) VALUES (
                        'SYS',
                        'massmail',
                        '$bnum',
                        '$pnum',
                        'generate',
                        'Mass Mailer Demand Letter Generated on $today',
                        '$runDate',
                        '$user',
                        'massmail')";
        //mysqli_query($dbhandle,$noteSQL);
        echo mysqli_error($dbhandle);

        $page = render('demandLetter.template.php', $row, $mailingRow);
        $pdf->AddPage();
        $pdf->writeHTML($page);
    }

    $filename = "pdfs/demand_letter_".$mailingRow['id'].".pdf";
    $pdf->Output($filename, 'F');

    $sql = "UPDATE mass_mailing SET done=1 WHERE id = ".$mailingRow['id']." LIMIT 1";
    //$sql = "UPDATE mass_mailing SET done=0, in_process=0 WHERE id = ".$mailingRow['id']." LIMIT 1";
    $result = mysqli_query($dbhandle,$sql);
    echo mysqli_error($dbhandle);
}

function render($template, $pat, $mailing){
   ob_start();
   include($template);
   $ret = ob_get_contents();
   ob_end_clean();
   return $ret;
}

?>

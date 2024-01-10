<?php

function maybeEncodeCSVField($string) {
    if(strpos($string, ',') !== false || strpos($string, '"') !== false || strpos($string, "\n") !== false) {
        $string = '"' . str_replace('"', '""', $string) . '"';
    }
    return $string;
}

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=lien-report.csv");

require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


$cutoff = '19900701';

$threeYearsAgo = date('m/d/Y', strtotime('-3 years'));
$eighteenMonthsAgo = date('m/d/Y', strtotime('-18 months'));

$where = array();
$where[] = 'tbal > 0';
$where[] = "(ca.calienstatus = 'NON' or ca.calienstatus = '')";

if ($_GET['toDate'] && $_GET['fromDate']) {

    $toDateTimeStamp = strtotime($_GET['toDate']);
    $fromDateTimeStamp = strtotime($_GET['fromDate']);

    $toDatePreCutoff  = date('Ymd', strtotime('-3 years', $toDateTimeStamp));
    $toDatePostCutoff = date('Ymd', strtotime('-18 months', $toDateTimeStamp));

    $fromDatePreCutoff  = date('Ymd', strtotime('-3 years', $fromDateTimeStamp));
    $fromDatePostCutoff = date('Ymd', strtotime('-18 months', $fromDateTimeStamp));

    $preCutoff = "(lvisit < '$cutoff' and lvisit >= '$fromDatePreCutoff' and lvisit <= '$toDatePreCutoff')";
    $postCutoff = "(lvisit >= '$cutoff' and lvisit >= '$fromDatePostCutoff' and lvisit <= '$toDatePostCutoff')";

    $where[] = "($preCutoff or $postCutoff)";
}

if (isset($_GET['upperLimit']) && $_GET['upperLimit']) {
    $where[] = "tbal <= ".intval($_GET['upperLimit']);
}

if (isset($_GET['lowerLimit']) && $_GET['lowerLimit']) {
    $where[] = "tbal >= ".intval($_GET['lowerLimit']);
}


$sql = "SELECT p.bnum, p.pnum, p.lvisit, p.tbal, mclc.clsmdescription, ca.caliendate, ca.calienuser, ca.caaccttype, ca.cawcab1
        FROM PTOS_Patients p
        LEFT JOIN collection_accounts ca ON (cabnum = bnum COLLATE latin1_swedish_ci and capnum = pnum COLLATE latin1_swedish_ci)
        LEFT JOIN master_collections_lienstatus_codes mclc ON (ca.caaccttype = mclc.clsmaccttype COLLATE latin1_swedish_ci and ca.calienstatus = mclc.clsmcode COLLATE latin1_swedish_ci)
        WHERE ".implode(" and ", $where);
$result = mysqli_query($dbhandle,$sql);
?>
BNum, PNum, Last Visit, Dead Date, Acct Type, WCAB, Balance<?php echo "\r\n" ?>
<?php while($row = mysqli_fetch_assoc($result)) {
    $line = "";
    $line .= maybeEncodeCSVField($row['bnum']).",";
    $line .= maybeEncodeCSVField($row['pnum']).",";
    $line .= maybeEncodeCSVField(date('Y-m-d', strtotime($row['lvisit']))).",";
    if ($row['lvisit'] < $cutoff) {
        $deadDate = date('Y-m-d', strtotime('+3 years', strtotime($row['lvisit'])));
    } elseif ($row['lvisit'] >= $cutoff) {
        $deadDate = date('Y-m-d', strtotime('+18 months', strtotime($row['lvisit'])));
    }
    $line .= maybeEncodeCSVField($deadDate).",";
    $line .= maybeEncodeCSVField($row['caaccttype']).",";
    $line .= maybeEncodeCSVField($row['cawcab1']).",";
    $line .= maybeEncodeCSVField($row['tbal'])."\r\n";
    echo $line;
} ?>
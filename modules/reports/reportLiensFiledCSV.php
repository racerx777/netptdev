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


$year = date('Y') - 3;
$date = $year.date('md');

$toDateTimeStamp = strtotime($_GET['toDate']);
$toDateYear = date('Y', $toDateTimeStamp) - 3;
$toDate = $toDateYear.date('md',$toDateTimeStamp);

$fromDateTimeStamp = strtotime($_GET['fromDate']);
$fromDateYear = date('Y', $fromDateTimeStamp) - 3;
$fromDate = $fromDateYear.date('md',$fromDateTimeStamp);

$sqlUpperLimit = "";
if (isset($_GET['upperLimit']) && $_GET['upperLimit']) {
    $sqlUpperLimit = "and tbal <= ".intval($_GET['upperLimit']);
}

$sqlLowerLimit = "";
if (isset($_GET['lowerLimit']) && $_GET['lowerLimit']) {
    $sqlLowerLimit = "and tbal >= ".intval($_GET['lowerLimit']);
}

    $sql = "SELECT p.bnum, p.pnum, p.lvisit, p.tbal, mclc.clsmdescription
            FROM PTOS_Patients p
            LEFT JOIN collection_accounts ca ON (cabnum = bnum COLLATE latin1_swedish_ci and capnum = pnum COLLATE latin1_swedish_ci)
            LEFT JOIN master_collections_lienstatus_codes mclc ON (ca.caaccttype = mclc.clsmaccttype COLLATE latin1_swedish_ci and ca.calienstatus = mclc.clsmcode COLLATE latin1_swedish_ci)
            WHERE tbal > 0
            and lvisit >= '$fromDate' and lvisit <= '$toDate'
            $sqlUpperLimit
            $sqlLowerLimit
            ORDER BY lvisit";
    $result = mysqli_query($dbhandle,$sql);


?>
BNum, PNum, Last Visit, Dead Date, Status, Balance<?php echo "\r\n" ?>
<?php while($row = mysqli_fetch_assoc($result)) {
    $line = "";
    $line .= maybeEncodeCSVField($row['bnum']).",";
    $line .= maybeEncodeCSVField($row['pnum']).",";
    $line .= maybeEncodeCSVField(date('Y-m-d', strtotime($row['lvisit']))).",";
    $deadDate  = date('Y', strtotime($row['lvisit']))+3;
    $deadDate .= "-".date('m-d', strtotime($row['lvisit']));
    $line .= maybeEncodeCSVField($deadDate).",";
    $line .= maybeEncodeCSVField($row['clsmdescription']).",";
    $line .= maybeEncodeCSVField($row['tbal'])."\r\n";
    echo $line;
} ?>
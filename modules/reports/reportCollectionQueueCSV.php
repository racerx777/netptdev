<?php
function maybeEncodeCSVField($string) {
    if(strpos($string, ',') !== false || strpos($string, '"') !== false || strpos($string, "\n") !== false) {
        $string = '"' . str_replace('"', '""', $string) . '"';
    }
    return $string;
}

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=collection-queue-report.csv");

require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
require_once('reportCollectionQueueQuery.php');

if(isset($_GET['detail']) && $_GET['detail']) : ?>
User, BNum, PNum, Acctype, Date, Amount
<?php 
foreach ($queues as $user => $queueRecord) {
    foreach($queueRecord['records'] as $record) {
        $line = "";
        $line .= maybeEncodeCSVField($user).",";
        $line .= maybeEncodeCSVField($record['bnum']).",";
        $line .= maybeEncodeCSVField($record['pnum']).",";
        $line .= maybeEncodeCSVField($record['acctype']).",";
        $line .= maybeEncodeCSVField($record['date']).",";
        $line .= maybeEncodeCSVField(number_format($record['amount'], 2));
        $line .= "\r\n";
        echo $line;
    }
} 
?>
<?php elseif(isset($_GET['acctype']) && $_GET['acctype']) : ?>
Queue,User,Acctype,Count,<?php foreach($bnums as $bnum => $bnumAmount) : ?><?php echo $bnum; ?>,<?php endforeach; ?>Total Amount<?php echo "\r\n" ?>
<?php foreach ($queues as $user => $acctQueueRecord) : ?>
<?php foreach ($acctQueueRecord['acctype'] as $acctype => $queueRecord) {
    $line = "";
    $line .= maybeEncodeCSVField($queueRecord['queue']).",";
    $line .= maybeEncodeCSVField($user).",";
    $line .= maybeEncodeCSVField($acctype).",";
    $line .= maybeEncodeCSVField($queueRecord['count']).",";
    foreach ($bnums as $bnum => $bnumAmount) {
        if (isset($queueRecord['amounts'][$bnum])) {
            $line .= maybeEncodeCSVField("$".number_format($queueRecord['amounts'][$bnum], 2)).",";
        } else {
            $line .= "$0.00,";
        }
    }
    $line .= "$".number_format($queueRecord['total'],2)."\r\n";
    echo $line;
}
?>
<?php endforeach; ?>

<?php else: ?>

Queue,User,Count,<?php foreach($bnums as $bnum => $bnumAmount) : ?><?php echo $bnum; ?>,<?php endforeach; ?>Total Amount<?php echo "\r\n" ?>
<?php foreach ($queues as $user => $queueRecord) {
    $line = "";
    $line .= maybeEncodeCSVField($queueRecord['queue']).",";
    $line .= maybeEncodeCSVField($user).",";
    $line .= maybeEncodeCSVField($queueRecord['count']).",";
    foreach ($bnums as $bnum => $bnumAmount) {
        if (isset($queueRecord['amounts'][$bnum])) {
            $line .= maybeEncodeCSVField("$".number_format($queueRecord['amounts'][$bnum], 2)).",";
        } else {
            $line .= "$0.00,";
        }
    }
    $line .=  maybeEncodeCSVField("$".number_format($queueRecord['total'],2))."\r\n";
    echo $line;
}
?>

<?php endif; ?>
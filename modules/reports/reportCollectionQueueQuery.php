<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

if (isset($_REQUEST['run']) && $_REQUEST['toDate'] && $_REQUEST['fromDate']) {


    $bnums = array();
    $queues = array();

    $fromDate = date('Ymd', strtotime($_REQUEST['fromDate']));
    $toDate = date('Ymd', strtotime($_REQUEST['toDate']));

    $query="SELECT rcc.bnum, rcc.pnum, rcc.acctype, rcc.amount, rcc.date, rcc.crtuser, rcc.cqagroup, cur_user, cur_group
            FROM report_collectors_cache rcc
            WHERE date >= '$fromDate' and date <= '$toDate'
            ORDER BY crtuser, date";

    $total = 0;
    $exception_total = 0;
    $count = 0;

    $pnum = null;
    $bnum = null;
    $descrip = null;

    $result = mysqli_query($dbhandle,$query);
    if ($result) {
        while($row = mysqli_fetch_assoc($result)) {

            $amount = $row['amount'];
            $bnum = $row['bnum'];
            $pnum = $row['pnum'];
            $date = strtotime($row['date']);
            $queue = $row['cqagroup'];
            $user = $row['crtuser'];
            $acctype = $row['acctype'];

            $exception = false;
            if($row['cur_user'] != $row['crtuser'] && $row['cur_user']) {
                $exception = true;
            }

            $record = array();
            $record['amount'] = $amount;
            $record['bnum'] = $bnum;
            $record['pnum'] = $pnum;
            $record['date'] = date('Y-m-d', $date);
            $record['acctype'] = $acctype;
            $record['exception_user'] = $row['cur_user'];
            $record['exception_queue'] = $row['cur_group'];



            //preg_match('/\$([0-9]+[\.]*[0-9]*)/', $descrip, $match);
            //$dollar_amount = ltrim($match[0],'$');
            //This should be redone.
            $dollar_amount = $amount;

            //Assign the Description

            //Initialize the Queue Array
            if (!isset($queues[$user]['total'])) {
                $queues[$user]['queue'] = $queue;
                $queues[$user]['total'] = 0;
                $queues[$user]['exception'] = 0;
                $queues[$user]['count'] = 0;
                $queues[$user]['records'] = array();
                $queues[$user]['amounts'] = array();
                $queues[$user]['acctype'] = array();
            }

            if (!isset($queues[$user]['acctype'][$acctype]['total'])) {
                $queues[$user]['acctype'][$acctype]['queue'] = $queue;
                $queues[$user]['acctype'][$acctype]['total'] = 0;
                $queues[$user]['acctype'][$acctype]['exception'] = 0;
                $queues[$user]['acctype'][$acctype]['count'] = 0;
                $queues[$user]['acctype'][$acctype]['records'] = array();
                $queues[$user]['acctype'][$acctype]['amounts'] = array();
            }

            if (!isset($queues[$user]['amounts'][$bnum])) {
                $queues[$user]['amounts'][$bnum] = 0;
            }

            if (!isset($queues[$user]['acctype'][$acctype]['amounts'][$bnum])) {
                $queues[$user]['acctype'][$acctype]['amounts'][$bnum] = 0;
            }

            if (!isset($bnums[$bnum])) {
                $bnums[$bnum] = 0;
            }

            $queues[$user]['amounts'][$bnum] += $dollar_amount;
            $queues[$user]['total'] += $dollar_amount;
            $queues[$user]['exception'] += ($exception) ? $dollar_amount : 0;
            $queues[$user]['count'] += 1;
            $queues[$user]['records'][] = $record;

            //print_r($record);

            $queues[$user]['acctype'][$acctype]['amounts'][$bnum] += $dollar_amount;
            $queues[$user]['acctype'][$acctype]['total'] += $dollar_amount;
            $queues[$user]['acctype'][$acctype]['exception'] += ($exception) ? $dollar_amount : 0;
            $queues[$user]['acctype'][$acctype]['count'] += 1;
            $queues[$user]['acctype'][$acctype]['records'][] = $record;

            //print_r($record);

            $bnums[$bnum] += $dollar_amount;
            $total += $dollar_amount;
            $exception_total += ($exception) ? $dollar_amount : 0;
            $count += 1;
        }
    }
}
?>
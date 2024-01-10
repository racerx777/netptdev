<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
// require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
// securitylevel(12);
// $dbhandle = dbconnect();


?>


<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();
// print_r("working in print_r124232323232");

$id = $_POST['callid'];
$callstatus = $_POST['callStatus'];
$message = $_POST['message'];
$usernameajax = $_POST['usernameajax'];


$dt = new DateTime();
 $newdate =$dt->format('Y-m-d H:i:s');

if ($_POST['callid']) {
    // print_r("working in print_r123");
    $sql111 = "SELECT * FROM case_scheduling_history WHERE cshcrid=$id  ORDER BY cshid DESC LIMIT 1";

    $resultss = mysqli_query($dbhandle, $sql111);

    while ($row = mysqli_fetch_array($resultss)) {
        $cshcrid = $row['cshcrid'];
        $csholdcasestatuscode = $row['csholdcasestatuscode'];
        $csholdpriority = $row['csholdpriority'];
        $csholdschcalldate = $row['csholdschcalldate'];
        $csholdphone = $row['csholdphone'];
        $cshnewcasestatuscode = $row['cshnewcasestatuscode'];
        $cshnewpriority = $row['cshnewpriority'];
        $cshnewschcalldate = $row['cshnewschcalldate'];
        $cshnewphone = $row['cshnewphone'];
        $cshdata = $row['cshdata'];
        $crtdate = $row['crtdate'];
        $crtuser = $row['crtuser'];
        $crtprog = $row['crtprog'];
        $upddate = $row['upddate'];
        $upduser = $row['upduser'];
        $updprog = $row['updprog'];
    }
    // print_r("working in print_r12");
    // [0] => 1200165
    // [cshid] => 1200165
    // [1] => 263380
    // [cshcrid] => 263380
    // [2] => PEN
    // [csholdcasestatuscode] => PEN
    // [3] => 10
    // [csholdpriority] => 10
    // [4] => 2023-04-25 11:00:00
    // [csholdschcalldate] => 2023-04-25 11:00:00
    // [5] => 
    // [csholdphone] => 
    // [6] => PEN
    // [cshnewcasestatuscode] => PEN
    // [7] => 10
    // [cshnewpriority] => 10
    // [8] => 2023-04-25 15:45:00
    // [cshnewschcalldate] => 2023-04-25 15:45:00
    // [9] => 
    // [cshnewphone] => 
    // [10] => Confirm Callback Referral-PEN (626)665-5569@@2023-04-25 15:45:00
    // [cshdata] => Confirm Callback Referral-PEN (626)665-5569@@2023-04-25 15:45:00
    // [11] => 2023-04-25 11:13:22
    // [crtdate] => 2023-04-25 11:13:22
    // [12] => YesicaAlva
    // [crtuser] => YesicaAlva
    // [13] => 
    // [crtstatus] => 
    // [14] => 
    // [crtnotes] => 
    // [15] => /index.php
    // [crtprog] => /index.php
    // [16] => 2023-04-25 11:13:22
    // [upddate] => 2023-04-25 11:13:22
    // [17] => YesicaAlva
    // [upduser] => YesicaAlva
    // [18] => /index.php
    // [updprog] => /index.php
    // print_r($result);
//     $values['cshcrid'] = $selectrow['crid'];
//     $values['csholdcasestatuscode'] = $selectrow['crcasestatuscode'];
//     $values['csholdpriority'] = $selectrow['csqpriority'];
//     $values['csholdschcalldate'] = $selectrow['csqschcalldate'];
//     $values['csholdphone'] = $selectrow['csqphone'];
//     //					$values['cshnewcasestatuscode'] = "";
// //					$values['cshnewpriority'] = "";
// //					$values['cshnewschcalldate'] = "";
// //					$values['cshnewphone'] = "";
// //					$values['cshdata'] = "";
//     $values['crtdate'] = $auditfields['date'];
//     $values['crtuser'] = $auditfields['user'];
    // $values['crtprog'] = $auditfields['prog'];

    $sql = "INSERT INTO case_scheduling_history (cshcrid , csholdcasestatuscode , csholdpriority , csholdschcalldate , csholdphone , cshnewcasestatuscode , cshnewpriority , cshnewschcalldate , cshnewphone , cshdata , crtdate , crtuser , crtstatus , crtnotes, crtprog , upddate , upduser , updprog ) VALUE ('$id', '$csholdcasestatuscode' ,'$csholdpriority' ,'$csholdschcalldate' , '$csholdphone' ,'$cshnewcasestatuscode' ,'$cshnewpriority' ,'$cshnewschcalldate' ,'$cshnewphone' ,'' ,'$newdate' ,'$usernameajax' ,'$callstatus' , '$message' ,'$crtprog' ,'$upddate' ,'$upduser' ,'$updprog' )";
    print_r($sql);
    $result = mysqli_query($dbhandle, $sql);

    print_r(mysqli_error($dbhandle));

    if ($result) {
        echo true;
    } else {
        echo false;
    }
}
?>
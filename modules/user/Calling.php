<?php 

require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(90);
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

if(isset($_POST['umid']) && isset($_POST['extension'])){
    $extension = $_POST['extension'];
    $status = $_POST['status'];
    $umid=$_POST['umid'];
    $sql = "UPDATE master_user SET call_id=$status , extension=$extension  WHERE umid=$umid";
    $result = mysqli_query($dbhandle, $sql);

    if($result){
        // $myObj = new stdClass();
        // $myObj->message = "Updated successfully!";
        // $myObj->status = true;
        $myObj = array();
        $myObj['message']="Updated successfully!";
        $myObj['status']=true;

        echo json_encode($myObj);
    }
}



if($_POST['onfocus'] == 1 && isset($_POST['extension'])){
    $extension = $_POST['extension'];
    // $status = $_POST['status'];
    $umid=$_POST['umid'];
    $sql = "UPDATE master_user SET  extension=$extension  WHERE umid=$umid";
    $result = mysqli_query($dbhandle, $sql);

    if($result){
        // $myObj = new stdClass();
        // $myObj->message = "Updated successfully!";
        // $myObj->status = true;
        $myObj = array();
        $myObj['message']="Updated successfully!";
        $myObj['status']=true;

        echo json_encode($myObj);
    }
}


?>
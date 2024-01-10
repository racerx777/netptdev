<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(11);
errorclear();


$jsonData = file_get_contents('php://input');

if ($jsonData === false) {
    http_response_code(400);
    exit;
}
$data = json_decode($jsonData, true);

if ($data === null) {
    http_response_code(400);
    exit;
}
parse_str($data, $dataArray);

$crid = $dataArray['crid'];
header('Content-Type: application/json');



if (!empty($crid)) {
    // trim and strip all input
    foreach ($dataArray as $key => $val) {
        if ($key != 'button') {
            if (is_string($dataArray[$key]))
                $dataArray[$key] = stripslashes(strip_tags(trim($val)));
        }
    }

    // Validate form fields
    require_once('prescriptionValidation.php');

    if (errorcount() == 0) {
        require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
        $dbhandle = dbconnect();


        if (!empty($dataArray['cpdx1'])){
            $query1 = "SELECT imicdCount FROM  master_ICD9 WHERE imicd9 = '" . $dataArray['cpdx1'] . "'";
            $result1 = mysqli_query($dbhandle, $query1);
            if ($result1) {
                if ($row = mysqli_fetch_assoc($result1)) {
                
                    $imicdCount = $row['imicdCount'] + 1;
                    $Updatequery1 ="UPDATE master_ICD9 SET imicdCount = '" . $imicdCount . "' WHERE imicd9 = '" . $dataArray['cpdx1'] . "'";

                    $UpdaetResult1 = mysqli_query($dbhandle, $Updatequery1);
                    
                } else {
                }
            } else {
                echo "Error: " . mysqli_error($dbhandle);
            }
        }

        if (!empty($dataArray['cpdx2'])){
            $query1 = "SELECT imicdCount FROM  master_ICD9 WHERE imicd9 = '" . $dataArray['cpdx2'] . "'";
            $result1 = mysqli_query($dbhandle, $query1);
            if ($result1) {
                if ($row = mysqli_fetch_assoc($result1)) {
                

                    $imicdCount = $row['imicdCount'] + 1;
                    $Updatequery1 ="UPDATE master_ICD9 SET imicdCount = '" . $imicdCount . "' WHERE imicd9 = '" . $dataArray['cpdx2'] . "'";

                    $UpdaetResult1 = mysqli_query($dbhandle, $Updatequery1);
                    
                } else {
                }
            } else {
                echo "Error: " . mysqli_error($dbhandle);
            
        }
    }

        if (!empty($dataArray['cpdx3'])){
            $query1 = "SELECT imicdCount FROM  master_ICD9 WHERE imicd9 = '" . $dataArray['cpdx3'] . "'";
            $result1 = mysqli_query($dbhandle, $query1);
            if ($result1) {
                if ($row = mysqli_fetch_assoc($result1)) {
                

                    $imicdCount = $row['imicdCount'] + 1;
                    $Updatequery1 ="UPDATE master_ICD9 SET imicdCount = '" . $imicdCount . "' WHERE imicd9 = '" . $dataArray['cpdx3'] . "'";

                    $UpdaetResult1 = mysqli_query($dbhandle, $Updatequery1);
                    
                } else {
                }
            } else {
                echo "Error: " . mysqli_error($dbhandle);
            
        }
    }


        if (!empty($dataArray['cpdx4'])){
            $query1 = "SELECT imicdCount FROM  master_ICD9 WHERE imicd9 = '" . $dataArray['cpdx4'] . "'";
            $result1 = mysqli_query($dbhandle, $query1);
            if ($result1) {
                if ($row = mysqli_fetch_assoc($result1)) {
                

                    $imicdCount = $row['imicdCount'] + 1;
                    $Updatequery1 ="UPDATE master_ICD9 SET imicdCount = '" . $imicdCount . "' WHERE imicd9 = '" . $dataArray['cpdx4'] . "'";

                    $UpdaetResult1 = mysqli_query($dbhandle, $Updatequery1);
                    
                } else {
                }
            } else {
                echo "Error: " . mysqli_error($dbhandle);
            
        }

    }

        if (!empty($dataArray['cpdx5'])){
            $query1 = "SELECT imicdCount FROM  master_ICD9 WHERE imicd9 = '" . $dataArray['cpdx5'] . "'";
            $result1 = mysqli_query($dbhandle, $query1);
            if ($result1) {
                if ($row = mysqli_fetch_assoc($result1)) {
                

                    $imicdCount = $row['imicdCount'] + 1;
                    $Updatequery1 ="UPDATE master_ICD9 SET imicdCount = '" . $imicdCount . "' WHERE imicd9 = '" . $dataArray['cpdx5'] . "'";

                    $UpdaetResult1 = mysqli_query($dbhandle, $Updatequery1);
                    
                } else {
                }
            } else {
                echo "Error: " . mysqli_error($dbhandle);
            
        }

    }

        if (!empty($dataArray['cpdx6'])){
            $query1 = "SELECT imicdCount FROM  master_ICD9 WHERE imicd9 = '" . $dataArray['cpdx6'] . "'";
            $result1 = mysqli_query($dbhandle, $query1);
            if ($result1) {
                if ($row = mysqli_fetch_assoc($result1)) {
                

                    $imicdCount = $row['imicdCount'] + 1;
                    $Updatequery1 ="UPDATE master_ICD9 SET imicdCount = '" . $imicdCount . "' WHERE imicd9 = '" . $dataArray['cpdx6'] . "'";

                    $UpdaetResult1 = mysqli_query($dbhandle, $Updatequery1);
                    
                } else {
                }
            } else {
                echo "Error: " . mysqli_error($dbhandle);
            
        }

    }
        if (!empty($dataArray['cpdx7'])){
            $query1 = "SELECT imicdCount FROM  master_ICD9 WHERE imicd9 = '" . $dataArray['cpdx7'] . "'";
            $result1 = mysqli_query($dbhandle, $query1);
            if ($result1) {
                if ($row = mysqli_fetch_assoc($result1)) {
                

                    $imicdCount = $row['imicdCount'] + 1;
                    $Updatequery1 ="UPDATE master_ICD9 SET imicdCount = '" . $imicdCount . "' WHERE imicd9 = '" . $dataArray['cpdx7'] . "'";

                    $UpdaetResult1 = mysqli_query($dbhandle, $Updatequery1);
                    
                } else {
                }
            } else {
                echo "Error: " . mysqli_error($dbhandle);
            
        }

    }

        if (!empty($dataArray['cpdx8'])){
            $query1 = "SELECT imicdCount FROM  master_ICD9 WHERE imicd9 = '" . $dataArray['cpdx8'] . "'";
            $result1 = mysqli_query($dbhandle, $query1);
            if ($result1) {
                if ($row = mysqli_fetch_assoc($result1)) {
                

                    $imicdCount = $row['imicdCount'] + 1;
                    $Updatequery1 ="UPDATE master_ICD9 SET imicdCount = '" . $imicdCount . "' WHERE imicd9 = '" . $dataArray['cpdx8'] . "'";

                    $UpdaetResult1 = mysqli_query($dbhandle, $Updatequery1);
                    
                } else {
                }
            } else {
                echo "Error: " . mysqli_error($dbhandle);
            
        }
    }

        if (!empty($dataArray['cpdx9'])){
            $query1 = "SELECT imicdCount FROM  master_ICD9 WHERE imicd9 = '" . $dataArray['cpdx9'] . "'";
            $result1 = mysqli_query($dbhandle, $query1);
            if ($result1) {
                if ($row = mysqli_fetch_assoc($result1)) {
                

                    $imicdCount = $row['imicdCount'] + 1;
                    $Updatequery1 ="UPDATE master_ICD9 SET imicdCount = '" . $imicdCount . "' WHERE imicd9 = '" . $dataArray['cpdx9'] . "'";

                    $UpdaetResult1 = mysqli_query($dbhandle, $Updatequery1);
                    
                } else {
                }
            } else {
                echo "Error: " . mysqli_error($dbhandle);
            
        }
    }
        if (!empty($dataArray['cpdx10'])){
            $query1 = "SELECT imicdCount FROM  master_ICD9 WHERE imicd9 = '" . $dataArray['cpdx10'] . "'";
            $result1 = mysqli_query($dbhandle, $query1);
            if ($result1) {
                if ($row = mysqli_fetch_assoc($result1)) {
                

                    $imicdCount = $row['imicdCount'] + 1;
                    $Updatequery1 ="UPDATE master_ICD9 SET imicdCount = '" . $imicdCount . "' WHERE imicd9 = '" . $dataArray['cpdx10'] . "'";

                    $UpdaetResult1 = mysqli_query($dbhandle, $Updatequery1);
                    
                } else {
                }
            } else {
                echo "Error: " . mysqli_error($dbhandle);
            
        }
    }
        if (!empty($dataArray['cpdx11'])){
            $query1 = "SELECT imicdCount FROM  master_ICD9 WHERE imicd9 = '" . $dataArray['cpdx11'] . "'";
            $result1 = mysqli_query($dbhandle, $query1);
            if ($result1) {
                if ($row = mysqli_fetch_assoc($result1)) {
                

                    $imicdCount = $row['imicdCount'] + 1;
                    $Updatequery1 ="UPDATE master_ICD9 SET imicdCount = '" . $imicdCount . "' WHERE imicd9 = '" . $dataArray['cpdx11'] . "'";

                    $UpdaetResult1 = mysqli_query($dbhandle, $Updatequery1);
                    
                } else {
                }
            } else {
                echo "Error: " . mysqli_error($dbhandle);
            
        }
    }
        if (!empty($dataArray['cpdx12'])){
            $query1 = "SELECT imicdCount FROM  master_ICD9 WHERE imicd9 = '" . $dataArray['cpdx12'] . "'";
            $result1 = mysqli_query($dbhandle, $query1);
            if ($result1) {
                if ($row = mysqli_fetch_assoc($result1)) {
                

                    $imicdCount = $row['imicdCount'] + 1;
                    $Updatequery1 ="UPDATE master_ICD9 SET imicdCount = '" . $imicdCount . "' WHERE imicd9 = '" . $dataArray['cpdx12'] . "'";

                    $UpdaetResult1 = mysqli_query($dbhandle, $Updatequery1);
                    
                } else {
                }
            } else {
                echo "Error: " . mysqli_error($dbhandle);
            
        }


    }




        if (empty($dataArray['cpdate']))
            $cpdate = "NULL, ";
        else
            $cpdate = "'" . mysqli_real_escape_string($dbhandle, dbDate($dataArray['cpdate'])) . "', ";
        if (empty($dataArray['cpexpiredate']))
            $cpexpiredate = "NULL, ";
        else
            $cpexpiredate = "'" . mysqli_real_escape_string($dbhandle, dbDate($dataArray['cpexpiredate'])) . "', ";



          

        //declare the SQL statement that will query the database
        $query = "INSERT INTO case_prescriptions ";
        $query .= "(cpcrid, cpdx1, cpdx2, cpdx3, cpdx4,  cpdx5 , cpdx6 ,cpdx7 , cpdx8 , cpdx9 , cpdx10 ,cpdx11,cpdx12 , cpfrequency, cpduration, cptotalvisits, cpttmcode, cpdmid, cpdlid, cpdate, cpexpiredate, cptherap, cpcnum, cpnote, cpstatuscode, cpstatusupdated,  crtdate, crtuser, crtprog) ";
        $query .= "VALUES(";
        $query .= "'" . mysqli_real_escape_string($dbhandle, $crid) . "', ";
        $query .= "'" . mysqli_real_escape_string($dbhandle, $dataArray['cpdx1']) . "', ";
        $query .= "'" . mysqli_real_escape_string($dbhandle, $dataArray['cpdx2']) . "', ";
        $query .= "'" . mysqli_real_escape_string($dbhandle, $dataArray['cpdx3']) . "', ";
        $query .= "'" . mysqli_real_escape_string($dbhandle, $dataArray['cpdx4']) . "', ";
        $query .= "'" . mysqli_real_escape_string($dbhandle, $dataArray['cpdx5']) . "', ";
        $query .= "'" . mysqli_real_escape_string($dbhandle, $dataArray['cpdx6']) . "', ";
        $query .= "'" . mysqli_real_escape_string($dbhandle, $dataArray['cpdx7']) . "', ";
        $query .= "'" . mysqli_real_escape_string($dbhandle, $dataArray['cpdx8']) . "', ";
        $query .= "'" . mysqli_real_escape_string($dbhandle, $dataArray['cpdx9']) . "', ";
        $query .= "'" . mysqli_real_escape_string($dbhandle, $dataArray['cpdx10']) . "', ";
        $query .= "'" . mysqli_real_escape_string($dbhandle, $dataArray['cpdx11']) . "', ";
        $query .= "'" . mysqli_real_escape_string($dbhandle, $dataArray['cpdx12']) . "', ";


        $query .= "'" . mysqli_real_escape_string($dbhandle, $dataArray['cpfrequency']) . "', ";
        $query .= "'" . mysqli_real_escape_string($dbhandle, $dataArray['cpduration']) . "', ";
        $query .= "'" . mysqli_real_escape_string($dbhandle, $dataArray['cptotalvisits']) . "', ";
        $query .= "'" . mysqli_real_escape_string($dbhandle, $dataArray['cpttmcode']) . "', ";
        $query .= "'" . mysqli_real_escape_string($dbhandle, $dataArray['cpdmid']) . "', ";
        $query .= "'" . mysqli_real_escape_string($dbhandle, $dataArray['cpdlid']) . "', ";
        $query .= $cpdate;
        $query .= $cpexpiredate;
        $query .= "'" . mysqli_real_escape_string($dbhandle, $dataArray['cptherap']) . "', ";
        $query .= "'" . mysqli_real_escape_string($dbhandle, $dataArray['cpcnum']) . "', ";
        $query .= "'" . mysqli_real_escape_string($dbhandle, $dataArray['cpnote']) . "', ";
        $query .= "'NEW', ";
        $query .= "NOW(), ";
        //		$query .= "'" . mysqli_real_escape_string($dbhandle,$dataArray['cpauthstatuscode']) . "', ";
        $query .= "'" . mysqli_real_escape_string($dbhandle, $dataArray['crtdate']) . "', ";
        $query .= "'" . mysqli_real_escape_string($dbhandle, $dataArray['crtuser']) . "', ";
        $query .= "'" . mysqli_real_escape_string($dbhandle, $dataArray['crtprog']) . "' ";
        $query .= ")";
        //	notify("000",$query);
        //execute the SQL query 
        if ($result = mysqli_query($dbhandle, $query)) {
            // notify("000","Record successfully inserted.");
            // foreach($dataArray as $key=>$val) 
            // 	unset($dataArray[$key]);
            echo json_encode("changes done");
        } else {
            echo json_encode(mysqli_error($dbhandle));
        }


        // error('001', "Error Inserting Record : " . mysqli_error($dbhandle)); 	
        //close the connection
        mysqli_close($dbhandle);
    }
} else
    error('000', "Error crid : $crid");
?>
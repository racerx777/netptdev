<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(11);
errorclear();
// Read the JSON data from the request body
$jsonData = file_get_contents('php://input');

// Check if the data is valid JSON
if ($jsonData === false) {
    http_response_code(400); // Bad Request
    exit;
}

// Attempt to decode the JSON data
$data = json_decode($jsonData, true); // true makes it return an associative array

// Check if JSON decoding was successful
if ($data === null) {
    http_response_code(400); // Bad Request
    exit;
}
parse_str($data, $dataArray);

// // Now you can access the data as an associative array
// // For example, if your JSON contains a 'Dx1Value' key:
// $dx1Value = $data['Dx1Value'];

// // Process the data as needed, e.g., update a database

// // Respond with JSON data (optional)
// $response = [
//     'message' => 'Data received and processed successfully',
// 	'data' => $dx1Value,
// 	'whole' => $dataArray['cpdate']
//     // You can include additional data in the response if needed
// ];

// header('Content-Type: application/json');
// echo json_encode($response);
// die;

// $requestBody = file_get_contents('php://input');
// $requestData = json_decode($requestBody, true);

// $cpdate = $dataArray['cpdate'];
// $cpdmid = $dataArray['cpdmid'];
// $cpdlid = $dataArray['cpdlid'];
// $cpdx1 = $dataArray['cpdx1'];
// $cpdx2 = $dataArray['cpdx2'];
// $cpdx3 = $dataArray['cpdx3'];
// $cpdx4 = $dataArray['cpdx4'];
// $cpdx5 = $dataArray['cpdx5'];
// $cpdx6 = $dataArray['cpdx6'];
// $cpdx7 = $dataArray['cpdx7'];
// $cpdx8 = $dataArray['cpdx8'];
// $cpdx9 = $dataArray['cpdx9'];
// $cpdx = $dataArray['cpdx10'];
// $cpdx11 = $dataArray['cpdx11'];
// $cpdx12 = $dataArray['cpdx12'];
// $cpcnum = $dataArray['cpcnum'];
// $cpttmcode = $dataArray['cpttmcode'];
// $cptherap = $dataArray['cptherap'];
// $cpfrequency = $dataArray['cpfrequency'];
// $cpduration = $dataArray['cpduration'];
// $cptotalvisits = $dataArray['cptotalvisits'];
// $cpexpiredate = $dataArray['cpexpiredate'];
// $formLoaded = $dataArray['formLoaded'];
$cpid = $dataArray['cpid'];
// $cpcrid = $dataArray['cpcrid'];
// $data = parse_str($requestBody);

header('Content-Type: application/json');
// print_r($data);


// $updateDXcode = $requestData['updateDXcode'];
// $Dx1Value = $requestData['Dx1Value'];
// if ($updateDXcode) {
// // $cpid = $requestData['cpid'];

// 	header('Content-Type: application/json');
//     echo json_encode($requestData);
// }
if (!empty($cpid)) {
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

        $set = array();
        //declare the SQL statement that will query the database

        $cpdxArray = array();
        for ($i = 1; $i <= 12; $i++) {
            $key = 'cpdx' . $i;
            if (!empty($dataArray[$key]) && $dataArray[$key] != 'Please select') {
                $cpdxArray[] = $dataArray[$key];
            }
        }
        for ($j = 1; $j <= 12; $j++) {
            $new_key = 'cpdx' . $j;
            $search_key = intval($j) - 1;
            if (!empty($cpdxArray[$search_key]) && $cpdxArray[$search_key] != 'Please select') {
                $dataArray[$new_key] = $cpdxArray[$search_key];
            } else {
                $dataArray[$new_key] = 'Please select';
            }
        }

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

        $query = "UPDATE case_prescriptions ";

        if (isset($dataArray['cpcrid']))
            $set[] = "cpcrid ='" . mysqli_real_escape_string($dbhandle, $dataArray['cpcrid']) . "'";
        if (isset($dataArray['cpdx1']))
            $set[] .= "cpdx1 ='" . mysqli_real_escape_string($dbhandle, $dataArray['cpdx1']) . "'";
        else
            $set[] .= "cpdx1 ='" . mysqli_real_escape_string($dbhandle, 'Please select') . "'";
        if (isset($dataArray['cpdx2']))
            $set[] .= "cpdx2 ='" . mysqli_real_escape_string($dbhandle, $dataArray['cpdx2']) . "'";
        else
            $set[] .= "cpdx2 ='" . mysqli_real_escape_string($dbhandle, 'Please select') . "'";
        if (isset($dataArray['cpdx3']))
            $set[] .= "cpdx3 ='" . mysqli_real_escape_string($dbhandle, $dataArray['cpdx3']) . "'";
        else
            $set[] .= "cpdx3 ='" . mysqli_real_escape_string($dbhandle, 'Please select') . "'";
        if (isset($dataArray['cpdx4']))
            $set[] .= "cpdx4 ='" . mysqli_real_escape_string($dbhandle, $dataArray['cpdx4']) . "'";
        else
            $set[] .= "cpdx4 ='" . mysqli_real_escape_string($dbhandle, 'Please select') . "'";
        if (isset($dataArray['cpdx5']))
            $set[] .= "cpdx5 ='" . mysqli_real_escape_string($dbhandle, $dataArray['cpdx5']) . "'";
        else
            $set[] .= "cpdx5 ='" . mysqli_real_escape_string($dbhandle, 'Please select') . "'";
        if (isset($dataArray['cpdx6']))
            $set[] .= "cpdx6 ='" . mysqli_real_escape_string($dbhandle, $dataArray['cpdx6']) . "'";
        else
            $set[] .= "cpdx6 ='" . mysqli_real_escape_string($dbhandle, 'Please select') . "'";
        if (isset($dataArray['cpdx7']))
            $set[] .= "cpdx7 ='" . mysqli_real_escape_string($dbhandle, $dataArray['cpdx7']) . "'";
        else
            $set[] .= "cpdx7 ='" . mysqli_real_escape_string($dbhandle, 'Please select') . "'";
        if (isset($dataArray['cpdx8']))
            $set[] .= "cpdx8 ='" . mysqli_real_escape_string($dbhandle, $dataArray['cpdx8']) . "'";
        else
            $set[] .= "cpdx8 ='" . mysqli_real_escape_string($dbhandle, 'Please select') . "'";
        if (isset($dataArray['cpdx9']))
            $set[] .= "cpdx9 ='" . mysqli_real_escape_string($dbhandle, $dataArray['cpdx9']) . "'";
        else
            $set[] .= "cpdx9 ='" . mysqli_real_escape_string($dbhandle, 'Please select') . "'";
        if (isset($dataArray['cpdx10']))
            $set[] .= "cpdx10 ='" . mysqli_real_escape_string($dbhandle, $dataArray['cpdx10']) . "'";
        else
            $set[] .= "cpdx10 ='" . mysqli_real_escape_string($dbhandle, 'Please select') . "'";
        if (isset($dataArray['cpdx11']))
            $set[] .= "cpdx11 ='" . mysqli_real_escape_string($dbhandle, $dataArray['cpdx11']) . "'";
        else
            $set[] .= "cpdx11 ='" . mysqli_real_escape_string($dbhandle, 'Please select') . "'";
        if (isset($dataArray['cpdx12']))
            $set[] .= "cpdx12 ='" . mysqli_real_escape_string($dbhandle, $dataArray['cpdx12']) . "'";
        else
            $set[] .= "cpdx12 ='" . mysqli_real_escape_string($dbhandle, 'Please select') . "'";
        if (isset($dataArray['cpfrequency']))
            $set[] .= "cpfrequency='" . mysqli_real_escape_string($dbhandle, $dataArray['cpfrequency']) . "'";
        if (isset($dataArray['cpduration']))
            $set[] .= "cpduration='" . mysqli_real_escape_string($dbhandle, $dataArray['cpduration']) . "'";
        if (!empty($dataArray['cptotalvisits']))
            $set[] .= "cptotalvisits='" . mysqli_real_escape_string($dbhandle, $dataArray['cptotalvisits']) . "'";
        else
            $set[] .= "cptotalvisits=NULL";
        if (isset($dataArray['cpttmcode']))
            $set[] .= "cpttmcode='" . mysqli_real_escape_string($dbhandle, $dataArray['cpttmcode']) . "'";
        if (isset($dataArray['cpdmid']))
            $set[] .= "cpdmid='" . mysqli_real_escape_string($dbhandle, $dataArray['cpdmid']) . "'";
        if (isset($dataArray['cpdlid']))
            $set[] .= "cpdlid='" . mysqli_real_escape_string($dbhandle, $dataArray['cpdlid']) . "'";
        if (!empty($dataArray['cpdate']))
            $set[] .= "cpdate='" . mysqli_real_escape_string($dbhandle, dbDate($dataArray['cpdate'])) . "'";
        else
            $set[] .= "cpdate=NULL";
        if (!empty($dataArray['cpexpiredate']))
            $set[] .= "cpexpiredate='" . mysqli_real_escape_string($dbhandle, dbDate($dataArray['cpexpiredate'])) . "'";
        else
            $set[] .= "cpexpiredate=NULL";
        if (isset($dataArray['cptherap']))
            $set[] .= "cptherap='" . mysqli_real_escape_string($dbhandle, $dataArray['cptherap']) . "'";
        if (isset($dataArray['cpcnum']))
            $set[] .= "cpcnum='" . mysqli_real_escape_string($dbhandle, $dataArray['cpcnum']) . "'";
        if (isset($dataArray['cpnote']))
            $set[] .= "cpnote='" . mysqli_real_escape_string($dbhandle, $dataArray['cpnote']) . "'";
        if (count($set) > 0)
            $query .= "SET " . implode(', ', $set);
        $query .= " WHERE cpid='$cpid'";
        //dump("query",$query);
        //execute the SQL query 
        $result = mysqli_query($dbhandle, $query);
        if ($result) {
            // $_SESSION['notify'][] = "Record successfully updated.";
            // foreach ($dataArray as $key => $val)
            // 	unset($dataArray[$key]);
            echo json_encode("changes done");
            // print_r("changes done");
        } else
            echo json_encode(mysqli_error($dbhandle));

        // print_r(mysqli_error($dbhandle));

        // error('001', "Error Updating Record : " . mysqli_error($dbhandle));
        //close the connection
        mysqli_close($dbhandle);
    }
} else
    error('000', "Error cpid : $cpid");
?>
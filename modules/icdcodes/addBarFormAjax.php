<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(23);
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();
?>
<?php
// if (isset($_POST['insert'])) {
//     $result = mysqli_query($dbhandle, "INSERT INTO master_ICD9 (imdx, imicd9) VALUES ('" . $_POST['description'] . "', '" . $_POST['code'] . "')");
//     echo json_encode($result);
// }




if (isset($_POST['insert'])) {
    $code = $_POST['code'];
    $description = $_POST['description'];

    if (!empty($code)) {
        // Check if the imicd9 value already exists
        $checkQuery = "SELECT COUNT(*) AS count FROM master_ICD9 WHERE imicd9 = '$code'";
        $checkResult = mysqli_query($dbhandle, $checkQuery);

        if ($checkResult) {
            $row = mysqli_fetch_assoc($checkResult);

            if ($row['count'] == 0) {
                // Insert a new record
                $insertQuery = "INSERT INTO master_ICD9 (imdx, imicd9 ,iminactive) VALUES ('$description', '$code' ,0)";
                $insertResult = mysqli_query($dbhandle, $insertQuery);

                if ($insertResult) {
                    echo json_encode(["message" => "Record inserted successfully" , "status"=> 200]);
                } else {
                    echo json_encode(["error" => "Error: " . mysqli_error($dbhandle)]);
                }
            } else {
                echo json_encode(["message" => "Record with ICD10 Codes value '$code' already exists" ,"status"=> 400]);
            }

            // Free the result set
            mysqli_free_result($checkResult);
        } else {
            echo json_encode(["error" => "Error: " . mysqli_error($dbhandle)]);
        }
    } else {
        echo json_encode(["error" => "imicd9 is empty. Record not inserted"]);
    }
}


if (isset($_POST['update'])) {
    if (isset($_POST['description'])) {
        $description = $_POST['description'];
    }
    if (isset($_POST['usescount'])) {
        $usescount = $_POST['usescount'];
    }
    if (isset($_POST['code'])) {
        $code = $_POST['code'];
    }

    $result = mysqli_query(
        $dbhandle,
        // "UPDATE master_ICD9 SET imdx = '$description' WHERE imicd9 = '$code'"
        "UPDATE master_ICD9
        SET imdx = '$description',
            imicdCount = '$usescount'
        WHERE imicd9 = '$code' OR imicd9 = '$usescountid';"
        

    );
    echo json_encode($result);

    // $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

}

if (isset($_POST["usescountupdate"])) {

    if (isset($_POST['usescount'])) {
        $usescount = $_POST['usescount'];
    }
    if (isset($_POST['usescountid'])) {
        $usescountid = $_POST['usescountid'];
    }


    $result = mysqli_query(
        $dbhandle,
        "UPDATE master_ICD9 SET imicdCount = '$usescount' WHERE imicd9 = '$usescountid'"

    );
    echo json_encode($result);

}

if (isset($_POST['count'])) {
    $tmp = mysqli_query($dbhandle, 'SELECT COUNT(*) FROM master_ICD9');
    $total_rows = mysqli_fetch_array($tmp)[0];

    echo json_encode($total_rows);

}
if (isset($_POST['showall'])) {


    if (isset($_POST['pageno'])) {
        $pageno = $_POST['pageno'];
    } else {
        $pageno = 1;
    }

    if (isset($_POST['sorting'])) {
        $sorting = $_POST['sorting'];
    } else {
        $sorting = "DESC";
    }


    $no_of_records_per_page = 20;
    $offset = ($pageno - 1) * $no_of_records_per_page;
    $tmp = mysqli_query($dbhandle, 'SELECT COUNT(*) FROM master_ICD9');
    $total_rows = mysqli_fetch_array($tmp)[0];
    $total_pages = ceil($total_rows / $no_of_records_per_page);
    // $result = mysqli_query($dbhandle, '  SELECT * FROM master_ICD9 LIMIT ' . $offset . ',' . $no_of_records_per_page ORDER BY ussescount) ;
    $result = mysqli_query($dbhandle, 'SELECT * FROM master_ICD9 ORDER BY imicdCount DESC LIMIT ' . $offset . ',' . $no_of_records_per_page);
    $newPrepare = 'SELECT * FROM master_ICD9 ORDER BY imicdCount DESC LIMIT ' . $offset . ',' . $no_of_records_per_page;
    ?>
    <!-- <div>
<span id='records-no'> </span> ICD10 codes(s) found.
</div> -->
<!-- id="icdcode" style="position: relative; cursor: pointer;" -->
<!-- id="description" style="position: relative; cursor: pointer;" -->
    <div style="float: right;width: 50px;
    float: right;
width: 50px;
margin-bottom: 25px;
position: absolute;
right: 0px;
top: -30px;"><img src="/img/icon-xls.png" style="position: absolute;margin-left: 25px;cursor: pointer;" title="Download as Excel File"
            onClick="return printPDFXLS()"></div>
    <table border="1" id="icdcode-table" cellpadding="3" cellspacing="0" width="100%">
        <tr>
            <th  id="icdcode" style="position: relative; cursor: pointer;">ICD10 Code 

            <i class="fas fa-sort-desc" id="icd10" aria-hidden="true"></i>
            </th>
            <th  >Imdx Description
            
            </th>
            <th class="tooltip" id="usesCount">
                <i class="fas fa-question-circle"></i><span class="tooltiptext">Highest Uses Count determines which ICD
                    codes will be displayed as the 100 "Most Used Codes" (First drop down when selecting ICD codes for
                    prescriptions).</span>
                Uses Count

                <i class="fas fa-sort-desc" aria-hidden="true"></i>
            </th>


            <th>Action</th>

        </tr>
        <?php
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {


            ?>
            <tr>
                <td>
                    <?php echo $row["imicd9"]; ?>&nbsp;
                </td>
                <td>
                    <?php echo $row["imdx"]; ?>&nbsp;
                </td>
                <td>
                    <?php echo $row["imicdCount"]; ?>&nbsp;
                </td>
                <input type="hidden" name="editid" value="<?php echo $row["id"] ?>">
                <td><input type="button" name="button[<?php echo $row["imicd9"] ?>]"
                        data-description="<?php echo $row["imdx"]; ?>" data-id="<?php echo $row["imicd9"] ?>"
                        class="edit-button" data-usescount="<?php echo $row["imicdCount"] ?>" value="Edit" />
                    <input class="btn-delete" name="btn-delete" type="button" data-id="<?php echo $row["imicd9"] ?>"
                        style="margin-left:10px;" value="Delete">
<!-- 
                    <input class="edit-usescount" data-usescount="<//?php echo $row["imicdCount"] ?>"
                        data-id="<//?php echo $row["imicd9"] ?>" name="btn-delete" type="button" style="margin-left:10px;"
                        value="Update Uses Count"> -->
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
    <input type="hidden" value="<?php echo $sorting; ?>" id="sortingsss" />
    <input type="hidden" value="<?php echo $newPrepare; ?>" id="prepareQuery" />


    <ul class="pagination">

        <!-- <li class="page-item disabled"><a class="page-link" data-pageid="<//?php if ($pageno <= 1) {
            // echo '#';
        // } else {
            // echo ($pageno - 1);
        } ?>" href="#">Previous</a></li> -->
        <?php
        $outOfRange = false;
        for ($i = 1; $i <= $total_pages; $i++) {

            if ($i <= 2 || $i >= $total_pages - 2 || abs($i - $pageno) <= 2) {


                $outOfRange = false;

                if ($i == $pageno) {
                    echo '<li class="page-item"><a data-pageid="' . $i . '" href="#" class="page-link">' . $i . '</a></li>';
                } else {
                    echo '<li class="page-item 132"><a data-pageid="' . $i . '" href="#" class="page-link">' . $i . '</a></li>';
                }
            } else {

                $outOfRange = true;
            }
        }

        ?>
        <!-- 
        <li class="page-item"><a class="page-link" data-pageid="<//?php if ($pageno >= $total_pages) {
            // echo '#';
        // } else {
            // echo ($pageno + 1);
        } ?>" href="#">Next</a></li> -->
    </ul>
    <?php
}
if (isset($_POST['main'])) {
    $sql = array();
    if (!empty($_POST['ficd10codes'])) {
        $sql[] = "master_ICD9.imicd9 LIKE '%" . $_POST['ficd10codes'] . "%'";
    }
    if (!empty($_POST['icd10description'])) {
        $sql[] = "master_ICD9.imdx LIKE '%" . $_POST['icd10description'] . "%'";
    }


    if (isset($_POST['sorting'])) {
		$sorting = $_POST['sorting'];
	} else {
		$sorting = "ASC";
	}

    if (isset($_POST['allValue'])) {
        if($_POST['allValue'] == "usesCount"){
            $allValue = "imicdCount";
        }
        if($_POST['allValue'] == "icdcode"){
            $allValue = "imicd9";
        }
    
		
	} else {
        $allValue = "imicdCount";
	}
    // data {"ficd10codes":"","icd10description":"","main":"1","query":"SELECT * FROM master_ICD9 ORDER BY imicdCount DESC LIMIT 0,20","sorting":"","allValue":"usesCount"}{"r":0}
    $count = 0;
    $query = "";
    $table_arr = array();
    if (!empty($sql)) {
        foreach ($sql as $keyword) {
            if ($count == 0) {
                $query .= $keyword;
            } else {
                $query .= ' AND ' . $keyword;
            }
            $count++;
        }
   
        if (isset($_POST['query'])) {


            if ($sorting != "" && $sorting == "ASC") {
                $prepare = "SELECT * FROM master_ICD9 WHERE $query  ORDER BY $allValue ASC";
            }
            if ($sorting != "" && $sorting == "DESC") {
                $prepare = "SELECT * FROM master_ICD9 WHERE $query ORDER BY $allValue DESC";
    
            }
    
        } else {

            // echo json_encode("working in not query");
            $prepare = "SELECT * FROM master_ICD9 WHERE $query ORDER BY $allValue DESC";
    
        }
        
        // echo json_encode("working in not query ksnjkhdjkshd");

    }else{

        // if (isset($_POST['query'])) {
           
            
            if ($sorting != "" && $sorting == "ASC") {
                $prepare = "SELECT * FROM master_ICD9  ORDER BY $allValue ASC";
            }
            if ($sorting != "" && $sorting == "DESC") {

                $prepare = "SELECT * FROM master_ICD9 ORDER BY $allValue DESC";
    
            }
    
        // }

    }

    // echo json_encode($_POST);



    // $prepare = "SELECT attorney.*, attorney_firm.firm_name FROM attorney INNER JOIN attorney_firm ON attorney.id=attorney_firm.firm_id WHERE $query";

    $result = mysqli_query($dbhandle, $prepare);

    // echo json_encode($result);
    if ($result) {
        if (mysqli_num_rows($result) == 0) {
            $table_arr['numRows'] = 0;
        } else {
            while ($row = mysqli_fetch_assoc($result)) {

                $table_arr['row'][] = $row;
                $table_arr['numRows'] = count($table_arr['row']);
            }
        }
    } else {
        $table_arr['r'] = 0;
    }
    $table_arr['hiddeninput'] =  $sorting;
    echo json_encode($table_arr);
    ?>


    <?php
    
}
if (isset($_POST['checkfirm'])) {
    $key = $_POST['checkfirm'];
    $query = "SELECT attorney.*, attorney_firm.firm_name FROM attorney INNER JOIN attorney_firm ON attorney.id=attorney_firm.firm_id WHERE attorney_firm.firm_name= '$key'";
    $result = mysqli_query($dbhandle, $query);
    if (mysqli_num_rows($result) == 0) {
        $check = array();
        $check['res'] = 0;
        $check['firm'] = $key;
        echo json_encode($check);
    } else {
        echo json_encode(1);
    }
}

if (isset($_POST['attorney_form'])) {
    if (isset($_POST['add-firm'])) {
        $query = "INSERT INTO attorney_firm (firm_name) VALUES ('" . $_POST['add-firm'] . "')";
        $insertresult = mysqli_query($dbhandle, $query);
    }
    if ($insertresult) {
        $selectid = "SELECT LAST_INSERT_ID() FROM attorney_firm";
        $selectidres = mysqli_query($dbhandle, $selectid);
        if ($selectrow = mysqli_fetch_assoc($selectidres)) {
            $insertquery = "INSERT INTO attorney (firm, name_first, name_middle, name_last, address, address2, city, state, zip, phone, email) VALUES ('" . $selectrow['LAST_INSERT_ID()'] . "', '" . $_POST['add-name_first'] . "', '" . $_POST['add-name_last'] . "', '" . $_POST['add-name_last'] . "', '" . $_POST['add-address'] . "', '" . $_POST['add-address2'] . "', '" . $_POST['add-city'] . "', '" . $_POST['add-state'] . "', '" . $_POST['add-zip'] . "', '" . $_POST['add-phone'] . "', '" . $_POST['add-email'] . "')";
            $result = mysqli_query($dbhandle, $insertquery);
            echo json_encode($result);
        }
    }
}

if (isset($_POST['delete'])) {
    $id = $_POST['delete'];
    $query = "DELETE FROM master_ICD9 WHERE imicd9 = '$id'";
    $result = mysqli_query($dbhandle, $query);
    if ($result) {
        echo 'true';
    } else {
        echo 'false';
    }
    // echo json_encode($result);
    // if (mysqli_num_rows($check) == 0) {
    //     $query = "SELECT firm FROM attorney WHERE id='$id'";
    //     $querycheck = mysqli_query($dbhandle, $query);
    //     if ($querycheck) {
    //         $firmid = mysqli_fetch_assoc($querycheck);
    //         $delid = $firmid['firm'];
    //         $prepare = "SELECT * FROM master_ICD9 WHERE $query";

    //         $firmres = mysqli_query($dbhandle, $delfirm);
    //         $delatt = "DELETE FROM attorney WHERE id = '$id'";
    //         $atto = mysqli_query($dbhandle, $delatt);
    //         if ($firmres and $atto) {
    //             echo 'true';
    //         } else {
    //             echo 'false';
    //         }
    //     }

    // } else {
    //     $rec = array();
    //     $rec_val = '';
    //     $i = 0;
    //     while ($row = mysqli_fetch_assoc($check)) {
    //         $rec_val .= 'Case [' . $row['crid'] . '] ' . PHP_EOL;
    //         $i++;
    //     }
    //     $rec_text = 'It can not be deleted because there are currently [' . $i . '] cases tied to the attorney record.' . PHP_EOL;
    //     $rec_text .= $rec_val;
    //     echo $rec_text;
    // }
    die;
}
if (isset($_POST['pause'])) {
    print_r('test');
    die;
}
?>
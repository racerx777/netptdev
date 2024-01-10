<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(23);
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();
?>

<style>
.ajax-pagination{
    display: flex;
}
.pdf-ajax{
    float: right;
margin-right: 5%;
margin-bottom: 1.3%;
margin-top: -1.7%;
}

</style>


<?php
$page_no = $_POST['page_no'];
$query = $_POST['query'];
$newCount = $_POST['newCount'];




$total_records_per_page = 100;

$offset = ($page_no - 1) * $total_records_per_page;
$previous_page = $page_no - 1;
$next_page = $page_no + 1;
$adjacents = "2";

$total_records = $newCount;

$total_no_of_pages = ceil($total_records / $total_records_per_page);
$second_last = $total_no_of_pages - 1; // total pages minus 1


// print_r("****");
// print_r($total_no_of_pages);
// LIMIT $offset, $total_records_per_page"

// if (userlevel() == 10 || userlevel() == 99)
$query .= " LIMIT $offset , $total_records_per_page";
$result = mysqli_query($dbhandle, $query);

function echosearchlink($pnum)
{
    if (userlevel() >= 23 && empty($_POST['searchpnum'])) {
        echo ('<input type="submit" name="button[' . $pnum . ']" value="' . $pnum . '" />');
    } else
        echo ("$pnum");
}
?>


<div class="ajax-pagination" >
        <?php
        $recordfrom = $page_no * 100 - 100;
        $recordTo = $page_no * 100;
        if ($total_records == 1)
            echo "$total_records Treatment found.";
        else {
            if ($total_records < 100) {
                echo "$total_records Treatments(s) found.";

            } else { ?>
                    <?php
                    echo "<p>$total_records Treatments(s) found. | $recordfrom - $recordTo displayed </p>";
            }
            // echo "Over $numRowsCount Patients found. Did not display all Patients.";
        }
        ?>

                <ul class="pagination">
                    <?php // if($page_no > 1){ echo "<li><a href='?page_no=1'>First Page</a></li>"; } ?>

                    <li <?php if ($page_no <= 1) {
                        echo "class='disabled pagination-btn'";
                    } ?>>

                    </li>


                    <?php
                    if ($total_no_of_pages <= 10) {
                        for ($counter = 1; $counter <= $total_no_of_pages; $counter++) {
                            if ($counter == $page_no) {
                                echo "<li class='active pagination-btn test-class'><a>$counter</a></li>";
                            } else {
                                echo "<li class='pagination-btn'><a href='?page_no=$counter'>$counter</a></li>";
                            }
                        }
                    } elseif ($total_no_of_pages > 10) {

                        if ($page_no <= 4) {
                            for ($counter = 1; $counter < 8; $counter++) {
                                if ($counter == $page_no) {
                                    echo "<li class='active pagination-btn test-class'><a>$counter</a></li>";
                                } else {
                                    echo "<li class='pagination-btn'><a href='?page_no=$counter'>$counter</a></li>";
                                }
                            }
                            echo "<li><a>...</a></li>";
                            echo "<li class='pagination-btn'><a href='?page_no=$second_last'>$second_last</a></li>";
                            echo "<li class='pagination-btn'><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
                        } elseif ($page_no > 4 && $page_no < $total_no_of_pages - 4) {
                            echo "<li class='pagination-btn'><a href='?page_no=1'>1</a></li>";
                            echo "<li class='pagination-btn'><a href='?page_no=2'>2</a></li>";
                            echo "<li><a>...</a></li>";
                            for ($counter = $page_no - $adjacents; $counter <= $page_no + $adjacents; $counter++) {
                                if ($counter == $page_no) {
                                    echo "<li class='active pagination-btn test-class'><a>$counter</a></li>";
                                } else {
                                    echo "<li class='pagination-btn'><a href='?page_no=$counter'>$counter</a></li>";
                                }
                            }
                            echo "<li><a>...</a></li>";
                            echo "<li class='pagination-btn'><a href='?page_no=$second_last'>$second_last</a></li>";
                            echo "<li class='pagination-btn'><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
                        } else {
                            echo "<li class='pagination-btn'><a href='?page_no=1'>1</a></li>";
                            echo "<li class='pagination-btn'><a href='?page_no=2'>2</a></li>";
                            echo "<li><a>...</a></li>";

                            for ($counter = $total_no_of_pages - 6; $counter <= $total_no_of_pages; $counter++) {
                                if ($counter == $page_no) {
                                    echo "<li class='active pagination-btn test-class'><a>$counter</a></li>";
                                } else {
                                    echo "<li class='pagination-btn'><a href='?page_no=$counter'>$counter</a></li>";
                                }
                            }
                        }
                    }
                    ?>
                </ul>
                </div>

    <div class="pdf-ajax" >
        <div style="display: inline-flex;" class="tooltip">
            <img src="/img/icon-pdf.png"  onClick="return printPDFXLS(1)"
                style="position: absolute;cursor: pointer;">&nbsp;&nbsp;

            <img src="/img/icon-xls.png" id="printPDFXLSButton" style="position: absolute;margin-left: 25px;cursor: pointer;"
                onClick="return printPDFXLS()">
        </div>
    </div>

    <table border="1" cellpadding="3" cellspacing="0" width="100%">
        <tr>
            <?php
            if (userlevel() == 23) {
                ?>
                <!-- <th nowrap="nowrap"><input name="selectall" type="checkbox" value="Sel" onclick="selectallcheckboxes();" /> -->
                </th>
                <?php
            }
            ?>
            <th><input name="sort[thcnum]" type="submit" value="Clinicchange" /></th>
            <th><input name="sort[thdate]" type="submit" value="Treatment Date" /></th>
            <th><input name="sort[thpnum]" type="submit" value="Number" /></th>
            <th><input name="sort[thlname]" type="submit" value="Last Name" /></th>
            <th><input name="sort[thfname]" type="submit" value="First Name" /></th>
            <!-- <th><input name="sort[thctmcode]" type="submit" value="Case Type" /></th> -->
            <th><input name="sort[thvtmcode]" type="submit" value="Visit Type" /></th>
            <th><input name="sort[thttmcode]" type="submit" value="Treatment Type" /></th>
            <th>Procedures/Modalities</th>
            <th><input name="sort[thnadate]" type="submit" value="Next Action Date" /></th>
            <th><input name="button[]" type="submit" value="Reset Sort"></th>
        </tr>
        <?php

        $numRows = mysqli_num_rows($result);

        $billablerows = 0;
        $new_result = array();
        while ($row = mysqli_fetch_array($result)) {
            $new_result[] = $row;
        }
        $html = '';

        foreach ($new_result as $key => $row) {


            $thid = $row['thid'];
            if (isset($_POST['checkbox'][$thid]) && $_POST['checkbox'][$thid] == 1)
                $_POST['checkbox'][$thid] = 'checked';
            else
                $_POST['checkbox'][$thid] = '';
            $pnum = $row['thpnum'];
            $casetypestyle = "";
            if (!empty($pnum)) {
                if (userlevel() >= 23) {
                    $casetypequery = "
        SELECT count(*) as casetypecount FROM (
            SELECT DISTINCT thctmcode from treatment_header where thpnum='$pnum'
        ) as a";
                    if ($casetyperesult = mysqli_query($dbhandle, $casetypequery)) {
                        if ($casetyperow = mysqli_fetch_assoc($casetyperesult)) {
                            if ($casetyperow['casetypecount'] > 1)
                                $casetypestyle = 'style="background-color:#FFFF00"';
                        }
                    }
                }
            } else
                unset($pnum);
            $casetypetext = $_SESSION['casetypes'][$row['thctmcode']];
            $visittypetext = $_SESSION['visittypes'][$row['thvtmcode']];
            $treatmenttypetext = $_SESSION['treatmenttypes'][$row['thttmcode']];
            $procmodarray = array();

            $queryproc = "SELECT * FROM treatment_procedures WHERE thid='" . $row['thid'] . "' AND pmcode not in ('A','P') ORDER BY thid, pmcode";
            $resultproc = mysqli_query($dbhandle, $queryproc);

            if (!$resultproc)
                error("001", mysqli_error($dbhandle));
            else {
                $numRowsproc = mysqli_num_rows($resultproc);
                if ($numRowsproc != NULL) {
                    while ($rowproc = mysqli_fetch_array($resultproc)) {
                        if (!empty($_SESSION['individualprocedures'][$row['thttmcode']][$rowproc['pmcode']])) {
                            $str = $_SESSION['individualprocedures'][$row['thttmcode']][$rowproc['pmcode']];
                            $selectBox = " (" . $rowproc['qty'] . ")";
                            $procmodarray[] = $str . $selectBox;
                        } else {
                            $querymaster = "SELECT * FROM master_procedures WHERE pmcode='" . $rowproc['pmcode'] . "'";
                            $resultmaster = mysqli_query($dbhandle, $querymaster);

                            if (!$resultmaster) {
                                error("001", mysqli_error($dbhandle));
                            } else {
                                $numRowsmaster = mysqli_num_rows($resultmaster);
                                if ($numRowsmaster != NULL) {
                                    while ($rowmaster = mysqli_fetch_array($resultmaster)) {
                                        $str = $rowmaster['pmdescription'];
                                        $selectBox = " (" . $rowproc['qty'] . ")";
                                        $procmodarray[] = $str . $selectBox;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if (!empty($procmodarray))
                $proceduretext = "<p><span style='color:#4b7fb4'>P |</span> " . implode(', ', $procmodarray) . "</p>";
            $procmodarray = array();

            //declare the SQL statement that will query the database
            $querymodality = "SELECT * FROM treatment_modalities WHERE thid='" . $row['thid'] . "' and mmcode not in ('15P') ORDER BY thid, mmcode";
            $resultmodality = mysqli_query($dbhandle, $querymodality);
            if (!$resultmodality)
                error("001", mysqli_error($dbhandle));
            else {
                $numRowsmodality = mysqli_num_rows($resultmodality);
                if ($numRowsmodality != NULL) {
                    while ($rowmodality = mysqli_fetch_array($resultmodality)) {
                        if (!empty($_SESSION['modalities'][$row['thttmcode']][$rowmodality['mmcode']]))
                            $procmodarray[] = $_SESSION['modalities'][$row['thttmcode']][$rowmodality['mmcode']];
                        if (!empty($_SESSION['supplymodalities'][$row['thttmcode']][$rowmodality['mmcode']]))
                            $procmodarray[] = $_SESSION['supplymodalities'][$row['thttmcode']][$rowmodality['mmcode']];

                        /*$selectBox = "<select name='".$row['thid'].'_'.$str."' onchange='return addProcedureModalityQty(\"treatment_modalities\",$rowmodality[thid],this.value,\"$rowmodality[mmcode]\",\"mmcode\")'>";
                        for ($i=0; $i < 6 ; $i++) { 
                        if($rowmodality['qty'] == $i)
                        $selectBox .= "<option value='".$i."' selected>".$i."</option>";
                        else
                        $selectBox .= "<option value='".$i."'>".$i."</option>";
                        }
                        $selectBox .= "</select>";*/

                    }
                }
            }
            //$proceduretext = implode(', ', $procmodarray);
            $modulitytext = "";
            ///$modulitytext = implode(', ', $procmodarray);
            if (!empty($procmodarray))
                $modulitytext = "<p><span style='color:#4b7fb4'>M | </span>" . implode(', ', $procmodarray) . "</p>";
            ?>
            <input type="hidden" name="numrows" id="numrowswww" value="<?php echo $numRows; ?>" />
            <tr>
                <!-- <//?php
        //if (userlevel() == 23) {
            //if ($row['thsbmstatus'] >= 100 && $row['thsbmstatus'] < 500 && !empty($pnum)) {
                $billablerows++;
                ?>
                //<td><input name="checkbox[<//?php echo $thid; ?>]" type="checkbox" value="<//?php echo $row['thid']; ?>" <//?php if ($_POST['checkbox'][$row['thid']] == 1)
                          echo "checked"; ?> /></td>
            <//?php
           // } else {
                ?>
                <td>&nbsp;</td>
         //   <//?php
       //     }
     //   }
// //?> -->

                <td>
                    <?php echo $row["thcnum"]; ?>&nbsp;
                </td>
                <td <?php echo $dateStyle; ?>>
                    <?php echo date('m/d/Y', strtotime($row["thdate"])); ?>&nbsp;
                </td>
                <td>
                    <?php echosearchlink($pnum); ?>&nbsp;
                </td>
                <td>
                    <?php echo $row["thlname"]; ?>&nbsp;
                </td>
                <td>
                    <?php echo $row["thfname"]; ?>&nbsp;
                </td>
                <!-- <td <//?php echo $casetypestyle; ?>>
            <//?php echo $casetypetext; ?>&nbsp;
        </td> -->
                <td>
                    <?php echo $visittypetext; ?>&nbsp;
                </td>
                <td>
                    <?php echo $treatmenttypetext; ?> &nbsp;
                </td>
                <td>
                    <?php echo $proceduretext; ?>
                    <?php echo $modulitytext; ?>
                </td>
                <td>
                    <?php if ($row['thnadate'] <= '2012-08-01 00:00:00.000')
                        echo "(none)";
                    else
                        echo date('m/d/Y', strtotime($row["thnadate"])); ?>&nbsp;
                </td>
                <td style="min-width:100px;">
                    <?php
                    if ($row['thsbmstatus'] == 0) {
                        if (isuserlevel(20))
                            echo ('Not yet submitted by clinic.');
                        else {
                            echo ('
    <input name="button[' . $row["thid"] . ']" type="submit" value="Edit" />
    <input name="button[' . $row["thid"] . ']" type="submit" value="Delete" />
    ');
                        }
                    }
                    if ($row['thsbmstatus'] > 0) {
                        if (isuserlevel(99)) {
                            echo ('<input name="button[' . $row["thid"] . ']" type="submit" value="Edit" />');
                            echo ('<input name="button[' . $row["thid"] . ']" type="submit" value="To UR" />');
                            echo ('<input name="button[' . $row["thid"] . ']" type="submit" value="To Patient Entry" />');
                            echo ('<input name="button[' . $row["thid"] . ']" type="submit" value="Patient Entered" />');
                            echo ('<input name="button[' . $row["thid"] . ']" type="submit" value="To Billing Entry" />');
                            echo ('<input name="button[' . $row["thid"] . ']" type="submit" value="Billing Entered" />');
                            echo ('<input name="button[' . $row["thid"] . ']" type="submit" value="Make Inactive" />');
                            echo ('<input name="button[' . $row["thid"] . ']" type="submit" value="Make Active" />');
                        } else {
                            if (isuserlevel(20)) {
                                if (($row['thsbmstatus'] >= 100 && $row['thsbmstatus'] <= 199) || $row['thsbmstatus'] == 510) {
                                    if (userlevel() == 23) {
                                        echo ('<input name="button[' . $row["thid"] . ']" type="submit" value="Edit" />');
                                        echo ('<input name="button[' . $row["thid"] . ']" type="submit" value="Make Inactive" />');
                                        echo ('<input name="button[' . $row["thid"] . ']" type="submit" value="To Patient Entry" />');
                                        echo ('<input name="button[' . $row["thid"] . ']" type="submit" value="To Billing Entry" />');
                                    }
                                    if ($row['thsbmstatus'] == 100)
                                        echo ("Treatment&nbsp;is&nbsp;in&nbsp;UR.<br>");
                                    if ($row['thsbmstatus'] == 150)
                                        echo ("Treatment&nbsp;is&nbsp;in&nbsp;UR&nbsp;and&nbsp;Patient&nbsp;has&nbsp;been&nbsp;entered.<br>");
                                }
                                if ($row['thsbmstatus'] == 300) {
                                    if (userlevel() == 21) {
                                        echo ('<input name="button[' . $row["thid"] . ']" type="submit" value="To UR" />');
                                        echo ('<input name="button[' . $row["thid"] . ']" type="submit" value="Patient Entered" />');
                                    }
                                    echo ("Treatment&nbsp;is&nbsp;in&nbsp;patient&nbsp;entry.<br>");
                                }
                                if ($row['thsbmstatus'] == 500) {
                                    if (userlevel() == 22) {
                                        echo ('<input name="button[' . $row["thid"] . ']" type="submit" value="To UR" />');
                                        echo ('<input name="button[' . $row["thid"] . ']" type="submit" value="Billing Entered" />');
                                    }
                                    echo ("Treatment&nbsp;is&nbsp;in&nbsp;billing.<br>");
                                }
                                if ($row['thsbmstatus'] == 700) {
                                    if (userlevel() == 23) {
                                        echo ('<input name="button[' . $row["thid"] . ']" type="submit" value="Edit" />');
                                        echo ('<input name="button[' . $row["thid"] . ']" type="submit" value="Make Inactive" />');
                                    }
                                    echo ("Treatment&nbsp;has&nbsp;been&nbsp;billed.<br>");
                                }
                                if ($row['thsbmstatus'] == 710) {
                                    if (userlevel() == 23) {
                                        echo ('<input name="button[' . $row["thid"] . ']" type="submit" value="Rollback Billing" />');
                                        echo ('<input name="button[' . $row["thid"] . ']" type="submit" value="Make Inactive" />');
                                    }
                                    echo ("Treatment&nbsp;has&nbsp;been&nbsp;auto-billed.<br>");
                                }
                                if ($row['thsbmstatus'] == 800) {
                                    if (userlevel() == 23) {
                                        echo ('<input name="button[' . $row["thid"] . ']" type="submit" value="Edit" />');
                                        echo ('<input name="button[' . $row["thid"] . ']" type="submit" value="Make Inactive" />');
                                    }
                                    echo ("Treatment&nbsp;is&nbsp;completed.<br>");
                                }
                                if ($row['thsbmstatus'] == 900) {
                                    if (userlevel() == 23) {
                                        echo ('<input name="button[' . $row["thid"] . ']" type="submit" value="Edit" />');
                                        echo ('<input name="button[' . $row["thid"] . ']" type="submit" value="Make Active" />');
                                    }
                                    echo ('<div style="background-color:yellow";>Treatment&nbsp;is&nbsp;cancelled/inactive.</div>');
                                }
                            } else {
                                if ($row['thsbmstatus'] >= 900)
                                    echo ("Treatment&nbsp;is&nbsp;cancelled/inactive.<br>");
                            }
                        }
                        echo ("Submitted&nbsp;on&nbsp;" . date('m/d/Y', strtotime($row['thsbmdate'])));
                    }
                    ?>
                </td>
            </tr>
            <?php
            // }
//gautam sinha
        }
        ?>
    </table>

    <div style="margin:10px;">
        <?php
        if (userlevel() == 23 && !empty($billablerows)) {
            ?>
            <div style="float:left">
                <input name="button[]" type="submit" value="Selected To Billing Entry">
            </div>
            <?php
        }
        if ($_REQUEST['searchfunction'] == 'Search') {
            $onclick = "window.close()";
            $title = "Close";
        } else {
            $onclick = "window.print();";
            $title = "Print Page";
        }
        ?>

        <div style="float:right; margin-right:3%">
            <input name="print" type="button" value="<?php echo $title; ?>"
                onclick="<?php echo $onclick; ?>">&nbsp;&nbsp;

        </div><br>





        <div>
            <div class="elementssss">
                <strong id="numrowstodisplaywww">Page
                    <?php echo $page_no ?>
                </strong>
            </div>
            <div class="elementssss">
                <strong>
                    <?php echo "of " . $total_no_of_pages; ?>
                </strong>
            </div>
        </div>





        <ul class="pagination">
            <?php // if($page_no > 1){ echo "<li><a href='?page_no=1'>First Page</a></li>"; } ?>

            <li <?php if ($page_no <= 1) {
                echo "class='disabled pagination-btn'";
            } ?>>

            </li>

            <?php
            if ($total_no_of_pages <= 10) {
                for ($counter = 1; $counter <= $total_no_of_pages; $counter++) {
                    if ($counter == $page_no) {
                        echo "<li class='active pagination-btn test-class'><a>$counter</a></li>";
                    } else {
                        echo "<li class='pagination-btn'><a href='?page_no=$counter'>$counter</a></li>";
                    }
                }
            } elseif ($total_no_of_pages > 10) {

                if ($page_no <= 4) {
                    for ($counter = 1; $counter < 8; $counter++) {
                        if ($counter == $page_no) {
                            echo "<li class='active pagination-btn test-class'><a>$counter</a></li>";
                        } else {
                            echo "<li class='pagination-btn'><a href='?page_no=$counter'>$counter</a></li>";
                        }
                    }
                    echo "<li><a>...</a></li>";
                    echo "<li class='pagination-btn'><a href='?page_no=$second_last'>$second_last</a></li>";
                    echo "<li class='pagination-btn'><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
                } elseif ($page_no > 4 && $page_no < $total_no_of_pages - 4) {
                    echo "<li class='pagination-btn'><a href='?page_no=1'>1</a></li>";
                    echo "<li class='pagination-btn'><a href='?page_no=2'>2</a></li>";
                    echo "<li><a>...</a></li>";
                    for ($counter = $page_no - $adjacents; $counter <= $page_no + $adjacents; $counter++) {
                        if ($counter == $page_no) {
                            echo "<li class='active pagination-btn test-class'><a>$counter</a></li>";
                        } else {
                            echo "<li class='pagination-btn'><a href='?page_no=$counter'>$counter</a></li>";
                        }
                    }
                    echo "<li><a>...</a></li>";
                    echo "<li class='pagination-btn'><a href='?page_no=$second_last'>$second_last</a></li>";
                    echo "<li class='pagination-btn'><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
                } else {
                    echo "<li class='pagination-btn'><a href='?page_no=1'>1</a></li>";
                    echo "<li class='pagination-btn'><a href='?page_no=2'>2</a></li>";
                    echo "<li><a>...</a></li>";

                    for ($counter = $total_no_of_pages - 6; $counter <= $total_no_of_pages; $counter++) {
                        if ($counter == $page_no) {
                            echo "<li class='active pagination-btn test-class'><a>$counter</a></li>";
                        } else {
                            echo "<li class='pagination-btn'><a href='?page_no=$counter'>$counter</a></li>";
                        }
                    }
                }
            }
            ?>

        </ul>
        <div>
            <input type="hidden" value="<?php echo $total_records; ?>" id="total_treatment_row" />
            <div class="boldLarger elementssss" id="numrowstodisplay">Found
                <?php echo number_format($newCount); ?>
            </div>
            <div class="boldLarger  elementssss" style="clear:both">
                <?php
                //close the connection
                mysqli_close($dbhandle);
                // 	Select unposted records for current clinic
                ?>
                <?php echo $_SESSION['workingDate']; ?> records on afterajax hit
                <?php echo date('m/d/Y H:i:s'); ?>.
            </div>
        </div>
        </fieldset>
    </div>
</div>
<style>
    .test-class a {
        color: #fff !important;
        background-color: #337ab7 !important;
        border-color: #337ab7 !important;
    }
</style>
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
securitylevel(12);
$dbhandle = dbconnect();
?>

<?php




// $searchSaved = getformvars($thisapplication, $thisform);


$page_no = $_POST['page_no'];
$newNumRows = $_POST['newNumRows'];
$query = $_POST['query'];


$total_records_per_page = 50;
$offset = ($page_no - 1) * $total_records_per_page;
$previous_page = $page_no - 1;
$next_page = $page_no + 1;
$adjacents = "2";

// sort is an array of the sort fields and properties "field"=>array("title", "collation")
// $sortSaved = getformvars($thisapplication, $thisform . 'results');




//dump("seRCHSAVED", $searchSaved);
// $where[] = "crcnum IN " . getUserClinicsList();

// if(userlevel()!=23)
// 	$where[]="
// 	crcasestatuscode in ('ACT','SCH') and (
// 	   lvisit between DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND DATE_ADD(CURDATE(), INTERVAL 7 DAY) OR 
// 	   crapptdate between DATE_SUB(CURDATE(), INTERVAL 5 DAY) AND DATE_ADD(CURDATE(), INTERVAL 5 DAY)
// 	) ";




$total_no_of_pages = ceil($newNumRows / $total_records_per_page);
$second_last = $total_no_of_pages - 1; // total pages minus 1



// $newQuery = $query;


$query .= " LIMIT $offset, $total_records_per_page";


//dump("query",$query);
if ($result = mysqli_query($dbhandle, $query)) {
    $numRows = mysqli_num_rows($result);



    ?>
    <!-- <div class="containedBox"> -->
    <fieldset>
        <legend style="font-size:large;">
            Search Patient Results
            <?php echo $sortvartitles; ?>
            <form method="post" name="searchReset">
                <input name="sort[RESETSORT]" type="submit" value="Reset Sort">
            </form>
        </legend>
        <?php
        if ($numRows > 0) {
            if ($numRows == 1)
                echo "$numRows patient found.";
            else {
                // if ($numRows < 100)
                //     echo "$numRows patients found.";
                // else
                //     echo "Over $numRows patients found. Did not display all patients.";
                echo "$newNumRows patients found.";
            }
            ?>
            <form method="post" name="searchResults">
                <table border="1" cellpadding="3" cellspacing="0">
                    <tr>
                        <th colspan="2"><input name="sort[crapptdate]" type="submit" value="Appt" /></th>
                        <th><input name="sort[palname]" type="submit" value="Last Name" /></th>
                        <th><input name="sort[pafname]" type="submit" value="First Name" /></th>
                        <th><input name="sort[padob]" type="submit" value="DOB" /></th>
                        <th><input name="sort[passn]" type="submit" value="SSN" /></th>
                        <th><input name="sort[crinjurydate]" type="submit" value="DOI" /></th>
                        <?php if (userlevel() == 23) {
                            ?>
                            <th><input name="sort[crcnum]" type="submit" value="Clinic" /></th>
                            <th><input name="sort[crpnum]" type="submit" value="Patient" /></th>
                            <th><input name="sort[crcasestatuscode]" type="submit" value="Case Status" /></th>
                            <th><input name="sort[crcasetypecode]" type="submit" value="Case Type" /></th>
                            <th><input name="sort[crtherapytypecode]" type="submit" value="Therapy Type" /></th>
                            <th><input name="sort[cnum]" type="submit" value="PTOS Clinic" /></th>
                            <th><input name="sort[pnum]" type="submit" value="PTOS Patient" /></th>
                            <th><input name="sort[lname]" type="submit" value="PTOS Last Name" /></th>
                            <th><input name="sort[fname]" type="submit" value="PTOS First Name" /></th>
                            <th><input name="sort[fvisit]" type="submit" value="PTOS First Visit" /></th>
                            <th><input name="sort[lvisit]" type="submit" value="PTOS Last Visit" /></th>
                            <th><input name="sort[acctype]" type="submit" value="PTOS Account Type" /></th>
                        <?php } ?>
                        <th>Print Patient Paperwork</th>
                    </tr>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        $casetypecodes = caseTypeOptions();
                        $thiscasetype = $casetypecodes[$row['crcasetypecode']]["title"];
                        $thisapptdate = displayDate($row["crapptdate"]);
                        $today = displayDate($row["today"]);
                        if ($thisapptdate == $today)
                            $rowstyle = ' style="background-color:#00FF00;"';
                        else
                            $rowstyle = "";
                        $thisappttime = displayTime($row["crapptdate"]);

                        if ($row['crinjurydate'] == '1969-12-31 15:59:59')
                            $injurydate = '';
                        else
                            $injurydate = $row['crinjurydate'];

                        ?>
                        <tr<?php echo $rowstyle; ?>>
                            <td>
                                <?php echo $thisapptdate; ?>&nbsp;
                            </td>
                            <td align="right">
                                <?php echo $thisappttime; ?>&nbsp;
                            </td>
                            <td>
                                <?php echo $row["palname"]; ?>&nbsp;
                            </td>
                            <td>
                                <?php echo $row["pafname"]; ?>&nbsp;
                            </td>
                            <td>
                                <?php echo displayDate($row["padob"]); ?>&nbsp;
                            </td>
                            <td>
                                <?php echo displaySsn($row["passn"]); ?>&nbsp;
                            </td>
                            <td>
                                <?php echo displayDate($injurydate); ?>&nbsp;
                            </td>
                            <?php if (userlevel() == 23) {
                                ?>
                                <td>
                                    <?php echo $row['crcnum']; ?>&nbsp;
                                </td>
                                <td>
                                    <?php echo $row['crpnum']; ?>&nbsp;
                                </td>
                                <td>
                                    <?php echo $row['crcasestatuscode']; ?>&nbsp;
                                </td>
                                <td>
                                    <?php echo $thiscasetype; ?>&nbsp;
                                </td>
                                <td>
                                    <?php echo $row["crtherapytypecode"]; ?>&nbsp;
                                </td>
                                <td>
                                    <?php echo $row['cnum']; ?>&nbsp;
                                </td>
                                <td>
                                    <?php echo $row['pnum']; ?>&nbsp;
                                </td>
                                <td>
                                    <?php echo $row['lname']; ?>&nbsp;
                                </td>
                                <td>
                                    <?php echo $row['fname']; ?>&nbsp;
                                </td>
                                <td>
                                    <?php echo displayDate($row['fvisit']); ?>&nbsp;
                                </td>
                                <td>
                                    <?php echo displayDate($row['lvisit']); ?>&nbsp;
                                </td>
                                <td>
                                    <?php echo $row['acctype']; ?>&nbsp;
                                </td>
                            <?php } ?>
                            <?php if ($row['crreadmit'] == '0') {
                                if ($thiscasetype == 'WC') { ?>

                                    <td>
                                        <!-- 			<input name="printEnglish" type="button" value="Print <?php echo $thiscasetype; ?> English" onclick="window.open('modules/patient/patientPrintForms.php?id=<?php echo $row['crid'] ?>&lang=en','printEnglish')" />
                                                                                                                <input name="printSpanish" type="button" value="Print <?php echo $thiscasetype; ?> Spanish" onclick="window.open('modules/patient/patientPrintForms.php?id=<?php echo $row['crid'] ?>&lang=sp','printSpanish')" /> -->
                                        <input name="printEnglish" type="button" value="Print <?php echo $thiscasetype; ?> English"
                                            onclick="return printPDF(<?php echo $row['crid'] ?>,'en')" />

                                        <input name="printSpanish" type="button" value="Print <?php echo $thiscasetype; ?> Spanish"
                                            onclick="return printPDF(<?php echo $row['crid'] ?>,'sp')" />
                                    </td>


                                <?php
                                } else { ?>
                                    <td>
                                        <!-- 			<input name="printEnglish" type="button" value="Print <?php echo $thiscasetype; ?> English" onclick="window.open('modules/patient/patientPrintForms.php?id=<?php echo $row['crid'] ?>&lang=en','printEnglish')" />
                                                                                                                <input name="printSpanish" type="button" value="Print <?php echo $thiscasetype; ?> Spanish" onclick="window.open('modules/patient/patientPrintForms.php?id=<?php echo $row['crid'] ?>&lang=sp','printSpanish')" /> -->
                                        <input name="printEnglish" type="button" value="Print <?php echo $thiscasetype; ?> English"
                                            onclick="return printPDF(<?php echo $row['crid'] ?>,'en')" />

                                        <input name="printSpanish" type="button" value="Print <?php echo $thiscasetype; ?> Spanish"
                                            onclick="return printPDF(<?php echo $row['crid'] ?>,'sp')" />
                                    </td>
                                <?php
                                }
                            } else { ?>
                                <td>
                                    <!--            	<input name="button[<?php echo $row["crid"] ?>]" type="submit" value="Print RA English" onclick="window.open('modules/patient/patientPrintForms.php?id=<?php echo $row['crid'] ?>&lang=en','printEnglish')" />
                                                                                                <input name="button[<?php echo $row["crid"] ?>]" type="submit" value="Print RA Spanish"  onclick="window.open('modules/patient/patientPrintForms.php?id=<?php echo $row['crid'] ?>&lang=sp','printSpanish')" /> -->
                                    <input name="printEnglish" type="button" value="Print RA English"
                                        onclick="return printPDF(<?php echo $row['crid'] ?>,'en')" />

                                    <input name="printSpanish" type="button" value="Print RA Spanish"
                                        onclick="return printPDF(<?php echo $row['crid'] ?>,'sp')" />
                                </td>
                            <?php } ?>
                            </tr>
                        <?php
                    }
                    ?>
                        <tr>
                            <?php
                            if (userlevel() == 23)
                                echo ('<td colspan="19">Walk In Patient</td>');
                            else
                                echo ('<td colspan="7">Walk In Patient</td>');
                            ?>
                            <td><input name="button[0]" type="button" value="English (Walkin)"
                                    onclick="window.open('modules/patient/patientPrintForms.php?id=&lang=en&id=walk-in','printEnglish')" />
                                <input name="button[0]" type="button" value="Spanish (Walkin)"
                                    onclick="window.open('modules/patient/patientPrintForms.php?id=&lang=sp&id=walk-in','printSpanish')" />
                            </td>
                        </tr>
                </table>




            </form>
        <?php
            // foreach ($_POST as $key => $val)
            //     unset($_POST[$key]);
        } else
            echo ('No patients found.');
} else
    error('001', "QUERY:" . $query . ":" . mysqli_error($dbhandle));
//close the connection
mysqli_close($dbhandle);
?>
</fieldset>
<div style='padding: 10px 20px 0px; border-top: dotted 1px #CCC;'>
<strong>Page <?php echo $page_no." of ".$total_no_of_pages; ?></strong>
</div>
<ul class="pagination">
    <?php // if($page_no > 1){ echo "<li><a href='?page_no=1'>First Page</a></li>"; } ?>


    <?php
    if ($total_no_of_pages <= 10) {
        for ($counter = 1; $counter <= $total_no_of_pages; $counter++) {
            if ($counter == $page_no) {
                echo "<li class='active test-class pagination-btn'><a>$counter</a></li>";
            } else {
                echo "<li class='pagination-btn'><a href='?page_no=$counter'>$counter</a></li>";
            }
        }
    } elseif ($total_no_of_pages > 10) {

        if ($page_no <= 4) {
            for ($counter = 1; $counter < 8; $counter++) {
                if ($counter == $page_no) {
                    echo "<li class='active test-class'><a>$counter</a></li>";
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
                    echo "<li class='active test-class pagination-btn'><a>$counter</a></li>";
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
                    echo "<li class=' active pagination-btn test-class'><a>$counter</a></li>";
                } else {
                    echo "<li class='pagination-btn'><a href='?page_no=$counter'>$counter</a></li>";
                }
            }
        }
    }
    ?>

</ul>


<!-- </div> -->


<style>
    .test-class a{
        color:#fff !important;
        background-color: #337ab7 !important;
        border-color: #337ab7 !important;
    }
</style>
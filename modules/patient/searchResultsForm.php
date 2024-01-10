<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');

securitylevel(12);

$searchSaved = getformvars($thisapplication, $thisform);


if (!empty($_POST['buttonSetSearch']) || !empty($searchSaved) || !empty($_POST['sort'])) {
	$searchvars = array(
		"crapptdate" => array("title" => "Appt Date", "type" => "text", "dbformat" => "datetime", "dblength" => "30", "displayformat" => "date", "displaylength" => "30", "test" => "EQUAL"),
		"palname" => array("title" => "Last Name", "type" => "text", "dbformat" => "varchar", "dblength" => "30", "displayformat" => "name", "displaylength" => "30", "test" => "LIKE"),
		"pafname" => array("title" => "First Name", "type" => "text", "dbformat" => "varchar", "dblength" => "30", "displayformat" => "name", "displaylength" => "30", "test" => "LIKE"),
		"padob" => array("title" => "Birth Date", "type" => "text", "dbformat" => "date", "dblength" => "8", "displayformat" => "date", "displaylength" => "10", "test" => "EQUAL"),
		"passn" => array("title" => "Social Security Number", "type" => "text", "dbformat" => "ssn", "dblength" => "9", "displayformat" => "ssn", "displaylength" => "11", "test" => "EQUAL"),
		"crcasetypecode" => array("title" => "Type", "type" => "text", "dbformat" => "varchar", "dblength" => "3", "displayformat" => "name", "displaylength" => "3", "test" => "EQUAL")
	);


	if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
		$page_no = $_GET['page_no'];
	} else {
		$page_no = 1;
	}

	$total_records_per_page = 100;
	$offset = ($page_no - 1) * $total_records_per_page;
	$previous_page = $page_no - 1;
	$next_page = $page_no + 1;
	$adjacents = "2";

	// sort is an array of the sort fields and properties "field"=>array("title", "collation")
	$sortSaved = getformvars($thisapplication, $thisform . 'results');
	// if Sort button pressed set sort values
	if (count($_POST['sort']) > 0) {
		// If Reset Sort Pressed then clear saved values
		if (!empty($_POST['sort']['RESETSORT'])) {
			clearformvars($thisapplication, $thisform . 'results');
			unset($sortSaved);
		} else {
			// determine sort field name from key
			$sortfield = key($_POST['sort']);
			// if that key exists in the sort then toggle collation
			if (array_key_exists($sortfield, $sortSaved)) {
				$collation = $sortSaved["$sortfield"]['collation'];
				if ($collation == 'desc')
					$sortSaved["$sortfield"]["collation"] = '';
				else
					$sortSaved["$sortfield"]["collation"] = 'desc';
			} else
				$sortSaved["$sortfield"] = $searchvars["$sortfield"];
			setformvars($thisapplication, $thisform . 'results', $sortSaved);
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();

	$query = "
		SELECT cnum, pnum, fname, lname, fvisit, lvisit, acctype, CURDATE() as today, paid, palname, pafname, padob, paphone1, paphone2, pacellphone, passn, crid, crreadmit, crcasetypecode, crcasestatuscode, crcnum, crpnum, crinjurydate, crapptdate, crtherapytypecode  
		FROM cases
		JOIN patients 
		ON crpaid=paid
		LEFT JOIN ptos_pnums 
		ON crpnum=pnum COLLATE latin1_swedish_ci
		";

		// $query2 = "
		// SELECT COUNT(DISTINCT crid)
		// FROM cases
		// JOIN patients 
		// ON crpaid=paid
		// LEFT JOIN ptos_pnums 
		// ON crpnum=pnum COLLATE latin1_swedish_ci
		// ";

	
	$where = array();
	//dump("seRCHSAVED", $searchSaved);
	$where[] = "crcnum IN " . getUserClinicsList();

	// if(userlevel()!=23)
	// 	$where[]="
	// 	crcasestatuscode in ('ACT','SCH') and (
	// 	   lvisit between DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND DATE_ADD(CURDATE(), INTERVAL 7 DAY) OR 
	// 	   crapptdate between DATE_SUB(CURDATE(), INTERVAL 5 DAY) AND DATE_ADD(CURDATE(), INTERVAL 5 DAY)
	// 	) ";

	foreach ($searchSaved as $formvar => $formvarvalue) {
		if (isset($formvarvalue) && !empty($formvarvalue)) {
			$title = $searchvars["$formvar"]['title'];
			$type = $searchvars["$formvar"]['type'];
			$dbformat = $searchvars["$formvar"]['dbformat'];
			$dblength = $searchvars["$formvar"]['dblength'];
			$displayformat = $searchvars["$formvar"]['displayformat'];
			$displaylength = $searchvars["$formvar"]['displaylength'];
			$length = $searchvars["$formvar"]['length'];
			$test = $searchvars["$formvar"]['test'];

			switch ($dbformat):
				case 'date':
					$formvarvalue = date("Y-m-d", strtotime($formvarvalue));
					break;
				case 'phone':
					$formvarvalue = dbPhone($formvarvalue);
					break;
				case 'ssn':
					$formvarvalue = dbSsn($formvarvalue);
					break;
			endswitch;

			switch ($test):
				case 'LIKE':
					$test = "LIKE '" . mysqli_real_escape_string($dbhandle, $formvarvalue) . "%'";
					break;
				default:
					$test = "= '" . mysqli_real_escape_string($dbhandle, $formvarvalue) . "'";
			endswitch;
			$where[] = "$formvar $test";
		}
	}


	//	$where[] = "";

	if (count($where) > 0) {


		//		. " and crcnum=pnum COLLATE latin1_swedish_ci ";
//		$where[] = "crcasestatuscode IN ('SCH','ACT') ";
//		$where[] = "lvisit >= '2010-01-01 00:00:00'";
		$query .= " WHERE " . implode(" and ", $where);
		// 		echo "<pre>";
// print_r($query);
// die();
// $query2 .= " WHERE " . implode(" and ", $where);
		if ($newResult = mysqli_query($dbhandle, $query)) {
			// $newNumRows = mysqli_num_rows($newResult);
			$newNumRows= mysqli_fetch_assoc($newResult);
		}
	
		// $total_no_of_pages = ceil($newNumRows / $total_records_per_page);
		// $second_last = $total_no_of_pages - 1;
		
		// total pages minus 1


		//
		// Sort Order - Contained in Session variable '$thisapplication'=>'searchResults'=>array(field=>collation) as sort
		//
		// echo  "<pre>";
		// print_r($newNumRows);

		// print_r($query);
		// die();
		$orderby = array();
		if (empty($sortSaved)) {
			// default sort here
			$sortvartitles = "unsorted (click column titles to add/toggle sort)";
		} else {
			$sortvartitles = "sorted by ";
			foreach ($sortSaved as $sortvar => $sortvarproperty) {
				$orderby[] = trim($sortvar . " " . $sortvarproperty["collation"]);
				$sortvartitles .= trim($sortvarproperty["title"] . " " . $sortvarproperty["collation"]) . ", ";
			}
		}

		if (count($orderby) > 0) {
			$sortvartitles = substr($sortvartitles, 0, -2);
			$query .= " ORDER BY " . implode(",", $orderby);
		} else
			$query .= " ORDER BY palname, pafname, padob, paphone1, passn ";





		$newQuery = $query;

		$query .= " LIMIT $offset, $total_records_per_page";


		//dump("query",$query);
		if ($result = mysqli_query($dbhandle, $query)) {
			$numRows = mysqli_num_rows($result);



			?>
			<input type="hidden" id="patientNewQuery" value="<?php echo $newQuery ?>" />

			<input type="hidden" id="patientNewCount" value="<?php echo $newNumRows ?>" />
			<div class="loader"></div>
			<div class="containedBox" id="patientTable">
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

							echo "$newNumRows patients found.";
							// if ($numRows < 100)
							// 	echo "$numRows patients found.";
							// else
							// 	echo "Over $numRows patients found. Did not display all patients.";
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

								$new_result = array();
								while ($row = mysqli_fetch_assoc($result)) {
									$new_result[] = $row;
								}
								// echo "<pre>";
								// print_r($new_result);
								// die();
								foreach ($new_result as $key => $row) {
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
									<?php $row['blankpdf'] = "blankpdf"; ?>
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
													<input name="printEnglish" class="printEnglishId" type="button"
														value="Print <?php echo $thiscasetype; ?> English"
														onclick="return printPDF(<?php echo $row['crid'] ?>,'en')" />

													<input name="printSpanish" id="printSpanishId" type="button"
														value="Print <?php echo $thiscasetype; ?> Spanish"
														onclick="return printPDF(<?php echo $row['crid'] ?>,'sp')" />
												</td>


												<?php
											} else { ?>
												<td>
													<!-- 			<input name="printEnglish"  type="button" value="Print <?php echo $thiscasetype; ?> English" onclick="window.open('modules/patient/patientPrintForms.php?id=<?php echo $row['crid'] ?>&lang=en','printEnglish')" />
																								<input name="printSpanish" id="printSpanishId" type="button" value="Print <?php echo $thiscasetype; ?> Spanish" onclick="window.open('modules/patient/patientPrintForms.php?id=<?php echo $row['crid'] ?>&lang=sp','printSpanish')" /> -->
													<input name="printEnglish" class="printEnglishId" type="button"
														value="Print <?php echo $thiscasetype; ?> English"
														onclick="return printPDF(<?php echo $row['crid'] ?>,'en')" />

													<input name="printSpanish" id="printSpanishId" type="button"
														value="Print <?php echo $thiscasetype; ?> Spanish"
														onclick="return printPDF(<?php echo $row['crid'] ?>,'sp')" />
												</td>
												<?php
											}
										} else { ?>
											<td>
												<!--            	<input name="button[<?php echo $row["crid"] ?>]" type="submit" value="Print RA English" onclick="window.open('modules/patient/patientPrintForms.php?id=<?php echo $row['crid'] ?>&lang=en','printEnglish')" />
																					<input name="button[<?php echo $row["crid"] ?>]" type="submit" value="Print RA Spanish"  onclick="window.open('modules/patient/patientPrintForms.php?id=<?php echo $row['crid'] ?>&lang=sp','printSpanish')" /> -->
												<input name="printEnglish" class="printEnglishId" type="button" value="Print RA English"
													onclick="return printPDF(<?php echo $row['crid'] ?>,'en')" />

												<input name="printSpanish" id="printSpanishId" type="button" value="Print RA Spanish"
													onclick="return printPDF(<?php echo $row['crid'] ?>,'sp')" />
											</td>
										<?php } ?>


										<tr>
											<!-- <//?php 
										 if (userlevel() == 23)
											 echo ('<td colspan="19"></td>');
										 else
											 echo ('<td colspan="7"></td>');
										 ?>
													<//?php if ($row['crreadmit'] == '0') {
											if ($thiscasetype == 'WC') { ?>

												<td>
											
													<input name="printEnglish" class="printEnglishId"  type="button" value="English (Walkin)"
														onclick="return printPDF(<//?php echo $row['crid'] ?>,'en')" />

													<input name="printSpanish" id="printSpanishId" type="button"  value="Spanish (Walkin)"
														onclick="return printPDF(<//?php echo $row['crid'] ?>,'sp')" />
												</td>


											<//?php
											} else { ?>
												<td>
												
													<input name="printEnglish" class="printEnglishId"  type="button" value="English (Walkin)"
														onclick="return printPDF(<//?php echo $row['crid'] ?>,'en')" />

													<input name="printSpanish" id="printSpanishId" type="button"  value="Spanish (Walkin)"
														onclick="return printPDF(<//?php echo $row['crid'] ?>,'sp')" />
												</td>
											<//?php
											}
										} else { ?>
											<td>
											
												<input name="printEnglish"  class="printEnglishId" type="button" value="Print RA English (Walkin)"
													onclick="return printPDF(<//?php echo $row['crid'] ?>,'en')" />

												<input name="printSpanish" id="printSpanishId" type="button" value="Print RA Spanish (Walkin)"
													onclick="return printPDF(<//?php echo $row['crid'] ?>,'sp')" />
											</td>
										<//?php } ?> -->
										</tr>


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
										<!-- <td>
											<input name="button[0]" type="button" value="English (Walkin)"
												onclick="window.open('modules/patient/patientPrintForms.php?id=&lang=en&id=walk-in','printEnglish')" />
											<input name="button[0]" type="button" value="Spanish (Walkin)"
												onclick="window.open('modules/patient/patientPrintForms.php?id=&lang=sp&id=walk-in','printSpanish')" />
										</td> -->

										<td>
											<input name="button[0]" type="button" value="English (Walkin) "
												onclick="return printPDF('English(Walkin)','en')" />
											<input name="button[0]" type="button" value="Spanish (Walkin) "
												onclick="return printPDF('Spanish(Walkin)','sp')" />
										</td>
									</tr>
							</table>



						</form>



						<?php
						foreach ($_POST as $key => $val)
							unset($_POST[$key]);
					} else
						echo ('No patients found.');
		} else
			error('001', "QUERY:" . $query . ":" . mysqli_error($dbhandle));
		//close the connection
		mysqli_close($dbhandle);
		?>
			</fieldset>
			<div style='padding: 10px 20px 0px; border-top: dotted 1px #CCC;'>
				<strong>Page
					<?php echo $page_no . " of " . $total_no_of_pages; ?>
				</strong>
			</div>
			<ul class="pagination">
				<?php // if($page_no > 1){ echo "<li><a href='?page_no=1'>First Page</a></li>"; } ?>


				<?php
				if ($total_no_of_pages <= 10) {
					for ($counter = 1; $counter <= $total_no_of_pages; $counter++) {
						if ($counter == $page_no) {
							echo "<li class='active pagination-btn'><a>$counter</a></li>";
						} else {
							echo "<li class='pagination-btn'><a href='?page_no=$counter'>$counter</a></li>";
						}
					}
				} elseif ($total_no_of_pages > 10) {

					if ($page_no <= 4) {
						for ($counter = 1; $counter < 8; $counter++) {
							if ($counter == $page_no) {
								echo "<li class='active'><a>$counter</a></li>";
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
								echo "<li class='active pagination-btn'><a>$counter</a></li>";
							} else {
								echo "<li class='pagination-btn'><a href='?page_no=$counter'>$counter</a></li>";
							}
						}
						echo "<li><a>...</a></li>";
						echo "<li class=' pagination-btn'><a href='?page_no=$second_last'>$second_last</a></li>";
						echo "<li class=' pagination-btn'><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
					} else {
						echo "<li class=' pagination-btn'><a href='?page_no=1'>1</a></li>";
						echo "<li class=' pagination-btn'><a href='?page_no=2'>2</a></li>";
						echo "<li><a>...</a></li>";

						for ($counter = $total_no_of_pages - 6; $counter <= $total_no_of_pages; $counter++) {
							if ($counter == $page_no) {
								echo "<li class=' active pagination-btn'><a>$counter</a></li>";
							} else {
								echo "<li class=' pagination-btn'><a href='?page_no=$counter'>$counter</a></li>";
							}
						}
					}
				}
				?>

			</ul>


		</div>


		<?php
	} else
		unset($_POST['ClickedSearch']);
}
?>
<style>
	.pagination {
		display: inline-block;
		padding-left: 0;
		margin: 20px 0;
		border-radius: 4px
	}

	.pagination>li {
		display: inline
	}

	.pagination>li>a,
	.pagination>li>span {
		position: relative;
		float: left;
		padding: 6px 12px;
		margin-left: -1px;
		line-height: 1.42857143;
		color: #337ab7;
		text-decoration: none;
		background-color: #fff;
		border: 1px solid #ddd
	}

	.pagination>li:first-child>a,
	.pagination>li:first-child>span {
		margin-left: 0;
		border-top-left-radius: 4px;
		border-bottom-left-radius: 4px
	}

	.pagination>li:last-child>a,
	.pagination>li:last-child>span {
		border-top-right-radius: 4px;
		border-bottom-right-radius: 4px
	}

	.pagination>li>a:focus,
	.pagination>li>a:hover,
	.pagination>li>span:focus,
	.pagination>li>span:hover {
		z-index: 2;
		color: #23527c;
		background-color: #eee;
		border-color: #ddd
	}

	.pagination>.active>a,
	.pagination>.active>a:focus,
	.pagination>.active>a:hover,
	.pagination>.active>span,
	.pagination>.active>span:focus,
	.pagination>.active>span:hover {
		z-index: 3;
		color: #fff;
		cursor: default;
		background-color: #337ab7;
		border-color: #337ab7
	}

	.pagination>.disabled>a,
	.pagination>.disabled>a:focus,
	.pagination>.disabled>a:hover,
	.pagination>.disabled>span,
	.pagination>.disabled>span:focus,
	.pagination>.disabled>span:hover {
		color: #777;
		cursor: not-allowed;
		background-color: #fff;
		border-color: #ddd
	}

	.pagination-lg>li>a,
	.pagination-lg>li>span {
		padding: 10px 16px;
		font-size: 18px;
		line-height: 1.3333333
	}

	.pagination-lg>li:first-child>a,
	.pagination-lg>li:first-child>span {
		border-top-left-radius: 6px;
		border-bottom-left-radius: 6px
	}

	.pagination-lg>li:last-child>a,
	.pagination-lg>li:last-child>span {
		border-top-right-radius: 6px;
		border-bottom-right-radius: 6px
	}

	.pagination-sm>li>a,
	.pagination-sm>li>span {
		padding: 5px 10px;
		font-size: 12px;
		line-height: 1.5
	}

	.pagination-sm>li:first-child>a,
	.pagination-sm>li:first-child>span {
		border-top-left-radius: 3px;
		border-bottom-left-radius: 3px
	}

	.pagination-sm>li:last-child>a,
	.pagination-sm>li:last-child>span {
		border-top-right-radius: 3px;
		border-bottom-right-radius: 3px
	}
</style>
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
<script>
	function printPDF(id, lng) {
		$.ajax({
			type: 'post',
			url: 'modules/patient/printPDF.php',
			data: { pdf_id: id, lng: lng },
			success: function (data) {
				window.open(data, '_blank');
			}
		});
	}
</script>


<style>
	.loader {
		border: 8px solid #f3f3f3;
		border-radius: 50%;
		border-top: 8px solid #4682B4;
		width: 60px;
		height: 60px;
		-webkit-animation: spin 2s linear infinite;
		/* Safari */
		animation: spin 2s linear infinite;
		/* margin-left: 49rem; */
		display: none;
		margin: 300px 0px 500px 49rem;
	}

	/* Safari */
	@-webkit-keyframes spin {
		0% {
			-webkit-transform: rotate(0deg);
		}

		100% {
			-webkit-transform: rotate(360deg);
		}
	}

	@keyframes spin {
		0% {
			transform: rotate(0deg);
		}

		100% {
			transform: rotate(360deg);
		}
	}
</style>
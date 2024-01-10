<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(10);


?>
<style>
	.elementssss {
		display: inline-block;

	}
</style>

<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->
<script type="text/javascript">
	function selectallcheckboxes() {
		// written by Daniel P 3/21/07
		// toggle all checkboxes found on the page
		var inputlist = document.getElementsByTagName("input");
		for (i = 0; i < inputlist.length; i++) {
			if (inputlist[i].getAttribute("type") == 'checkbox') { // look only at input elements that are checkboxes
				if (inputlist[i].checked) inputlist[i].checked = false
				else inputlist[i].checked = true;
			}
		}
	}
</script>
<style>
	.pagination {
		display: inline-block;
		padding-left: 70px;
		margin: 6px 0;
		border-radius: 4px
	}
	.pagination-division {
		display: flex;
	}
	.pdf-download{
		float: right;
		margin-right: 5%;
		margin-bottom: 1.3%;
		margin-top: -1.7%;
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

	.tooltip {
		position: relative;
		display: inline-block;
		border-bottom: 1px dotted black;
	}

	.tooltip .tooltiptext {
		visibility: hidden;
		width: 120px;
		background-color: black;
		color: #fff;
		text-align: center;
		border-radius: 6px;
		padding: 5px 0;

		/* Position the tooltip */
		position: absolute;
		z-index: 1;
		top: -5px;
		right: 105%;
	}

	.tooltip:hover .tooltiptext {
		visibility: visible;
	}
</style>
<?php

//if($_SESSION['button']=='Search') 
if (!empty($_SESSION['id']) && $_SESSION['button'] != 'Search') {
	cleartreatmentsearchvalues();
	$_POST['searchpnum'] = $_SESSION['id'];
	$_SESSION['button'] = 'Search';
}
//dumppost();
puttreatmentsearchvalues();

if ($_SESSION['button'] == 'Reset Sort')
	cleartreatmentsortvalues();

if (($_POST['button'][0] == 'Search' || (!empty($_SESSION['button']) && ($_SESSION['button'] == 'Search' || $_SESSION['button'] == 'Reset Sort')) || !empty($_POST['sort']))) {
	$search = FALSE;
	gettreatmentsearchvalues();
	foreach ($_POST as $key => $val) {
		if (!empty($val))
			$search = TRUE;
	}

	if ($search) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();

		// Declare the SQL statement that will query the database
		$query = "SELECT treatment_header.* FROM treatment_header";
		$where = array();

		// Set minimum Clinic Filter
		if (!empty($_SESSION['useraccess']['clinics'])) {
			$where[] = "thcnum IN " . getUserClinicsList() . " ";
		}

		// Determine Clinic Filter
		if (!empty($_POST['searchcnum']) && !empty($_POST['searchcnum'][0]))
			$where[] = "thcnum IN ( '" . implode("','", $_POST['searchcnum']) . "' )";


		if (!empty($_POST['searchcliniccode']) && !empty($_POST['searchcliniccode'][0]))
			$where[] = "thcnum IN ( '" . implode("','", $_POST['searchcliniccode']) . "' )";

		if (isset($_POST['searchfromtreatmentdate']) && !empty($_POST['searchfromtreatmentdate']))
			$where[] = "DATE_FORMAT( thdate, '%Y%m%d' ) >= DATE_FORMAT( '" . date('Y-m-d', strtotime(mysqli_real_escape_string($dbhandle, $_POST['searchfromtreatmentdate']))) . "', '%Y%m%d') ";

		if (isset($_POST['searchtotreatmentdate']) && !empty($_POST['searchtotreatmentdate']))
			$where[] = "DATE_FORMAT( thdate, '%Y%m%d' ) <= DATE_FORMAT( '" . date('Y-m-d', strtotime(mysqli_real_escape_string($dbhandle, $_POST['searchtotreatmentdate']))) . "', '%Y%m%d') ";

		if (isset($_POST['searchpnum']) && !empty($_POST['searchpnum']))
			$where[] = "thpnum= '" . mysqli_real_escape_string($dbhandle, $_POST['searchpnum']) . "'";

		if (isset($_POST['searchbnum']) && !empty($_POST['searchbnum'])) {
			$query .= " LEFT JOIN PTOS_Patients ON pnum = thpnum ";
			$where[] = "bnum = '" . mysqli_real_escape_string($dbhandle, $_POST['searchbnum']) . "'";
		}

		if (isset($_POST['searchlname']) && !empty($_POST['searchlname']))
			$where[] = "thlname LIKE '" . mysqli_real_escape_string($dbhandle, $_POST['searchlname']) . "%'";

		if (isset($_POST['searchfname']) && !empty($_POST['searchfname']))
			$where[] = "thfname LIKE '" . mysqli_real_escape_string($dbhandle, $_POST['searchfname']) . "%'";

		if (isset($_POST['searchctmcode']) && !empty($_POST['searchctmcode']))
			$where[] = "thctmcode= '" . mysqli_real_escape_string($dbhandle, $_POST['searchctmcode']) . "'";

		if (isset($_POST['searchvtmcode']) && !empty($_POST['searchvtmcode']))
			$where[] = "thvtmcode= '" . mysqli_real_escape_string($dbhandle, $_POST['searchvtmcode']) . "'";

		if (isset($_POST['searchttmcode']) && !empty($_POST['searchttmcode']))
			$where[] = "thttmcode= '" . mysqli_real_escape_string($dbhandle, $_POST['searchttmcode']) . "'";

		if (isset($_POST['searchfromsubmitdate']) && !empty($_POST['searchfromsubmitdate']))
			$where[] = "DATE_FORMAT( thsbmdate, '%Y%m%d' ) >= DATE_FORMAT( '" . date('Y-m-d', strtotime(mysqli_real_escape_string($dbhandle, $_POST['searchfromsubmitdate']))) . "', '%Y%m%d') ";

		if (isset($_POST['searchtosubmitdate']) && !empty($_POST['searchtosubmitdate']))
			$where[] = "DATE_FORMAT( thsbmdate, '%Y%m%d' ) <= DATE_FORMAT( '" . date('Y-m-d', strtotime(mysqli_real_escape_string($dbhandle, $_POST['searchtosubmitdate']))) . "', '%Y%m%d') ";

		if (isset($_POST['searchsbmstatus']) && !empty($_POST['searchsbmstatus']))
			$where[] = "thsbmstatus " . mysqli_real_escape_string($dbhandle, $_POST['searchsbmstatus']) . " ";

		if (count($where) > 0)
			$query .= " WHERE " . implode(" and ", $where) . " ";

		// Implement Sort
// build sortfields array from saved form values
//gettreatmentsortvalues();
		gettreatmentsortvalues();
		if (!empty($_POST['sortfields']))
			foreach ($_POST['sortfields'] as $field => $data) {
				list($title, $collation) = explode("|", $data);
				$sortfields["$field"] = array("title" => $title, "collation" => $collation);
			} else
			$sortfields = array();

		// URL Encoded Parameters need to be re-packaged
		if (is_array($_POST['sort'])) {
			if (!empty($_POST['sort'])) {
				$sortfield = key($_POST['sort']);
				if (array_key_exists($sortfield, $sortfields)) {
					if ($sortfields[$sortfield]['collation'] == 'desc')
						$sortfields[$sortfield]['collation'] = '';
					else
						$sortfields[$sortfield]['collation'] = 'desc';
				} else
					$sortfields[$sortfield] = array("title" => $_POST['sort']["$sortfield"], "collation" => '');
				foreach ($sortfields as $key => $val)
					$_POST['sortfields'][$key] = $val['title'] . "|" . $val['collation'];
				puttreatmentsortvalues();
			}
		}

		$orderby = array();
		$sortfieldtitles = ''; foreach ($sortfields as $key => $val) {
			$orderby[] = $key . " " . $val['collation'];
			if ($val['collation'] == 'desc')
				$sortfieldtitles .= $val['title'] . ' (descending), ';
			else
				$sortfieldtitles .= $val['title'] . ', ';
		}
		if (count($orderby) > 0) {
			$sortfieldtitles = substr($sortfieldtitles, 0, -2);
			$query .= " ORDER BY " . implode(", ", $orderby) . " ";
		}


		if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
			$page_no = $_GET['page_no'];
			// print_r("***");
			// print_r($page_no);

		} else {
			$page_no = 1;
			// print_r("++++");
			// print_r($page_no);
		}

		$countsss = mysqli_query($dbhandle, $query);
		$newCount = mysqli_num_rows($countsss);

		$total_records_per_page = 100;

		$offset = ($page_no - 1) * $total_records_per_page;
		$previous_page = $page_no - 1;
		$next_page = $page_no + 1;
		$adjacents = "2";

		$total_records = $newCount;


		$total_no_of_pages = ceil($total_records / $total_records_per_page);
		$second_last = $total_no_of_pages - 1; // total pages minus 1

		$newquery = $query;
		$getcount = mysqli_query($dbhandle, $newquery);
		$newcounts = mysqli_num_rows($getcount);

		$query .= " LIMIT $offset , $total_records_per_page";

		//dump('query', $query);
		$result = mysqli_query($dbhandle, $query);


		if (!$result)
			error("001", "MySql[searchresults]:" . mysqli_error($dbhandle));

		$numRows = mysqli_num_rows($result);




		function echosearchlink($pnum)
		{
			if (userlevel() >= 23 && empty($_POST['searchpnum'])) {
				echo ('<input type="submit" name="button[' . $pnum . ']" value="' . $pnum . '" />');
			} else
				echo ("$pnum");
		}
		?>
		<input type="hidden" name="query" id="query" value="<?php echo $newquery; ?>" />
		<input type="hidden" name="newCount" id="newCount" value="<?php echo $newCount; ?>" />
		<div class="loader"></div>
		<div style="float:right; margin-right:5%; margin-bottom: 0.3%; margin-top: 0.3%;">
			<!-- <img src="/img/icon-pdf.png" onClick="return printPDFXLS(1)" style="position: absolute;cursor: pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<img src="/img/icon-pdf.png" onClick="return printPDFXLS(1)" style="position: absolute;cursor: pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -->

			<!-- <//?php -->
			<!-- if ($newcounts > 1000) { -->
			<!-- ?> -->
			<!-- <div style="display: inline-flex;" class="tooltip"> -->
			<!-- <img src="/img/gray-pdf.png" style="position: absolute;cursor: pointer;width: 19px;">&nbsp;&nbsp; -->
			<!-- <span class="tooltiptext">Use XLS <img src="/img/icon-xls.png"> -->
			<!-- export for more than 1000 records.</span> -->
			<!-- </div> -->
			<!-- <//?php } else { -->
			<!-- ?> -->
			<!-- <img src="/img/icon-pdf.png" onClick="return printPDFXLS(1)" -->
			<!-- style="position: absolute;cursor: pointer;">&nbsp;&nbsp; -->
			<!-- <//?php } ?> -->
		</div><br>



		<fieldset class="containedBox">
			<legend class="boldLarger">Search Results New
				<?php if (count($orderby) > 0)
					echo " sorted by " . $sortfieldtitles;
				else
					echo " unsorted (click column titles to add/toggle sort)"; ?>
			</legend>
			<?php
			if ($numRows > 0) {
				?>
				<?php
				foreach ($sortfields as $key => $val) {
					?>
					<input name="sortfields[<?php echo $key; ?>]" type="hidden"
						value="<?php echo $val['title'] . "|" . $val['collation']; ?>" />
					<?php
				}
				?>

				<div id="resultdata">
			<div class="pagination-division">
					<?php
					$recordfrom = $page_no * 100 - 100;
					$recordTo = $page_no * 100;
					if ($newcounts == 1) {
						echo "$newcounts Treatment found.";

					} else {
						if ($newcounts < 100) {
							?>

							<?php
							echo "$newcounts Treatments(s) found.";

						} else { ?>

							<?php
							echo "<p>$newcounts Treatments(s) found. | $recordfrom - $recordTo displayed </p>";
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
									echo "<li class='active pagination-btn'><a>$counter</a></li>";
								} else {
									echo "<li class='pagination-btn'><a href='?page_no=$counter'>$counter</a></li>";
								}
							}
						} elseif ($total_no_of_pages > 10) {

							if ($page_no <= 4) {
								for ($counter = 1; $counter < 8; $counter++) {
									if ($counter == $page_no) {
										echo "<li class='active pagination-btn'><a>$counter</a></li>";
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
								echo "<li class='pagination-btn'><a href='?page_no=$second_last'>$second_last</a></li>";
								echo "<li class='pagination-btn'><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
							} else {
								echo "<li class='pagination-btn'><a href='?page_no=1'>1</a></li>";
								echo "<li class='pagination-btn'><a href='?page_no=2'>2</a></li>";
								echo "<li><a>...</a></li>";

								for ($counter = $total_no_of_pages - 6; $counter <= $total_no_of_pages; $counter++) {
									if ($counter == $page_no) {
										echo "<li class='active pagination-btn'><a>$counter</a></li>";
									} else {
										echo "<li class='pagination-btn'><a href='?page_no=$counter'>$counter</a></li>";
									}
								}
							}
						}
						?>

					</ul>
				
			</div>
					<div class="pdf-download">
						<div style="display: inline-flex;" class="tooltip">
							<img src="/img/icon-pdf.png" onClick="return printPDFXLS(1)"
								style="position: absolute;cursor: pointer;">&nbsp;&nbsp;

							<img src="/img/icon-xls.png" id="printPDFXLSButton"   style="position: absolute;margin-left: 25px;cursor: pointer;"
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

						// print_r($result);
						$billablerows = 0;
						$new_result = array();
						while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
							$new_result[] = $row;



						}


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
							<tr>
								<!-- <//?php
								// if (userlevel() == 23) {
									// if ($row['thsbmstatus'] >= 100 && $row['thsbmstatus'] < 500 && !empty($pnum)) {
										// $billablerows++;
										// ?>
										<td><input name="checkbox[<?php echo $thid; ?>]" type="checkbox" value="<?php echo $row['thid']; ?>" <?php if ($_POST['checkbox'][$row['thid']] == 1)
											  //   echo "checked"; ?> /></td>
									<//?php
									} else {
										?>
										<td>&nbsp;</td>
									<//?//php
									}
								}
								?> -->
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
							<!-- <img src="/img/icon-pdf.png" onClick="return printPDFXLS(1)" style="position: absolute;cursor: pointer;">&nbsp;&nbsp;
						<img src="/img/icon-xls.png" style="position: absolute;margin-left: 25px;cursor: pointer;" onClick="return printPDFXLS()"> -->
						</div><br>
						<!-- <div style="height: 30px;float: right;width: 50px;"></div> -->





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





						<!-- /////////////////////////////////////// -->


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
										echo "<li class='active pagination-btn'><a>$counter</a></li>";
									} else {
										echo "<li class='pagination-btn'><a href='?page_no=$counter'>$counter</a></li>";
									}
								}
							} elseif ($total_no_of_pages > 10) {

								if ($page_no <= 4) {
									for ($counter = 1; $counter < 8; $counter++) {
										if ($counter == $page_no) {
											echo "<li class='active pagination-btn'><a>$counter</a></li>";
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
									echo "<li class='pagination-btn'><a href='?page_no=$second_last'>$second_last</a></li>";
									echo "<li class='pagination-btn'><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
								} else {
									echo "<li class='pagination-btn'><a href='?page_no=1'>1</a></li>";
									echo "<li class='pagination-btn'><a href='?page_no=2'>2</a></li>";
									echo "<li><a>...</a></li>";

									for ($counter = $total_no_of_pages - 6; $counter <= $total_no_of_pages; $counter++) {
										if ($counter == $page_no) {
											echo "<li class='active pagination-btn'><a>$counter</a></li>";
										} else {
											echo "<li class='pagination-btn'><a href='?page_no=$counter'>$counter</a></li>";
										}
									}
								}
							}
							?>

						</ul>
					</div>
					<div>
						<input type="hidden" value="<?php echo $newcounts; ?>" id="total_treatment_row" />
						<div class="boldLarger elementssss" id="numrowstodisplay">Found
							<?php echo number_format($newcounts); ?>
						</div>
						<div class="boldLarger  elementssss" style="clear:both">
							<?php

				//		require_once('treatmentSubmitTreatmentsForm.php');
	

			} else {
				echo ('No treatments found ');
			}
			//close the connection
			mysqli_close($dbhandle);
			// 	Select unposted records for current clinic
			?>
						<?php echo $_SESSION['workingDate']; ?> records on
						<?php echo date('m/d/Y H:i:s'); ?>.
					</div>
				</div>
		</fieldset>
		</div>
		</div>


		<?php
	} else {
		?>
		<fieldset class="containedBox">
			<legend class="boldLarger">Search Results</legend>
			<div>At least one search value must be entered above.</div>
		</fieldset>
	<?php }
}
?>

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
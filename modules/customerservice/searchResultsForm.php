<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(11);
$searchSaved = getformvars('customerservice', 'search');
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

	.pagination {
		margin: 6px 50px;
	}

	.Patient-search-Results {
		display: flex;
	}

	.pdf-icon-bar {
		margin: -30px 0px 0px 0px;
	}

	.swal2-styled.swal2-confirm {
		background-color: #dc3741 !important;
	}

	.swal2-deny.swal2-styled {
		background-color: #14A44D !important;

	}

	button.swal2-cancel.swal2-styled {
		padding: 8px !important;
	}

	button.swal2-deny.swal2-styled {
		padding: 8px !important;
	}

	button.swal2-confirm.swal2-styled {
		padding: 8px !important;
	}

	.swal2-html-container {

		font-size: 1em !important;
	}
</style>
<?php


if (!empty($searchSaved) || !empty($_POST['formSubmit']) || !empty($_POST['sort'])) {


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
	$sortSaved = getformvars('customerservice', 'searchResults');
	// if Sort button pressed set sort values
	if (count($_POST['sort']) > 0) {
		// If Reset Sort Pressed then clear saved values
		if (!empty($_POST['sort']['RESETSORT'])) {
			clearformvars('customerservice', 'searchResults');
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
			setformvars('customerservice', 'searchResults', $sortSaved);
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();

	$query = "
		SELECT DISTINCT paid, palname, pafname, DATE_FORMAT(padob, '%m/%d/%Y') as padob, paphone1, passn
		FROM patients p";

	if (isset($searchSaved['pnum']) && $searchSaved['pnum']) {
		//We Don't have easy access to pnum from the patients table so we have to join to cases where we do
		$query .= "LEFT JOIN cases ON crpaid = paid
                   LEFT JOIN PTOS_Patients ON crpnum = pnum";
	}
	$where = array();

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

	if (count($where) > 0) {
		$query .= " WHERE " . implode(" and ", $where);

		//
		// Sort Order - Contained in Session variable 'customerservice'=>'searchResults'=>array(field=>collation) as sort
		//

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
		} else {
			$query .= " ORDER BY palname, pafname, padob, paphone1, passn";
		}
		$newquery = $query;
		// $query.= " LIMIT 100 ";
		$query .= " LIMIT $offset, $total_records_per_page";

		$result = mysqli_query($dbhandle, $query);
		if ($result) {
			$numRows = mysqli_num_rows($result);

			$newresult = mysqli_query($dbhandle, $newquery);
			$numRowsCount = mysqli_num_rows($newresult);


			$total_no_of_pages = ceil($numRowsCount / $total_records_per_page);
			$second_last = $total_no_of_pages - 1;

			// print_r("total_no_of_pages*************");
			// print_r($total_records_per_page);
			// print_r("total_no_of_pages*************");
			// print_r($total_no_of_pages);

			?>
			<input type="hidden" value="<?php echo $numRowsCount; ?>" id="totalcount" />
			<div class="containedBox">
				<fieldset>
					<legend style="font-size:large;">Search Patient Results
						<?php echo $sortvartitles; ?>
					</legend>
					<?php
					// if ($numRows > 0) {
					// 	if ($numRows == 1)
					// 		echo "$numRows patient found.";
					// 	else {
					// 		if ($numRows < 100)
					// 			echo "$numRows patients found.";
					// 		else
					// 			echo "Over $numRows patients found. Did not display all patients.";
					// 	}
					$recordfrom = $page_no * 100 - 100;
					$recordTo = $page_no * 100;
					if ($numRowsCount > 0) {
						if ($numRowsCount == 1)
							echo "$numRowsCount Patient found.";
						else {
							if ($numRowsCount < 100) {
								echo "$numRowsCount Patients(s) found.";

							} else { ?>
								<div class="Patient-search-Results">
									<?php
									echo "<p>$numRowsCount Patients(s) found. | $recordfrom - $recordTo displayed </p>";
							}
							// echo "Over $numRowsCount Patients found. Did not display all Patients.";
						}

						?>
							<ul class="pagination">


								<?php
								if ($total_no_of_pages <= 10) {
									for ($counter = 1; $counter <= $total_no_of_pages; $counter++) {
										if ($counter == $page_no) {
											echo "<li class='active pagination-btn'><a>$counter</a></li>";
										} else {
											echo "<li class='pagination-btn'><a href='?page_no=$counter?total=$numRowsCount'>$counter</a></li>";
										}
									}
								} elseif ($total_no_of_pages > 10) {

									if ($page_no <= 4) {
										for ($counter = 1; $counter < 8; $counter++) {
											if ($counter == $page_no) {
												echo "<li class='active'><a>$counter</a></li>";
											} else {
												echo "<li class='pagination-btn'><a href='?page_no=$counter?total=$numRowsCount'>$counter</a></li>";
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
												echo "<li class='pagination-btn'><a href='?page_no=$counter?total=$numRowsCount'>$counter</a></li>";
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
												echo "<li class=' pagination-btn'><a href='?page_no=$counter?total=$numRowsCount'>$counter</a></li>";
											}
										}
									}
								}
								?>

							</ul>
						</div>
						<div class="pdf-icon-bar">
							<a href="/modules/customerservice/reports/printXLS.php" id="exceldownload"><img src="/img/icon-xls.png"
									id="exceldownload2" style="float:right;margin-left: 3px;cursor: pointer;margin-right: 15px;"></a>
							<!-- 
							<a href="/modules/customerservice/reports/printPdf.php" onClick="return printPDFXLS(1)"><img src="/img/icon-pdf.png"
									style="float: right;cursor: pointer;margin-bottom: 10px;margin-right: 10px;"></a> -->

							<img src="/img/icon-pdf.png" onClick="return printPDFXLS(1)"
								style="float: right;cursor: pointer;margin-bottom: 10px;margin-right: 10px;">

						</div>

						<form method="post" name="searchResults">
							<table border="1" cellpadding="3" cellspacing="0" width="100%">
								<tr>
									<th><input name="sort[palname]" type="submit" value="Last Name" /></th>
									<th><input name="sort[pafname]" type="submit" value="First Name" /></th>
									<th><input name="sort[padob]" type="submit" value="DOB" /></th>
									<th><input name="sort[paphone1]" type="submit" value="Phone" /></th>
									<th><input name="sort[passn]" type="submit" value="SSN" /></th>
									<th><input name="sort[RESETSORT]" type="submit" value="Reset Sort"></th>
								</tr>
								<?php

								while ($row = mysqli_fetch_assoc($result)) {
									if ($row['painactive'] == '1') {
										$rowstyle = ' style="background-color:#FFFFCC;"';
										$togglebutton = 'Make Active';
									} else {
										$rowstyle = '';
										$togglebutton = 'Make Inactive';
									}
									?>
									<tr<?php echo $rowstyle; ?>>
										<td>
											<?php echo $row["palname"]; ?>&nbsp;
										</td>
										<td>
											<?php echo $row["pafname"]; ?>&nbsp;
										</td>
										<td>
											<?php echo $row["padob"]; ?>&nbsp;
										</td>
										<td>
											<?php echo displayPhone($row["paphone1"]); ?>&nbsp;
										</td>
										<td>
											<?php echo displaySsn($row["passn"]); ?>&nbsp;
										</td>
										<td><input name="button[<?php echo $row["paid"] ?>]" type="submit" value="Edit Patient" />
											<!--					<input name="button[<?php echo $row["paid"] ?>]" type="submit" value="<?php echo $togglebutton ?>" />-->
											<?php
											$parm = array();
											$parm[] = 'buttonSetSearchCase=1';
											//	foreach($searchvars as $field=>$property) {
//		$parm[]='searchcase[' . $field . "]=" . $row["$field"];
//	}
											$parm[] = "searchcase[paid]=" . $row['paid'];
											$urlparm = urlencode(implode("&", $parm));
											?>
											<input name="navigation[<?php echo ($urlparm) ?>]" type="submit" value="Search Cases" />

										</td>
										</tr>
										<?php
								}
								?>
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
							echo "<li class='pagination-btn'><a href='?page_no=$counter?total=$numRowsCount'>$counter</a></li>";
						}
					}
				} elseif ($total_no_of_pages > 10) {

					if ($page_no <= 4) {
						for ($counter = 1; $counter < 8; $counter++) {
							if ($counter == $page_no) {
								echo "<li class='active'><a>$counter</a></li>";
							} else {
								echo "<li class='pagination-btn'><a href='?page_no=$counter?total=$numRowsCount'>$counter</a></li>";
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
								echo "<li class='pagination-btn'><a href='?page_no=$counter?total=$numRowsCount'>$counter</a></li>";
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
								echo "<li class=' pagination-btn'><a href='?page_no=$counter?total=$numRowsCount'>$counter</a></li>";
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





<link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
	integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
	crossorigin="anonymous" referrerpolicy="no-referrer" />
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/js/all.min.js"
	integrity="sha512-2bMhOkE/ACz21dJT8zBOMgMecNxx0d37NND803ExktKiKdSzdwn+L7i9fdccw/3V06gM/DBWKbYmQvKMdAA9Nw=="
	crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>


	function printPDFXLS(is_pdf = 0) {
		// alert("dfgdgfhjgf");

		if (is_pdf) {




			let time = 0
			let totalcount = $("#totalcount").val();

			if (totalcount < 1000) {

			} else {
				time = totalcount / 1000;
				time = Math.floor(time);

			}


			// PDF creation may take 0 minute(s) + for 10 records.Excel file is an immediate download that you can print to PDF.
			// 			var abc = `PDF creation may take ${time} minute + for ${totalcount} records.
			// Excel option is an immediate download that you can print to PDF.`

			var abc = `PDF creation may take ${time} minute(s) + for ${totalcount} records. <br>
			Excel files download faster and can be printed to PDF.`

			if (totalcount > 300) {
				Swal.fire({
					html: abc,

					showDenyButton: true,
					showCancelButton: true,
					confirmButtonText:
						'<i class="fa-solid fa-file-pdf"></i> Print to PDF',

					confirmButtonAriaLabel: 'Cancel PDF',
					denyButtonText: `Print to Excel`,
					cancelButtonText: 'Cancel',

				}).then((result) => {
					/* Read more about isConfirmed, isDenied below */
					if (result.isConfirmed) {

						callajax();

					} else if (result.isDenied) {
						$("#exceldownload2").click();
					}

					// if (!result.isConfirmed) {

					// 	$("#exceldownload2").click();


					// }

				})
			} else {
				callajax();
			}




		} else {
			Swal.fire({
				title: 'Please Wait !',
				html: 'Creating Document',
				allowOutsideClick: false,
				onBeforeOpen: () => {
					Swal.showLoading()
				},
			});
		}
	}

	function callajax() {
		Swal.fire({
			title: 'Please Wait !',
			html: 'Creating Document',
			allowOutsideClick: false,
			showConfirmButton: false,
			showLoading: true
			// onOpen: () => {
			// 	Swal.showLoading()
			// },
		});
		var is_pdf_12 = "is_pdf";

		$.ajax({
			type: "GET",
			url: "modules/customerservice/reports/printPdf.php",
			// xhrFields: { responseType: "blob" },
			cache: false,
			contentType: "application/json",
			xhrFields: { responseType: "blob" },
			data: `printpdf=${is_pdf_12}`,
			success: function (response) {
				console.log("response", response)


				let blob = new Blob([response], { type: 'arraybuffer' });
				let link = document.createElement('a');
				let objectURL = window.URL.createObjectURL(blob);
				link.href = objectURL;
				link.target = '_self';
				link.download = "treatmentsearchreport.pdf";
				(document.body || document.documentElement).appendChild(link);
				link.click();


				// a = document.createElement('a');
				// var binaryData = [];
				// binaryData.push(response);
				// a.href = window.URL.createObjectURL(new Blob(binaryData, {type: "application/pdf"}));
				// a.download = "Estimation.pdf";
				// a.style.display = 'none';
				// document.body.appendChild(a);
				// a.click();


				swal.fire({
					title: 'File downloaded to your computer.',
					type: 'success',
					timer: 3000,
					showConfirmButton: false
				})
				setTimeout(() => {
					window.URL.revokeObjectURL(objectURL);
					link.remove();
				}, 100);

			}, error: function (xhr, status, error) {

				// console.log("dfjsdhfjhdsjkhjkd", error);
			}
		});
	}
</script>
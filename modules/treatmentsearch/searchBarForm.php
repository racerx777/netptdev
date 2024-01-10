<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');

securitylevel(10);
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 259200);
// header('Content-type: application/pdf');
// Format Clinic
// if(isset($_POST['searchcnum']) && !empty($_POST['searchcnum'])) {
// 	print_r($_POST['searchcnum']);die;
// }
// if(isset($_GET['test'])){
?>
<!-- <link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
	integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
	crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/js/all.min.js"
	integrity="sha512-2bMhOkE/ACz21dJT8zBOMgMecNxx0d37NND803ExktKiKdSzdwn+L7i9fdccw/3V06gM/DBWKbYmQvKMdAA9Nw=="
	crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script> -->


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
<style>
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

<script type="text/javascript">
	function printPDFXLS(is_pdf = 0) {

		let searchcnum_inp = document.querySelector('#searchcnum').value;
		let searchcliniccodeid_inp = document.querySelector('#searchcliniccodeid').value;
		let searchfromtreatmentdate_inp = document.querySelector('#searchfromtreatmentdate').value;
		let searchtotreatmentdate_inp = document.querySelector('#searchtotreatmentdate').value;
		let searchpnum_inp = document.querySelector('#searchpnum').value;
		let searchlname_inp = document.querySelector('#searchlname').value;
		let searchfname_inp = document.querySelector('#searchfname').value;
		let searchctmcodeId_inp = document.querySelector('#searchctmcodeId').value;
		let searchvtmcodeId_inp = document.querySelector('#searchvtmcodeId').value;
		let searchttmcodeId_inp = document.querySelector('#searchttmcodeId').value;
		let searchnadate_inp = document.querySelector('#searchnadate').value;
		let searchfromsubmitdate_inp = document.querySelector('#searchfromsubmitdate').value;
		let searchtosubmitdate_inp = document.querySelector('#searchtosubmitdate').value;
		let searchsbmstatusId_inp = document.querySelector('#searchsbmstatusId').value;
		let total_rows = document.querySelector('#total_treatment_row').value;
		let appliedSearch = "";
		if (searchcnum_inp) {
			appliedSearch = `Clinic = ${searchcnum_inp} `;
		}
		if (searchcliniccodeid_inp) {
			if (appliedSearch) {
				appliedSearch += `|Clinic Code = ${searchcliniccodeid_inp}`

			} else {
				appliedSearch = `Clinic Code = ${searchcliniccodeid_inp} `;

			}
		}

		if (searchfromtreatmentdate_inp) {
			if (appliedSearch) {
				appliedSearch += `| Treatment Date Range = ${searchfromtreatmentdate_inp}`

			} else {
				appliedSearch = `Treatment Date Range = ${searchfromtreatmentdate_inp} `;

			}
		}


		if (searchtotreatmentdate_inp) {
			if (appliedSearch) {
				appliedSearch += `| Treatment Date Range = ${searchtotreatmentdate_inp}`

			} else {
				appliedSearch = `Treatment Date Range = ${searchtotreatmentdate_inp} `;

			}
		}




		if (searchpnum_inp) {
			if (appliedSearch) {
				appliedSearch += `| Patient Number = ${searchpnum_inp}`

			} else {
				appliedSearch = `Patient Number = ${searchpnum_inp} `;

			}
		}



		if (searchlname_inp) {
			if (appliedSearch) {
				appliedSearch += `| Patient Last Name = ${searchlname_inp}`

			} else {
				appliedSearch = `Patient Last Name = ${searchlname_inp} `;

			}
		}



		if (searchfname_inp) {
			if (appliedSearch) {
				appliedSearch += `| Patient First Name = ${searchfname_inp}`

			} else {
				appliedSearch = `Patient First Name = ${searchfname_inp} `;

			}
		}


		if (searchctmcodeId_inp) {
			if (appliedSearch) {
				appliedSearch += `| Case Type = ${searchctmcodeId_inp}`

			} else {
				appliedSearch = `Case Type = ${searchctmcodeId_inp} `;

			}
		}

		if (searchvtmcodeId_inp) {
			if (appliedSearch) {
				appliedSearch += `| Visit Type = ${searchvtmcodeId_inp}`

			} else {
				appliedSearch = `Visit Type = ${searchvtmcodeId_inp} `;

			}
		}

		if (searchttmcodeId_inp) {
			if (appliedSearch) {
				appliedSearch += `| Treatment Type = ${searchttmcodeId_inp}`

			} else {
				appliedSearch = `Treatment Type = ${searchttmcodeId_inp} `;

			}
		}


		if (searchnadate_inp) {
			if (appliedSearch) {
				appliedSearch += `| Next Action Date = ${searchnadate_inp}`

			} else {
				appliedSearch = `Next Action Date = ${searchnadate_inp} `;

			}
		}

		if (searchfromsubmitdate_inp) {
			if (appliedSearch) {
				appliedSearch += `| Submission Date Range = ${searchfromsubmitdate_inp}`

			} else {
				appliedSearch = `Submission Date Range = ${searchfromsubmitdate_inp} `;

			}
		}

		if (is_pdf) {


			console.log("if", is_pdf);


			let time = 0
			let totalcount = total_rows;

			if (totalcount < 1000) {

			} else {
				time = totalcount / 1000;
				time = Math.floor(time);

			}

			if (totalcount > 1000) {
				var abc = `A PDF can not be generated with over 1000 records. <br>
				Filter your results further or "Print to Excel" instead.`
			} else {
				var abc = `PDF creation may take ${time} minute(s) + for ${totalcount} records.<br>
			Excel files download faster and can be printed to PDF.`
			}





			if (totalcount > 100) {

				if (totalcount > 1000) {
					Swal.fire({
						html: abc,

						showDenyButton: true,
						showCancelButton: true,
						showConfirmButton: false,
						denyButtonText: `Print to Excel`,
						cancelButtonText: 'Cancel',

					}).then((result) => {
						/* Read more about isConfirmed, isDenied below */
						if (result.isConfirmed) {

							callajax(is_pdf);

						} else if (result.isDenied) {
							$("#printPDFXLSButton").click();
						}

						// if (!result.isConfirmed) {

						// 	$("#exceldownload2").click();


						// }

					})
				} else {
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

							callajax(is_pdf);

						} else if (result.isDenied) {
							$("#printPDFXLSButton").click();
						}

						// if (!result.isConfirmed) {

						// 	$("#exceldownload2").click();


						// }

					})
				}



			} else {
				callajax(is_pdf);
			}




			// $.ajax({
			// 	type: "GET",
			// 	url: "modules/treatmentsearch/printPdf.php",
			// 	xhrFields: { responseType: "blob" },
			// 	data: `printpdf=${is_pdf}&searchcnum=${searchcnum_inp}&searchcliniccode=${searchcliniccodeid_inp}&searchfromtreatmentdate=${searchfromtreatmentdate_inp}&searchtotreatmentdate=${searchtotreatmentdate_inp}&searchpnum=${searchpnum_inp}&searchlname=${searchlname_inp}&searchfname=${searchfname_inp}&searchctmcode=${searchctmcodeId_inp}&searchvtmcode=${searchvtmcodeId_inp}&searchttmcode=${searchttmcodeId_inp}&searchnadate=${searchnadate_inp}&searchfromsubmitdate=${searchfromsubmitdate_inp}&searchtosubmitdate=${searchtosubmitdate_inp}&searchsbmstatus=${searchsbmstatusId_inp}&total_rows=${total_rows}&appliedSearch=${appliedSearch}`,
			// 	success: function (response) {
			// 		console.log("response",response)
			// 		let blob = new Blob([response], { type: 'arraybuffer' });
			// 		let link = document.createElement('a');
			// 		let objectURL = window.URL.createObjectURL(blob);
			// 		link.href = objectURL;
			// 		link.target = '_self';
			// 		link.download = "treatmentsearchreport.pdf";
			// 		(document.body || document.documentElement).appendChild(link);
			// 		link.click();
			// 		swal.fire({
			// 			title: 'File downloaded to your computer.',
			// 			type: 'success',
			// 			timer: 3000,
			// 			showConfirmButton: false
			// 		})
			// 		setTimeout(() => {
			// 			window.URL.revokeObjectURL(objectURL);
			// 			link.remove();
			// 		}, 100);

			// 	}, error: function (xhr, status, error) {

			// 		console.log("dfjsdhfjhdsjkhjkd", error);
			// 	}
			// });

		} else {
			console.log("else", is_pdf);

			Swal.fire({
				title: 'Please Wait !',
				html: 'Creating Document',
				allowOutsideClick: false,
				showConfirmButton: false,
				// onBeforeOpen: () => {
				// 	Swal.showLoading()
				// },
			});
			// printPDFXLSButton
			$.ajax({
				type: "GET",
				url: "modules/treatmentsearch/printXLS.php",
				data: `printpdf=${is_pdf}&searchcnum=${searchcnum_inp}&searchcliniccode=${searchcliniccodeid_inp}&searchfromtreatmentdate=${searchfromtreatmentdate_inp}&searchtotreatmentdate=${searchtotreatmentdate_inp}&searchpnum=${searchpnum_inp}&searchlname=${searchlname_inp}&searchfname=${searchfname_inp}&searchctmcode=${searchctmcodeId_inp}&searchvtmcode=${searchvtmcodeId_inp}&searchttmcode=${searchttmcodeId_inp}&searchnadate=${searchnadate_inp}&searchfromsubmitdate=${searchfromsubmitdate_inp}&searchtosubmitdate=${searchtosubmitdate_inp}&searchsbmstatus=${searchsbmstatusId_inp}&XLS="XLS"&total_rows=${total_rows}`,
				xhrFields: {
					responseType: 'arraybuffer' // to avoid binary data being mangled on charset conversion
				},
				success: function (data) {
					var blob = new Blob([data], { type: 'application/vnd.ms-excel' });
					var downloadUrl = URL.createObjectURL(blob);
					var a = document.createElement("a");
					a.href = downloadUrl;
					a.download = "treatmentsearchreport.xls";
					document.body.appendChild(a);
					a.click();
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
				}
			});

		}

	}

	function callajax(is_pdf) {


		let searchcnum_inp = document.querySelector('#searchcnum').value;
		let searchcliniccodeid_inp = document.querySelector('#searchcliniccodeid').value;
		let searchfromtreatmentdate_inp = document.querySelector('#searchfromtreatmentdate').value;
		let searchtotreatmentdate_inp = document.querySelector('#searchtotreatmentdate').value;
		let searchpnum_inp = document.querySelector('#searchpnum').value;
		let searchlname_inp = document.querySelector('#searchlname').value;
		let searchfname_inp = document.querySelector('#searchfname').value;
		let searchctmcodeId_inp = document.querySelector('#searchctmcodeId').value;
		let searchvtmcodeId_inp = document.querySelector('#searchvtmcodeId').value;
		let searchttmcodeId_inp = document.querySelector('#searchttmcodeId').value;
		let searchnadate_inp = document.querySelector('#searchnadate').value;
		let searchfromsubmitdate_inp = document.querySelector('#searchfromsubmitdate').value;
		let searchtosubmitdate_inp = document.querySelector('#searchtosubmitdate').value;
		let searchsbmstatusId_inp = document.querySelector('#searchsbmstatusId').value;
		let total_rows = document.querySelector('#total_treatment_row').value;
		let appliedSearch = "";
		if (searchcnum_inp) {
			appliedSearch = `Clinic = ${searchcnum_inp} `;
		}
		if (searchcliniccodeid_inp) {
			if (appliedSearch) {
				appliedSearch += `|Clinic Code = ${searchcliniccodeid_inp}`

			} else {
				appliedSearch = `Clinic Code = ${searchcliniccodeid_inp} `;

			}
		}

		if (searchfromtreatmentdate_inp) {
			if (appliedSearch) {
				appliedSearch += `| Treatment Date Range = ${searchfromtreatmentdate_inp}`

			} else {
				appliedSearch = `Treatment Date Range = ${searchfromtreatmentdate_inp} `;

			}
		}


		if (searchtotreatmentdate_inp) {
			if (appliedSearch) {
				appliedSearch += `| Treatment Date Range = ${searchtotreatmentdate_inp}`

			} else {
				appliedSearch = `Treatment Date Range = ${searchtotreatmentdate_inp} `;

			}
		}




		if (searchpnum_inp) {
			if (appliedSearch) {
				appliedSearch += `| Patient Number = ${searchpnum_inp}`

			} else {
				appliedSearch = `Patient Number = ${searchpnum_inp} `;

			}
		}



		if (searchlname_inp) {
			if (appliedSearch) {
				appliedSearch += `| Patient Last Name = ${searchlname_inp}`

			} else {
				appliedSearch = `Patient Last Name = ${searchlname_inp} `;

			}
		}



		if (searchfname_inp) {
			if (appliedSearch) {
				appliedSearch += `| Patient First Name = ${searchfname_inp}`

			} else {
				appliedSearch = `Patient First Name = ${searchfname_inp} `;

			}
		}


		if (searchctmcodeId_inp) {
			if (appliedSearch) {
				appliedSearch += `| Case Type = ${searchctmcodeId_inp}`

			} else {
				appliedSearch = `Case Type = ${searchctmcodeId_inp} `;

			}
		}

		if (searchvtmcodeId_inp) {
			if (appliedSearch) {
				appliedSearch += `| Visit Type = ${searchvtmcodeId_inp}`

			} else {
				appliedSearch = `Visit Type = ${searchvtmcodeId_inp} `;

			}
		}

		if (searchttmcodeId_inp) {
			if (appliedSearch) {
				appliedSearch += `| Treatment Type = ${searchttmcodeId_inp}`

			} else {
				appliedSearch = `Treatment Type = ${searchttmcodeId_inp} `;

			}
		}


		if (searchnadate_inp) {
			if (appliedSearch) {
				appliedSearch += `| Next Action Date = ${searchnadate_inp}`

			} else {
				appliedSearch = `Next Action Date = ${searchnadate_inp} `;

			}
		}

		if (searchfromsubmitdate_inp) {
			if (appliedSearch) {
				appliedSearch += `| Submission Date Range = ${searchfromsubmitdate_inp}`

			} else {
				appliedSearch = `Submission Date Range = ${searchfromsubmitdate_inp} `;

			}
		}


		Swal.fire({
			title: 'Please Wait !',
			html: 'Creating Document',
			allowOutsideClick: false,
			showConfirmButton: false,
			// onBeforeOpen: () => {
			// 	Swal.showLoading()
			// },
		});

		$.ajax({
			type: "GET",
			url: "modules/treatmentsearch/printPdf.php",
			xhrFields: { responseType: "blob" },
			data: `printpdf=${is_pdf}&searchcnum=${searchcnum_inp}&searchcliniccode=${searchcliniccodeid_inp}&searchfromtreatmentdate=${searchfromtreatmentdate_inp}&searchtotreatmentdate=${searchtotreatmentdate_inp}&searchpnum=${searchpnum_inp}&searchlname=${searchlname_inp}&searchfname=${searchfname_inp}&searchctmcode=${searchctmcodeId_inp}&searchvtmcode=${searchvtmcodeId_inp}&searchttmcode=${searchttmcodeId_inp}&searchnadate=${searchnadate_inp}&searchfromsubmitdate=${searchfromsubmitdate_inp}&searchtosubmitdate=${searchtosubmitdate_inp}&searchsbmstatus=${searchsbmstatusId_inp}&total_rows=${total_rows}&appliedSearch=${appliedSearch}`,
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

				console.log("dfjsdhfjhdsjkhjkd", error);
			}
		});

	}
</script>

<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$userid = $_SESSION['user']['umid'];
$dbhandle = dbconnect();
$query = 'SELECT * FROM user_clinic_access WHERE ucaumid = ' . $userid;
$option1 = '';
$result = mysqli_query($dbhandle, $query);
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
	$query1 = "SELECT cmcnum, cmname FROM master_clinics WHERE cmcnum = '" . $row['ucacmcnum'] . "'";
	if (empty($_SESSION['useraccess']['clinics'][$row['ucacmcnum']])) {
		$result1 = mysqli_query($dbhandle, $query1);
		while ($row1 = mysqli_fetch_array($result1, MYSQLI_ASSOC)) {
			$option1 .= "<option value='" . $row1['cmcnum'] . "'>" . $row1['cmname'] . " (" . $row1['cmcnum'] . ")</option>";
		}
	}
}
// }
// Format Treatment Date Range Ymd
if (isset($_POST['searchfromtreatmentdate']) && !empty($_POST['searchfromtreatmentdate']))
	$_POST['searchfromtreatmentdate'] = date('m/d/Y', strtotime($_POST['searchfromtreatmentdate']));

if (isset($_POST['searchtotreatmentdate']) && !empty($_POST['searchtotreatmentdate']))
	$_POST['searchtotreatmentdate'] = date('m/d/Y', strtotime($_POST['searchtotreatmentdate']));

// Format Patient Number
if (isset($_POST['searchpnum']) && !empty($_POST['searchpnum'])) {
	//dumppost();
}

// Format Patient Last Name
//if(isset($_POST['searchlname']) && !empty($_POST['searchlname'])) {
//}

// Format Patient First Name
//if(isset($_POST['searchfname']) && !empty($_POST['searchfname'])) {
//}

// Format Case Types (default to last selected)
if (isset($_SESSION['casetypes'])) {
	foreach ($_SESSION['casetypes'] as $key => $val)
		$selectedcasetype[$key] = '';
}
if (isset($_POST['searchctmcode']) && !empty($_POST['searchctmcode']))
	$selectedcasetype[$_POST['searchctmcode']] = ' selected ';

// Format Visit Type (default to last selected)
if (isset($_SESSION['visittypes'])) {
	foreach ($_SESSION['visittypes'] as $key => $val)
		$selectedvisittype[$key] = '';
}
if (isset($_POST['searchvtmcode']) && !empty($_POST['searchvtmcode']))
	$selectedvisittype[$_POST['searchvtmcode']] = ' selected ';

// Format Treatment Init (default to last selected)
if (isset($_SESSION['treatmenttypes'])) {
	foreach ($_SESSION['treatmenttypes'] as $ttkey => $val)
		$selectedtreatmenttype["$ttkey"] = '';
}
if (isset($_POST['searchttmcode']) && !empty($_POST['searchttmcode']))
	$selectedtreatmenttype[$_POST['searchttmcode']] = ' selected ';

// Format Submit Dates Ymd
if (isset($_POST['searchfromsubmitdate']) && !empty($_POST['searchfromsubmitdate']))
	$_POST['searchfromsubmitdate'] = date('m/d/Y', strtotime($_POST['searchfromsubmitdate']));

if (isset($_POST['searchtosubmitdate']) && !empty($_POST['searchtosubmitdate']))
	$_POST['searchtosubmitdate'] = date('m/d/Y', strtotime($_POST['searchtosubmitdate']));
?>




<div class="containedBox" id="addBarForm">
	<fieldset>

		<legend class="boldLarger">Search Treatment Information working</legend>

		<table width="100%" border="1" cellspacing="0" cellpadding="3" class="main_table">
			<tr>
				<th class="clinic increase-width">Clinic</th>
				<?php if (isuserlevel(23) || isuserlevel(99) || isuserlevel(20)): ?>
					<th class="cliniccode">Clinic Code</th>
					<!-- <th>Business Unit</th> -->
				<?php endif; ?>
				<th colspan="2" class="treat">Treatment Date Range</th>
				<th class="treat1">Patient Number</th>
				<th class="treat2">Patient Last Name</th>
				<th class="treat3">Patient First Name</th>
				<th class="treat4">Case Type</th>
				<th class="treat5">Visit Type</th>
				<th class="treat6">Treatment Type</th>
				<th class="treat7">Next Action Date</th>
				<th colspan="2" class="treat8">Submission Date Range</th>
				<?php
				if (isuserlevel(20)) {
					?>
					<th class="treat9">Treatment Status</th>
					<?php
				}
				?>

			</tr>
			<tr>
				<!-- onfocus="down(this)" onblur="up(this)" onclick="up(this)" onfocusout="up(this)" -->
				<td class="clinic increase-width">
					<?php if (isuserlevel(23) || isuserlevel(99) || isuserlevel(20)): ?>
						<select name="searchcnum[]" id="searchcnum" size="1"
							style="position: static; width: 100%; overflow: unset;">
							<option value=""></option>
							<?php echo getSelectOptions($arrayofarrayitems = $_SESSION['useraccess']['clinics'], $optionvaluefield = 'cmcnum', $arrayofoptionfields = array('cmname' => ' (', 'cmcnum' => ')'), $defaultoption = $_POST['searchcnum'][0], $addblankoption = FALSE, $arraykey = '', $arrayofmatchvalues = array());
							?>
						</select>
					<?php else: ?>
						<select name="searchcnum[]" id="searchcnum">
							<option value=""></option>
							<?php $selectOption = getSelectOptions($arrayofarrayitems = $_SESSION['useraccess']['clinics'], $optionvaluefield = 'cmcnum', $arrayofoptionfields = array('cmname' => ' (', 'cmcnum' => ')'), $defaultoption = $_POST['searchcnum'], $addblankoption = FALSE, $arraykey = '', $arrayofmatchvalues = array());
							// if(!empty($_SESSION['useraccess']['clinics'])){
							echo $selectOption;
							// }else{
							echo $option1;
							// }
							?>
						</select>
					<?php endif; ?>
				</td>
				<?php if (isuserlevel(23) || isuserlevel(99) || isuserlevel(20)): ?>
					<td class="cliniccode">
						<input type="text" name="searchcliniccode[]" class="searchcliniccode " id="searchcliniccodeid"
							value="<?php if (isset($_POST['searchcliniccode'][0]))
								echo $_POST['searchcliniccode'][0]; ?>">
						<!-- <select id="searchpnum" name="searchbnum">
																																																																<option value="">All</option>
																																																																<option value="WS" <?php echo (isset($_POST['searchbnum']) && $_POST['searchbnum'] == 'WS') ? 'selected=selected' : ''; ?>>WS</option>
																																																																<option value="NET" <?php echo (isset($_POST['searchbnum']) && $_POST['searchbnum'] == 'NET') ? 'selected=selected' : ''; ?>>NET</option>
																																																															</select> -->
					</td>
				<?php endif; ?>
				<td nowrap="nowrap" class="normal treat" style="text-decoration:none">
					<input id="searchfromtreatmentdate" value="<?php if (isset($_POST['searchfromtreatmentdate']))
						echo $_POST['searchfromtreatmentdate']; ?>" type="text" size="10" maxlength="10" name="searchfromtreatmentdate">
					<img align="absmiddle" name="searchfromtreatmentdateClick" id="searchfromtreatmentdateClick"
						src="/img/calendar.gif" />
					<!-- <input
						id="searchfromtreatmentdate" name="searchfromtreatmentdate" type="text" size="10" maxlength="10"
						value="<?php if (isset($_POST['searchfromtreatmentdate']))
							echo $_POST['searchfromtreatmentdate']; ?>" onchange="validateDate(this.id)">
					<img align="absmiddle" name="searchfromtreatmentdate1" id="searchfromtreatmentdate1"
						src="/img/calendar.gif"
						onclick="cal.select(document.forms['searchForm'].searchfromtreatmentdate,'searchfromtreatmentdate1','MM/dd/yyyy'); return false;" /> -->
				</td>
				<td class="normal treat" nowrap="nowrap" style="text-decoration:none">
					<input id="searchtotreatmentdate" type="text" size="10" maxlength="10" value="<?php if (isset($_POST['searchtotreatmentdate']))
						echo $_POST['searchtotreatmentdate']; ?>" name="searchtotreatmentdate">
					<img align="absmiddle" name="searchtotreatmentdateClick" id="searchtotreatmentdateClick"
						src="/img/calendar.gif" />
					<!-- <input id="searchtotreatmentdate"
						name="searchtotreatmentdate" type="text" size="10" maxlength="10" value="<?php if (isset($_POST['searchtotreatmentdate']))
							echo $_POST['searchtotreatmentdate']; ?>" onchange="validateDate(this.id)">
					<img align="absmiddle" name="searchtotreatmentdate1" id="searchtotreatmentdate1"
						src="/img/calendar.gif"
						onclick="cal.select(document.forms['searchForm'].searchtotreatmentdate,'searchtotreatmentdate1','MM/dd/yyyy'); return false;" /> -->
				</td>
				<td class="treat1">
					<input id="searchpnum" name="searchpnum" type="text" size="6" maxlength="6" value="<?php if (isset($_POST['searchpnum']))
						echo $_POST['searchpnum']; ?>" />
					<!--				<select name="searchpnum" id="searchpnum" onchange="updatePatientInformation(this.id)">

						<?php //echo getSelectOptions($arrayofarrayitems=$_SESSION['useraccess']['patients'], $optionvaluefield='pnum', $arrayofoptionfields=array('pnum'=>' (', 'lname'=>', ', 'fname'=>') ', 'cnum'=>''), $defaultoption=$_POST['thpnum'], $addblankoption=TRUE, $arraykey="", $arrayofmatchvalues=array()); 
						?>
				</select>
-->
				</td>
				<td class="treat2"> <input id="searchlname" name="searchlname" type="text" size="10" maxlength="30"
						value="<?php if (isset($_POST['searchlname']))
							echo $_POST['searchlname']; ?>" onchange="upperCase(this.id)" <?php echo $namedisabled; ?>></td>
				<td class="treat3"><input id="searchfname" name="searchfname" type="text" size="10" maxlength="30"
						value="<?php if (isset($_POST['searchfname']))
							echo $_POST['searchfname']; ?>" onchange="upperCase(this.id)" <?php echo $namedisabled; ?>></td>
				<td class="treat4"><select class="selecthover" id="searchctmcodeId" name="searchctmcode" size="1"
						onfocus="down(this)" onblur="up(this)" onclick="up(this)" onfocusout="up(this)">
						<option label=""></option>
						<?php
						foreach ($_SESSION['casetypes'] as $key => $val)
							echo "<option " . $selectedcasetype[$key] . " value='" . $key . "'>" . $_SESSION['casetypes'][$key] . "</option>";
						?>
					</select></td>
				<td class="treat5"><select class="selecthover" name="searchvtmcode" id="searchvtmcodeId" size="1"
						onfocus="down(this)" onblur="up(this)" onclick="up(this)" onfocusout="up(this)">
						<option label=""></option>
						<?php foreach ($_SESSION['visittypes'] as $key => $val)
							echo "<option " . $selectedvisittype[$key] . " value='" . $key . "'>" . $_SESSION['visittypes'][$key] . "</option>";
						?>
					</select></td>
				<td class="treat6"><select class="selecthover" name="searchttmcode" id="searchttmcodeId" size="1"
						onchange="displayProceduresAndModalities(this.value);" onfocus="down(this)" onblur="up(this)"
						onclick="up(this)" onfocusout="up(this)">
						<option label=""></option>
						<?php foreach ($_SESSION['treatmenttypes'] as $key => $val)
							echo "<option " . $selectedtreatmenttype[$key] . " value='" . $key . "'>" . $_SESSION['treatmenttypes'][$key] . "</option>";
						?>
					</select></td>
				<!-- <input type="date" id="searchnadate"
						name="searchnadate"> -->
				<td nowrap="nowrap" class="normal treat7" style="text-decoration:none">
					<input id="searchnadate" type="text" size="10" maxlength="10" value="<?php if (isset($_POST['searchnadate']))
						echo $_POST['searchnadate']; ?>" name="searchnadate">
					<img align="absmiddle" name="searchnadateClick" id="searchnadateClick" src="/img/calendar.gif" />
					<!-- <input id="searchnadate"
						name="searchnadate" type="text" size="10" maxlength="10" value="<//?php if (isset($_POST['searchnadate']))
							echo $_POST['searchnadate']; ?>" onchange="validateDate(this.id)">
					<img align="absmiddle" name="searchnadate1" id="searchnadate1" src="/img/calendar.gif"
						onclick="cal.select(document.forms['searchForm'].searchnadate,'searchnadate1','MM/dd/yyyy'); return false;" /> -->
				</td>

				<td class="normal treat8" nowrap="nowrap" style="text-decoration:none">

					<input type="text" id="searchfromsubmitdate" name="searchfromsubmitdate" value="<?php if (isset($_POST['searchfromsubmitdate']))
						echo $_POST['searchfromsubmitdate']; ?>">
					<img align="absmiddle" name="searchfromsubmitdate1" id="searchfromsubmitdateClick"
						src="/img/calendar.gif" />
					<!-- <input id="searchfromsubmitdate"
						name="searchfromsubmitdate" type="text" size="10" maxlength="10" value="<//?php if (isset($_POST['searchfromsubmitdate']))
							echo $_POST['searchfromsubmitdate']; ?>" onchange="validateDate(this.id)">
					<img align="absmiddle" name="searchfromsubmitdate1" id="searchfromsubmitdate1"
						src="/img/calendar.gif"
						onclick="cal.select(document.forms['searchForm'].searchfromsubmitdate,'searchfromsubmitdate1','MM/dd/yyyy'); return false;" /> -->
				</td>
				<td class="normal treat8" nowrap="nowrap" style="text-decoration:none">
					<input id="searchtosubmitdate" type="text" size="10" maxlength="10" value="<?php if (isset($_POST['searchtosubmitdate']))
						echo $_POST['searchtosubmitdate']; ?>" name="searchtosubmitdate">
					<img align="absmiddle" name="searchtosubmitdateClick" id="searchtosubmitdateClick"
						src="/img/calendar.gif" />
					<!-- <input id="searchtosubmitdate"
						name="searchtosubmitdate" type="text" size="10" maxlength="10" value="<//?php if (isset($_POST['searchtosubmitdate']))
							echo $_POST['searchtosubmitdate']; ?>" onchange="validateDate(this.id)">
					<img align="absmiddle" name="searchtosubmitdate1" id="searchtosubmitdate1" src="/img/calendar.gif"
						onclick="cal.select(document.forms['searchForm'].searchtosubmitdate,'searchtosubmitdate1','MM/dd/yyyy'); return false;" /> -->
				</td>
				<?php
				if (isuserlevel(20)) {
					?>
					<td class="treat9">
						<select name="searchsbmstatus" onfocus="down(this)" id="searchsbmstatusId" onblur="up(this)"
							onclick="up(this)" onfocusout="up(this)">
							<option value=""></option>
							<option value="between 0 and 99" <?php if ($_POST['searchsbmstatus'] == "between 0 and 99")
								echo " selected"; ?>>Not Yet Submitted</option>
							<option value="between 100 and 199" <?php if ($_POST['searchsbmstatus'] == "between 100 and 199")
								echo " selected"; ?>>Treatments in UR</option>
							<option value="between 300 and 399" <?php if ($_POST['searchsbmstatus'] == "between 300 and 399")
								echo " selected"; ?>>Treatments in Patient Entry</option>
							<option value="between 500 and 599" <?php if ($_POST['searchsbmstatus'] == "between 500 and 599")
								echo " selected"; ?>>Treatments in Billing Entry</option>
							<option value="between 100 and 599" <?php if ($_POST['searchsbmstatus'] == "between 100 and 599")
								echo " selected"; ?>>Active Treatments</option>
							<option value="between 900 and 999" <?php if ($_POST['searchsbmstatus'] == "between 900 and 999")
								echo " selected"; ?>>Inactive Treatments</option>
							<option value="between 700 and 799" <?php if ($_POST['searchsbmstatus'] == "between 700 and 799")
								echo " selected"; ?>>Billed Treatments</option>
						</select>
					</td>
					<?php
				}
				?>
			</tr>
		</table>
		<div style="clear:both; margin:10px;">
			<div style="float:left">
				<input name="button[]" type="submit" id="treatmentSearch" value="Search" />
			</div>
			<div style="float:right">
				<input name="button[]" type="submit" value="Reset Search" />
			</div>
		</div>

		<div id="t123"></div>
	</fieldset>
	<!-- <input type = "text" id = "datepicker"> -->
</div>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script> -->

<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
<script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>

<script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



<!-- <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->
<style>
	.main_table td input,
	.main_table td select {
		width: 94%;
	}

	.main_table td {
		width: 7%;
	}

	.main_table td.increase-width {
		width: 20%;
	}

	.main_table td.reduce-width {
		width: 6%;
	}

	.main_table td.normal input {
		width: 78%;
	}

	.main_table td.normal.increase-width input {
		width: 90%;
	}

	td.reduce-width input {
		width: 70% !important;
	}

	td.clinic.increase-width span.select2 {
		width: 100% !important;
	}

	.select2-container--default .select2-selection--multiple .select2-selection__choice {
		white-space: inherit;
	}

	td.clinic span.select2-selection {
		border: solid #8a8a8a 1px;
		height: 21px;
		overflow: auto;
		border-radius: 2px;
		/*border: 1px solid #2c2c2c;*/
		font-size: 12px;
		min-height: 21px;
	}
</style>


<script>

</script>
<script>
	function down(what) {
		pos = jQuery(what).offset(); // remember position
		console.log(pos);
		jQuery(what).css("position", "absolute");
		// jQuery(what).offset(pos);   // reset position
		jQuery(what).attr("size", "8");
		jQuery(what).css("width", "19%");
		jQuery(what).css("overflow", "auto");
	}

	function up(what) {
		jQuery(what).css("position", "static");
		jQuery(what).attr("size", "1"); // close dropdown
		jQuery(what).css("width", "100%");
		jQuery(what).css("overflow", "unset");
	}

	$(document).ready(function () {
		$(".main_table td").hover(function () {
			$('.main_table tr').each(function () {
				$(this).find('td').each(function () {
					$(this).addClass("reduce-width");
				})
			})
			$(this).removeClass("reduce-width");
			$(this).addClass("increase-width");

		});
	});


	$(document).ready(function () {
		$('#searchcnum').focus();
		// $('#searchcnum').select2();
		// $('#searchcnum').select2('open');
		// $('#searchcnum').focus(function(){
		// 	$(this).select2('open');
		// });
		// 		jQuery("#hiddenField").datepicker({
		//     showOn: 'button',
		//     buttonText: 'Choose Date',
		//     dateFormat: 'dd/mm/yy',
		//     constrainInput: true
		// });

		// $("#hiddenField").datepicker("show");
		$(".main_table th").hover(function () {
			$('.main_table tr').each(function () {
				$(this).find('td').each(function () {
					$(this).addClass("reduce-width");
				})
			});

			$('.main_table th').each(function () {

				$(this).each(function () {
					$(this).removeClass("increase-width");
				})
			});

			$(this).find('td').removeClass("reduce-width");
			var className = '.' + $(this).attr('class');
			$()
			$(className).addClass("increase-width");
			$(className).removeClass("reduce-width");

		});
	});
</script>

<script>
	$(document).ready(function () {
		$('.main_table input,.main_table select').focus(function () {
			$('.main_table tr').each(function () {
				$(this).find('td').each(function () {
					$(this).addClass("reduce-width");
				})
			});
			$(this).parent().removeClass("reduce-width");
			$(this).parent().addClass("increase-width");
		});
		// $(".selecthover").focus(function () {
		// 	$('.main_table tr').each(function () {
		// 		$(this).find('td').each(function () {
		// 			$(this).addClass("reduce-width");
		// 		});
		// 	});
		// 	$(this).removeClass("reduce-width");
		// 	$(this).addClass("increase-width");

		// 	// $(".selecthover").attr("size", 1);u
		// });
	});
</script>

<script type="text/javascript">
	$(document).ready(function () {
		$("#searchfromsubmitdateClick").click(function () {
			$('#searchfromsubmitdate').focus();
		});


		$("#searchtosubmitdateClick").click(function () {
			$('#searchtosubmitdate').focus();
		});
		$("#searchnadateClick").click(function () {
			$('#searchnadate').focus();
		});


		$("#searchfromtreatmentdateClick").click(function () {
			$('#searchfromtreatmentdate').focus();
		});

		$("#searchtotreatmentdateClick").click(function () {
			$('#searchtotreatmentdate').focus();
		});




		$("#searchfromsubmitdate").datepicker({
			onClose: function () {
				this.focus();
			}
		});
		$("#searchtosubmitdate").datepicker({
			onClose: function () {
				this.focus();
			}
		});
		$("#searchnadate").datepicker({
			onClose: function () {
				this.focus();
			}
		});
		$("#searchfromtreatmentdate").datepicker({
			onClose: function () {
				this.focus();
			}
		});
		$("#searchtotreatmentdate").datepicker({
			onClose: function () {
				this.focus();
			}
		});





		// $(document).on('focus', '.selecthover', function (e) {

		// $(".selecthover").attr("size", 1);

		// })
		$(document).on('click', '.pagination-btn', function (e) {
			e.preventDefault();
			var query = $('#query').val();
			var newCount = $('#newCount').val();

			var pageNumber = $(this).find('a').text();

			let th = this;
			$.ajax({
				type: 'post',
				url: 'modules/treatmentsearch/searchResultAjax.php',
				data: {
					query: query,
					page_no: pageNumber,
					newCount: newCount
				},
				success: function (data) {

					// console.log("qwww", data);
					$('#resultdata').html(data);
					$('.pagination-btn').removeClass("active");
					$(th).addClass('active');
					// console.log("ggggg", );
					// var newnumrow = $("#numrowswww").val();
					// $('#numrowstodisplaywww').text(''); 
					// $('#numrowstodisplaywww').text(pageNumber); 

				}
			});
		});

	});
</script>

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script> -->
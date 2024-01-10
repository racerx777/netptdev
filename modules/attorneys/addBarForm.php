<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(23);
ini_set('memory_limit', '3000M');
ini_set('max_execution_time', 0);
// phpinfo();
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
	integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
	crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/js/all.min.js"
	integrity="sha512-2bMhOkE/ACz21dJT8zBOMgMecNxx0d37NND803ExktKiKdSzdwn+L7i9fdccw/3V06gM/DBWKbYmQvKMdAA9Nw=="
	crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<!-- 

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.min.css'> -->
</link>

<script type="text/javascript">

	function printPDFXLS(is_pdf = 0) {
		// $from = document.printApptForm.from;
		// $to = document.printApptForm.to;
		// $fromval = $from.value;
		// $toval = $to.value;
		// $detail = document.printApptForm.detail;
		// $summary = document.printApptForm.summary;
		// if($fromval == '' || $toval == ''){
		// 	if($fromval == ''){
		// 		document.querySelector('#from-error-msg').textContent = 'This Field is Required';
		// 	}else{
		// 		document.querySelector('#from-error-msg').textContent = '';
		// 	}
		// 	if($toval == ''){
		// 		document.querySelector('#to-error-msg').textContent = 'This Field is Required';
		// 	}else{
		// 		document.querySelector('#to-error-msg').textContent = '';
		// 	}
		// 	return;
		// }
		var firmname_inp = document.querySelector('#firmname').value;
		var fname_inp = document.querySelector('#fname').value;
		var lname_inp = document.querySelector('#lname').value;
		var city_inp = document.querySelector('#city').value;
		var zip_inp = document.querySelector('#zip').value;
		var total_rows = document.querySelector('#total_rows').value;

		let appliedSearch = "";
		if (firmname_inp) {
			appliedSearch = `Firm Name = ${firmname_inp} `;
		}
		if (fname_inp) {
			if (appliedSearch) {
				appliedSearch += `| First Name = ${fname_inp}`

			} else {
				appliedSearch = `First Name = ${fname_inp} `;

			}
		}
		if (lname_inp) {
			if (appliedSearch) {
				appliedSearch += `| Last Name = ${lname_inp}`;
			} else {
				appliedSearch = `Last Name = ${lname_inp} `;

			}
		}
		if (city_inp) {
			if (appliedSearch) {
				appliedSearch += `| City Name = ${city_inp}`;
			} else {
				appliedSearch = `City Name = ${fname_inp} `;

			}

		} if (zip_inp) {
			if (appliedSearch) {
				appliedSearch += `| Zip Code = ${zip_inp}`;
			} else {
				appliedSearch = `Zip Code = ${zip_inp} `;

			}
		}

		if (is_pdf) {
			// 	// $url = "/modules/attorneysreport/printPdf.php?from=" + $from.value + "&to=" + $to.value + "&summary=" + $summary.value + "&detail=" + $detail.value+"&printpdf="+is_pdf;
			// 	// $url = "/modules/attorneysreport/printPdf.php?from=" + $from.value + "&to=" + $to.value +"&printpdf="+is_pdf;
			// 	$url = "/modules/attorneys/printPdf.php?printpdf=" + is_pdf + "&firmname=" + $firmname_inp + "&fname=" + $fname_inp + "&lname=" + $lname_inp + "&city=" + $city_inp + "&zip=" + $zip_inp;

			var printpdf = `printpdf=${is_pdf}&firmname=${firmname_inp}&fname=${fname_inp}&lname=${lname_inp}&city=${city_inp}&zip=${zip_inp}`;
			// `&firmname=${firmname_inp}&fname=${fname_inp}&lname=${lname_inp}&city=${city_inp}&ascDesc=${zip}&sorting=${zip_inp 	}`
			// Swal.fire(
			// 	'Creating Document',
			// 	buttons: false,
			// )

			// Swal.fire({
			//   type: 'success',
			//   title: 'Creating Document',
			//   showConfirmButton: false,
			// })



			Swal.fire({
				title: 'Please Wait !',
				html: 'Creating Document',// add html attribute if you want or remove
				allowOutsideClick: false,
				onBeforeOpen: () => {
					Swal.showLoading()
				},
			});

			$.ajax({
				type: "GET",
				url: "modules/attorneys/printPdf.php",
				data: `printpdf=${is_pdf}&firmname=${firmname_inp}&fname=${fname_inp}&lname=${lname_inp}&city=${city_inp}&zip=${zip_inp}&total_rows=${total_rows}&appliedSearch=${appliedSearch}`,
				xhrFields: {
					responseType: 'blob' // to avoid binary data being mangled on charset conversion
				},
				success: function (data) {
					console.log("data", data);
					let blob = new Blob([data], { type: 'arraybuffer' });
					let link = document.createElement('a');
					let objectURL = window.URL.createObjectURL(blob);
					link.href = objectURL;
					link.target = '_self';
					link.download = "attorneysreport.pdf";
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

				}
			});


			// 	var newWindow = window.open($url);
			// 	newWindow.swal("Have a nice day!");
			// 	alert("File downloaded to your computer.");
			// 	// window.open($url);

		} else {
			// $url = "/modules/attorneys/printXLS.php?firmname=" + $firmname_inp + "&fname=" + $fname_inp + "&lname=" + $lname_inp + "&city=" + $city_inp + "&zip=" + $zip_inp;
			// 	// alert("Working");
			// 	window.open($url);
			var printpdf = `printpdf=${is_pdf}&firmname=${firmname_inp}&fname=${fname_inp}&lname=${lname_inp}&city=${city_inp}&zip=${zip_inp}`;

			// var newWindow = window.open($url);
			// newWindow.alert('Creating Document');
			// alert("File downloaded to your computer.");




			Swal.fire(
				'Creating Document',
			)

			$.ajax({
				type: "GET",
				url: "modules/attorneys/printXLS.php",
				data: `firmname=${firmname_inp}&fname=${fname_inp}&lname=${lname_inp}&city=${city_inp}&zip=${zip_inp}&total_rows=${total_rows}&appliedSearch=${appliedSearch}`,
				xhrFields: {
					responseType: 'arraybuffer' // to avoid binary data being mangled on charset conversion
				},
				success: function (data) {
					var blob = new Blob([data], { type: 'application/vnd.ms-excel' });
					var downloadUrl = URL.createObjectURL(blob);
					var a = document.createElement("a");
					a.href = downloadUrl;
					a.download = "attorneysreport.xls";
					document.body.appendChild(a);
					a.click();
					Swal.fire(
						'File downloaded to your computer.',
					)
				}
			});




		}
	}
</script>
<!-- Loader Css -->
<style>
	.loader {
		border: 16px solid #f3f3f3;
		border-radius: 50%;
		border-top: 16px solid #3498db;
		width: 120px;
		height: 120px;
		-webkit-animation: spin 2s linear infinite;
		/* Safari */
		animation: spin 2s linear infinite;
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

	.loader {
		text-align: center;
	}
</style>


<div class="containedBox">
	<fieldset class="search-box">
		<legend style="font-size:large">Search Attorney Information</legend>
		<form method="post" name="addForm" id="searchform">
			<table width="100%" border="1" cellspacing="0" cellpadding="3">
				<tr>
					<th>Firm Name</th>
					<th>Attorney First Name</th>
					<th>Last Name</th>
					<th>City</th>
					<th>Zip</th>
				</tr>
				<tr>
					<td><input class="enter" id="firmname" name="firmname" type="text" size="20" maxlength="30"></td>
					<td><input class="enter" id="fname" name="fname" type="text" size="20" maxlength="30"></td>
					<td><input class="enter" id="lname" name="lname" type="text" size="20" maxlength="30"></td>
					<td><input class="enter" id="city" name="city" type="text" size="20" maxlength="20"></td>
					<td><input class="enter" id="zip" name="zip" type="text" size="20" maxlength="20"></td>
					<input type="hidden" name="main" value="1">
				</tr>
				<tr>
					<td colspan="6">
						<div>
							<div style="float:left;">
								<input name="searchformsub" type="button" id="searchformsub" value="Search" />
							</div>
							<div style="float:right;">
								<input name="searchformadd" type="button" id="searchformadd" value="Add" />
								<button id="show-all" style="display: none;">Show All</button>
							</div>
						</div>
					</td>
				</tr>
			</table>
		</form>
	</fieldset>
</div>
<fieldset id="searchField-new" style="display:none">
	<legend style="font-size:large;">Search Results</legend>
	<form method="post" name="searchResults">

		<div id="append-table-new"></div>

		<!-- <div class="loader"></div> -->
	</form>
</fieldset>
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
<script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
	$('.enter').keypress(function (e) {
		var key = e.which;
		if (key == 13) {
			$('#searchformsub').click();
			return false;
		}
	});
	$('#show-all').click(function (e) {
		e.preventDefault();
		if ($('#append-table').length) {
			$('#append-table').html('');
		} else {
			$('#append-table-new').html('');
		}
		$('.loader').show();
		var pageId = $(this).attr("data-pageid");
		if (pageId != '' || pageId != undefined) {
			pageId == pageId;
		} else {
			pageId == 1;
		}
		$.ajax({
			type: 'post',
			url: 'modules/attorneys/addBarFormAjax.php',
			data: { showall: '1', pageno: pageId },
			success: function (data) {
				if ($('#append-table').length) {
					$('#append-table').html(data);
					$('.loader').hide();
				} else {
					$('#append-table-new').html(data);
					$('.loader').hide();
				}
			}
		});
	});
	// $(document).ready(function () {


	// 	$('#asdf').click(function (e) {
	// 		alert("fddfd");
	// 	});
	// });


	$('#searchformsub').click(function (e) {
		e.preventDefault();
		if ($('#append-table').length) {
			$('#append-table').html('');
		} else {
			$('#append-table-new').html('');
		}
		$('.loader').show();
		var pageId = $(this).attr("data-pageid");
		if (pageId != '' || pageId != undefined) {
			pageId == pageId;
		} else {
			pageId == 1;
		}
		$.ajax({
			type: 'post',
			url: 'modules/attorneys/addBarFormAjax.php',
			data: $('#searchform').serialize(),
			success: function (data) {
				if ($('#append-table').length) {
					$('#append-table').html(data);
					$('.loader').hide();
				} else {
					$('#append-table-new').html(data);
					$('.loader').hide();
				}
			}
		});
	});



	$(document).on('click', '#attorney-table th', function (e) {
		e.preventDefault();
		// alert($(this).attr("id"));
		let allValue = $(this).attr("id");
		let sortingBy = "";
		let ascDesc = "";
		let sorting = $("#sorting").val();
		let newSort = ""
		// if(sorting == "" && allValue == "firmName"){
		// 	newSort = "DSCE";
		// }if(sorting == "" && allValue != "firmName"){
		// 	newSort = "ASC";
		// }
		console.log("sortingsorting", sorting);

		if (sorting == "ASC") {
			newSort = "DSCE";
		} if (sorting == "DSCE") {
			newSort = "ASC";
		}

		// console.log("sorting", newSort);
		// console.log("allValue", allValue);

		if (allValue == "firmName") {


			sortingBy = "attorney_firm.firm_name";
			ascDesc = "firmName";
		}
		if (allValue == "firstName") {
			sortingBy = "attorney.name_first";
			ascDesc = "firstName";

		} if (allValue == "middleName") {
			sortingBy = "attorney.name_middle";
			ascDesc = "middleName";

		} if (allValue == "lastName") {
			sortingBy = "attorney.name_last";
			ascDesc = "lastName";

		} if (allValue == "address") {
			sortingBy = "attorney.address";
			ascDesc = "address";

		} if (allValue == "city") {
			sortingBy = "attorney.city";
			ascDesc = "city";

		} if (allValue == "state") {
			sortingBy = "attorney.state";
			ascDesc = "state";

		} if (allValue == "zip") {
			sortingBy = "attorney.zip";
			ascDesc = "zip";

		} if (allValue == "phone") {
			sortingBy = "attorney.phone";
			ascDesc = "phone";

		} if (allValue == "email") {
			sortingBy = "attorney.email";
			ascDesc = "email";

		}

		var query = $("#prepareQuery").val();
		var pageId = $("#pagenoss").val();

		if ($('#append-table').length) {
			$('#append-table').html('');
		} else {
			$('#append-table-new').html('');
		}
		$('.loader').show();
		// var pageId = $(this).attr("data-pageid");
		console
			.log("pageId", pageId);
		if (pageId != '' || pageId != undefined) {
			pageId == pageId;
		} else {
			pageId == 1;
		}
		// {main:'1',pageno:pageId},
		// $('#searchform').serialize(), + "&pageno="+pageId + "&main="+1,
		// var ab = 1;
		$.ajax({
			type: 'post',
			url: 'modules/attorneys/addBarFormAjax.php',
			data: $('#searchform').serialize() + `&main=1&pageno=${pageId}&query=${query}&sortingBy=${sortingBy}&ascDesc=${ascDesc}&sorting=${newSort}&allValue=${allValue}`,
			success: function (data) {
				// $('#append-table').html(data);


				if (data.r == 0) {
					$('#append-table').html("");
					$('.loader').hide();
				}

				if ($('#append-table').length) {
					$('#append-table').html(data);
					$('.loader').hide();
				} else {
					$('#append-table-new').html(data);
					$('.loader').hide();
				}
			}
		});




	});

	$(document).on('click', '.page-link-main', function (e) {
		e.preventDefault();
		let allValue = $("#allValue").val();
		let sorting = $("#sortingsss").val();
		var query = $("#prepareQuery").val();
		if ($('#append-table').length) {
			$('#append-table').html('');
		} else {
			$('#append-table-new').html('');
		}
		$('.loader').show();
		var pageId = $(this).attr("data-pageid");
		console
			.log("pageId", pageId);
		if (pageId != '' || pageId != undefined) {
			pageId == pageId;
		} else {
			pageId == 1;
		}
		console.log("query", query);

		let sortingBy = "";
		let ascDesc = "";

		if (allValue == "firmName") {


			sortingBy = "attorney_firm.firm_name";
			ascDesc = "firmName";
		}
		if (allValue == "firstName") {
			sortingBy = "attorney.name_first";
			ascDesc = "firstName";

		} if (allValue == "middleName") {
			sortingBy = "attorney.name_middle";
			ascDesc = "middleName";

		} if (allValue == "lastName") {
			sortingBy = "attorney.name_last";
			ascDesc = "lastName";

		} if (allValue == "address") {
			sortingBy = "attorney.address";
			ascDesc = "address";

		} if (allValue == "city") {
			sortingBy = "attorney.city";
			ascDesc = "city";

		} if (allValue == "state") {
			sortingBy = "attorney.state";
			ascDesc = "state";

		} if (allValue == "zip") {
			sortingBy = "attorney.zip";
			ascDesc = "zip";

		} if (allValue == "phone") {
			sortingBy = "attorney.phone";
			ascDesc = "phone";

		} if (allValue == "email") {
			sortingBy = "attorney.email";
			ascDesc = "email";

		}



		var ab = 1;
		$.ajax({
			type: 'post',
			url: 'modules/attorneys/addBarFormAjax.php',
			// contentType: "text/html; charset=UTF-8",     
			data: $('#searchform').serialize() + `&main=1&pageno=${pageId}&query=1&sortingBy=${sortingBy}&ascDesc=${ascDesc}&sorting=${sorting}&allValue=${allValue}`,
			success: function (data) {
				// $('#append-table').html(data);

				console.log("data", data);
				if (data.r == 0) {
					$('#append-table').html("");
					$('.loader').hide();
				}

				if ($('#append-table').length) {
					$('#append-table').html(data);
					$('.loader').hide();
				} else {
					$('#append-table-new').html(data);
					$('.loader').hide();
				}
			}
		});
	});









	$(document).on('click', '.page-link', function (e) {
		e.preventDefault();
		if ($('#append-table').length) {
			$('#append-table').html('');
		} else {
			$('#append-table-new').html('');
		}
		$('.loader').show();
		var pageId = $(this).attr("data-pageid");
		if (pageId != '' || pageId != undefined) {
			pageId == pageId;
		} else {
			pageId == 1;
		}
		$.ajax({
			type: 'post',
			url: 'modules/attorneys/addBarFormAjax.php',
			data: { showall: '1', pageno: pageId },
			success: function (data) {
				// $('#append-table').html(data);

				if ($('#append-table').length) {
					$('#append-table').html(data);
					$('.loader').hide();
				} else {
					$('#append-table-new').html(data);
					$('.loader').hide();
				}
			}
		});
	});

	// $('#searchformsub').click(function(e){
	// 	e.preventDefault();

	// 	$('.loader').show();

	// 		$.ajax({
	// 		type: 'post',
	// 		dataType: 'json',
	// 		url: 'modules/attorneys/addBarFormAjax.php',
	// 		data: $('#searchform').serialize(),
	// 		success: function (data) {
	// 			console.log("data", data);
	// 			if(data.r == 0){
	// 				jQuery("#show-all").trigger('click');
	// 			}
	// 			else {
	// 				// jQuery('.pagination').remove();
	// 				if(data.numRows == 0){
	// 					if ( $('#append-table').length){
	// 						$('#append-table').html('There are no records found for your specific search term(s).');
	// 						$('.loader').hide();
	// 					}else{
	// 						$('#append-table-new').html('There are no records found for your specific search term(s).');
	// 						$('.loader').hide();
	// 					}
	// 				}
	// 				else{


	// 					// $('#append-table').text(data.rowsCount);

	// 					$('#records-no').text(data.numRows);
	// 					jQuery("#attorney-table").find('tr').remove();
	// 					var tableData =  data.rowsCount+ ' Attorneys(s) found.' + '<div style="height: 30px;float: right;width: 45px;"><img src="/img/icon-pdf.png" onClick="return printPDFXLS(1)" style="position: absolute;cursor: pointer;">&nbsp;&nbsp;<img src="/img/icon-xls.png" style="position: absolute;margin-left: 25px;cursor: pointer;" onClick="return printPDFXLS()"></div><table width="100%" cellspacing="0" cellpadding="3" border="1"><tbody><tr><th>Firm Name</th><th>First Name</th><th>Middle Name</th><th>Last Name</th><th>Address</th><th>Suite #</th><th>city</th><th>State</th><th>Zip</th><th>Phone</th><th>Email</th><th>Functions</th></tr>';
	// 					$.each(data.row, function(i, item) {

	// 						var btn_val = data.row[i].btnTxt;
	// 						var btn_title = "";
	// 						var btn_color = "";
	// 						if(btn_val == 'Reactivate'){

	// 							btn_title = 'title="This attorney is currently at status Inactive."';
	// 							btn_color = 'style="background-color:#ff9ba3;"';
	// 						}
	// 						tableData += '<tr '+btn_color+'><td>'+data.row[i].firm_name+'</td><td>'+data.row[i].name_first+'</td><td>'+data.row[i].name_middle+'</td><td>'+data.row[i].name_last+'</td><td>'+data.row[i].address+'</td><td>'+data.row[i].address2+'</td><td>'+data.row[i].city+'</td><td>'+data.row[i].state+'</td><td>'+data.row[i].zip+'</td><td>'+data.row[i].phone+'</td><td>'+data.row[i].email+'</td><td><input name="button['+data.row[i].id+']" type="submit" value="Edit"><input class="btn-delete" '+btn_title+' name="btn-delete" type="button" data-id="'+data.row[i].id+'" style="margin-left:10px;" value="'+btn_val+'"></td></tr>';
	// 					});
	// 					tableData+="</tbody></table>"
	// 					if ( $('#append-table').length){
	// 						$('#append-table').html(tableData);
	// 						$('.loader').hide();
	// 					}else{
	// 						$('#append-table-new').html(tableData);
	// 						$('.loader').hide();
	// 					}
	// 				}
	// 			}
	// 		}
	// 	});
	// });



	$('#searchformadd').click(function (e) {
		var firmadd = $('#firmname').val();
		if (firmadd.length == 0) {
			alert('Search for Firm Name first, If attorney is not found, click this "Add" button again.');
			return false;
		}
		else {
			$('.loader').show();
			$.ajax({
				type: 'post',
				dataType: 'json',
				url: 'modules/attorneys/addBarFormAjax.php',
				data: { checkfirm: firmadd },
				success: function (data) {
					if (data.res == 0) {
						$('.search-box').hide();
						var form = '<div class="centerFieldset" style="margin-top:100px;"> <form class="add-form" action="" id="add-form"> <fieldset style="width: 80%;"> <legend>Add Reffering Attorney Data</legend><span id="error"></span><div style="height: 30px;float: right;width: 45px;"><img src="/img/icon-pdf.png" onClick="return printPDFXLS(1)" style="position: absolute;cursor: pointer;">&nbsp;&nbsp;<img src="/img/icon-xls.png" style="position: absolute;margin-left: 25px;cursor: pointer;" onClick="return printPDFXLS()"></div> <table> <tbody> <tr> <td>Firm Name</td> <td><input name="add-firm" class="add-firm" type="text" value="' + data.firm + '" id="add-firmname"><span class="add-firm-msg" style="color:red; display:none;">This field is required</span><input type="hidden" name="firmid"></td> </tr> <tr> <td>First Name </td> <td><input name="add-name_first" class="add-firstname" id="add-fname" type="text"><span class="add-fn-msg" style="color:red; display:none;">This field is required</span><input type="hidden" name="uid"></td> </tr> <tr> <td>Middle Name </td> <td><input name="add-name_middle" class="add-middlename" id="mname" type="text"></td> </tr> <tr> <td>Last Name </td> <td><input name="add-name_last" class="add-lastname" type="text" id="add-lname"><span class="add-ln-msg" style="color:red; display:none;">This field is required</span></td> </tr> <tr> <td>Address</td> <td><input name="add-address" class="add-address" type="text" id="address"></td> </tr> <tr> <td>Suite Number</td> <td><input name="add-address2" class="add-address2" type="text" id="address2"></td> </tr> <tr> <td>City</td> <td><input name="add-city" class="add-city" type="text" id="add-city"><span class="add-city-msg" style="color:red; display:none;">This field is required</span></td> </tr> <tr> <td>State</td> <td><input name="add-state" class="add-state" type="text" id="state"></td> </tr> <tr> <td>Zip</td> <td><input name="add-zip" class="add-zip" id="add-zip" type="text"><span class="add-zip-msg" style="color:red; display:none;">This field is required</span></td> </tr> <tr> <td>Phone </td> <td><input name="add-phone" class="add-phone" id="add-phone" type="phone"><span class="add-phone-msg" style="color:red; display:none;">Phone number is not valid</span> </td> </tr> <tr> <td>Email </td> <td><input name="add-email" class="add-email" type="text" id="add-email"><span class="add-email-msg" style="color:red; display:none;">Email is not valid</span></td> </tr> </tbody> </table> <div class="containedBox"> <div style="float:left; margin:10px;"><input type="hidden" name="paid_id" value="paid_"><input type="hidden" class="add_id" name="add_id"><input type="hidden" name="attorney_form" value="Add"><input type="button" class="modal-close cancel-btn" value="Cancel"></div> <div style="float:left; margin:10px;"><input type="button" name="add" id="add-attorney" value="Add Attorney"></div> </div> </fieldset> </form></div>';
						jQuery('#searchField').html(form);
						$('.loader').hide();
					}
					if (data == 1) {
						alert('Record Found With This Firm Name Please Choose Another One!!!');
						$('.loader').hide();
					}
				}
			});
		}
	});
	$(document).ready(function () {
		$(document).on("blur", ".add-address2", function () {
			changeText1();
		});
		function changeText1() {
			var str = $('.add-address2').val()
			str = str.replace(/\s/g, '');
			str = str.toUpperCase();
			str = str.replace('SUITE', '#').replace('STE', '#').replace('STE.', '#').replace('ROOM', '#').replace('APARTMENT', '#').replace('APT', '#').replace('BLDG', '#').replace('BLDG.', '#').replace('FLOOR', '#').replace('FL', '#').replace('UNIT', '#').replace('SLIP', '#').replace('##', '#').replace('###', '#');
			$('.add-address2').val(str);
		}
		$(document).on("click", "#add-attorney", function () {
			if ($('#add-fname').val().length === 0) {
				$("#add-fname").next('span').show();
			} else {
				$('#add-fname').next('span').hide();
			}
			if ($('#add-lname').val().length === 0) {
				$('#add-lname').next('span').show();
			} else {
				$('#add-lname').next('span').hide();
			}
			if ($('#add-firmname').val().length === 0) {
				$('#add-firmname').next('span').show();
			} else {
				$('#add-firmname').next('span').hide();
			}
			if ($('#add-city').val().length === 0) {
				$('#add-city').next('span').show();
			} else {
				$('#add-city').next('span').hide();
			}
			if ($('#add-zip').val().length === 0) {
				$('#add-zip').next('span').show();
			} else {
				$('#add-zip').next('span').hide();
			}
			if ($('#add-phone').val().length === 0) {
				$('#add-phone').next('span').show();
			} else {
				$('#add-phone').next('span').hide();
			}
			if ($('#add-email').val().length === 0) {
				$('#add-email').next('span').show();
			} else {
				var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
				if (testEmail.test($('#add-email').val())) {
					$('#add-email').next('span').hide();
				} else {
					$('#add-email').next('span').show();
					return;
				}
			}
			if (($('#add-fname').val().length !== 0) && ($('#add-lname').val().length !== 0) && ($('#add-firmname').val().length !== 0) && ($('#add-city').val().length !== 0) && ($('#add-zip').val().length !== 0) && ($('#add-phone').val().length !== 0) && ($('#add-email').val().length !== 0)) {
				changeText1();
				$.ajax({
					type: 'post',
					dataType: 'json',
					url: 'modules/attorneys/addBarFormAjax.php',
					data: $('#add-form').serialize(),
					success: function (data) {
						if (data == true) {
							$.ajax({
								type: 'post',
								dataType: 'json',
								url: 'modules/attorneys/addBarFormAjax.php',
								data: { newInsert: 'new' },
								success: function (data) {
									// if(data==true)
									// {
									var tableData = '<div style="height: 30px;float: right;width: 45px;"><img src="/img/icon-pdf.png" onClick="return printPDFXLS(1)" style="position: absolute;cursor: pointer;">&nbsp;&nbsp;<img src="/img/icon-xls.png" style="position: absolute;margin-left: 25px;cursor: pointer;" onClick="return printPDFXLS()"></div><table width="100%" cellspacing="0" cellpadding="3" border="1"><tbody><tr><th>Firm Name</th><th>First Name</th><th>Middle Name</th><th>Last Name</th><th>Address</th><th>Suite #</th><th>city</th><th>State</th><th>Zip</th><th>Phone</th><th>Email</th><th>Functions</th></tr>';
									$.each(data.row, function (i, item) {

										var btn_val = data.row[i].btnTxt;
										var btn_title = "";
										var btn_color = "";
										if (btn_val == 'Reactivate') {
											btn_title = 'title="This attorney is assigned to a case so deletion is not possible."';
											btn_color = 'style="background-color:#ff9ba3;"';
										}
										tableData += '<tr ' + btn_color + '><td>' + data.row[i].firm_name + '</td><td>' + data.row[i].name_first + '</td><td>' + data.row[i].name_middle + '</td><td>' + data.row[i].name_last + '</td><td>' + data.row[i].address + '</td><td>' + data.row[i].address2 + '</td><td>' + data.row[i].city + '</td><td>' + data.row[i].state + '</td><td>' + data.row[i].zip + '</td><td>' + data.row[i].phone + '</td><td>' + data.row[i].email + '</td><td><input name="button[' + data.row[i].id + ']" type="submit" value="Edit"><input class="btn-delete" ' + btn_title + ' name="btn-delete" type="button" data-id="' + data.row[i].id + '" style="margin-left:10px;" value="' + btn_val + '"></td></tr>';
									});
									tableData += "</tbody></table>"
									$('#append-table-new').html(tableData);
									$('.search-box').show();
									$('#searchField').hide();
									$('#searchField-new').show();
								}
							});
						}
					}
				});
			}
		});
		$(document).on("click", ".cancel-btn", function () {
			$('.attorney-tab').trigger('click');
		});
	});

	$(document).on("click", ".btn-delete", function (e) {
		e.preventDefault();
		var id = $(this).attr('data-id');
		$.ajax({
			type: 'post',
			url: 'modules/attorneys/addBarFormAjax.php',
			data: { delete: id },
			success: function (data) {
				if (data == 'true') {
					alert('Users Is Deleted!!!');
				}
				else if (data == 'false') {
					alert('Users Is Not Deletable!');
				}
				else {

					alert(data);
				}
			}
		});
	});
</script>
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(23);
?>
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"> -->


<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"> -->

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
    integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/js/all.min.js"
    integrity="sha512-2bMhOkE/ACz21dJT8zBOMgMecNxx0d37NND803ExktKiKdSzdwn+L7i9fdccw/3V06gM/DBWKbYmQvKMdAA9Nw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>



    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.6/js/jquery.dataTables.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.6/css/jquery.dataTables.min.css">



<style>
    .tooltip {
        position: relative;
        cursor: pointer;
    }

    .tooltiptext {
        visibility: hidden;
        width: 200px;
        background-color: #333;
        color: #fff;
        text-align: center;
        border-radius: 5px;
        padding: 5px;
        position: absolute;
        z-index: 1;
        bottom: 125%;
        left: 50%;
        transform: translateX(-50%);
        opacity: 0;
        transition: opacity 0.3s;
    }

    .tooltip:hover .tooltiptext {
        visibility: visible;
        opacity: 1;
    }
</style>

<script type="text/javascript">
    function printPDFXLS() {

        $ficd10codes = document.querySelector('#ficd10codes').value;
        $icd10description = document.querySelector('#icd10description').value;


        $url = "/modules/icdcodes/printXLS.php?ficd10codes=" + $ficd10codes + "&icd10description=" + $icd10description;
        window.open($url);
        // if (is_pdf) {
        //     // $url = "/modules/attorneysreport/printPdf.php?from=" + $from.value + "&to=" + $to.value + "&summary=" + $summary.value + "&detail=" + $detail.value+"&printpdf="+is_pdf;
        //     // $url = "/modules/attorneysreport/printPdf.php?from=" + $from.value + "&to=" + $to.value +"&printpdf="+is_pdf;
        //     $url = "/modules/attorneys/printPdf.php?printpdf=" + is_pdf + "&firmname=" + $firmname_inp + "&fname=" + $fname_inp + "&lname=" + $lname_inp + "&city=" + $city_inp + "&zip=" + $zip_inp;
        //     window.open($url);
        // } else {
        //     $url = "/modules/attorneys/printXLS.php?firmname=" + $firmname_inp + "&fname=" + $fname_inp + "&lname=" + $lname_inp + "&city=" + $city_inp + "&zip=" + $zip_inp;
        //     window.open($url);
        // }
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

    #append-table {
        position: relative;
        margin-top: 5px;
    }
</style>
<!-- <style>
.tooltip {
  position: relative;
  cursor: pointer;
}

.tooltiptext {
  visibility: hidden;
  width: 200px;
  background-color: #333;
  color: #fff;
  text-align: center;
  border-radius: 5px;
  padding: 5px;
  position: absolute;
  z-index: 1;
  bottom: 125%;
  left: 50%;
  transform: translateX(-50%);
  opacity: 0;
  transition: opacity 0.3s;
}

.tooltip:hover .tooltiptext {
  visibility: visible;
  opacity: 1;
}
</style> -->
<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
    }

    /* The Modal (background) */
    .modal {
        display: none;
        /* Hidden by default */
        position: fixed;
        /* Stay in place */
        z-index: 1;
        /* Sit on top */
        padding-top: 50px;
        /* Location of the box */
        left: 0;
        top: 0;
        width: 100%;
        /* Full width */
        height: 100%;
        /* Full height */
        overflow: auto;
        /* Enable scroll if needed */
        background-color: rgba(0, 0, 0, 0.4);
        /* Black w/ opacity */
    }

    /* Modal Content */
    .modal-content {
        background-color: #fefefe;
        margin: auto;
        padding: 20px;
        border: 1px solid #888;
        width: 60%;
        /* Adjust the modal width as needed */
    }

    /* The Close Button */
    .close {
        color: #aaaaaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }
</style>
<style>
    /* Custom styles for the form and input fields */
    .modal-content form {
        margin-bottom: 20px;
    }

    .modal-content label {
        font-weight: bold;
        display: block;
    }

    .modal-content .form-group {
        margin-bottom: 20px;
    }

    .modal-content input[type="text"],
    .modal-content input[type="email"],
    .modal-content textarea {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
        /* Reduced font size */
    }

    .modal-content .btn-primary,
    .modal-content .btn-secondary {
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }

    .modal-content .btn-primary {
        background-color: #007bff;
        color: #fff;
        border: none;
        margin-right: 10px;
        /* Add some spacing between buttons */
    }

    .modal-content .btn-primary:hover {
        background-color: #0056b3;
    }

    .modal-content .btn-secondary {
        background-color: #ccc;
        color: #333;
    }

    /* Close button (X) style */
    .modal-header .close {
        color: #aaa;
        font-size: 28px;
        font-weight: bold;
        opacity: 1;
    }

    .modal-header .close:hover,
    .modal-header .close:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }
</style>


<!-- Trigger/Open The Modal -->
<!-- <button id="myBtn">Open Modal</button> -->
<input type="hidden" class="form-control" id="newusescount" name="newusescount">
<!-- The Modal -->
<div id="myModal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <span class="close" id="closeModal">&times;</span>
        <h2>Update ICD Code</h2>

        <form>
            <div class="form-group" style="width: 98%;">
                <!-- <label for="inputField">Input Field:</label> -->
                <input type="text" class="form-control" id="inputField" name="inputField">&nbsp;
                <input type="text" class="form-control" id="editUsesCount" name="editUsesCount">
                <input type="hidden" class="form-control" id="inputFieldid" name="inputFieldid">

            </div>
            <!-- You can add more input fields as needed -->
            <button type="button" class="btn btn-primary  edit-imc-code">Submit</button>
            <button type="button" class="btn btn-secondary" id="cancelMyModal">Cancel</button>

        </form>
    </div>
</div>


<div id="myModalCount" class="modal">
    <!-- Modal content -->
    <div class="modal-content" style=" width: 18% ">
        <span class="close" id="closeModal">&times;</span>
        <h2>Update Uses Count</h2>

        <form>
            <div class="form-group">
                <!-- <label for="inputField">Input Field:</label> -->
                <input type="number" class="form-control" id="usescount" name="usescount">
                <input type="hidden" class="form-control" id="usescountid" name="usescountid">

            </div>
            <!-- You can add more input fields as needed -->
            <button type="button" class="btn btn-primary  edit-uses-count">Submit</button>
            <button type="button" class="btn btn-secondary" id="myModalCountCancel">Cancel</button>

        </form>
    </div>
</div>


<div id="addModal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <span class="close" id="closeModaladd">&times;</span>
        <h2>Add ICD10 </h2>
        <form>
            <div class="form-group" style="width: 98%;">


                <label for="inputField">ICD10 Codes</label>
                <input type="text" class="form-control" id="addIcdCode" name="addIcdCode">

                <label for="inputField">ICD10 Description</label>
                <input type="text" class="form-control" id="addCodeDescription" name="addCodeDescription">

                <input type="hidden" class="form-control" id="inputFieldid" name="inputFieldid">

            </div>
            <!-- You can add more input fields as needed -->
            <button type="button" class="btn btn-primary add-imc-code">Submit</button>
            <button type="button" class="btn btn-secondary" id="cancelAddModel">Cancel</button>

        </form>
    </div>
</div>

<script>
    // JavaScript to handle modal interactions
    // document.getElementById('myBtn').addEventListener('click', function () {
    //     document.getElementById('myModal').style.display = 'block';
    // });

    document.getElementById('closeModal').addEventListener('click', function () {
        // document.getElementById('myModal').style.display = 'none';

        $('#myModal').modal('hide');

    });

    document.getElementById('closeModaladd').addEventListener('click', function () {
        // document.getElementById('myModal').style.display = 'none';

        $('#addModal').modal('hide');

    });

    // Close the modal when clicking outside of it (optional)
    window.addEventListener('click', function (event) {
        var modal = document.getElementById('myModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    });
</script>

<div class="containedBox">
    <fieldset class="search-box">
        <legend style="font-size:large">Search ICD10 codes </legend>
        <form method="post" name="addForm" id="searchform">
            <table width="100%" border="1" cellspacing="0" cellpadding="3">
                <tr>
                    <th>ICD10 codes</th>
                    <th>ICD10 description</th>
                </tr>

                <tr>
                    <td><input class="enter" id="ficd10codes" name="ficd10codes" type="text" size="20" maxlength="30"
                            style="width: 98%;">
                    </td>
                    <td><input class="enter" id="icd10description" name="icd10description" type="text" size="20"
                            maxlength="30" style="width: 98%;"></td>
                    <input type="hidden" name="main" value="1">
                </tr>
                <tr>
                    <td colspan="6">
                        <div>
                            <div style="float:left;">
                                <input name="searchformsub" type="button" id="searchformsub" value="Search"
                                    style="width: 100px;" />
                                <input name="clearfilter" type="button" id="clearfilter" value="Clear Filter"
                                    style="width: 100px;" />

                            </div>
                            <div style="float:right;">
                                <input name="searchformadd" type="button" id="searchformadd" value="Add"
                                    style="width: 100px;" />
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
    <legend style="font-size:large;">Icd Codes Results</legend>
    <form method="post" name="searchResults">
        <div id="append-table-new"></div>
        <!-- <div class="loader"></div> -->
    </form>
</fieldset>
<!-- <script src="https://code.jquery.com/jquery-3.6.0.js"></script> -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>

<script>

    $(document).on('click', '#searchformadd', function (event) {


        let Searchdescription = $('#icd10description').val();
        let Searchid = $('#ficd10codes').val();

        console.log("Searchdescription", Searchdescription)
        console.log("Searchid", Searchid)

        if (Searchdescription || Searchid) {
            $('#addCodeDescription').val(Searchdescription);
            $('#addIcdCode').val(Searchid);

        }
if(Searchdescription == ""){
    $('#addCodeDescription').val("");
}
if(Searchid == ""){
    $('#addIcdCode').val(Searchid);
}


        $('#addModal').modal('show');


    });

    $(document).on('click', '.edit-usescount', function (event) {

        var usesCount = $(this).data('usescount');
        var usescountid = $(this).data('id');



        $('#myModalCount').modal('show');
        $('#usescount').val(usesCount);
        $('#usescountid').val(usescountid);






    });



    $(document).on('click', '#myModalCountCancel', function (event) {



        $('#myModalCount').modal('hide');



    });

    $(document).on('click', '#cancelAddModel', function (event) {


        $('#addModal').modal('hide');



    });


    $(document).on('click', '#cancelMyModal', function (event) {



        $('#myModal').modal('hide');



    });


    $(window).on("load", function () {
        $.ajax({
            type: 'post',
            url: 'modules/icdcodes/addBarFormAjax.php',
            data: { showall: '1', pageno: 1 },
            success: function (data) {
                getDataCount();

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
</script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    console.log("working in javascript")
    $(document).ready(function () {
        $(document).on('click', '.edit-button', function (event) {
            var dataDescription = $(this).data('description');
            var dataid = $(this).data('id');
           
            $('#myModal').modal('show');
            $('#inputField').val(dataDescription);
            $('#inputFieldid').val(dataid);
            $("#inputField").focus();
            var usescountid = $(this).data('usescount');
        $('#editUsesCount').val(usescountid);

        });
    });


    $(document).on('click', '#clearfilter', function (event) {

        getData();
        getDataCount();
        $("#ficd10codes").val("")
        $("#icd10description").val("")

    });


    $(document).on('click', '.edit-imc-code', function (event) {

        let description = $('#inputField').val();
        let id = $('#inputFieldid').val();
        let usescount = $('#editUsesCount').val();

        if (description && id) {
            $.ajax({
                type: 'post',
                url: 'modules/icdcodes/addBarFormAjax.php',
                data: { update: '1', code: id, usescount: usescount ,description: description },
                success: function (data) {
                    console.log(data)
                    if (data) {
                        $('#searchformsub').click();
                        // getData();
                        $('#myModal').modal('hide');

                        // Swal.fire(
                        //     // 'Good job!',
                        //     'Record successfully updated!',

                        // )

                    }
                }
            });
        }


    })

    $(document).on('click', '.edit-uses-count', function (event) {

        let usescount = $('#usescount').val();
        let usescountid = $('#usescountid').val();


        if (usescount) {
            $.ajax({
                type: 'post',
                url: 'modules/icdcodes/addBarFormAjax.php',
                data: { usescountupdate: '1', usescount: usescount, usescountid: usescountid },
                success: function (data) {
                    console.log(data)
                    if (data) {
                        getData();
                        $('#myModalCount').modal('hide');

                        Swal.fire(
                            // 'Good job!',
                            'Record successfully updated!',

                        )

                    }
                }
            });
        }


    })


    $('#searchformsub').click(function (e) {
        e.preventDefault();
        $('.loader').show();

        let res1 = $("#ficd10codes").val().trim();
        let res2 = $("#icd10description").val().trim();

        if (res1 === '' && res2 === '') {
            getData()
            getDataCount();
        } else {


            $.ajax({
                type: 'post',
                dataType: 'json',
                url: 'modules/icdcodes/addBarFormAjax.php',
                data: $('#searchform').serialize(),
                success: function (data) {
                    if (data.r == 0) {
                        jQuery("#show-all").trigger('click');
                    }
                    else {
                        jQuery('.pagination').remove();
                        if (data.numRows == 0) {
                            if ($('#append-table').length) {
                                $('#records-no').text(0);

                                $('#append-table').html('There are no records found for your specific search term(s).');
                                $('.loader').hide();
                            } else {
                                $('#records-no').text(0);

                                $('#append-table-new').html('There are no records found for your specific search term(s).');

                                $('.loader').hide();
                            }
                        }
                        else {
                            $('#records-no').text(data.numRows);
                            jQuery("#attorney-table").find('tr').remove();
                            // var tableData = '<div style="height: 30px;float: right;width: 45px;"><img src="/img/icon-pdf.png" onClick="return printPDFXLS(1)" style="position: absolute;cursor: pointer;">&nbsp;&nbsp;<img src="/img/icon-xls.png" style="position: absolute;margin-left: 25px;cursor: pointer;" onClick="return printPDFXLS()"></div><table width="100%" cellspacing="0" cellpadding="3" border="1"><tbody><tr><th>ICD10 Code</th><th>Imdx Description</th><th>Actions</th></tr>';

                            var tableData = '<div style="float: right; width: 50px; margin-bottom: 25px; position: absolute; right: 0px;top: -30px;float: right;width: 45px;"><img src="/img/icon-xls.png" style="position: absolute;margin-left: 25px;cursor: pointer;" title="Download as Excel File" onClick="return printPDFXLS()"></div><table width="100%" cellspacing="0" id="icdcode-table"  cellpadding="3" border="1"><tbody><tr><th id="icdcode" style="position: relative; cursor: pointer;">ICD10 Code</th><th>Imdx Description</th>  <th class="tooltip" id="usesCount"> <i class="fas fa-question-circle"></i><span class="tooltiptext">Highest Uses Count determines which ICD codes will be displayed as the 100 "Most Used Codes" (First drop down when selecting ICD codes for  prescriptions).</span> Uses Count<i class="fas fa-sort-asc" aria-hidden="true"></i><th>Actions</th></tr>';
                            $.each(data.row, function (i, item) {

                                // console.log("data", data)
                                var btn_val = data.row[i].btnTxt;
                                var btn_title = "";
                                var btn_color = "";
                                if (btn_val == 'Reactivate') {

                                    btn_title = 'title="This attorney is currently at status Inactive."';
                                    btn_color = 'style="background-color:#ff9ba3;"';
                                }


                                tableData += '<tr ' + btn_color + '><td>' + data.row[i].imicd9 + '</td><td>' + data.row[i].imdx + '</td><td>' + (data.row[i].imicdCount ? data.row[i].imicdCount : 0) + '</td><td><input name="button[' + data.row[i].imicd9 + ']" type="button" data-description="' + data.row[i].imdx + '" data-usescount="' + data.row[i].imicdCount + '" data-id="' + data.row[i].imicd9 + '" class="edit-button" value="Edit"><input class="btn-delete"  name="btn-delete" type="button" data-id="' + data.row[i].imicd9 + '" style="margin-left:10px;" value="Delete"></td></tr>';

                                // tableData += '<tr ' + btn_color + '><td>' + data.row[i].imicd9 + '</td><td>' + data.row[i].imdx + '</td><td>' + data.row[i].imicdCount + '</td><td><input name="button[' + data.row[i].imicd9 + ']" type="button" data-description="' + data.row[i].imdx + '" data-id="' + data.row[i].imicd9 + '" class="edit-button" value="Edit"><input class="btn-delete"  name="btn-delete" type="button" data-id="' + data.row[i].imicd9 + '" style="margin-left:10px;" value="Delete"></td></tr>';
                            });

                            tableData += "</tbody></table>"
                            if ($('#append-table').length) {
                                $('#append-table').html(tableData);
                                $('.loader').hide();
                            } else {
                                $('#append-table-new').html(tableData);
                                $('.loader').hide();
                            }
                        }
                    }
                }
            });
        }
    });

    $(document).on('click', '#icdcode-table th', function (e) {
        e.preventDefault();
        let allValue = $(this).attr("id");
        let sorting = $("#sortingsss").val();
        let newSort = ""


        if (allValue == "usesCount") {
            if (sorting == "ASC") {
                newSort = "DESC";
            } if (sorting == "DESC") {
                newSort = "ASC";
            }
        }

        if (allValue == "icdcode") {
            if (sorting == "ASC") {
                newSort = "DESC";
            } if (sorting == "DESC") {
                newSort = "ASC";
            }
        }

        // if (allValue == "description") {
        //     if (sorting == "ASC") {
        //         newSort = "DESC";
        //     } if (sorting == "DESC") {
        //         newSort = "ASC";
        //     }
        // }

//         newSort ASC
// (index):1049 sorting DESC
// (index):1050 allValue usesCount
// (index):1051 newusescount ASC
        

        var query = $("#prepareQuery").val();

        // if ($('#append-table').length) {
        // 	$('#append-table').html('');
        // } else {
        // 	$('#append-table-new').html('');
        // }
        // $('.loader').show();

        if (sorting == undefined) {
            let newSort2 = $("#newusescount").val();
            console.log("newSort2dddd", newSort2)
            sorting = newSort2
            if (newSort2 == "ASC") {
                newSort = "DESC";
            }else if (newSort2 == "DESC") {
                newSort = "ASC";
            }else{
                 newSort = "ASC"
            }
            
        }
        
        // else{
        //     newSort = "ASC"
        // }

        // console.log("newSort", newSort)

        console.log("sorting", sorting)
        console.log("newSort", newSort)

        // console.log("allValue", allValue)
        // console.log("newusescount", $("#newusescount").val())
if(allValue == "usesCount" || allValue == "icdcode"){
    var newData;

if (allValue === "icdcode") {
    newData = $('#searchform').serialize() + `&main=1&query=${query}&sorting=${newSort}&allValue=${allValue}`;
} else if (allValue === "usesCount") {
    newData = $('#searchform').serialize() + `&main=1&query=${query}&sorting=${newSort}&allValue=${allValue}`;
}

    $.ajax({
            type: 'post',
            url: 'modules/icdcodes/addBarFormAjax.php',
            dataType: 'json',
            data: newData,
            success: function (data) {


                if (data.r == 0) {
                    // jQuery("#show-all").trigger('click');
                }
                else {
                    jQuery('.pagination').remove();
                    if (data.numRows == 0) {
                        if ($('#append-table').length) {
                            $('#records-no').text(0);

                            $('#append-table').html('There are no records found for your specific search term(s).');
                            $('.loader').hide();
                        } else {
                            $('#records-no').text(0);

                            $('#append-table-new').html('There are no records found for your specific search term(s).');

                            $('.loader').hide();
                        }
                    }
                    else {
                        $('#newusescount').val(data.hiddeninput)
                        $('#records-no').text(data.numRows);
                        jQuery("#attorney-table").find('tr').remove();
                        // var tableData = '<div style="height: 30px;float: right;width: 45px;"><img src="/img/icon-pdf.png" onClick="return printPDFXLS(1)" style="position: absolute;cursor: pointer;">&nbsp;&nbsp;<img src="/img/icon-xls.png" style="position: absolute;margin-left: 25px;cursor: pointer;" onClick="return printPDFXLS()"></div><table width="100%" cellspacing="0" cellpadding="3" border="1"><tbody><tr><th>ICD10 Code</th><th>Imdx Description</th><th>Actions</th></tr>';

                        if(allValue == "icdcode"){

                            var tableData = '<div style="float: right; width: 50px; margin-bottom: 25px; position: absolute; right: 0px;top: -30px;float: right;width: 45px;"><img src="/img/icon-xls.png" style="position: absolute;margin-left: 25px;cursor: pointer;" title="Download as Excel File" onClick="return printPDFXLS()"></div><table width="100%" cellspacing="0" id="icdcode-table"  cellpadding="3" border="1"><tbody><tr><th id="icdcode" style="position: relative; cursor: pointer;">ICD10 Code <i  class="fas fa-sort-asc" aria-hidden="true"></i></th><th>Imdx Description</th>      <th class="tooltip" id="usesCount"> <i class="fas fa-question-circle"></i><span class="tooltiptext">Highest Uses Count determines which ICD codes will be displayed as the 100 "Most Used Codes" (First drop down when selecting ICD codes for  prescriptions).</span> Uses Count <i id="changeasc" class="fas fa-sort-desc" aria-hidden="true"></i><th>Actions</th></tr>';
                        }


                        if(allValue == "usesCount"){

var tableData = '<div style="float: right; width: 50px; margin-bottom: 25px; position: absolute; right: 0px;top: -30px;float: right;width: 45px;"><img src="/img/icon-xls.png" style="position: absolute;margin-left: 25px;cursor: pointer;" title="Download as Excel File" onClick="return printPDFXLS()"></div><table width="100%" cellspacing="0" id="icdcode-table"  cellpadding="3" border="1"><tbody><tr><th id="icdcode" style="position: relative; cursor: pointer;">ICD10 Code  <i  class="fas fa-sort-desc" aria-hidden="true"></i></th><th>Imdx Description</th>      <th class="tooltip" id="usesCount"> <i class="fas fa-question-circle"></i><span class="tooltiptext">Highest Uses Count determines which ICD codes will be displayed as the 100 "Most Used Codes" (First drop down when selecting ICD codes for  prescriptions).</span> Uses Count<i id="changeasc" class="fas fa-sort-asc" aria-hidden="true"></i><th>Actions</th></tr>';
}

                        
            
                        $.each(data.row, function (i, item) {

                            var btn_val = data.row[i].btnTxt;
                            var btn_title = "";
                            var btn_color = "";
                            if (btn_val == 'Reactivate') {

                                btn_title = 'title="This attorney is currently at status Inactive."';
                                btn_color = 'style="background-color:#ff9ba3;"';
                            }


                            tableData += '<tr ' + btn_color + '><td>' + data.row[i].imicd9 + '</td><td>' + data.row[i].imdx + '</td><td>' + (data.row[i].imicdCount ? data.row[i].imicdCount : 0) + '</td><td><input name="button[' + data.row[i].imicd9 + ']" type="button" data-description="' + data.row[i].imdx + '" data-usescount="' + data.row[i].imicdCount + '" data-id="' + data.row[i].imicd9 + '" class="edit-button" value="Edit"><input class="btn-delete"  name="btn-delete" type="button" data-id="' + data.row[i].imicd9 + '" style="margin-left:10px;" value="Delete"> </td></tr>';

                            // tableData += '<tr ' + btn_color + '><td>' + data.row[i].imicd9 + '</td><td>' + data.row[i].imdx + '</td><td>' + data.row[i].imicdCount + '</td><td><input name="button[' + data.row[i].imicd9 + ']" type="button" data-description="' + data.row[i].imdx + '" data-id="' + data.row[i].imicd9 + '" class="edit-button" value="Edit"><input class="btn-delete"  name="btn-delete" type="button" data-id="' + data.row[i].imicd9 + '" style="margin-left:10px;" value="Delete"></td></tr>';
                        });

                        tableData += "</tbody></table>"
                        if ($('#append-table').length) {

                            $('#append-table').html(tableData);
                            $('.loader').hide();

                            addClassAscDesc(newSort ,allValue)

                        } else {
                            $('#append-table-new').html(tableData);
                            $('.loader').hide();
                        }
                    }
                }
            }
        });

}

    });

function addClassAscDesc(sorting ,allValue){
    var iconElement = $('#changeasc');
    
if(sorting == "ASC"){
// Remove the 'fa-sort-asc' class
iconElement.removeClass('fa-sort-desc');

// Add the 'fa-sort-desc' class
iconElement.addClass('fa-sort-asc');
}else{
   // Remove the 'fa-sort-asc' class
iconElement.removeClass('fa-sort-asc');

// Add the 'fa-sort-desc' class
iconElement.addClass('fa-sort-desc'); 
}
if(allValue =="icdcode"){
    $("#icd10").removeClass("fas fa-sort-desc").addClass("fa-sort-asc");

}

}

    $(document).on('click', '.add-imc-code', function (event) {

        let description = $('#addCodeDescription').val();
        let id = $('#addIcdCode').val();
        if (description && id) {
            $.ajax({
                type: 'post',
                url: 'modules/icdcodes/addBarFormAjax.php',
                data: { insert: '1', code: id, description: description },
                success: function (response) {
                    const data = JSON.parse(response);
              
                    if (data.status == 200) {
                        getData();
                        $('#addModal').modal('hide');
                        $('#addCodeDescription').val("");
                        $('#addIcdCode').val("");
                        Swal.fire(
                            // 'Good job!',
                            'Record added successfully!',

                        )

                    }else if(data.status == 400){
                        alert(data.message)
                    }
                    
                    else {
                        alert("Somthing went wrong!")
                    }
                }
            });
        } else {
            alert("Description and Codes fileds are requierd!")
        }





    })


    function getData() {
        $.ajax({
            type: 'post',
            url: 'modules/icdcodes/addBarFormAjax.php',
            data: { showall: '1', pageno: 1 },
            success: function (data) {
                // console.log("data", data)
                if ($('#append-table').length) {
                    $('#append-table').html(data);
                    $('.loader').hide();
                } else {
                    $('#append-table-new').html(data);
                    $('.loader').hide();
                }
            }
        });
    }


    function getDataCount() {
        $.ajax({
            type: 'post',
            url: 'modules/icdcodes/addBarFormAjax.php',
            data: { count: '1' },
            success: function (data) {
                console.log("data count", typeof data)
                let stringWithoutQuotes = data.replace(/"/g, '');
                $('#records-no').text(parseInt(stringWithoutQuotes));


            }
        });
    }

    $('.enter').keypress(function (e) {
        console.log("sdjkdjksdjksjd")
        var key = e.which;
        if (key == 13) {
            $('#searchformsub').click();
            return false;
        }
    });








    $('#show-all').click(function (e) {
        e.preventDefault();
        $('.loader').show();
        var pageId = $(this).attr("data-pageid");
        if (pageId != '' || pageId != undefined) {
            pageId == pageId;
        } else {
            pageId == 1;
        }

        $.ajax({
            type: 'post',
            url: 'modules/icdcodes/addBarFormAjax.php',
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
    $(document).on('click', '.page-link', function (e) {
        e.preventDefault();
        $('.loader').show();
        var pageId = $(this).attr("data-pageid");
        if (pageId != '' || pageId != undefined) {
            pageId == pageId;
        } else {
            pageId == 1;
        }
        $.ajax({
            type: 'post',
            url: 'modules/icdcodes/addBarFormAjax.php',
            data: { showall: '1', pageno: pageId },
            success: function (data) {
                // $('#append-table').html(data);
                getDataCount()

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




    $(document).on("click", ".btn-delete", function (e) {
        e.preventDefault();
        var id = $(this).attr('data-id');

        Swal.fire({

  text: `Are you sure you want to permanently delete ICD Code ${id}`,
  showCancelButton: true, // Add Cancel button
  showConfirmButton: true, // Add OK button
  cancelButtonText: 'Cancel', // Change the text of the Cancel button
  confirmButtonText: 'OK',   // Change the text of the OK button
}).then((result) => {
  if (result.isConfirmed) {
   $.ajax({
            type: 'post',
            url: 'modules/icdcodes/addBarFormAjax.php',
            data: { delete: id },
            success: function (data) {
                if (data == 'true') {
                    getData();
                    Swal.fire('ICD10 Code Is Deleted!', '', 'success');
                }
                else if (data == 'false') {
                    alert('Something went wrong');
                }
                else {

                    alert(data);
                }
            }
        });
   
  } else if (result.dismiss === Swal.DismissReason.cancel) {
    // Cancel button was clicked
    // Swal.fire('You clicked Cancel!', '', 'error');
  }
});
     
    });
</script>
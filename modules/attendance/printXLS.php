<?php
include('pdftemplate.php');
header ( "Content-type: application/vnd.ms-excel" );
header ( "Content-Disposition: attachment; filename=attendancereport.xls" );
echo $html;
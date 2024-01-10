<?php
include('pdftemplate.php');
header ( "Content-type: application/vnd.ms-excel" );
header ( "Content-Disposition: attachment; filename=schedulingperformancereport.xls" );
echo $html;
<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();
$html = '';
$sql=array();

if (!empty($_REQUEST['ficd10codes'])) {
    $sql[] = "master_ICD9.imicd9 LIKE '%" . $_REQUEST['ficd10codes'] . "%'";
}
if (!empty($_REQUEST['icd10description'])) {
    $sql[] = "master_ICD9.imdx LIKE '%" . $_REQUEST['icd10description'] . "%'";
}

if(!empty($sql)){
	$count=0;
	foreach ($sql as $keyword) {
		if($count==0)
		{
			$query.=$keyword;
		}
		else
		{
			$query.=' AND '.$keyword;
		}
		$count++;
	}
	$prepare = "SELECT * FROM master_ICD9 WHERE $query";
}else{
	$prepare = "SELECT * FROM master_ICD9 ";
}
// <div style="float:left"><img src="wsptn logo bw outline.jpg" width="300px"></div>

$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Attorneys Report</title>
</head>
<style>
#customers {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td, #customers th {
  padding: 8px;
  text-align: left; 
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #4682B4;
  color: white;
}
</style>
<body>
<div style="float:right">
	<h1>ICD10 codes</h1>
	<p>Date: '.date("m/d/Y").'</p>
</div>
<div style="clear:both;">';
if($result = mysqli_query($dbhandle,$prepare)) {
	$html .= '<table id="customers" style="border-collapse: collapse; border: solid; margin-right: 20px; width: 100%;" >
				<tr>
					<th style="font-size: 10px">imicd9</th>
					<th style="font-size: 10px">imdx</th>
		
				</tr>';

	while($row=mysqli_fetch_array($result,MYSQLI_ASSOC)) {
		$html .='<tr style="border-collapse: collapse; border: solid; border-bottom-color: #CCCCCC;">
				<td style="font-size: 10px; width: 2%" >'.$row['imicd9'].'&nbsp;&nbsp;</td>
				<td style="font-size: 10px; width: 2%" >'.$row['imdx'].'&nbsp;&nbsp;</td>
		</tr>';
	}
	$html .= '</table>';
	$html .= '<br />';
}

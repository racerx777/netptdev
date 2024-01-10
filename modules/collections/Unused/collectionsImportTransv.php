<?php
set_time_limit(0);
ignore_user_abort();
$script_path='/home/wsptn/public_html/netpt';
require_once($script_path . '/common/session.php');

require_once($script_path . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


$fh = fopen($script_path."/collections/net/transv.txt", 'r');
$srcfields=fgetcsv($fh,9999,"|");
fclose($fh);
$dstfields=array(
'bnum', 'pnum', 'date', 'code', 'descrip', 'amount', 'therap', 'billed', 'visit', 'acctype', 'ipayed', 'ppayed', 'credit', 'dr1', 'dr2', 'dr3', 'dr4', 'dr5', 'dr6', 'dr7', 'dr8', 'dr9', 'drc1', 'drc2', 'drc3', 'drc4', 'drc5', 'drc6', 'drc7', 'drc8', 'drc9', 'cnum', 'inscd'
);
$dftfields['bnum']=$BNUM;
foreach($dstfields as $id=>$dstfield) { 
	if( ($key = array_search($dstfield, $srcfields))===FALSE) 
		$mapfields["$dstfield"]="Not Mapped"; // zero means it's in the db but not mapped from data
	else
		$mapfields["$dstfield"]=$key;
}
$reads=0;
$inserts=0;
$updates=0;
$insertsbad=0;
$updatesbad=0;
echo("<PRE>");
$selectquery="
	SELECT recno, bnum, importdata 
	FROM ptos_transv_import
	LIMIT 500000
";
if($selectresult=mysqli_query($dbhandle,$selectquery)) {
	while($row=mysqli_fetch_assoc($selectresult)) {
		$recno=$row['recno'];
		$BNUM=$row['bnum'];
		$read=explode("|",$row['importdata']);
		$reads++;
		if(($reads % 100000)==0)
			echo "$reads<br>";
		$transv=cleanfields($mapfields, $read, $dftfields);
		$transv['bnum']=$BNUM;
		$fields=array_keys($transv);
		$fields=implode(", ", $fields);
		$values=array();
		foreach($transv as $field=>$value) 
			$values[]="'".mysqli_real_escape_string($dbhandle,$value)."'";
		$sqlvalues=implode(", ", $values);
		$query="
			INSERT INTO ptos_transv ($fields) VALUES($sqlvalues)
		";
//echo("query:".$query);
		if($result=mysqli_query($dbhandle,$query)) {
			$inserts++;
			$deletequery="
				DELETE FROM ptos_transv_import WHERE recno='$recno'
			";
			if($deleteresult=mysqli_query($dbhandle,$deletequery)) 
				$deletes++;
			else
				$deletesbad++;
		}
		else
			$insertsbad++;
	}
	echo("</PRE>");
	echo("$BNUM Records Read: $reads<br>");
	echo("$BNUM Records Inserted: $inserts ($insertsbad)<br>");
	echo("$BNUM Records Deleted: $deletes ($deletesbad)<br>");
	echo('<a href="./collectionsImportTransv.php">Click Here to coninue importing records into NetPT 500K at a time.</a><br>');
}
else
	echo("$selectquery<br>".mysqli_error($dbhandle));

function cleanfields($mapfields, $data, $dftfields) {
	$array=array();
	foreach($mapfields as $field=>$index) {
		if($index==="Not Mapped")
			$array["$field"]=$dftfields["$field"];
		else {
			$value=$data[$index];
			$value=trim($value);
			$value=strtoupper($value);
			$array["$field"]=$value;
		}
	}
	return($array);
}
?>
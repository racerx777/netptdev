<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(11); 
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


// Select Active Patients List Records
$clinicsarray = $_SESSION['useraccess']['clinics'];
$clinicslistarray = array();
foreach($clinicsarray as $key=>$val) 
	$clinicslistarray[] = $val['cmcnum'];
$clinicslist = "('" . implode("', '", $clinicslistarray) . "')";

$namesquery  = "
SELECT bnum1 as bnum, cnum1 as cnum, pnum1 as pnum, sum(TESTCNUM) as TOTALCNUMERRORS, sum(TESTLNAME) as TOTALLNAMEERRORS, sum(TESTFNAME) as TOTALFNAMEERRORS, sum(TESTREVERSED) as TOTALREVERSEDERRORS, sum(TESTCNUM+TESTLNAME+TESTFNAME+TESTREVERSED-(2*TESTREVERSED)) as TOTALERRORS
FROM (
	SELECT bnum1, cnum1, pnum1,
		CASE 
		WHEN cnum1<>thcnum or cnum1<>crcnum THEN 1
		ELSE 0
		END AS TESTCNUM,
		CASE
		WHEN lname1<>thlname or lname1<>crlname THEN 1
		ELSE 0
		END AS TESTLNAME,
		CASE
		WHEN fname1<>thfname or fname1<>crfname THEN 1
		ELSE 0
		END AS TESTFNAME,
		CASE
		WHEN fname1=thlname and lname1=thfname THEN 1
		ELSE 0
		END AS TESTREVERSED
	FROM (
		SELECT 
			bnum COLLATE latin1_swedish_ci as bnum1,
			cnum COLLATE latin1_swedish_ci as cnum1,
			pnum COLLATE latin1_swedish_ci as pnum1,
			lname COLLATE latin1_swedish_ci as lname1,
			fname COLLATE latin1_swedish_ci as fname1
		FROM 
			ptos_pnums 
		WHERE 
			lvisit >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) 
		) as active_patients
		LEFT JOIN treatment_header on pnum1=thpnum
		LEFT JOIN (
			SELECT crcnum, crpnum, crlname, crfname FROM cases WHERE crpnum<>''
		) as active_cases
		ON pnum1=crpnum
		WHERE thsbmstatus < '700'
	) as active_patient_errors
GROUP BY bnum1, cnum1, pnum1
HAVING TOTALERRORS > 0
ORDER BY bnum1, cnum1, pnum1
";
if($namesresult = mysqli_query($dbhandle,$namesquery)) {
	$numRows = mysqli_num_rows($namesresult);
	if($numRows > 0) {
    ?>
<div class="containedBox">
	<fieldset>
	<legend style="font-size:large">PTOS NetPT Information Issues</legend>
	<form method="post" name="duplicateList">
		<table border="1" cellpadding="3" cellspacing="0" width="100%">
			<tr>
				<th rowspan="2">PNUM</th>
				<th rowspan="2">Business</th>
				<th rowspan="2">Source</th>
				<th colspan="8">&nbsp;</th>
				<th rowspan="2" colspan="5">Information</th>
			</tr>
			<tr style="color:#FFFFFF; background-color:#4682B4;">
				<th>Clinic</th>
				<th>First Name</th>
				<th>Last Name</th>
				<th>Date</th>
				<th>Code</th>
				<th>Code</th>
				<th>Code</th>
				<th>Status</th>
			</tr>
	<?php
		while($row = mysqli_fetch_assoc($namesresult)) {
			unset($netpterror);
				unset($cnum);
				unset($lname);
				unset($fname);
			$pnum=$row['pnum'];

// Get PTOS PNUMS RECORD
			$ptosquery = "SELECT bnum, cnum, pnum, lname, fname FROM ptos_pnums WHERE pnum='$pnum' ";
			if($ptosresult=mysqli_query($dbhandle,$ptosquery)) {
				$ptosnumrows=mysqli_num_rows($ptosresult); 
				if($ptosrow=mysqli_fetch_assoc($ptosresult)) {
					if($pnum != $lastrowid) {
						$toggle++;
						$lastrowid = $pnum;
					}
					if(($toggle % 2) == 1)
						$redstyle = ' style="background-color:#FFFFFF;"';
					else
						$redstyle = ' style="background-color:#D3D3D3;"';
//							if(!empty($row['TOTALCNUMERRORS']))
//								$cnum='style="color:#000000; background-color:#FF0000;"';
//							else
//								$cnum='style="color:#999999"';
//				
//							if(!empty($row['TOTALLNAMEERRORS']))
//								$lname='style="color:#000000; background-color:#FF0000;"';
//							else
//								$lname='style="color:#999999"';
//				
//							if(!empty($row['TOTALFNAMEERRORS']))
//								$fname='style="color:#000000; background-color:#FF0000;"';
//							else
//								$fname='style="color:#999999"';
//				
//							if(!empty($row['TOTALREVERSEDERRORS'])) {
//								$lname='style="color:#000000; background-color:#00FFFF;"';
//								$fname='style="color:#000000; background-color:#00FFFF;"';
//							}
?>
				<tr<?php echo $redstyle; ?>>
					<td valign="top" rowspan="3"><?php echo($pnum); ?></td>
					<td valign="top" rowspan="3" <?php echo $bnum; ?>><?php echo($ptosrow["bnum"]); ?></td>
					<td valign="top">PTOS Account</td>
					<td><?php echo($ptosrow["cnum"]); ?></td>
					<td><?php echo($ptosrow["fname"]); ?></td>
					<td><?php echo($ptosrow["lname"]); ?></td>
					<td colspan="5">&nbsp;</td>
				</tr>
<?php
// Get NetPT Case and Patient RECORD
					$netptquery = "SELECT crcnum, crpnum, crlname, crfname, palname, pafname FROM cases JOIN patients on crpaid=paid WHERE crpnum='$pnum' ";
					if($netptresult=mysqli_query($dbhandle,$netptquery)) {
						$netptnumrows=mysqli_num_rows($netptresult); 
						if($netptrow=mysqli_fetch_assoc($netptresult)) {
							if(($netptrow['crfname'] != $ptosrow['fname'])) {
								$fname='style="color:#000000; background-color:#FF0000;"';
								$netpterror=1;
							}
							else
								$fname="";
		
							if(($netptrow['crlname']!=$ptosrow['lname'])) {
								$lname='style="color:#000000; background-color:#FF0000;"';
								$netpterror=1;
							}
							else
								$lname="";
		
							if(($netptrow['crcnum']!=$ptosrow['cnum'])) {
								$cnum='style="color:#000000; background-color:#FF0000;"';
								$netpterror=1;
							}
							else
								$cnum="";
							if(!empty($netpterror)) 
								$td="<td $cnum>".$netptrow["crcnum"]."</td><td $fname>".$netptrow["crfname"]."</td><td $lname>".$netptrow["crlname"]."</td>";
							else 
								$td='<td colspan="8">(same as above)</td>';
						}
						else 
							$td='<td colspan="8">NO CASE FOUND</td>';

						echo("<tr $redstyle><td valign=\"top\">NetPT Case</td>$td</tr>");

							unset($treatmenterror);
							$treatmentquery = "SELECT * FROM treatment_header WHERE thpnum='$pnum' and thsbmstatus < '700'";
							if($treatmentresult=mysqli_query($dbhandle,$treatmentquery)) {
								$numrows=mysqli_num_rows($treatmentresult); 
								while($treatment_header_row=mysqli_fetch_assoc($treatmentresult)) { 				
									unset($errorflag);
									if(($treatment_header_row['thfname'] != $ptosrow['fname'])) {
										$fnamecolor='style="color:#000000; background-color:#FF0000;"';
										$errorflag=1;
									}
									else
										$fnamecolor="";
				
									if(($treatment_header_row['thlname']!=$ptosrow['lname'])) {
										$lnamecolor='style="color:#000000; background-color:#FF0000;"';
										$errorflag=1;
									}
									else
										$lnamecolor="";
				
									if(($treatment_header_row['thcnum']!=$ptosrow['cnum'])) {
										$cnumcolor='style="color:#000000; background-color:#FF0000;"';
										$errorflag=1;
									}
									else
										$cnumcolor="";
									
									if(!empty($errorflag)) {
										$treatmenterror=1;
				?>
				<tr<?php echo $redstyle; ?>> 
					<td valign="top">NetPT Treatments</td>
					<td <?php echo $cnumcolor; ?>><?php echo $treatment_header_row['thcnum']; ?></td>
					<td <?php echo $fnamecolor; ?>><?php echo $treatment_header_row['thfname']; ?></td>
					<td <?php echo $lnamecolor; ?>><?php echo $treatment_header_row['thlname']; ?></td>
					<td><?php echo displayDate($treatment_header_row['thdate']); ?></td>
					<td><?php echo $treatment_header_row['thctmcode']; ?></td>
					<td><?php echo $treatment_header_row['thvtmcode']; ?></td>
					<td><?php echo $treatment_header_row['thttmcode']; ?></td>
					<td><?php echo $treatment_header_row['thsbmstatus']; ?></td>
				</tr>
<?php
									}
								} //while 
								if(empty($treatmenterror)) 
									echo("<tr $redstyle ><td valign=\"top\">NetPT Treatments</td><td colspan=\"8\">(same as PTOS)</td></tr>");
							} //if
							else 
								error("001", "SELECT QUERY:$treatmentquery" . mysqli_error($dbhandle));	
//						} //if
//						else 
//							error("001", "FETCH QUERY:$netptquery" . mysqli_error($dbhandle));	
					} //if
					else 
						error("001", "SELECT QUERY:$netptquery" . mysqli_error($dbhandle));	
				} //if
				else 
					error("001", "FETCH QUERY:$ptosquery" . mysqli_error($dbhandle));	
			} //if
			else 
				error("001", "SELECT QUERY:$ptosquery" . mysqli_error($dbhandle));	
		} //while
		?>
		</table>
	</form>
	</fieldset>
</div>
	<?php
	} // numrows
	else 
		if($numrows==0)
			notify("000","No issues found.");
		else
			notify("000", "$numrows issues found.");
} // dberror
else
	echo("ERROR: SELECT<br>$namesquery<br>" . mysqli_error($dbhandle));
mysqli_close($dbhandle);
?>

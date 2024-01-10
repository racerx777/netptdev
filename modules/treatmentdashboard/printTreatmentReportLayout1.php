<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $report['title']; ?></title>
</head>
<body>
<div style="float:left"><img src="/img/wsptn logo bw outline.jpg" width="300px"></div>
<div style="float:right">
	<div><?php echo $report['title']; ?></div>
</div>
<div style="clear:both;">
<table border="1" style="text-align:center; border: solid;" cellpadding="5" cellspacing="0" width="100%">
	<caption>
	<?php echo $report['title']; ?>
	</caption>
	<tr>
		<th width="3%" rowspan="3">Business Unit</th>
		<th width="3%" rowspan="3">Provider</th>
		<th width="3%" rowspan="3">Clinic</th>
		<th width="48%" colspan="6">PT</th>
		<th colspan="2" rowspan="2">Exc.</th>
		<th colspan="2" rowspan="2">PT Total</th>
		<th width="6%" colspan="2" rowspan="2">OT Total</th>
		<th width="6%" colspan="2" rowspan="2">Acu Total</th>
		<th width="6%" colspan="2" rowspan="2">Pool Total</th>
		<th width="6%" colspan="2" rowspan="2"> Total</th>
	</tr>
	<tr>
		<th colspan="2">A</th>
		<th colspan="2">B</th>
		<th colspan="2">C</th>
	</tr>
	<tr>
		<th width="3%">#</th>
		<th width="3%">%</th>
		<th width="3%">#</th>
		<th width="3%">%</th>
		<th width="3%">#</th>
		<th width="3%">%</th>
		<th width="3%">#</th>
		<th width="3%">%</th>
		<th width="3%">#</th>
		<th width="3%">%</th>
		<th width="3%">#</th>
		<th width="3%">%</th>
		<th width="3%">#</th>
		<th width="3%">%</th>
		<th width="3%">#</th>
		<th width="3%">%</th>
		<th width="3%">#</th>
		<th width="3%">%</th>
	</tr>
	<?php 
function pct($num, $precision=1) {
	$mynum = $num*100;
	$mynum = round($mynum,1);
	return("$mynum");
}
		if(count($rptrow)>0) {
			foreach($rptrow as $line=>$data) {
				$total=$data['TotalP'];
				$pct['PTa']=($data['PTP'] == 0 ? 0 : pct($data['PTa']/$data['PTP']));
				$pct['PTb']=($data['PTP'] == 0 ? 0 : pct($data['PTb']/$data['PTP']));
				$pct['PTc']=($data['PTP'] == 0 ? 0 : pct($data['PTc']/$data['PTP']));
				$pct['PTE']=($data['PTP'] == 0 ? 0 : pct($data['PTE']/$data['PTP']));

// Check Breaklevel Provider
				if($data['pg'] != $savedpg) {
//					Output Provider totals if there is more than 1 clinic
					if(isset($outputtotalpg)) {
						unset($outputtotalpg);
						$totalpg=$datapg['TotalP'];
						$pctpg['PTa']=($datapg['PTP'] == 0 ? 0 : pct($datapg['PTa']/$datapg['PTP']));
						$pctpg['PTb']=($datapg['PTP'] == 0 ? 0 : pct($datapg['PTb']/$datapg['PTP']));
						$pctpg['PTc']=($datapg['PTP'] == 0 ? 0 : pct($datapg['PTc']/$datapg['PTP']));
						$pctpg['PTE']=($datapg['PTP'] == 0 ? 0 : pct($datapg['PTE']/$datapg['PTP']));
?>
	<tr>
		<td><?php echo "&nbsp;";?></td>
		<td colspan="2"><?php echo "$savedpg Total";?></td>
		<td><div align="center"><?php echo $datapg['PTa']; ?></div></td>
		<td><div align="center"><?php echo $pctpg['PTa']; ?></div></td>
		<td><div align="center"><?php echo $datapg['PTb']; ?></div></td>
		<td><div align="center"><?php echo $pctpg['PTb']; ?></div></td>
		<td><div align="center"><?php echo $datapg['PTc']; ?></div></td>
		<td><div align="center"><?php echo $pctpg['PTc']; ?></div></td>
		<td><div align="center"><?php echo $datapg['PTE']; ?></div></td>
		<td><div align="center"><?php echo $pctpg['PTE']; ?></div></td>
		<td><div align="center"><?php echo $datapg['PTP']; ?></div></td>
		<td><div align="center"><?php echo pct($datapg['PTP']/$totalpg);?></div></td>
		<td><div align="center"><?php echo $datapg['OTP']; ?></div></td>
		<td><div align="center"><?php echo pct($datapg['OTP']/$totalpg);?></div></td>
		<td><div align="center"><?php echo $datapg['AcuP']; ?></div></td>
		<td><div align="center"><?php echo pct($datapg['AcuP']/$totalpg);?></div></td>
		<td><div align="center"><?php echo $datapg['PoolP']; ?></div></td>
		<td><div align="center"><?php echo pct($datapg['PoolP']/$totalpg);?></div></td>
		<td><div align="center"><?php echo $datapg['TotalP']; ?></div></td>
		<td><div align="center"><?php echo pct($datapg['TotalP']/$totalpg);?></div></td>
	</tr>
	<?php					
					}
// Rollup Totals
					$databu['PTa']+=$datapg['PTa'];
					$databu['PTb']+=$datapg['PTb'];
					$databu['PTc']+=$datapg['PTc'];
					$databu['PTE']+=$datapg['PTE'];
					$databu['PTP']+=$datapg['PTP'];
					$databu['OTP']+=$datapg['OTP'];
					$databu['AcuP']+=$datapg['AcuP'];
					$databu['PoolP']+=$datapg['PoolP'];
					$databu['TotalP']+=$datapg['TotalP'];
// Clear Provider Totals
					unset($datapg);
					$rptrowpg = $data['pg'];
					$savedpg = $rptrowpg;
				}
				else {
					$rptrowpg = "&nbsp";
					$outputtotalpg = 1;
				}

// Check Breaklevel Business Unit
				if($data['bu'] != $savedbu) {
					if(isset($outputtotalbu)) {
						unset($outputtotalbu);
						$totalbu=$databu['TotalP'];
						$pctbu['PTa']=($databu['PTP'] == 0 ? 0 : pct($databu['PTa']/$databu['PTP']));
						$pctbu['PTb']=($databu['PTP'] == 0 ? 0 : pct($databu['PTb']/$databu['PTP']));
						$pctbu['PTc']=($databu['PTP'] == 0 ? 0 : pct($databu['PTc']/$databu['PTP']));
						$pctbu['PTE']=($databu['PTP'] == 0 ? 0 : pct($databu['PTE']/$databu['PTP']));
?>
	<tr bgcolor="#EAEAEA">
		<td colspan="3"><?php echo "$savedbu Total";?></td>
		<td><div align="center"><?php echo $databu['PTa']; ?></div></td>
		<td><div align="center"><?php echo $pctbu['PTa']; ?></div></td>
		<td><div align="center"><?php echo $databu['PTb']; ?></div></td>
		<td><div align="center"><?php echo $pctbu['PTb']; ?></div></td>
		<td><div align="center"><?php echo $databu['PTc']; ?></div></td>
		<td><div align="center"><?php echo $pctbu['PTc']; ?></div></td>
		<td><div align="center"><?php echo $databu['PTE']; ?></div></td>
		<td><div align="center"><?php echo $pctbu['PTE']; ?></div></td>
		<td><div align="center"><?php echo $databu['PTP']; ?></div></td>
		<td><div align="center"><?php echo pct($databu['PTP']/$totalbu);?></div></td>
		<td><div align="center"><?php echo $databu['OTP']; ?></div></td>
		<td><div align="center"><?php echo pct($databu['OTP']/$totalbu);?></div></td>
		<td><div align="center"><?php echo $databu['AcuP']; ?></div></td>
		<td><div align="center"><?php echo pct($databu['AcuP']/$totalbu);?></div></td>
		<td><div align="center"><?php echo $databu['PoolP']; ?></div></td>
		<td><div align="center"><?php echo pct($databu['PoolP']/$totalbu);?></div></td>
		<td><div align="center"><?php echo $databu['TotalP']; ?></div></td>
		<td><div align="center"><?php echo pct($databu['TotalP']/$totalbu);?></div></td>
	</tr>
	<?php
					}
					$datarpt['PTa']+=$databu['PTa'];
					$datarpt['PTb']+=$databu['PTb'];
					$datarpt['PTc']+=$databu['PTc'];
					$datarpt['PTE']+=$databu['PTE'];
					$datarpt['PTP']+=$databu['PTP'];
					$datarpt['OTP']+=$databu['OTP'];
					$datarpt['AcuP']+=$databu['AcuP'];
					$datarpt['PoolP']+=$databu['PoolP'];
					$datarpt['TotalP']+=$databu['TotalP'];
					unset($databu);
					$rptrowbu = $data['bu'];
					$savedbu = $rptrowbu;
				}
				else {
					$rptrowbu = "&nbsp";
					$outputtotalbu = 1;
				}
// Add row data to provider totals
				$datapg['PTa']+=$data['PTa'];
				$datapg['PTb']+=$data['PTb'];
				$datapg['PTc']+=$data['PTc'];
				$datapg['PTE']+=$data['PTE'];
				$datapg['PTP']+=$data['PTP'];
				$datapg['OTP']+=$data['OTP'];
				$datapg['AcuP']+=$data['AcuP'];
				$datapg['PoolP']+=$data['PoolP'];
				$datapg['TotalP']+=$data['TotalP'];
		?>
	<tr>
		<td><?php echo $rptrowbu;?></td>
		<td><?php echo $rptrowpg;?></td>
		<td><?php echo $data['cn'];?></td>
		<td><div align="center"><?php echo $data['PTa']; ?></div></td>
		<td><div align="center"><?php echo $pct['PTa'];?></div></td>
		<td><div align="center"><?php echo $data['PTb']; ?></div></td>
		<td><div align="center"><?php echo $pct['PTb'];?></div></td>
		<td><div align="center"><?php echo $data['PTc']; ?></div></td>
		<td><div align="center"><?php echo $pct['PTc'];?></div></td>
		<td><div align="center"><?php echo $data['PTE']; ?></div></td>
		<td><div align="center"><?php echo $pct['PTE'];?></div></td>
		<td><div align="center"><?php echo $data['PTP']; ?></div></td>
		<td><div align="center"><?php echo pct($data['PTP']/$total);?></div></td>
		<td><div align="center"><?php echo $data['OTP']; ?></div></td>
		<td><div align="center"><?php echo pct($data['OTP']/$total);?></div></td>
		<td><div align="center"><?php echo $data['AcuP']; ?></div></td>
		<td><div align="center"><?php echo pct($data['AcuP']/$total);?></div></td>
		<td><div align="center"><?php echo $data['PoolP']; ?></div></td>
		<td><div align="center"><?php echo pct($data['PoolP']/$total);?></div></td>
		<td><div align="center"><?php echo $data['TotalP']; ?></div></td>
		<td><div align="center"><?php echo pct($data['TotalP']/$total);?></div></td>
	</tr>
	<?php
			} // for each 

// Check Breaklevel Provider
//				if($data['pg'] != $savedpg) {
//					Output Provider totals if there is more than 1 clinic
					if(isset($outputtotalpg)) {
						unset($outputtotalpg);
						$totalpg=$datapg['TotalP'];
						$pctpg['PTa']=($datapg['PTP'] == 0 ? 0 : pct($datapg['PTa']/$datapg['PTP']));
						$pctpg['PTb']=($datapg['PTP'] == 0 ? 0 : pct($datapg['PTb']/$datapg['PTP']));
						$pctpg['PTc']=($datapg['PTP'] == 0 ? 0 : pct($datapg['PTc']/$datapg['PTP']));
						$pctpg['PTE']=($datapg['PTP'] == 0 ? 0 : pct($datapg['PTE']/$datapg['PTP']));
?>
	<tr>
		<td><?php echo "&nbsp;";?></td>
		<td colspan="2"><?php echo "$savedpg Total";?></td>
		<td><div align="center"><?php echo $datapg['PTa']; ?></div></td>
		<td><div align="center"><?php echo $pctpg['PTa']; ?></div></td>
		<td><div align="center"><?php echo $datapg['PTb']; ?></div></td>
		<td><div align="center"><?php echo $pctpg['PTb']; ?></div></td>
		<td><div align="center"><?php echo $datapg['PTc']; ?></div></td>
		<td><div align="center"><?php echo $pctpg['PTc']; ?></div></td>
		<td><div align="center"><?php echo $datapg['PTE']; ?></div></td>
		<td><div align="center"><?php echo $pctpg['PTE']; ?></div></td>
		<td><div align="center"><?php echo $datapg['PTP']; ?></div></td>
		<td><div align="center"><?php echo pct($datapg['PTP']/$totalpg);?></div></td>
		<td><div align="center"><?php echo $datapg['OTP']; ?></div></td>
		<td><div align="center"><?php echo pct($datapg['OTP']/$totalpg);?></div></td>
		<td><div align="center"><?php echo $datapg['AcuP']; ?></div></td>
		<td><div align="center"><?php echo pct($datapg['AcuP']/$totalpg);?></div></td>
		<td><div align="center"><?php echo $datapg['PoolP']; ?></div></td>
		<td><div align="center"><?php echo pct($datapg['PoolP']/$totalpg);?></div></td>
		<td><div align="center"><?php echo $datapg['TotalP']; ?></div></td>
		<td><div align="center"><?php echo pct($datapg['TotalP']/$totalpg);?></div></td>
	</tr>
	<?php					
					}
// Rollup Totals
					$databu['PTa']+=$datapg['PTa'];
					$databu['PTb']+=$datapg['PTb'];
					$databu['PTc']+=$datapg['PTc'];
					$databu['PTE']+=$datapg['PTE'];
					$databu['PTP']+=$datapg['PTP'];
					$databu['OTP']+=$datapg['OTP'];
					$databu['AcuP']+=$datapg['AcuP'];
					$databu['PoolP']+=$datapg['PoolP'];
					$databu['TotalP']+=$datapg['TotalP'];
// Clear Provider Totals
					unset($datapg);
					$rptrowpg = $data['pg'];
					$savedpg = $rptrowpg;
//				}
//				else {
//					$rptrowpg = "&nbsp";
//					$outputtotalpg = 1;
//				}

// Check Breaklevel Business Unit
//				if($data['bu'] != $savedbu) {
					if(isset($outputtotalbu)) {
						unset($outputtotalbu);
						$totalbu=$databu['TotalP'];
						$pctbu['PTa']=($databu['PTP'] == 0 ? 0 : pct($databu['PTa']/$databu['PTP']));
						$pctbu['PTb']=($databu['PTP'] == 0 ? 0 : pct($databu['PTb']/$databu['PTP']));
						$pctbu['PTc']=($databu['PTP'] == 0 ? 0 : pct($databu['PTc']/$databu['PTP']));
						$pctbu['PTE']=($databu['PTP'] == 0 ? 0 : pct($databu['PTE']/$databu['PTP']));
?>
	<tr bgcolor="#EAEAEA">
		<td colspan="3"><?php echo "$savedbu Total";?></td>
		<td><div align="center"><?php echo $databu['PTa']; ?></div></td>
		<td><div align="center"><?php echo $pctbu['PTa']; ?></div></td>
		<td><div align="center"><?php echo $databu['PTb']; ?></div></td>
		<td><div align="center"><?php echo $pctbu['PTb']; ?></div></td>
		<td><div align="center"><?php echo $databu['PTc']; ?></div></td>
		<td><div align="center"><?php echo $pctbu['PTc']; ?></div></td>
		<td><div align="center"><?php echo $databu['PTE']; ?></div></td>
		<td><div align="center"><?php echo $pctbu['PTE']; ?></div></td>
		<td><div align="center"><?php echo $databu['PTP']; ?></div></td>
		<td><div align="center"><?php echo pct($databu['PTP']/$totalbu);?></div></td>
		<td><div align="center"><?php echo $databu['OTP']; ?></div></td>
		<td><div align="center"><?php echo pct($databu['OTP']/$totalbu);?></div></td>
		<td><div align="center"><?php echo $databu['AcuP']; ?></div></td>
		<td><div align="center"><?php echo pct($databu['AcuP']/$totalbu);?></div></td>
		<td><div align="center"><?php echo $databu['PoolP']; ?></div></td>
		<td><div align="center"><?php echo pct($databu['PoolP']/$totalbu);?></div></td>
		<td><div align="center"><?php echo $databu['TotalP']; ?></div></td>
		<td><div align="center"><?php echo pct($databu['TotalP']/$totalbu);?></div></td>
	</tr>
	<?php
					}
					$datarpt['PTa']+=$databu['PTa'];
					$datarpt['PTb']+=$databu['PTb'];
					$datarpt['PTc']+=$databu['PTc'];
					$datarpt['PTE']+=$databu['PTE'];
					$datarpt['PTP']+=$databu['PTP'];
					$datarpt['OTP']+=$databu['OTP'];
					$datarpt['AcuP']+=$databu['AcuP'];
					$datarpt['PoolP']+=$databu['PoolP'];
					$datarpt['TotalP']+=$databu['TotalP'];
					unset($databu);
					$rptrowbu = $data['bu'];
					$savedbu = $rptrowbu;
//				}
//				else {
//					$rptrowbu = "&nbsp";
//					$outputtotalbu = 1;
//				}
		} // if
		$totalrpt=$datarpt['TotalP'];
		$pctrpt['PTa']=($datarpt['PTP'] == 0 ? 0 : pct($datarpt['PTa']/$datarpt['PTP']));
		$pctrpt['PTb']=($datarpt['PTP'] == 0 ? 0 : pct($datarpt['PTb']/$datarpt['PTP']));
		$pctrpt['PTc']=($datarpt['PTP'] == 0 ? 0 : pct($datarpt['PTc']/$datarpt['PTP']));
		$pctrpt['PTE']=($datarpt['PTP'] == 0 ? 0 : pct($datarpt['PTE']/$datarpt['PTP']));
		?>
	<tr bgcolor="#D5D5D5">
		<td colspan="3"><?php echo "Report Total";?></td>
		<td><div align="center"><?php echo $datarpt['PTa']; ?></div></td>
		<td><div align="center"><?php echo $pctrpt['PTa']; ?></div></td>
		<td><div align="center"><?php echo $datarpt['PTb']; ?></div></td>
		<td><div align="center"><?php echo $pctrpt['PTb']; ?></div></td>
		<td><div align="center"><?php echo $datarpt['PTc']; ?></div></td>
		<td><div align="center"><?php echo $pctrpt['PTc']; ?></div></td>
		<td><div align="center"><?php echo $datarpt['PTE']; ?></div></td>
		<td><div align="center"><?php echo $pctrpt['PTE']; ?></div></td>
		<td><div align="center"><?php echo $datarpt['PTP']; ?></div></td>
		<td><div align="center"><?php echo pct($datarpt['PTP']/$totalrpt);?></div></td>
		<td><div align="center"><?php echo $datarpt['OTP']; ?></div></td>
		<td><div align="center"><?php echo pct($datarpt['OTP']/$totalrpt);?></div></td>
		<td><div align="center"><?php echo $datarpt['AcuP']; ?></div></td>
		<td><div align="center"><?php echo pct($datarpt['AcuP']/$totalrpt);?></div></td>
		<td><div align="center"><?php echo $datarpt['PoolP']; ?></div></td>
		<td><div align="center"><?php echo pct($datarpt['PoolP']/$totalrpt);?></div></td>
		<td><div align="center"><?php echo $datarpt['TotalP']; ?></div></td>
		<td><div align="center"><?php echo pct($datarpt['TotalP']/$totalrpt);?></div></td>
	</tr>
</table>
</div>
</body>
</html>

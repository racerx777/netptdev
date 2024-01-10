<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 
function inz($val) {
	if(is_array($val)) {
	}
	else {
		if(is_null($val)) {
			$val=0;
		}
	}
}

// determine if detail, summary or both
if($_REQUEST['detail'] == '0')
	$displaydetailtable=FALSE;
else
	$displaydetailtable=TRUE;
if($_REQUEST['summary'] == '0')
	$displaysummarytable=FALSE;
else
	$displaysummarytable=TRUE;

// determine if dates provided are referral dates or cancelled dates
if(!empty($_REQUEST['fromdate']))
	$fromdate=$_REQUEST['fromdate'];
if(!empty($_REQUEST['todate']))
	$todate=$_REQUEST['todate'];

if(!empty($fromdate) && empty($todate) ) 
	$todate=$fromdate; 
if(!empty($todate) && empty($fromdate) ) 
	$fromdate=$todate;

$report=array();
if(!empty($todate)) {
	$report['type'] = 1;
	$report['column1'] = "Treatment Date";
	$report['fromdate']=date("Y-m-d 00:00:00", strtotime($fromdate));
	$report['todate']=date("Y-m-d 23:59:59", strtotime($todate));
	$report['title'] = "Treatment Efficiency Report by Business Unit, Provider, Clinic " . displayDate($report['fromdate']) . " - " . displayDate($report['todate']); 
	$report['where'] = "WHERE thdate between '" . $report['fromdate'] . "' and '" . 		$report['todate'] . "' and thsbmstatus between '100' and '800'";
//ORDER BY thcnum, thttmcode, coalesce(tpg.gmcode,"OTHER")
	$report['orderby'] = "ORDER BY bumname, pgmname, thcnum, thttmcode, rectype, rptcode";
}
else
	unset($report);

if(is_array($report) && count($report)!=0) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	unset($tpgquery);
// Columns
	$txquery = "
SELECT bumname, pgmname, thcnum, ttmcode, colgrp, code, codecount FROM (
	SELECT thcnum, coalesce(thttmcode,'OTH') as ttmcode, 1 as colgrp, coalesce(tpg.gmcode,'PATIENTS') as code, count(*) as codecount
	FROM treatment_header th
	 LEFT JOIN treatment_procedure_groups tpg ON th.thid=tpg.thid
	WHERE th.thid between 10000 and 11000
	GROUP BY thcnum, ttmcode, colgrp, code

	UNION

	SELECT thcnum, coalesce(thttmcode,'OTH') as ttmcode, 2 as colgrp, coalesce(tp.pmcode,'OTH') as code, count(*) as codecount
	FROM treatment_header th
	 LEFT JOIN treatment_procedures tp ON th.thid=tp.thid
	  LEFT JOIN master_procedures pm on tp.pmcode=pm.pmcode
	WHERE th.thid between 10000 and 11000 and pm.pmcodetype='P'
	GROUP BY thcnum, ttmcode, colgrp, code

	UNION

	SELECT thcnum, coalesce(thttmcode,'OTH') as ttmcode, 3 as colgrp, coalesce(tm.mmcode,'OTH') as code, count(*) as codecount
	FROM treatment_header th
	 LEFT JOIN treatment_modalities tm ON th.thid=tm.thid
	WHERE th.thid between 10000 and 11000
	GROUP BY thcnum, ttmcode, colgrp, code
) as a
	LEFT JOIN master_clinics
	ON thcnum = cmcnum
		LEFT JOIN master_provider_groups
		ON cmpgmcode = pgmcode
			LEFT JOIN master_business_units
			ON pgmbumcode = bumcode
GROUP BY bumname, pgmname, thcnum, ttmcode, colgrp, code
ORDER BY bumname, pgmname, thcnum, ttmcode, colgrp, code
	$orderby
		";

	$where = $report['where'];
	$orderby = $report['orderby'];
	$txquery = "
SELECT bumname, pgmname, thcnum, thttmcode, rectype, rptcode, gmcodecnt FROM (
	SELECT thcnum, thttmcode, 'G' as rectype, ttpg.rptcode, count(*) as gmcodecnt
	FROM treatment_header th
	 JOIN treatment_procedure_groups tpg ON th.thid=tpg.thid
	  JOIN treatmenttype_procedure_groups ttpg ON th.thttmcode=ttpg.ttmcode and tpg.gmcode=ttpg.gmcode
	 JOIN treatment_procedures tp ON th.thid=tp.thid
	  JOIN treatmenttype_procedures ttp ON th.thttmcode=ttp.ttmcode and tp.pmcode=ttp.pmcode
	$where and thttmcode not in ('A','P')
	GROUP BY thcnum, thttmcode, rectype, rptcode
	UNION
	SELECT thcnum, thttmcode, 'P' as rectype, '' as rptcode, count(*) as pmcodecnt
	FROM treatment_header th
	 JOIN treatment_procedures tp ON th.thid=tp.thid
	  JOIN treatmenttype_procedures ttp ON th.thttmcode=ttp.ttmcode and tp.pmcode=ttp.pmcode
	$where
	GROUP BY thcnum, thttmcode, rectype, rptcode
	UNION
	SELECT thcnum, thttmcode, 'M' as rectype, '' as rptcode, count(*) as mmcodecnt
	FROM treatment_header th
	 JOIN treatment_modalities tm ON th.thid=tm.thid
	  JOIN treatmenttype_modalities ttm ON th.thttmcode=ttm.ttmcode and tm.mmcode=ttm.mmcode
	$where
	GROUP BY thcnum, thttmcode, rectype, rptcode
) as a
	LEFT JOIN master_clinics
	ON thcnum = cmcnum
		LEFT JOIN master_provider_groups
		ON cmpgmcode = pgmcode
			LEFT JOIN master_business_units
			ON pgmbumcode = bumcode
GROUP BY bumname, pgmname, thcnum, thttmcode, rectype, rptcode
$orderby
";
dump("txtresult",$txtresult);
	if($txresult = mysqli_query($dbhandle,$txquery)) {
		while($row=mysqli_fetch_assoc($txresult)) {
			$recsread++;

			$bum=$row['bumname']; 
			$pgm=$row['pgmname']; 
			$cnum=$row['thcnum']; 

			$key="$bum-$pgm-$cnum";

			$bums["$bum"]++;
			$pgms["$bum"]["$pgm"]++;
			$cnums["$bum"]["$pgm"]["$cnum"]++;

			$ttmcode=$row['thttmcode'];

			if($ttmcode == 'A')
				$ttmcode = 'Acu';
			if($ttmcode == 'P')
				$ttmcode = 'Pool';
			
			$rectype=$row['rectype'];
			$rptcode=$row['rptcode'];
			$codecnt=$row['gmcodecnt'];

			$ttmcnt["$cnum"]["$ttmcode"]+=$codecnt;
			$rectypecnt["$cnum"]["$ttmcode"]["$rectype"]+=$codecnt;
			$cnt["$cnum"]+=$codecnt;

			if($ttmcode == 'PT') { // Physical Therapy
				$grp1cnt["$cnum"]["$rptcode"]+=$codecnt;
				if($rectype == 'G') { 
					$grp2cnt["$cnum"]["$rptcode"]+=$codecnt;
					$rectypecnt["$cnum"]["$ttmcode"]["P"]-=$codecnt;
				}
			}
		} // while 
		if(count($cnums)>0) {
			foreach($cnums as $bu=>$arr1) {
				foreach($arr1 as $pg=>$arr2) {
					foreach($arr2 as $cn=>$arr3) {
						$rptrow[]['bu']=$bu;
						$i=count($rptrow)-1;
						$rptrow["$i"]['pg']=$pg;
						$rptrow["$i"]['cn']=$cn;
						$rptrow["$i"]['PTa']=($grp2cnt["$cn"]["A"] == NULL ? 0 : $grp2cnt["$cn"]["A"]);
						$rptrow["$i"]['PTb']=($grp2cnt["$cn"]["B"] == NULL ? 0 : $grp2cnt["$cn"]["B"]);
						$rptrow["$i"]['PTc']=($grp2cnt["$cn"]["B"] == NULL ? 0 : $grp2cnt["$cn"]["C"]);
						$rptrow["$i"]['PTG']=($rectypecnt["$cn"]['PT']["G"] == NULL ? 0 : $rectypecnt["$cn"]['PT']["G"]);
						$rptrow["$i"]['PTE']=($rectypecnt["$cn"]['PT']["P"] == NULL ? 0 : $rectypecnt["$cn"]['PT']["P"]);
						$rptrow["$i"]['PTP']=$rptrow["$i"]['PTG'] + $rptrow["$i"]['PTE'];
						$rptrow["$i"]['OTP']=($ttmcnt["$cn"]['OT'] == NULL ? 0 : $ttmcnt["$cn"]['OT']);
						$rptrow["$i"]['AcuP']=($ttmcnt["$cn"]['Acu'] == NULL ? 0 : $ttmcnt["$cn"]['Acu']);
						$rptrow["$i"]['PoolP']=($ttmcnt["$cn"]['Pool'] == NULL ? 0 : $ttmcnt["$cn"]['Pool']);
						$rptrow["$i"]['TotalP']=$rptrow["$i"]['PTP']+$rptrow["$i"]['OTP']+$rptrow["$i"]['AcuP']+$rptrow["$i"]['PoolP'];
						$rptrow["$i"]['PTM']=($rectypecnt["$cn"]['PT']["M"] == NULL ? 0 : $rectypecnt["$cn"]['PT']["M"]);
						$rptrow["$i"]['OTM']=($rectypecnt["$cn"]['OT']["M"] == NULL ? 0 : $rectypecnt["$cn"]['OT']["M"]);
						$rptrow["$i"]['AcuM']=($rectypecnt["$cn"]['Acu']["M"] == NULL ? 0 : $rectypecnt["$cn"]['Acu']["M"]);
						$rptrow["$i"]['PoolM']=($rectypecnt["$cn"]['Pool']["M"] == NULL ? 0 : $rectypecnt["$cn"]['Pool']["M"]);
						$rptrow["$i"]['TotalM']=$rptrow["$i"]['PTM']+$rptrow["$i"]['OTM']+$rptrow["$i"]['AcuM']+$rptrow["$i"]['PoolM'];
					}
				}
			}
		}
		require_once("printTreatmentReportLayout1.php");
	} // if
	else 
		dump("query",$query);
} 
?>
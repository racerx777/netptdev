<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/common/session.php');
securitylevel(33);
require_once($_SERVER['DOCUMENT_ROOT'].'/common/user.options.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
 
		if(isset($_REQUEST['printpdfp'])) {

			$dbhandle = dbconnect();

			$html ="";
			if(!empty($_REQUEST['days']) || $_REQUEST['days']==0)   // number of days since last touched
				$days=$_REQUEST['days']+0;
			else
				$days=60;

			if(!empty($days) || $days==0) {
				$today=today();
				$daysdate = date("Y-m-d", strtotime($today . " -" . $days . " day"));
				$dbfromdate=dbDate($daysdate,'Y-m-d')." 00:00:00";

				$excludedate = date("Y-m-d",strtotime($today . " -1 day"));
				$dbexcludedate=dbDate($excludedate,'Y-m-d')." 00:00:00";
			}
			else {
				echo "Number of Days cannot be empty/zero.";
				exit();
			}


			if(isset($_REQUEST['userid'])) // If selected a user translate to queue
				$userid=$_REQUEST['userid'];
			else
				unset($userid);

			if(isset($_REQUEST['printnotes']))
				$printnotes=$_REQUEST['printnotes'];
			else
				$printnotes='none';

			if(isset($_REQUEST['mintbal']))
				$mintbal=$_REQUEST['mintbal']+0;
			else
				$mintbal=0;
			
			$tdisuser=getuser();

			$where=array();

			// if it is not Sunni, Vidal or constance reset user id to current user id
			if (!isuserlevel(34)) {
			    $userid=getuserid();
			}
			$queue = "";
			if(!empty($userid)) {
				$userinformation=getUserInformation($userid);
				$umuser=$userinformation['umuser'];
				if(isset($umuser)) {
					$queue=getUserQueueAssignment($umuser);
			//		$where[]="cqauser='$umuser'";
					$where[]="cqgroup='$queue'";
				}
			}



			$where[]="(ca.upddate IS NULL or ca.upddate < '$dbfromdate')";
			$where[]="(ca.crtdate IS NULL or ca.crtdate < '$dbexcludedate')";
			//$where[]="( cqschcalldate IS NULL OR cqschcalldate < DATE_SUB( NOW( ) , INTERVAL 1 DAY ) )";
			//$where[]="tbal > $mintbal and t30+t60+t90+t120>0";
			//$where[]="cqauser IS NOT NULL";

			if(count($where)>0)
				$wheresql = "WHERE ".implode(' AND ', $where);

			$select="
			SELECT count(*) as totalrecords
			FROM collection_accounts ca
			LEFT JOIN collection_queue q ON caid = cqcaid
			LEFT JOIN ( SELECT pnum, t30, t60, t90, t120, tbal FROM PTOS_Patients WHERE tbal>$mintbal and t30+t60+t90+t120>0 ) pat1 ON capnum=pnum
			$wheresql  
			";

			$noteresult=mysqli_query($dbhandle,$select);
			$numrows=mysqli_fetch_assoc($noteresult);
			$recordperpdf = 3000;
			$totalrecords = $numrows['totalrecords'];
			$pdfcount = ceil($totalrecords / $recordperpdf);


			$url = "/modules/collections/Reports/untouched/printPdf.php?days=".$_REQUEST['days']."&userid=".$_REQUEST['userid']."&printnotes=".$_REQUEST['printnotesp']."&mintbal=".$_REQUEST['mintbal']."&printpdf=".$_REQUEST['printpdfp'];

			if($pdfcount > 3) {

				echo '<p>The total Record count is  <b>'.$totalrecords.'</b> so the PDF has been divided into  <b>'.$pdfcount.'</b> PDFs.Click the numbered links 
						below to download PDFs or click the Excel icon to download a single file and print to PDF from your computer.</p>';
				
				$k = 1;
				$j = $recordperpdf;
				echo "<ul>";
				for($i=1;$i<=$pdfcount;$i++) {
					echo "<li class='col-md-2'>";
					$pagenos = "&offset=".$k."&limitmax=".$recordperpdf;
						//if($CCC == $pdfcount)
							echo "<a class='paginateLink' data-href=".$url.$pagenos."  >".$i."</a>";
						
					echo "</li>";
					$k = $j+1;
					$j = $j + $recordperpdf;
				}

				echo "</ul>";
			}
			die;
		}
require_once($_SERVER['DOCUMENT_ROOT'].'/common/javascript.js');
?>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous">
</script>
<script type="text/javascript" src="modules/collections/jquery.fileDownload.js"></script>
<div id="fader" style="display: none;color:#fff;font-weight: bold" align="center">Please Wait...
  <button class="cancledownload"  onclick='cancledownload();' >Cancel Download</button>
</div>

<script language="JavaScript">
	function cancledownload() {
		if(window.stop !== undefined) 
		{
		  window.stop();
		}
		else if(document.execCommand !== undefined)
		{
		  document.execCommand("Stop", false);
		}
		document.getElementById('fader').style.display = 'none';
	}

	$("body").delegate(".paginateLink","click",function() {
		document.getElementById('fader').style.display = 'block';
		var url = $(this).data('href');
		callPdf(url);
		// body...
	});

	function callPdf(pdfurl){
		console.log(pdfurl);
		var i = 0;
	
		fetch(pdfurl)
		  .then(resp => resp.blob())
		  .then(blob => {
		  	console.log(blob);
		    const url = window.URL.createObjectURL(blob);
		    const a = document.createElement('a');
		    a.style.display = 'none';
		    a.href = url;
		    // the filename you want
		    a.download = 'untouchedreport.'+ext;
		    document.body.appendChild(a);
		    a.click();
		    window.URL.revokeObjectURL(url);
		   document.getElementById('fader').style.display = 'none';
		  })
		  .catch(() => alert('download Cancel'));

	}
	function printPDFXLS(is_pdf=0) {

		//document.getElementById('fader').style.display = 'block';
		var includedetails=false;
		var untouched=false;
		var userid='';
		days = document.printForm.days.value;
		PrintNotes = document.printForm.PrintNotes;
		printradio='';
		for(i=0;i<PrintNotes.length;i++) {
			if(PrintNotes[i].checked==true)
				printradio=PrintNotes[i].value;
		}
		mintbal=document.printForm.mintbal.value;
		if(document.printForm.userid==undefined)
			userid = '';
		else
			userid=document.printForm.userid.value;



		if(is_pdf){
		
			url = "/modules/collections/collectionsUnTouchedAccountsSelection.php?days=" + days + "&userid=" + userid + "&printnotesp=" + printradio + "&mintbal="+mintbal+"&printpdfp="+is_pdf;
				//window.open(url);
			ext = 'pdf';
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
			    if (this.readyState == 4 && this.status == 200) {
			      document.getElementById("paginationlinks").innerHTML = this.responseText;
			    } else {
			    	document.getElementById('paginationlinks').innerHTML = "Loading ...";
			    }
			  };
			xhttp.open("GET", url, true);
			xhttp.send();
			
		}else{
			url = "/modules/collections/Reports/untouched/printXLS.php?days=" + days + "&userid=" + userid + "&printnotes=" + printradio + "&mintbal="+mintbal;
			//window.open(url);
			ext = 'xls';
			document.getElementById('fader').style.display = 'block';
			callPdf(url);
		}

		
	}



		var cal = new CalendarPopup();

	function printReport() {
		var includedetails=false;
		var untouched=false;
		var userid='';
		days = document.printForm.days.value;
		PrintNotes = document.printForm.PrintNotes;
		printradio='';
		for(i=0;i<PrintNotes.length;i++) {
			if(PrintNotes[i].checked==true)
				printradio=PrintNotes[i].value;
		}
		mintbal=document.printForm.mintbal.value;
		if(document.printForm.userid==undefined)
			userid = '';
		else
			userid=document.printForm.userid.value;
		$url = "/modules/collections/collectionsUnTouchedAccountsReport.php?days=" + days + "&userid=" + userid + "&printnotes=" + printradio + "&mintbal="+mintbal;
		window.open($url);
		return;
	}
</script>
<style type="text/css">
	#paginationlinks ul {
		display:inline-flex;

	}
	#paginationlinks ul li {
		padding: 2px;
		
		list-style:none;
	}
	.paginateLink {
	    padding: 7px;
	    background: #4682B4;
	    color: #fff;
	    text-decoration: none;
	}
	.acbtnrecords {
	    float: left;
	    vertical-align: middle;
	    padding: 0px 0 0 0;
	}

	.acbtnrecords input {
		height:27px;
	}
	#fader {
	  	opacity: 0.6;
		background: #4682B4;
		position: absolute;
		top: 43%;
		right: 29%;
		bottom: 48%;
		left: 30%;
		display: none;
		border-radius: 5px;
		padding: 10px;
	}
</style>
<?php
if(!isset($_POST['fromdate']))
	$_POST['fromdate']=today();

if(!isset($_POST['thrudate']))
	$_POST['thrudate']=$_POST['thrudate'];

$user=getuser();
$listoptions="";
if($list = getUserList()) {
	if(count($list) > 0) {
		$listoptions =  getSelectOptions(
			$arrayofarrayitems=$list,
			$optionvaluefield='umid',
			$arrayofoptionfields=array(
				'umuser'=>': ',
				'umname'=>''
				),
			$defaultoption=$_POST['userid'],
			$addblankoption=FALSE,
			$arraykey='umrole',
			$arrayofmatchvalues=array('33'=>'33','34'=>'34','73'=>'73'));
	}
	else
		error("999","User getSelectOptions() Error-No values in master table.");
}
else
	echo("Error-getUserList().");
?>


<div class="containedBox">
		
	<fieldset>
	<legend style="font-size:large">Un-Touched Accounts Report Criteria</legend>
	<form method="post" name="printForm" onsubmit="return formValidator()">
		<table width="50%" border="1" cellspacing="0" cellpadding="3">
			<tr>
				<th>Select accounts that have not been touched in</th>
				<td colspan="2" nowrap="nowrap" style="text-decoration:none"><input id="days" name="days" type="text" size="4" maxlength="4" value="<?php if(isset($_POST['days'])) echo $_POST['days']; else echo "60" ?>" />
					days</td>
			</tr>
<?php
if (isuserlevel(34)) {
?>
			<tr>
				<th>Select accounts assigned to user</th>
				<td colspan="2" nowrap="nowrap" style="text-decoration:none">
					<select id="userid" name="userid" type="text" size="1" maxlength="30" value="<?php if(isset($_POST['userid'])) echo $_POST['userid']; ?>" />
						<option value="">All Users</option>
					<?php echo $listoptions; ?>
					</select>
				</td>
			</tr>
<?php
}
?>
			<tr>
				<th> Notes to include</th>
				<td nowrap="nowrap" style="text-decoration:none"><table width="200">
						<tr>
							<td><label>
								<input type="radio" name="PrintNotes" value="none" id="NoNotes" checked="checked" />
								No Notes</label></td>
						</tr>
						<tr>
							<td><label>
								<input type="radio" name="PrintNotes" value="last" id="LastNote" />
								Last Note</label></td>
						</tr>
						<tr>
							<td><label>
								<input type="radio" name="PrintNotes" value="all" id="AllNotes" />
								All Notes</label></td>
						</tr>
					</table></td>
			</tr>
			<tr>
				<th><span style="text-decoration:none">Minimum Balance</span></th>
				<td colspan="2" nowrap="nowrap" style="text-decoration:none"><input id="mintbal" name="mintbal" type="text" size="20" maxlength="20" value="<?php if(isset($_POST['mintbal'])) echo $_POST['mintbal']; else echo '0'; ?>" ></td>
			</tr>
			<tr>
				<th>&nbsp;</th>
				<td colspan="2" nowrap="nowrap" style="text-decoration:none">
					<div class="acbtnrecords">
						<input type="button" name="DisplayUnTouchedAccountsReport" id="DisplayUnTouchedAccountsReport" value="Display Untouched Accounts Report" onclick="printReport();" />
					</div>
					<div class="exporticons">
						<!-- <img src="/img/icon-pdf.png" onClick="return printPDFXLS(1)" style="margin-left:5px;float: ;cursor: pointer;margin-right: 10px;margin-bottom: -6px;position: absolute;">&nbsp;&nbsp; -->
						<img src="/img/icon-xls.png" onClick="return printPDFXLS()" style="margin-left: 15px;cursor: pointer;margin-right: 15px;position: absolute;margin-bottom: -6px;" >
					</div>
					
				</td>
			</tr>
		</table>
	</form>
	
	</fieldset>
</div>
<div id="paginationlinks"></div>
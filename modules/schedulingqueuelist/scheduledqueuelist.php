<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 
// Select Call Record $callid
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();
$user = getuser();
//lockuser = '$user' AND
$callquery = "
		SELECT * 
		FROM case_scheduling_queue 
			LEFT JOIN cases 
			ON csqcrid=crid 
			LEFT JOIN patients 
			ON crpaid=paid 
			LEFT JOIN doctors
			ON crrefdmid=dmid
			LEFT JOIN doctor_locations
			ON crrefdlid=dlid
			WHERE crcasestatuscode = 'PEN'
			AND csqschcalldate < (NOW() + INTERVAL 1 MONTH) AND csqschcalldate >= (NOW() - INTERVAL 1 MONTH)
			ORDER BY csqpriority, csqschcalldate, csqid desc
			";
//}
if($callresult = mysqli_query($dbhandle,$callquery)) {
	if(!mysqli_num_rows($callresult)) {
		error("002", "No scheduled queue there");	
	}
}
else
	error("001", mysqli_error($dbhandle));

?>

<?php if(isset($_GET['newform'])) : ?>
<div class="containedBox">
<!-- New form with bootstrap -->
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>NETPT-APP</title>
  </head>
  <body>

	<div class="container-fluid">
		<div class="row justify-content-md-center">
			<div class="col-12">
				<ul class="nav nav-tabs" id="myTab" role="tablist">
				  <li class="nav-item">
				    <a class="nav-link" id="home-tab" data-toggle="tab" href="#scheduling-queue" role="tab" aria-controls="home" aria-selected="false">Scheduling Queue</a>
				  </li>
				  <li class="nav-item">
				    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#search-patient" role="tab" aria-controls="profile" aria-selected="false">Search Patients</a>
				  </li>
				  <li class="nav-item">
				    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#search-cases" role="tab" aria-controls="contact" aria-selected="false">Search Cases</a>
				  </li>
				  <li class="nav-item">
				    <a class="nav-link active" id="contact-tab" data-toggle="tab" href="#scheduled-queue" role="tab" aria-controls="contact" aria-selected="true">Scheduling Queue List</a>
				  </li>
				</ul>
				<div class="tab-content" id="myTabContent">
				  <div class="tab-pane fade" id="scheduling-queue" role="tabpanel" aria-labelledby="home-tab">Scheduling Queue</div>
				  <div class="tab-pane fade" id="search-patient" role="tabpanel" aria-labelledby="profile-tab">Search Patients</div>
				  <div class="tab-pane fade" id="search-cases" role="tabpanel" aria-labelledby="contact-tab">Search Cases</div>
				  <div class="tab-pane fade show active" id="scheduled-queue" role="tabpanel" aria-labelledby="contact-tab">
				  	<div class="row mt-4">
				  		<div class="col-12">
							<table class="table">
							  	<thead>
							  		<tr>
							  			<th>Case Number</th>
							          	<th>Last Name</th>
							          	<th>First Name</th>
							          	<th>Scheduled Date</th>
							          	<th>DOB</th>
							          	<th>Clinic</th>
							          	<th>S1 csqpriority</th>
							          	<th>S2 csqschcalldate</th>
							          	<th>S3 csqid</th>
							          	<th>Case Status</th>
							          	<th>Actions</th>
							  		</tr>
							  	</thead>
							  	<tbody>
						  			<?php while ($row = mysqli_fetch_assoc($callresult)) { ?>
						  				<tr>
											<td><?php echo $row['crid']; ?></td>
											<td><?php echo $row['palname']; ?></td>
											<td><?php echo $row['pafname']; ?></td>
											<td><?php echo displayDate($row['csqschcalldate']);?></td>
											<td><?php echo displayDate($row['padob']);?></td>
											<td><?php if(!empty($row['crcnum'])) echo $row['crcnum']; else echo "Not Assigned"; ?></td>
											<td><?php echo $row['csqpriority']; ?></td>
								          	<td><?php echo $row['csqschcalldate']; ?></td>
								          	<td><?php echo $row['csqid']; ?></td>
								         	<td><?php echo $row['crcasestatuscode']; ?></td>
								         	<td><a href=""><i class="fa fa-calendar" ></i></a></td>
						  				</tr>
						  			<?php } ?>
							  	</tbody>
							</table>
				  		</div>
				  	</div>
				  </div>
				</div>
			</div>
		</div>
	</div>
	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" type="text/css">

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>

</div>
<!-- Form end -->

<?php else: ?>


<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" type="text/css">
<div class="containedBox">
	<a href="/modules/schedulingqueuelist/reports/printXLS.php"><img src="/img/icon-xls.png" style="float:right;margin-left: 3px;cursor: pointer;margin-right: 15px;" ></a>
	<a href="/modules/schedulingqueuelist/reports/printPdf.php"><img src="/img/icon-pdf.png" style="float: right;cursor: pointer;margin-bottom: 10px;margin-right: 10px;">&nbsp;&nbsp;</a>
  <fieldset style="margin-top: 8px">
    <legend style="font-size:large;">
    Scheduled Queue List 
    </legend>
    <form method="post" name="searchResults" id="searchResults" onsubmit="return validateDate()">
      <table border="1" cellpadding="3" cellspacing="0" width="100%" >
        <tr>
          <th><input name="sort[casenumber]" type="submit" value="Case Number" /></th>
          <th><input name="sort[palname]" type="submit" value="Last Name" /></th>
          <th><input name="sort[pafname]" type="submit" value="First Name" /></th>
          <th><input name="sort[scheduleddate]" type="submit" value="Scheduled Date" /></th>
          <th><input name="sort[padob]" type="submit" value="DOB" /></th>
          <th><input name="sort[crcnum]" type="submit" value="Clinic" /></th>
          <th><input name="sort[s1csqpriority]" type="submit" value="S1 csqpriority" /></th>
          <th><input name="sort[s2csqschcalldate]" type="submit" value="S2 csqschcalldate" /></th>
          <th><input name="sort[s3csqid]" type="submit" value="S3 csqid" /></th>
          <th><input name="sort[casestatus]" type="submit" value="Case Status" /></th>
          <th></th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($callresult)) { ?>
        	<?php
        		// open edit case page
				$parm=array();
				$parm[]="button[".$row['crid']."]=Edit Case" ;
				$urlparm=urlencode(implode("&", $parm));

				// open edit patient page
				$pparm=array();
				$pparm[]="button[".$row['paid']."]=Edit Patient" ;
				$urlpparm=urlencode(implode("&", $pparm));

				// go to scheduling queue
				$qparm=array();
				$qparm[]="button[".$row['csqid']."]=fromschedulequeuelist" ;
				$urlqparm=urlencode(implode("&", $qparm));
			?>
    		<tr >
	          <td><input class="casenumber" name="navigation[<?php echo $urlparm ?>]" id="casenumber_<?php echo $row['crid']; ?>" onclick="return changeCaseBtnValue('<?php echo $row[crid]; ?>')" type="submit" value="<?php echo $row['crid']; ?>" /></td>
	          <td><input class="patientnumber" name="navigation[<?php echo $urlpparm ?>]" id="patientnumber_<?php echo $row['paid']; ?>" onclick="return changePatientBtnValue('<?php echo $row[paid]; ?>')" type="submit" value="<?php echo $row['palname']; ?>" /></td>
	          <td><?php echo $row['pafname']; ?></td>
	          <td><?php echo displayDate($row['csqschcalldate']);?></td>
	          <td><?php echo displayDate($row['padob']);?></td>
	          <td><?php if(!empty($row['crcnum'])) echo $row['crcnum']; else echo "Not Assigned"; ?></td>
	          <td><?php echo $row['csqpriority']; ?></td>
	          <td><?php echo $row['csqschcalldate']; ?></td>
	          <td><?php echo $row['csqid']; ?></td>
	          <td><?php echo $row['crcasestatuscode']; ?></td>
	          <!-- <td style="font-size:24px"><a href="javascript:void(0);" onclick="return goToSchedulingQueue('<?php echo $row['csqid']; ?>')" style="cursor: pointer;"><i class="fa fa-calendar" ></i></a></td> -->
	          <td><input name="navigation[<?php echo $urlqparm ?>]" type="submit" value="Scheduling Queue" /></td>
	          <!-- <td><input name="navigation[]" type="submit" value="Schedule Queue" /></td> -->
	        </tr>
        <?php } ?>
      </table>
    </form>
  </fieldset>
</div>
<script type="text/javascript">
	function validateDate(){
		//if(document.getElementById("casenumber").value){
			return true;
		//}
		//return false;
	}
	function changeCaseBtnValue(id){
		document.getElementById("casenumber_"+id).value = "Case Number";
	}
	function changePatientBtnValue(id){
		document.getElementById("patientnumber_"+id).value = "Edit Patient";
	}
	function goToSchedulingQueue(id){
		document.getElementById("sqBtn_"+id).type="submit"; 
		document.getElementById("sqBtn_"+id).click(); 
	}
</script>
<style type="text/css">
	tr:hover {
	  background-color: #ededed;
	}
	.casenumber,.patientnumber{
		box-sizing: unset;
	    background: #e6e6e6;
	    box-shadow: none;
	    border: none;
	    text-shadow: none;
	    stroke: "#cccccc"
	}
</style>
<?php endif; ?>
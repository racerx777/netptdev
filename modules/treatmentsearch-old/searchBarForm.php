<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(10);
// Format Clinic
//if(isset($_POST['searchcnum']) && !empty($_POST['searchcnum'])) {
//}

// Format Treatment Date Range Ymd
if(isset($_POST['searchfromtreatmentdate']) && !empty($_POST['searchfromtreatmentdate']))
	$_POST['searchfromtreatmentdate'] = date('m/d/Y', strtotime($_POST['searchfromtreatmentdate']));

if(isset($_POST['searchtotreatmentdate']) && !empty($_POST['searchtotreatmentdate']))
	$_POST['searchtotreatmentdate'] = date('m/d/Y', strtotime($_POST['searchtotreatmentdate']));

// Format Patient Number
if(isset($_POST['searchpnum']) && !empty($_POST['searchpnum'])) {
//dumppost();
}

// Format Patient Last Name
//if(isset($_POST['searchlname']) && !empty($_POST['searchlname'])) {
//}

// Format Patient First Name
//if(isset($_POST['searchfname']) && !empty($_POST['searchfname'])) {
//}

// Format Case Types (default to last selected)
if(isset($_SESSION['casetypes'])) {
	foreach($_SESSION['casetypes'] as $key=>$val)
		$selectedcasetype[$key]='';
}
if(isset($_POST['searchctmcode']) && !empty($_POST['searchctmcode']))
	$selectedcasetype[$_POST['searchctmcode']] = ' selected ';

// Format Visit Type (default to last selected)
if(isset($_SESSION['visittypes'])) {
	foreach($_SESSION['visittypes'] as $key=>$val)
		$selectedvisittype[$key]='';
}
if(isset($_POST['searchvtmcode']) && !empty($_POST['searchvtmcode']))
	$selectedvisittype[$_POST['searchvtmcode']] = ' selected ';

// Format Treatment Init (default to last selected)
if(isset($_SESSION['treatmenttypes'])) {
	foreach($_SESSION['treatmenttypes'] as $ttkey=>$val)
		$selectedtreatmenttype["$ttkey"]='';
}
if(isset($_POST['searchttmcode']) && !empty($_POST['searchttmcode']))
	$selectedtreatmenttype[$_POST['searchttmcode']] = ' selected ';

// Format Submit Dates Ymd
if(isset($_POST['searchfromsubmitdate']) && !empty($_POST['searchfromsubmitdate']))
	$_POST['searchfromsubmitdate'] = date('m/d/Y', strtotime($_POST['searchfromsubmitdate']));

if(isset($_POST['searchtosubmitdate']) && !empty($_POST['searchtosubmitdate']))
	$_POST['searchtosubmitdate'] = date('m/d/Y', strtotime($_POST['searchtosubmitdate']));
?>
<div class="containedBox" id="addBarForm">
	<fieldset>
	<legend class="boldLarger">Search Treatment Information</legend>
		<table width="100%" border="1" cellspacing="0" cellpadding="3">
			<tr>
				<th>Clinic</th>
                <?php if(isuserlevel(23) || isuserlevel(99) || isuserlevel(20)): ?>
                <th>Business Unit</th>
                <?php endif; ?>
				<th colspan="2">Treatment Date Range</th>
				<th>Patient Number</th>
				<th>Patient Last Name</th>
				<th>Patient First Name</th>
				<th>Case Type</th>
				<th>Visit Type</th>
				<th>Treatment Type</th>
				<th>Next Action Date</th>
				<th colspan="2">Submission Date Range</th>
<?php
		if(isuserlevel(20)) {
?>
				<th>Treatment Status</th>
<?php
		}
?>
			</tr>
			<tr>
				<td>
                    <?php if(isuserlevel(23) || isuserlevel(99) || isuserlevel(20)): ?>
                    <select name="searchcnum[]" id="searchcnum" multiple="multiple">
						<?php echo getSelectOptions($arrayofarrayitems=$_SESSION['useraccess']['clinics'], $optionvaluefield='cmcnum', $arrayofoptionfields=array('cmname'=>' (', 'cmcnum'=>')'), $defaultoption=$_POST['searchcnum'], $addblankoption=FALSE, $arraykey='', $arrayofmatchvalues=array()); ?>
					</select>
                    <?php else: ?>
                    <select name="searchcnum[]" id="searchcnum" >
                        <option value=""></option>
						<?php echo getSelectOptions($arrayofarrayitems=$_SESSION['useraccess']['clinics'], $optionvaluefield='cmcnum', $arrayofoptionfields=array('cmname'=>' (', 'cmcnum'=>')'), $defaultoption=$_POST['searchcnum'], $addblankoption=FALSE, $arraykey='', $arrayofmatchvalues=array()); ?>
					</select>
                    <?php endif; ?>
				</td>
                <?php if(isuserlevel(23) || isuserlevel(99) || isuserlevel(20)): ?>
                <td>
                    <select id="searchpnum" name="searchbnum">
                        <option value="">All</option>
                        <option value="WS" <?php echo (isset($_POST['searchbnum']) && $_POST['searchbnum'] == 'WS') ? 'selected=selected' : ''; ?>>WS</option>
                        <option value="NET" <?php echo (isset($_POST['searchbnum']) && $_POST['searchbnum'] == 'NET') ? 'selected=selected' : ''; ?>>NET</option>
                    </select>
                </td>
                <?php endif; ?>
                <td nowrap="nowrap" style="text-decoration:none"><input id="searchfromtreatmentdate" name="searchfromtreatmentdate" type="text" size="10" maxlength="10" value="<?php if(isset($_POST['searchfromtreatmentdate'])) echo $_POST['searchfromtreatmentdate']; ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="searchfromtreatmentdate1" id="searchfromtreatmentdate1" src="/img/calendar.gif" onclick="cal.select(document.forms['searchForm'].searchfromtreatmentdate,'searchfromtreatmentdate1','MM/dd/yyyy'); return false;" /></td>
				<td nowrap="nowrap" style="text-decoration:none"><input id="searchtotreatmentdate" name="searchtotreatmentdate" type="text" size="10" maxlength="10" value="<?php if(isset($_POST['searchtotreatmentdate'])) echo $_POST['searchtotreatmentdate']; ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="searchtotreatmentdate1" id="searchtotreatmentdate1" src="/img/calendar.gif" onclick="cal.select(document.forms['searchForm'].searchtotreatmentdate,'searchtotreatmentdate1','MM/dd/yyyy'); return false;" /></td>
				<td>
					<input id="searchpnum" name="searchpnum" type="text" size="6" maxlength="6" value="<?php if(isset($_POST['searchpnum'])) echo $_POST['searchpnum'];  ?>" />
<!--				<select name="searchpnum" id="searchpnum" onchange="updatePatientInformation(this.id)">

						<?php //echo getSelectOptions($arrayofarrayitems=$_SESSION['useraccess']['patients'], $optionvaluefield='pnum', $arrayofoptionfields=array('pnum'=>' (', 'lname'=>', ', 'fname'=>') ', 'cnum'=>''), $defaultoption=$_POST['thpnum'], $addblankoption=TRUE, $arraykey="", $arrayofmatchvalues=array()); ?>
				</select>
-->
				</td>
				<td><input id="searchlname" name="searchlname" type="text" size="10" maxlength="30" value="<?php if(isset($_POST['searchlname'])) echo $_POST['searchlname'];  ?>" onchange="upperCase(this.id)" <?php echo $namedisabled;?>></td>
				<td><input id="searchfname" name="searchfname" type="text" size="10" maxlength="30" value="<?php if(isset($_POST['searchfname'])) echo $_POST['searchfname'];  ?>" onchange="upperCase(this.id)" <?php echo $namedisabled;?>></td>
				<td><select name="searchctmcode" size="1">
						<option label=""></option>
						<?php
						foreach($_SESSION['casetypes'] as $key=>$val)
							echo "<option " . $selectedcasetype[$key] . " value='" . $key . "'>" . $_SESSION['casetypes'][$key] . "</option>";
					?>
					</select></td>
				<td><select name="searchvtmcode" size="1">
						<option label=""></option>
						<?php
						foreach($_SESSION['visittypes'] as $key=>$val)
							echo "<option " . $selectedvisittype[$key] . " value='" . $key . "'>" . $_SESSION['visittypes'][$key] . "</option>";
					?>
					</select></td>
				<td><select name="searchttmcode" size="1" onchange="displayProceduresAndModalities(this.value);">
						<option label=""></option>
						<?php
						foreach($_SESSION['treatmenttypes'] as $key=>$val)
							echo "<option " . $selectedtreatmenttype[$key] . " value='" . $key . "'>" . $_SESSION['treatmenttypes'][$key] . "</option>";
					?>
					</select></td>

				<td nowrap="nowrap" style="text-decoration:none"><input id="searchnadate" name="searchnadate" type="text" size="10" maxlength="10" value="<?php if(isset($_POST['searchnadate'])) echo $_POST['searchnadate']; ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="searchnadate1" id="searchnadate1" src="/img/calendar.gif" onclick="cal.select(document.forms['searchForm'].searchnadate,'searchnadate1','MM/dd/yyyy'); return false;" /></td>

				<td nowrap="nowrap" style="text-decoration:none"><input id="searchfromsubmitdate" name="searchfromsubmitdate" type="text" size="10" maxlength="10" value="<?php if(isset($_POST['searchfromsubmitdate'])) echo $_POST['searchfromsubmitdate']; ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="searchfromsubmitdate1" id="searchfromsubmitdate1" src="/img/calendar.gif" onclick="cal.select(document.forms['searchForm'].searchfromsubmitdate,'searchfromsubmitdate1','MM/dd/yyyy'); return false;" /></td>
				<td nowrap="nowrap" style="text-decoration:none"><input id="searchtosubmitdate" name="searchtosubmitdate" type="text" size="10" maxlength="10" value="<?php if(isset($_POST['searchtosubmitdate'])) echo $_POST['searchtosubmitdate']; ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="searchtosubmitdate1" id="searchtosubmitdate1" src="/img/calendar.gif" onclick="cal.select(document.forms['searchForm'].searchtosubmitdate,'searchtosubmitdate1','MM/dd/yyyy'); return false;" /></td>
<?php
		if(isuserlevel(20)) {
?>
				<td>
					<select name="searchsbmstatus">
						<option value=""></option>
						<option value="between 0 and 99"<?php if($_POST['searchsbmstatus']=="between 0 and 99") echo " selected"; ?>>Not Yet Submitted</option>
						<option value="between 100 and 199"<?php if($_POST['searchsbmstatus']=="between 100 and 199") echo " selected"; ?>>Treatments in UR</option>
						<option value="between 300 and 399"<?php if($_POST['searchsbmstatus']=="between 300 and 399") echo " selected"; ?>>Treatments in Patient Entry</option>
						<option value="between 500 and 599"<?php if($_POST['searchsbmstatus']=="between 500 and 599") echo " selected"; ?>>Treatments in Billing Entry</option>
						<option value="between 100 and 599"<?php if($_POST['searchsbmstatus']=="between 100 and 599") echo " selected"; ?>>Active Treatments</option>
						<option value="between 900 and 999"<?php if($_POST['searchsbmstatus']=="between 900 and 999") echo " selected"; ?>>Inactive Treatments</option>
						<option value="between 700 and 799"<?php if($_POST['searchsbmstatus']=="between 700 and 799") echo " selected"; ?>>Billed Treatments</option>
					</select>
				</td>
<?php
		}
?>
			</tr>
		</table>
		<div style="clear:both; margin:10px;">
			<div style="float:left">
				<input name="button[]" type="submit" value="Search" />
			</div>
			<div style="float:right">
				<input name="button[]" type="submit" value="Reset Search" />
			</div>
		</div>
	</fieldset>
</div>

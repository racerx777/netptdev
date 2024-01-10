<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(16); 
?>
<div class="centerFieldset" style="margin-top:100px;">
	<form method="post" name="docsForm">
	<input type="hidden" name="formLoaded" value="1" />
		<fieldset style="text-align:center;">
		<legend>Docs Requested/Sent for prescription $cpid</legend>
		<table style="text-align:left;">
			<tr>
				<td valign="top">Documents Requested/Sent </td>
				<td><div style="vertical-align:top"><input name="doc[1]" type="checkbox" value="1" />Prescription</div>
				<div style="vertical-align:top"><input name="doc[1]" type="checkbox" value="1" />PR-2</div>
				<div style="vertical-align:top"><input name="doc[1]" type="checkbox" value="1" />Demographics</div>
				<div style="vertical-align:top"><input name="doc[1]" type="checkbox" value="1" />Other<input id="cshdata" name="cshdata" type="text" size="64" maxlength="64" value="" onchange="uppercase(this.id)" /></div></td>
			</tr>
			<tr>
				<td colspan="2"><div>
						<div style="float:left; margin:20px;">
							<input name="button[]" type="submit" value="Back" />
						</div>
						<div style="float:left; margin:20px;">
							<input name="button[3]" type="submit" value="Confirm Docs Requested" />
						</div>
					</div></td>
			</tr>
		</table>
		</fieldset>
	</form>
</div>
<?php
exit;
?>
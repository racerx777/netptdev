<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(16); 
?>
<div class="centerFieldset" style="margin-top:100px;">
	<form method="post" name="docsForm">
	<input type="hidden" name="formLoaded" value="1" />
		<fieldset style="text-align:center;">
		<legend>Docs Sent - prescription <?php echo $_POST['cpid']; ?><input type="hidden" name="cpid" value="<?php if(isset($_POST['cpid'])) echo $_POST['cpid']; ?>" /></legend>
		<table style="text-align:left;">
			<tr>
				<td valign="top">Documents Sent </td>
				<td><div style="vertical-align:top"><input name="doc[PRESCRIPTION]" type="checkbox" value="1" />Prescription</div>
				<div style="vertical-align:top"><input name="doc[PR-2]" type="checkbox" value="1" />PR-2</div>
				<div style="vertical-align:top"><input name="doc[DEMOGRAPHICS]" type="checkbox" value="1" />Demographics</div>
				<div style="vertical-align:top"><input name="doc[OTHER]" type="checkbox" value="1" />Other<input id="other" name="other" type="text" size="64" maxlength="64" value="" onchange="upperCase(this.id)" /></div></td>
			</tr>
			<tr>
				<td colspan="2"><div>
						<div style="float:left; margin:20px;">
							<input name="button[]" type="submit" value="Back" />
						</div>
						<div style="float:left; margin:20px;">
							<input name="button[<?php echo $_POST['cpid']; ?>]" type="submit" value="Confirm Sent Docs/Info" />
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
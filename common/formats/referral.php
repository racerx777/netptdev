<div style="border:double; border-bottom-color:#000000">
				<table border="0" cellspacing="1" cellpadding="3" width="100%">
					<th style="font-size:14pt" align="left" colspan="4">Referral Information</th>
					<tr>
						<td>Referral Date</td>
						<td colspan="3"><?php echo $referraldate; ?></td>
					</tr>
					<?php if($_POST['crcasestatuscode']=='SCH' || $_POST['crcasestatuscode']=='ACT') { ?>
					<tr>
						<td>To Clinic:</td>
						<td><?php echo $clinicname; ?></td>
						<td>Eval Date:</td>
						<td><?php echo $evaldate; ?></td>
					</tr>
					<?php }
						echo("<th>STATUS: " . $_POST['crcasestatuscode'] . "</th>");
					?>
					<tr>
						<td>Therapy:</td>
						<td><?php echo $therapytype; ?>&nbsp;</td>
						<td>ReAd/Relo:</td>
						<td><?php if($_POST['crreadmit']==1) echo 'Y'; else echo 'N'; ?></td>
					</tr>
				</table>
			</div>
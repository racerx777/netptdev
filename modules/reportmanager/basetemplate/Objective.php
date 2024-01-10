<!-- Begin of Objective HTML -->

<div class="clearboth"></div>
<div class="position_relative">
	<!--  Objective Note -->
	<div class="position_relative" >
		<div class="col20pct">Objective Note</div>
		<div class="col70pct">
			<textarea cols="60" rows="3" id="rhobjectivenote" name="report[header][rhobjectivenote]"><?php echo $report['header']['rhobjectivenote'] ?></textarea>
		</div>
		<div class="col10pct" style="white-space:normal;">
			<input type="checkbox" id="rhobjectivenoteprint" name="report[header][rhobjectivenoteprint]" <?php if($report['header']['rhobjectivenoteprint']=='1') echo 'checked="checked"'; ?> />
			Print on Report</div>
	</div>

	<div class="clearboth" ></div>
	<div class="position_relative" >
		<hr />
	</div>

	<div class="clearboth" ></div>
	<div class="position_relative" >
		<div class="col20pct">Blood Pressure</div>
		<div class="col80pct">
			<input id="rhbloodpressure" name="report[header][rhbloodpressure]" value="<?php echo $report['header']['rhbloodpressure'] ?>" />
			Systolic/Distolic
		</div>
	</div>

	<div class="clearboth" ></div>
	<div class="position_relative" >
		<div class="col20pct">Heart Rate</div>
		<div class="col80pct">
			<input id="rhheartrate" name="report[header][rhheartrate]" value="<?php echo $report['header']['rhheartrate'] ?>" />
			Beats per Minute
		</div>
	</div>

	<?php
//	htmlTextAreaWithHelper('header','rhbloodpressure', 'Blood Pressure', 'bloodpressure', $helperarrays['bloodpressure'], $report, 20, 1);
//	htmlTextAreaWithHelper('header','rhheartrate', 'Heart Rate', 'heartrate', $helperarrays['heartrate'], $report, 20, 1);

$bodypartboxstyle='style="background-color: #4682B4;"';
$bodyparttestboxstyle='style="margin:1px; background-color: #3d719c;"';
$rhid=$report['header']['rhid'];

if(is_array($report['detail_bodypart']['record']) && count($report['detail_bodypart']['record'])>0) {
	foreach($report['detail_bodypart']['record'] as $bodypartrecord=>$bodypartarray) {
	?>
	<div <?php echo $bodypartboxstyle; ?> >
		<div class="clearboth" ></div>
		<div class="position_relative" >
			<hr />
		</div>
		<div class="clearboth" ></div>
		<div class="position_relative" id="bodypart_<?php echo $bodypartrecord; ?>" >
			<div class="col20pct" <?php echo $bodypartboxstyle; ?> >Body Part</div>
			<div class="col60pct" <?php echo $bodypartboxstyle; ?> >


<?php
		foreach($bodypartarray as $field=>$value) {
			$hiddenbodypartarray='<input type="hidden" id="'.$field.'_'.$bodypartrecord.'" name="report[detail_bodypart][record]['.$bodypartrecord.']['.$field.']" value="'.$value.'" />';
			echo $hiddenbodypartarray;
		} // for each
		echo $bodypartarray['imbsdescription'] 
	?>
			</div>
			<div class="col20pct" <?php echo $bodypartboxstyle; ?> >
				<input type="checkbox" id="checkboxDetailBodypart_<?php echo $bodypartrecord; ?>" name="checkboxDetailBodypart[<?php echo $bodypartrecord; ?>]" value="<?php echo $rhid; ?>" />
				Remove Body Part</div>
			<div id="Tests<?php echo $bodypartrecord; ?>" <?php echo $bodyparttestboxstyle; ?> >
				<?php



		$testcount["$bodypartrecord"]=0;
		if(is_array($report['detail_bodypart_test']['record']) && count($report['detail_bodypart_test']['record'])>0) {
			foreach($report['detail_bodypart_test']['record'] as $testrecord=>$testarray) {
				if($testarray['rdbtrdbid']==$bodypartrecord) {
		?>
				<div class="clearboth" ></div>
				<div class="position_relative" id="bodyparttest_<?php echo $testrecord; ?>" >
					<div class="col20pct" ><?php echo $testarray['rttype']." TEST:"; ?></div>
					<div class="col60pct" >
						<?php
					foreach($testarray as $field=>$value) {
						$hiddentestarray='<input type="hidden" id="'.$field.'_'.$testrecord.'" name="report[detail_bodypart_test][record]['.$testrecord.']['.$field.']" value="'.$value.'" />';
						echo $hiddentestarray;
					} // for each
					echo substr($testarray['rtname'],0,50); 
		?>
					</div>
					<div class="col10pct" <?php echo $bodyparttestboxstyle; ?> >
						<input type="checkbox" id="checkboxDetailBodypartTest_<?php echo $testrecord; ?>" name="checkboxDetailBodypartTest[<?php echo $testrecord; ?>]" value="<?php echo $bodypartrecord; ?>" />
						Remove Test</div>
				</div>
				<div class="clearboth" ></div>
				<div class="position_relative" >
					<div class="col20pct" align="right" >RESULT&nbsp;-&nbsp;</div>
					<div id="SliderDiv1" class="col70pct" >
						<input type="text" id="<?php echo "rdbtrtid_$testrecord"; ?>" name="report[detail_bodypart_test][record][<?php echo $testrecord; ?>][rdbtresult1]" value="<?php echo $testarray['rdbtresult1']; ?>" size="5" >
						<?php echo $testarray['rtmname'] ?> </div>
					<div class="col10pct" id="test1_buttons" >&nbsp;</div>
				</div>
				<div class="clearboth" ></div>
				<div class="position_relative" >
					<div class="col20pct" align="right" >NOTE&nbsp;-&nbsp;</div>
					<div class="col80pct" >
						<textarea rows="3" cols="60" id="<?php echo "rdbtrtid_$testrecord"; ?>" name="report[detail_bodypart_test][record][<?php echo $testrecord; ?>][rdbtnote]"><?php echo $testarray['rdbtnote']; ?></textarea>
					</div>
				</div>
				<div class="clearboth" ></div>
				<div class="position_relative" >
					<div class="col100pct">&nbsp;</div>
				</div>
				<?php 
					$testcount["$bodypartrecord"]++;
				} // if testarray
			}
		} // if detail array count > 0
	?>
			</div>
		</div>
		</div>
		<div class="clearboth" ></div>
		<div class="position_relative" >
			<div class="col20pct">&nbsp;</div>
			<div class="col60pct">
				<input type="submit" id="addDetailBodypartTest_<?php echo $bodypartrecord; ?>" name="button[<?php echo $bodypartrecord; ?>]" value="Add Test" />
				<input type="submit" id="addTestsUsingTemplate_<?php echo $bodypartrecord; ?>" name="button[<?php echo $bodypartrecord; ?>]" value="Add Tests Using Template" <?php if($testcount["$bodypartrecord"]>0) echo 'disabled="disabled"' ?> />
				<input type="submit" id="saveTestsTemplate_<?php echo $bodypartrecord; ?>" name="button[<?php echo $bodypartrecord; ?>]" value="Save Tests Template" <?php if($testcount["$bodypartrecord"]==0) echo 'disabled="disabled"' ?> />
				
			</div>
			<div class="col20pct"><input type="submit" id="removeDetailBodypartTest_<?php echo $bodypartrecord; ?>" name="button[<?php echo $bodypartrecord; ?>]" value="Remove Tests" <?php if(count($report['detail_bodypart_test']['record'])==0) echo('disabled="disabled"'); ?>/></div>
		</div>
		<div class="clearboth" ></div>
		<div class="position_relative" > &nbsp; </div>
		<?php 
	} //  for each bodypart

?>
	</div>
	<?php
} // if
?>
	<div class="clearboth" ></div>
	<div class="position_relative" >
		<div class="col60pct">
			<input type="submit" id="addDetailBodypart_<?php echo $rhid; ?>" name="button[<?php echo $rhid; ?>]" value="Add Body Part" <?php if(count($report['detail_bodypart']['record'])==0) echo('disabled="disabled"'); ?> />
			<input type="submit" id="addUsingDx" name="button[<?php echo $rhid; ?>]" value="Add Using Dx" <?php if(count($report['detail_bodypart']['record'])>0 || empty($report['header']['rhicd9dxbodypart1']) ) echo('disabled="disabled"'); ?> />
		</div>
		<div class="col20pct">
			<input type="submit" id="removeDetailBodypart_<?php echo $rhid; ?>" name="button[<?php echo $rhid; ?>]" value="Remove Body Parts" <?php if(count($report['detail_bodypart']['record'])==0) echo('disabled="disabled"'); ?>/>
		</div>
		<div class="col20pct">&nbsp;</div>
	</div>
	<div class="clearboth" ></div>
	<div class="position_relative" >
		<div class="col100pct">&nbsp;</div>
	</div>

</div>

<div class="clearboth"></div>

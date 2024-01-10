<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();
$dbselect = dbselect($dbhandle);
function blockHtml($id=NULL, $class=NULL, $style=NULL) {
	$html=array();
	if(!empty($id))
		$html[]=' id="'.$id.'"';
	if(!empty($class))
		$html[]=' class="'.$class.'"';
	if(is_array($style)) {
		if($count($style)>0) {
			$combinedstyle=array();
			foreach($style as $property=>$value) // colon-ize keyed array $stylearray['left-margin']=>'30px' to $style[0]=>"left-margin:30px'
				$combinedstyle[]="$property:$value";
			$style = implode("; ", $combinedstyle); // implode values with ; and wrap 
		}
	}
	if(is_string($style)) {
		if(!empty($style))
			$html[]=' style="'.$style.'"';
	}
	$htmlstring=implode($html);
	$block['open']="<div$htmlstring> <!-- Begin Block -->
";
	$block['close']='</div> <!-- End Block-->
';
	return($block);
}

function buildLiteral($blockelement) {
	$rtbetype=$blockelement['rtbetype']; // blank, BLOCK, ELEMENT
	$rtbeidref=$blockelement['rtbeidref']; // if BLOCK / ELEMENT referenceing id

	if(!empty($blockelement['rtbeselector'])) // selector id
		$selector=' id="'.$blockelement['rtbeselector'].'"'; // class 

	if(!empty($blockelement['rtbeclass']))
		$class=' class="'.$blockelement['rtbeclass'].'"'; // class 

	if(!empty($blockelement['rtbestyle']))
		$style=' style="'.$blockelement['rtbestyle'].'"'; // style 

	if(!empty($blockelement['rtbehtml'])) // additional html
		$addlhtml=$blockelement['rtbehtml'];

	if(!empty($blockelement['rtbedescription'])) // additional html
		$title=$blockelement['rtbedescription'];

	$rtbetagtype=$blockelement['rtbetagtype']; // how to display this element

	$rtename=$blockelement['rtbetagtype'];
	$title=$blockelement['rtbetagtype'];
	$rtereturn='<DIV'.$selector.$class.$style.' title="'.$rtename.'" title="'.$title.'" '.$addlhtml.' >'.$_POST["$rtename"].'</DIV>';
	return($rtereturn);
}

function buildElement($blockelement) {
	unset($rtereturn);
	$rtbetype=$blockelement['rtbetype']; // blank, BLOCK, ELEMENT
	$rtbeidref=$blockelement['rtbeidref']; // if BLOCK / ELEMENT referenceing id

	if(!empty($blockelement['rtbeselector'])) // selector id
		$selector=' id="'.$blockelement['rtbeselector'].'"'; // class 

	if(!empty($blockelement['rtbeclass']))
		$class=' class="'.$blockelement['rtbeclass'].'"'; // class 

	if(!empty($blockelement['rtbestyle']))
		$style=' style="'.$blockelement['rtbestyle'].'"'; // style 

	if(!empty($blockelement['rtbehtml'])) // additional html
		$addlhtml=$blockelement['rtbehtml'];

	if(!empty($blockelement['rtbedescription'])) // additional html
		$title=$blockelement['rtbedescription'];

	$rtbetagtype=$blockelement['rtbetagtype']; // how to display this element

	$selectelement="SELECT * FROM report_template_element where rteid='$rtbeidref'";
	if($resultelement=mysql_query($selectelement)) {
		if($element=mysql_fetch_assoc($resultelement)) {
			$rtetype=$element['rtetype'];
			$rtename=$element['rtename'];
			$rtevalue=$element['rtevalue'];
	//<img class="{instance.classname}" id="{instance.id}" name="{instance.name}" title="{instance.title}" src="{instance.src}" alt="{instance.alt}" height="{instance.height}" width="{instance.width}" />
			if($rtetype=='IMAGE')
				$rtereturn='<IMG'.$selector.$class.$style.' SRC="'.$rtevalue.'" name="'.$rtename.'" title="'.$title.'" '.$addlhtml.' >';
	
	//<div class="{instance.class}">This is text Title</div>
			if($rettype=='LITERAL')
				$rtereturn='<DIV'.$selector.$class.$style.' title="'.$rtename.'" title="'.$title.'" '.$addlhtml.' >'.$_POST["$rtename"].'</DIV>';
	
			if($rtetype=='DATA') { 
	// Assume display div
				if(empty($rtbetagtype))
					$rtereturn='<DIV'.$selector.$class.$style.' label="'.$rtename.'" title="'.$title.'" '.$addlhtml.' >'.$_POST["$rtename"].'</DIV>';
				if($rtbetagtype=='INPUT type="text"') 
	// <INPUT type=”text” class="{instance.classname}" id="{instance.id}" name="{instance.name}" title="{instance.title}" size="{instance.size}" maxlength="{instance.maxlength}" value="{instance.defaultvalue}" >
					$rtereturn='<INPUT type="text"'.$selector.$class.$style.' name="'.$rtename.'" title="'.$title.'" value="'.$_POST["$rtename"].'" '.$addlhtml.' >';
	
	
	// FIND A WAY TO DEAL WITH CHECKED ITEMS
	
	
	//<INPUT type="checkbox" class="{instance.classname}" id="{instance.id}" name="{instance.name}" title="{instance.title}" value="{instance.defaultvalue}" checked="{instance.checked}" >				
				if($rtbetagtype=='INPUT type="checkbox"')
					$rtereturn='<INPUT type="checkbox"'.$selector.$class.$style.' name="'.$rtename.'" title="'.$title.'" value="'.$_POST["$rtename"].'" '.$addlhtml.' >';
	//<INPUT type="radio" class="{instance.classname}" id="{instance.id}" name="{instance.radio.name}" title="{instance.title}" value="{instance.defaultvalue}" checked="{instance.checked}" >				if($rtbetagtype=='INPUT type="radio"')
				if($rtbetagtype=='INPUT type="radio"')
					$rtereturn='<INPUT type="radio"'.$selector.$class.$style.' name="'.$rtename.'" title="'.$title.'" value="'.$_POST["$rtename"].'" '.$addlhtml.' >';
	//<select class="{instance.classname}" id="{instance.name}" name="{instance.name}" title="{instance.title}" tabindex="">
				if($rtbetagtype=='SELECT')
					$rtereturn='<SELECT '.$selector.$class.$style.' name="'.$rtename.'" title="'.$title.'" value="'.$_POST["$rtename"].'" '.$addlhtml.' >';
	
	
	// FIND A WAY TO DEAL WITH selected
	
	
	//	<option class="{instance.class}" id="{instance.option.id}" label="{instance.option.label}" selected="selected" title="{instance.option.title}" value="{instance.option.value}">{instance.option.description}</option>
				if($rtbetagtype=='OPTION')
					$rtereturn='<OPTION'.$selector.$class.$style.' label="'.$rtename.'" title="'.$title.'" value="'.$_POST["$rtename"].'" '.$addlhtml.' >'.$title.'</OPTION>';
	//	<textarea class="{instance.class}" id="{instance.option.id}" label="{instance.option.label}" selected="selected" title="{instance.option.title}" value="{instance.option.value}">{instance.option.description}</option>
				if($rtbetagtype=='TEXTBOX')
					$rtereturn='<TEXTAREA'.$selector.$class.$style.' label="'.$rtename.'" title="'.$title.'" '.$addlhtml.' >'.$_POST["$rtename"].'</TEXTAREA>';
			}
		}
	}
	return($rtereturn);
}

function buildBlock($rtbid, $recursivecall=NULL) {
// Get Block
	$selecttemplateblock="SELECT * FROM report_template_block where rtbid='$rtbid' ORDER BY rtbdispseq";
	if($resulttemplateblock=mysql_query($selecttemplateblock)) {
		if($templateblock=mysql_fetch_assoc($resulttemplateblock)) {

			if(!empty($templateblock['rtbclassposition'])) 
				$classposition=' class="'.$templateblock['rtbclassposition'].'"'; // BLOCK wrapper position class 	
			if(!empty($templateblock['rtbclassmargin']))
				$classmargin=' class="'.$templateblock['rtbclassmargin'].'"'; // BLOCK wrapper margin class 
			if(!empty($templateblock['rtbclassalign']))
				$classalign=' class="'.$templateblock['rtbclassalign'].'"'; // BLOCK wrapper alignment class 
	
			if(!empty($templateblock['rtbselector'])) {
				$selectorposition=' id="position_'.$templateblock['rtbselector'].'"'; // BLOCK selector id 
				$selectormargin=' id="margin_'.$templateblock['rtbselector'].'"'; // BLOCK selector id 
				$selectoralign=' id="align_'.$templateblock['rtbselector'].'"'; // BLOCK selector id 
				$selector=' id="'.$templateblock['rtbselector'].'"'; // BLOCK selector id 
			}
			if(!empty($templateblock['rtbclass']))
				$class=' class="'.$templateblock['rtbclass'].'"'; // BLOCK class 
			if(!empty($templateblock['rtbstyle']))
				$style=' style="'.$templateblock['rtbstyle'].'"'; // BLOCK style 
			if(!$recursivecall) {
				$html[]='<DIV'.$selectorposition.$classposition.'> <!-- BLOCK POSITION -->';
				$html[]='<DIV'.$selectormargin.$classmargin.'> <!-- BLOCK MARGIN -->';
				$html[]='<DIV'.$selectoralign.$classalign.'> <!-- BLOCK ALIGN -->';
			}
			$html[]='<DIV'.$selector.$class.'> <!-- BLOCK -->';
			$selecttemplateblockelement="SELECT * FROM report_template_block_element where rtbertbid='$rtbid' ORDER BY rtbedispseq";
			if($resulttemplateblockelement=mysql_query($selecttemplateblockelement)) {
				while($templateblockelement=mysql_fetch_assoc($resulttemplateblockelement)) {
					$rtbetype=$templateblockelement['rtbetype']; // blank, BLOCK, ELEMENT
					$rtbeidref=$templateblockelement['rtbeidref']; // if BLOCK / ELEMENT referenceing id
					$rtbeselector=$templateblockelement['rtbeselector']; // selector id
					$rtbeclass=$templateblockelement['rtbeclass']; // class 
					$rtbestyle=$templateblockelement['rtbestyle']; // style string/array
					$rtbehtml=$templateblockelement['rtbehtml']; // additional html
					$rtbetagtype=$templateblockelement['rtbetagtype']; // how to display this element
					if($rtbetype=='BLOCK')
						$html[]=buildBlock($rtbeidref, true);
					else {
						if($rtbetype=='ELEMENT')
							$html[]=buildElement($templateblockelement);
						else
							$html[]=buildLiteral($templateblockelement);
					}
				}
			}
			$html[]='</DIV> <!-- BLOCK -->';
				$html[]='</DIV> <!-- BLOCK ALIGN -->';
				$html[]='</DIV> <!-- BLOCK MARGIN -->';
				$html[]='</DIV> <!-- BLOCK POSITION -->';
		}
	}
	else
		echo(mysql_error());
// if stuff to build return stuff
	return($html);
}

function buildTemplate($rtid) {
// read block id header (doesn't have content only id, class, and style for div
// while block detail id read block detail (has content or link to another block id)
// if block detail is pointer to another block
// recurse that block id
// else output this block id open
// while end
// output this block id close
	$selecttemplate="SELECT * FROM report_template WHERE rtid='$rtid'";
	if($resulttemplate=mysql_query($selecttemplate)) {
		if($template=mysql_fetch_assoc($resulttemplate)) {
			$selecttemplatedetail="SELECT * FROM report_template_detail where rtdrtid='$rtid' ORDER BY rtddispseq";
			if($resulttemplatedetail=mysql_query($selecttemplatedetail)) {
				while($templatedetail=mysql_fetch_assoc($resulttemplatedetail)) {
					$rtbid=$templatedetail['rtdrtbid'];
					$html[]=buildBlock($rtbid);
				}
echo('<textarea cols="150" rows="150">');
dump("html",$html);
echo("</textarea>");
			}
		}
	}
}
?>
<style>
body {
	margin: 0;
	padding: 0;
	width:1152px;
}
.block {
	position:relative;
}

.block_relative {
	position:relative;
}

.margin_20 {
	margin:20px;
}
.left-margin_0px {
	margin-left:0px;
}
.left-margin_10 {
	margin-left:10px;
}
.left-margin_30 {
	margin-left:30px;
}

.width_100pct_clearboth {
	width:99%;
	clear:both;
}
.width_33pct_float_left {
	float:left;
	width:33%;
	white-space:nowrap;
}
.width_20pct_float_left {
	float:left;
	width:20%;
	white-space:nowrap;
}
.width_80pct_float_left {
	float:left;
	width:80%;
	white-space:nowrap;
}
.clearboth {
	clear:both;
}

.heading1 {
	font-size:xx-large;
}
.heading2 {
	font-size:x-large;
}
.heading3 {
	font-size:large;
}
.note {
	font-style:italic;
}
.text-align_center {
	text-align:center;
}
.text-align_left {
	text-align:left;
}
.label {
	font-style:normal;
	font-weight:bold;
}
.font-style_normal {
	font-style:normal;
}

<!-- Block Position Styles -->
<!-- Block Margin Styles -->
<!-- Block Alignment Styles -->
<!-- Text Font Styles -->
<!-- Text Margin Styles -->
<!-- Text Alignment Styles -->
</style>
<?php 

buildTemplate(1);
exit();

$block[0]=blockHtml("","block","");
$block[1]=blockHtml("","left-margin_0","");
$block[2]=blockHtml("","value","");
echo $block[0]['open'];
echo $block[1]['open'];
echo $block[2]['open'];
?>
				<div class="text-align_center">
					<div><img width="216px" src="../../../img/wsptn logo bw outline.jpg" /></div>
					<div class="margin_20">
						<div>{CLINIC_NAME}</div>
						<div>{CLINIC_ADDRESS1}</div>
						<div>{CLINIC ADDRESS2}</div>
						<div>{CLINIC_PHONE}</div>
						<div>{CLINIC_FAX}</div>
					</div>
				</div>
<?php
echo $block[2]['close'];
echo $block[1]['close'];
echo $block[0]['close'];
?>
	<div style="margin:20px;">&nbsp;</div>

	<div class="block"> <!-- Level 1 Container -->
		<div class="left-margin_0"> <!-- Level 1 Margin and Text Alignment -->
			<div class="heading1">
				<div class="text-align_center">
					{REPORT_TITLE}
				</div>
			</div>
		</div>
	</div>

	<div style="margin:20px;">&nbsp;</div>

	<div class="block"> <!-- Level 1 Container -->
		<div class="left-margin_10"> <!-- Level 1 Margin and Text Alignment -->
			<div class="heading1">
				{BLOCK_TITLE}
			</div>
			<div class="note">
				{BLOCK_NOTE}
			</div>
			<div class="left-margin_30">
				{BLOCK_DATA}
			</div>
		</div>
	</div>

	<div style="margin:20px;">&nbsp;</div>

	<!-- Subjective -->
	<div class="block"> <!-- Level 1 Container -->
		<div class="left-margin_10"> <!-- Level 1 Margin and Text Alignment -->
			<div class="heading1">Subjective</div>
			<div class="note">(note or not note)</div>
		
			<div class="block"> <!-- Level 2 Container -->
				<div class="left-margin_30"> <!-- Level 2 Margin and Text Alignment -->
					<div class="heading2">Current Condition</div>
					<div class="note">(note or not note)</div>
		
					<div class="block"> <!-- Level 3 Container -->
						<div class="left-margin_30"> <!-- Level 3 Margin and Text Alignment -->
							<div class="heading3">Details</div>
							<div class="note"></div>
	
							<div class="block"> <!--Data Table Block-->
								<div class="width_100pct_clearboth">
									<div class="width_20pct_float_left">
										<div class="text-align_left"></div>
									</div>
									<div class="width_80pct_float_left"></div>
								</div>
								<div class="clearboth"></div>
								<hr style="margin:1px;"/>
								<div class="width_100pct_clearboth">
									<div class="width_20pct_float_left">Chief Complaint 1</div>
									<div class="width_80pct_float_left">hurts</div>
								</div>
								<div class="clearboth"></div>
								<div class="width_100pct_clearboth">
									<div class="width_20pct_float_left">Onset Date</div>
									<div class="width_80pct_float_left">2/1/11</div>
								</div>
								<div class="clearboth"></div>
								<div class="width_100pct_clearboth">
									<div class="width_20pct_float_left">Type of Injury</div>
									<div class="width_80pct_float_left">Workers Compensation</div>
								</div>
								<div class="clearboth"></div>
								<div class="width_100pct_clearboth">
									<div class="width_20pct_float_left">Specific Injury</div>
									<div class="width_80pct_float_left">asdflkasd</div>
								</div>
								<div class="clearboth"></div>
								<hr style="margin:1px;"/>
								<div class="linespace">&nbsp;</div>
							</div>
						</div>
					</div>
	
					<div class="block"> <!-- Level 3 Container -->
						<div class="left-margin_30"> <!-- Level 3 Margin and Text Alignment -->
							<div class="heading3">Pain History</div>
							<div class="note">(note or no note)</div>
		
							<div class="block"> <!-- Level 4 Container -->
								<div class="left-margin_30"> <!-- Level 4 Margin and Text Alignment -->
									<div class="heading3">Pain Area</div>
									<div class="note">(note or no note)</div>
		
									<div class="block"> <!--Data Table Block-->
										<div class="width_100pct_clearboth">
											<div class="width_20pct_float_left">Area</div>
											<div class="width_20pct_float_left">Current</div>
											<div class="width_20pct_float_left">Best</div>
											<div class="width_20pct_float_left">Worst</div>
										</div>
										<div class="clearboth"></div>
										<hr style="margin:1px;"/>
										<div class="width_100pct_clearboth">
											<div class="width_20pct_float_left">Test Description 1</div>
											<div class="width_20pct_float_left">8/10</div>
											<div class="width_20pct_float_left">8/10</div>
											<div class="width_20pct_float_left">10/10</div>
										</div>
										<div class="clearboth"></div>
										<div class="width_100pct_clearboth">
											<div class="width_20pct_float_left">Test Description 1</div>
											<div class="width_20pct_float_left">8/10</div>
											<div class="width_20pct_float_left">8/10</div>
											<div class="width_20pct_float_left">10/10</div>
										</div>
										<div class="clearboth"></div>
										<div class="width_100pct_clearboth">
											<div class="width_20pct_float_left">Test Description 1</div>
											<div class="width_20pct_float_left">8/10</div>
											<div class="width_20pct_float_left">8/10</div>
											<div class="width_20pct_float_left">10/10</div>
										</div>
										<div class="clearboth"></div>
										<hr style="margin:1px;"/>
										<div class="linespace">&nbsp;</div>
									</div>
		
								</div>
							</div>
		
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

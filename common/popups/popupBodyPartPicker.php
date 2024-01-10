<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(13); 
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

?>
<script type="text/javascript">
<!--
function selectBodypart(bodypartcode, name){
// Add an element to the list of bodyparts table
	var bodypartlistdiv=document.getElementById('bodypartlistdiv');
	var id='bodypartlist_'+bodypartcode;
	var div=document.getElementById(id);
	if (div != null) {
		bodypartlistdiv.removeChild(div);
	}
	else {
		var newdiv = document.createElement('div');
		newdiv.id=id;
		newdiv.innerHTML = "<input type='checkbox' id='bodypart_"+bodypartcode+"' name='addReportBodypart["+bodypartcode+"]' value='"+bodypartcode+"' checked='checked' >"+name;
		bodypartlistdiv.appendChild(newdiv);
	}
	if(bodypartlistdiv.childElementCount>0) 
		document.getElementById("ConfirmAddBodyparts").disabled=false;
	else
		document.getElementById("ConfirmAddBodyparts").disabled=true;
}

function showBodypart() {
}

function hideBodypart() {
}
//-->
</script>
<?php
function cleanString($string) {
	$string = preg_replace('/[^a-zA-Z0-9-]/', '', $string);
	return($string);
}

function displayImageMap($image, $map, $imagepath=NULL) {

	$imageClean=cleanString($image);
	$mapClean=cleanString($map);
	
	if(empty($imagepath))
		$imagepath='/img/';

	$imagesrc=$imagepath.$image;
	$mapelements=array();
	$select="SELECT * FROM report_bodypart_imagemap WHERE rbiimage='$image' and rbimap='$map'";	
	if($result=mysqli_query($dbhandle,$select)) {
		while($row=mysqli_fetch_assoc($result)) {
			$rbicode=$row['rbicode'];
			$alttitle=$row['rbialttitle'];
			$shape=$row['rbishape'];
			$id=$cleanimage.$cleanmap.$row['rbiid'];
			$coords=$row['rbicoords'];

			$selectBodypart="selectBodypart('$rbicode','$alttitle');";
			$javascripts='onclick="'.$selectBodypart.'"';

//			$javascripts=$row['rbijavascripts'];

			$id="bodypart_".$rbicode;
			$mapelements[]='<area alt="'.$alttitle.'" shape="'.$shape.'" id="'.$id.'" title="'.$alttitle.'" href="#" coords="'.$coords.'" '.$javascripts.' />';
		}
	}
	if(count($mapelements)>0) {
		echo '<img src="'.$imagesrc.'" usemap="'.$map.'" alt="Image Map of '.$image.' mapped with '.$map.'" />';
		echo '<map name="'.$map.'" id="'.$imageClean.$mapClean.'" >';
		foreach($mapelements as $index=>$maphtml)
			echo $maphtml;
		echo '</map>';
	}
	else {
		echo "Imagemap $image has no mapped areas using map $map.";
		exit();
	}
}

displayImageMap('body_labels.gif','bodyparts')
?>
<form id="selectBodypartsForm" name="selectBodypartsForm" method="post">
	<table border="1">
		<tr>
			<td colspan="2"><div id="bodypartlistdiv"></div></td>
		</tr>
		<tr>
			<td align="center"><input id="cancel" name="cancel" type="submit" value="Cancel Add Body Parts" /></td>
			<td align="center"><input id="ConfirmAddBodyparts" name="button[<?php echo $rhid; ?>]" type="submit" disabled="disabled" value="Confirm Add Body Parts" /></td>
		</tr>
	</table>
</form>
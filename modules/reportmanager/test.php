<style>
table, th, td
{
border: 1px solid black;
}
</style>
<?php
function error($num,$text) {
echo $num;
echo $text;
exit();
}

function makeOBJtableheader($bodypart, $testgroup, $measurementnames, $tabletitle=NULL, $resultnames=array('Current'), $bilateralnames=NULL) {
//	Make sure an measurementnames array is provided
	$measurementcount=0;
	if(is_array($measurementnames)) {
		$measurementcount=count($measurementnames);
		if($measurementcount<1) 
			error("999","Error:maketableheader:Function requires at least one measurementnames array element.");
	}
	else {
		error("999","Error:maketableheader:Function requires a measurementnames array.");
	}
		
//	Make sure a resultnames array is provided, or default to Current
	$resultcount=0;
	if(!is_array($resultnames)) 
		$resultnames=array('Current');

	if(is_array($resultnames)) {
		$resultcount=count($resultnames);
		if($resultcount<1) 
			error("999","Error:maketableheader:Function requires at least one resultnames array element.");
	}
	else {
		error("999","Error:maketableheader:Function requires a resultnames array.");
	}
		
//	Make sure that tabletitle string is provided or default it to "Test"
	if(empty($tabletitle))
		$tabletitle="Test";

//	Make sure that bilateralnames is an array or default it to an empty array
	$bilateralcount=0;
	if(is_null($bilateralnames)) 
		$bilateralnames=array();

	if(is_array($bilateralnames)) {
		$bilateralcount=count($bilateralnames);
//		if($bilateralcount<1) 
//			error("999","Error:maketableheader:Function requires at least one bilateralnames array element.");
	}
	else {
		error("999","Error:maketableheader:Function requires a bilateralnames array.");
	}

$table['id']="OBJ_$bodypart_$testgroup"; // OBJ_bodypart_testgroup; OBJ_10_1 for CSPINE & AROM
$table['class']="OBJ_$measurementname"; // OBJ_GIRTH




?>
	<table id="<?php echo $table['id']; ?>" class="<?php echo $table['class']; ?>" >
<?



// Compare and Bilateral
	if($resultcount==2 && $bilateralcount==2) { // Comparative and Bilateral
	?>
		<tr>
			<th rowspan="3" width="200px"><?php echo $tabletitle; ?></th>
			<th colspan="<?php echo 1+($measurementcount*$bilateralcount); ?>" rowspan="1" width="160px"><?php echo $resultnames[1]; ?></th>
			<th colspan="<?php echo 1+($measurementcount*$bilateralcount); ?>" rowspan="1" width="160px"><?php echo $resultnames[2]; ?></th>
			<th rowspan="3" width="60px">Units</th>
			<th rowspan="3" width="40px">&nbsp;</th>
		</tr>
		<tr>
	<?php
	// Current
		foreach($measurementnames as $measurementnameindex=>$measurementname) {
	?>
			<th colspan="2"><?php echo $measurementname; ?></th>
	<?php
		}
	
	// Previous
		foreach($measurementnames as $measurementnameindex=>$measurementname) {
	?>
			<th colspan="2"><?php echo $measurementname; ?></th>
	<?php
		}
	?>
		</tr>
		<tr>
	<?php
	// Current
		foreach($measurementnames as $measurementnameindex=>$measurementname) {
			foreach($bilateralnames as $bilateralnameindex=>$bilateralname) {
	?>
			<th><?php echo $bilateralname; ?></th>
	<?php
			}
		}
	
	// Previous
		foreach($measurementnames as $measurementnameindex=>$measurementname) {
			foreach($bilateralnames as $bilateralnameindex=>$bilateralname) {
	?>
			<th><?php echo $bilateralname; ?></th>
	<?php
			}
		}
	?>
		</tr>
	<?php
	}

// Compare and Not Bilateral
	if($resultcount==2 && $bilateralcount==0) { 
	?>
	<table id="<?php echo $table['id']; ?>" class="<?php echo $table['class']; ?>" width="<?php echo $table['width']; ?>" >
		<tr>
			<th rowspan="2" width="200px"><?php echo $tabletitle; ?></th>
			<th colspan="<?php echo ($measurementcount); ?>" rowspan="1" width="160px">Current Status:</th>
			<th colspan="<?php echo ($measurementcount); ?>" rowspan="1" width="160px" >Previous Status:</th>
			<th rowspan="2" width="60px">Units</th>
			<th rowspan="2" width="40px">&nbsp;</th>
		</tr>
		<tr>
	<?php
	// Current
		foreach($measurementnames as $measurementnameindex=>$measurementname) {
	?>
			<th><?php echo $measurementname; ?></th>
	<?php
		}
	
	// Previous
		foreach($measurementnames as $measurementnameindex=>$measurementname) {
	?>
			<th><?php echo $measurementname; ?></th>
	<?php
		}
	?>
		</tr>
	<?php
	}

// No Compare and Bilateral
	if($resultcount==1 && $bilateralcount==2) { 
	?>
	<table id="<?php echo $table['id']; ?>" class="<?php echo $table['class']; ?>" width="<?php echo $table['width']; ?>" >
		<tr>
			<th rowspan="3" width="200px"><?php echo $tabletitle; ?></th>
			<th colspan="<?php echo ($measurementcount*$bilateralcount); ?>" rowspan="1" width="160px">Current Status:</th>
			<th rowspan="3" width="60px">Units</th>
			<th rowspan="3" width="40px">&nbsp;</th>
		</tr>
		<tr>
	<?php
	// Current
		foreach($measurementnames as $measurementnameindex=>$measurementname) {
	?>
			<th colspan="2" rowspan="1"><?php echo $measurementname; ?></th>
	<?php
		}
	?>
		</tr>
		<tr>
	<?php
	// Current
		foreach($measurementnames as $measurementnameindex=>$measurementname) {
			foreach($bilateralnames as $bilateralnameindex=>$bilateralname) {
	?>
			<th><?php echo $bilateralname; ?></th>
	<?php
			}
		}
	?>
		</tr>
	<?php
	}

// No Compare and Not Bilateral
	if($resultcount==1 && $bilateralcount==0) { 
	?>
	<table id="<?php echo $table['id']; ?>" class="<?php echo $table['class']; ?>" width="<?php echo $table['width']; ?>" >
		<tr>
			<th rowspan="2" width="200px"><?php echo $tabletitle; ?></th>
			<th colspan="<?php echo ($measurementcount); ?>" rowspan="1" width="160px">Current Status:</th>
			<th rowspan="2" width="60px">Units</th>
			<th rowspan="2" width="40px">&nbsp;</th>
		</tr>
		<tr>
	<?php
	// Current
		foreach($measurementnames as $measurementnameindex=>$measurementname) {
	?>
			<th><?php echo $measurementname; ?></th>
	<?php
		}
	?>
		</tr>
	<?php
	}

?>
</table>
<?php
}

$bodypart='10';
$testgroup='1';
$tabletitle='Active Motion';
$resultnames=array('Current'=>'30','Previous'=>'30');
$measurementnames=array('ROM'=>'10','MMT'=>'10');
$bilateralnames=array('R'=>'5','L'=>'5');
makeOBJtableheader($bodypart, $testgroup, $measurementnames, $tabletitle, $resultnames, $bilateralnames);

makeOBJtableheader($bodypart, $testgroup, $measurementnames, $tabletitle, $resultnames);

makeOBJtableheader($bodypart, $testgroup, $measurementnames, $tabletitle, NULL ,$bilateralnames);

makeOBJtableheader($bodypart, $testgroup, $measurementnames);

$tabletitle='Joint';
$resultnames=array('Current'=>'30', 'Previous'=>'30');
$measurementnames=array('Force Dir'=>'10', 'Grd'=>'10', 'End-Feel'=>'10', 'Symptoms'=>'10');
$bilateralnames=NULL;
makeOBJtableheader($bodypart, $testgroup, $measurementnames, $tabletitle, $resultnames, $bilateralnames);
?>
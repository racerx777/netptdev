<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(11); 

require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


function getClinicInformation($code, $includeinactive=0) {
	$where=array();
	if(!empty($code))
		$where[] = "cmcnum='$code'";

	if($includeinactive == '1')
		$where[] = "cminactive='1'";

	if($includeinactive == '0')
		$where[] = "cminactive='0'";

	$wheresql=implode(" AND ",$where);
	if(!empty($wheresql))
		$wheresql = "WHERE $wheresql";
	$query = "
		SELECT *
		FROM master_clinics 
		$wheresql 
		ORDER BY cmname
		LIMIT 1
	";
	if($result = mysqli_query($dbhandle,$query)) {
		if($row = mysqli_fetch_assoc($result)) {
			$thisarray=array();
			foreach($row as $field=>$value) 
				$thisarray["$field"]=$value;
			return($thisarray);
		}
	}
	else {
		error("001","getClinicInformation<br>$query<br>".mysqli_error($dbhandle));
	}
	return(false);
}


// if edit and case id provided...

function distance($lat1, $lon1, $lat2, $lon2, $unit) {
	$theta = $lon1 - $lon2;
	$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
	$dist = acos($dist);
	$dist = rad2deg($dist);
	$miles = $dist * 60 * 1.1515;
	$unit = strtoupper($unit);

	if ($unit == "K") {
		return ($miles * 1.609344);
	} else if ($unit == "N") {
		return ($miles * 0.8684);
	} else {
		return $miles;
	}
}

function getClinicTypeOptions($ttmcode=NULL, $inactive=0) {
	$thislist=array();
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	if(!empty($ttmcode))
		$where[] = "cttmttmcode='$ttmcode'";
	if(empty($inactive))
		$where[] = "cminactive='0'";
	if(count($where)>0) 
		$wheresql = "WHERE ".implode(" and ", $where);
	$query = "
	SELECT *
	FROM master_clinics 
	LEFT JOIN master_clinics_treatmenttypes
		on cmcnum=cttmcnum 
	$wheresql 
	ORDER BY cmname
	";

//dump("query",$query);


	if($result = mysqli_query($dbhandle,$query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			
			$thisarray=array();
			$thisarray['value']=$row['cmcnum'];
			$thisarray['title']=$row['cmname'] . ", " . $row['cmcity'];
			$thisarray['cmcity']=$row['cmcity'];
			$latitude = $row['latitude'];
			$longitude = $row['longitude'];
			$origin = $row['cmcity'];
			$origin = str_replace(' ', '+', $origin);
			$origin1 = $row['cmcity'].' '.$row['cmstate'].' '.$row['cmzip'];
			$origin1 = str_replace(' ', '+', $origin1);
			if(empty($latitude) || empty($longitude) || empty($_SESSION['plat']) || empty($_SESSION['plong'])){
				$api = file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=".$origin1."&destinations=".$_SESSION['a']."&key=AIzaSyCtgQzrja4KtWMZoko3V9SShu_XUa7gsPs");
			    $jsonde = json_decode($api);
		     	$distance = $jsonde->rows[0]->elements[0]->distance->text;
		     	$thisarray['distance']= $distance;
		     	$thisarray['rm_dis']= str_replace(',','',str_replace(' mi','',$distance));
			}else{
				$new_distance = distance(floatval($_SESSION['plat']),floatval($_SESSION['plong']),floatval($row['latitude']),floatval($row['longitude']),"M");
				$new_distance = "" .round( $new_distance, 2);
				$thisarray['distance']= $new_distance .' mi';
				$thisarray['rm_dis']= str_replace(',','',$new_distance);
			}

		    $thislist[]=$thisarray;
	       
		}
		usort($thislist, function($a, $b) {
		    return $a['rm_dis'] <=> $b['rm_dis'];
		});
		return($thislist);
	}
	else 
		error("001",mysqli_error($dbhandle));
	return(false);
}

function getMaster_Clinics($bnum=NULL, $cnum=NULL, $inactive=NULL) {
	$array = array();
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	if( !empty( $bnum ) )
		$wherearray[] = "cmbnum='$bnum'";
	if( !empty( $cnum ) )
		$wherearray[] = "cmcnum='$cnum'";
	if( empty( $inactive ) )
		$wherearray[]="cminactive='0'";
	if( count($wherearray) > 0)
		$where = 'WHERE '. implode(" and ", $wherearray);
	$query  = "
		SELECT * 
		FROM master_clinics 
		$where 
		";
	if($result = mysqli_query($dbhandle,$query)) {
		while($row = mysqli_fetch_assoc($result)) {
			$cmbnum=$row['cmbnum'];
			$cmcnum=$row['cmcnum'];
			$array[$cmbnum][$cmcnum] = $row;
		}
	}
	if(count($array)==1)
		return($array[$cmbnum][$cmcnum]);
	return($array);
}
?>
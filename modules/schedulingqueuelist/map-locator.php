<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 
// Select Call Record $callid
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();
$user = getuser();
$code_type = $_SESSION['crtherapytypecode'];
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
    //error("002", "No scheduled queue there"); 
  }
}
else
  error("001", mysqli_error($dbhandle));
  $p_lat = $_POST['p_latitude'];
  $p_lng = $_POST['p_longitude'];
  if(empty($p_lat) || empty($p_lng)){
      $address_new = $_POST['paaddress1'].' '.$_POST['pacity'].' '.$_POST['pastate'].' '.$_POST['pazip'];
      $url_new = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($address_new)."&key=AIzaSyCtgQzrja4KtWMZoko3V9SShu_XUa7gsPs&sensor=false";
      $geocode_new = file_get_contents($url_new);
      $json_new = json_decode($geocode_new);
      $lat_new = $json_new->results[0]->geometry->location->lat;
      $lng_new = $json_new->results[0]->geometry->location->lng;
      $update_new="UPDATE patients SET p_latitude='".$lat_new."', p_longitude='".$lng_new."' WHERE paid='".$_POST['paid']."'";
      mysqli_query($dbhandle,$update_new);
  }
// if (isset($_GET['test'])) {
    $location_arr = array();
    $match_arr = array();
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
    // function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000){
    //   // convert from degrees to radians
    //   $latFrom = deg2rad($latitudeFrom);
    //   $lonFrom = deg2rad($longitudeFrom);
    //   $latTo = deg2rad($latitudeTo);
    //   $lonTo = deg2rad($longitudeTo);

    //   $latDelta = $latTo - $latFrom;
    //   $lonDelta = $lonTo - $lonFrom;

    //   $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
    //     cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
    //   return $angle * $earthRadius;
    // }
    $query = "SELECT * FROM master_clinics LEFT JOIN master_clinics_treatmenttypes on cmcnum=cttmcnum WHERE cminactive = 0 ORDER BY cmname";
    if($result = mysqli_query($dbhandle,$query)) {
      while ($row = mysqli_fetch_assoc($result)) {
          if(!empty($row['cmaddress2']) || !empty($row['cmaddress1'])){
            // $new_distance = haversineGreatCircleDistance(floatval($p_lat),floatval($p_lng),floatval($row['latitude']),floatval($row['longitude']));
            $new_distance = distance(floatval($p_lat),floatval($p_lng),floatval($row['latitude']),floatval($row['longitude']),"M");
            $new_distance = "" .round( $new_distance, 2);
            $loc_address2 = $row['cmcity'].', '.$row['cmstate'].' '.$row['cmzip'];
            if($row['cttmttmcode'] == 'A'){
              $therapy_code = 'Acupuncture';
            }elseif($row['cttmttmcode'] == 'OT'){
              $therapy_code = 'Acupuncture';
            }elseif($row['cttmttmcode'] == 'PT'){
                $therapy_code = 'Physical Therapy';
            }elseif($row['cttmttmcode'] == 'P'){
              $therapy_code = 'Pool Therapy';
            }
            $replace_address = str_replace('#','',$row['cmaddress2']);
            if(!empty($replace_address)){
              // $map_address = $row['cmaddress2'].', '.$row['cmaddress1'] .' '.$row['cmcity'].' '.$row['cmzip'];
              $map_address = $row['cmaddress1'] .' '.$row['cmcity'].' '.$row['cmzip'].' '.$replace_address;
            }else{
              $map_address = $row['cmaddress1'] .', '.$row['cmcity'].', '.$row['cmzip'];
            }
            if(!empty($row['cmaddress2'])){
              $show_address = $row['cmaddress1'] .', '.$row['cmcity'].', '.$row['cmzip'].', '.$row['cmaddress2'];
            }else{
              $show_address = $row['cmaddress1'] .', '.$row['cmcity'].', '.$row['cmzip'];
            }
            $map_address = str_replace(' ','+',$map_address);
            $map_address =  'https://maps.google.com/?q='.$map_address;
            if($row['latitude'] != 0 && $row['longitude'] != 0){
	            if(isset($_GET['show_more'])){
	            	 $match_arr[$row['cmid']] = array('title' => $row['cmname'],'address1' => $row['cmaddress2'].' '.$row['cmaddress1'],'address2' => $loc_address2,'coords'=>array('lat' => floatval($row['latitude']),'lng'=> floatval($row['longitude'])),'cmid'=>$row['cmid'],'therapy_id'=>$row['cttmttmcode'],'placeId'=>$row['placeId'],'therapy_code'=>$therapy_code,'map_address' => $map_address,'show_distance'=>$show_address,'new_distance'=>$new_distance);
	            }else{
		            if($code_type == $row['cttmttmcode']){
		              $match_arr[$row['cmid']] = array('title' => $row['cmname'],'address1' => $row['cmaddress2'].' '.$row['cmaddress1'],'address2' => $loc_address2,'coords'=>array('lat' => floatval($row['latitude']),'lng'=> floatval($row['longitude'])),'cmid'=>$row['cmid'],'therapy_id'=>$row['cttmttmcode'],'placeId'=>$row['placeId'],'therapy_code'=>$therapy_code,'map_address' => $map_address,'show_distance'=>$show_address,'new_distance'=>$new_distance);  
		            }            	
	            }
	            $location_arr['locations'][$row['cmid']][] = array('title' => $row['cmname'],'address1' => $row['cmaddress2'].' '.$row['cmaddress1'],'address2' => $loc_address2,'coords'=>array('lat' => floatval($row['latitude']),'lng'=> floatval($row['longitude'])),'cmid'=>$row['cmid'],'therapy_id'=>$row['cttmttmcode'],'placeId'=>$row['placeId'],'therapy_code'=>$therapy_code,'map_address' => $map_address,'show_address'=>$show_address,'new_distance'=>$new_distance);
	        }
        }
        $location_arr['mapOptions'] = array('center' => array('lat'=> 38,'lng' => -100),'fullscreenControl' => 1,'mapTypeControl' =>'','streetViewControl' =>'','zoom' => 4,'zoomControl' => 1,'maxZoom' => 17);
        $location_arr['mapsApiKey'] = 'AIzaSyCtgQzrja4KtWMZoko3V9SShu_XUa7gsPs';
      }
    }
    $combined = array();
    if($code_type == 'A'){
      $code_name = 'Acupuncture';
    }elseif($code_type == 'OT'){
      $code_name = 'Acupuncture';
    }elseif($code_type == 'PT'){
        $code_name = 'Physical Therapy';
    }elseif($code_type == 'P'){
      $code_name = 'Pool Therapy';
    }
    foreach($match_arr as $match_data){
      foreach($location_arr['locations'][$match_data['cmid']] as $rowdata){
        if(($key = array_search($rowdata['cmid'], array_column($combined,'cmid'))) !== false){
        	if (empty($_GET) || isset($_GET['add_more'])) {
        		$combined[$key]['therapy_code'] = $code_name;
        	}else{
          		$combined[$key]['therapy_code'] .= ' , '.$rowdata['therapy_code'];
        	}
        }else{
          $combined[] = $rowdata;
        }
      }
    }
    unset($location_arr['locations']);
    
    // function sortByOrder($a, $b) {
    //     return $a['new_distance'] - $b['new_distance'];
    // }
    function order_by_weight($a, $b) {
        return $a['new_distance'] > $b['new_distance'] ? 1 : -1;
    }

    usort($combined, 'order_by_weight');
    $new_arr = array();
    $all_location_count = count($combined);
    $new_count = 0;
    if(isset($_GET['show_more'])){
    	$c_val = count($combined);
    }else if(!isset($_GET['add_more'])){
      $c_val = 4;
    }else{
      $c_val = count($combined);
    }
    foreach ($combined as $combined_value) {
      $new_arr[] = $combined_value;
      if($new_count == $c_val){
        break;
      }
      $new_count++;
    }
    // $location_arr['locations'] = $combined;
    $location_arr['locations'] = $new_arr;
    $location_arr = json_encode($location_arr);
    if($code_type == 'A'){
      $code_name = 'Acupuncture';
    }elseif($code_type == 'OT'){
      $code_name = 'Acupuncture';
    }elseif($code_type == 'PT'){
        $code_name = 'Physical Therapy';
    }elseif($code_type == 'P'){
      $code_name = 'Pool Therapy';
    }
    if(isset($_GET['show_more']) ){
      $show_text = 'Showing All Clinics ('.$all_location_count. ')';
    }
    else if(count($new_arr) != $c_val){
    	$show_text = 'Showing ('.count($new_arr).') of ('.$all_location_count. ') '.$code_name.' clinics';
    }else{
    	$show_text = 'Showing ('.count($new_arr).') '.$code_name.' clinics';
    }
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
  .gmnoprint.gm-bundled-control.gm-bundled-control-on-bottom{
    bottom: calc(50% - 170px) !important;
  }
</style>
<?php endif; ?>

<!DOCTYPE html>
<html>
  <head>
    <title>Locator</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/handlebars/4.7.7/handlebars.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <style>
      html,
      body {
        height: 100%;
        margin: 0;
        padding: 0;
      }

      #map-container {
        width: 100%;
        height: 100%;
        position: relative;
        font-family: "Roboto", sans-serif;
        box-sizing: border-box;
      }

      #map-container button {
        background: none;
        color: inherit;
        border: none;
        padding: 0;
        font: inherit;
        font-size: inherit;
        cursor: pointer;
      }

      #map {
        position: absolute;
        left: 22em;
        top: 0;
        right: 0;
        bottom: 0;
      }

      #locations-panel {
        position: absolute;
        left: 0;
        width: 22em;
        top: 0;
        bottom: 0;
        overflow-y: auto;
        background: white;
        padding: 0.5em;
        box-sizing: border-box;
      }

      @media only screen and (max-width: 876px) {
        #map {
          left: 0;
          bottom: 50%;
        }

        #locations-panel {
          top: 50%;
          right: 0;
          width: unset;
        }
      }

      #locations-panel-list > header {
        padding: 1.4em 1.4em 0 1.4em;
      }

      #locations-panel-list h1.search-title {
        font-size: 1em;
        font-weight: 500;
        margin: 0;
      }

      #locations-panel-list h1.search-title > img {
        vertical-align: bottom;
        margin-top: -1em;
      }

      #locations-panel-list .search-input {
        width: 100%;
        margin-top: 0.8em;
        position: relative;
      }

      #locations-panel-list .search-input input {
        width: 100%;
        border: 1px solid rgba(0, 0, 0, 0.2);
        border-radius: 0.3em;
        height: 2.2em;
        box-sizing: border-box;
        padding: 0 2.5em 0 1em;
        font-size: 1em;
        background-color:#FF6;
      }
        input:autofill{
        background-color:red;
        }

      #locations-panel-list .search-input-overlay {
        position: absolute;
      }

      #locations-panel-list .search-input-overlay.search {
        right: 2px;
        top: 2px;
        bottom: 2px;
        width: 2.4em;
      }

      #locations-panel-list .search-input-overlay.search button {
        width: 100%;
        height: 100%;
        border-radius: 0.2em;
        color: black;
        background: transparent;
      }

      #locations-panel-list .search-input-overlay.search .icon {
        margin-top: 0.05em;
        vertical-align: top;
      }

      #locations-panel-list .section-name {
        font-weight: 500;
        font-size: 0.9em;
        margin: 1.8em 0 1em 1.5em;
      }

      #locations-panel-list .location-result {
        position: relative;
        padding: 0.8em 3.5em 0.8em 1.4em;
        border-bottom: 1px solid rgba(0, 0, 0, 0.12);
        cursor: pointer;
      }

      #locations-panel-list .location-result:first-of-type {
        border-top: 1px solid rgba(0, 0, 0, 0.12);
      }

      #locations-panel-list .location-result:last-of-type {
        border-bottom: none;
      }

      #locations-panel-list .location-result.selected {
        outline: 2px solid #4285f4;
      }

      #locations-panel-list button.select-location {
        margin-bottom: 0.6em;
        text-align: left;
      }

      #locations-panel-list .therapy-type {
        margin-bottom: 0.6em;
        text-align: left;
      }

      #locations-panel-list .location-result h2.name {
        font-size: 1em;
        font-weight: 500;
        margin: 0;
      }

      #locations-panel-list .location-result .address {
        font-size: 0.9em;
        margin-bottom: 0.5em;
      }

      #locations-panel-list .location-result .details-button {
        font-size: 0.9em;
        color: #7e7efd;
      }

      #locations-panel-list .location-result .distance {
        position: absolute;
        top: 0.9em;
        right: 0;
        text-align: center;
        font-size: 0.9em;
        width: 5em;
      }

      #locations-panel-list .show-directions {
        position: absolute;
        right: 1.2em;
        top: 2.3em;
      }

      #location-results-list {
        list-style-type: none;
        margin: 0;
        padding: 0;
      }

      /* ------------- DETAILS PANEL ------------------------------- */
      #locations-panel-details {
        padding: 1.4em;
        box-sizing: border-box;
        display: none;
      }

      #locations-panel-details .back-button {
        font-size: 1em;
        font-weight: 500;
        color: #7e7efd;
        display: block;
        text-decoration: none;
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
        font-family: inherit;
      }

      #locations-panel-details .back-button .icon {
        width: 20px;
        height: 20px;
        vertical-align: bottom;

        /* Match link color #7e7efd */
        filter: invert(65%) sepia(87%) saturate(4695%) hue-rotate(217deg) brightness(105%) contrast(98%);
      }

      #locations-panel-details > header {
        text-align: center;
      }

      #locations-panel-details .banner {
        margin-top: 1em;
      }

      #locations-panel-details h2 {
        font-size: 1.1em;
        font-weight: 500;
        margin-bottom: 0.3em;
      }

      #locations-panel-details .distance {
        font-size: 0.9em;
        text-align: center;
      }

      #locations-panel-details .address {
        text-align: center;
        font-size: 0.9em;
        margin-top: 1.3em;
      }

      #locations-panel-details .atmosphere {
        text-align: center;
        font-size: 0.9em;
        margin: 0.8em 0;
      }

      #locations-panel-details .star-rating-numeric {
        color: #555;
      }

      #locations-panel-details .star-icon {
        width: 1.2em;
        height: 1.2em;
        margin-right: -0.3em;
        margin-top: -0.08em;
        vertical-align: top;
        filter: invert(88%) sepia(60%) saturate(2073%) hue-rotate(318deg) brightness(93%) contrast(104%);
      }

      #locations-panel-details .star-icon:last-of-type {
        margin-right: 0.2em;
      }

      #locations-panel-details .price-dollars {
        color: #555;
      }

      #locations-panel-details hr {
        height: 1px;
        color: rgba(0, 0, 0, 0.12);
        background-color: rgba(0, 0, 0, 0.12);
        border: none;
        margin-bottom: 1em;
      }

        input:-webkit-autofill {
          background-color: red;
        }
        input:autofill {
           background-color: red;
        }

      #locations-panel-details .contact {
        font-size: 0.9em;
        margin: 0.8em 0;
        display: flex;
        align-items: center;
      }

      #locations-panel-details .contact .icon {
        flex: 0 0 auto;
        width: 1.5em;
        height: 1.5em;
      }

      #locations-panel-details .contact .right {
        padding: 0.1em 0 0 1em;
      }

      #locations-panel-details a {
        text-decoration: none;
        color: #7e7efd;
      }

      #locations-panel-details .hours .weekday {
        display: inline-block;
        width: 5em;
      }

      #locations-panel-details .website a {
        white-space: nowrap;
        display: inline-block;
        overflow: hidden;
        max-width: 16em;
        text-overflow: ellipsis;
      }

      #locations-panel-details p.attribution {
        color: #777;
        margin: 0;
        font-size: 0.8em;
        font-style: italic;
      }
    </style>
    <script>
      'use strict';

      /** Hide a DOM element. */
      function hideElement(el) {
        el.style.display = 'none';
      }

      /** Show a DOM element that has been hidden. */
      function showElement(el) {
        el.style.display = 'block';
      }

      /**
       * Defines an instance of the Locator+ solution, to be instantiated
       * when the Maps library is loaded.
       */
      function LocatorPlus(configuration) {
        const locator = this;

        locator.locations = configuration.locations || [];
        locator.capabilities = configuration.capabilities || {};

        const mapEl = document.getElementById('map');
        const panelEl = document.getElementById('locations-panel');
        locator.panelListEl = document.getElementById('locations-panel-list');
        const sectionNameEl =
            document.getElementById('location-results-section-name');
        const resultsContainerEl = document.getElementById('location-results-list');

        const itemsTemplate = Handlebars.compile(
            document.getElementById('locator-result-items-tmpl').innerHTML);

        locator.searchLocation = null;
        locator.searchLocationMarker = null;
        locator.selectedLocationIdx = null;
        locator.userCountry = null;

        // Initialize the map -------------------------------------------------------
        locator.map = new google.maps.Map(mapEl, configuration.mapOptions);
        var geocoder = new google.maps.Geocoder();
        var address = "<?php echo $_POST['paaddress2'].' '.$_POST['paaddress1'].' '.$_POST['pacity'].' '.$_POST['pastate'].' '.$_POST['pazip']; ?>";
        console.log('test');
        geocoder.geocode({'address': address}, function(results, status) {
          if (status === 'OK') {
            const image ="https://img.icons8.com/external-kmg-design-outline-color-kmg-design/64/000000/external-pin-user-interface-kmg-design-outline-color-kmg-design-1.png";
            //  locator.map.setCenter(results[0].geometry.location);
            var marker = new google.maps.Marker({
              map: locator.map,
              position: results[0].geometry.location,
              icon: image
            });
          }
        });
        
        const img = "https://img.icons8.com/color/48/000000/marker--v1.png";
        const infoWindow = new google.maps.InfoWindow();
        // Store selection.
        const selectResultItem = function(locationIdx, panToMarker, scrollToResult) {
          locator.selectedLocationIdx = locationIdx;
          for (let locationElem of resultsContainerEl.children) {
            locationElem.classList.remove('selected');
            if (getResultIndex(locationElem) === locator.selectedLocationIdx) {
              locationElem.classList.add('selected');
              if (scrollToResult) {
                panelEl.scrollTop = locationElem.offsetTop;
              }
            }
          }
          if (panToMarker && (locationIdx != null)) {
            locator.map.panTo(locator.locations[locationIdx].coords);
            const marker = new google.maps.Marker({
              position: locator.locations[locationIdx].coords,
              map: locator.map,
              title: locator.locations[locationIdx].title,
              icon: img
            });
            infoWindow.setContent("<div style = 'width:200px;min-height:20px;font-weight:bolder;'>" + locator.locations[locationIdx].title + "</div>");
            infoWindow.open(locator.map, marker);
          }
        };

        // Create a marker for each location.
        const markers = locator.locations.map(function(location, index) {
          const marker = new google.maps.Marker({
            position: location.coords,
            map: locator.map,
            title: location.title,
            icon: img
          });
          marker.addListener('click', function() {
            selectResultItem(index, false, true);
            infoWindow.setContent("<div style = 'width:200px;min-height:20px;font-weight:bolder;'>" + location.title + "</div>");
            infoWindow.open(map, marker);
          });
          return marker;
        });

        // Fit map to marker bounds.
        locator.updateBounds = function() {
          const bounds = new google.maps.LatLngBounds();
          if (locator.searchLocationMarker) {
            bounds.extend(locator.searchLocationMarker.getPosition());
          }
          for (let i = 0; i < markers.length; i++) {
            bounds.extend(markers[i].getPosition());
          }
          locator.map.fitBounds(bounds);
        };
        if (locator.locations.length) {
          locator.updateBounds();
        }

        // Get the distance of a store location to the user's location,
        // used in sorting the list.
        const getLocationDistance = function(location) {
          if (!locator.searchLocation) return null;

          // Use travel distance if available (from Distance Matrix).
          if (location.travelDistanceValue != null) {
            return location.travelDistanceValue;
          }

          // Fall back to straight-line distance.
          return google.maps.geometry.spherical.computeDistanceBetween(
              new google.maps.LatLng(location.coords),
              locator.searchLocation.location);
        };

        // Render the results list --------------------------------------------------
        const getResultIndex = function(elem) {
          return parseInt(elem.getAttribute('data-location-index'));
        };

        locator.renderResultsList = function() {
          let locations = locator.locations.slice();
          for (let i = 0; i < locations.length; i++) {
            locations[i].index = i;
          }
          if (locator.searchLocation) {
            sectionNameEl.textContent =
                'Nearest locations (' + locations.length + ')';
            locations.sort(function(a, b) {
              return getLocationDistance(a) - getLocationDistance(b);
            });
          } else {
            sectionNameEl.textContent = `<?php echo $show_text; ?>`;
          }
          const resultItemContext = {
            locations: locations,
            showDirectionsButton: !!locator.searchLocation
          };
          resultsContainerEl.innerHTML = itemsTemplate(resultItemContext);
          for (let item of resultsContainerEl.children) {
            const resultIndex = getResultIndex(item);
            if (resultIndex === locator.selectedLocationIdx) {
              item.classList.add('selected');
            }

            const resultSelectionHandler = function() {
              if (resultIndex !== locator.selectedLocationIdx) {
                locator.clearDirections();
              }
              selectResultItem(resultIndex, true, false);
            };

            // Clicking anywhere on the item selects this location.
            // Additionally, create a button element to make this behavior
            // accessible under tab navigation.
            item.addEventListener('click', resultSelectionHandler);
            item.querySelector('.select-location')
                .addEventListener('click', function(e) {
                  resultSelectionHandler();
                  e.stopPropagation();
                });

            item.querySelector('.details-button')
                .addEventListener('click', function() {
                  locator.showDetails(resultIndex);
                });

            if (resultItemContext.showDirectionsButton) {
              item.querySelector('.show-directions')
                  .addEventListener('click', function(e) {
                    selectResultItem(resultIndex, false, false);
                    locator.updateDirections();
                    e.stopPropagation();
                  });
            }
          }
        };

        // Optional capability initialization --------------------------------------
        initializeSearchInput(locator);
        initializeDistanceMatrix(locator);
        initializeDirections(locator);
        initializeDetails(locator);

        // Initial render of results -----------------------------------------------
        locator.renderResultsList();
      }

      /** When the search input capability is enabled, initialize it. */
      function initializeSearchInput(locator) {
        const geocodeCache = new Map();
        const geocoder = new google.maps.Geocoder();

        const searchInputEl = document.getElementById('location-search-input');
        const searchButtonEl = document.getElementById('location-search-button');

        const updateSearchLocation = function(address, location) {
          if (locator.searchLocationMarker) {
            locator.searchLocationMarker.setMap(null);
          }
          if (!location) {
            locator.searchLocation = null;
            return;
          }
          const image ="https://img.icons8.com/external-kmg-design-outline-color-kmg-design/64/000000/external-pin-user-interface-kmg-design-outline-color-kmg-design-1.png";
          locator.searchLocation = {'address': address, 'location': location};
          locator.searchLocationMarker = new google.maps.Marker({
            position: location,
            map: locator.map,
            title: 'My location',
            icon: image
          });

          // Update the locator's idea of the user's country, used for units. Use
          // `formatted_address` instead of the more structured `address_components`
          // to avoid an additional billed call.
          const addressParts = address.split(' ');
          locator.userCountry = addressParts[addressParts.length - 1];

          // Update map bounds to include the new location marker.
          locator.updateBounds();

          // Update the result list so we can sort it by proximity.
          locator.renderResultsList();

          locator.updateTravelTimes();

          locator.clearDirections();
        };

        const geocodeSearch = function(query) {
          if (!query) {
            return;
          }

          const handleResult = function(geocodeResult) {
            searchInputEl.value = geocodeResult.formatted_address;
            updateSearchLocation(
                geocodeResult.formatted_address, geocodeResult.geometry.location);
          };

          if (geocodeCache.has(query)) {
            handleResult(geocodeCache.get(query));
            return;
          }
          const request = {address: query, bounds: locator.map.getBounds()};
          geocoder.geocode(request, function(results, status) {
            if (status === 'OK') {
              if (results.length > 0) {
                const result = results[0];
                geocodeCache.set(query, result);
                handleResult(result);
              }
            }
          });
        };

        // Set up geocoding on the search input.
        searchButtonEl.addEventListener('click', function() {
          geocodeSearch(searchInputEl.value.trim());
        });

        // Initialize Autocomplete.
        initializeSearchInputAutocomplete(
            locator, searchInputEl, geocodeSearch, updateSearchLocation);
      }

      /** Add Autocomplete to the search input. */
      function initializeSearchInputAutocomplete(
          locator, searchInputEl, fallbackSearch, searchLocationUpdater) {
        // Set up Autocomplete on the search input. Bias results to map viewport.
        const autocomplete = new google.maps.places.Autocomplete(searchInputEl, {
          types: ['geocode'],
          fields: ['place_id', 'formatted_address', 'geometry.location']
        });
        autocomplete.bindTo('bounds', locator.map);
        autocomplete.addListener('place_changed', function() {
          const placeResult = autocomplete.getPlace();
          if (!placeResult.geometry) {
            // Hitting 'Enter' without selecting a suggestion will result in a
            // placeResult with only the text input value as the 'name' field.
            fallbackSearch(placeResult.name);
            return;
          }
          searchLocationUpdater(
              placeResult.formatted_address, placeResult.geometry.location);
        });
      }

      /** Initialize Distance Matrix for the locator. */
      function initializeDistanceMatrix(locator) {
        // const distanceMatrixService = new google.maps.DistanceMatrixService();

        // Annotate travel times to the selected location using Distance Matrix.
        locator.updateTravelTimes = function() {
          if (!locator.searchLocation) return;

          const units = (locator.userCountry === 'USA') ?
              google.maps.UnitSystem.IMPERIAL :
              google.maps.UnitSystem.METRIC;
          const request = {
            origins: [locator.searchLocation.location],
            destinations: locator.locations.map(function(x) {
              return x.coords;
            }),
            travelMode: google.maps.TravelMode.DRIVING,
            unitSystem: units,
          };
          const callback = function(response, status) {
            if (status === 'OK') {
              const distances = response.rows[0].elements;
              for (let i = 0; i < distances.length; i++) {
                const distResult = distances[i];
                let travelDistanceText, travelDistanceValue;
                if (distResult.status === 'OK') {
                  travelDistanceText = distResult.distance.text;
                  travelDistanceValue = distResult.distance.value;
                }
                const location = locator.locations[i];
                location.travelDistanceText = travelDistanceText;
                location.travelDistanceValue = travelDistanceValue;
              }

              // Re-render the results list, in case the ordering has changed.
              locator.renderResultsList();
            }
          };
          // distanceMatrixService.getDistanceMatrix(request, callback);
        };
      }

      /** Initialize Directions service for the locator. */
      function initializeDirections(locator) {
        const directionsCache = new Map();
        const directionsService = new google.maps.DirectionsService();
        const directionsRenderer = new google.maps.DirectionsRenderer({
          suppressMarkers: true,
        });

        // Update directions displayed from the search location to
        // the selected location on the map.
        locator.updateDirections = function() {
          if (!locator.searchLocation || (locator.selectedLocationIdx == null)) {
            return;
          }
          const cacheKey = JSON.stringify(
              [locator.searchLocation.location, locator.selectedLocationIdx]);
          if (directionsCache.has(cacheKey)) {
            const directions = directionsCache.get(cacheKey);
            directionsRenderer.setMap(locator.map);
            directionsRenderer.setDirections(directions);
            return;
          }
          const request = {
            origin: locator.searchLocation.location,
            destination: locator.locations[locator.selectedLocationIdx].coords,
            travelMode: google.maps.TravelMode.DRIVING
          };
          directionsService.route(request, function(response, status) {
            if (status === 'OK') {
              directionsRenderer.setMap(locator.map);
              directionsRenderer.setDirections(response);
              directionsCache.set(cacheKey, response);
            }
          });
        };

        locator.clearDirections = function() {
          directionsRenderer.setMap(null);
        };
      }

      /** Initialize Place Details service and UI for the locator. */
      function initializeDetails(locator) {
        const panelDetailsEl = document.getElementById('locations-panel-details');
        const detailsService = new google.maps.places.PlacesService(locator.map);

        const detailsTemplate = Handlebars.compile(
            document.getElementById('locator-details-tmpl').innerHTML);

        const renderDetails = function(context) {
          panelDetailsEl.innerHTML = detailsTemplate(context);
          panelDetailsEl.querySelector('.back-button')
              .addEventListener('click', hideDetails);
        };

        const hideDetails = function() {
          showElement(locator.panelListEl);
          hideElement(panelDetailsEl);
        };

        locator.showDetails = function(locationIndex) {
          const location = locator.locations[locationIndex];
          const context = {location};

          // Helper function to create a fixed-size array.
          const initArray = function(arraySize) {
            const array = [];
            while (array.length < arraySize) {
              array.push(0);
            }
            return array;
          };

          if (location.placeId) {
            const request = {
              placeId: location.placeId,
              fields: [
                'formatted_phone_number', 'website', 'opening_hours', 'url',
                'utc_offset_minutes', 'price_level', 'rating', 'user_ratings_total'
              ]
            };
            detailsService.getDetails(request, function(place, status) {
              if (status == google.maps.places.PlacesServiceStatus.OK) {
                if (place.opening_hours) {
                  const daysHours =
                      place.opening_hours.weekday_text.map(e => e.split(/\:\s+/))
                          .map(e => ({'days': e[0].substr(0, 3), 'hours': e[1]}));

                  for (let i = 1; i < daysHours.length; i++) {
                    if (daysHours[i - 1].hours === daysHours[i].hours) {
                      if (daysHours[i - 1].days.indexOf('-') !== -1) {
                        daysHours[i - 1].days =
                            daysHours[i - 1].days.replace(/\w+$/, daysHours[i].days);
                      } else {
                        daysHours[i - 1].days += ' - ' + daysHours[i].days;
                      }
                      daysHours.splice(i--, 1);
                    }
                  }
                  place.openingHoursSummary = daysHours;
                }
                if (place.rating) {
                  const starsOutOfTen = Math.round(2 * place.rating);
                  const fullStars = Math.floor(starsOutOfTen / 2);
                  const halfStars = fullStars !== starsOutOfTen / 2 ? 1 : 0;
                  const emptyStars = 5 - fullStars - halfStars;

                  // Express stars as arrays to make iterating in Handlebars easy.
                  place.fullStarIcons = initArray(fullStars);
                  place.halfStarIcons = initArray(halfStars);
                  place.emptyStarIcons = initArray(emptyStars);
                }
                if (place.price_level) {
                  place.dollarSigns = initArray(place.price_level);
                }
                if (place.website) {
                  const url = new URL(place.website);
                  place.websiteDomain = url.hostname;
                }
                place.test = 'https://maps.google.com/?q=1310+3rd+Ave,+Chula+Vista,+CA+91911+A4';
                context.place = place;
                renderDetails(context);
              }
            });
          }
          renderDetails(context);
          hideElement(locator.panelListEl);
          showElement(panelDetailsEl);
        };
      }
    </script>
    <script>
      const CONFIGURATION = JSON.parse('<?php echo $location_arr; ?>');
      // const CONFIGURATION = {
      //   "locations": [
      //     {"title":"WestStar Palmdale","address1":"13890 Palmdale Rd","address2":"Victorville, CA 92392, USA","coords":{"lat":34.507023872252034,"lng":-117.36271949325409},"therapy_id":"","placeId":"ChIJ6wkDdl9vw4AR9ze8b_UIRSU"},
      //     {"title":"WestStar San Bernardino","address1":"1255 E Highland Ave","address2":"San Bernardino, CA 92404, USA","coords":{"lat":34.135452648000815,"lng":-117.25985516441804},"therapy_id":"PT","placeId":"ChIJYy6Q7oRUw4AR5RsnqpHefLA"},
      //     {"title":"WestStar Irvine","address1":"15775 Laguna Canyon Rd","address2":"Irvine, CA 92618, USA","coords":{"lat":33.666617142056985,"lng":-117.7610382932541},"therapy_id":"","placeId":"ChIJw_Ap4mnd3IARk-97yA2nlNE"},
      //     {"title":"WestStar Poway","address1":"12411 Poway Rd","address2":"Poway, CA 92064, USA","coords":{"lat":32.951557186229984,"lng":-117.06219592209015},"therapy_id":"","placeId":"ChIJo8zx-zP624ARMG8y_yYkTUo"},
      //     {"title":"WestStar Anaheim","address1":"1515 W North St","address2":"Anaheim, CA 92801, USA","coords":{"lat":33.84143504691418,"lng":-117.93671336441804},"therapy_id":"","placeId":"ChIJWx8GV-8p3YARMrWmIouztNQ"},
      //     {"title":"WestStar Covina","address1":"275 W San Bernardino Rd","address2":"Covina, CA 91723, USA","coords":{"lat":34.09015763208674,"lng":-117.8942628067459},"therapy_id":"","placeId":"ChIJHTZRykMow4AR6RVz5xTvTMQ"},
      //     {"title":"WestStar Montclair","address1":"9635 Monte Vista Ave","address2":"Montclair, CA 91763, USA","coords":{"lat":34.07979433643217,"lng":-117.6972792932541},"therapy_id":"OT,PT","placeId":"ChIJo954WYcxw4ARCwqAHPQmOW8"},
      //     {"title":"WestStar San Bernardino","address1":"155 W Hospitality Ln","address2":"San Bernardino, CA 92408, USA","coords":{"lat":34.065464553559636,"lng":-117.28589406441803},"therapy_id":"PT","placeId":"ChIJGaMj-Y2s3IAR82cMFE7WG2E"},
      //     {"title":"WestStar Colton","address1":"1400 E Cooley Dr","address2":"Colton, CA 92324, USA","coords":{"lat":34.054121822446966,"lng":-117.30531936441803},"therapy_id":"PT","placeId":"ChIJpc1UpPms3IARNea2oFAbfu8"},
      //     {"title":"WestStar Riverside","address1":"6841 Magnolia Ave","address2":"Riverside, CA 92506, USA","coords":{"lat":33.949698868471906,"lng":-117.39924693558197},"therapy_id":"","placeId":"ChIJf-pTyK2x3IARgeCO67sZDsI"},
      //     {"title":"WestStar Riverside","address1":"10116 Indiana Ave","address2":"Riverside, CA 92503, USA","coords":{"lat":33.90700888256047,"lng":-117.45233116441803},"therapy_id":"","placeId":"ChIJz2bIhbSw3IARakspwtDlnpU"},
      //     {"title":"WestStar Murrieta","address1":"40976 California Oaks Rd","address2":"Murrieta, CA 92562, USA","coords":{"lat":33.567404066589,"lng":-117.20348116441804},"therapy_id":"","placeId":"ChIJ0RU01ESC3IARwUlghkjtUAY"},
      //     {"title":"WestStar Santa Ana","address1":"600 S Grand Ave","address2":"Santa Ana, CA 92705, USA","coords":{"lat":33.74098679666429,"lng":-117.85083756441803},"therapy_id":"OT,PT","placeId":"ChIJdcYbrW3Z3IARdluzQ9aweXQ"},
      //     {"title":"WestStar Garden Grove","address1":"9240 W Garden Grove Blvd","address2":"Garden Grove, CA 92844, USA","coords":{"lat":33.773431812184015,"lng":-117.97110166441803},"therapy_id":"","placeId":"EjU5MjQwIFcgR2FyZGVuIEdyb3ZlIEJsdmQsIEdhcmRlbiBHcm92ZSwgQ0EgOTI4NDQsIFVTQSJREk8KNAoyCafqX3t9KN2AESVPs-Yama7bGh4LEO7B7qEBGhQKEgk3-gt5KybdgBEaiW7duvGJOwwQmEgqFAoSCTHNaI4-L92AEVeNkVLdUjN5"},
      //     {"title":"WestStar Anaheim","address1":"780 N Euclid St","address2":"Anaheim, CA 92801, USA","coords":{"lat":33.84267607280603,"lng":-117.93988179325409},"therapy_id":"","placeId":"ChIJIWWt_-4p3YARtP8D85E1iXg"},
      //     {"title":"WestStar Long Beach","address1":"980 Atlantic Ave","address2":"Long Beach, CA 90813, USA","coords":{"lat":33.778834547365705,"lng":-118.18472383558198},"therapy_id":"","placeId":"ChIJOdkqmEEx3YAR8ZBIRSHpWs8"},
      //     {"title":"WestStar Long Beach","address1":"3939 Atlantic Ave","address2":"Long Beach, CA 90807, USA","coords":{"lat":33.83009801138958,"lng":-118.18531816441804},"therapy_id":"","placeId":"ChIJLRFG35gz3YARtDSLKQr2qBY"},
      //     {"title":"WestStar Long Beach","address1":"4949 Atlantic Ave","address2":"Long Beach, CA 90805, USA","coords":{"lat":33.84550756458548,"lng":-118.18538186441803},"therapy_id":"","placeId":"ChIJp3R_YnIz3YARbVC0hKfGbHE"},
      //     {"title":"WestStar Long Beach","address1":"6801 Long Beach Blvd","address2":"Long Beach, CA 90805, USA","coords":{"lat":33.877881552841316,"lng":-118.20428286441805},"therapy_id":"","placeId":"ChIJV3P9iqnMwoARee3bKZODxAo"},
      //     {"title":"WestStar Downey","address1":"12115 Paramount Blvd","address2":"Downey, CA 90242, USA","coords":{"lat":33.93166664695795,"lng":-118.1465611067459},"therapy_id":"","placeId":"ChIJ3brBPKbNwoARY7XW5dZNZ-c"},
      //     {"title":"WestStar Pico Rivera","address1":"9048 Slauson Ave","address2":"Pico Rivera, CA 90660, USA","coords":{"lat":33.96985962299232,"lng":-118.10383836441802},"therapy_id":"","placeId":"ChIJRxjTngXSwoAR4NoQpcShxx4"},
      //     {"title":"WestStar Montebello","address1":"7807 Telegraph Rd","address2":"Montebello, CA 90640, USA","coords":{"lat":33.976619525914906,"lng":-118.12211523558197},"therapy_id":"OT,P,PT","placeId":"ChIJp_45vAfOwoARmFKwFhPtyic"},
      //     {"title":"WestStar San Marcos","address1":"365 S Rancho Santa Fe Rd","address2":"San Marcos, CA 92078, USA","coords":{"lat":33.14157373946986,"lng":-117.20256393558195},"therapy_id":"","placeId":"EjMzNjUgUyBSYW5jaG8gU2FudGEgRmUgUmQsIFNhbiBNYXJjb3MsIENBIDkyMDc4LCBVU0EiURJPCjQKMglj9m5zPnXcgBEU06usUdOYgxoeCxDuwe6hARoUChIJQb9dQlv124ARTmakmRK_VBoMEO0CKhQKEgn3z1LhFHXcgBEvN7yPaHP2iA"},
      //     {"title":"WestStar San Diego","address1":"3547 Camino del Rio S","address2":"San Diego, CA 92108, USA","coords":{"lat":32.77752783747368,"lng":-117.11696696441801},"therapy_id":"","placeId":"ChIJ7wDRfKVV2YARB3OPEVUZbWE"},
      //     {"title":"WestStar W Los Angeles","address1":"11870 Santa Monica Blvd Suite 208","address2":"Los Angeles, CA 90025, USA","coords":{"lat":34.04187028471922,"lng":-118.45936862209015},"therapy_id":"","placeId":"ChIJdSmsBGm7woARyj5ONA1ohDs"},
      //     {"title":"WestStar El Cajon","address1":"440 Chambers St","address2":"El Cajon, CA 92020, USA","coords":{"lat":32.799860,"lng":-116.967230},"therapy_id":"","placeId":"ChIJBzLsZctZ2YARZiYHWjAlNnc"}
      //   ],
      //   "mapOptions": {"center":{"lat":38.0,"lng":-100.0},"fullscreenControl":true,"mapTypeControl":false,"streetViewControl":false,"zoom":4,"zoomControl":true,"maxZoom":17},
      //   "mapsApiKey": "AIzaSyCtgQzrja4KtWMZoko3V9SShu_XUa7gsPs"
      // };

      function initMap() {
        const geocoder = new google.maps.Geocoder();
        const address = "<?php echo $_POST['paaddress2'].' '.$_POST['paaddress1'].' '.$_POST['pacity'].' '.$_POST['pastate'].' '.$_POST['pazip']; ?>";
        console.log(address);
        console.log(CONFIGURATION);
        geocoder.geocode( { 'address': address}, function(results, status) {
          if (status == google.maps.GeocoderStatus.OK) {
            const addLatitude = results[0].geometry.location.lat();
            const addLongitude = results[0].geometry.location.lng();
            var config = CONFIGURATION;
        //     console.log(config.locations);
            const factor = 0.621371;
            var loc = CONFIGURATION.locations;
            let temp = []
            loc.forEach((locrec, index) => {
        //       if(loc[index].therapy_id != ''){
        //         var searchelem = '<?php echo $code_type; ?>';
        //         loc[index].therapy_id.split(',').forEach(function(host) {
        //           if (searchelem == host) {
                    // var p1 = new google.maps.LatLng(addLatitude, addLongitude);
                    // var p2 = new google.maps.LatLng(locrec.coords.lat, locrec.coords.lng);
                    // var dist = (google.maps.geometry.spherical.computeDistanceBetween(p1, p2) / 1000).toFixed(2)+' km';
                    // var distmiles = ((google.maps.geometry.spherical.computeDistanceBetween(p1, p2) / 1000) * factor).toFixed(2)+' miles';
                    var distmiles = locrec.new_distance+' miles';
                    // loc[index].dist = dist;
                    loc[index].distmiles = distmiles;
                    temp.push(locrec);
        //           }
        //         });
        //       }
            });
            loc = temp;
            config.locations = loc;
            new LocatorPlus(config);
          }
          // else{

          // console.log(configval);
            // new LocatorPlus(CONFIGURATION);
          // }
        });
        // new LocatorPlus(CONFIGURATION);
      }
	</script>
	<script id="locator-result-items-tmpl" type="text/x-handlebars-template">
      {{#each locations}}
        <li class="location-result" data-location-index="{{index}}">
          <button class="select-location">
            <h2 class="name">{{title}}</h2>
          </button>
          <div class="therapy-type"><b>{{therapy_code}}</b></div>
          <div class="address">{{show_address}}</div>
            <a href="/?add={{title}}&loc={{title}}"><input type="button" value="select"></a>
          <button class="details-button">
            View details
          </button>
          <span><b>{{distmiles}}</b></span>
          {{#if travelDistanceText}}
            <div class="distance">{{travelDistanceText}}</div>
          {{/if}}

          {{#if ../showDirectionsButton}}
            <button class="show-directions" title="Show directions to this location">
              <svg width="34" height="34" viewBox="0 0 34 34"
                   fill="none" xmlns="http://www.w3.org/2000/svg" alt="Show directions">
                <path d="M17.5867 9.24375L17.9403 8.8902V8.8902L17.5867 9.24375ZM16.4117 9.24375L16.7653 9.59731L16.7675 9.59502L16.4117 9.24375ZM8.91172 16.7437L8.55817 16.3902L8.91172 16.7437ZM8.91172 17.9229L8.55817 18.2765L8.55826 18.2766L8.91172 17.9229ZM16.4117 25.4187H16.9117V25.2116L16.7652 25.0651L16.4117 25.4187ZM16.4117 25.4229H15.9117V25.63L16.0582 25.7765L16.4117 25.4229ZM25.0909 17.9229L25.4444 18.2765L25.4467 18.2742L25.0909 17.9229ZM25.4403 16.3902L17.9403 8.8902L17.2332 9.5973L24.7332 17.0973L25.4403 16.3902ZM17.9403 8.8902C17.4213 8.3712 16.5737 8.3679 16.0559 8.89248L16.7675 9.59502C16.8914 9.4696 17.1022 9.4663 17.2332 9.5973L17.9403 8.8902ZM16.0582 8.8902L8.55817 16.3902L9.26527 17.0973L16.7653 9.5973L16.0582 8.8902ZM8.55817 16.3902C8.0379 16.9105 8.0379 17.7562 8.55817 18.2765L9.26527 17.5694C9.13553 17.4396 9.13553 17.227 9.26527 17.0973L8.55817 16.3902ZM8.55826 18.2766L16.0583 25.7724L16.7652 25.0651L9.26517 17.5693L8.55826 18.2766ZM15.9117 25.4187V25.4229H16.9117V25.4187H15.9117ZM16.0582 25.7765C16.5784 26.2967 17.4242 26.2967 17.9444 25.7765L17.2373 25.0694C17.1076 25.1991 16.895 25.1991 16.7653 25.0694L16.0582 25.7765ZM17.9444 25.7765L25.4444 18.2765L24.7373 17.5694L17.2373 25.0694L17.9444 25.7765ZM25.4467 18.2742C25.9631 17.7512 25.9663 16.9096 25.438 16.3879L24.7354 17.0995C24.8655 17.2279 24.8687 17.4363 24.7351 17.5716L25.4467 18.2742Z" fill="#7e7efd"/>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M19 19.8333V17.75H15.6667V20.25H14V16.9167C14 16.4542 14.3708 16.0833 14.8333 16.0833H19V14L21.9167 16.9167L19 19.8333Z" fill="#7e7efd"/>
                <circle cx="17" cy="17" r="16.5" stroke="#7e7efd"/>
              </svg>
            </button>
          {{/if}}
        </li>
      {{/each}}
    </script>
    <script id="locator-details-tmpl" type="text/x-handlebars-template">
      <button class="back-button">
        <img class="icon" src="https://fonts.gstatic.com/s/i/googlematerialicons/arrow_back/v11/24px.svg" alt=""/>
        Back
      </button>
      <header>
        <div class="banner">
          <svg width="23" height="32" viewBox="0 0 23 32" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M22.9976 11.5003C22.9976 13.2137 22.7083 14.9123 21.8025 16.7056C18.6321 22.9832 12.7449 24.3314 12.2758 30.7085C12.2448 31.1294 11.9286 31.4744 11.4973 31.4744C11.0689 31.4744 10.7527 31.1294 10.7218 30.7085C10.2527 24.3314 4.3655 22.9832 1.19504 16.7056C0.289306 14.9123 0 13.2137 0 11.5003C0 5.13275 5.14557 0 11.5003 0C17.852 0 22.9976 5.13275 22.9976 11.5003Z" fill="#4285F4"/>
            <path fill-rule="evenodd" clip-rule="evenodd" transform="translate(5.5,5.5)" d="M6 8.84091L9.708 11L8.724 6.92961L12 4.19158L7.6856 3.83881L6 0L4.3144 3.83881L0 4.19158L3.276 6.92961L2.292 11L6 8.84091Z" fill="#FBE15C"/>
          </svg>
        </div>
        <h4>{{location.distmiles}}</h4>
        <h2>{{location.title}}</h2>
        <h2>{{location.therapy_code}}</h2>
      </header>
      {{#if location.travelDistanceText}}
        <div class="distance">{{location.travelDistanceText}} away</div>
      {{/if}}
      <div class="address">
        {{location.show_address}}
      </div>
      <div class="atmosphere">
        {{#if place.rating}}
          <span class="star-rating-numeric">{{place.rating}}</span>
          <span>
            {{#each place.fullStarIcons}}
              <img src="https://fonts.gstatic.com/s/i/googlematerialicons/star/v15/24px.svg"
                   alt="" class="star-icon"/>
            {{/each}}
            {{#each place.halfStarIcons}}
              <img src="https://fonts.gstatic.com/s/i/googlematerialicons/star_half/v17/24px.svg"
                   alt="" class="star-icon"/>
            {{/each}}
            {{#each place.emptyStarIcons}}
              <img src="https://fonts.gstatic.com/s/i/googlematerialicons/star_outline/v9/24px.svg"
                   alt="" class="star-icon"/>
            {{/each}}
          </span>
        {{/if}}
        {{#if place.user_ratings_total}}
          <a href="{{place.url}}" target="_blank">{{place.user_ratings_total}} reviews</a>
        {{else}}
          <a href="{{location.map_address}}" target="_blank">See on Google Maps</a>
        {{/if}}
        {{#if place.price_level}}
          &bull;
          <span class="price-dollars">
            {{#each place.dollarSigns}}${{/each}}
          </span>
        {{/if}}
      </div>
      <hr/>
      {{#if place.opening_hours}}
        <div class="hours contact">
          <img src="https://fonts.gstatic.com/s/i/googlematerialicons/schedule/v12/24px.svg"
               alt="Opening hours" class="icon"/>
          <div class="right">
            {{#each place.openingHoursSummary}}
              <div>
                <span class="weekday">{{days}}</span>
                <span class="hours">{{hours}}</span>
              </div>
            {{/each}}
          </div>
        </div>
      {{/if}}
      {{#if place.website}}
        <div class="website contact">
          <img src="https://fonts.gstatic.com/s/i/googlematerialicons/public/v10/24px.svg"
               alt="Website" class="icon"/>
          <div class="right">
            <a href="{{place.website}}" target="_blank">{{place.websiteDomain}}</a>
          </div>
        </div>
      {{/if}}
      {{#if place.formatted_phone_number}}
        <div class="phone contact">
          <img src="https://fonts.gstatic.com/s/i/googlematerialicons/phone/v10/24px.svg"
               alt="Phone number" class="icon"/>
          <div class="right">
            {{place.formatted_phone_number}}
          </div>
        </div>
      {{/if}}
      {{#if place.html_attributions}}
        {{#each place.html_attributions}}
          <p class="attribution">{{{this}}}</p>
        {{/each}}
      {{/if}}
    </script>
  </head>
  <?php
    $address = $_POST['paaddress1'].' '.$_POST['pacity'].' '.$_POST['pastate'].' '.$_POST['pazip']; ?>
  <body>
    <div id="map-container">
      <div id="locations-panel">
        <div id="locations-panel-list">
          <header>
            <h1 class="search-title">
              <img src="https://fonts.gstatic.com/s/i/googlematerialicons/place/v15/24px.svg"/>
              Click to Request Directions
            </h1>
            <div class="search-input">
             <input id="location-search-input" placeholder="Enter your address or zip code" value="<?php echo $address; ?>">
              <div id="search-overlay-search" class="search-input-overlay search">
                <button id="location-search-button">
                  <img class="icon" src="https://fonts.gstatic.com/s/i/googlematerialicons/search/v11/24px.svg" alt="Search"/>
                </button>
              </div>
            </div>
          </header>
          <div class="section-name" id="location-results-section-name">
            All locations
          </div>
          <div style="margin-top: 10px;">
          	<a href="/" class="add-more"><input type="button" value="Cancel" style="text-align: center; margin-left: 25px; margin-bottom: 11px;"></a>
          </div>
          <div class="results">
            <ul id="location-results-list"></ul>
            <?php if(isset($_GET['show_more']) ){ ?>
              <!-- <div style="border-top: 1px solid rgba(0, 0, 0, 0.12);padding-top: 10px;"><a href="?add_more=load" style="font-weight: 500;font-size: 0.9em;margin: 1.8em 0 1em 1.5em;color: black;">Back to <?php echo $code_name; ?> Clinics</a></div> -->
            <?php } else if(!isset($_GET['add_more']) ){ ?>
              <div style="border-top: 1px solid rgba(0, 0, 0, 0.12);padding: 10px 0px;"><a href="?add_more=load" style="font-weight: 500;font-size: 0.9em;margin: 1.8em 0 1em 1.5em;color: black;">Show All <?php echo $code_name; ?> Clinics</a></div>
            <?php }else if (isset($_GET['add_more'])) { ?>
              <div style="border-top: 1px solid rgba(0, 0, 0, 0.12);padding: 10px 0px;">
              	<a href="?show_more=all" style="font-weight: 500;font-size: 0.9em;margin: 1.8em 0 1em 1.5em;color: black;">Show All Clinics</a>
              	<!-- <a href="?map" style="font-weight: 500;font-size: 0.9em;margin: 1.8em 0 1em 1.5em;color: black;">Back to showing (5) Clinics</a> -->
              </div>
            <?php } ?> 
          </div>
          <div style="border-top: 1px solid rgba(0, 0, 0, 0.12);padding-top: 10px;">
            <?php if(isset($_GET['show_more']) ){ ?>
              	<a href="?add_more=load" ><input type="button" value="Back" style="text-align: center; margin-left: 25px; margin-bottom: 11px;"></a>
            <?php }else if (isset($_GET['add_more'])) { ?>
              	<a href="?map" ><input type="button" value="Back" style="text-align: center; margin-left: 25px; margin-bottom: 11px;"></a>
            <?php } ?> 
            <a href="/" class="add-more"><input type="button" value="Cancel" style="text-align: center; margin-left: 25px; margin-bottom: 11px;"></a>
          </div>
        </div>
        <div id="locations-panel-details"></div>
      </div>
      <div id="map"></div>
    </div>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCtgQzrja4KtWMZoko3V9SShu_XUa7gsPs&callback=initMap&libraries=places,geometry&solution_channel=GMP_QB_locatorplus_v4_cABCDE" async defer></script>
  </body>
</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script>
$(window).on('load', function() {
    jQuery(".location-result a").each(function() {
      var url = jQuery(this).attr("href");    
      var rurl = url.replace('#','!');
      jQuery(this).attr("href", rurl);
    });

    jQuery(document).on('focusout','#location-search-input',function(){
      console.log('change');
      setTimeout(function(){
        jQuery(".location-result a").each(function() {
          var url = jQuery(this).attr("href");    
          var rurl = url.replace('#','!');
          jQuery(this).attr("href", rurl);
        });
      }, 1000);
    });

    // jQuery(document).on('click','.add-more',function(e){
    //   e.preventDefault();
    //   document.cookie = 'add_more=load; expires=Sun, 1 Jan 2023 00:00:00 UTC; path=/'
    //   window.location.reload();
    //   // var url = window.location.href;    
    //   // if (url.indexOf('?') > -1){
    //   //   url += '&add_more=load'
    //   // }else{
    //   //   url += '?add_more=load'
    //   // }
    //   // window.location.reload(url)
    // });
    // $(".submitBtn").click(function(e){
    //   e.preventDefault();
    //   var googleAddress = $(this).data("googleaddress");
    //   $('.google_loc').val(googleAddress);
    //     $("#myForm").submit(); // Submit the form
    // });
});
</script>
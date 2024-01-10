<?php

$myServer = 'localhost';
$myUser = 'wsptn_netpt';
$myPass = 'OsmWoL?cUt~aco89';
$myDB = 'wsptn_netpt';

$dbhandle = @mysql_connect($myServer, $myUser, $myPass) or die("Couldn't connect to SQL Server on $myServer") or die("Error connecting to database. ".mysqli_error($dbhandle));
$dbselect = @mysql_select_db($myDB, $dbhandle) or die("Error selecting database. ".mysqli_error($dbhandle));

$cityToTerritory = array();
$zipToTerritory = array();

$fh = fopen('doctor-territory.csv', 'r');

$count = 0;
$data = fgetcsv($fh, 10000000);
while($data = fgetcsv($fh, 1000000)) {
    $city = $data[6];
    $zip = $data[7];
    $terr = $data[9];

    if( isset($cityToTerritory[$city][$terr]) ) {
        $cityToTerritory[$city][$terr]++;
    } else {
        $cityToTerritory[$city][$terr]=1;
    }

    if( isset($zipToTerritory[$zip][$terr]) ) {
        $zipToTerritory[$zip][$terr]++;
    } else {
        $zipToTerritory[$zip][$terr]=1;
    }
}

foreach($cityToTerritory as $city => $terrs) {
    if ($city) {
        $highestTerr = null;
        $highestTerrCount = 0;
        foreach($terrs as $terr => $count) {
            if($count > $highestTerrCount) {
                $highestTerr = $terr;
                $highestTerrCount = $count;
            }
        }
        $sql = "UPDATE doctor_locations SET dlterritory = $highestTerr WHERE dlcity = '".strtoupper($city)."'";
        echo $sql;
        mysqli_query($dbhandle,$sql);
        echo mysqli_error($dbhandle);
    }
}

foreach($zipToTerritory as $zip => $terrs) {
    if ($zip) {
        $highestTerr = null;
        $highestTerrCount = 0;
        foreach($terrs as $terr => $count) {
            if($count > $highestTerrCount) {
                $highestTerr = $terr;
                $highestTerrCount = $count;
            }
        }
        $sql = "UPDATE doctor_locations SET dlterritory = $highestTerr WHERE dlzip = '".strtoupper($zip)."'";
        mysqli_query($dbhandle,$sql);
        echo mysqli_error($dbhandle);
    }
}


?>

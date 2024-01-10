<?php

    $myServer = "208.57.67.221,31433";
    $myUser = "sunnispoon";
    $myPass = "apmiWeststar";
	$dbhandle = mssql_connect($myServer, $myUser, $myPass) or die("Couldn't connect to SQL Server on $myServer@" . $_SESSION['SERVER_NAME']);

    $myDB = "netpt_wsptn_com";
	$dbselect = mssql_select_db($myDB, $dbhandle) or die("Couldn't open database $myDB");

    $version = mssql_query('SELECT @@VERSION');
    $row = mssql_fetch_array($version);

    echo $row[0];

    // Clean up
    mssql_free_result($version);
?>
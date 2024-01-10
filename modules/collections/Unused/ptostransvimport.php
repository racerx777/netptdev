<?php

$myServer = 'localhost';
$myUser = "wsptn_netptdev";
$myPass = "3WZMmBI5LPZs";
$myDB = "wsptn_netptdev";
$path = '/home/wsptn/public_html/netpt/collections';
$percent = .10;

function ungzip($source, $target, $delete=true) {
    
    $result = false;
    $message = "FAILED";
    
    if ($fp=fopen($target,"w")) {
        if($gz=gzopen($source,"r")) {
            
            while($string = gzread($gz, 16384)) {
                $result = fwrite($fp, $string);
            }
            
            $message = "SUCCESSFUL";
            gzclose($gz);
            
            if ($delete) {
                unlink($source);
            }
        }
        fclose($fp);
    }
    
    return $result;
}

$dbhandle = @mysql_connect($myServer, $myUser, $myPass) or die("Couldn't connect to SQL Server on $myServer") or die("Error connecting to database. ".mysqli_error($dbhandle));
$dbselect = @mysql_select_db($myDB, $dbhandle) or die("Error selecting database. ".mysqli_error($dbhandle));

$source=$path.'/ws/transv.txt.gz';
$target=$path.'/ws/transv.txt';
$return = ungzip($source, $target, false);
if (!$return) {
    echo 'WS UNZIP FAILED'.PHP_EOL;
    exit;
}
echo 'WS UNZIP COMPLETED'.PHP_EOL;

$source=$path.'/net/transv.txt.gz';
$target=$path.'/net/transv.txt';
$return = ungzip($source, $target, false);
if (!$return) {
    echo 'NET UNZIP FAILED'.PHP_EOL;
    exit;
}
echo 'NET UNZIP COMPLETED'.PHP_EOL;


//Truncate the Staging Table
$sql = "TRUNCATE TABLE ptos_transv_staging;";
mysqli_query($dbhandle,$sql);
if (mysqli_errno($dbhandle)) {
    echo mysqli_error($dbhandle);
    exit;
}
echo 'TRUNCATE ptos_transv_staging COMPLETED'.PHP_EOL;

//Load the WS Data
$sql = "
LOAD DATA INFILE '$path/ws/transv.txt'
INTO TABLE ptos_transv_staging
FIELDS TERMINATED BY '|'
IGNORE 1 LINES
(pnum,edit,date,code,descrip,amount,eamount,units,therap,pos,billed,pos,visit,
acctype,ipayed,ppayed,credit,tcopay,tos,dr1,dr2,dr3,dr4,dr5,dr6,dr7,dr8,dr9,drc1,drc2,
drc3,drc4,drc5,drc6,drc7,drc8,drc9,whobill,cnum,inscd,dtask,daystopay,status,billtype,
modifier,colltrak,userid)";
mysqli_query($dbhandle,$sql);
if (mysqli_errno($dbhandle)) {
    echo mysqli_error($dbhandle);
    exit;
}
echo 'WS Data Loaded into Staging COMPLETED'.PHP_EOL;

//mysqlimport --fields-enclosed-by='|' --ignore-lines=1 --host=localhost --password=3WZMmBI5LPZs --columns=pnum,edit,date,code,descrip,amount,eamount,units,therap,pos,billed,pos,visit,acctype,ipayed,ppayed,credit,tcopay,tos,dr1,dr2,dr3,dr4,dr5,dr6,dr7,dr8,dr9,drc1,drc2,drc3,drc4,drc5,drc6,drc7,drc8,drc9,whobill,cnum,inscd,dtask,daystopay,status,billtype,modifier,colltrak,userid wsptn_netptdev ptos_transv_staging

//mysqlimport --user=wsptn_netptdev --fields-enclosed-by='|' --ignore-lines=1 --host=localhost --password=3WZMmBI5LPZs --columns=pnum,edit,date,code,descrip,amount,eamount,units,therap,pos,billed,pos,visit,acctype,ipayed,ppayed,credit,tcopay,tos,dr1,dr2,dr3,dr4,dr5,dr6,dr7,dr8,dr9,drc1,drc2,drc3,drc4,drc5,drc6,drc7,drc8,drc9,whobill,cnum,inscd,dtask,daystopay,status,billtype,modifier,colltrak,userid wsptn_netptdev /home/wsptn/public_html/netpt/collections/net/ptos_transv_staging.txt
 
//Load the Net Data
$sql = "
LOAD DATA LOCAL INFILE '$path/net/transv.txt'
INTO TABLE ptos_transv_staging
FIELDS TERMINATED BY '|'
IGNORE 1 LINES
(pnum,@wastedVar,date,code,descrip,amount,@wastedVar,@wastedVar,therap,@wastedVar,billed,@wastedVar,visit,
acctype,ipayed,ppayed,credit,@wastedVar,@wastedVar,dr1,dr2,dr3,dr4,dr5,dr6,dr7,dr8,dr9,drc1,drc2,
drc3,drc4,drc5,drc6,drc7,drc8,drc9,@wastedVar,cnum,inscd,@wastedVar,@wastedVar,@wastedVar,@wastedVar,
@wastedVar,@wastedVar,@wastedVar)
SET bnum = 'NET',
crtuser = 'NetPT Cron Job',
crtdate = now(),
crtprog = '".$_SERVER['PHP_SELF']."'
";
mysqli_query($dbhandle,$sql);
if (mysqli_errno($dbhandle)) {
    echo mysqli_error($dbhandle);
    exit;
}
echo 'NET Data Loaded into Staging COMPLETED'.PHP_EOL;

    
//Get the Staging Count
$stagingCountQuery = "SELECT COUNT(*) as stagingCount FROM ptos_transv_staging;";
$stagingCountResult = mysqli_query($dbhandle,$stagingCountQuery);
$stagingCountRow = mysqli_fetch_assoc($stagingCountResult);
$stagingCount = $stagingCountRow['stagingCount'];

$prodCountQuery = "SELECT COUNT(*) as prodCount FROM ptos_transv";
$prodCountResult = mysqli_query($dbhandle,$prodCountQuery);
$prodCountRow = mysqli_fetch_assoc($prodCountResult);
$prodCount = $prodCountRow['prodCount'];

//Check if the Staging Table is within a few percentage points of our prod table
if ($prodCount * (1+$percent) >= $stagingCount && $prodCount * (1-$percent) <= $stagingCount) {
    
    $sql = "TRUNCATE TABLE ptos_transv;";
    mysqli_query($dbhandle,$sql);
    if (mysqli_errno($dbhandle)) {
        echo mysqli_error($dbhandle);
        exit;
    }
    
    $rebulidProdSQL = 'INSERT INTO ptos_transv SELECT * FROM ptos_transv_staging';
    mysqli_query($dbhandle,$rebulidProdSQL);
    if (mysqli_errno($dbhandle)) {
        echo mysqli_error($dbhandle);
        exit;
    }
    echo 'Data Loaded into ptos_transv COMPLETED'.PHP_EOL;
    echo 'Transv had: '.$prodCount.' Now has:'.$stagingCount.PHP_EOL;
} else {
    echo 'Prod Count:'.$prodCount.' Staging Count:'.$stagingCount.' Data seems off, not loading'.PHP_EOL;
}

?>

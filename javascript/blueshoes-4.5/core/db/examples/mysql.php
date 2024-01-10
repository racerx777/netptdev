<?php
/**
* @package    core_db
* @subpackage examples
*/


require_once($_SERVER["DOCUMENT_ROOT"]    . "../global.conf.php");
include_once($APP['path']['core'] . 'db/Bs_MySql.class.php');

$dbSettings['name']         = 'bs_site_www_bs_org'; //'yourdatabasename';
$dbSettings['host']         = 'localhost';
$dbSettings['port']         = '3306';
$dbSettings['user']         = 'root'; //'username';
$dbSettings['pass']         = ''; //'password';
$dbSettings['socket']       = '';
$dbSettings['syntax']       = 'mysql';
$dbSettings['type']         = 'mysql';
$dbSettings['transactions'] = FALSE;

$isOk = FALSE;
do {
  $bsDb =& new Bs_MySql();
  $connId = $bsDb->connect($dbSettings);
  if (isEx($connId)) {
    //crap. connection failed.
    $connId->stackTrace('', __FILE__, __LINE__, 'fatal');
    $connId->stackDump('alert');
    break;
  }
  
  //oki, we have a valid db connection now. our instance is $bsDb.
  
  echo "<h2>CREATING DB TABLE</h2>";
  $sql = "
    CREATE TABLE IF NOT EXISTS test.BsMysqlTest(
      ID           INT NOT NULL DEFAULT 0 AUTO_INCREMENT, 
      firstField   VARCHAR(30) NOT NULL DEFAULT '', 
      secondField  VARCHAR(30) NOT NULL DEFAULT '', 
      PRIMARY KEY ID (ID)
    )
  ";
  $status = $bsDb->write($sql);
  if (isEx($status)) {
    $status->stackTrace('', __FILE__, __LINE__, 'fatal');
    $status->stackDump('alert');
  }
  
  echo "<br><h2>INSERTING RECORD</h2>";
  $sql = "INSERT INTO test.BsMysqlTest (firstField, secondField) VALUES ('first', 'second')";
  $newId = $bsDb->idWrite($sql);
  if (isEx($newId)) {
    $newId->stackTrace('', __FILE__, __LINE__, 'fatal');
    $newId->stackDump('alert');
    break;
  } else {
    echo "Inserted record with new ID: {$newId}<br><br>";
  }
  
  echo "<br><h2>UPDATING RECORD</h2>";
  $sql = "UPDATE test.BsMysqlTest SET firstField='FIRST'";
  $numRecs = $bsDb->countWrite($sql);
  if (isEx($numRecs)) {
    $numRecs->stackTrace('', __FILE__, __LINE__, 'fatal');
    $numRecs->stackDump('alert');
    break;
  } else {
    echo "Updated {$numRecs} record(s)<br><br>";
  }
  
  echo "<br><h2>SELECTING RECORDS (countRead)</h2>";
  $sql = "SELECT * FROM test.BsMysqlTest";
  $numRecs = $bsDb->countRead($sql);
  if (isEx($numRecs)) {
    $numRecs->stackTrace('', __FILE__, __LINE__, 'fatal');
    $numRecs->stackDump('alert');
    break;
  } else {
    echo "Selected {$numRecs} record(s)<br><br>";
  }
  
  echo "<br><h2>SELECTING RECORDS (getOne)</h2>";
  $sql = "SELECT firstField FROM test.BsMysqlTest WHERE ID = 2";
  $data = $bsDb->getOne($sql);
  if (isEx($data)) {
    $data->stackTrace('', __FILE__, __LINE__, 'fatal');
    $data->stackDump('alert');
    break;
  } else {
    dump($data);
  }
  
  echo "<br><h2>SELECTING RECORDS (getRow)</h2>";
  $sql = "SELECT * FROM test.BsMysqlTest WHERE ID = 2";
  $data = $bsDb->getRow($sql);
  if (isEx($data)) {
    $data->stackTrace('', __FILE__, __LINE__, 'fatal');
    $data->stackDump('alert');
    break;
  } else {
    dump($data);
  }
  
  echo "<br><h2>SELECTING RECORDS (getCol)</h2>";
  $sql = "SELECT firstField FROM test.BsMysqlTest WHERE ID < 5";
  $data = $bsDb->getCol($sql);
  if (isEx($data)) {
    $data->stackTrace('', __FILE__, __LINE__, 'fatal');
    $data->stackDump('alert');
    break;
  } else {
    dump($data);
  }
  
  echo "<br><h2>SELECTING RECORDS (getAssoc)</h2>";
  $sql = "SELECT * FROM test.BsMysqlTest WHERE ID < 3";
  $data = $bsDb->getAssoc($sql, TRUE, TRUE);
  if (isEx($data)) {
    $data->stackTrace('', __FILE__, __LINE__, 'fatal');
    $data->stackDump('alert');
    break;
  } else {
    dump($data);
  }
  
  
  
  $isOk = TRUE;
} while (FALSE);

if ($isOk) {
  echo 'everything successful.';
}



?>
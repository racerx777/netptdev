<?php
/**
* @package    applications_debedoo
* @subpackage examples
*/


require_once($_SERVER["DOCUMENT_ROOT"]    . "../global.conf.php");
require_once($APP['path']['applications'] . 'debedoo/Bs_Debedoo.class.php');

$d =& new Bs_Debedoo();
$d->setLanguage('en');
$d->internalName  = 'ExamplePhoneBook';
$d->dbTableName   = 'ExamplePhonePerson'; //changed this from 'ExamplePhoneCompany'.
$d->addHeadString = "BS Phone Book Example";

$dsn = array(
    'name'=>'test', 'host'=>'localhost', 'port'=>'3306', 'socket'=>'', 
    'user'=>'root', 'pass'=>'', 'syntax'=>'mysql', 'type'=>'mysql'
);
$status = $d->setDbByDsn($dsn);
if (isEx($status)) {
  $status->stackDump('die');
}

$out = $d->doItYourself();
if (isEx($out)) {
  $out->stackDump('die');
} else {
  echo $out;
}
?>
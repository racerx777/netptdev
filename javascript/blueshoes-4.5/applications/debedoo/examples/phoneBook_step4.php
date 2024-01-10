<?php
/**
* @package    applications_debedoo
* @subpackage examples
*/


require_once($_SERVER["DOCUMENT_ROOT"]    . "../global.conf.php");
require_once($APP['path']['applications'] . 'debedoo/Bs_Debedoo.class.php');

$d =& new Bs_Debedoo();
$d->setLanguage('en');
$d->internalName  = (isSet($_REQUEST['bs_debedoo']['iName'])) ? $_REQUEST['bs_debedoo']['iName'] : 'ExamplePhoneBookP';
$d->dbTableName   = ($d->internalName == 'ExamplePhoneBookP') ? 'ExamplePhonePerson' : 'ExamplePhoneCompany';
$d->addHeadString = "<h2>BS Phone Book Example</h2><a href='?bs_debedoo[iName]=ExamplePhoneBookC'>Company</a> - <a href='?bs_debedoo[iName]=ExamplePhoneBookP'>Person</a><br><br>";

$dsn = array(
    'name'=>'test', 'host'=>'localhost', 'port'=>'3306', 'socket'=>'', 
    'user'=>'root', 'pass'=>'', 'syntax'=>'mysql', 'type'=>'mysql'
);
$status = $d->setDbByDsn($dsn);
if (isEx($status)) {
  $status->stackDump('die');
}

if ($d->internalName == 'ExamplePhoneBookP') {
  $d->useOverviewProfile = 'default';
  $d->overviewSettings['default']['fields'] = array(
    'ID'                      => TRUE, 
    'ExamplePhoneCompanyID'   => array(
      'caption'                 => 'Company', 
      'trim'                    => '40', 
      'foreignKey'              => array(
        'fields'                  => array('caption', 'location'), 
        'orderBy'                 => 'caption, location', 
      ), 
    ), 
    'firstname'               => TRUE, 
    'lastname'                => TRUE, 
    'phoneNumber'             => TRUE, 
    'notes'                   => TRUE, 
  );
}

$out = $d->doItYourself();
if (isEx($out)) {
  $out->stackDump('die');
} else {
  echo $out;
}
?>
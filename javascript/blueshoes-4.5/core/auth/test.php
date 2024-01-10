<?php/********************************************************************************************
* BlueShoes Framework; This file is part of the php application framework.
* NOTE: This code is stripped (obfuscated). To get the clean documented code goto 
*       www.blueshoes.org and register for the free open source *DEVELOPER* version or 
*       buy the commercial version.
*       
*       In case you've already got the developer version, then this is one of the few 
*       packages/classes that is only available to *PAYING* customers.
*       To get it go to www.blueshoes.org and buy a commercial version.
* 
* @copyright www.blueshoes.org
* @author    Samuel Blume <sam at blueshoes dot org>
* @author    Andrej Arn <andrej at blueshoes dot org>
*/?><?php
if (!isSet($GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'])) $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] = '../';require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($GLOBALS['APP']['path']['core'] . 'auth/Bs_UserManagement.class.php');require_once($GLOBALS['APP']['path']['core'] . 'db/Bs_Db.class.php');require_once($GLOBALS['APP']['path']['core'] . 'storage/objectpersister/Bs_SimpleObjPersister.class.php');$dsn = array('name'=>'test', 'host'=>'localhost', 'port'=>'3306', 'socket'=>'',
'user'=>'root', 'pass'=>'', 'syntax'=>'mysql', 'type'=>'mysql');if (isEx($dbObject = &getDbObject($dsn))) {$dbObject->stackDump('echo');die();}
$objP =& new Bs_SimpleObjPersister;$objP->setDbObject($dbObject);$um =& new Bs_UserManagement;$status = $um->initObjPersister($objP);if ($_POST['bs_form']['name'] == 'user') {$do = 'addUser';} else {$do = $_REQUEST['do'];}
switch ($do) {case 'showUsers':
$mainContent = showUsers();break;case 'addUser':
$mainContent = addUser();break;default:
$mainContent = 'hello :-)';}
function addUser() {global $um; $u =& new Bs_User;$u->loadFormHints();$u->activateField('email');$dbHints   = array('name'=>'hobbies', 'metaType'=>'text',  'size'=>'5000');$formHints = array(
'group'           => 'grpContact', 
'fieldType'       => 'Bs_FormFieldTextarea', 
'must'            => TRUE, 
'caption'         => array('en'=>'Hobbies', 'de'=>'Hobbys'), 
'editability'     => 'always', 
'valueDefault'    => '', 
'bsDataType'      => 'blob', 
);$u->addField('hobbies', $dbHints, $formHints);$status = $u->formDoItYourself();if (is_string($status)) {return $status; } else {$status = $um->storeUser($u);if (!$status) {return $um->getLastError();}
return 'saved successfully.';}
}
function showUsers() {global $um; $userHash = &$um->getAllUsers(TRUE);if (!$userHash) return $um->getLastError(); $ret = '<table>';reset($userHash);$i = 1;while (list($username) = each($userHash)) {$ret .= '<tr>';$ret .= '<td>#' . $i . '</td>';$ret .= '<td>' . $userHash[$username]->user . '</td>';$ret .= '<td>' . $userHash[$username]->isActive . '</td>';$ret .= '<td>' . $userHash[$username]->startDatetime . '</td>';$ret .= '<td>' . $userHash[$username]->endDatetime . '</td>';$ret .= '<td>' . $userHash[$username]->email . '</td>';$ret .= '</tr>';$i++;}
$ret .= '</table>';reset($userHash);return $ret;}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Unbenannt</title>
</head>
<body>
<table width="800">
<tr>
<td valign="top" width="160">
group list<br>
add group<br>
<br>
<a href="<?php echo $_SERVER['PHP_SELF'];?>?do=showUsers">user list</a><br>
<a href="<?php echo $_SERVER['PHP_SELF'];?>?do=addUser">add user</a><br>
<br>
permission list<br>
add permission<br>
<br>
item list<br>
add item<br>
<br>
relation list</br>
add relation<br>
<br>
</td>
<td valign="top" width="640">
<?php echo $mainContent;?>
</td>
</tr>
</table>
</body>
</html>

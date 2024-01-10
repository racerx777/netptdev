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
if (!isSet($GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'])) $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] = '../';require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($GLOBALS['APP']['path']['core'] . 'db/Bs_Db.class.php');require_once($GLOBALS['APP']['path']['core'] . 'storage/objectpersister/Bs_SimpleObjPersister.class.php');require_once($GLOBALS['APP']['path']['applications'] . 'mailinglist/Bs_MailingList.class.php');require_once($GLOBALS['APP']['path']['applications'] . 'mailinglist/Bs_Ml_User.class.php');$dsn = array('name'=>'test', 'host'=>'localhost', 'port'=>'3306', 'socket'=>'',
'user'=>'root', 'pass'=>'', 'syntax'=>'mysql', 'type'=>'mysql');if (isEx($dbAgent = &getDbObject($dsn))) {$dbAgent->stackDump('echo');die();}
$objP =& new Bs_SimpleObjPersister;$objP->setDbAgent($dbAgent);$ml =& new Bs_MailingList;$status = $ml->initObjPersister($objP);$pageContent = addUser();if (is_array($pageContent)) {$mainContent = $pageContent['errors'] . $pageContent['form'];$headContent = $pageContent['include'] . $pageContent['onLoad'] . $pageContent['head'];} else {$mainContent = $pageContent;$headContent = '';}
function addUserHelper_addField(&$obj, $grp, $name, $caption) {$dbHints   = array('name'=>$name, 'metaType'=>'boolean');$formHints = array(
'group'           => $grp, 
'fieldType'       => 'Bs_FormFieldCheckbox', 
'caption'         => $caption, 
'text'            => $caption, 
'editability'     => 'always', 
'valueDefault'    => FALSE, 
'hideCaption'     => 2, 
'styles'          => array('class'=>'newsletterFields'), 
'textStyles'      => array('class'=>'newsletterFields'), 
);$obj->addField($name, $dbHints, $formHints);}
function addUser() {global $ml; $u =& new Bs_Ml_User;$u->loadFormHints();$u->formFields['email']['size'] = 10;$u->formFields['email']['events']['onFocus'] = "bs_doDisplay('spanNewsChecks');";$u->formProps['useTemplate']          = FALSE;$u->formProps['templatePath']         = 'c:/cvs/blueshoes.org/blueshoes.org/online/'; $u->formProps['skinName']             = 'textRight';addUserHelper_addField($u, 'Framework',    'newsFramework',        'Framework');addUserHelper_addField($u, 'Framework',    'newsKb',               'Knowledge Base');addUserHelper_addField($u, 'Plugins',      'newsInstanthelp',      'Instant Help');addUserHelper_addField($u, 'Plugins',      'newsPluginOnomastics', 'Onomastics');addUserHelper_addField($u, 'Plugins',      'newsIndexserver',      'Index Server');addUserHelper_addField($u, 'Applications', 'newsCms',              'CMS');addUserHelper_addField($u, 'Applications', 'newsDebedoo',          'Debedoo');addUserHelper_addField($u, 'Applications', 'newsSmartshop',        'Smart Shop');addUserHelper_addField($u, 'Applications', 'newsImagearchive',     'Image Archive');addUserHelper_addField($u, 'Applications', 'newsFilemanager',      'File Manager');addUserHelper_addField($u, 'JavaScript',   'newsSpreadsheet',      'Spreadsheet Editor');addUserHelper_addField($u, 'JavaScript',   'newsWysiwyg',          'Wysiwyg Editor');addUserHelper_addField($u, 'JavaScript',   'newsTree',             'Tree Control');addUserHelper_addField($u, 'JavaScript',   'newsSlider',           'Slider Control');addUserHelper_addField($u, 'JavaScript',   'newsCheckbox',         'Checkbox Control');addUserHelper_addField($u, 'JavaScript',   'newsRadio',            'Radio Control');addUserHelper_addField($u, 'JavaScript',   'newsDropdown',         'Dropdown Control');addUserHelper_addField($u, 'JavaScript',   'newsToolbar',          'Toolbar Control');addUserHelper_addField($u, 'JavaScript',   'newsJsrs',             'JS Remote Scripting');addUserHelper_addField($u, 'PHP Dev',      'newsCheat',            'PHP Cheat Sheet');addUserHelper_addField($u, 'PHP Dev',      'newsExam',             'PHP Syntax Exam');addUserHelper_addField($u, 'PHP Dev',      'newsBench',            'PHP Benchmark');require_once($GLOBALS['APP']['path']['core'] . 'html/form/Bs_FormItAble.class.php');$fia =& new Bs_FormItAble;$form = &$fia->buildForm($u);if (@$_POST['bs_form']['step'] == '2') {$status = $form->doItYourself();if (is_array($status)) return $status;} else {return $form->getAll();}
$form->addError('Email address already exists');return $form->getAll();$formData = $form->getValuesArray(TRUE, 'valueInternal');while (list($varName, $value) = each($formData)) {$u->$varName = $value;}
$status = $ml->storeUser($u);if (!$status) {return Bs_Error::getLastError();}
return 'saved successfully.';}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Unbenannt</title>
<style>
.newsletterFields {font-family: arial;font-size:   11px;}
legend {font-family: arial;font-size:   12px;font-weight: bold;}
</style>
<?php echo $headContent;?>
</head>
<body>
<?php echo $mainContent;?>
</body>
</html>

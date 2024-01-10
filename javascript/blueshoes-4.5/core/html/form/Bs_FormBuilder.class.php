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
define('BS_FORMBUILDER_VERSION',      '4.5.$Revision: 1.3 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'db/Bs_MySql.class.php');require_once($APP['path']['core'] . 'html/form/Bs_FormHandler.class.php');require_once($APP['path']['core'] . 'html/Bs_HtmlTable.class.php');class Bs_FormBuilder extends Bs_Object {var $_form;var $bsDb;var $persistType = 'db';function Bs_FormBuilder() {parent::Bs_Object(); $this->bsDb = &$GLOBALS['bsDb'];$this->checkTableStructure();}
function checkTableStructure() {if (!$this->bsDb->tableExists('Form', NULL, FALSE)) {$sqlC = "
CREATE TABLE Form (
ID int(10) unsigned NOT NULL auto_increment,
internalSerializedData blob,
prefixinternalName varchar(255) default NULL,
PRIMARY KEY  (ID),
KEY prefixinternalName (prefixinternalName)
)
"; $status = $this->bsDb->write($sqlC);if (isEx($status)) {dump($status);}
}
if (!$this->bsDb->tableExists('FormElement', NULL, FALSE)) {$sqlC = "
CREATE TABLE FormElement (
ID int(10) unsigned NOT NULL auto_increment,
internalSerializedData blob,
prefixname varchar(255) default NULL,
prefixelementType varchar(255) default NULL,
prefixFormID int(11) default NULL,
prefixfieldType varchar(255) default NULL,
PRIMARY KEY  (ID),
KEY prefixname (prefixname),
KEY prefixelementType (prefixelementType),
KEY prefixFormID (prefixFormID),
KEY prefixfieldType (prefixfieldType)
)
"; $this->bsDb->write($sqlC);}
}
function doItYourself() {$ret = '';if (isSet($GLOBALS['todo'])) {switch ($GLOBALS['todo']) {case 'formOverview':
$status = $this->_getPageFormOverview();if (isEx($status)) {$status->stackTrace('was here in doItYourself()', __FILE__, __LINE__);return $status;}
$ret .= $status;break;case 'elementSelector':
$status = $this->_getPageElementSelector();if (isEx($status)) {$status->stackTrace('was here in doItYourself()', __FILE__, __LINE__);return $status;}
$ret .= $status;break;case 'preview':
$status = $this->_getPreview();if (isEx($status)) {$status->stackTrace('was here in doItYourself()', __FILE__, __LINE__);return $status;}
$ret .= $status;break;default:
$ret .= 'lalala';}
} else {switch ($GLOBALS['bs_form']['name']) {case 'Form':
$status = $this->treatFormForm();if (isEx($status)) {$status->stackTrace('was here in doItYourself()', __FILE__, __LINE__);return $status;}
$ret .= $status;break;case 'FormElement':
$status = $this->treatFormFormElement();if (isEx($status)) {$status->stackTrace('was here in doItYourself()', __FILE__, __LINE__);return $status;}
$ret .= $status;break;default:
$status = $this->_getPageOverview();if (isEx($status)) {$status->stackTrace('was here in doItYourself()', __FILE__, __LINE__);return $status;}
$ret .= $status;}
}
return $ret;}
function _getPreview() {$formHandler =& new Bs_FormHandler($GLOBALS['bs_form']['recordId']);$status = $formHandler->loadForm();if (isEx($status)) {$status->stackTrace('was here in _getPreview()', __FILE__, __LINE__);return $status;}
$formHandler->form->seedClearingHouse();reset($formHandler->form->clearingHouse);while(list($k) = each($formHandler->form->clearingHouse)) {$formHandler->form->clearingHouse[$k]->elementStringFormat = "%s <a href=\"{$_SERVER['PHP_SELF']}?bs_form[name]=FormElement&bs_form[mode]=edit&bs_form[elementType]={$formHandler->form->clearingHouse[$k]->elementType}&bs_form[fieldType]={$formHandler->form->clearingHouse[$k]->fieldType}&bs_form[recordId]={$formHandler->form->clearingHouse[$k]->persisterID}\">edit</a>";}
$status = $formHandler->go(FALSE);if (isEx($status)) {$status->stackTrace('was here in _getPreview()', __FILE__, __LINE__);return $status;}
return $status;}
function treatFormForm() {$this->_loadFormForm();$ret = '';if (isSet($GLOBALS['HTTP_POST_VARS']['bs_form']['step']) && ($GLOBALS['HTTP_POST_VARS']['bs_form']['step'] == '2')) {$this->_form->setReceivedValues($GLOBALS['HTTP_POST_VARS']);$this->_form->postLoadTrigger(); $isOk = $this->_form->validate();if ($isOk) {$tempForm =& new Bs_Form();if ($GLOBALS['HTTP_POST_VARS']['bs_form']['mode'] == 'edit') {$tempForm->persisterID = $GLOBALS['HTTP_POST_VARS']['bs_form']['recordId'];$tempForm->unPersist(NULL, FALSE); }
$valuesArray = $this->_form->getValuesArray();reset($valuesArray);while (list($k) = each($valuesArray)) {$tempForm->$k = $valuesArray[$k];}
$status = $tempForm->persist(FALSE);if (isEx($status)) {$status->stackTrace('was here in treatFormForm()', __FILE__, __LINE__);return $status;}
$ret .= 'everything is ok<br><br>';} else {$ret .= $this->_form->getErrorTable('Errors occured');$ret .= $this->_form->getForm();}
} else {if (isSet($GLOBALS['HTTP_GET_VARS']['bs_form']['mode']) && ($GLOBALS['HTTP_GET_VARS']['bs_form']['mode'] == 'edit')) {$ret .= $this->_getFormFormForEdit();} else {$ret .= $this->_getFormFormForAdd();}
}
return $ret;}
function treatFormFormElement() {if ($GLOBALS['HTTP_POST_VARS']['bs_form']['step'] == '2') {$elementType = $GLOBALS['HTTP_POST_VARS']['elementType'];$fieldType   = $GLOBALS['HTTP_POST_VARS']['fieldType'];} else {$elementType = $GLOBALS['bs_form']['elementType'];$fieldType   = $GLOBALS['bs_form']['fieldType'];}
$this->_loadFormFormElement($elementType, $fieldType);$ret = '';if ($GLOBALS['HTTP_POST_VARS']['bs_form']['step'] == '2') {$this->_form->setReceivedValues($GLOBALS['HTTP_POST_VARS']);$isOk = $this->_form->validate();if ($isOk) {switch ($elementType) {case 'field':
$classString = 'Bs_FormField' . ucfirst($fieldType);$tempElement =& new $classString();break;default:
$classString = 'Bs_Form' . ucfirst($elementType);$tempElement =& new $classString;}
$this->_treatFormFormElementHelper($this->_form->elementContainer->formElements, $tempElement);if ($this->_form->recordId > 0) $tempElement->persisterID = $this->_form->recordId;$status = $tempElement->persister->persist();if (isEx($status)) {$status->stackTrace('was here in treatFormFormElement()', __FILE__, __LINE__);return $status;}
$ret .= 'everything is ok<br><br>';} else {$ret .= $this->_form->getErrorTable('Errors occured');$ret .= $this->_form->getForm();}
} else {$this->_form->setBsFormData($GLOBALS['HTTP_GET_VARS']['bs_form']);if ($GLOBALS['HTTP_GET_VARS']['bs_form']['mode'] == 'edit') {$ret .= $this->_getFormFormElementForEdit($elementType, $fieldType);} elseif ($GLOBALS['HTTP_GET_VARS']['bs_form']['mode'] == 'add') {$ret .= $this->_getFormFormElementForAdd($elementType, $fieldType);}
}
return $ret;}
function _treatFormFormElementHelper(&$elementContainer, &$tempElement) {if (is_array($elementContainer)) {reset($elementContainer);while(list($k) = each($elementContainer)) {switch ($elementContainer[$k]->elementType) {case 'container':
$this->_treatFormFormElementHelper($elementContainer[$k]->formElements, $tempElement);break;case 'field':
$tempElement->$k = $elementContainer[$k]->valueInternal;break;}
}
}
}
function _addFormRecord() {}
function _updateFormRecord() {}
function _getFormFormForEdit() {$this->_form->setBsFormData($GLOBALS['HTTP_GET_VARS']['bs_form']);$tempForm =& new Bs_Form();$tempForm->persisterID = $GLOBALS['HTTP_GET_VARS']['bs_form']['recordId'];$status = $tempForm->unPersist(NULL, FALSE);if (isEx($status)) {$status->stackTrace('was here in treatFormForm()', __FILE__, __LINE__);return $status;}
$objVars = get_object_vars($tempForm);$this->_form->setLoadedValues($objVars);$this->_form->postLoadTrigger(); $ret  = '<h3>Forms: Edit Form</h3>';$ret .= $this->_form->getForm();return $ret;}
function _getFormFormForAdd() {$this->_form->postLoadTrigger(); $this->_form->mode = 'add';$ret  = '<h3>Forms: Add Form</h3>';$ret .= $this->_form->getForm();return $ret;}
function _getFormFormElementForEdit($elementType, $fieldType) {$this->_form->setBsFormData($GLOBALS['HTTP_GET_VARS']['bs_form']);$this->_preloadFormElementSettings($elementType, $fieldType, $GLOBALS['HTTP_GET_VARS']['bs_form']['recordId']);$ret  = '<h3>Forms: Edit Form Element</h3>';$ret .= $this->_form->getForm();return $ret;}
function _getFormFormElementForAdd($elementType, $fieldType) {if (isSet($GLOBALS['HTTP_GET_VARS']['bs_form']['templateRecordId'])) {$tempArray = array('FormID'=>$GLOBALS['HTTP_GET_VARS']['bs_form']['formRecordId']);$this->_preloadFormElementSettings($elementType, $fieldType, $GLOBALS['HTTP_GET_VARS']['bs_form']['templateRecordId'], $tempArray);}
$ret  = '<h3>Forms: Add Form Element</h3>';$ret .= $this->_form->getForm();return $ret;}
function _preloadFormElementSettings($elementType, $fieldType, $ID, $overwriteDataArray=NULL) {switch ($elementType) {case 'field':
$classString = 'Bs_FormField' . ucfirst($fieldType);$tempElement =& new $classString;break;default:
$classString = 'Bs_Form' . ucfirst($elementType);$tempElement =& new $classString;}
$tempElement->persisterID = $ID;$status = $tempElement->persister->unPersist();if (isEx($status)) {$status->stackTrace('was here in _getFormFormElementForEdit()', __FILE__, __LINE__);return $status;}
$objVars = get_object_vars($tempElement);if (is_array($overwriteDataArray)) {$objVars = array_merge($objVars, $overwriteDataArray);}
$this->_form->setLoadedValues($objVars);$this->_form->postLoadTrigger($objVars);}
function _getPageOverview() {$ret = '';$ret .= '<h3>Forms: Overview</h3>';$ret .= "<a href=\"{$_SERVER['PHP_SELF']}?bs_form[name]=Form\">Add Form</a><br>\n";$ret .= "<a href=\"{$_SERVER['PHP_SELF']}?todo=elementSelector&bs_form[recordId]=0\">add form element template</a><br>\n";$ret .= "<br>\n";$ret .= "Existing forms:<br>";$status = $this->getFormsTable();if (isEx($status)) {$status->stackTrace('was here in _getPageOverview()', __FILE__, __LINE__);return $status;}
$ret .= $status;return $ret;}
function getFormsTable() {$sqlQ = "SELECT ID, prefixinternalName FROM Form ORDER BY prefixinternalName";$rsArray = $this->bsDb->getAll($sqlQ);if (isEx($rsArray)) {$rsArray->stackTrace('was here in getFormsOverview()', __FILE__, __LINE__);return $rsArray;}
while (list($k) = each($rsArray)) {$rsArray[$k]['editLink'] = "<a href=\"{$_SERVER['PHP_SELF']}?todo=formOverview&bs_form[recordId]={$rsArray[$k]['ID']}\">details</a>";}
array_unshift($rsArray, array('ID', 'Name', 'Details'));$tbl =& new Bs_HtmlTable($rsArray);return $tbl->renderTable();}
function _getPageFormOverview() {$ret = '';$ret .= '<h3>Forms: Form Overview</h3>';$ret .= "<a href=\"{$_SERVER['PHP_SELF']}?todo=preview&bs_form[recordId]={$GLOBALS['HTTP_GET_VARS']['bs_form']['recordId']}\">show form preview</a><br>";$ret .= "<a href=\"{$_SERVER['PHP_SELF']}?bs_form[name]=Form&bs_form[mode]=edit&bs_form[recordId]={$GLOBALS['HTTP_GET_VARS']['bs_form']['recordId']}\">edit form properties</a><br>";$ret .= "<a href=\"{$_SERVER['PHP_SELF']}?todo=elementSelector&bs_form[recordId]={$GLOBALS['HTTP_GET_VARS']['bs_form']['recordId']}\">add form element</a><br>";$ret .= "<a href=\"{$_SERVER['PHP_SELF']}\">back to overview</a><br><br>";$ret .= "Existing form elements:<br>";$status = $this->getElementsTable($GLOBALS['HTTP_GET_VARS']['bs_form']['recordId']);if (isEx($status)) {$status->stackTrace('was here in _getPageFormOverview()', __FILE__, __LINE__);return $status;}
$ret .= $status;return $ret;}
function getElementsTable($formId) {$sqlQ = "SELECT ID, prefixname, prefixelementType, prefixfieldType FROM FormElement WHERE prefixFormID = {$formId} ORDER BY prefixname";$rsArray = $this->bsDb->getAll($sqlQ);if (isEx($rsArray)) {$rsArray->stackTrace('was here in getElementsTable()', __FILE__, __LINE__);return $rsArray;}
while (list($k) = each($rsArray)) {$rsArray[$k]['editLink'] = "<a href='{$_SERVER['PHP_SELF']}?bs_form[name]=FormElement&bs_form[mode]=edit&bs_form[elementType]={$rsArray[$k]['prefixelementType']}&bs_form[fieldType]={$rsArray[$k]['prefixfieldType']}&bs_form[recordId]={$rsArray[$k]['ID']}'>edit</a>";}
array_unshift($rsArray, array('ID', 'Name', 'Element Type', 'Field Type', 'Edit'));$tbl =& new Bs_HtmlTable($rsArray);return $tbl->renderTable();}
function _getPageElementSelector() {$ret = '';$ret .= '<h3>Forms: add form element</h3><br>';$ret .= '<h4>Plain new field:</h4>';$ret .= "<table border='1' cellpadding='10' cellspacing='0'>\n";$ret .= " <tr>\n";$ret .= $this->_elementSelectRowBuilder('field', 'Text');$ret .= $this->_elementSelectRowBuilder('field', 'Password');$ret .= $this->_elementSelectRowBuilder('field', 'Hidden');$ret .= " </tr>\n";$ret .= " <tr>\n";$ret .= $this->_elementSelectRowBuilder('field', 'Checkbox');$ret .= $this->_elementSelectRowBuilder('field', 'Radio');$ret .= $this->_elementSelectRowBuilder('field', 'Select');$ret .= " </tr>\n";$ret .= " <tr>\n";$ret .= $this->_elementSelectRowBuilder('field', 'Textarea');$ret .= $this->_elementSelectRowBuilder('field', 'Image');$ret .= $this->_elementSelectRowBuilder('field', 'File');$ret .= " </tr>\n";$ret .= " <tr>\n";$ret .= $this->_elementSelectRowBuilder('field', 'Submit');$ret .= $this->_elementSelectRowBuilder('field', 'Reset');$ret .= $this->_elementSelectRowBuilder('field', 'Button');$ret .= " </tr>\n";$ret .= "</table>\n";$ret .= '<br><br>';$ret .= '<h4>Other Form Element:</h4>';$ret .= "<table border='1' cellpadding='10' cellspacing='0'>\n";$ret .= " <tr>\n";$ret .= $this->_elementSelectRowBuilder('Container');$ret .= $this->_elementSelectRowBuilder('Image');$ret .= $this->_elementSelectRowBuilder('Line');$ret .= " </tr>\n";$ret .= " <tr>\n";$ret .= $this->_elementSelectRowBuilder('Text');$ret .= $this->_elementSelectRowBuilder('Html');$ret .= $this->_elementSelectRowBuilder('Code');$ret .= " </tr>\n";$ret .= "</table>\n";$ret .= '<br><br>';$ret .= '<h4>Create from template:</h4>';$sqlQ = "SELECT ID, prefixname, prefixelementType, prefixfieldType FROM FormElement WHERE prefixFormID = 0 ORDER BY prefixname";$rsArray = $this->bsDb->getAll($sqlQ);if (isEx($rsArray)) {$rsArray->stackTrace('was here in _getPageElementSelector()', __FILE__, __LINE__);return $rsArray;}
while (list($k) = each($rsArray)) {$rsArray[$k]['useLink']  = "<a href=\"{$_SERVER['PHP_SELF']}?bs_form[name]=FormElement&bs_form[mode]=add&bs_form[elementType]=" . strToLower($rsArray[$k]['prefixelementType']) . "&bs_form[fieldType]=" . strToLower($rsArray[$k]['prefixfieldType']) . "&bs_form[templateRecordId]={$rsArray[$k]['ID']}&bs_form[formRecordId]={$GLOBALS['bs_form']['recordId']}\">use</a>";$rsArray[$k]['editLink'] = "<a href=\"{$_SERVER['PHP_SELF']}?bs_form[name]=FormElement&bs_form[mode]=edit&bs_form[elementType]={$rsArray[$k]['prefixelementType']}&bs_form[fieldType]={$rsArray[$k]['prefixfieldType']}&bs_form[recordId]={$rsArray[$k]['ID']}\">edit</a>";}
array_unshift($rsArray, array('ID', 'Name', 'Element Type', 'Field Type', 'Use Template', 'Edit Template'));$tbl =& new Bs_HtmlTable($rsArray);$ret .= $tbl->renderTable();$ret .= '<br><br>';$ret .= '<h4>Create from existing field:</h4>';$sqlQ = "SELECT ID, prefixname, prefixelementType, prefixfieldType FROM FormElement WHERE prefixFormID = {$GLOBALS['HTTP_GET_VARS']['bs_form']['recordId']} ORDER BY prefixname";$rsArray = $this->bsDb->getAll($sqlQ);if (isEx($rsArray)) {$rsArray->stackTrace('was here in _getPageElementSelector()', __FILE__, __LINE__);return $rsArray;}
while (list($k) = each($rsArray)) {$rsArray[$k]['useLink']  = "<a href=\"{$_SERVER['PHP_SELF']}?bs_form[name]=FormElement&bs_form[mode]=add&bs_form[elementType]=" . strToLower($rsArray[$k]['prefixelementType']) . "&bs_form[fieldType]=" . strToLower($rsArray[$k]['prefixfieldType']) . "&bs_form[templateRecordId]={$rsArray[$k]['ID']}&bs_form[formRecordId]={$GLOBALS['bs_form']['recordId']}\">use</a>";}
array_unshift($rsArray, array('ID', 'Name', 'Element Type', 'Field Type', 'Use Template'));$tbl =& new Bs_HtmlTable($rsArray);$ret .= $tbl->renderTable();$ret .= '<br><br>';return $ret;}
function _elementSelectRowBuilder($elementType, $fieldType=NULL) {if ($elementType == 'field') {$link = "{$_SERVER['PHP_SELF']}?bs_form[name]=FormElement&bs_form[mode]=add&bs_form[elementType]=field&bs_form[fieldType]=" . strToLower($fieldType) . "&bs_form[formRecordId]=" . $GLOBALS['bs_form']['recordId'];return "   <td align='center' valign='bottom'><a href='{$link}'><div><img src='images/formBuilder_field{$fieldType}.gif' border='0'><br>{$fieldType}</div></a></td>\n";} else {$link = "{$_SERVER['PHP_SELF']}?bs_form[name]=FormElement&bs_form[mode]=add&bs_form[elementType]=" . strToLower($elementType) . "&bs_form[formRecordId]=" . $GLOBALS['bs_form']['recordId'];return "   <td align='center' valign='bottom'><a href='{$link}'><div><img src='images/formBuilder_{$elementType}.gif' border='0'><br>{$elementType}</div></a></td>\n";}
}
function _loadFormForm() {$this->_form =& new Bs_Form();$this->_form->internalName  = "Form";$this->_form->name          = "Form";$this->_form->language      = "en";$this->_form->useAccessKeys = TRUE;$FormID = 0;unset($container);$container =& new Bs_FormContainer();$container->name         = "basic";$container->FormID       = $FormID;$container->caption      = array('en'=>'Basic Information');$container->orderId      = 1000;$this->_form->elementContainer->addElement($container);$element =& new Bs_FormFieldText();$element->name          = 'internalName';$element->FormID        = $FormID;$element->caption       = array('en'=>'Internal Name');$element->editability   = 'once';$element->minLength     = 3;$element->maxLength     = 30;$element->orderId       = 1000;$element->bsDataType    = 'text';$element->bsDataInfo    = 2;$element->must          = TRUE;$element->trim          = 'both';$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'name';$element->FormID        = $FormID;$element->caption       = array('en'=>'Name');$element->editability   = 'always';$element->minLength     = 0;$element->maxLength     = 100;$element->orderId       = 900;$element->bsDataType    = 'text';$element->bsDataInfo    = 1;$element->must          = FALSE;$element->trim          = 'both';$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'action';$element->FormID        = $FormID;$element->caption       = array('en'=>'Action');$element->editability   = 'always';$element->minLength     = 0;$element->maxLength     = 255;$element->orderId       = 800;$element->bsDataType    = 'text';$element->bsDataInfo    = 1;$element->must          = FALSE;$element->trim          = 'both';$container->addElement($element);unset($element);$element =& new Bs_FormFieldSelect();$element->name          = 'method';$element->FormID        = $FormID;$element->caption       = array('en'=>'Method');$element->optionsHard   = array('en'=>array(''=>'', 'get'=>'get', 'post'=>'post'));$element->editability   = 'always';$element->minLength     = 3;$element->maxLength     = 4;$element->orderId       = 700;$element->bsDataType    = 'text';$element->bsDataInfo    = 3;$element->must          = FALSE;$element->trim          = 'both';$container->addElement($element);unset($element);$element =& new Bs_FormFieldSelect();$element->name          = 'encType';$element->FormID        = $FormID;$element->caption       = array('en'=>'Enctype');$element->optionsHard   = array('en'=>array(''=>'', 
'application/x-www-form-urlencoded'=>'application/x-www-form-urlencoded (default)', 
'multipart/form-data'=>'multipart/form-data (required for INPUT TYPE=FILE)', 
'text/plain'=>'text/plain (for mailto: only)'));$element->editability   = 'always';$element->minLength     = 10;$element->maxLength     = 40;$element->orderId       = 600;$element->bsDataType    = 'text';$element->bsDataInfo    = 1;$element->must          = FALSE;$element->trim          = 'both';$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'target';$element->FormID        = $FormID;$element->caption       = array('en'=>'Target');$element->editability   = 'always';$element->minLength     = 0;$element->maxLength     = 100;$element->orderId       = 500;$element->bsDataType    = 'text';$element->bsDataInfo    = 1;$element->must          = FALSE;$element->trim          = 'both';$container->addElement($element);unset($element);unset($container);$container =& new Bs_FormContainer();$container->name         = "look";$container->FormID       = $FormID;$container->caption      = array('en'=>'Look & Feel');$container->orderId      = 900;$this->_form->elementContainer->addElement($container);$element =& new Bs_FormFieldText();$element->name          = 'styles';$element->FormID        = $FormID;$element->caption       = array('en'=>'Styles');$element->editability   = 'always';$element->minLength     = 0;$element->maxLength     = 255;$element->orderId       = 1000;$element->bsDataType    = 'string';$element->must          = FALSE;$element->trim          = 'both';$element->setExplode("return array('class'=>'Class', 
'id'   =>'ID', 
'style'=>'Style');");$container->addElement($element);unset($element);$element =& new Bs_FormFieldCheckbox();$element->name          = 'useAccessKeys';$element->FormID        = $FormID;$element->caption       = array('en'=>'Use Access Keys');$element->editability   = 'always';$element->orderId       = 950;$element->bsDataType    = 'boolean';$element->valueDefault  = FALSE;$element->text          = 'Use access keys for keyboard access to the fields.';$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'accessKeyTags';$element->FormID        = $FormID;$element->caption       = array('en'=>'Access Keys');$element->editability   = 'always';$element->minLength     = 0;$element->maxLength     = 255;$element->orderId       = 900;$element->bsDataType    = 'blob';$element->must          = FALSE;$element->trim          = 'both';$element->setExplode("return array('0'=>'Start Tag', 
'1'=>'End Tag');");$container->addElement($element);unset($element);$element =& new Bs_FormFieldSelect();$element->name          = 'direction';$element->FormID        = $FormID;$element->caption       = array('en'=>'Direction');$element->optionsHard   = array('en'=>array(''=>'', 
'ltr'=>'left to right (default)', 
'rtl'=>'right to left'));$element->editability   = 'always';$element->minLength     = 0;$element->maxLength     = 3;$element->orderId       = 800;$element->bsDataType    = 'text';$element->bsDataInfo    = 3;$element->must          = FALSE;$element->trim          = 'both';$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'events';$element->FormID        = $FormID;$element->caption       = array('en'=>'Events');$element->editability   = 'always';$element->minLength     = 0;$element->maxLength     = 255;$element->orderId       = 700;$element->bsDataType    = 'blob';$element->must          = FALSE;$element->trim          = 'both';$element->setExplode("return array('onSubmit'   =>'onSubmit', 
'onRest'     =>'onRest', 
'onClick'    =>'onClick', 
'onDblClick' =>'onDblClick', 
'onMouseDown'=>'onMouseDown', 
'onMouseUp'  =>'onMouseUp', 
'onMouseOver'=>'onMouseOver', 
'onMouseMove'=>'onMouseMove', 
'onMouseOut' =>'onMouseOut', 
'onKeyPress' =>'onKeyPress', 
'onKeyDown'  =>'onKeyDown', 
'onKeyUp'    =>'onKeyUp');");$container->addElement($element);unset($element);$element =& new Bs_FormFieldSelect();$element->name          = 'disabledMode';$element->FormID        = $FormID;$element->caption       = array('en'=>'Disable Mode');$element->optionsHard   = array('en'=>array(''=>'', 
'1'=>'ie style using disabled and readonly (default)', 
'2'=>'javascript', 
'3'=>'html'));$element->editability   = 'always';$element->minLength     = 1;$element->maxLength     = 1;$element->orderId       = 600;$element->bsDataType    = 'number';$element->bsDataInfo    = '1|3';$element->must          = FALSE;$element->trim          = 'none';$container->addElement($element);unset($element);$element =& new Bs_FormFieldSelect();$element->name          = 'mustFieldsVisualMode';$element->FormID        = $FormID;$element->caption       = array('en'=>'Must Fields Visual Mode');$element->optionsHard   = array('en'=>array(''=>'', 
'none'     =>'none', 
'starLeft' =>'starLeft', 
'starRight'=>'starRight'));$element->editability   = 'always';$element->minLength     = 4;$element->maxLength     = 9;$element->orderId       = 500;$element->bsDataType    = 'text';$element->bsDataInfo    = '3';$element->must          = FALSE;$element->trim          = 'none';$element->valueDefault  = 'starLeft';$container->addElement($element);unset($element);$element =& new Bs_FormFieldCheckbox();$element->name          = 'useTemplate';$element->FormID        = $FormID;$element->caption       = array('en'=>'Use Template');$element->editability   = 'always';$element->orderId       = 400;$element->bsDataType    = 'boolean';$element->valueDefault  = FALSE;$element->text          = 'Use template if available.';$container->addElement($element);unset($element);$element =& new Bs_FormFieldTextarea();$element->name             = 'buttons';$element->caption          = array('en'=>'Buttons');$element->editability      = 'always';$element->minLength        = 0;$element->maxLength        = 65535;$element->orderId          = 300;$element->bsDataType       = 'blob';$element->valueDefault     = 'default';$codePostReceive = "#code codePostReceive\n
if ((isSet(\$this->valueInternal)) && (!empty(\$this->valueInternal))) {\n
if (\$this->valueInternal == 'default') return TRUE;\n
\$t = eval(\$this->valueInternal);\n
if (is_array(\$t)) {\n
\$this->valueInternal = &\$t;\n
return TRUE;\n
} else {\n
return FALSE;\n
}\n
}\n
return TRUE;\n
";$codePostLoad = "#code codePostLoad\n
#var_dump(\$this->valueDefault);\n
if (isSet(\$this->valueDefault) && is_array(\$this->valueDefault)) {\n
\$this->valueDefault  = \$GLOBALS['Bs_Array']->arrayToCode(\$this->valueDefault, '\$array');\n
\$this->valueDefault .= 'return \$array;';\n
} elseif (isSet(\$this->valueDefault) && (\$this->valueDefault == 'default')) {\n
#do nothing
} else {\n
#\$this->valueDefault = '';\n
}\n
";$element->codePostReceive = $codePostReceive;$element->codePostLoad    = $codePostLoad;$container->addElement($element);unset($element);unset($container2);$container2 =& new Bs_FormContainer();$container2->name         = "handling";$container2->FormID       = $FormID;$container2->caption      = array('en'=>'Handling');$container2->orderId      = 800;$this->_form->elementContainer->addElement($container2);unset($container);$container =& new Bs_FormContainer();$container->name         = "storage";$container->FormID       = $FormID;$container->orderId      = 1000;$container->useCheckboxAsCaption = 'saveToDb';$container2->addElement($container);$element =& new Bs_FormFieldCheckbox();$element->name          = 'saveToDb';$element->FormID        = $FormID;$element->caption       = array('en'=>'Persist');$element->editability   = 'always';$element->orderId       = 1000;$element->bsDataType    = 'boolean';$element->valueDefault  = TRUE;$element->text          = 'Save the submitted form data to a database.';$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'dbTableName';$element->FormID        = $FormID;$element->caption       = array('en'=>'Table name');$element->editability   = 'always';$element->minLength     = 8;$element->maxLength     = 25;$element->orderId       = 900;$element->bsDataType    = 'text';$element->bsDataInfo    = 2;$element->mustStartWith = array('form');$element->trim          = 'both';$element->enforce       = array('minLength'=>TRUE, 'mustStartWith'=>TRUE);$container->addElement($element);unset($element);$element =& new Bs_FormFieldTextarea();$element->name             = 'additionalParams';$element->caption          = array('en'=>'Additional Params');$element->editability      = 'always';$element->minLength        = 0;$element->maxLength        = 65535;$element->orderId          = 800;$element->bsDataType       = 'blob';$codePostReceive = "#code codePostReceive\n
if ((isSet(\$this->valueInternal)) && (!empty(\$this->valueInternal))) {\n
\$t = eval(\$this->valueInternal);\n
if (is_array(\$t)) {\n
\$this->valueInternal = &\$t;\n
return TRUE;\n
} else {\n
return FALSE;\n
}\n
}\n
return TRUE;\n
";$codePostLoad = "#code codePostLoad\n
#var_dump(\$this->valueDefault);\n
if (isSet(\$this->valueDefault) && is_array(\$this->valueDefault)) {\n
\$this->valueDefault  = \$GLOBALS['Bs_Array']->arrayToCode(\$this->valueDefault, '\$array');\n
\$this->valueDefault .= 'return \$array;';\n
} else {\n
#\$this->valueDefault = '';\n
}\n
";$element->codePostReceive = $codePostReceive;$element->codePostLoad    = $codePostLoad;$container->addElement($element);unset($element);unset($container);$container =& new Bs_FormContainer();$container->name         = "rawMail";$container->FormID       = $FormID;$container->orderId      = 900;$container->useCheckboxAsCaption = 'sendMailRaw';$container2->addElement($container);$element =& new Bs_FormFieldCheckbox();$element->name          = 'sendMailRaw';$element->FormID        = $FormID;$element->caption       = array('en'=>'Raw Mail');$element->editability   = 'always';$element->orderId       = 1000;$element->bsDataType    = 'boolean';$element->valueDefault  = FALSE;$element->text          = 'Send the submitted form data in raw format by eMail.';$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'mailRawTo';$element->FormID        = $FormID;$element->caption       = array('en'=>'To');$element->editability   = 'always';$element->minLength     = 5;$element->maxLength     = 255;$element->orderId       = 900;$element->bsDataType    = 'text';$element->bsDataInfo    = 1;$element->must          = FALSE;$element->mustIf        = array(array('field'=>'sendMailRaw'));$element->trim          = 'both';$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'mailRawCc';$element->FormID        = $FormID;$element->caption       = array('en'=>'Cc');$element->editability   = 'always';$element->minLength     = 5;$element->maxLength     = 255;$element->orderId       = 800;$element->bsDataType    = 'text';$element->bsDataInfo    = 1;$element->trim          = 'both';$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'mailRawBcc';$element->FormID        = $FormID;$element->caption       = array('en'=>'Bcc');$element->editability   = 'always';$element->minLength     = 5;$element->maxLength     = 255;$element->orderId       = 700;$element->bsDataType    = 'text';$element->bsDataInfo    = 1;$element->trim          = 'both';$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'mailRawSubject';$element->FormID        = $FormID;$element->caption       = array('en'=>'Subject');$element->editability   = 'always';$element->minLength     = 5;$element->maxLength     = 150;$element->orderId       = 600;$element->bsDataType    = 'text';$element->bsDataInfo    = 1;$element->mustIf        = array(array('field'=>'sendMailRaw'));$element->trim          = 'both';$element->setExplode("global \$APP; return \$APP['usedLanguages'];");$container->addElement($element);unset($element);unset($container);$container =& new Bs_FormContainer();$container->name         = "niceMail1";$container->FormID       = $FormID;$container->orderId      = 800;$container->useCheckboxAsCaption = 'sendMailNice1';$container2->addElement($container);$element =& new Bs_FormFieldCheckbox();$element->name          = 'sendMailNice1';$element->FormID        = $FormID;$element->caption       = array('en'=>'Nice Mail 1');$element->editability   = 'always';$element->orderId       = 1000;$element->bsDataType    = 'boolean';$element->valueDefault  = FALSE;$element->text          = 'Send an email based on a template.';$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'mailNice1To';$element->FormID        = $FormID;$element->caption       = array('en'=>'To');$element->editability   = 'always';$element->minLength     = 5;$element->maxLength     = 255;$element->orderId       = 900;$element->bsDataType    = 'text';$element->bsDataInfo    = 1;$element->must          = FALSE;$element->mustIf        = array(array('field'=>'sendMailNice1'));$element->trim          = 'both';$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'mailNice1Cc';$element->FormID        = $FormID;$element->caption       = array('en'=>'Cc');$element->editability   = 'always';$element->minLength     = 5;$element->maxLength     = 255;$element->orderId       = 800;$element->bsDataType    = 'text';$element->bsDataInfo    = 1;$element->trim          = 'both';$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'mailNice1Bcc';$element->FormID        = $FormID;$element->caption       = array('en'=>'Bcc');$element->editability   = 'always';$element->minLength     = 5;$element->maxLength     = 255;$element->orderId       = 700;$element->bsDataType    = 'text';$element->bsDataInfo    = 1;$element->trim          = 'both';$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'mailNice1Subject';$element->FormID        = $FormID;$element->caption       = array('en'=>'Subject');$element->editability   = 'always';$element->minLength     = 5;$element->maxLength     = 150;$element->orderId       = 600;$element->bsDataType    = 'text';$element->bsDataInfo    = 1;$element->mustIf        = array(array('field'=>'sendMailNice1'));$element->trim          = 'both';$element->setExplode("global \$APP; return \$APP['usedLanguages'];");$container->addElement($element);unset($element);$element =& new Bs_FormFieldTextarea();$element->name          = 'mailNice1Template';$element->FormID        = $FormID;$element->caption       = array('en'=>'Template');$element->editability   = 'always';$element->minLength     = 10;$element->maxLength     = 65535;$element->orderId       = 500;$element->bsDataType    = 'blob';$element->mustIf        = array(array('field'=>'sendMailNice1'));$element->setExplode("global \$APP; return \$APP['usedLanguages'];");$container->addElement($element);unset($element);unset($container);$container =& new Bs_FormContainer();$container->name         = "functions";$container->FormID       = $FormID;$container->caption      = array('en'=>'Functions');$container->orderId      = 700;$this->_form->elementContainer->addElement($container);$element =& new Bs_FormFieldSubmit();$element->name         = "submit";$element->FormID       = $FormID;$element->editability  = 'always';$element->caption      = 'Save';$container->addElement($element);unset($element);$element =& new Bs_FormFieldButton();$element->name         = "cancel";$element->FormID       = $FormID;$element->editability  = 'always';$element->caption      = 'Cancel';$element->events['onClick'] = "javascript:window.location.href = '{$_SERVER['PHP_SELF']}';";$container->addElement($element);unset($element);}
function _loadFormFormElement($elementType, $fieldType) {$this->_form =& new Bs_Form();$this->_form->internalName  = "FormElement";$this->_form->name          = "FormElement";$this->_form->language      = "en";$this->_form->useAccessKeys = TRUE;unset($container);$container =& new Bs_FormContainer();$container->name         = "basic";$container->caption      = array('en'=>'Basic Information');$container->orderId      = 1000;$this->_form->elementContainer->addElement($container);$element =& new Bs_FormFieldText();$element->name          = 'FormID';$element->caption       = array('en'=>'Form ID');$element->editability   = 'once';$element->minLength     = 1;$element->maxLength     = 10;$element->orderId       = 1000;$element->bsDataType    = 'number';$element->bsDataInfo    = '1|';$element->must          = TRUE;$element->valueDefault  = $GLOBALS['HTTP_GET_VARS']['bs_form']['formRecordId'];$element->visibility    = 'invisible';$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'elementType';$element->caption       = array('en'=>'Element Type');$element->editability   = 'once';$element->minLength     = 4;$element->maxLength     = 20;$element->orderId       = 900;$element->bsDataType    = 'text';$element->bsDataInfo    = 3;$element->must          = TRUE;$element->valueDefault  = $elementType;$element->visibility    = 'invisible';$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'name';$element->caption       = array('en'=>'Name');$element->editability   = 'once';$element->minLength     = 2;$element->maxLength     = 20;$element->orderId       = 800;$element->bsDataType    = 'text';$element->bsDataInfo    = 2;$element->must          = TRUE;$container->addElement($element);unset($element);if ($elementType == 'container') {$element =& new Bs_FormFieldText();$element->name          = 'level';$element->caption       = array('en'=>'Level');$element->editability   = 'always';$element->minLength     = 1;$element->maxLength     = 2;$element->orderId       = 750;$element->bsDataType    = 'number';$element->bsDataInfo    = '1|20';$element->must          = FALSE;$element->trim          = 'both';$container->addElement($element);unset($element);}
$element =& new Bs_FormFieldText();$element->name          = 'container';$element->caption       = array('en'=>'Container');$element->editability   = 'always';$element->minLength     = 1;$element->maxLength     = 30;$element->orderId       = 700;$element->bsDataType    = 'text';$element->bsDataInfo    = 3;$element->must          = FALSE;$element->trim          = 'both';$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'orderId';$element->caption       = array('en'=>'Order');$element->editability   = 'always';$element->minLength     = 1;$element->maxLength     = 15;$element->orderId       = 600;$element->bsDataType    = 'number';$element->bsDataInfo    = '0|'; $element->must          = FALSE;$element->trim          = 'both';$container->addElement($element);unset($element);$element =& new Bs_FormFieldTextarea();$element->name             = 'elementStringFormat';$element->caption          = array('en'=>'elementStringFormat');$element->editability      = 'always';$element->minLength        = 0;$element->maxLength        = 65535;$element->orderId          = 500;$element->bsDataType       = 'blob';$element->setExplode("global \$APP; return \$APP['usedLanguages'];");$container->addElement($element);unset($element);if ($elementType == 'field') {unset($container);$container =& new Bs_FormContainer();$container->name         = "basic2";$container->caption      = array('en'=>'Basic Information 2');$container->orderId      = 950;$this->_form->elementContainer->addElement($container);$element =& new Bs_FormFieldText();$element->name          = 'fieldType';$element->caption       = array('en'=>'Type');$element->editability   = 'once';$element->minLength     = 4;$element->maxLength     = 20;$element->orderId       = 1000;$element->bsDataType    = 'text';$element->bsDataInfo    = 2;$element->must          = TRUE;$element->valueDefault  = $fieldType;$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'caption';$element->caption       = array('en'=>'Caption');$element->editability   = 'always';$element->minLength     = 1;$element->maxLength     = 30;$element->orderId       = 900;$element->bsDataType    = 'text';$element->bsDataInfo    = 1;$element->must          = TRUE;$element->trim          = 'both';$element->setExplode("global \$APP; return \$APP['usedLanguages'];");$container->addElement($element);unset($element);if ($fieldType == 'select') {$element =& new Bs_FormFieldSelect();$element->name          = 'valueDefaultType';$element->caption       = array('en'=>'Default Value type');$element->optionsHard   = array(''=>'', 'text'=>'text', 'code'=>'code', 'array'=>'array');$element->editability   = 'always';$element->orderId       = 801;$element->bsDataType    = 'text';$element->bsDataInfo    = 3;$element->must          = FALSE;$container->addElement($element);unset($element);$element =& new Bs_FormFieldTextarea();$element->name             = 'valueDefault';$element->caption          = array('en'=>'Default Value');$element->editability      = 'always';$element->minLength        = 0;$element->maxLength        = 65535;$element->orderId          = 800;$element->bsDataType       = 'blob';$codePostReceive = "#code codePostReceive\n
//we don't check for $this->hasFormObject() here.
//if ((\$this->_form->getFieldValue('valueDefaultType') == 'array') && (\$this->_form->getFieldValue('multiple') == TRUE)) {\n
if (\$this->_form->getFieldValue('valueDefaultType') == 'array') {\n
if (is_array(\$this->valueInternal)) {\n
while (list(\$k) = each(\$this->valueInternal)) {\n
\$t = eval(\$this->valueInternal[\$k]);\n
if ((is_array(\$t)) || (is_string(\$t))) {\n
\$this->valueInternal[\$k] = &\$t;\n
} else {\n
return FALSE;\n
}\n
}
return TRUE;\n
} elseif (is_string(\$this->valueInternal) && !empty(\$this->valueInternal)) {\n
\$t = eval(\$this->valueInternal);\n
if ((is_array(\$t)) || (is_string(\$t))) {\n
\$this->valueInternal = &\$t;\n
return TRUE;\n
} else {\n
return FALSE;\n
}\n
}\n
//murphy. should have been cought.\n
//\$this->valueInternal = '';\n
return FALSE;\n
}
";$codePostLoad = "#code codePostLoad\n
#var_dump(\$this->valueDefault);\n
if (is_array(\$this->valueDefault)) {\n
\$t = \$GLOBALS['Bs_Array']->guessType(\$this->valueDefault);\n
switch (\$t) {\n
case 'vector':\n
case 'vector_guess':\n
\$this->valueDefault  = \$GLOBALS['Bs_Array']->arrayToCode(\$this->valueDefault, '\$array');\n
\$this->valueDefault .= 'return \$array;';\n
break;\n
case 'hash':\n
case 'hash_guess':\n
while (list(\$k) = each(\$this->valueDefault)) {\n
if (is_array(\$this->valueDefault[\$k])) {\n
\$this->valueDefault[\$k]  = \$GLOBALS['Bs_Array']->arrayToCode(\$this->valueDefault[\$k], '\$array');\n
\$this->valueDefault[\$k] .= 'return \$array;';\n
}\n
}\n
break;\n
default: //also case FALSE:\n
//yuck.\n
#\$this->valueDefault = '';\n
}\n
}\n
";$element->codePostReceive = $codePostReceive;$element->codePostLoad    = $codePostLoad;$element->setExplode("global \$APP; return \$APP['usedLanguages'];");$container->addElement($element);unset($element);} else {$element =& new Bs_FormFieldSelect();$element->name          = 'valueDefaultType';$element->caption       = array('en'=>'Default Value type');$element->optionsHard   = array(''=>'', 'text'=>'text', 'code'=>'code');$element->editability   = 'always';$element->orderId       = 801;$element->bsDataType    = 'text';$element->bsDataInfo    = 3;$element->must          = FALSE;$container->addElement($element);unset($element);$element =& new Bs_FormFieldTextarea();$element->name          = 'valueDefault';$element->caption       = array('en'=>'Default Value');$element->editability   = 'always';$element->minLength     = 0;$element->maxLength     = 65535;$element->orderId       = 800;$element->bsDataType    = 'blob';$element->bsDataInfo    = 1;$element->must          = FALSE;$element->setExplode("global \$APP; return \$APP['usedLanguages'];");$container->addElement($element);unset($element);}
unset($container);$container =& new Bs_FormContainer();$container->name         = "visibility";$container->caption      = array('en'=>'Visibility');$container->orderId      = 900;$this->_form->elementContainer->addElement($container);$element =& new Bs_FormFieldSelect();$element->name          = 'editability';$element->caption       = array('en'=>'Editability');$element->optionsHard   = array('en'=>array(''=>'', 'always'=>'always', 'once'=>'once', 'never'=>'never'));$element->editability   = 'always';$element->orderId       = 1000;$element->bsDataType    = 'text';$element->bsDataInfo    = 3;$element->must          = TRUE;$container->addElement($element);unset($element);$element =& new Bs_FormFieldSelect();$element->name          = 'visibility';$element->caption       = array('en'=>'Visibility');$element->optionsHard   = array('en'=>array(''         =>'', 
'normal'   =>'normal', 
'read'     =>'read', 
'readonly' =>'readonly', 
'invisible'=>'invisible', 
'omit'     =>'omit'
)
);$element->editability   = 'always';$element->orderId       = 900;$element->bsDataType    = 'text';$element->bsDataInfo    = 3;$element->must          = FALSE;$container->addElement($element);unset($element);unset($container);$container =& new Bs_FormContainer();$container->name         = "dbstorage";$container->caption      = array('en'=>'DB Storage');$container->orderId      = 800;$this->_form->elementContainer->addElement($container);$element =& new Bs_FormFieldCheckbox();$element->name          = 'saveToDb';$element->caption       = array('en'=>'Persist');$element->editability   = 'always';$element->orderId       = 1000;$element->bsDataType    = 'boolean';$element->valueDefault  = TRUE;$element->text          = 'Save the value of this field to the database.';$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'dbFieldName';$element->caption       = array('en'=>'DB Field Name');$element->editability   = 'once';$element->minLength     = 1;$element->maxLength     = 15;$element->orderId       = 900;$element->bsDataType    = 'text';$element->bsDataInfo    = 3;$element->must          = FALSE;$element->trim          = 'both';$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'dbDataType';$element->caption       = array('en'=>'DB Data Type');$element->editability   = 'once';$element->minLength     = 3;$element->maxLength     = 15;$element->orderId       = 800;$element->bsDataType    = 'text';$element->bsDataInfo    = 3;$element->must          = FALSE;$element->trim          = 'both';$container->addElement($element);unset($element);unset($container);$container =& new Bs_FormContainer();$container->name         = "representation";$container->caption      = array('en'=>'Representation');$container->orderId      = 700;$this->_form->elementContainer->addElement($container);$element =& new Bs_FormFieldTextarea();$element->name          = 'explodeEval';$element->caption       = array('en'=>'Explode');$element->editability   = 'always';$element->minLength     = 0;$element->maxLength     = 65535;$element->orderId       = 1000;$element->bsDataType    = 'blob';$container->addElement($element);unset($element);$element =& new Bs_FormFieldSelect();$element->name          = 'direction';$element->FormID        = $FormID;$element->caption       = array('en'=>'Direction');$element->optionsHard   = array('en'=>array(''=>'', 'ltr'=>'ltr (left to right)', 'rtl'=>'rtl (right to left)'));$element->editability   = 'always';$element->orderId       = 900;$element->bsDataType    = 'text';$element->bsDataInfo    = 3;$element->must          = FALSE;$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'styles';$element->caption       = array('en'=>'Styles');$element->editability   = 'always';$element->minLength     = 0;$element->maxLength     = 255;$element->orderId       = 800;$element->bsDataType    = 'string';$element->must          = FALSE;$element->trim          = 'both';$element->setExplode("return array('class'=>'Class', 
'id'   =>'ID', 
'style'   =>'Style', 
'tabIndex'   =>'tabIndex'
);");$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'events';$element->caption       = array('en'=>'Events');$element->editability   = 'always';$element->minLength     = 0;$element->maxLength     = 255;$element->orderId       = 700;$element->bsDataType    = 'blob';$element->must          = FALSE;$element->trim          = 'both';$element->setExplode("return array(
'onFocus'    =>'onFocus', 
'onBlur'     =>'onBlur', 
'onSelect'   =>'onSelect', 
'onChange'   =>'onChange', 
'onClick'    =>'onClick', 
'onDblClick' =>'onDblClick', 
'onMouseDown'=>'onMouseDown', 
'onMouseUp'  =>'onMouseUp', 
'onMouseOver'=>'onMouseOver', 
'onMouseMove'=>'onMouseMove', 
'onMouseOut' =>'onMouseOut', 
'onKeyPress' =>'onKeyPress', 
'onKeyDown'  =>'onKeyDown', 
'onKeyUp'    =>'onKeyUp');");$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'additionalTags';$element->caption       = array('en'=>'Additional Tags');$element->editability   = 'always';$element->minLength     = 0;$element->maxLength     = 255;$element->orderId       = 600;$element->bsDataType    = 'text';$element->bsDataInfo    = 1;$element->must          = FALSE;$element->trim          = 'both';$container->addElement($element);unset($element);unset($container);$container =& new Bs_FormContainer();$container->name         = "validating";$container->caption      = array('en'=>'Validating');$container->orderId      = 600;$this->_form->elementContainer->addElement($container);$element =& new Bs_FormFieldSelect();$element->name          = 'bsDataType';$element->caption       = array('en'=>'BS Data Type');$element->optionsHard   = array(''=>'', 
'number'=>'number', 
'boolean'=>'boolean', 
'text'=>'text', 
'blob'=>'blob', 
'html'=>'html', 
'email'=>'email', 
'url'=>'url', 
'username'=>'username', 
'password'=>'password', 
'zipcode'=>'zipcode', 
'price'=>'price', 
'creditcard'=>'creditcard', 
'ip'=>'ip', 
'domain'=>'domain', 
'date'=>'date', 
'time'=>'time', 
'month'=>'month', 
'year'=>'year', 
'datetime'=>'datetime', 
'timestamp'=>'timestamp'
);$element->editability   = 'always';$element->orderId       = 1000;$element->bsDataType    = 'text';$element->bsDataInfo    = 3;$element->must          = TRUE;$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'bsDataInfo';$element->caption       = array('en'=>'BS Data Info');$element->editability   = 'always';$element->minLength     = 0;$element->maxLength     = 20;$element->orderId       = 900;$element->bsDataType    = 'text';$element->bsDataInfo    = 1;$element->must          = FALSE;$element->trim          = 'both';$container->addElement($element);unset($element);$element =& new Bs_FormFieldCheckbox();$element->name          = 'enforce';$element->caption       = array('en'=>'Enforce');$element->editability   = 'always';$element->orderId       = 800;$element->bsDataType    = 'boolean';$element->valueDefault  = FALSE;$element->text          = 'may enforce this rule check';$element->setExplode("return array( 'must'              =>'must', 
'mustIf'            =>'mustIf', 
'mustOneOf'         =>'mustOneOf', 
'onlyOneOf'         =>'onlyOneOf', 
'onlyIf'            =>'onlyIf', 
'minLength'         =>'minLength', 
'maxLength'         =>'maxLength', 
'mustStartWith'     =>'mustStartWith', 
'notStartWith'      =>'notStartWith', 
'mustEndWith'       =>'mustEndWith', 
'notEndWith'        =>'notEndWith', 
'mustContain'       =>'mustContain', 
'notContain'        =>'notContain', 
'equalTo'           =>'equalTo', 
'notEqualTo'        =>'notEqualTo', 
'mustBeUnique'      =>'mustBeUnique', 
'regularExpression' =>'regularExpression', 
'additionalCheck'   =>'additionalCheck'
);");$container->addElement($element);unset($element);$element =& new Bs_FormFieldCheckbox();$element->name          = 'must';$element->caption       = array('en'=>'Must');$element->editability   = 'always';$element->orderId       = 700;$element->bsDataType    = 'boolean';$element->valueDefault  = FALSE;$element->text          = 'This field has to be filled out.';$container->addElement($element);unset($element);$element =& new Bs_FormFieldTextarea();$element->name             = 'mustIf';$element->caption          = array('en'=>'Must If');$element->editability      = 'always';$element->minLength        = 0;$element->maxLength        = 65535;$element->orderId          = 680;$element->bsDataType       = 'blob';$codePostReceive = "#code codePostReceive\n
if ((isSet(\$this->valueInternal)) && (!empty(\$this->valueInternal))) {\n
\$t = eval(\$this->valueInternal);\n
if (is_array(\$t)) {\n
\$this->valueInternal = &\$t;\n
return TRUE;\n
} else {\n
return FALSE;\n
}\n
}\n
return TRUE;\n
";$codePostLoad = "#code codePostLoad\n
#var_dump(\$this->valueDefault);\n
if (is_array(\$this->valueDefault)) {\n
\$this->valueDefault  = \$GLOBALS['Bs_Array']->arrayToCode(\$this->valueDefault, '\$array');\n
\$this->valueDefault .= 'return \$array;';\n
} else {\n
#\$this->valueDefault = '';\n
}\n
";$element->codePostReceive = $codePostReceive;$element->codePostLoad    = $codePostLoad;$container->addElement($element);unset($element);$element =& new Bs_FormFieldTextarea();$element->name             = 'mustOneOf';$element->caption          = array('en'=>'Must One Of');$element->editability      = 'always';$element->minLength        = 0;$element->maxLength        = 65535;$element->orderId          = 675;$element->bsDataType       = 'blob';$codePostReceive = "#code codePostReceive\n
if ((isSet(\$this->valueInternal)) && (!empty(\$this->valueInternal))) {\n
\$t = eval(\$this->valueInternal);\n
if (is_array(\$t)) {\n
\$this->valueInternal = &\$t;\n
return TRUE;\n
} else {\n
return FALSE;\n
}\n
}\n
return TRUE;\n
";$codePostLoad = "#code codePostLoad\n
#var_dump(\$this->valueDefault);\n
if (is_array(\$this->valueDefault)) {\n
\$this->valueDefault  = \$GLOBALS['Bs_Array']->arrayToCode(\$this->valueDefault, '\$array');\n
\$this->valueDefault .= 'return \$array;';\n
} else {\n
#\$this->valueDefault = '';\n
}\n
";$element->codePostReceive = $codePostReceive;$element->codePostLoad    = $codePostLoad;$container->addElement($element);unset($element);$element =& new Bs_FormFieldTextarea();$element->name             = 'onlyOneOf';$element->caption          = array('en'=>'Only One Of');$element->editability      = 'always';$element->minLength        = 0;$element->maxLength        = 65535;$element->orderId          = 670;$element->bsDataType       = 'blob';$codePostReceive = "#code codePostReceive\n
if ((isSet(\$this->valueInternal)) && (!empty(\$this->valueInternal))) {\n
\$t = eval(\$this->valueInternal);\n
if (is_array(\$t)) {\n
\$this->valueInternal = &\$t;\n
return TRUE;\n
} else {\n
return FALSE;\n
}\n
}\n
return TRUE;\n
";$codePostLoad = "#code codePostLoad\n
#var_dump(\$this->valueDefault);\n
if (is_array(\$this->valueDefault)) {\n
\$this->valueDefault  = \$GLOBALS['Bs_Array']->arrayToCode(\$this->valueDefault, '\$array');\n
\$this->valueDefault .= 'return \$array;';\n
} else {\n
#\$this->valueDefault = '';\n
}\n
";$element->codePostReceive = $codePostReceive;$element->codePostLoad    = $codePostLoad;$container->addElement($element);unset($element);$element =& new Bs_FormFieldTextarea();$element->name             = 'onlyIf';$element->caption          = array('en'=>'Only If');$element->editability      = 'always';$element->minLength        = 0;$element->maxLength        = 65535;$element->orderId          = 660;$element->bsDataType       = 'blob';$codePostReceive = "#code codePostReceive\n
if ((isSet(\$this->valueInternal)) && (!empty(\$this->valueInternal))) {\n
\$t = eval(\$this->valueInternal);\n
if (is_array(\$t)) {\n
\$this->valueInternal = &\$t;\n
return TRUE;\n
} else {\n
return FALSE;\n
}\n
}\n
return TRUE;\n
";$codePostLoad = "#code codePostLoad\n
#var_dump(\$this->valueDefault);\n
if (is_array(\$this->valueDefault)) {\n
\$this->valueDefault  = \$GLOBALS['Bs_Array']->arrayToCode(\$this->valueDefault, '\$array');\n
\$this->valueDefault .= 'return \$array;';\n
} else {\n
#\$this->valueDefault = '';\n
}\n
";$element->codePostReceive = $codePostReceive;$element->codePostLoad    = $codePostLoad;$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'minLength';$element->caption       = array('en'=>'Min Length');$element->editability   = 'always';$element->minLength     = 0;$element->maxLength     = 8;$element->orderId       = 600;$element->bsDataType    = 'number';$element->bsDataInfo    = '0|65535';$element->must          = TRUE;$element->trim          = 'both';$element->valueDefault  = '0';$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'maxLength';$element->caption       = array('en'=>'Max Length');$element->editability   = 'always';$element->minLength     = 0;$element->maxLength     = 8;$element->orderId       = 500;$element->bsDataType    = 'number';$element->bsDataInfo    = '0|65535';$element->must          = FALSE;$element->trim          = 'both';$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'equalTo';$element->caption       = array('en'=>'Equal To');$element->editability   = 'always';$element->minLength     = 0;$element->maxLength     = 30;$element->orderId       = 400;$element->bsDataType    = 'text';$element->bsDataInfo    = '3';$element->must          = FALSE;$element->trim          = 'both';$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'notEqualTo';$element->caption       = array('en'=>'Not Equal To');$element->editability   = 'always';$element->minLength     = 0;$element->maxLength     = 30;$element->orderId       = 300;$element->bsDataType    = 'text';$element->bsDataInfo    = '3';$element->must          = FALSE;$element->trim          = 'both';$container->addElement($element);unset($element);$element =& new Bs_FormFieldCheckbox();$element->name          = 'mustBeUnique';$element->caption       = array('en'=>'Must Be Unique');$element->editability   = 'always';$element->orderId       = 200;$element->bsDataType    = 'boolean';$element->valueDefault  = FALSE;$element->text          = 'The input in this field has to be unique (in the db).';$container->addElement($element);unset($element);$element =& new Bs_FormFieldTextarea();$element->name             = 'regularExpression';$element->caption          = array('en'=>'Regular Expression');$element->editability      = 'always';$element->minLength        = 0;$element->maxLength        = 65535;$element->orderId          = 100;$element->bsDataType       = 'blob';$codePostReceive = "#code codePostReceive\n
if ((isSet(\$this->valueInternal)) && (!empty(\$this->valueInternal))) {\n
\$t = eval(\$this->valueInternal);\n
if (is_array(\$t)) {\n
\$this->valueInternal = &\$t;\n
return TRUE;\n
} else {\n
return FALSE;\n
}\n
}\n
return TRUE;\n
";$codePostLoad = "#code codePostLoad\n
#var_dump(\$this->valueDefault);\n
if (is_array(\$this->valueDefault)) {\n
\$this->valueDefault  = \$GLOBALS['Bs_Array']->arrayToCode(\$this->valueDefault, '\$array');\n
\$this->valueDefault .= 'return \$array;';\n
} else {\n
#\$this->valueDefault = '';\n
}\n
";$element->codePostReceive = $codePostReceive;$element->codePostLoad    = $codePostLoad;$container->addElement($element);unset($element);$element =& new Bs_FormFieldTextarea();$element->name             = 'additionalCheck';$element->caption          = array('en'=>'Additional Check');$element->editability      = 'always';$element->minLength        = 0;$element->maxLength        = 65535;$element->orderId          = 50;$element->bsDataType       = 'blob';$container->addElement($element);unset($element);unset($container);$container =& new Bs_FormContainer();$container->name         = "valuemodification";$container->caption      = array('en'=>'Value Modification');$container->orderId      = 500;$this->_form->elementContainer->addElement($container);$element =& new Bs_FormFieldSelect();$element->name          = 'trim';$element->caption       = array('en'=>'Trim');$element->optionsHard   = array(''     =>'', 
'none' =>'none', 
'left' =>'left', 
'right'=>'right', 
'both' =>'both'
);$element->editability   = 'always';$element->orderId       = 1000;$element->bsDataType    = 'text';$element->bsDataInfo    = 3;$element->must          = FALSE;$element->valueDefault  = 'both';$container->addElement($element);unset($element);$element =& new Bs_FormFieldTextarea();$element->name             = 'remove';$element->caption          = array('en'=>'Remove');$element->editability      = 'always';$element->minLength        = 0;$element->maxLength        = 65535;$element->orderId          = 900;$element->bsDataType       = 'blob';$codePostReceive = "#code codePostReceive\n
if ((isSet(\$this->valueInternal)) && (!empty(\$this->valueInternal))) {\n
\$t = eval(\$this->valueInternal);\n
if (is_array(\$t)) {\n
\$this->valueInternal = &\$t;\n
return TRUE;\n
} else {\n
return FALSE;\n
}\n
}\n
return TRUE;\n
";$codePostLoad = "#code codePostLoad\n
#var_dump(\$this->valueDefault);\n
if (is_array(\$this->valueDefault)) {\n
\$this->valueDefault  = \$GLOBALS['Bs_Array']->arrayToCode(\$this->valueDefault, '\$array');\n
\$this->valueDefault .= 'return \$array;';\n
} else {\n
#\$this->valueDefault = '';\n
}\n
";$element->codePostReceive = $codePostReceive;$element->codePostLoad    = $codePostLoad;$container->addElement($element);unset($element);$element =& new Bs_FormFieldTextarea();$element->name             = 'removeI';$element->caption          = array('en'=>'Remove insensitiv (case)');$element->editability      = 'always';$element->minLength        = 0;$element->maxLength        = 65535;$element->orderId          = 800;$element->bsDataType       = 'blob';$codePostReceive = "#code codePostReceive\n
if ((isSet(\$this->valueInternal)) && (!empty(\$this->valueInternal))) {\n
\$t = eval(\$this->valueInternal);\n
if (is_array(\$t)) {\n
\$this->valueInternal = &\$t;\n
return TRUE;\n
} else {\n
return FALSE;\n
}\n
}\n
return TRUE;\n
";$codePostLoad = "#code codePostLoad\n
#var_dump(\$this->valueDefault);\n
if (is_array(\$this->valueDefault)) {\n
\$this->valueDefault  = \$GLOBALS['Bs_Array']->arrayToCode(\$this->valueDefault, '\$array');\n
\$this->valueDefault .= 'return \$array;';\n
} else {\n
#\$this->valueDefault = '';\n
}\n
";$element->codePostReceive = $codePostReceive;$element->codePostLoad    = $codePostLoad;$container->addElement($element);unset($element);$element =& new Bs_FormFieldTextarea();$element->name             = 'replace';$element->caption          = array('en'=>'Replace');$element->editability      = 'always';$element->minLength        = 0;$element->maxLength        = 65535;$element->orderId          = 700;$element->bsDataType       = 'blob';$codePostReceive = "#code codePostReceive\n
if ((isSet(\$this->valueInternal)) && (!empty(\$this->valueInternal))) {\n
\$t = eval(\$this->valueInternal);\n
if (is_array(\$t)) {\n
\$this->valueInternal = &\$t;\n
return TRUE;\n
} else {\n
return FALSE;\n
}\n
}\n
return TRUE;\n
";$codePostLoad = "#code codePostLoad\n
#var_dump(\$this->valueDefault);\n
if (is_array(\$this->valueDefault)) {\n
\$this->valueDefault  = \$GLOBALS['Bs_Array']->arrayToCode(\$this->valueDefault, '\$array');\n
\$this->valueDefault .= 'return \$array;';\n
} else {\n
#\$this->valueDefault = '';\n
}\n
";$element->codePostReceive = $codePostReceive;$element->codePostLoad    = $codePostLoad;$container->addElement($element);unset($element);$element =& new Bs_FormFieldTextarea();$element->name             = 'replaceI';$element->caption          = array('en'=>'Replace insensitiv (case)');$element->editability      = 'always';$element->minLength        = 0;$element->maxLength        = 65535;$element->orderId          = 600;$element->bsDataType       = 'blob';$codePostReceive = "#code codePostReceive\n
if ((isSet(\$this->valueInternal)) && (!empty(\$this->valueInternal))) {\n
\$t = eval(\$this->valueInternal);\n
if (is_array(\$t)) {\n
\$this->valueInternal = &\$t;\n
return TRUE;\n
} else {\n
return FALSE;\n
}\n
}\n
return TRUE;\n
";$codePostLoad = "#code codePostLoad\n
#var_dump(\$this->valueDefault);\n
if (is_array(\$this->valueDefault)) {\n
\$this->valueDefault  = \$GLOBALS['Bs_Array']->arrayToCode(\$this->valueDefault, '\$array');\n
\$this->valueDefault .= 'return \$array;';\n
} else {\n
#\$this->valueDefault = '';\n
}\n
";$element->codePostReceive = $codePostReceive;$element->codePostLoad    = $codePostLoad;$container->addElement($element);unset($element);$element =& new Bs_FormFieldSelect();$element->name          = 'case';$element->caption       = array('en'=>'Case');$element->optionsHard   = array(''     =>'', 
'lower' =>'lower (make a string all lowercase)', 
'upper' =>'upper (make a string all uppercase)', 
'ucwords'=>'ucwords (make the first character of words upper case)', 
'ucfirst' =>'ucfirst (make the first character of the string upper case)', 
'nospam1' =>'nospam1 (spam fighter soft)', 
'nospam2' =>'nospam2 (spam fighter hard)'
);$element->editability   = 'always';$element->orderId       = 500;$element->bsDataType    = 'text';$element->bsDataInfo    = 3;$element->must          = FALSE;$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'bsDataManipulation';$element->caption       = array('en'=>'BS Data Manipulation');$element->editability   = 'always';$element->minLength     = 0;$element->maxLength     = 20;$element->orderId       = 400;$element->bsDataType    = 'text';$element->bsDataInfo    = 1;$element->must          = FALSE;$element->trim          = 'both';$container->addElement($element);unset($element);$element =& new Bs_FormFieldTextarea();$element->name             = 'bsDataManipVar';$element->caption          = array('en'=>'Bs Data Manip Var');$element->editability      = 'always';$element->minLength        = 0;$element->maxLength        = 65535;$element->orderId          = 300;$element->bsDataType       = 'blob';$codePostReceive = "#code codePostReceive\n
if ((isSet(\$this->valueInternal)) && (!empty(\$this->valueInternal))) {\n
\$t = eval(\$this->valueInternal);\n
if (is_array(\$t)) {\n
\$this->valueInternal = &\$t;\n
return TRUE;\n
} else {\n
return FALSE;\n
}\n
}\n
return TRUE;\n
";$codePostLoad = "#code codePostLoad\n
#var_dump(\$this->valueDefault);\n
if (is_array(\$this->valueDefault)) {\n
\$this->valueDefault  = \$GLOBALS['Bs_Array']->arrayToCode(\$this->valueDefault, '\$array');\n
\$this->valueDefault .= 'return \$array;';\n
} else {\n
#\$this->valueDefault = '';\n
}\n
";$element->codePostReceive = $codePostReceive;$element->codePostLoad    = $codePostLoad;$container->addElement($element);unset($element);unset($container);$container =& new Bs_FormContainer();$container->name         = "onlyforthisfield";$container->caption      = array('en'=>'Only for this field type');$container->orderId      = 400;$this->_form->elementContainer->addElement($container);switch ($fieldType) {case 'text':
case 'password':
case 'file':
$element =& new Bs_FormFieldText();$element->name          = 'size';$element->caption       = array('en'=>'Size');$element->editability   = 'always';$element->minLength     = 1;$element->maxLength     = 8;$element->orderId       = 1000;$element->bsDataType    = 'number';$element->bsDataInfo    = '1|200';$element->must          = FALSE;$element->trim          = 'both';$element->valueDefault  = 30;$container->addElement($element);unset($element);break;case 'checkbox':
$element =& new Bs_FormFieldText();$element->name          = 'text';$element->caption       = array('en'=>'Text');$element->editability   = 'always';$element->minLength     = 1;$element->maxLength     = 255;$element->orderId       = 1000;$element->bsDataType    = 'text';$element->bsDataInfo    = '1';$element->must          = TRUE;$element->trim          = 'both';$element->setExplode("global \$APP; return \$APP['usedLanguages'];");$container->addElement($element);unset($element);break;case 'select':
$element =& new Bs_FormFieldCheckbox();$element->name          = 'multiple';$element->caption       = array('en'=>'Multiple');$element->editability   = 'always';$element->orderId       = 1000;$element->bsDataType    = 'boolean';$element->valueDefault  = FALSE;$element->text          = 'Allow multiple selections.';$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'size';$element->caption       = array('en'=>'Size');$element->editability   = 'always';$element->minLength     = 1;$element->maxLength     = 3;$element->orderId       = 900;$element->bsDataType    = 'number';$element->bsDataInfo    = '1|100';$element->mustIf        = array(array('field'=>'multiple'));$element->trim          = 'both';$container->addElement($element);unset($element);$element =& new Bs_FormFieldSelect();$element->name          = 'optionsType';$element->caption       = array('en'=>'Options Type');$element->optionsHard   = array(''    =>'', 
'eval'=>'eval', 
'hard'=>'hard'
);$element->editability   = 'always';$element->orderId       = 800;$element->bsDataType    = 'text';$element->bsDataInfo    = 3;$element->must          = FALSE;$container->addElement($element);unset($element);$element =& new Bs_FormFieldTextarea();$element->name          = 'optionsEval';$element->caption       = array('en'=>'Options');$element->editability   = 'always';$element->minLength     = 10;$element->maxLength     = 65535;$element->orderId       = 700;$element->bsDataType    = 'blob';$element->setExplode("global \$APP; return \$APP['usedLanguages'];");$container->addElement($element);unset($element);break;case 'radio':
$element =& new Bs_FormFieldSelect();$element->name          = 'optionsType';$element->caption       = array('en'=>'Options Type');$element->optionsHard   = array(''    =>'', 
'eval'=>'eval', 
'hard'=>'hard'
);$element->editability   = 'always';$element->orderId       = 800;$element->bsDataType    = 'text';$element->bsDataInfo    = 3;$element->must          = FALSE;$container->addElement($element);unset($element);$element =& new Bs_FormFieldTextarea();$element->name          = 'optionsEval';$element->caption       = array('en'=>'Options');$element->editability   = 'always';$element->minLength     = 10;$element->maxLength     = 65535;$element->orderId       = 700;$element->bsDataType    = 'blob';$element->setExplode("global \$APP; return \$APP['usedLanguages'];");$container->addElement($element);unset($element);break;case 'textarea':
$element =& new Bs_FormFieldText();$element->name          = 'cols';$element->caption       = array('en'=>'Cols');$element->editability   = 'always';$element->minLength     = 1;$element->maxLength     = 4;$element->orderId       = 1000;$element->bsDataType    = 'number';$element->bsDataInfo    = '1|1000';$element->trim          = 'both';$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'rows';$element->caption       = array('en'=>'Rows');$element->editability   = 'always';$element->minLength     = 1;$element->maxLength     = 4;$element->orderId       = 900;$element->bsDataType    = 'number';$element->bsDataInfo    = '1|1000';$element->trim          = 'both';$container->addElement($element);unset($element);$element =& new Bs_FormFieldSelect();$element->name          = 'wrap';$element->caption       = array('en'=>'Wrap');$element->optionsHard   = array(''     =>'', 
'off' =>'off', 
'soft' =>'soft', 
'hard' =>'hard'
);$element->editability   = 'always';$element->orderId       = 800;$element->bsDataType    = 'text';$element->bsDataInfo    = 3;$element->must          = FALSE;$container->addElement($element);unset($element);break;case 'image':
$element =& new Bs_FormFieldText();$element->name          = 'src';$element->caption       = array('en'=>'Source');$element->editability   = 'always';$element->minLength     = 4;$element->maxLength     = 255;$element->orderId       = 1000;$element->bsDataType    = 'url';$element->bsDataInfo    = '3';$element->trim          = 'both';$element->setExplode("global \$APP; return \$APP['usedLanguages'];");$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'alt';$element->caption       = array('en'=>'Alt Text');$element->editability   = 'always';$element->minLength     = 1;$element->maxLength     = 255;$element->orderId       = 900;$element->bsDataType    = 'text';$element->bsDataInfo    = '1';$element->trim          = 'both';$element->setExplode("global \$APP; return \$APP['usedLanguages'];");$container->addElement($element);unset($element);$element =& new Bs_FormFieldSelect();$element->name          = 'align';$element->caption       = array('en'=>'Align');$element->optionsHard   = array(''          =>'', 
'left'      =>'left', 
'right'     =>'right', 
'top'       =>'top', 
'middle'    =>'middle', 
'bottom'    =>'bottom', 
'texttop'   =>'texttop', 
'absmiddle' =>'absmiddle', 
'baseline'  =>'baseline', 
'absbottom' =>'absbottom'
);$element->editability   = 'always';$element->orderId       = 800;$element->bsDataType    = 'text';$element->bsDataInfo    = 3;$element->must          = FALSE;$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'usemap';$element->caption       = array('en'=>'Clientside Image Map');$element->editability   = 'always';$element->minLength     = 1;$element->maxLength     = 255;$element->orderId       = 700;$element->bsDataType    = 'text';$element->bsDataInfo    = '1';$element->trim          = 'both';$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'width';$element->caption       = array('en'=>'Width');$element->editability   = 'always';$element->minLength     = 1;$element->maxLength     = 4;$element->orderId       = 600;$element->bsDataType    = 'number';$element->bsDataInfo    = '1|2000';$element->trim          = 'both';$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'height';$element->caption       = array('en'=>'Height');$element->editability   = 'always';$element->minLength     = 1;$element->maxLength     = 4;$element->orderId       = 500;$element->bsDataType    = 'number';$element->bsDataInfo    = '1|2000';$element->trim          = 'both';$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'border';$element->caption       = array('en'=>'Border');$element->editability   = 'always';$element->minLength     = 1;$element->maxLength     = 2;$element->orderId       = 400;$element->bsDataType    = 'number';$element->bsDataInfo    = '0|99';$element->trim          = 'both';$element->valueDefault  = 0;$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'hspace';$element->caption       = array('en'=>'Horicontal Space (hspace)');$element->editability   = 'always';$element->minLength     = 1;$element->maxLength     = 4;$element->orderId       = 300;$element->bsDataType    = 'number';$element->bsDataInfo    = '0|1000';$element->trim          = 'both';$element->valueDefault  = 0;$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'vspace';$element->caption       = array('en'=>'Vertical Space (vspace)');$element->editability   = 'always';$element->minLength     = 1;$element->maxLength     = 4;$element->orderId       = 200;$element->bsDataType    = 'number';$element->bsDataInfo    = '0|1000';$element->trim          = 'both';$element->valueDefault  = 0;$container->addElement($element);unset($element);break;case 'button':
$element =& new Bs_FormFieldTextarea();$element->name          = 'htmlContent';$element->caption       = array('en'=>'Html Content');$element->editability   = 'always';$element->minLength     = 0;$element->maxLength     = 65535;$element->orderId       = 1000;$element->bsDataType    = 'html';$element->bsDataInfo    = FALSE;$element->setExplode("global \$APP; return \$APP['usedLanguages'];");$container->addElement($element);unset($element);$element =& new Bs_FormFieldSelect();$element->name          = 'type';$element->caption       = array('en'=>'Type');$element->optionsHard   = array(''        =>'', 
'submit'  =>'submit', 
'reset'   =>'reset', 
'button'  =>'button (default)'
);$element->editability   = 'always';$element->orderId       = 900;$element->bsDataType    = 'text';$element->bsDataInfo    = 3;$element->must          = FALSE;$container->addElement($element);unset($element);break;}
} if ($elementType != 'field') {unset($container);$container =& new Bs_FormContainer();$container->name         = "onlyforthiselement";$container->caption      = array('en'=>'Only for this element type');$container->orderId      = 700;$this->_form->elementContainer->addElement($container);if ($elementType == 'container') {$element =& new Bs_FormFieldText();$element->name          = 'caption';$element->caption       = array('en'=>'Caption');$element->editability   = 'always';$element->minLength     = 1;$element->maxLength     = 30;$element->orderId       = 1000;$element->bsDataType    = 'text';$element->bsDataInfo    = 1;$element->must          = FALSE;$element->trim          = 'both';$element->setExplode("global \$APP; return \$APP['usedLanguages'];");$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'useCheckboxAsCaption';$element->caption       = array('en'=>'Use Checkbox as Caption');$element->editability   = 'always';$element->minLength     = 1;$element->maxLength     = 30;$element->orderId       = 900;$element->bsDataType    = 'text';$element->bsDataInfo    = 3;$element->must          = FALSE;$element->trim          = 'both';$container->addElement($element);unset($element);$element =& new Bs_FormFieldCheckbox();$element->name          = 'pseudoContainer';$element->caption       = array('en'=>'Pseudo Container');$element->editability   = 'always';$element->orderId       = 800;$element->bsDataType    = 'boolean';$element->valueDefault  = FALSE;$element->text          = 'Use as pseudo container.';$container->addElement($element);unset($element);} elseif ($elementType == 'image') {$element =& new Bs_FormFieldText();$element->name          = 'src';$element->caption       = array('en'=>'Source');$element->editability   = 'always';$element->minLength     = 4;$element->maxLength     = 255;$element->orderId       = 1000;$element->bsDataType    = 'url';$element->bsDataInfo    = '3';$element->trim          = 'both';$element->setExplode("global \$APP; return \$APP['usedLanguages'];");$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'alt';$element->caption       = array('en'=>'Alt Text');$element->editability   = 'always';$element->minLength     = 1;$element->maxLength     = 255;$element->orderId       = 900;$element->bsDataType    = 'text';$element->bsDataInfo    = '1';$element->trim          = 'both';$element->setExplode("global \$APP; return \$APP['usedLanguages'];");$container->addElement($element);unset($element);$element =& new Bs_FormFieldSelect();$element->name          = 'align';$element->caption       = array('en'=>'Align');$element->optionsHard   = array(''          =>'', 
'left'      =>'left', 
'right'     =>'right', 
'top'       =>'top', 
'middle'    =>'middle', 
'bottom'    =>'bottom', 
'texttop'   =>'texttop', 
'absmiddle' =>'absmiddle', 
'baseline'  =>'baseline'
);$element->editability   = 'always';$element->orderId       = 800;$element->bsDataType    = 'text';$element->bsDataInfo    = 3;$element->must          = FALSE;$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'border';$element->caption       = array('en'=>'Border');$element->editability   = 'always';$element->minLength     = 1;$element->maxLength     = 2;$element->orderId       = 700;$element->bsDataType    = 'number';$element->bsDataInfo    = '0|99';$element->trim          = 'both';$element->valueDefault  = 0;$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'width';$element->caption       = array('en'=>'Width');$element->editability   = 'always';$element->minLength     = 1;$element->maxLength     = 4;$element->orderId       = 600;$element->bsDataType    = 'number';$element->bsDataInfo    = '1|2000';$element->trim          = 'both';$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'height';$element->caption       = array('en'=>'Height');$element->editability   = 'always';$element->minLength     = 1;$element->maxLength     = 4;$element->orderId       = 500;$element->bsDataType    = 'number';$element->bsDataInfo    = '1|2000';$element->trim          = 'both';$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'hspace';$element->caption       = array('en'=>'Horicontal Space (hspace)');$element->editability   = 'always';$element->minLength     = 1;$element->maxLength     = 4;$element->orderId       = 400;$element->bsDataType    = 'number';$element->bsDataInfo    = '0|1000';$element->trim          = 'both';$element->valueDefault  = 0;$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'vspace';$element->caption       = array('en'=>'Vertical Space (vspace)');$element->editability   = 'always';$element->minLength     = 1;$element->maxLength     = 4;$element->orderId       = 300;$element->bsDataType    = 'number';$element->bsDataInfo    = '0|1000';$element->trim          = 'both';$element->valueDefault  = 0;$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'usemap';$element->caption       = array('en'=>'Clientside Image Map');$element->editability   = 'always';$element->minLength     = 1;$element->maxLength     = 255;$element->orderId       = 200;$element->bsDataType    = 'text';$element->bsDataInfo    = '1';$element->trim          = 'both';$element->mustStartWith = '#';$container->addElement($element);unset($element);$element =& new Bs_FormFieldCheckbox();$element->name          = 'ismap';$element->caption       = array('en'=>'ismap');$element->editability   = 'always';$element->orderId       = 100;$element->bsDataType    = 'boolean';$element->valueDefault  = FALSE;$element->text          = 'ismap. well. dunno.';$container->addElement($element);unset($element);} elseif ($elementType == 'line') {$element =& new Bs_FormFieldSelect();$element->name          = 'align';$element->caption       = array('en'=>'Align');$element->optionsHard   = array(''              =>'', 
'left'      =>'left', 
'top'       =>'center', 
'right'     =>'right'
);$element->editability   = 'always';$element->orderId       = 1000;$element->bsDataType    = 'text';$element->bsDataInfo    = 3;$element->must          = FALSE;$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'width';$element->caption       = array('en'=>'Width');$element->editability   = 'always';$element->minLength     = 1;$element->maxLength     = 4;$element->orderId       = 900;$element->bsDataType    = 'number';$element->bsDataInfo    = '1|2000';$element->trim          = 'both';$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'size';$element->caption       = array('en'=>'Size (Height)');$element->editability   = 'always';$element->minLength     = 1;$element->maxLength     = 2;$element->orderId       = 800;$element->bsDataType    = 'number';$element->bsDataInfo    = '1|99';$element->trim          = 'both';$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name          = 'color';$element->caption       = array('en'=>'Color');$element->editability   = 'always';$element->minLength     = 1;$element->maxLength     = 20;$element->orderId       = 700;$element->bsDataType    = 'text';$element->bsDataInfo    = '1';$element->trim          = 'both';$container->addElement($element);unset($element);$element =& new Bs_FormFieldCheckbox();$element->name          = 'noshade';$element->caption       = array('en'=>'noshade');$element->editability   = 'always';$element->orderId       = 600;$element->bsDataType    = 'boolean';$element->valueDefault  = FALSE;$element->text          = 'noshade. well. dunno.';$container->addElement($element);unset($element);} elseif ($elementType == 'text') {$element =& new Bs_FormFieldTextarea();$element->name          = 'text';$element->caption       = array('en'=>'Text');$element->editability   = 'always';$element->minLength     = 1;$element->maxLength     = 65535;$element->orderId       = 1000;$element->bsDataType    = 'blob';$element->setExplode("global \$APP; return \$APP['usedLanguages'];");$container->addElement($element);unset($element);} elseif ($elementType == 'html') {$element =& new Bs_FormFieldTextarea();$element->name          = 'html';$element->caption       = array('en'=>'Html');$element->editability   = 'always';$element->minLength     = 1;$element->maxLength     = 65535;$element->orderId       = 1000;$element->bsDataType    = 'html';$element->bsDataInfo    = TRUE;$element->setExplode("global \$APP; return \$APP['usedLanguages'];");$container->addElement($element);unset($element);} elseif ($elementType == 'code') {$element =& new Bs_FormFieldTextarea();$element->name          = 'code';$element->caption       = array('en'=>'Code');$element->editability   = 'always';$element->minLength     = 1;$element->maxLength     = 65535;$element->orderId       = 1000;$element->bsDataType    = 'blob';$element->wrap          = 'off';$element->setExplode("global \$APP; return \$APP['usedLanguages'];");$container->addElement($element);unset($element);}
}
unset($container);$container =& new Bs_FormContainer();$container->name         = "buttons";$container->caption      = array('en'=>'Functions');$container->orderId      = 100;$this->_form->elementContainer->addElement($container);$element =& new Bs_FormFieldSubmit();$element->name         = "submit";$element->editability  = 'always';$element->caption      = 'Save';$container->addElement($element);unset($element);$element =& new Bs_FormFieldButton();$element->name         = "cancel";$element->FormID       = $FormID;$element->editability  = 'always';$element->caption      = 'Cancel';$element->events['onClick'] = "javascript:window.location.href = '{$_SERVER['PHP_SELF']}?todo=formOverview&bs_form[recordId]={$GLOBALS['bs_form']['formRecordId']}';";$container->addElement($element);unset($element);}
}
?>
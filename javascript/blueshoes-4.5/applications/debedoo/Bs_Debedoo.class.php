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
define('BS_DEBEDOO_VERSION',      '4.5.$Revision: 1.6 $');require_once($_SERVER['DOCUMENT_ROOT']      . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'db/Bs_DbWrapper.class.php');require_once($APP['path']['core'] . 'storage/objectpersister/Bs_ObjPersisterForMySql.class.php');require_once($APP['path']['core'] . 'html/form/Bs_Form.class.php');require_once($APP['path']['core'] . 'html/table/Bs_HtmlTable.class.php');require_once($APP['path']['core'] . 'html/table/Bs_HtmlTableWindrose.class.php');require_once($APP['path']['core'] . 'text/Bs_TextUtil.class.php');require_once($APP['path']['core'] . 'text/Bs_LanguageHandler.class.php');require_once($APP['path']['core'] . 'util/Bs_Array.class.php');function _bs_debedoo_mycmp($a, $b) {global $_bs_debedoo_mycmp_keyfield;if ($a[$_bs_debedoo_mycmp_keyfield] == $b[$_bs_debedoo_mycmp_keyfield]) return 0;return ($a[$_bs_debedoo_mycmp_keyfield] < $b[$_bs_debedoo_mycmp_keyfield]) ? -1 : 1;}
class Bs_Debedoo extends Bs_Object {var $persister = NULL;var $persisterID;var $persisterVarSettings = array('internalName'         => array('mode'=>'lonely',      'metaType'=>'string',      'index'=>TRUE), 
'formName'             => array('mode'=>'lonely',      'metaType'=>'string',      'index'=>TRUE), 
'_dbDsn'               => array('mode'=>'stream',      'crypt'=>TRUE), 
'dbName'               => array('mode'=>'lonely',      'metaType'=>'string',      'index'=>TRUE), 
'dbTableName'          => array('mode'=>'lonely',      'metaType'=>'string',      'index'=>TRUE), 
'searchSettings'       => array('mode'=>'stream'), 
'overviewSettings'     => array('mode'=>'stream'), 
'addHeadString'        => array('mode'=>'stream'), 
);var $_bsForm;var $_bsDb;var $_bsDbWrapper;var $Bs_TextUtil;var $Bs_Array;var $internalName;var $formName;var $_dbDsn;var $dbName;var $dbTableName;var $searchSettings;var $overviewSettings;var $useOverviewProfile;var $addHeadString;var $overviewWindroseStyles;var $useLongID = FALSE;var $userGroup;var $_getVars;var $_postVars;var $selfDocument;var $_fieldStruct;var $_language = 'en';var $guiStrings;var $_numRecords;var $_sqlFrom;var $_sqlWhere;var $_searchTerm;var $_clickOffset = 0;function Bs_Debedoo() {parent::Bs_Object(); $this->_getVars  = $_GET;$this->_postVars = $_POST;if (isSet($GLOBALS['bsDb'])) $this->_bsDb = &$GLOBALS['bsDb'];$this->Bs_TextUtil  = &$GLOBALS['Bs_TextUtil'];$this->Bs_Array     = &$GLOBALS['Bs_Array'];$this->selfDocument = $_SERVER['PHP_SELF'];$this->persister =& new Bs_ObjPersisterForMySql($this, &$this->persisterVarSettings); $this->persister->setDbTableName('BsDebedooProfiles');}
function setDbByDsn($dsn) {$this->_dbDsn = $dsn;$bsDb = &getDbObject($this->_dbDsn);if (isEx($bsDb)) return $bsDb; $this->_bsDb = &$bsDb;if (isSet($this->_dbDsn['name'])) $this->_bsDb->selectDb($this->_dbDsn['name']);return TRUE;}
function setGetVars($getVars) {$this->_getVars = &$getVars;}
function setPostVars($postVars) {$this->_postVars = &$postVars;}
function setLanguage($lang='en') {do {$Bs_LanguageHandler =& new Bs_LanguageHandler();$t = &$Bs_LanguageHandler->determineLanguage($GLOBALS['APP']['path']['applications'] . 'debedoo/lang/debedooGui', $lang);if (is_null($t)) break; list($lang, $path) = $t;$this->guiStrings = &$Bs_LanguageHandler->readLanguage($path);$this->_language = $lang;if (isSet($this->_form) AND is_object($this->_form)) $this->_form->language  = $this->_language;return TRUE;} while (FALSE);return FALSE;}
function doItYourself() {if (isSet($this->_getVars['bs_debedoo'])) {if (isSet($this->_getVars['bs_debedoo']['offset'])) {$this->_clickOffset = $this->_getVars['bs_debedoo']['offset'];}
}
if (isSet($this->_postVars['bs_debedoo']['searchTerm'])) {$this->_searchTerm = $this->_postVars['bs_debedoo']['searchTerm'];} elseif (isSet($this->_getVars['bs_debedoo']['searchTerm'])) {$this->_searchTerm = $this->_getVars['bs_debedoo']['searchTerm'];}
do {if (isSet($this->_postVars['bs_form'])) {if (isSet($this->_postVars['bs_form']['btnCancel'])) {break;}
if (isSet($this->_postVars['bs_form']['btnEdit'])) {return $this->_treatFormEdit();break;}
if (isSet($this->_postVars['bs_form']['btnDelete'])) {return $this->_treatFormDelete();break;}
$useGet = FALSE;$t = $this->_postVars['bs_form']['mode'];} elseif (isSet($this->_getVars['bs_form'])) {$useGet = TRUE;$t = $this->_getVars['bs_form']['mode'];}
if (!isSet($t)) break;$ret = '';$ret .= $this->_getHtmlStart();switch ($t) {case 'view':
$ret .= $this->_treatFormView($useGet);break;case 'add':
$ret .= $this->_treatFormAdd($useGet);break;case 'edit':
$ret .= $this->_treatFormEdit($useGet);break;case 'delete':
$ret .= $this->_treatFormDelete($useGet);break;case 'search':
$ret .= $this->getHomepage(FALSE);break;default:
dump($this->_postVars);return 'case not handled in doItYourself()!';}
$ret .= $this->_getHtmlEnd();return $ret;} while (FALSE);return $this->getHomepage();}
function getHomepage($withHtmlAround=TRUE) {if (!isSet($this->dbTableName)) return $this->_getUndefinedHomepage();$ret = '';if ($withHtmlAround) $ret .= $this->_getHtmlStart();$overview = $this->getOverview();if (isEx($overview)) {$overview->stackTrace('was here in getHomepage()', __FILE__, __LINE__);$ret .= $overview->stackDump('return');} else {$ret .= $overview;}
if ($withHtmlAround) $ret .= $this->_getHtmlEnd();return $ret;}
function _getUndefinedHomepage() {$ret  = $this->_getHtmlStart();$ret .= $this->_listDatabases();$ret .= $this->_getHtmlEnd();return $ret;}
function getSearchForm() {$ret = '';if (isSet($this->_searchTerm)) {$searchTerm = $this->_searchTerm;} else {$searchTerm = '';}
$ret .= "
<form action='{$this->selfDocument}?bs_debedoo[iName]={$this->internalName}' method='post'>
<input type='hidden' name='bs_form[mode]' value='search'>
<input type='text' name='bs_debedoo[searchTerm]' value=\"{$searchTerm}\">
<input type='submit' name='bs_form[btnSearch]' value='" . $this->guiStrings['default']['search'] . "'>
</form>
";return $ret;}
function getOverview() {if (!empty($this->_searchTerm)) {$this->_fieldStruct = $this->_determineFieldStructure();$this->_sqlFrom = $this->dbTableName;$sqlWhere       = '';$i=0;$foreignTableHistory = array(); foreach ($this->_fieldStruct as $k => $fieldRec) {if ($i) $sqlWhere .= 'OR ';$sqlWhere .= $this->dbTableName . '.' . $k . ' LIKE "' . $this->_searchTerm . '%" ';if (!is_array($fieldRec['foreignKey']) && is_array($this->overviewSettings[$this->useOverviewProfile]['fields'][$k]['foreignKey'])) {$fieldRec['foreignKey'] = $this->overviewSettings[$this->useOverviewProfile]['fields'][$k]['foreignKey'];}
if (is_array($fieldRec['foreignKey'])) {$fKey = $fieldRec['foreignKey'];if (!in_array($fKey['dbTableName'], $foreignTableHistory)) { $foreignTableHistory[] = $fKey['dbTableName'];$tblStruct = $this->_bsDb->getTableStructure($fKey['dbTableName']);if (isEx($tblStruct)) {$tblStruct->stackDump('echo');} else {$this->_sqlFrom .= " INNER JOIN {$fKey['dbTableName']} ON {$this->dbTableName}.{$k} = {$fKey['dbTableName']}.{$fKey['dbFieldName']} ";while (list($fieldName) = each($tblStruct)) {$sqlWhere .= " OR {$fKey['dbTableName']}.{$fieldName} LIKE \"{$this->_searchTerm}%\" ";}
}
}
}
$i++;}
reset($this->_fieldStruct);} else {$sqlWhere = '';}
$this->_sqlWhere = $sqlWhere;$result = $this->_getOverviewData();if (isEx($result)) {$result->stackTrace('was here in getOverview()', __FILE__, __LINE__);return $result;}
$this->_extendOverviewDataByEditLinks($result);$this->_cleanOverviewData($result);if ($this->_getOverviewSetting('resolveKeys')) $this->_resolveForeignKeys($result);if ($this->_getOverviewSetting('autoNumbers')) $this->_addAutoNumbers($result);$this->_addOverviewTitle($result);$ret = '';$ret .= $this->getSearchForm() . '<br>';if (!isSet($_GET['bs_debedoo']['statusCode']))  $_GET['bs_debedoo']['statusCode'] = 1;if (!isSet($_GET['bs_debedoo']['statusMsg']))  $_GET['bs_debedoo']['statusMsg'] = 'OK';if ($_GET['bs_debedoo']['statusCode'] == '1') {$ret .= "<font color='green'><b>" . $_GET['bs_debedoo']['statusMsg'] . "</b></font><br><br>";} elseif ($_GET['bs_debedoo']['statusCode'] == '0') {$ret .= "<font color='red'><b>" . $_GET['bs_debedoo']['statusMsg'] . "</b></font><br><br>";}
$ret .= "<a href=\"" . $this->buildUrl(TRUE) . "\">" . $this->guiStrings['default']['list'] . "</a> - ";$ret .= "<a href=\"" . $this->buildUrl()     . "&bs_form[mode]=add\">" . $this->guiStrings['default']['add'] . "</a><br><br>";$htmlWindroseObj =& new Bs_HtmlTableWindrose();if (isSet($this->overviewWindroseStyles)) {foreach($this->overviewWindroseStyles as $k => $style) {$htmlWindroseObj->setStyle($k, $style);}
} else {$htmlWindroseObj->setStyle('ALL', 'color:black; weight:normal; font-size:12px; font-style:normal; font-family:Verdana,Arial; border-left:thin solid #58A29E; ');$htmlWindroseObj->setStyle('ZR_0', 'background-color:white; ');$htmlWindroseObj->setStyle('ZR_1', 'background-color:#DBEFEB; ');$htmlWindroseObj->setStyle('N', 'color:white; background-color:#58A29E; font-weight:bold; ');}
$ht =& new Bs_HtmlTable();$ht->setWindroseStyle(&$htmlWindroseObj);$ht->initByMatrix($result);$ht->flipData();$ret .= $ht->renderTable();$ret .= '<br>';$ret .= $this->_getClickthroughBar();$ret .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';$ret .= $this->_getNumRecords() . ' ' . $this->guiStrings['default']['records'];$ret .= '<br>';return $ret;}
function _getNumRecords() {if (isSet($this->_numRecords)) return $this->_numRecords;$sqlQ = $this->_createOverviewSqlQuery();$ret = $this->_bsDb->getNumRecords($sqlQ);if (isEx($ret)) {$ret->stackDump('echo'); $ret = 0;}
return $this->_numRecords = $ret;}
function _createOverviewSqlQuery() {$sqlQ = "SELECT ";$sqlQ .= join(', ', $this->_getFieldsToUseInOverview());if (isSet($this->_sqlFrom)) {$sqlQ .= " FROM " . $this->_sqlFrom;} else {$sqlQ .= " FROM {$this->dbTableName}";}
if (!empty($this->_sqlWhere)) {$sqlQ .= " WHERE {$this->_sqlWhere}";}
$limit = $this->_getOverviewSetting('limit');if (($this->_clickOffset > 0) && ($limit > 0)) {$sqlQ .= " LIMIT {$this->_clickOffset},{$limit}";} elseif ($limit > 0) {$sqlQ .= " LIMIT {$limit}";} elseif ($this->_clickOffset > 0) {$sqlQ .= " LIMIT {$this->_clickOffset},0";}
return $sqlQ;}
function _getFieldsToUseInOverview() {$ret = array();do {if (!isSet($this->useOverviewProfile)) break;if (!isSet($this->overviewSettings[$this->useOverviewProfile]['fields'])) break;if (!is_array($this->overviewSettings[$this->useOverviewProfile]['fields'])) break;$t = $this->overviewSettings[$this->useOverviewProfile]['fields'];while (list($k) = each($t)) {$ret[] = $this->dbTableName . '.' . $k;}
return $ret;} while (FALSE);$numFields = 0;reset($this->_fieldStruct);while (list($k) = each($this->_fieldStruct)) {if ($this->_fieldStruct[$k]['overviewSelect']) {$ret[] = $this->dbTableName . '.' . $k;$numFields++;if ($numFields == 5) break; }
}
return $ret;}
function _getOverviewData() {if (!isSet($this->_fieldStruct)) {$fStruct = $this->_determineFieldStructure();if (isEx($fStruct)) {$fStruct->stackTrace('was here in _getOverviewData()', __FILE__, __LINE__);return $fStruct;}
$this->_fieldStruct = $fStruct;}
$sqlQ = $this->_createOverviewSqlQuery();$data = $this->_bsDb->getAssoc2($sqlQ);return $data;}
function _getOverviewSetting($key) {switch ($key) {case 'limit':
return 20;break;case 'resolveKeys':
return TRUE;break;case 'autoNumbers':
return TRUE;break;}
}
function _cleanOverviewData(&$data) {foreach($data as $k => $dev0) {if (isSet($this->_fieldStruct[$k]['overviewHide']) AND $this->_fieldStruct[$k]['overviewHide']) {unset($data[$k]);}
}
reset($data);}
function _addOverviewTitle(&$data) {foreach($data as $k => $dev0) {if (isSet($this->overviewSettings[$this->useOverviewProfile]['fields'][$k]['caption'])) {$caption = $this->Bs_TextUtil->getLanguageDependentValue($this->overviewSettings[$this->useOverviewProfile]['fields'][$k]['caption'],   $this->_language);} elseif (isSet($this->_fieldStruct[$k]['caption'])) {$caption = $this->Bs_TextUtil->getLanguageDependentValue($this->_fieldStruct[$k]['caption'],   $this->_language);} else {$caption = '&nbsp;';}
array_unshift(&$data[$k], $caption); }
reset($data);}
function _resolveForeignKeys(&$data) {if (empty($data)) return;reset($this->_fieldStruct);while (list($k) = each($this->_fieldStruct)) {if (empty($data[$k])) continue;if (!is_array($this->_fieldStruct[$k]['foreignKey']) && is_array($this->overviewSettings[$this->useOverviewProfile]['fields'][$k]['foreignKey'])) {$this->_fieldStruct[$k]['foreignKey'] = $this->overviewSettings[$this->useOverviewProfile]['fields'][$k]['foreignKey'];}
if (is_array($this->_fieldStruct[$k]['foreignKey'])) {$fKey = &$this->_fieldStruct[$k]['foreignKey'];$sqlQ = "SELECT * FROM {$fKey['dbTableName']} WHERE {$fKey['dbFieldName']} IN (" . join(',', $data[$k]) . ')' . $this->_getForeignKeyOrderBy($k);$idRecords = $this->_bsDb->getAll($sqlQ);$t = current($idRecords);$keyValueField = $this->_getForeignKeyValueField($k, $t);if (($keyValueField !== FALSE) && is_array($keyValueField)) {if (isSet($data[$k])) {while (list($fuck) = each($idRecords)) {$idRecords[$fuck] = &$this->Bs_Array->hashKeysToLower($idRecords[$fuck]);}
while (list($y,$foreignKey) = each($data[$k])) {reset($idRecords);$fKeyValue = array();while (list($tt) = each($idRecords)) {if ($idRecords[$tt][strToLower($fKey['dbFieldName'])] == $foreignKey) {$fKeyValue = array();foreach ($keyValueField as $currentKeyField) {$fKeyValue[] = $idRecords[$tt][$currentKeyField];}
}
}
$data[$k][$y] = join(' ', $fKeyValue);}
reset($data[$k]);}
}
}
}
}
function _addAutoNumbers(&$data) {if (empty($data)) return;$i = sizeOf(current($data));$data = array_merge(array('_number_' => range(1 + $this->_clickOffset, $i + $this->_clickOffset)), $data);}
function _extendOverviewDataByEditLinks(&$data) {if (empty($data)) return;$numRows = sizeOf(current($data));if (isSet($data['id'])) {$idArray = $data['id'];} else {$idArray = current($data); }
for ($i=0; $i<$numRows; $i++) {$recordId = $idArray[$i];$v[] = "<a href=\"" . $this->buildUrl() . "&bs_form[mode]=view&bs_form[recordId]={$recordId}\">" . $this->guiStrings['default']['view'] . "</a>";$e[] = "<a href=\"" . $this->buildUrl() . "&bs_form[mode]=edit&bs_form[recordId]={$recordId}\">" . $this->guiStrings['default']['edit'] . "</a>";$d[] = "<a href=\"" . $this->buildUrl() . "&bs_form[mode]=delete&bs_form[recordId]={$recordId}\">" . $this->guiStrings['default']['delete'] . "</a>";}
$data['_view_']   = $v;$data['_edit_']   = $e;$data['_delete_'] = $d;reset($data);}
function _getClickthroughBar() {$ret = '';$offset  = $this->_clickOffset;$numRecs = $this->_getNumRecords();$limit   = $this->_getOverviewSetting('limit');if ($offset > 0) {$ret .= "<a href=\"{$this->selfDocument}?bs_debedoo[iName]={$this->internalName}&bs_debedoo[searchTerm]={$this->_searchTerm}&bs_debedoo[offset]=" . ($offset - $limit) . "\">" . $this->guiStrings['default']['backward'] . "</a>&nbsp;&nbsp;";}
if ($numRecs > $limit) {if ($offset == 0) { $currentPage = 1;} else {$currentPage = (int) ceil(($offset +1) / $limit);}
$y=1;for ($i=0; $i<$numRecs; $i+=$limit) { if ($y == $currentPage) {$ret .= '<b>' . $y . '</b> ';} else {$ret .= "<a href=\"{$this->selfDocument}?bs_debedoo[iName]={$this->internalName}&bs_debedoo[searchTerm]={$this->_searchTerm}&bs_debedoo[offset]={$i}\">" .$y . '</a> ';}
$y++;if ($y > 10) break; }
}
if (($numRecs - $offset) > $limit) {$ret .= "&nbsp;&nbsp;<a href=\"{$this->selfDocument}?bs_debedoo[iName]={$this->internalName}&bs_debedoo[searchTerm]={$this->_searchTerm}&bs_debedoo[offset]=" . ($offset + $limit) . "\">" . $this->guiStrings['default']['forward'] . "</a>";}
return $ret;}
function _getForeignKeyValueField($fieldName, $recordData) {$recordData = &$this->Bs_Array->hashKeysToLower($recordData);do {if (!isSet($this->useOverviewProfile)) break;if (!isSet($this->overviewSettings[$this->useOverviewProfile]['fields'][$fieldName]['foreignKey']['fields'])) break;$t = $this->overviewSettings[$this->useOverviewProfile]['fields'][$fieldName]['foreignKey']['fields'];if (is_string($t)) $t = array($t);return $t;} while (FALSE);$lang = strToLower(substr($this->_language, 0, 2));if (isSet($recordData['caption' . $lang])) {$keyValueField = 'caption' . $lang;} elseif (isSet($recordData['caption'])) {$keyValueField = 'caption';} elseif (isSet($recordData['captionen'])) {$keyValueField = 'captionen';} else {while (list($field) = each($recordData)) {if (($field !== 'id') && ($field !== strToLower($fieldName))) {$keyValueField = $field;break;}
}
}
if (!isSet($keyValueField)) return FALSE;return array($keyValueField);}
function _getForeignKeyOrderBy($fieldName) {do {if (!isSet($this->useOverviewProfile)) break;if (!isSet($this->overviewSettings[$this->useOverviewProfile]['fields'][$fieldName]['foreignKey']['orderBy'])) break;$t = $this->overviewSettings[$this->useOverviewProfile]['fields'][$fieldName]['foreignKey']['orderBy'];if (is_string($t)) return 'ORDER BY ' . $t;} while (FALSE);return '';}
function search() {}
function _loadForm() {$this->_form =& new Bs_Form();$this->_form->action = $this->buildUrl(); $formLoaded = FALSE;do {if (!isSet($this->formName) || empty($this->formName)) break;$exception = $this->_form->unPersist($this->formName);if (isEx($exception)) {$exception->stackTrace('was here in _loadForm()', __FILE__, __LINE__);break;}
unset($this->_form->saveToDb);unset($this->_form->sendMailRaw);unset($this->_form->sendMailNice1);$formLoaded = TRUE;} while (FALSE);if (!$formLoaded) {$this->_buildForm();}
$this->_form->language  = $this->_language;return TRUE;}
function _buildForm() {$this->_form->buttons = 'default';unset($container);$container =& new Bs_FormContainer();$container->name            = "container";$container->pseudoContainer = TRUE;$container->orderId         = 1000;$this->_form->elementContainer->addElement($container);$fieldStruct = $this->_determineFieldStructure();if (isEx($fieldStruct)) {$fieldStruct->stackTrace('in _buildForm()', __FILE__, __LINE__);return $fieldStruct;}
$i = 1000;foreach ($fieldStruct as $k => $fieldRec) {if (isSet($this->overviewSettings[$this->useOverviewProfile]['fields'][$k]['caption'])) {$fieldCaption = $this->Bs_TextUtil->getLanguageDependentValue($this->overviewSettings[$this->useOverviewProfile]['fields'][$k]['caption'], $this->_language);} else {$fieldCaption = $fieldRec['caption'];}
if (!is_array($fieldRec['foreignKey']) && is_array($this->overviewSettings[$this->useOverviewProfile]['fields'][$k]['foreignKey'])) {$fieldRec['foreignKey'] = $this->overviewSettings[$this->useOverviewProfile]['fields'][$k]['foreignKey'];}
$fieldDone = FALSE;if (is_array($fieldRec['foreignKey'])) {$orderBy = $this->_getForeignKeyOrderBy($k);$sqlQ = "SELECT * FROM {$fieldRec['foreignKey']['dbTableName']} " . $orderBy;$idRecords = $this->_bsDb->getAll($sqlQ);if (isEx($idRecords)) {$idRecords->stackDump('echo');break;}
$keyField = $this->_getForeignKeyValueField($k, current($idRecords));foreach ($idRecords as $t => $val) {$idRecords[$t] = &$this->Bs_Array->hashKeysToLower($val);}
if (empty($orderBy) && ($keyField !== FALSE)) {$GLOBALS['_bs_debedoo_mycmp_keyfield'] = current($keyField);uasort($idRecords, '_bs_debedoo_mycmp');}
$keyFieldName = strToLower($fieldRec['foreignKey']['dbFieldName']);$selectOptions = array(''=>''); foreach ($idRecords as $t => $val) {$fKeyValue = array();foreach ($keyField as $currentKeyField) {$fKeyValue[] = $idRecords[$t][$currentKeyField];}
$selectOptions[$val[$keyFieldName]] = join(' ', $fKeyValue);}
$element =& new Bs_FormFieldSelect();$element->name          = $k;$element->caption       = $fieldCaption;$element->optionsHard   = $selectOptions; $element->editability   = $fieldRec['editability'];$element->minLength     = 0;$element->maxLength     = $fieldRec['maxLength'];$element->orderId       = --$i;$element->bsDataType    = $fieldRec['bsDataType'];$element->bsDataInfo    = $fieldRec['bsDataInfo'];$element->must          = $fieldRec['must'];$fieldDone = TRUE;} elseif ($fieldRec['dbDataType'] == 'blob') {$element =& new Bs_FormFieldTextarea();$element->name              = $k;$element->caption           = $fieldCaption;$element->rows = 10;$element->editability       = $fieldRec['editability'];$element->minLength         = 0;$element->maxLength         = $fieldRec['maxLength'];$element->orderId           = --$i;$element->bsDataType        = $fieldRec['bsDataType'];$element->bsDataInfo        = $fieldRec['bsDataInfo'];$fieldDone = TRUE;}
if (!$fieldDone) {$element =& new Bs_FormFieldText();$element->name              = $k;$element->caption           = $fieldCaption;$element->editability       = $fieldRec['editability'];$element->minLength         = 0;$element->maxLength         = $fieldRec['maxLength'];$element->orderId           = --$i;$element->bsDataType        = $fieldRec['bsDataType'];$element->bsDataInfo        = $fieldRec['bsDataInfo'];$element->must              = $fieldRec['must'];$element->trim              = 'both';}
$container->addElement($element);unset($element);}
}
function _determineFieldStructure() {$ret = array();$dbName    = (isSet($this->dbName)) ? $this->dbName : null;$tblStruct = $this->_bsDb->getTableStructure($this->dbTableName, $dbName);if (isEx($tblStruct)) {$tblStruct->stackTrace('in _determineFieldStructure()', __FILE__, __LINE__);return $tblStruct;}
foreach ($tblStruct as $k => $tblRec) {unset($t); $t = array();$t['caption']        = ucWords(strtr($k, '_', ' '));$t['dbDataType']     = $tblRec['type'];switch ($t['dbDataType']) {case 'tinyint':
case 'smallint':
case 'mediumint':
case 'int':
case 'integer': case 'bigint':
case 'float':
case 'double':
case 'double precision': case 'real': case 'decimal':
case 'numeric': $t['bsDataType']      = 'number';break;case 'date':
case 'time':
case 'datetime':
$t['maxLength'] = 19;case 'timestamp':
case 'year':
$t['bsDataType']      = 'clock';if ($t['dbDataType'] == 'timestamp') $t['overviewHide'] = TRUE;break;case 'char':
case 'varchar':
$t['bsDataType']      = 'text';break;case 'tinyblob':
case 'tinytext':   case 'blob':
case 'mediumblob':
case 'mediumtext': case 'longblob':
case 'longtext':   $t['bsDataType']   = 'blob';break;case 'enum':
case 'set':
$t['bsDataType'] = 'text';break;default:
$t['bsDataType'] = 'text'; }
$t['bsDataInfo']     = 1; $t['must']           = FALSE; $t['minLength']      = 0;if (!isSet($t['maxLength'])) {$t['maxLength']      = $tblRec['length'];}
if (!($tblRec['primaryKey'] === TRUE) && ((strlen($k) > 2) && (substr($k, -2) == 'ID'))) {$t['foreignKey']     = array(
'dbDsn'       => null, 
'dbName'      => null, 'dbTableName' => substr($k, 0, -2), 
'dbFieldName' => ($this->useLongID) ? $k : 'ID', 
'multiple'    => FALSE, );} else {$t['foreignKey']     = FALSE;}
$t['mustBeUnique']   = $tblRec['unique'];$t['valueDefault']   = $tblRec['default'];$t['overviewSelect'] = ($t['bsDataType'] == 'blob') ? FALSE : TRUE;$t['overviewShow']   = ($t['bsDataType'] == 'blob') ? FALSE : TRUE;if (!isSet($t['overviewHide'])) $t['overviewHide']   = FALSE; $t['editability']    = ($tblRec['autoIncrement']) ? 'never' : 'always';switch (str_replace('_', '', strToLower($k))) {case 'id':
$t['caption']        = 'ID';$t['editability']    = 'never';$t['overviewHide']   = TRUE;break;case 'caption':
$t['overviewSelect'] = TRUE;$t['caption']        = array('en'=>'Caption', 'de'=>'Bezeichnung');break;case 'captionen':
$t['overviewSelect'] = TRUE;$t['caption']        = array('en'=>'Caption EN', 'de'=>'Bezeichnung EN');break;case 'captionde':
$t['overviewSelect'] = TRUE;$t['caption']        = array('en'=>'Caption DE', 'de'=>'Bezeichnung DE');break;case 'captionfr':
$t['overviewSelect'] = TRUE;$t['caption']        = array('en'=>'Caption FR', 'de'=>'Bezeichnung FR');break;case 'adminid':
$t['caption']        = 'Administrator';$t['editability']    = 'never';$t['overviewHide']   = TRUE;break;case 'timestamp':
$t['caption']        = array('en'=>'Timestamp', 'de'=>'Zeitstempel');$t['editability']    = 'never';$t['overviewHide']   = TRUE;break;case 'ipnumber':
$t['caption']        = array('en'=>'IP Number', 'de'=>'IP Nummer');$t['editability']    = 'never';$t['bsDataType']       = 'ip';$t['overviewHide']   = TRUE;break;case 'ipresolved':
$t['caption']        = array('en'=>'Host', 'de'=>'Host');$t['editability']    = 'never';$t['bsDataType']       = 'host';$t['overviewHide']   = TRUE;break;case 'createdatetime':
$t['caption']        = array('en'=>'Created', 'de'=>'Erstellt');$t['editability']    = 'never';$t['overviewHide']   = TRUE;break;case 'createadminid': $t['caption']        = array('en'=>'Creator', 'de'=>'Ersteller');$t['editability']    = 'never';$t['overviewHide']   = TRUE;break;case 'lastmod':
case 'lastmodified':
case 'lastmoddatetime':
case 'lastmodifieddatetime':
$t['caption']        = array('en'=>'Last modified', 'de'=>'Zuletzt geändert');$t['editability']    = 'never';$t['overviewHide']   = TRUE;break;case 'lastmodadminid': case 'lastmodifiedadminid':
$t['caption']        = array('en'=>'Last modified from', 'de'=>'Zuletzt geändert von');$t['editability']    = 'never';$t['overviewHide']   = TRUE;break;case 'street':
case 'streetname':
case 'namestreet':
$t['caption']        = array('en'=>'Street', 'de'=>'Strasse');$t['minLength']      = 2;break;case 'streetnumber':
case 'numberstreet':
$t['caption']        = array('en'=>'Street Number', 'de'=>'Strassen-Nummer');$t['minLength']      = 1;break;case 'location':
case 'city':
$t['caption']        = array('en'=>'Location', 'de'=>'Ort');$t['minLength']      = 2;break;case 'zipcode':
case 'zip':
$t['caption']        = array('en'=>'Zipcode', 'de'=>'PLZ');$t['minLength']      = 4;$t['bsDataType']       = 'zipcode';break;case 'pobox':
case 'postbox':
$t['caption']        = array('en'=>'Pobox', 'de'=>'Postfach');break;case 'fullname':
case 'name':
$t['caption']        = 'Name';$t['minLength']      = 2;break;case 'firstname':
$t['caption']        = array('en'=>'Firstname', 'de'=>'Vorname');$t['minLength']      = 2;break;case 'lastname':
$t['caption']        = array('en'=>'Lastname', 'de'=>'Nachname');$t['minLength']      = 2;break;case 'phone':
$t['caption']        = array('en'=>'Phone', 'de'=>'Telefon');$t['minLength']      = 7;break;case 'phoneoffice':
case 'officephone':
$t['caption']        = array('en'=>'Phone Office', 'de'=>'Telefon Geschäft');$t['minLength']      = 7;break;case 'phoneprivate':
case 'privatephone':
$t['caption']        = array('en'=>'Phone Private', 'de'=>'Telefon Privat');$t['minLength']      = 7;break;case 'fax':
$t['caption']        = 'Fax';$t['minLength']      = 7;break;case 'cell':
case 'cellphone':
case 'phonecell':
case 'mobile':
case 'mobilephone':
case 'phonemobile':
case 'handy':
case 'natel':
case 'portable':
$t['caption']        = array('en'=>'Cell Phone', 'de'=>'Handy');$t['minLength']      = 7;break;case 'email':
$t['caption']        = array('en'=>'E-Mail', 'de'=>'E-Mail');$t['minLength']      = 7;$t['bsDataType']       = 'email';$t['bsDataInfo']       = 1;break;case 'website':
case 'url':
case 'homepage':
$t['caption']        = 'Website';$t['minLength']      = 11;$t['bsDataType']       = 'url';break;case 'comments':
$t['caption']        = array('en'=>'Comments', 'de'=>'Bemerkungen');break;case 'lang':
case 'language':
$t['caption']        = array('en'=>'Language', 'de'=>'Sprache');break;case 'sex':
case 'anrede':
case 'geschlecht':
$t['caption']        = array('en'=>'Sex', 'de'=>'Anrede');break;default:
}
if ($t['dbDataType'] == 'timestamp') {$t['editability']  = 'never';$t['overviewHide'] = TRUE;}
$ret[$k] = $t;}
return $ret;}
function _treatFormAdd() {if (!isSet($this->_form)) {$status = $this->_loadForm();if (isEx($status)) {$status->stackTrace('was here in _treatFormAdd()', __FILE__, __LINE__);return $status;}
}
$ret = '';if (isSet($this->_postVars['bs_form']['step']) && ($this->_postVars['bs_form']['step'] == '2')) {$this->_form->setReceivedValues($this->_postVars);$isOk = $this->_form->validate();if ($isOk) {$recordData = $this->_form->getValuesArray(FALSE, 'valueInternal'); $this->_bsDbWrapper =& new Bs_DbWrapper();$this->_bsDbWrapper->setDbObj($this->_bsDb);if (isSet($this->dbName)) {if ($this->_bsDb->getDbName() != $this->dbName) {$dsn = $this->_bsDb->getDsn();$dsn['name'] = $this->dbName;$dbObj = &getDbObject($dsn);$this->_bsDbWrapper->setDbObj($dbObj);} else {$this->_bsDbWrapper->setDbName($this->dbName);}
}
$this->_bsDbWrapper->setDbTableName($this->dbTableName);$this->_bsDbWrapper->initByData($recordData);$recordId = $this->_bsDbWrapper->dbInsert();$url = $this->buildUrl() . '&bs_debedoo[statusCode]=1&bs_debedoo[statusMsg]=' . urlencode($this->guiStrings['default']['savesuccess']);redirect($url);} else {$ret .= $this->_form->getForm(TRUE);}
} else {$this->_form->mode = 'add';$ret .= $this->_form->getForm();}
return $ret;}
function _treatFormEdit($useGet=FALSE) {if (!isSet($this->_form)) {$status = $this->_loadForm();if (isEx($status)) {$status->stackTrace('was here in _treatFormEdit()', __FILE__, __LINE__);return $status;}
}
$requestVars = ($useGet) ? $this->_getVars : $this->_postVars;$ret = '';if (isSet($requestVars['bs_form']['step']) && ($requestVars['bs_form']['step'] == '2') && ($requestVars['bs_form']['mode'] != 'view')) {$this->_form->setReceivedValues($requestVars);$isOk = $this->_form->validate();if ($isOk) {$recordData = $this->_form->getValuesArray(FALSE, 'valueInternal'); $this->_bsDbWrapper =& new Bs_DbWrapper();$this->_bsDbWrapper->setDbObj($this->_bsDb);$this->_bsDbWrapper->setDbTableName($this->dbTableName);$this->_bsDbWrapper->initByData($recordData);$recordId = $this->_bsDbWrapper->dbUpdate();if (function_exists('treatFormEdit_postStore')) {treatFormEdit_postStore($form); }
$url = $this->buildUrl() . '&bs_debedoo[statusCode]=1&bs_debedoo[statusMsg]=' . urlencode($this->guiStrings['default']['savesuccess']);redirect($url);} else {$ret .= $this->_form->getForm(TRUE);}
} else {$this->_form->mode     = 'edit';$this->_form->recordId = $requestVars['bs_form']['recordId'];$this->_bsDbWrapper =& new Bs_DbWrapper();$this->_bsDbWrapper->setDbObj($this->_bsDb);$this->_bsDbWrapper->setDbTableName($this->dbTableName);$dbKeyFieldName = ($this->useLongID) ? $this->dbTableName . 'ID' : 'ID'; $boolStatus = $this->_bsDbWrapper->initByID($requestVars['bs_form']['recordId'], $dbKeyFieldName);$loadedData = $this->_bsDbWrapper->getData();$this->_form->setLoadedValues($loadedData);if (function_exists('treatFormEdit_showFirsttimeAfterLoad')) {treatFormEdit_showFirsttimeAfterLoad($this->_form); }
$ret .= $this->_form->getForm();if (function_exists('treatFormEdit_showFirsttimeAfterGetForm')) {treatFormEdit_showFirsttimeAfterGetForm($this->_form, $ret); }
}
return $ret;}
function _treatFormDelete($useGet=FALSE) {if (!isSet($this->_form)) {$status = $this->_loadForm();if (isEx($status)) {$status->stackTrace('was here in _treatFormDelete()', __FILE__, __LINE__);return $status;}
}
$requestVars = ($useGet) ? $this->_getVars : $this->_postVars;$ret = '';if (isSet($requestVars['bs_form']['step']) && ($requestVars['bs_form']['step'] == '2') && ($requestVars['bs_form']['mode'] != 'view')) {$this->_form->setReceivedValues($requestVars);$this->_bsDbWrapper =& new Bs_DbWrapper();$this->_bsDbWrapper->setDbObj($this->_bsDb);$this->_bsDbWrapper->setDbTableName($this->dbTableName);$dbKeyFieldName = ($this->useLongID) ? $this->dbTableName . 'ID' : 'ID'; $boolStatus = $this->_bsDbWrapper->initByID($requestVars['bs_form']['recordId'], $dbKeyFieldName);$status = $this->_bsDbWrapper->dbDelete();$url = $this->buildUrl() . '&bs_debedoo[statusCode]=1&bs_debedoo[statusMsg]=' . urlencode($this->guiStrings['default']['savesuccess']);redirect($url);} else {$this->_form->mode     = 'delete';$this->_form->recordId = $requestVars['bs_form']['recordId'];$this->_bsDbWrapper =& new Bs_DbWrapper();$this->_bsDbWrapper->setDbObj($this->_bsDb);$this->_bsDbWrapper->setDbTableName($this->dbTableName);$dbKeyFieldName = ($this->useLongID) ? $this->dbTableName . 'ID' : 'ID'; $boolStatus = $this->_bsDbWrapper->initByID($requestVars['bs_form']['recordId'], $dbKeyFieldName);$loadedData = $this->_bsDbWrapper->getData();$this->_form->setLoadedValues($loadedData);$ret .= $this->_form->getForm();}
return $ret;}
function _treatFormView() {$ret = '';if (!isSet($this->_form)) {$status = $this->_loadForm();if (isEx($status)) {$status->stackTrace('was here in _treatFormView()', __FILE__, __LINE__);return $status;}
}
$this->_form->setReceivedValues($this->_getVars);$this->_bsDbWrapper =& new Bs_DbWrapper();$this->_bsDbWrapper->setDbObj($this->_bsDb);$this->_bsDbWrapper->setDbTableName($this->dbTableName);$dbKeyFieldName = ($this->useLongID) ? $this->dbTableName . 'ID' : 'ID'; $boolStatus = $this->_bsDbWrapper->initByID($requestVars['bs_form']['recordId'], $dbKeyFieldName);$loadedData = $this->_bsDbWrapper->getData();$this->_form->setLoadedValues($loadedData);$ret .= $this->_form->getForm();return $ret;}
function _debedooTreatAddForm() {}
function _getHtmlStart() {$ret = '
<html>
<head>
<title>bs debedoo</title>
<style>
body {font-family: Arial, Helvetica, sans-serif;font-size: 10pt;font-weight: normal;}
td {font-family: Arial, Helvetica, sans-serif;font-size: 10pt;font-weight: normal;}
a {font-family: Arial, Helvetica, sans-serif;font-size: 10pt;font-weight: normal;color: black;}
</style>
</head>
<body bgcolor="white">
';if (is_string($this->addHeadString)) $ret .= $this->addHeadString;return $ret;}
function _getHtmlEnd() {return '
</body>
</html>
';}
function unPersist($internalName=NULL) {if (is_null($internalName)) {$status = $this->persister->unpersist();} else {$status = $this->persister->unpersist("WHERE " . BS_OP_FIELD_PREFIX . "internalName = '{$internalName}'");}
if (isEx($status)) {$status->stackTrace('was here in unPersist()', __FILE__, __LINE__);$funcArgs = func_get_args();$status->setStackParam('functionArgs', $funcArgs);return $status;} elseif ($status === FALSE) {return FALSE;}
return TRUE;}
function buildUrl($minimal=FALSE) {$ret = $this->selfDocument; $ret .= '?bs_debedoo[iName]='      . $this->internalName;if ($minimal) return $ret;$ret .= '&bs_debedoo[offset]='     . $this->_clickOffset;$ret .= '&bs_debedoo[searchTerm]=' . $this->_searchTerm;return $ret;}
}
?>
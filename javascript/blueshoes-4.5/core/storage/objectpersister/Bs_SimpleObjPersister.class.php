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
define('BS_SIMPLEOBJPERSISTER_VERSION',      '4.5.$Revision: 1.10 $');if (!isSet($GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'])) $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] = '../';if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($GLOBALS['APP']['path']['core'] . 'db/Bs_DbGeneral.lib.php');define('BS_SOP_STORE_OK', 1);define('BS_SOP_STORE_INSERT_FORCED', 2);define('BS_SOP_STORE_INSERT_FORCED_WITH_ID_CHANGE', 3);class Bs_SimpleObjPersister extends Bs_Object {var $_classRegistry = array();var $_dbObject = NULL;var $_cryptPlugin = array (
'crypter'         => NULL,  'cryptKey'        => 'use setCrypt() to set this crypt key',
);var $_defaultPersistHints = array (
'table' => array ('name'   => NULL,   'create' => TRUE    ),
'debug' => array ('checkHintSyntax' => TRUE,  'checkClassVars'  => TRUE,  ),
'props'  => array ('ignoreData'=>FALSE,
),
);var $_validKeyTypes  = array('auto_increment', 'uniqueStr13', 'uniqueStr23', 'callback', 'manual');var $_validMetaTypes = array('boolean', 'integer', 'double', 'string', 'blob', 'text', 'serialize');function Bs_SimpleObjPersister() {}
function setCrypt($cryptKey, $crypter=NULL) {$_func_='setCrypt';$err = FALSE;if (@!is_null($crypter)) {if(@!is_object($crypter)) {$err = "The passed crypter is NOT an object";} elseif (!method_exists($options['crypter'], 'encrypt')) {$err = 'The passed crypter is MISSING the methode "encrypt($key, $data)"';} elseif (!method_exists($options['crypter'], 'decrypt')) {$err = 'The passed crypter is MISSING the methode "decrypt($key, $data)"';} else {$this->_cryptPlugin['crypter']  =& $crypter;}
}
$this->_cryptPlugin['cryptKey'] = $cryptKey;if ($err) {Bs_Error::setError($err, 'ERROR', __LINE__, $_func_,__FILE__);return FALSE;}
return TRUE;}
function setDbObject(&$dbObject) {$this->_dbObject =& $dbObject;$this->_dbIsMySql = (bool)(@$dbObject->getDsn('type')==='mysql');}
function &getDbObject() {return isSet($this->_dbObject) ? $this->_dbObject : FALSE;}
function register($theObject, $hints=NULL) {$_func_='register';$err = '';$status = FALSE;do { if (!$classname = $this->_getClassName($theObject)) {Bs_Error::setError("-- See previous Error.", 'ERROR', __LINE__, $_func_,__FILE__);break; }
$theObject = is_object($theObject) ? $theObject : new $theObject();if (empty($hints) AND method_exists($theObject, 'bs_sop_getHints')){$hints = $theObject->bs_sop_getHints($this);if (empty($hints)) {$err = "No persisting hints returned by the callback function {$classname}::bs_sop_getHints() from your object[{$classname}].";break; }
} else {$err = "No persisting hints found for [{$classname}]. You must pass the hint-hash when calling register() OR define the callback methode bs_sop_getHints() in your class, that returns the hint-hash.";break; }
foreach($this->_defaultPersistHints as $key => $defaults) {$hints[$key] = empty($hints[$key]) ? $defaults : array_merge($defaults, $hints[$key]);}
$tmpObj = new stdClass();if (isSet($hints['props']['ignorData'])) {$tmpObj->ignoreData = (bool) $hints['props']['ignorData'];} elseif (isSet($hints['props']['ignoreData'])) {$tmpObj->ignoreData = (bool) $hints['props']['ignoreData'];}
$hints['table']['name'] = empty($hints['table']['name']) ? $classname : $hints['table']['name'];if (!$tmpObj->ignoreData) {if (empty($hints['primary']) AND empty($hints['fields']) AND isSet($hints['primary']) AND isSet($hints['fields'])) {$tmpObj->ignoreData = TRUE;}
}
if (isSet($hints['fields'])) {foreach($hints['fields'] as $fieldName => $attr) {if (!isSet($attr['name']) && is_string($fieldName)) {$hints['fields'][$fieldName]['name'] = $fieldName;}
}
}
if (!$tmpObj->ignoreData) {if (@$hints['debug']['checkHintSyntax']) {if (!$this->hintsSyntaxCheck($hints, $classname)) {trigger_error(join('<hr>', Bs_Error::getLastErrors()), E_USER_WARNING);$err = '-- See previous error(s)';break; }
}
if (@$hints['debug']['checkClassVars']) {if(!$this->classVarCheck($classname, $hints)) {trigger_error(join('<hr>', Bs_Error::getLastErrors()), E_USER_WARNING);$err = '-- See previous error(s)';break; }
}
} $tmpObj->classname = $classname;if (!empty($hints['primary']) AND is_array($hints['primary'])) {reset($hints['primary']);list($tmpObj->primaryVarName, $tmpObj->primaryAttr) = each($hints['primary']);} else {$tmpObj->primaryVarName = '';$tmpObj->primaryAttr = array();}
$tmpObj->fields  = isSet($hints['fields']) ? $hints['fields'] : array();$tmpObj->debug   = $hints['debug'];$tmpObj->table   = $hints['table'];$tmpObj->callPreStore   = method_exists($theObject, 'bs_sop_prestore');$tmpObj->callPostStore  = method_exists($theObject, 'bs_sop_poststore');$tmpObj->callPreLoad    = method_exists($theObject, 'bs_sop_preload');$tmpObj->callPostLoad   = method_exists($theObject, 'bs_sop_postload');$tmpObj->callPreDelete  = method_exists($theObject, 'bs_sop_predelete');$tmpObj->callPostDelete = method_exists($theObject, 'bs_sop_postdelete');if (empty($this->_cryptPlugin['crypter']) OR !is_object($this->_cryptPlugin['crypter'])) {if (empty($GLOBALS['Bs_Rc4Crypt'])) _bs_sop_lasyLoad('crypt/Bs_Rc4Crypt.class.php');$this->_cryptPlugin['crypter'] =& $GLOBALS['Bs_Rc4Crypt'];$this->_cryptPlugin['cryptKey'] = 'My Random Crypt String';}
if (!empty($err)) break; $this->_classRegistry[$classname] = $tmpObj;$status = TRUE;} while(FALSE);if (!$status) {Bs_Error::setError($err, 'ERROR', __LINE__, $_func_,__FILE__);return FALSE;}
return TRUE;}
function load(&$theObject, $varsToLoad=NULL) {$_func_='load';$status = FALSE;$whereClause = '';do {if (!is_object($theObject)) {$err='Paramter 1 is not an object'; break;} if (!$classRegistry = $this->_getClassRegistry($theObject)) {$err='-- See previous error(s).'; break;}
$primaryVarName = $classRegistry->primaryVarName;if (!$classRegistry->ignoreData) {if (empty($theObject->$primaryVarName)) {$err = "Passed Object has *no* ID set (or it's empty)! The ID we look for is [{$classRegistry->classname}::{$primaryVarName}]";break; }
$whereClause =  'WHERE ' . $classRegistry->primaryAttr['name'] . "='" . $theObject->$primaryVarName . "'";}
if (FALSE === $this->_genericLoad($theObject, $varsToLoad, $whereClause, $singleLoad=TRUE)) {$err = "Call to _genericLoad() failed. See previous error(s).";break; }
$status = TRUE;} while(FALSE);if (!$status) {Bs_Error::setError($err, 'ERROR', __LINE__, $_func_, __FILE__);return FALSE;}
return TRUE;}
function loadByIds($classname, $ids, $varsToLoad=NULL) {$_func_='loadByIds';$status = FALSE;$objList = array();if (!empty($ids) AND !is_array($ids)) $ids = array($ids);$whereClause = '';do {if (!$classRegistry = $this->_getClassRegistry($classname)) {$err='-- See previous error(s).'; break;}
$whereClause = 'WHERE ' . $classRegistry->primaryAttr['name'] . ' IN (';$firstLoop = TRUE;foreach ($ids as $id) {$whereClause .= $firstLoop ? '' : ', ';$firstLoop = FALSE;$whereClause .=  "'" . $id . "'";}
$whereClause .=  ')';$objList = $this->_genericLoad($classRegistry->classname, $varsToLoad, $whereClause, $singleLoad=FALSE);if (FALSE === $objList) {$err = "Call to _genericLoad() failed. See previous error(s).";break; }
$status = TRUE;} while(FALSE);if (!$status) {Bs_Error::setError($err, 'ERROR', __LINE__, $_func_, __FILE__);return FALSE;}
return $objList;}
function &loadById($classname, $id, $varsToLoad=NULL) {$ret = $this->loadByIds($classname, $id, $varsToLoad);if (FALSE === $ret) return FALSE;if (empty($ret)) return NULL;return $ret = array_shift($ret);}
function loadMeByWhere(&$theObject, $whereClause, $varsToLoad=NULL) {$_func_='loadMeByWhere';$status = FALSE;$objList = array();do {if (!is_object($theObject)) {$err='Paramter 1 is not an object'; break;} if (!$this->_genericLoad($theObject, $varsToLoad, $whereClause, $singleLoad=TRUE)) {$err = "Call to _genericLoad() failed. See previous error(s).";break; }
$status = TRUE;} while(FALSE);if (!$status) {Bs_Error::setError($err, 'ERROR', __LINE__, $_func_, __FILE__);return FALSE;}
return TRUE;}
function loadByWhere($classname, $whereClause, $varsToLoad=NULL) {$_func_='loadByWhere';$status = FALSE;$objList = array();do {$objList = $this->_genericLoad($classname, $varsToLoad, $whereClause, $singleLoad=FALSE);if (FALSE===$objList) {$err = 'Call to _genericLoad() failed. See previous error(s)';break; }
$status = TRUE;} while(FALSE);if (!$status) {Bs_Error::setError($err, 'ERROR', __LINE__, $_func_, __FILE__);return FALSE;}
return $objList;}
function loadAll($classname, $varsToLoad=NULL) {$_func_='loadAll';$objList = $this->loadByWhere($classname, $whereClause='', $varsToLoad);if (FALSE===$objList) {$err = 'Call to loadByWhere() failed. See previous error(s)';Bs_Error::setError($err, 'ERROR', __LINE__, $_func_, __FILE__);return FALSE;}
return $objList;}
function _genericLoad(&$theObject, $varsToLoad=NULL, $whereClause='', $singleLoad=FALSE) {$_func_='_genericLoad';$status = FALSE;$objList = array();if (!empty($varsToLoad) AND !is_array($varsToLoad)) $varsToLoad = array($varsToLoad);$err = 'HEY, programmer! Put some comment here if you run into an error!';do {if (empty($this->_dbObject)) {$className = basename(__FILE__);trigger_error('No DB-Interface! You must init {$className} with an initialized instance of Bs_MySql as the DB-interface to us it.', E_USER_WARNING);}
if (!$classRegistry = $this->_getClassRegistry($theObject)) {$err='-- See previous error(s).'; break;}
if ($classRegistry->ignoreData) {$status = TRUE;if (@is_string($theObject)) $theObject = new $theObject();if ($classRegistry->callPreLoad)  $status = (FALSE!==$theObject->bs_sop_preLoad($this, $varsToLoad));if ($classRegistry->callPostLoad) $theObject->bs_sop_postLoad($this, $status);break; }
$classname = $classRegistry->classname;$loadAll = TRUE;if (empty($varsToLoad)) {  $loadAll = TRUE;} else { $passedVars = $varsToLoad;if (!empty($varsToLoad)) {$loadAll = FALSE;} else {$registeredVars = join(',', array_keys($classRegistry->fields));$passedVars = join(',', $passedVars);$err = "No registered fields to load, check Hint-hash. Passed var-list[{$passedVars}] Registered var-list[{$registeredVars}].";break; }
}
$sqlFieldList = array();if ($loadAll) {$sqlFieldList[] = '*';$varsToLoad = array_keys($classRegistry->fields);} else {$sqlFieldList[] = $classRegistry->primaryAttr['name'];foreach($varsToLoad as $varName) $sqlFieldList[] = $classRegistry->fields[$varName]['name'];}
$sql = 'SELECT ' . join(',',$sqlFieldList) . ' FROM ' . $classRegistry->table['name'] . ' ' . $whereClause;if (isEx($e = $this->_dbObject->getAll($sql))) {$err = $e->stackDump('return');break;} elseif (sizeOf($e) == 0) {$err = "SQL[$sql] returned *no* result!";break;}
$primaryVarName = $classRegistry->primaryVarName;$primaryFieldName = $classRegistry->primaryAttr['name'];foreach($e as $rec) {$id = $rec[$primaryFieldName];unset($tmpObj);if ($singleLoad) {$tmpObj =& $theObject;} else {$tmpObj =& new $classname();}
$objList[$id] =& $tmpObj;if ($classRegistry->callPreLoad) $tmpObj->bs_sop_preLoad($this, $varsToLoad);$tmpObj->$primaryVarName = $id;foreach($varsToLoad as $varName) {if (!isSet($classRegistry->fields[$varName])) {trigger_error("Can't load a varible that is not defined in the hint-hash; ignored it. The class name was:[{$classname}] and the field name you tryed to load was:[{$varName}].", E_USER_WARNING);continue; }
$fieldAttr = $classRegistry->fields[$varName];if (!isSet($rec[$fieldAttr['name']])) {if (!$this->updateTableStructure($theObject)) {trigger_error("In class[{$classname}] a new field[{$fieldAttr['name']}] was detected; but failed to update DB; ignored it. Error details: " .Bs_Error::getLastErrors(), E_USER_WARNING);}
continue; }
$tmp = $rec[$fieldAttr['name']];switch ($fieldAttr['metaType']) {case 'integer':   $tmp = (int)$tmp;         break;case 'boolean':   $tmp = (bool)$tmp;        break;case 'double' :   $tmp = (float)$tmp;       break;case 'serialize': 
if (!empty($tmp)) $tmp = unserialize($tmp); break;default: }
if (!empty($fieldAttr['crypt'])) $tmp = $this->_cryptPlugin['crypter']->decrypt($this->_cryptPlugin['cryptKey'], $tmp);$tmpObj->$varName = $tmp;} if ($classRegistry->callPostLoad) $tmpObj->bs_sop_postLoad($this, $status);if ($singleLoad) break; }
$status = TRUE;} while(FALSE);if (!$status) {Bs_Error::setError($err, 'ERROR', __LINE__, $_func_, __FILE__);return FALSE;}
if ($singleLoad) return TRUE;return $objList;}
function insert(&$theObject, $varsToStore=NULL) {return $this->store($theObject, $varsToStore, $forceInsertWithCurrentID=TRUE);}
function store(&$theObject, $varsToStore=NULL, $forceInsertWithCurrentID=FALSE) {$_func_='store';$status = FALSE;$return = BS_SOP_STORE_OK;if (!empty($varsToStore) AND !is_array($varsToStore)) $varsToStore = array($varsToStore);$triggerPostCallback = FALSE; $err = 'HEY, programer! Put some comment here if you run into an error!';$tryBlock = 1;do {if (empty($this->_dbObject)) {$className = basename(__FILE__);trigger_error('No DB-Interface! You must init {$className} with an initialized instance of Bs_MySql as the DB-interface to us it.', E_USER_WARNING);}
if (!is_object($theObject)) { $err='Paramter 1 is not an object'; break $tryBlock; } if (!$classRegistry = $this->_getClassRegistry($theObject)) {$err='-- See previous error(s).'; break $tryBlock;}
if ($classRegistry->ignoreData) {$status = TRUE;if ($classRegistry->callPreStore)  $status = (FALSE!==$theObject->bs_sop_preStore($this, $varsToStore));if ($classRegistry->callPostStore) $theObject->bs_sop_postStore($this, $status);break; }
$tryToUpdateTblStruc = $classRegistry->table['create'];$primaryHash = $fieldHash = array();$useOfAutoIncrement = ($classRegistry->primaryAttr['type']==='auto_increment');$primaryVarName = $classRegistry->primaryVarName;$primaryFieldName = $classRegistry->primaryAttr['name'];$itsAnUpdate = TRUE;$tryBlock++;do { $tryInsertFallback = FALSE;if (!empty($theObject->$primaryVarName)) {$itsAnUpdate = $forceInsertWithCurrentID ? FALSE : TRUE;$primaryHash[$primaryFieldName] = $theObject->$primaryVarName;} else {$itsAnUpdate = FALSE;$uniquePrefix = isSet($classRegistry->primaryAttr['prefix']) ? $classRegistry->primaryAttr['prefix'] : '';$tryBlock++;switch ($classRegistry->primaryAttr['type']) {case 'auto_increment':
$useOfAutoIncrement = TRUE;break;case 'uniqueStr13':
$primaryHash[$primaryFieldName] = uniqid($uniquePrefix, FALSE);break;case 'uniqueStr23':
$primaryHash[$primaryFieldName] = uniqid($uniquePrefix, TRUE);break;case 'callback':
if (FALSE === ($id =$theObject->bs_sop_getId($this))) {$err = "Callback to Object[{$classRegistry->classname}]->bs_sop_getId() returned FALSE -> canceled while fetching a ID for the Object.";break $tryBlock; }
$primaryHash[$primaryFieldName] = $id;break;case 'manual':
$err = "No primary ID for [{$classRegistry->classname}::{$primaryVarName}]. If primary-type=='manuel' then you must supply a unique object-ID as key.";break $tryBlock; default:;}
$tryBlock--;}
if (empty($varsToStore) OR !$itsAnUpdate) {  $varsToStore = array_keys($classRegistry->fields);} else {  if (!empty($varsToStore)) {} else {$registeredVars = join(',', array_keys($classRegistry->fields));$passedVars = join(',', $passedVars);$err = "No registered fields to store, check Hint-hash. Passed var-list[{$passedVars}] Registered var-list[{$registeredVars}].";break $tryBlock; }
}
$triggerPostCallback = TRUE; if ($classRegistry->callPreStore) {if (FALSE === $theObject->bs_sop_preStore($this, $varsToStore)) {$err = "Callback to Object[{$classRegistry->classname}]->bs_sop_preStore() returned FALSE -> canceled storing of Object.";break $tryBlock; }
}
$tryBlock++;foreach ($varsToStore as $varName) {$tmp = NULL;$fieldAttr = $classRegistry->fields[$varName];if (!isSet($theObject->$varName) OR is_null($theObject->$varName)) {$tmp = '';} else {$tmp = $theObject->$varName;;}
if ($fieldAttr['metaType']==='serialize') $tmp = serialize($tmp);if (!empty($fieldAttr['crypt'])) $tmp = $this->_cryptPlugin['crypter']->encrypt($this->_cryptPlugin['cryptKey'], $tmp);$fieldHash[$fieldAttr['name']] = $tmp;}
$tryBlock--;$sql = $this->_generateSql($classRegistry, $primaryHash, $fieldHash, $itsAnUpdate, $useOfAutoIncrement);$tryBlock++;do {$tryToUpdateTblStruc_triggered = FALSE; if ($itsAnUpdate) {$exec = 'countWrite';  } else {$exec = $useOfAutoIncrement ? 'idwrite' : 'write'; }
if (isEx($e = $this->_dbObject->$exec($sql))) {if ($tryToUpdateTblStruc) {  $tryToUpdateTblStruc = FALSE;  if ($this->updateTableStructure($classRegistry->classname)) { $tryToUpdateTblStruc_triggered = TRUE;continue;}
}
$err = $e->stackDump('return');break $tryBlock; }
} while($tryToUpdateTblStruc_triggered);$tryBlock--;if ($itsAnUpdate) {if (0===$e) {$sql = 'SELECT '. $primaryFieldName . ' FROM ' . $classRegistry->table['name'] . ' WHERE ' . $primaryFieldName . '=' . "'".$this->_dbObject->escapeString($primaryHash[$primaryFieldName])."'";if (isEx($e=$this->_dbObject->countRead($sql))) {$err = "Checking if the object-ID is realy missing in the table.\n".$e->stackDump('return');break $tryBlock; } elseif (0===$e) {$tryInsertFallback = TRUE;if ($useOfAutoIncrement) {$theObject->$primaryVarName = NULL;$return = BS_SOP_STORE_INSERT_FORCED_WITH_ID_CHANGE;} else {$forceInsertWithCurrentID = TRUE;$return = BS_SOP_STORE_INSERT_FORCED;}
}
}
} else {$theObject->$primaryVarName = $useOfAutoIncrement ? $e : $primaryHash[$primaryFieldName];}
} while ($tryInsertFallback);$tryBlock--;$status = TRUE;} while(FALSE);if ($triggerPostCallback AND $classRegistry->callPostStore) $theObject->bs_sop_postStore($this, $status, $varsToStore);if (!$status) {Bs_Error::setError($err, 'ERROR', __LINE__, $_func_,__FILE__);return FALSE;}
return $return;}
function delete(&$theObject) {$_func_='delete';$status = FALSE;$triggerPostCallback = FALSE; do {if (!is_object($theObject)) {$err='Paramter 1 is not an object'; break;} if (!$classRegistry = $this->_getClassRegistry($theObject)) {$err='-- See previous error(s).'; break;}
if ($classRegistry->ignoreData) {$status = TRUE;if ($classRegistry->callPreDelete)  $status = (FALSE!==$theObject->bs_sop_preDelete($this));if ($classRegistry->callPostDelete) $theObject->bs_sop_postDelete($this, $status);break; }
$primaryVarName = $classRegistry->primaryVarName;if (empty($theObject->$primaryVarName)) {$err = "Passed Object has *no* ID set (or it's empty)! The ID we look for is [{$classRegistry->classname}::{$primaryVarName}]";break; }
$sql = 'DELETE FROM ' . $classRegistry->table['name'] . ' WHERE ' . $classRegistry->primaryAttr['name'] . "='" . $theObject->$primaryVarName . "'";$triggerPostCallback = TRUE; if ($classRegistry->callPreDelete) {if (FALSE===$theObject->bs_sop_preDelete($this)) {$err = "Callback to  Object[{$classRegistry->classname}]->bs_sop_preDelete() returned FALSE -> canceled deletion of Object.";break; }
}
if (isEx($e = $this->_dbObject->write($sql))) {$err = $e->stackDump('return');break;}
$status = TRUE;} while(FALSE);if ($triggerPostCallback AND $classRegistry->callPostDelete) $theObject->bs_sop_postDelete($this, $status);if (!$status) {Bs_Error::setError($err, 'ERROR', __LINE__, $_func_, __FILE__);return FALSE;}
return TRUE;}
function _generateSql($classRegistry, $primaryHash, $fieldHash, $itsAnUpdate, $useOfAutoIncrement) {$fieldSetStr = '';reset($primaryHash);list($primaryName, $primaryVal) = each($primaryHash);$primarySetStr = $primaryName .'='. (is_string($primaryVal) ?  "'".$this->_dbObject->escapeString($primaryVal)."'" : $primaryVal);if ($itsAnUpdate) {$sql = 'UPDATE ' . $classRegistry->table['name'] . ' SET ';} else {$sql = 'INSERT INTO ' . $classRegistry->table['name'] . ' SET ';if (!$useOfAutoIncrement) $fieldSetStr = $primarySetStr; }
foreach($fieldHash as $fieldName => $val) {if (!empty($fieldSetStr)) $fieldSetStr .= ', ';if (is_null($val)) {$val ='';} elseif (is_bool($val)){$val = (int)$val;}
$fieldSetStr .= $fieldName .'='. (is_string($val) ?  "'".$this->_dbObject->escapeString($val)."'" : $val);}
$sql .= $fieldSetStr;if ($itsAnUpdate) {$sql .= ' WHERE ' . $primarySetStr;}
return $sql;}
function tableExists($classname) {$_func_='tableExists';$status = FALSE;$e = FALSE;do { if (empty($this->_dbObject)) {$className = basename(__FILE__);trigger_error('No DB-Interface! You must init {$className} with an initialized instance of Bs_MySql as the DB-interface to us it.', E_USER_WARNING);}
if (!$classRegistry = $this->_getClassRegistry($classname)) {$err='-- See previous error(s).';break;}
if ($classRegistry->ignoreData) {$e = $status = TRUE;break; }
if (isEx($e = $this->_dbObject->tableExists($classRegistry->table['name']))) {$err = $e->stackDump('return');break; }
$status = TRUE;} while(FALSE);if (!$status) {Bs_Error::setError($err, 'ERROR', __LINE__, $_func_, __FILE__);return FALSE;}
return $e;}
function updateTableStructure($classname) {$_func_ = 'updateTableStructure';static $defaultDbFieldAttr  = array(
'type'          => 'varchar', 
'length'        => NULL,
'enum'          => FALSE,
'default'       => NULL,
'notNull'       => TRUE,
'primaryKey'    => FALSE,
'multipleKey'   => FALSE,  'fulltext'      => FALSE,
'unique'        => FALSE,
'unsigned'      => FALSE,
'zerofill'      => FALSE,
'binary'        => FALSE,
'autoIncrement' => FALSE,
'foreignKey'    => array(),
);$structure = array();$status = FALSE;do {if (!$classRegistry = $this->_getClassRegistry($classname)) {$err='-- See previous error(s).'; break;}
$primaryAttr = $classRegistry->primaryAttr;$dbFieldAttr = $defaultDbFieldAttr;$dbFieldAttr['primaryKey'] = TRUE;if ($primaryAttr['type'] === 'auto_increment') {$dbFieldAttr['type'] = 'integer';$dbFieldAttr['length'] = NULL;$dbFieldAttr['autoIncrement'] = TRUE;} else {$dbFieldAttr['type'] = 'varchar';$len = 0;if (isSet($primaryAttr['prefix'])) $len = strlen($primaryAttr['prefix']);switch ($primaryAttr['type']) {case 'uniqueStr13': $len += 13; break;case 'uniqueStr23': $len += 23; break;default : $len = 255; }
$dbFieldAttr['length'] = $len;}
$structure[$primaryAttr['name']] = $dbFieldAttr;foreach ($classRegistry->fields as $attr) {$dbFieldAttr = $defaultDbFieldAttr;$dbFieldAttr['multipleKey'] = (isSet($attr['index']) AND $attr['index']);$dbFieldAttr['fulltext'] = (isSet($attr['fulltext']) AND $attr['fulltext']);switch ($attr['metaType']) {case 'boolean':
$dbFieldAttr['default'] = 0;$dbFieldAttr['type'] = 'integer';break;case 'string':
$dbFieldAttr['default'] = '';$dbFieldAttr['type'] = 'varchar';    $dbFieldAttr['length'] = $attr['size'];break;case 'blob':
case 'text': 
case 'serialize':
$blobOrText = ($attr['metaType'] == 'serialize') ? 'blob' : $attr['metaType'];if ($this->_dbIsMySql) {if ($attr['size'] < (1<<16)) $dbFieldAttr['type'] = $blobOrText;       elseif ($attr['size'] < (1<<24)) $dbFieldAttr['type'] = 'medium'.$blobOrText; else $dbFieldAttr['type'] = 'large'.$blobOrText;                               } else {$dbFieldAttr['type'] = 'blob';}
$dbFieldAttr['length'] = $attr['size'];break;default:
$dbFieldAttr['default'] = NULL;$dbFieldAttr['type'] = $attr['metaType'];}
$structure[$attr['name']] = $dbFieldAttr;}
if (isEx($e = $this->_dbObject->updateTableStructure($structure, $classRegistry->table['name']))) {$err = $e->stackDump('return');break;}
$status = $e;} while(FALSE);if (!$status) {Bs_Error::setError($err, 'ERROR', __LINE__, $_func_, __FILE__);return FALSE;}
return $status;}
function hintsSyntaxCheck($theHints, $classname='UNKOWN CLASS') {$_func_ = 'hintsSyntaxCheck';$status = TRUE;do { $errStr_validKeyTypes = '{'. join('|', $this->_validKeyTypes).'}';if (!is_array($theHints)) {Bs_Error::setError("Invalid Hint-Hash in Class [{$classname}]: Must be a hash-array.", 'ERROR', __LINE__, $_func_, __FILE__);$status = FALSE;break; }
if (empty($theHints['primary'])) { Bs_Error::setError("Invalid Hint in 'primary'-block of Class [{$classname}]: Missing 'primary'-block. Add 'primary'. E.g. array('primary' => ... .", 'ERROR', __LINE__, $_func_, __FILE__);$status = FALSE;break; }
reset($theHints['primary']);list($key, $keyAttr) = each($theHints['primary']);if (!is_array($keyAttr)) {Bs_Error::setError("Invalid Hint in 'primary'-block of Class [{$classname}]: Must be a hash-array.", 'ERROR', __LINE__, $_func_, __FILE__);$status = FALSE;break; }
if (empty($keyAttr['name'])) {Bs_Error::setError("Invalid Hint in 'primary'-block of Class [{$classname}]: No valid 'name'. Add 'name' (recommend 'ID'). E.g. array('primary' => array('name'=>'ID'... .", 'ERROR', __LINE__, $_func_, __FILE__);$status = FALSE;} elseif (bs_isReservedDbWord($keyAttr['name'])) {Bs_Error::setError("Invalid Hint in 'primary'-block of Class [{$classname}]: The field name 'name'[$key] belongs to the reserved word list (potential conflict).", 'WARNING', __LINE__, $_func_, __FILE__);$status = FALSE;}
if (empty($keyAttr['type']) OR (!in_array($keyAttr['type'], $this->_validKeyTypes))) {$tmpTxt = empty($keyAttr['type']) ? 'Missing primary-type' : "Invalid primary-type:[{$keyAttr['type']}]";Bs_Error::setError("Invalid Hint in 'primary'-block of Class [{$classname}]: {$tmpTxt}. Add 'type'. E.g. array('primary' => array('type'=>'{$errStr_validKeyTypes}'... .", 'WARNING', __LINE__, $_func_, __FILE__);$status = FALSE;}
} while(FALSE);do { $errStr_validMetaTypes = '{'. join('|', $this->_validMetaTypes).'}';if (empty($theHints['fields'])) { Bs_Error::setError("Invalid Hint in 'fields'-block in Class [{$classname}]: Missing 'fields'-block. Add 'fields'. E.g. array(<b>'fields'</b> => ... .", 'ERROR', __LINE__, $_func_, __FILE__);$status = FALSE;break; }
$collectAllFieldNamesToCheckForUniqueness = array();foreach($theHints['fields'] as $key=>$keyAttr) {if (!is_array($keyAttr)) {Bs_Error::setError("Invalid Hint in 'fields'-block in Class [{$classname}]: Must be a hash-array.", 'ERROR', __LINE__, $_func_, __FILE__);$status = FALSE;continue;}
if (empty($keyAttr['name'])) {Bs_Error::setError("Invalid Hint in 'fields'-block in Class [{$classname}]: No valid 'name' for field[{$key}]. Add 'name' E.g. array('fields' => array(<b>'name'=>'myField{$key}'</b>... .", 'ERROR', __LINE__, $_func_, __FILE__);$status = FALSE;} elseif (bs_isReservedDbWord($keyAttr['name'])) {Bs_Error::setError("Invalid Hint in 'fields'-block in Class [{$classname}]: The field name [{$keyAttr['name']}] belongs to the reserved word list (potential conflict).", 'WARNING', __LINE__, $_func_, __FILE__);$status = FALSE;} else {if (isSet($collectAllFieldNamesToCheckForUniqueness[$keyAttr['name']])) {Bs_Error::setError("Invalid Hint in 'fields'-block in Class [{$classname}]: The field name [{$keyAttr['name']}] is already in use in your hints!.", 'WARNING', __LINE__, $_func_, __FILE__);$status = FALSE;} else {$collectAllFieldNamesToCheckForUniqueness[$keyAttr['name']] = TRUE;}
}
if (empty($keyAttr['metaType']) OR !in_array($keyAttr['metaType'], $this->_validMetaTypes)) {Bs_Error::setError("Invalid Hint in 'fields'-block of Class [{$classname}]: Invalid or missing 'metaType' for field[{$key}]. Add 'metaType'. E.g. array('fields' => array('metaType'=>'{$errStr_validMetaTypes}', ... .", 'ERROR', __LINE__, $_func_, __FILE__);$status = FALSE;continue;} 
if (in_array($keyAttr['metaType'], array('string', 'blob', 'text', 'serialize'))) {if (empty($keyAttr['size'])) {Bs_Error::setError("Invalid Hint in 'fields'-block of Class [{$classname}]: Missing 'size' for field[{$key}]. 'metaType' of {$keyAttr['metaType']} needs a 'size' attribute . E.g. array('fields' => array('metaType'=>'blob', 'size'=>1000, ... .", 'ERROR', __LINE__, $_func_, __FILE__);$status = FALSE;} elseif ($keyAttr['metaType']==='string' AND $keyAttr['size']>255) {Bs_Error::setError("Invalid Hint in 'fields'-block of Class [{$classname}]: Invalid  'size' for field[{$key}]. 'metaType' of [{$keyAttr['metaType']}] can be max. 255 char long. Used metaType 'blob'(case sensitive) or 'text'(case insensitive) for fields larger then 255 char.", 'ERROR', __LINE__, $_func_, __FILE__);$status = FALSE;}
} elseif (!empty($keyAttr['crypt'])) {Bs_Error::setError("Invalid Hint in 'fields'-block of Class [{$classname}]: Can't set 'crypt' for field[{$key}] with metaType[{$keyAttr['metaType']}]. Only 'metaType'={blob|text} can be cryptified.", 'ERROR', __LINE__, $_func_, __FILE__);$status = FALSE;}
}
} while(FALSE);return $status;}
function classVarCheck($classname, $theHints) {$_func_ = 'hintsTypeCheck';$status = TRUE;do { if (!class_exists($classname)) {Bs_Error::setError("Variable-Check: Invalid or missing Object[{$classname}]. Object[{$classname}] is either not an object or not in scope (=not included).", 'ERROR', __LINE__, $_func_, __FILE__);$status = FALSE;break; }
} while(FALSE);if (!$status)  return $status;$classVars = get_class_vars($classname);do { reset($theHints['primary']);list($key, $keyAttr) = each($theHints['primary']);if (!isSet($classVars[$key])) {Bs_Error::setError("Variable-Check: Missing variable[{$key}] in Object[{$classname}]. The variable must exsits and have a type (by setting a value). E.g. class {$classname} { var \${$key} = ''; ... }.", 'ERROR', __LINE__, $_func_, __FILE__);$status = FALSE;} else {$phpType = getType($classVars[$key]);if ($phpType!=='integer' AND $keyAttr['type']==='auto_increment') {Bs_Error::setError("Variable-Check: Type missmatch in 'primary'-block: Expected 'integer' but detected '{$phpType}' for key[{$key}].", 'WARNING', __LINE__, $_func_, __FILE__);$status = FALSE;} elseif ($phpType==='string' AND $keyAttr['type']==='auto_increment') {Bs_Error::setError("Variable-Check: Type missmatch in 'primary'-block: Expected 'string' but detected '{$phpType}' for key[{$key}].", 'WARNING', __LINE__, $_func_, __FILE__);$status = FALSE;} elseif ($keyAttr['type']==='callback' AND !in_array('bs_sop_getid', get_class_methods($classname))) {Bs_Error::setError("Methode-Check: Missing methode {$classname}::bs_sop_getId(\$sopAgent). If 'primary'-block-type is 'callback', then methode *must* exsist and return a unique string (used when object is inserted).", 'WARNING', __LINE__, $_func_, __FILE__);$status = FALSE;}
}
foreach ($theHints['fields'] as $key => $keyAttr) {if (!isSet($classVars[$key])) {Bs_Error::setError("Variable-Check: Missing variable[{$key}] in Object[{$classname}]. The variable must exsits and have a default value. E.g. class {$classname} { var \${$key} = ''; ... }.", 'ERROR', __LINE__, $_func_, __FILE__);$status = FALSE;continue;}
$phpType = getType($classVars[$key]);$expectedMetaTypes = $this->_getExpectedMetaTypes($phpType);$errStr_expectedMetaTypes = join(' or ', $expectedMetaTypes);if (!in_array($keyAttr['metaType'], $expectedMetaTypes)) {Bs_Error::setError("Variable-Check: Type missmatch in 'fields'-block: Expected [{$errStr_expectedMetaTypes}] but metaType given is '{$keyAttr['metaType']}' for key[{$key}].", 'WARNING', __LINE__, $_func_, __FILE__);$status = FALSE;}
}
} while(FALSE);return $status;}
function _getClassName($theObject) {$_func_ = '_getClassName';$status = FALSE;$err = '';do {if (@is_object($theObject)) {$classname = get_class($theObject);} elseif (@is_string($theObject)) {$classname = strToLower($theObject);if (!class_exists($classname)) {$err = "Parmeter 1 [{$theObject}] is not a defined class (not in scope, you may have to include it).";break; }
} else {$err = 'Parmeter 1 is not an object nor a string (= class name).';break; }
$status = TRUE;} while(FALSE);if (!$status) {Bs_Error::setError($err, 'ERROR', __LINE__, $_func_,__FILE__);return FALSE;}
return $classname;}
function _getClassRegistry($theObject) {$_func_='_getClassRegistry';$status = FALSE;$classname = '';do { $classname = @is_object($theObject) ? get_class($theObject) : strToLower($theObject);if (empty($this->_classRegistry[$classname])) {if (!$this->register($theObject)) {$err = "Failed to auto-register the Object[$classname]. Check previous errors and/or try to register() *before* you do any storing/loading/deleting!";break; }
}
$status = TRUE;} while(FALSE);if (!$status) {Bs_Error::setError($err, 'ERROR', __LINE__, $_func_, __FILE__);return FALSE;}
return $this->_classRegistry[$classname];}
function _getExpectedMetaTypes($phpType) {switch ($phpType) {case 'boolean':
case 'integer':
case 'double':
$expectedMetaTypes = array($phpType);break;case 'string':
case 'resource':
$expectedMetaTypes = array('string','blob','text','serialize');break;case 'array':
case 'object':
case 'NULL':
case 'unknown type':
$expectedMetaTypes = array('blob','serialize');break;default:
$expectedMetaTypes = array();}
return $expectedMetaTypes;}
function smartError($hints) {$validKeyTypes = '{'. join('|', $this->_validKeyTypes).'}';$validMetaTypes = '{'. join('|', $this->_validMetaTypes).'}';$out =<<<EOD
      <hr>HINTS Quick Info: Hints define the details on how to store the object to the underlying DB.
      The Hint-structure is of follow:
      _blocks_ - 2 main blocks 'primary' and 'fields' _/_
      _vars_ - The PHP variables you want to store _/_
      _attrs_ - The DB field attributes _/_
      _type_   -> The DB key field type, one of {$validKeyTypes}. Used for the DB field type. _/_
      _field_   -> The field names to use for the DB field name. _/_
      _meta_   -> The field metaType, one of {$validMetaTypes}. Used for the DB field type. _/_
      
      Note: 
        o 'index' is an optional attribute and is DB dependent espacialy for {blob|text|serialize} &gt; 255 char.
        o 'crypt' is an optional attribute and only works with metaType = {string|blob|text|serialize} (Note: crypted data may uses extra space).
        o if metaType = {string|blob|text|serialize} you must add the 'size' attribue (See Sample).
EOD;
$out .= "\n<hr><b>Sample:</b>\n";$out .= "array(";foreach($hints as $k1 => $val1) {$out .= "\n  _blocks_'$k1'_/_ => array(";$line = '';foreach($val1 as $k2 => $val2) {if ('array'==getType($val2)) {if (empty($line)) $line = "\n";$line .= "    _vars_'$k2'_/_ => array(";foreach($val2 as $k3 => $val3) {if (getType($val3) == 'boolean') $val3 = ($val3) ? 'TRUE' : 'FALSE';$line .= " _attrs_'$k3'_/_=>";switch ($k3) {case 'name'    : $line .=  "_field_'$val3'_/_,"; break;case 'metaType': $line .=  "_meta_'$val3'_/_,"; break;case 'type'    : $line .=  "_type_'$val3'_/_,";break;default : $line .=  "'$val3',";}
}
$line .= "),\n";} else {$line .= " _vars_'$val2'_/_,";}
}
$out .= $line;$out .= "  ),";}
$out .= "\n);";$out = strtr($out, array("\n"=>"<br>\n", ' '=>'&nbsp;'));$out = strtr($out, array('_blocks_'=>'<span style="color: navy; font-weight: bold;">', 
'_vars_'  =>'<span style="color: #9932CC; font-weight: bold;">',
'_attrs_' =>'<span style="color: blue; font-weight: bold;">',
'_field_' =>'<span style="color: green; font-weight: bold;">',
'_meta_' =>'<span style="color: Teal; font-weight: bold;">',
'_type_' =>'<span style="color: #3CB371; font-weight: bold;">',
'_/_'=>'</span>'));return '<span style="font-size:10">'. $out . '</span>';}
} function _bs_sop_lasyLoad($bsClass) {require_once($GLOBALS['APP']['path']['core'] . $bsClass);}
if (basename($_SERVER['PHP_SELF']) == 'Bs_SimpleObjPersister.class.php') {require_once($GLOBALS['APP']['path']['core'] . 'db/Bs_Db.class.php');$dsn = array ('name' => 'test', 'host' => 'localhost', 'port' => '3306', 'socket' =>'',
'user' => 'root', 'pass' => '',
'syntax' => 'mysql', 'type' => 'mysql');if (isEx($dbObject =& getDbObject($dsn))) {$dbObject->stackDump('echo');die();}
$objPersister = new Bs_SimpleObjPersister();$objPersister->setDbObject($dbObject);$objPersister->setCrypt('Some random string is good 4lsd034n545b');$my_hintHash = array (
'primary' => array (
'uniqueKey' => array('name'=>'id', 'type'=>'uniqueStr13', 'prefix'=>'x_'),
),
'fields' => array (
'myString'=> array('name'=>'aString', 'metaType'=>'string',    'size'=>25,  'index'=>TRUE, 'fulltext'=>TRUE),
'myData' => array('name'=>'a',   'metaType'=>'string', 'size'=>10,  'fulltext'=>TRUE ),
'password'=> array('name'=>'pass',    'metaType'=>'string',    'size'=>30,   'crypt'=>TRUE), 
)
);class myObjectToBePersisted {var $uniqueKey = '';var $myString = '';var $myObject = '';var $password = '';var $myData = '';var $dummy1 = 'foo'; var $dummy2 = 'bar';function myObjectToBePersisted() {$this->myString = 'hello';$this->myObject = new stdclass();$this->password = 'psssssssssssssssssst!';}
function bs_sop_getHints($sopAgent) {GLOBAL $my_hintHash;return $my_hintHash;}
function bs_sop_preStore($sopAgent, $storeVarList) {if (isSet($storeVarList['myData'])) $this->myData = $this->zip($this->myData);}
function bs_sop_postLoad($sopAgent, $loadVarList) {if (isSet($loadVarList['myData'])) $this->myData = $this->unzip($this->myData);}
function zip($x) {return 'ziped data';}
function unzip($x) {return 'unziped data';}
} do { $aObj = new myObjectToBePersisted();if (!$objPersister->store($aObj)) {   echo join('<hr>', Bs_Error::getLastErrors());break; }
if (!$objPersister->store($aObj, 'myString')) {   echo join('<hr>', Bs_Error::getLastErrors());break; }
} while (FALSE);echo $objPersister->smartError($my_hintHash);}
?>
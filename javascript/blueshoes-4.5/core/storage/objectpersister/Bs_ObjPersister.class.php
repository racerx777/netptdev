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
define('BS_OBJPERSISTER_VERSION',      '4.5.$Revision: 1.2 $');require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');define('BS_OP_BLOB_HASH_NAME',   'internalSerializedData'); define('BS_OP_FIELD_PREFIX',     'f_');                     define('BS_OP_TABLE_PREFIX',     't_');                     define('BS_OP_STREAM_PREFIX',    '**STREAM**');             function Bs_ObjPersister_require_Bs_Rc4Crypt() {require_once($GLOBALS['APP']['path']['core'] . 'crypt/Bs_Rc4Crypt.class.php');}
class Bs_ObjPersister extends Bs_Object {var $_objToPersist = NULL;var $_persistInfo = NULL;var $rc4key = 'i think that a hardcoded key is much better than nothing.';var $Bs_Rc4Crypt;function Bs_ObjPersister(&$obj, $persistHints=NULL) {parent::Bs_Object();$this->_objToPersist =& $obj;$this->_persistInfo = $this->_cleanPersistHints($persistHints);}
function setVarSettings($persistHints) {$this->_persistInfo = $this->_cleanPersistHints($persistHints);$ret=sizeOf($this->_persistInfo);return $ret==0 ? FALSE : $ret;}
function getPersistInfo() {return $this->_persistInfo;}
function _cleanPersistHints($dirtyPersistHints) {if (empty($dirtyPersistHints) OR !is_array($dirtyPersistHints)) {return array();}
$cleanedPersistHints = array();$cleanHint = array();foreach($dirtyPersistHints as $key => $dirtyHint) {$key = trim($key);if (empty($key)) continue; $cleanHint['mode'] = empty($dirtyHint['mode']) ? 'stream' : strToLower($dirtyHint['mode']);if ($cleanHint['mode'] === 'no') continue; if ($cleanHint['mode'] !== 'lonely') $cleanHint['mode'] = 'stream';$cleanHint['crypt'] = empty($dirtyHint['crypt']) ? FALSE : (boolean) ($dirtyHint['mode'] == TRUE);$cleanHint['index'] = empty($dirtyHint['index']) ? FALSE : (boolean) ($dirtyHint['index'] == TRUE);if ($cleanHint['mode'] !== 'lonely') { $cleanHint['index'] = FALSE;$cleanHint['name']  = $key;$cleanHint['metaType']  = 'stream';} else {            $reservedWordCheck = FALSE;if (empty($dirtyHint['name'])) {$cleanHint['name'] = strToUpper($key);$reservedWordCheck = TRUE;} else {$cleanHint['name'] =  ($dirtyHint['name'] === TRUE) ? strToUpper($key) : strToUpper(trim($dirtyHint['name']));}
if ($reservedWordCheck AND $this->isReservedWord($cleanHint['name'])) {$cleanHint['name'] =  strToUpper(BS_OP_FIELD_PREFIX . $cleanHint['name']);}
$autodetectMetaType = FALSE;$cleanHint['metaType'] = empty($dirtyHint['metaType']) ?  '?' : strToLower($dirtyHint['metaType']);if (!in_array($cleanHint['metaType'], array('integer', 'double', 'string', 'blob' , 'boolean', 'datetime', 'stream'))) {if (!@isSet($this->_objToPersist->$key)) { $cleanHint['metaType'] = 'stream';} else {                                   $phpType = getType($this->_objToPersist->$key);switch ($phpType) {case 'boolean':
case 'integer':
case 'double':
$cleanHint['metaType'] = $phpType;break;case 'string':
case 'resource':
$cleanHint['metaType'] = 'string';break;case 'array':
case 'object':
case 'NULL':
case 'unknown type':
$cleanHint['metaType'] = 'stream';break;default:
$cleanHint['metaType'] = 'stream';}
}
}
} $cleanedPersistHints[$key] = array_merge($dirtyPersistHints[$key], $cleanHint);}
return $cleanedPersistHints;}
function _getKeyName($varName, $nameSetting) {if ($nameSetting === TRUE) {return $varName;} elseif (is_string($nameSetting)) {return $nameSetting;} else {return BS_OP_FIELD_PREFIX . $varName;}
}
function &_getPersistData() {$this->_persistTrigger();$persistData = array();$persistInfo = &$this->getPersistInfo();reset($persistInfo);while (list($key) = each($persistInfo)) {$value = $this->_objToPersist->$key;if ($persistInfo[$key]['crypt']) $value = $this->_encrypt($value);switch ($persistInfo[$key]['mode']) {case 'lonely':
switch ($persistInfo[$key]['metaType']) {case 'stream':
$persistData[$key] = BS_OP_STREAM_PREFIX . serialize($value);break;default:
$persistData[$key] = $value;}
break;case 'stream':
$persistData[BS_OP_BLOB_HASH_NAME][$key] = $value;break;}
}
if (isSet($persistData[BS_OP_BLOB_HASH_NAME]))
$persistData[BS_OP_BLOB_HASH_NAME] = BS_OP_STREAM_PREFIX . serialize($persistData[BS_OP_BLOB_HASH_NAME]);if ((!isSet($persistData['persisterID'])) && (isSet($this->_objToPersist->persisterID))) 
$persistData['ID'] = $this->_objToPersist->persisterID;return $persistData;}
function _setPersistData(&$dataArray) {if (is_array($dataArray)) {reset($dataArray);$prefixLength = strlen(BS_OP_STREAM_PREFIX);$persistInfo  = &$this->getPersistInfo();while (list($key) = each($dataArray)) {if ($key == BS_OP_BLOB_HASH_NAME) {if (strpos($dataArray[$key], BS_OP_STREAM_PREFIX) === 0) {$t = unserialize(substr($dataArray[$key], $prefixLength));if (is_array($t)) {while (list($k) = each($t)) {$value = $this->_setPersistDataHelper($persistInfo[$k]['crypt'], $t[$k]); $this->_objToPersist->$k = $value;}
}
}
} elseif ($key == 'ID') {$this->setPersisterID($dataArray[$key]); } elseif (strpos($dataArray[$key], BS_OP_STREAM_PREFIX) === 0) {$value = $this->_setPersistDataHelper($persistInfo[$key]['crypt'], substr($dataArray[$key], $prefixLength)); $this->_objToPersist->$key = unserialize($value);} else {$value = $this->_setPersistDataHelper($persistInfo[$key]['crypt'], $dataArray[$key]); switch ($persistInfo[$key]['metaType']) {case 'integer':
$this->_objToPersist->$key = (int)$value;break;case 'boolean':
$this->_objToPersist->$key = (bool)$value;break;default:
$this->_objToPersist->$key = $value;}
}
}
}
$this->_unpersistTrigger();}
function _setPersistDataHelper($crypt, $value) {if ($crypt) {return $this->_decrypt($value);}
return $value;}
function _encrypt($param) {if (!is_object($this->Bs_Rc4Crypt)) {Bs_ObjPersister_require_Bs_Rc4Crypt();$this->Bs_Rc4Crypt = &$GLOBALS['Bs_Rc4Crypt'];}
return $this->Bs_Rc4Crypt->crypt($this->rc4key, serialize($param));}
function _decrypt($param) {if (!is_object($this->Bs_Rc4Crypt)) {Bs_ObjPersister_require_Bs_Rc4Crypt();$this->Bs_Rc4Crypt = &$GLOBALS['Bs_Rc4Crypt'];}
return unserialize($this->Bs_Rc4Crypt->crypt($this->rc4key, $param));}
function _persistTrigger() {if (method_exists($this->_objToPersist, 'prePersistTrigger')) {$this->_objToPersist->persistTrigger();}
}
function _unpersistTrigger() {if (method_exists($this->_objToPersist, 'postUnpersistTrigger')) {$this->_objToPersist->unpersistTrigger();}
}
function setPersisterID(&$persisterID) {if (method_exists($this->_objToPersist, 'setPersisterID')) {$this->_objToPersist->setPersisterID((int)$persisterID);} else {$this->_objToPersist->persisterID = (int)$persisterID;}
}
function persist() {return new Bs_Exception($msg = 'persist() is an abstract function! It must be overloded.', __FILE__, __LINE__, NULL, 'fatal');}
function unPersist() {return new Bs_Exception($msg = 'unPersist() is an abstract function! It must be overloded.', __FILE__, __LINE__, NULL, 'fatal');}
function isReservedWord($word) {$word = strToLower(trim($word));static $discourage = array('action', 'bit', 'date', 'enum', 'no', 'text', 'time', 'timestamp');if (in_array($word, $discourage)) {return 1;}
static $disallowed = array (
'action',           'add',              'aggregate',        'all',
'alter',            'after',            'and',              'as',
'asc',              'avg',              'avg_row_length',   'auto_increment',
'between',          'bigint',           'bit',              'binary',
'blob',             'bool',             'both',             'by',
'cascade',          'case',             'char',             'character',
'change',           'check',            'checksum',         'column',
'columns',          'comment',          'constraint',       'create',
'cross',            'current_date',     'current_time',     'current_timestamp',
'data',             'database',         'databases',        'date',
'datetime',         'day',              'day_hour',         'day_minute',
'day_second',       'dayofmonth',       'dayofweek',        'dayofyear',
'dec',              'decimal',          'default',          'delayed',
'delay_key_write',  'delete',           'desc',             'describe',
'distinct',         'distinctrow',      'double',           'drop',
'end',              'else',             'escape',           'escaped',
'enclosed',         'enum',             'explain',          'exists',
'fields',           'file',             'first',            'float',
'float4',           'float8',           'flush',            'foreign',
'from',             'for',              'full',             'function',
'global',           'grant',            'grants',           'group',
'having',           'heap',             'high_priority',    'hour',
'hour_minute',      'hour_second',      'hosts',            'identified',
'ignore',           'in',               'index',            'infile',
'inner',            'insert',           'insert_id',        'int',
'integer',          'interval',         'int1',             'int2',
'int3',             'int4',             'int8',             'into',
'if',               'is',               'isam',             'join',
'key',              'keys',             'kill',             'last_insert_id',
'leading',          'left',             'length',           'like',
'lines',            'limit',            'load',             'local',
'lock',             'logs',             'long',             'longblob',
'longtext',         'low_priority',     'max',              'max_rows',
'match',            'mediumblob',       'mediumtext',       'mediumint',
'middleint',        'min_rows',         'minute',           'minute_second',
'modify',           'month',            'monthname',        'myisam',
'natural',          'numeric',          'no',               'not',
'null',             'on',               'optimize',         'option',
'optionally',       'or',               'order',            'outer',
'outfile',          'pack_keys',        'partial',          'password',
'precision',        'primary',          'procedure',        'process',
'processlist',      'privileges',       'read',             'real',
'references',       'reload',           'regexp',           'rename',
'replace',          'restrict',         'returns',          'revoke',
'rlike',            'row',              'rows',             'second',
'select',           'set',              'show',             'shutdown',
'smallint',         'soname',           'sql_big_tables',   'sql_big_selects',
'sql_low_priority_updates','sql_log_off',      'sql_log_update',   'sql_select_limit',
'sql_small_result', 'sql_big_result',   'sql_warnings',     'straight_join',
'starting',         'status',           'string',           'table',
'tables',           'temporary',        'terminated',       'text',
'then',             'time',             'timestamp',        'tinyblob',
'tinytext',         'tinyint',          'trailing',         'to',
'type',             'use',              'using',            'unique',
'unlock',           'unsigned',         'update',           'usage',
'values',           'varchar',          'variables',        'varying',
'varbinary',        'with',             'write',            'when',
'where',            'year',             'year_month',       'zerofill'
);if (in_array($word, $disallowed)) {return 2;}
return 0;}
}
if (basename($_SERVER['PHP_SELF']) == 'Bs_ObjPersister.class.php') {$testObj_persistHints = array(
'aInt'    => array('mode'=>'lonely', 'metaType'=>'integer', 'index'=>TRUE, 'name'=>'myInt'), 'bInt'    => array('mode'=>'stream', 'someExtraInfo'=>'foo', 'index'=>TRUE),                 'cInt'    => array('mode'=>'no'),                                                            'aString' => array('mode'=>'lonely',  'index'=>TRUE),                 'aBool'   => array('mode'=>'lonely', 'crypt'=>1, 'name'=>TRUE),                              'aArray'  => array(), 
'password'=> array('mode'=>'lonely', 'metaType'=>'string'), 
'aObj'    => array('mode'=>'stream'), 
);class testObj {var $aInt = 0;var $bInt = 0;var $cInt = 0;var $aBool = true;var $aString = 'default';var $aArray = array();var $password = '';var $aObj = NULL;function testObj() {GLOBAL $testObj_persistHints;$this->persister =& new Bs_ObjPersister($this, $testObj_persistHints);}
}
$tO = new testObj();}
?>
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
define('BS_OODBBASICS_VERSION',      '4.5.$Revision: 1.2 $');if (!isSet($GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'])) $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] = '../';require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');define('BS_OODB_STREAM_DEFAULT_NAME',  'defaultStream');define('BS_OODB_STREAM_PREFIX',   '***STREAM***');class Bs_OoDbBasics extends Bs_Object {var $pObjToPersist = NULL;var $pObjName = '';var $pDefaultScope = '';var $pStorageAutoCreate = FALSE;function Bs_OoDbBasics($fileName) {parent::Bs_Object($fileName);static $pObjPutInStorage       = array();static $pObjFetchedFromStorage = array();static $pErrorStore  = array();static $pErrorFetch  = array();static $pErrorDelete = array();$this->pObjPutInStorage       = &$pObjPutInStorage;$this->pObjFetchedFromStorage = &$pObjFetchedFromStorage;$this->pErrorStore   = &$pErrorStore;$this->pErrorFetch   = &$pErrorFetch;$this->pErrorDelete  = &$pErrorDelete;}
function _setObject(&$pObj,  $scope='') {unSet($this->pObjToPersist); $this->pObjToPersist = &$pObj;$this->pObjName = get_class($pObj);$this->pDefaultScope = $scope;unSet($this->pMetaData);       $this->pMetaData       = array();unSet($this->pInfoList);       $this->pInfoList       = array();unSet($this->pDataHasChanged); $this->pDataHasChanged = TRUE;unSet($this->pObjToPersist->_persistErrorStore); $this->pObjToPersist->_persistErrorStore = array();unSet($this->pObjToPersist->_persistErrorFetch); $this->pObjToPersist->_persistErrorFetch = array();unSet($this->pObjToPersist->_persistErrorDelete); $this->pObjToPersist->_persistErrorDelete = array();$this->pObjToPersist->_persistLastStatus = array('stored'=>FALSE, 'fetched'=>FALSE);}
var $pMetaData = NULL;var $pInfoList = NULL;var $pDataHasChanged = TRUE;function &_getOoDbProperty($useCach=FALSE) {$_ooDbProperty = &$this->pObjToPersist->_ooDbProperty;if (!isSet($_ooDbProperty)) $_ooDbProperty = array();if ($useCach AND (!empty($_ooDbProperty))) {return $_ooDbProperty;}
reset($_ooDbProperty);while (list($varName) = each($_ooDbProperty)) {switch ($_ooDbProperty[$varName]['mode']) {case 'lonely':
$_ooDbProperty[$varName] = array_merge(array('metaType'=>'string', 'index'=>FALSE, 'readOnly'=>FALSE, 'ignor'=>FALSE), $_ooDbProperty[$varName]);break;case 'stream':
$_ooDbProperty[$varName] = array_merge(array('streamName'=>'', 'readOnly'=>FALSE, 'ignor'=>FALSE), $_ooDbProperty[$varName]);if (empty($_ooDbProperty[$varName]['streamName'])) $_ooDbProperty[$varName]['streamName'] = BS_OODB_STREAM_DEFAULT_NAME;break;case 'object':   $_ooDbProperty[$varName] = array_merge(array('reference'=>'strong', 'useScope'=>'', 'readOnly'=>FALSE, 'ignor'=>FALSE), $_ooDbProperty[$varName]);break;}
}
$scanedVars = array();$tmpVars = get_object_vars($this->pObjToPersist);while(list($varName) = each($tmpVars)) {$phpType = getType($tmpVars[$varName]);$t = substr($varName, -2); if ($t === '_s') {$scanedVars[$varName] = array('mode'=>'stream', 'streamName'=>BS_OODB_STREAM_DEFAULT_NAME);} elseif ($t === '_p') {switch ($phpType) {case 'boolean':
case 'integer':
case 'double':
$mode = 'lonely';  $metaType = $phpType;break;case 'string':
case 'resource':
$mode = 'lonely';  $metaType = 'string';break;case 'array':
case 'object':
case 'NULL':
case 'unknown type':
$mode = 'stream';  $streamName = $varName;break;default:
$mode = 'stream';  $streamName = BS_OODB_STREAM_DEFAULT_NAME;}
if ($mode=='lonely') {$scanedVars[$varName] = array('mode'=>$mode, 'metaType'=>$metaType, 'index'=>FALSE);} elseif ($mode=='stream') {$scanedVars[$varName] = array('mode'=>$mode, 'streamName'=>$streamName);}
}
}  reset ($scanedVars);while(list($varName) = each($scanedVars)) {if (empty($_ooDbProperty[$varName])) $_ooDbProperty[$varName] = $scanedVars[$varName];}
return $_ooDbProperty;}
function _prepareToPersistData($useCach=TRUE) {if ($useCach AND (!empty($this->pInfoList))) {return TRUE;}
$this->pInfoList = array('lonely'=>array(), 'object'=>array(), 'ignored'=>array()); $this->pMetaData = array();$pInfoList = &$this->pInfoList;$pMetaData = &$this->pMetaData;$toSerializeData = array();   $_ooDbProperty = &$this->_getOoDbProperty($useCach=FALSE);reset($_ooDbProperty);while (list($varName) = each($_ooDbProperty)) {if ($varName==='ID') continue; $varProperty = &$_ooDbProperty[$varName];$theData = &$this->pObjToPersist->$varName;if ($varProperty['ignor']) {$pInfoList['ignor!'][$varName]['data'] = '';$pInfoList['ignor!'][$varName]['property'] = &$varProperty;continue;}
if ($varProperty['readOnly']) {$pInfoList['readOnly!'][$varName]['data'] = &$theData;$pInfoList['readOnly!'][$varName]['property'] = &$varProperty;continue;}
if ((!isSet($theData)) OR is_null($theData)) {continue;}
$mode = &$varProperty['mode'];switch($mode) {case 'lonely':
$pMetaData[$varName] = &$theData;$pInfoList[$mode][$varName]['data'] = &$theData;$pInfoList[$mode][$varName]['property'] = &$varProperty;break;case 'stream':
$toSerializeData[$varProperty['streamName']][$varName] = &$theData;break;default: $pInfoList[$mode][$varName]['data']    = &$theData; $pInfoList[$mode][$varName]['property'] = &$varProperty;}
}
if (sizeOf($toSerializeData)>0) {reset($toSerializeData);while (list($streamName) = each($toSerializeData)) {$pMetaData[$streamName] = BS_OODB_STREAM_PREFIX . serialize($toSerializeData[$streamName]);$pInfoList['lonely'][$streamName]['data']    = &$pMetaData[$streamName];$pInfoList['lonely'][$streamName]['property'] = array('mode' =>'lonely','metaType'=>'blob');}
}
do { $nothingToSave = TRUE;$nothingToSave = ($nothingToSave AND (sizeOf($pInfoList['lonely'])==0));$nothingToSave = ($nothingToSave AND (sizeOf($pInfoList['object'])==0)); if ($nothingToSave) {$this->pDataHasChanged = FALSE;break; }
$pObjTagInfo     = &$this->getPersistTag($this->pObjToPersist);$pMetaData['ID'] = &$pObjTagInfo['ID'];$pInfoList['lonely']['ID']['data']    = &$pObjTagInfo['ID'];$pInfoList['lonely']['ID']['property'] = array('mode' =>'lonely', 'metaType'=>'integer', 'index'=>'TRUE');if ($pObjTagInfo['forceSave'] OR empty($pObjTagInfo['md5'])) {$this->pDataHasChanged = TRUE;} else {if ($pObjTagInfo['md5'] === $this->_calcMd5Fingerprint($pMetaData)) {$this->pDataHasChanged = FALSE;} else {$this->pDataHasChanged = TRUE;}
} 
} while (FALSE); }
function _reassembleObjectMetaData(&$metaData) {if (empty($metaData['ID'])) {$errStr = "In _reassembleObjectMetaData(). NO ID given. \$metaData['ID'] was empty";$this->_setError_Fetch('ID', $errStr);$objID = 0;} else {$objID = (int)$metaData['ID'];}
$pObjTagInfo = &$this->getPersistTag($this->pObjToPersist);$pObjTagInfo['ID'] = $objID;$prefixLength = strlen(BS_OODB_STREAM_PREFIX);reset($metaData);while (list($varName) = each($metaData)) {if ($varName === 'ID') continue;$data = &$metaData[$varName];if (is_string($data) AND strpos($data, BS_OODB_STREAM_PREFIX) === 0) {$t = unserialize(substr($data, $prefixLength));if (is_array($t)) {while (list($varName) = each($t)) {$this->pObjToPersist->$varName = $t[$varName];}
}
continue;} else {$this->pObjToPersist->$varName = $data;}
}
$pObjTagInfo['md5'] = $this->_calcMd5Fingerprint($metaData);}
var $pDefaultScope = '';function getID(&$object, $scope='') {$objTag = &$this->getPersistTag($object, $scope);return $objTag['ID'];}
function setID($ID, &$object, $scope='') {$objTag = &$this->getPersistTag($object, $scope);$objTag['ID'] =  $ID;$this->pDefaultScope = $scope;}
function getScope(&$object) {if (!empty($object->_ooDbScope)) {$scope = $object->_ooDbScope;} else {$scope = $this->pDefaultScope;}
if ($this->getStorageScope()==$scope) $scope='';return $scope;}
function setHardScope(&$object, $scope='') {if (is_string($scope)) {$object->_ooDbScope = $scope;}
}
function forceSave(&$object, $force=TRUE) {$objTag = &$this->getPersistTag($object);$objTag['forceSave'] = $force;}
function &getPersistTag(&$object, $scope='') {$scope = empty($scope) ? $this->getScope($object) : '';$tagKey = empty($scope) ? 'this' : $scope;$objTag = &$object->_persistTag;if (!isSet($objTag[$tagKey])) {$objTag[$tagKey] = array();}
$objTag[$tagKey] = array_merge(array('ID'=>0, 'md5'=>'', 'forceSave'=>FALSE), $objTag[$tagKey]);return $objTag[$tagKey];}
var $pObjPutInStorage;var $pObjFetchedFromStorage;function _memorizeObjStored(&$object) {if (empty($this->pObjToPersist) OR (!is_object($this->pObjToPersist))) return;$pObjTagInfo = &$this->getPersistTag($object);$objID = $pObjTagInfo['ID'];$scope = $this->getScope($object);$targetID = (empty($scope)) ? '' : $scope . '.';$targetID .= $this->pObjName .':'. $objID;if (!isSet($object->_persistObjStored)) $this->pObjToPersist->_persistObjStored = array();$object->_persistObjStored[$targetID] = TRUE;$this->pObjPutInStorage[] = &$object;}
function _isObjAlreadyStored(&$object) {$pObjTagInfo = &$this->getPersistTag($object);$objID = $pObjTagInfo['ID'];$scope = $this->getScope($object);$targetID = (empty($scope)) ? '' : $scope . '.';$targetID .= $this->pObjName .':'. $objID;if (isSet($object->_persistObjStored[$targetID])) {return  $object->_persistObjStored[$targetID];} else {return FALSE;}
}
function _unMemorizeObjStored() {$size = sizeOf($this->pObjPutInStorage);for ($i=0; $i<$size; $i++) {$this->pObjPutInStorage[$i]->_persistObjStored = array();}
$this->pObjPutInStorage = array();}
function _memorizeObjFetched(&$object) {if (empty($object) OR (!is_object($object))) return;$pObjTagInfo = &$this->getPersistTag($object);$objID = $pObjTagInfo['ID'];$scope = $this->getScope($object);$sourceID = (empty($scope)) ? '' : $scope . '.';$sourceID .= get_class($object) .':'. $objID;$this->pObjFetchedFromStorage[$sourceID] = &$object;}
function &_getObjAlreadyFetched($className, $objID, $scope) {$sourceID = (empty($scope)) ? '' : $scope . '.';$sourceID .= $className .':'. $objID;if (isSet($this->pObjFetchedFromStorage[$sourceID])) {$ret = &$this->pObjFetchedFromStorage[$sourceID];} else {$ret = FALSE;}
return $ret;}
function _unMemorizeObjFetched() {$this->pObjFetchedFromStorage = array();}
function &_calcMd5Fingerprint(&$pMetaData) {if (empty($pMetaData)) {$fingerprint = '';} else {ksort($pMetaData, SORT_STRING);$fingerprint = md5(serialize($pMetaData));}
return $fingerprint;}
function &_flattenObjArray(&$objTree, &$objList, $hashPrefix='', $treeDepth=0) {if (empty($objTree)) return;do { if ($treeDepth>7) break;              if ($treeDepth>0) $hashPrefix .= ';';reset($objTree);while (list($key) = each($objTree)) {$newHash = $hashPrefix . $key;$node = &$objTree[$key];$phpType = getType($node);switch ($phpType) {case 'array':
$this->_flattenObjArray($node, $objList, $newHash, $treeDepth+1);break;case 'object':
$objList[$newHash] = &$node;break;}
}
} while(FALSE);return $objList;}
function &flattenObjArray(&$objTree) {if (empty($objTree)) return array();if (is_object($objTree)) return array(&$objTree);$objList = array();return $this->_flattenObjArray($objTree, $objList);}
function &_getStreamFields($useCach=FALSE) {$streamFields = array();$_ooDbProperty = &$this->_getOoDbProperty($useCach);reset($_ooDbProperty);while(list($varName) = each($_ooDbProperty)) {if (empty($_ooDbProperty[$varName]['streamName'])) continue;$streamName = &$_ooDbProperty[$varName]['streamName'];$streamFields[$streamName] = array('mode' =>'lonely','metaType'=>'blob');}
return $streamFields;}
function &getFieldNames($useCach=TRUE) {$ooDbProperty = &$this->_getOoDbProperty($useCach);$fieldNames = array();reset($ooDbProperty);while(list($varName) = each($ooDbProperty)) {if ($ooDbProperty[$varName]['mode']!=='lonely') continue;$fieldNames[$varName] = &$ooDbProperty[$varName];}
if (!isSet($fieldNames['ID'])) $fieldNames['ID'] = array('mode'=>'lonely', 'metaType'=>'integer', 'index'=>TRUE);$streamFields = $this->_getStreamFields($useCach);$fieldNames = array_merge($fieldNames, $this->_getStreamFields($useCach));return $fieldNames;}
var $pErrorStore;var $pErrorFetch;var $pErrorDelete;function _setError_Store($varName, &$error, $type='error') {$this->pObjToPersist->_persistErrorStore[$varName][] = $error;$className = (empty($this->pObjName)) ?  'UNKNOWN' : $this->pObjName;$this->pErrorStore[] = array('className'=>$className , 'varName'=>$varName , 'msg'=>$error);}
function _setError_Fetch($varName, &$error, $type='error') {$this->pObjToPersist->_persistErrorFetch[$varName][] = $error;$className = (empty($this->pObjName)) ?  'UNKNOWN' : $this->pObjName;$this->pErrorFetch[] = array('className'=>$className , 'varName'=>$varName , 'msg'=>$error);}
function _setError_Delete($varName, &$error, $type='error') {$this->pObjToPersist->_persistErrorDelete[$varName][] = $error;$className = (empty($this->pObjName)) ?  'UNKNOWN' : $this->pObjName;$this->pErrorDelete[] = array('className'=>$className , 'varName'=>$varName, 'msg'=>$error);}
function &getError_Persist($all=FALSE) {$ret = FALSE;if ($all) {if (sizeOf($this->pErrorStore)) $ret = &$this->pErrorStore;} else {if (sizeOf($this->pObjToPersist->_persistErrorStore)) $ret = &$this->pObjToPersist->_persistErrorStore;}
return $ret;}
function &getError_Unpersist($all=FALSE) {$ret = FALSE;if ($all) {if (sizeOf($this->pErrorFetch)) $ret = &$this->pErrorFetch;} else {if (sizeOf($this->pObjToPersist->_persistErrorFetch)) $ret = &$this->pObjToPersist->_persistErrorFetch;}
return $ret;}
function &getError_Delete($all=FALSE) {$ret = FALSE;if ($all) {if (sizeOf($this->pErrorDelete)) $ret = &$this->pErrorDelete;} else {if (sizeOf($this->pObjToPersist->_persistErrorDelete)) $ret = &$this->pObjToPersist->_persistErrorDelete; else $ret = FALSE;}
return $ret;}
function &errorDump() {$storeSize = sizeOf($this->pErrorStore);$out = '';$out .= '<strong>Store Errors</strong>';$out .= '<table border=1>';if ($storeSize==0) {$out .= '<tr><td>None</td></tr>';} else {for ($i=0; $i<$storeSize; $i++)  {$out .= '<tr><td>';$out .= 'In ' . $this->pErrorStore[$i]['className'] . ':' . $this->pErrorStore[$i]['varName'];$out .= '</td><td>';$msg = &$this->pErrorStore[$i]['msg'];if (isEx($msg)) {$out .= $msg->_toHtml();} else {$out .= $msg;}
$out .= '</td></tr>';}
}
$out .= '</table>';$out .= '<strong>Fetch Errors</strong>';$out .= '<table border=1>';$fetchSize = sizeOf($this->pErrorFetch);if ($fetchSize==0) {$out .= '<tr><td>None</td></tr>';} else {for ($i=0; $i<$fetchSize; $i++)  {$out .= '<tr><td>';$out .= 'In ' . $this->pErrorFetch[$i]['className'] . ':' . $this->pErrorFetch[$i]['varName'];$out .= '</td><td>';$msg = &$this->pErrorFetch[$i]['msg'];if (isEx($msg)) {$out .= $msg->_toHtml();} else {$out .= $msg;}
$out .= '</td></tr>';}
}
$out .= '</table>';$out .= '<strong>Delete Errors</strong>';$out .= '<table border=1>';$delSize = sizeOf($this->pErrorDelete);if ($delSize==0) {$out .= '<tr><td>None</td></tr>';} else {for ($i=0; $i<$delSize; $i++)  {$out .= '<tr><td>';$out .= 'In ' . $this->pErrorDelete[$i]['className'] . ':' . $this->pErrorDelete[$i]['varName'];$out .= '</td><td>';$msg = &$this->pErrorDelete[$i]['msg'];if (isEx($msg)) {$out .= $msg->_toHtml();} else {$out .= $msg;}
$out .= '</td></tr>';}
}
$out .= '</table>';return $out;}
}
?>

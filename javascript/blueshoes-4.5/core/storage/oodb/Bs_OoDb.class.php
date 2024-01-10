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
define('BS_OODB_VERSION',      '4.5.$Revision: 1.2 $');if (!isSet($GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'])) $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] = '../';require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'storage/oodb/Bs_OoDbBasics.class.php');class Bs_OoDb extends Bs_OoDbBasics {function Bs_OoDb() {parent::Bs_OoDbBasics(__FILE__);}
function &softDelete($class, $classID=-1, $scope='', $firstCall=TRUE) {$status = TRUE;$ret    = FALSE;$transactionId = 'OoDb:softDelete';do { $tryBlock = 1;$className = '';if (is_object($class)) {$className = get_class($class);if ($classID == -1) {$_persistTag = &$this->getPersistTag($class);$classID = $_persistTag['ID'];}
} elseif (is_string($class)) {$className = strToLower($class);}
if (empty($className) OR (!is_numeric($classID)) OR ($classID<=0)) {$errStr  = 'In softDelete($class, $classID, $scope, $firstCall).'."Invalid class/className:'{$className}' OR classID:'{$classID}'".basename(__FILE__).':'.__LINE__;$this->_setError_Delete("Object[{$className}]", $errStr);$status = $ret = FALSE;break $tryBlock;}
$cihParents = &$this->fetchCihParents($className, $classID, $reference='strong', $scope);if (!empty($cihParents) AND is_array($cihParents)) {$status = TRUE;$ret    = $cihParents;break $tryBlock;} 
if (!is_array($cihParents)) {$errStr  = 'In softDelete($className, $classID, $scope, $firstCall). Exception calling fetchCihParents().'.basename(__FILE__).':'.__LINE__;$this->_setError_Delete("Object[{$this->pObjName}]", $errStr);$this->_setError_Delete("Object[{$this->pObjName}]", $cihParents);$status = $ret = FALSE;break $tryBlock;}
$cihChildren = &$this->fetchCihChildren($className, $classID, $reference='', $scope);if (!is_array($cihChildren)) {$errStr  = 'In softDelete($className, $classID, $scope, $firstCall). Exception calling fetchCihChildren().'.basename(__FILE__).':'.__LINE__;$this->_setError_Delete("Object[{$this->pObjName}]", $errStr);if (isEx($cihChildren)) {$this->_setError_Delete("Object[{$this->pObjName}]", $cihChildren);}
$status = FALSE;break $tryBlock;}
if ($firstCall) {if (sizeOf($cihChildren)) $this->startTransaction($transactionId);}
$delStatus = &$this->deleteObjectData($className, $classID, $scope);if ($delStatus!==TRUE) {$errStr  = 'In softDelete($className, $classID, $scope, $firstCall). Exception calling deleteObjectData().'.basename(__FILE__).':'.__LINE__;$this->_setError_Delete("Object[{$this->pObjName}]", $errStr);$this->_setError_Delete("Object[{$this->pObjName}]", $delStatus);$status = FALSE;break $tryBlock;}
$tryBlock++;$relSize = sizeOf($cihChildren);for ($i=0; $i<$relSize; $i++) {$cih = &$cihChildren[$i];if ($cih['readOnly']>0) continue;$ret = &$this->softDelete($cih['chieldClass'], $cih['chieldID'], $cih['chieldScope'], $firstCall=FALSE);if ($ret!==TRUE) {$errStr  = 'In softDelete(). Exception in recursive call to softDelete(). See previous errors.'.basename(__FILE__).':'.__LINE__;$this->_setError_Delete("Object[{$this->pObjName}]", $errStr);$status = FALSE;break $tryBlock;}
}
$tryBlock--;$status = $ret = TRUE;} while(FALSE); if ($firstCall) {if ($status) {    $this->commit($transactionId);} else {          $this->rollback($transactionId);}
}
return $ret;}
function persist(&$obj, $scope='', $firstCall=TRUE) {$status = FALSE;$ret    = 0;$transactionId = 'OoDb:persist';do { $tryBlock = 1;if (!is_object($obj)) {$errStr = 'In persist (). The passed parameter is not an object! It\'s of type \''.getType($obj).'\'. ' . __FILE__ .':'.__LINE__ ;$this->_setError_Store('Object[?]', $errStr);break $tryBlock;}
$pObjName = get_class($obj);$this->_setObject($obj, $scope);$_persistTag = &$this->getPersistTag($obj);if ($this->_isObjAlreadyStored($obj)) {$ret = $_persistTag['ID'];$status = TRUE;break $tryBlock;}
$this->_prepareToPersistData($useCach=FALSE);$_ooDbProperty = &$this->_getOoDbProperty($useCach=TRUE);$scope = $this->getScope($obj);$lonelyList = &$this->pInfoList['lonely'];$objectList = &$this->pInfoList['object'];if ($firstCall) {if (!is_object($obj)) {user_error('In persist(). The object to persist is of type ['.getType($obj).']. A valid object must be given first.', E_USER_ERROR);}
$this->_unMemorizeObjStored();if (sizeOf($objectList)) {$this->startTransaction($transactionId);}
}
if ($this->pDataHasChanged) {    $objID = $this->storeObjectData($lonelyList, $pObjName, $scope);if (isEx($objID)) {$errStr = "In persist(). Call storeObjectData() failed with exception. " . basename(__FILE__) . ':' .  __LINE__;$this->_setError_Store('Object['.$pObjName.']', $errStr);$this->_setError_Store('Object['.$pObjName.']', $objID);$ret = $status = FALSE;break $tryBlock;}
if (is_numeric($objID)) {$_persistTag['ID'] = (int)$objID;$_persistTag['md5'] = $this->_calcMd5Fingerprint($this->pMetaData);$this->_memorizeObjStored($obj);} else {$errStr = "In persist(). Call storeObjectData() failed with invalid object ID:'{$objID}' of type '" . getType($objID) ."' " . basename(__FILE__) . ':' .  __LINE__;$this->_setError_Store('Object['.$pObjName.']', $errStr);$ret = $status = FALSE;break $tryBlock;}
}
if (sizeOf($objectList)==0) {$ret = $_persistTag['ID'];$status = TRUE;break $tryBlock;}
$clearInHouseData = array();$currentChieldObjects = array();$tryBlock++;reset($objectList);while (list($varName) = each($objectList)) {$tmp = &$objectList[$varName]['data'];if (is_array($tmp)) {$is_arrayOfObjects = TRUE;$arrayOfObjects = &$tmp;} else {$is_arrayOfObjects = FALSE;$arrayOfObjects = array(&$tmp);}
if (empty($arrayOfObjects)) {  continue;}
$flatObjArray = &$this->flattenObjArray($arrayOfObjects);$tryBlock++;reset($flatObjArray);while (list($hashKey) = each($flatObjArray)) {$chieldObjToStore = &$flatObjArray[$hashKey];if (empty($chieldObjToStore) OR (!is_object($chieldObjToStore))) {continue;}
$persister = $this; $toUseScope = empty($_ooDbProperty[$varName]['useScope']) ? $scope : $_ooDbProperty[$varName]['useScope'];$chieldObjID = $persister->persist($chieldObjToStore, $toUseScope, $firstCall=FALSE);if ($chieldObjID===FALSE) {if ($is_arrayOfObjects) {$errStr = "In persist(); recursive call to persist(). Failed to store object-array with varName:'{$varName}['{$hashKey}']' classname :'".get_class($chieldObjToStore)."' " . basename(__FILE__) . ':' .  __LINE__;} else {$errStr = "In persist(); recursive call to persist(). Failed to store object with varName:'{$varName}' classname :'".get_class($chieldObjToStore)."' " . basename(__FILE__) . ':' .  __LINE__;}
$this->_setError_Store($varName, $errStr);$status = FALSE;break $tryBlock;} elseif ($chieldObjID==0) { continue;}
$chieldScope = $this->getScope($chieldObjToStore);$ii = sizeOf($clearInHouseData);$clearInHouseData[$ii]['parentClass']  = $pObjName;$clearInHouseData[$ii]['parentID']     = $_persistTag['ID'];$clearInHouseData[$ii]['varName']      = $varName;$clearInHouseData[$ii]['chieldScope']  = $chieldScope;$clearInHouseData[$ii]['chieldClass']  = $persister->pObjName;$clearInHouseData[$ii]['chieldID']     = $chieldObjID;$clearInHouseData[$ii]['hashKey']      = ($is_arrayOfObjects) ? $hashKey : '';$clearInHouseData[$ii]['readOnly']     = ($_ooDbProperty[$varName]['readOnly']) ? 1 : 0;$clearInHouseData[$ii]['weakRef']      = (empty($chieldScope) AND ($_ooDbProperty[$varName]['reference']==='strong')) ? 0 : 1;$currentChieldObjects["{$chieldScope};{$persister->pObjName};{$chieldObjID}"] = &$clearInHouseData[$ii];} $tryBlock--;} $tryBlock--;$cihChildren = &$this->fetchCihChildren($pObjName, $_persistTag['ID'], $reference='', $scope);if (!is_array($cihChildren)) {$errStr = "In persist(). Call fetchCihChildren() failed with exception. " . basename(__FILE__) . ':' .  __LINE__;$this->_setError_Store('Object['.$pObjName.']', $errStr);$this->_setError_Store('Object['.$pObjName.']', $cihChildren);$ret = FALSE;$status = FALSE;break $tryBlock;}
if (sizeOf($clearInHouseData)) {$cihStatus = $this->storeClearInHouse($clearInHouseData, $this->getScope($obj));if (($cihStatus==FALSE) OR isEx($cihStatus)) {$errStr = "In persist(). Call to storeClearInHouse() failed. " . basename(__FILE__) . ':' .  __LINE__;$this->_setError_Store($varName, $errStr);if (isEx($cihStatus)) {$this->_setError_Store($varName, $cihStatus);}
$ret = FALSE;$status = FALSE;break $tryBlock;}
}
$toDeleteObjList = array();$cihChildrenSize = sizeOf($cihChildren);for ($i=0; $i<$cihChildrenSize; $i++) {$cih = &$cihChildren[$i];if (isSet($this->pInfoList['ignor!'][$cih['varName']])) continue; if (!isSet($currentChieldObjects["{$cih['chieldScope']};{$cih['chieldClass']};{$cih['chieldID']}"])) {$toDeleteObjList[] = array('className'=>$cih['chieldClass'], 'classID'=>$cih['chieldID'], 'scope'=>$cih['chieldScope']);}
}
$delSize = sizeOf($toDeleteObjList);for ($i=0; $i<$delSize; $i++) {$tdol = &$toDeleteObjList[$i];$delStatus = $this->deleteObjectRelation($pObjName, $_persistTag['ID'], $tdol['className'], $tdol['classID'], $scope);if (isEx($delStatus)) {$errStr = "In persist(). Call deleteObjectRelation() failed with exception. " . basename(__FILE__) . ':' .  __LINE__;$this->_setError_Store('Object['.$pObjName.']', $errStr);$this->_setError_Store('Object['.$pObjName.']', $delStatus);$ret = FALSE;$status = FALSE;break $tryBlock;}
$delStatus = $this->softDelete($tdol['className'], $tdol['classID'], $tdol['scope']);if (isEx($delStatus)) {$errStr = "In persist(). Call softDelete() failed with exception. " . basename(__FILE__) . ':' .  __LINE__;$this->_setError_Store('Object['.$pObjName.']', $errStr);$this->_setError_Store('Object['.$pObjName.']', $delStatus);$ret = FALSE;$status = FALSE;break $tryBlock;}
}
$status = TRUE;$ret = $_persistTag['ID'];} while(FALSE);  if ($firstCall) {$this->_unMemorizeObjStored();if ($status) {    $this->commit($transactionId);} else {          $this->rollback($transactionId);}
}
return $ret;}
function &unpersist($class, $query=-1, $scope='', $firstCall=TRUE) {$status = 1;$ret    = FALSE;$className = is_string($class) ? strToLower($class) : (is_object($class) ? get_class($class) : '');do { $tryBlock = 1;if (is_string($class)) {if (is_numeric($query) AND ($query<=0)) {$status = 0;break $tryBlock; }
if (class_exists($class)) {@$obj =& new $class();} else {$errStr = "In unpersist(\$class={$className}, ...). Class '{$className}' does not exsist or is out of scope." .basename(__FILE__) .':'.__LINE__;$this->_setError_Fetch("Object[{$className}]", $errStr);$status = -1;break $tryBlock; }
} else {$obj = &$class;}
if ($firstCall) {$this->_unMemorizeObjFetched(); }
if (!is_object($obj)) {$errStr = "In unpersist(\$class={$className}, \$query={$query}, \$scope={$scope}, \$firstCall={$firstCall}). Class of type:'".getType($class)."'. Object instanciation failed or no object passed. " .basename(__FILE__) .':'.__LINE__;$this->_setError_Fetch("Object[{$className}]", $errStr);$status = -1;break $tryBlock; }
$this->_setObject($obj, $scope);$_persistTag = &$this->getPersistTag($obj);if (is_numeric($query)) {if ($query===-1) $query = $_persistTag['ID'];if ($query<=0) {$status = 0;break $tryBlock; }
}
$metaData = &$this->pMetaData;$objRecord = &$this->fetchObjectData($className, $query, $scope);if (isEx($objRecord)) {$errStr = 'In unpersist(). Exception during  fetchObjectData() ' .basename(__FILE__) .':'.__LINE__;$this->_setError_Fetch("Object[{$className}]", $errStr);$this->_setError_Fetch("Object[{$className}]", $objRecord);$status = -1;break $tryBlock; }
if (is_null($objRecord)) {$status = 0;break $tryBlock; }
$_ooDbProperty = &$this->_getOoDbProperty($useCach=FALSE);$streamNameHash = &$this->_getStreamFields($useCach=TRUE);$metaData['ID'] = (int)$objRecord['ID'];unSet($objRecord['ID']);$tryBlock++;reset($objRecord);while (list($varName) = each($objRecord)) {if (isSet($streamNameHash[$varName]) OR ($varName===BS_OODB_STREAM_DEFAULT_NAME)) {       $metaData[$varName] = &$objRecord[$varName];continue;}
if (isSet($_ooDbProperty[$varName])) {          switch ($_ooDbProperty[$varName]['metaType']) {case 'boolean':
$metaData[$varName] = (bool)$objRecord[$varName];break;case 'integer':
$metaData[$varName] = (int)$objRecord[$varName];break;case 'double':
$metaData[$varName] = (double)$objRecord[$varName];break;default:
$metaData[$varName] = &$objRecord[$varName];}
}
}
$tryBlock--;$this->_reassembleObjectMetaData($metaData);$this->_memorizeObjFetched($obj);$okToUnpersist=array();reset ($_ooDbProperty);while(list($varName) = each($_ooDbProperty)) {$property = &$_ooDbProperty[$varName];if (($property['mode']==='object') AND (!$property['ignor'])) {$okToUnpersist[$varName] = TRUE;}
}
if (sizeOf($okToUnpersist)==0) {} else {$clearInHouseData = &$this->fetchCihChildren($this->pObjName, $metaData['ID'], $reference='', $this->getScope($obj));if (isEx($clearInHouseData)) {$errStr = "In unpersist(). Excetion during  fetchCihChildren(\$objID='{$metaData['ID']}', \$dbName='". $this->getScope($obj)."') " .basename(__FILE__) .':'.__LINE__;$this->_setError_Fetch("Object[{$className}]", $errStr);$this->_setError_Fetch("Object[{$className}]", $clearInHouseData);$status = -1;break $tryBlock; }
$cihSize = sizeOf($clearInHouseData);$tryBlock++;$unpObjStatus = TRUE;for ($i=0; $i<$cihSize; $i++) {$cih = &$clearInHouseData[$i];$varName = &$cih['varName'];if (!isSet($okToUnpersist[$varName])) continue; $tmpStatus = $this->_unpersistObject($varName, $cih['chieldClass'], $cih['chieldID'], $cih['chieldScope'], $cih['hashKey']);if ($tmpStatus===FALSE) {  $errStr = "In unpersist(). Fatal error during call to: _unpersistObject('{$varName}', '{$cih['chieldClass']}', '{$cih['chieldID']}', '{$cih['chieldScope']}', '{$cih['hashKey']}') " .basename(__FILE__) .':'.__LINE__;$this->_setError_Fetch("Object[{$className}]", $errStr);$unpObjStatus = FALSE;} elseif (is_null($tmpStatus)) {  if ($cih['weakRef']) { } else {$errStr = "In unpersist(). Missing : _unpersistObject('{$varName}', '{$cih['chieldClass']}', '{$cih['chieldID']}', '{$cih['chieldScope']}', '{$cih['hashKey']}') " .basename(__FILE__) .':'.__LINE__;$this->_setError_Fetch("Object[{$className}]", $errStr);$unpObjStatus = FALSE;}
}
}
$tryBlock--;$status = ($unpObjStatus) ? 1 : -1;} } WHILE (FALSE); if ($status==1) {       $obj->_persistLastStatus['fetched'] = TRUE;return $obj;} elseif ($status==0) { return NULL;} else {                return $ret;}
}
function _unpersistObject(&$varName, &$className, $objID, $scope, $hashKey=NULL) {$_ooDbProperty = &$this->_getOoDbProperty($useCach=TRUE);$varNamePersistProperty = &$_ooDbProperty[$varName];$theNewObj = NULL;$status = FALSE;do {  $theNewObj = &$this->_getObjAlreadyFetched($className, $objID, $scope);if (($objID>0) AND $theNewObj) { } else {$persister = $this; $theNewObj = &$persister->unpersist($className, $objID, $scope, $firstCall=FALSE);if (is_null($theNewObj)) { $this->pObjToPersist->$varName = NULL;$status = TRUE;$varNamePersistProperty['fetched'] = 'notFound';break; } else if (!is_object($theNewObj)) {$errStr  = "In unpersistObject(). Fatal errors during unpersist(\$className='{$className}', \$objID='{$objID}', \$scope='{$scope}'). Retruned a '".getType($theNewObj)."'. " .basename(__FILE__) .':'.__LINE__;$this->_setError_Fetch($varName, $errStr);$status = FALSE;break; } else {$status = TRUE;$varNamePersistProperty['fetched'] = $theNewObj->_persistLastStatus['fetched'];}
} if ($hashKey=='') {$this->pObjToPersist->$varName = &$theNewObj;} else {$varTmp = &$this->pObjToPersist->$varName;$k = explode(';', $hashKey);switch (sizeOf($k)) {case 1: $varTmp[$k[0]] = &$theNewObj; break;case 2: $varTmp[$k[0]][$k[1]] = &$theNewObj; break;case 3: $varTmp[$k[0]][$k[1]][$k[2]] = &$theNewObj; break;case 4: $varTmp[$k[0]][$k[1]][$k[2]][$k[3]] = &$theNewObj; break;case 5: $varTmp[$k[0]][$k[1]][$k[2]][$k[3]][$k[4]] = &$theNewObj; break;case 6: $varTmp[$k[0]][$k[1]][$k[2]][$k[3]][$k[4]][$k[5]] = &$theNewObj; break;case 7: $varTmp[$k[0]][$k[1]][$k[2]][$k[3]][$k[4]][$k[5]][$k[6]] = &$theNewObj; break;default:;}
}
} while(FALSE); return $status;}
function &oQuery($className, $completeQuery, $scope='') {$status = TRUE;$ret    = array();do { $tryBlock = 1;$dataArray = &$this->queryFetch($completeQuery);if (!is_array($dataArray)) {$errStr  = 'In oQuery(). Exception calling queryFetch().'.basename(__FILE__).':'.__LINE__;$this->_setError_Fetch("Object[{$className}]", $errStr);$this->_setError_Fetch("Object[{$this->pObjName}]", $dataArray);$status = FALSE;break $tryBlock;}
$tryBlock++;$dataSize = sizeOf($dataArray);for ($i=0; $i<$dataSize; $i++) {$rs = &$dataArray[$i];$ret[$i] = &$this->unpersist($className, $rs['ID'], $scope);if ($ret[$i]===FALSE) {$errStr = 'In oQuery(). Fatal error during unpersist(), see previouse errors.' .basename(__FILE__) .':'.__LINE__;$this->_setError_Fetch("Object[{$className}]", $errStr);$status = $ret = FALSE;break $tryBlock;}
}
$tryBlock--;$status = TRUE;} while(FALSE); return $ret;}
function getStorageScope() {return '';}
function storeObjectData() {user_error('fetchObjectData() is an abstract function! It must be overloded.', E_USER_ERROR);}
function fetchObjectData() {user_error('fetchObjectData() is an abstract function! It must be overloded.', E_USER_ERROR);}
function queryFetch() {user_error('queryFetch() is an abstract function! It must be overloded.', E_USER_ERROR);}
function deleteObjectData() {user_error('deleteObjectData() is an abstract function! It must be overloded.', E_USER_ERROR);}
function deleteObjectRelation() {user_error('deleteObjectRelation() is an abstract function! It must be overloded.', E_USER_ERROR);}
function deleteAllObjectRelation() {user_error('deleteObjectRelation() is an abstract function! It must be overloded.', E_USER_ERROR);}
function storeClearInHouse() {user_error('storeClearInHouse() is an abstract function! It must be overloded.', E_USER_ERROR);}
function fetchCihChildren() {user_error('fetchCihChildren() is an abstract function! It must be overloded.', E_USER_ERROR);}
function fetchCihParents() {user_error('fetchCihParents() is an abstract function! It must be overloded.', E_USER_ERROR);}
function startTransaction() {}
function commit() {}
function rollback() {}
}
?>

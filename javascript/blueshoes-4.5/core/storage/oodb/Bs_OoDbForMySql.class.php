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
define('BS_OODBFORMYSQL_VERSION',      '4.5.$Revision: 1.2 $');if (!isSet($GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'])) $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] = '../';require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'storage/oodb/Bs_OoDb.class.php');require_once($APP['path']['core'] . 'db/Bs_MySql.class.php');define('BS_OBJPERSISTERFORMYSQL_VERSION',      '4.5.$Revision: 1.2 $');define('BS_OODB_CLEAR_IN_HOUSE_TBL', '_oodb_cih');define('BS_OODB_CLEAR_IN_HOUSE_EXTENTION', '_cih');class Bs_OoDbForMySql extends Bs_OoDb {var $bsDb;function Bs_OoDbForMySql($dbObject=NULL) {if (is_null($dbObject)) {$this->bsDb = &$GLOBALS['bsDb'];} else {$this->bsDb = &$dbObject;}
parent::Bs_OoDb();}
function deleteAllObjectRelation($parentClass, $parentID, $dbName='', $exclueVarNames='') {$ret = TRUE;do { $exclude = '';if (is_array($exclueVarNames)) {$exclude = ' AND varName NOT IN (';$firstLoop = TRUE;reset($exclueVarNames);while (list($varName) = each($exclueVarNames)) {$exclude .= $firstLoop ? $firstLoop=FALSE : ', ';$exclude .= "'{$varName}'";}
$exclude .= ')';}
$targetTable = empty($dbName) ? BS_OODB_CLEAR_IN_HOUSE_TBL : $dbName . '.' . BS_OODB_CLEAR_IN_HOUSE_TBL;$query   = 'DELETE FROM ' . $targetTable . " WHERE parentID={$parentID} AND parentCass='{$parentClass}' AND chieldID={$chieldID} AND chieldClass='{$chieldClass}' AND chieldScope=''" . $exclude;$sqlStatus = $this->bsDb->write($query);if (isEx($sqlStatus)) {$ret = &$sqlStatus;break $tryBlock; }
$tblName = $parentClass . BS_OODB_CLEAR_IN_HOUSE_EXTENTION;$targetTable = empty($dbName) ? $tblName : $dbName . '.' . $tblName;$query   = 'DELETE FROM ' . $targetTable . " WHERE parentID={$parentID} AND chieldID={$chieldID} AND chieldClass='{$chieldClass}' AND chieldScope=''" . $exclude;$sqlStatus = $this->bsDb->write($query);if (isEx($sqlStatus)) {$ret = &$sqlStatus;break $tryBlock; }
} while(FALSE); return $ret;}
function deleteObjectRelation($parentClass, $parentID, $chieldClass, $chieldID, $dbName='') {$ret = TRUE;do { $targetTable = empty($dbName) ? BS_OODB_CLEAR_IN_HOUSE_TBL : $dbName . '.' . BS_OODB_CLEAR_IN_HOUSE_TBL;$query   = 'DELETE FROM ' . $targetTable . " WHERE parentID={$parentID} AND parentCass='{$parentClass}' AND chieldID={$chieldID} AND chieldClass='{$chieldClass}' AND chieldScope=''";$sqlStatus = $this->bsDb->write($query);if (isEx($sqlStatus)) {$ret = &$sqlStatus;break $tryBlock; }
$tblName = $parentClass . BS_OODB_CLEAR_IN_HOUSE_EXTENTION;$targetTable = empty($dbName) ? $tblName : $dbName . '.' . $tblName;$query   = 'DELETE FROM ' . $targetTable . " WHERE parentID={$parentID} AND chieldID={$chieldID} AND chieldClass='{$chieldClass}' AND chieldScope=''";$sqlStatus = $this->bsDb->write($query);if (isEx($sqlStatus)) {$ret = &$sqlStatus;break $tryBlock; }
} while(FALSE); return $ret;}
function &deleteObjectData($tblName, $objID, $dbName='') {$ret = TRUE;do { $tryBlock = 1;$targetTable = empty($dbName) ? $tblName : $dbName . '.' . $tblName;$query   = 'DELETE FROM  ' . $targetTable . " WHERE ID={$objID}";$sqlStatus = $this->bsDb->write($query);if (isEx($sqlStatus)) {$ret = &$sqlStatus;break $tryBlock; }
$targetTable = empty($dbName) ? BS_OODB_CLEAR_IN_HOUSE_TBL : $dbName . '.' . BS_OODB_CLEAR_IN_HOUSE_TBL;$where = " WHERE chieldID={$objID} AND chieldClass='{$tblName}' AND chieldScope=''";$query   = 'SELECT DISTINCT parentClass FROM  ' . $targetTable . $where;$cihRefTbls = &$this->bsDb->getAll($query);if (isEx($cihRefTbls)) {$ret = &$cihRefTbls;break $tryBlock; }
$cihRefTblsSize = sizeOf($cihRefTbls);if ($cihRefTblsSize) {$query   = 'DELETE FROM ' . $targetTable . $where;$sqlStatus = $this->bsDb->write($query);if (isEx($sqlStatus)) {$ret = &$sqlStatus;break $tryBlock; }
$tryBlock++;for ($i=0; $i<$cihRefTblsSize; $i++) {$targetTable = empty($dbName) ? $tblName : $cihRefTbls[$i]['parentClass'] . '.' . $cihRefTbls[$i]['parentClass'];$query   = 'DELETE FROM ' . $targetTable . $where;$sqlStatus = $this->bsDb->write($query);if (isEx($sqlStatus)) {$ret = &$sqlStatus;break $tryBlock; }
}
$tryBlock--;}
} while(FALSE); return $ret;}
function storeObjectData(&$lonelyList, $tblName, $dbName='') {$ret = 0;do { $tryBlock = 1;$lonelyListSize = sizeOf($lonelyList);if ($lonelyListSize==0) {break $tryBlock;             } 
$objID = $lonelyList['ID']['data'];if ($this->pStorageAutoCreate) {$filedNames = &$this->getFieldNames($useCach=TRUE);$ret = $this->createClassTable($dbName, $tblName, $filedNames);if ((!$ret) OR isEx($ret)) {break $tryBlock;}
}
$targetTable = empty($dbName) ? $tblName : $dbName . '.' . $tblName;$firstLoop = TRUE;reset($lonelyList);while (list($varName) = each($lonelyList)) {if ($firstLoop) {$sql = "{$targetTable} SET ";$firstLoop=FALSE;} else {$sql .= ', ';}
if ($varName==='ID') {$sql .= $varName . "={$objID}";} else {$sql .= $varName . '=\'' . addSlashes($lonelyList[$varName]['data']) . '\'';}
} $retryWithSqlInsert = FALSE;if ($objID > 0) {$sqlQuery = 'UPDATE '.$sql.' WHERE ID='. $objID;$sqlStat = $this->bsDb->write($sqlQuery);if (isEx($sqlStat)) {$sqlStat->stackTrace('in storeObjectData(). Failed to update \'lonely\'-data of object [' . $tblName . "].\n The SQL query was [{$sqlQuery}]", __FILE__, __LINE__);$ret = &$sqlStat;break $tryBlock;}
if ($this->bsDb->affectedRows()==0) $retryWithSqlInsert = TRUE;}
if (($objID==0) OR ($retryWithSqlInsert)) {$sqlQuery = 'INSERT INTO '. $sql;$sqlStat = $this->bsDb->idWrite($sqlQuery);if (isEx($sqlStat)) {if ($retryWithSqlInsert AND ($sqlStat->_errCode==BS_DB_ERROR_ALREADY_EXISTS)) {$sqlStat = $objID;} else {$sqlStat->stackTrace('in storeObjectData(). Failed to insert \'lonely\'-data of object [' . $tblName . "].\n The SQL query was [{$sqlQuery}]", __FILE__, __LINE__);$ret = &$sqlStat;break $tryBlock;}
}
$objID = $sqlStat;}
$ret = $objID;} while(FALSE); return $ret;}
function storeClearInHouse(&$clearInHouseData, $dbName='') {$status = TRUE;$mainCihTarget = empty($dbName) ? BS_OODB_CLEAR_IN_HOUSE_TBL : $dbName . '.' . BS_OODB_CLEAR_IN_HOUSE_TBL;do { $tryBlock =1;if ($this->pStorageAutoCreate) {$status = $this->_createClearInHouse(BS_OODB_CLEAR_IN_HOUSE_TBL, $dbName);if (isEx($status)) {$status->stackTrace("in storeClearInHouse(). Failed to create Table for 'object'-RELATION of object [{$this->pObjName}].", __FILE__, __LINE__);break $tryBlock;}
}
$tryBlock++;$smallCih = array();$cihSize = sizeOf($clearInHouseData);for ($i=0; $i<$cihSize; $i++) {$cih = &$clearInHouseData[$i];$weakRef = ($cih['weakRef']) ? 1 : 0;$md5 = md5($cih['parentID'].';'.$cih['parentClass'].';'.$cih['chieldID'].';'.$cih['chieldClass'].';'.$cih['varName'].';'.$cih['hashKey']);$sql = "REPLACE INTO {$mainCihTarget} SET md5='{$md5}',".
" parentClass='{$cih['parentClass']}', parentID={$cih['parentID']},".
" varName='{$cih['varName']}', chieldScope='{$cih['chieldScope']}',".
" chieldClass='{$cih['chieldClass']}', chieldID={$cih['chieldID']},".
" hashKey='{$cih['hashKey']}', readOnly='{$cih['readOnly']}', weakRef={$weakRef}";$status = $this->bsDb->write($sql);if (isEx($status)) {$status->stackTrace("in storeClearInHouse(). Failed to store MAIN Clear In House of object [{$this->pObjName}].\n The SQL query was [{$sql}]", __FILE__, __LINE__);break $tryBlock;}
$littleCihTbl = $cih['parentClass'] . BS_OODB_CLEAR_IN_HOUSE_EXTENTION;$littleCihTarget = empty($dbName) ? $littleCihTbl : $dbName . '.' . $littleCihTbl;if ($this->pStorageAutoCreate) {$status = $this->_createClearInHouse($littleCihTbl, $dbName);if (isEx($status)) {$status->stackTrace("in storeClearInHouse(). Failed to create Table for 'object'-RELATION of object [{$this->pObjName}].", __FILE__, __LINE__);break $tryBlock;}
}
$sql = "REPLACE INTO {$littleCihTarget} SET md5='{$md5}',".
" parentClass='{$cih['parentClass']}', parentID={$cih['parentID']},".
" varName='{$cih['varName']}', chieldScope='{$cih['chieldScope']}',".
" chieldClass='{$cih['chieldClass']}', chieldID={$cih['chieldID']},".
" hashKey='{$cih['hashKey']}', readOnly='{$cih['readOnly']}', weakRef={$weakRef}";$status = $this->bsDb->write($sql);if (isEx($status)) {$status->stackTrace("in storeClearInHouse(). Failed to store Clear In House of object [{$this->pObjName}].\n The SQL query was [{$sql}]", __FILE__, __LINE__);break $tryBlock;}
} $tryBlock--;} while (FALSE); return $status;}
function &fetchCihParents($tblName, $objID, $reference='', $dbName='') {$targetTable = empty($dbName) ? BS_OODB_CLEAR_IN_HOUSE_TBL : $dbName . '.' . BS_OODB_CLEAR_IN_HOUSE_TBL;$query   = 'SELECT * FROM  ' . $targetTable . " WHERE chieldID={$objID} AND chieldClass='{$tblName}'";switch ($reference) {case 'strong': $query .= ' AND weakRef=0'; break;case 'weak':   $query .= ' AND weakRef=1'; break;}
$rsArray = &$this->bsDb->getAll($query);if (is_null($rsArray)) $rsArray = array();if ((isEx($rsArray)) AND ($rsArray->_errCode == BS_DB_ERROR_NOSUCHTABLE))  $rsArray = array();return $rsArray;}
function &fetchCihChildren($tblName, $objID, $reference='', $dbName='') {$targetTable = empty($dbName) ? BS_OODB_CLEAR_IN_HOUSE_TBL : $dbName . '.' . BS_OODB_CLEAR_IN_HOUSE_TBL;$query   = 'SELECT * FROM  ' . $targetTable . " WHERE parentID={$objID} AND parentClass='{$tblName}'";switch ($reference) {case 'strong': $query .= ' AND weakRef=0'; break;case 'weak':   $query .= ' AND weakRef=1'; break;}
$rsArray = &$this->bsDb->getAll($query);if (is_null($rsArray)) $rsArray = array();if ((isEx($rsArray)) AND ($rsArray->_errCode == BS_DB_ERROR_NOSUCHTABLE))  $rsArray = array();return $rsArray;}
function &fetchObjectData($tblName, $sqlSomething, $dbName='') {$targetTable = empty($dbName) ? $tblName : $dbName . '.' . $tblName;if (is_array($sqlSomething)) {$rsArray = &$sqlSomething;reset($rsArray);} else {if (is_numeric($sqlSomething)) {$sqlWhere = 'WHERE ID=' . $sqlSomething;} elseif (is_string($sqlSomething)) {$sqlWhere = &$sqlSomething;} else {return new Bs_Exception($msg="In unPersist(). Error in passed parameter 1 : '{$sqlSomething}'", __FILE__, __LINE__);}
$query   = 'SELECT * FROM  ' . $targetTable . ' ' . $sqlWhere . ' LIMIT 1';$rsArray = &$this->bsDb->getRow($query);}
if ((isEx($rsArray)) AND ($rsArray->_errCode == BS_DB_ERROR_NOSUCHTABLE))  $rsArray = NULL;return $rsArray;}
function &queryFetch($completeQuery) {$rsArray = &$this->bsDb->getAll($completeQuery);return $rsArray;}
function startTransaction($transactionId='') {if ($this->bsDb->provides('transactions')) $this->bsDb->startTransaction($transactionId);}
function commit($transactionId='') {if ($this->bsDb->provides('transactions')) $this->bsDb->commit($transactionId);}
function rollback($transactionId='') {if ($this->bsDb->provides('transactions')) $this->bsDb->rollback($transactionId);}
function dropTable($tblName) {$query = 'DROP TABLE IF EXISTS ' . $this->_objToPersist->persistTag['target'];$ret = $this->_Bs_Db->write($query);if (isEx($ret)) {$ret->stackTrace('in dropTable()', __FILE__, __LINE__);return $ret;}
return TRUE;}
function _createClearInHouse($tblName, $dbName='') {$ret = TRUE;$targetTable = empty($dbName) ? $tblName : $dbName . '.' . $tblName;$sql = "CREATE TABLE IF NOT EXISTS {$targetTable} (";$sql .= 'ID INT UNSIGNED NOT NULL DEFAULT 0 AUTO_INCREMENT, ';$sql .= 'parentClass  VARCHAR(64) NOT NULL DEFAULT \'\', ';$sql .= 'parentID     INT NOT NULL DEFAULT -1, ';$sql .= 'varName      VARCHAR(64) NOT NULL DEFAULT \'\', ';$sql .= 'chieldScope  VARCHAR(64) NOT NULL DEFAULT \'\', ';$sql .= 'chieldClass  VARCHAR(64) NOT NULL DEFAULT \'\', ';$sql .= 'chieldID     INT NOT NULL DEFAULT -1, ';$sql .= 'weakRef      TINYINT NOT NULL DEFAULT 0, ';$sql .= 'readOnly     TINYINT NOT NULL DEFAULT 0, ';$sql .= 'hashKey      VARCHAR(255) NOT NULL DEFAULT \'\', ';$sql .= 'md5     CHAR(32) NOT NULL DEFAULT \'\', ';$sql .= 'PRIMARY KEY ID (ID), ';$sql .= 'UNIQUE INDEX md5 (md5), ';$sql .= 'INDEX parentClass (parentClass), ';$sql .= 'INDEX pID_class   (parentID,parentClass), ';$sql .= 'INDEX chieldClass (chieldClass), ';$sql .= 'INDEX cID_class   (chieldID,chieldClass) ';$sql .= ')';$ret = $this->bsDb->write($sql);return $ret;}
function _typeTrans(&$dbType, &$metaType) {$metaType2dbType = array ( 
'boolean'=>'TINYINT', 'integer'=>'INT', 'double'=>'DOUBLE',
'string'=>'VARCHAR(255)', 'blob'=>'BLOB');if (is_null($metaType)) {$dbType2metaType = array_flip($metaType2dbType);$metaType = $dbType2metaType[$dbType];} else {$dbType = $metaType2dbType[$metaType];}
}
function _dbType2metaType($dbType) {$metaType = NULL;$this->_typeTrans($dbType, $metaType);return $metaType;}
function _metaType2dbType($metaType) {$dbType = NULL;$this->_typetrans($dbType, $metaType);return $dbType;}
function matchDbFields_Versus_OoDbProperty($dbName, $tblName, &$ooDbProperty) {$dbMatch = array();do { $tryBlock = 1;$tableProperties = array();$tableProperties = $this->bsDb->getTableProperties($tblName, $dbName);if (isEx($tableProperties)) {$tableProperties->stackTrace('in matchDbFields_Versus_OoDbProperty().', __FILE__, __LINE__);$ret = &$tableProperties;break $tryBlock;}
reset($ooDbProperty);while (list($varName) = each($ooDbProperty)) {if ($varName==='ID')  continue; $dbMatch[$varName] = array('status'=>'?', 'metaType'=>'?', 'currentDbType'=>'-', 'newDbType'=>'-');$dbMatch[$varName]['metaType'] = $ooDbProperty[$varName]['metaType'];$dbMatch[$varName]['newDbType'] = $this->_metaType2dbType($ooDbProperty[$varName]['metaType']);if (isSet($tableProperties[$varName])) { $dbMatch[$varName]['currentDbType'] = strToUpper($tableProperties[$varName]['Type']);if ($dbMatch[$varName]['currentDbType']=='VARCHAR') {$dbMatch[$varName]['currentDbType'] .= '('.$tableProperties[$varName]['Length'].')';} 
if ($dbMatch[$varName]['currentDbType'] !== $dbMatch[$varName]['newDbType']) {$dbMatch[$varName]['status'] = 'mismatch';} else {$dbMatch[$varName]['status'] = 'ok';}
} else {$dbMatch[$varName]['status'] = 'missing';}
}
$ret = $dbMatch;} while(FALSE); return $ret;}
function _assembleSqlQueryForClassTbl($dbName, $tblName, &$ooDbProperty, $createAddChange='create') {$targetTable = empty($dbName) ? $tblName : $dbName . '.' . $tblName;$metaType2dbDefault = array('string'=>"''", 'blob'=>"''", 'boolean'=>0, 'integer'=>0, 'double'=>0);$queryFields = $queryKeys = array();$sql='';switch($createAddChange) {case 'create': $sql .= "CREATE TABLE {$targetTable} (";$queryFields[] = 'ID INT UNSIGNED NOT NULL DEFAULT 0 AUTO_INCREMENT';$queryKeys[]   = 'PRIMARY KEY ID (ID)';$modTxt = '';break;case 'add':    $sql .= "ALTER TABLE {$targetTable} "; $modTxt='ADD'; break;case 'modify': $sql .= "ALTER TABLE {$targetTable} "; $modTxt='MODIFY'; break;default:
}
reset($ooDbProperty);while (list($varName) = each($ooDbProperty)) {if ($varName==='ID')  continue;$metaType = &$ooDbProperty[$varName]['metaType'];$dbType = $this->_metaType2dbType($metaType);$setIndex = &$ooDbProperty[$varName]['index'];$queryFields[] = "{$modTxt} {$varName} {$dbType} NOT NULL DEFAULT {$metaType2dbDefault[$metaType]}";if (isSet($setIndex) AND $setIndex AND ($createAddChange!='modify')) {$queryKeys[] = "{$modTxt} INDEX {$varName} ({$varName})";}
}
$sql .= join(', ', $queryFields);if ($queryKeys) $sql .= ', ' . join(',', $queryKeys);$sql .= ($createAddChange=='create') ? ')':'';return $sql;}
function createClassTable($dbName, $tblName, &$ooDbProperty) {$ret = TRUE;$targetTable = empty($dbName) ? $tblName : $dbName . '.' . $tblName;do { $tryBlock = 1;$tableExsists = $this->bsDb->tableExists($tblName, $dbName, FALSE);if (isEx($tableExsists)) {$tableExsists->stackTrace('in createClassTable().', __FILE__, __LINE__);$ret = &$tableExsists;break $tryBlock;}
if (!$tableExsists) {$sql = &$this->_assembleSqlQueryForClassTbl($dbName, $tblName, $ooDbProperty, $createAddChange='create');$ret = $this->bsDb->write($sql);if (isEx($ret)) {$ret->stackTrace('in createClassTable()', __FILE__, __LINE__);}
break $tryBlock;} 
if ($tableExsists) {$fieldMismatch = array();$fieldMissing = array();$dbMatch = $this->matchDbFields_Versus_OoDbProperty($dbName, $tblName, &$ooDbProperty);if (isEx($dbMatch)) {$dbMatch->stackTrace('in createClassTable().', __FILE__, __LINE__);$ret = &$dbMatch;break $tryBlock;}
reset($dbMatch);while(list($varName)=each($dbMatch)) {switch ($dbMatch[$varName]['status']) {case 'mismatch': $fieldMismatch[$varName] = &$ooDbProperty[$varName];break;case 'missing' : $fieldMissing[$varName] = &$ooDbProperty[$varName];break;default:continue;}
}
if (sizeOf($fieldMissing)>0) {$sql = &$this->_assembleSqlQueryForClassTbl($dbName, $tblName, $fieldMissing, $createAddChange='add');$ret = $this->bsDb->write($sql);if (isEx($ret)) {$ret->stackTrace('in createClassTable()', __FILE__, __LINE__);break $tryBlock;}
} 
if (sizeOf($fieldMismatch)>0) {$sql = &$this->_assembleSqlQueryForClassTbl($dbName, $tblName, $fieldMismatch, $createAddChange='modify');$ret = $this->bsDb->write($sql);if (isEx($ret)) {$ret->stackTrace('in createClassTable()', __FILE__, __LINE__);break $tryBlock;}
} 
} } while(FALSE); if (!isEx($ret)) {$sql = "SELECT * FROM {$targetTable} LIMIT 1";$this->bsDb->write($sql);}
return $ret;}
function createClassTableFromName($class, $dbName) {$persister = $this;do { $tryBlock = 1;$include = FALSE;if (preg_match('/\.class\.php/Ui',$class)) {$tmp = basename($class);$fragment = explode('.', $tmp);$className = $fragment[0];$include = TRUE;} else {$className = $class;}
if ($include) {global $APP;require_once($class);}
if (class_exists($className)) {@$obj =& new $className();} else {$errStr = "In createClassTableFromName(\$className='{$className}', \$dbName='{$dbName}'). Class '{$className}' does not exsist or is out of scope.";$ret =& new Bs_Exception($errStr, __FILE__, __LINE__);$status = -1;break $tryBlock; }
if (!is_object($obj)) {$errStr = "In createClassTableFromName(\$className='{$className}', \$dbName='{$dbName}'). Class of type:'".getType($className)."'. Object instanciation failed.";$ret =& new Bs_Exception($errStr, __FILE__, __LINE__);$status = -1;break $tryBlock; }
$persister->_setObject($obj,  $dbName);$filedNames = $persister->getFieldNames($useCach=FALSE);$ret = $persister->createClassTable($dbName, $className, $filedNames);if ((!$ret) OR isEx($ret)) {break $tryBlock;}
$ret = $persister->_createClearInHouse($className . BS_OODB_CLEAR_IN_HOUSE_EXTENTION, $dbName);if ((!$ret) OR isEx($ret)) {break $tryBlock;}
$ret = $persister->_createClearInHouse(BS_OODB_CLEAR_IN_HOUSE_TBL, $dbName);if ((!$ret) OR isEx($ret)) {break $tryBlock;}
$ret = TRUE;} while(FALSE); return $ret;}
function &getStorageScope() {$dsnInfo = &$this->bsDb->getDsn();return $dsnInfo['name'];}
}
?>

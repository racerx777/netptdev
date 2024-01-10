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
define('BS_CUGDB_VERSION',      '4.5.$Revision: 1.3 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'auth/cug/Bs_Cug.class.php');require_once($APP['path']['core'] . 'db/Bs_MySql.class.php');require_once($APP['path']['core'] . 'text/Bs_TextUtil.class.php');class Bs_CugDb extends Bs_Cug {var $_bsDb;var $userDbName;var $userTableName;var $logDbName;var $logTableName;var $_signupForm;var $manyFailuresErrorMsg = array(
'en' => "<br>This was your [numFailures]. attempt that failed in a row. 
There is no maximum number of allowed attempts. Remember that the username and password 
may be case sensitive. Be aware that every request is logged. During your next try you have 
to wait [timeoutNext] seconds.", 
'de' => "<br>Dies war Ihr [numFailures]. Versucht der in Serie fehlschlug.
Es gibt kein Maximum an erlaubten Versuchen. Achtung: Gross/Kleinschrift beim Benutzernamen 
und Passwort spielt eine Rolle. Hinweis: Jeder Zugriff auf diese Seite wird aufgezeichnet. 
Bei Ihrem nächsten Versuch müssen Sie [timeoutNext] warten.", 
);function Bs_CugDb($cugName, $postData=NULL) {parent::Bs_Cug($cugName, $postData); if (isSet($GLOBALS['bsDb'])) $this->_bsDb = &$GLOBALS['bsDb'];}
function setDbObject(&$bsDb) {unset($this->_bsDb);$this->_bsDb =& $bsDb;}
function _validateLogin() {$t        = $this->form->getFieldValue('username');$username = $t[0];$t        = $this->form->getFieldValue('password');$password = $t[0];$row = NULL;$isOk = FALSE;do {if (!$this->_bsDb->dbPing()) {$isUserError    = FALSE;$failedReason   = 'exception';$this->errorMsg = 'Currently there is *no* connection to the Db. Please try later or contact the system administrator.';break; }
$sqlQ = "SELECT * FROM " . $this->getDbString('user') . " WHERE {$this->userFieldNames['user']} LIKE '" . addSlashes($username) . "'";$row  = $this->_bsDb->getRow($sqlQ);if (is_null($row)) {$isUserError    = TRUE;$failedReason   = 'username';$this->errorMsg = 'Username or password wrong. Please try again.';break; }
if (isEx($row)) {$isUserError    = FALSE;$failedReason   = 'exception';$this->errorMsg = 'Internal System Problem. Please try again or contact the system administrator.';break; }
$validateData = array();$validateData['sentUser'] = $username;$validateData['sentPass'] = $password;$validateData['user'] = $row[$this->userFieldNames['user']];$validateData['pass'] = $row[$this->userFieldNames['pass']];if (isSet($row[$this->userFieldNames['isActive']]) && isSet($row[$this->userFieldNames['startDatetime']]) && isSet($row[$this->userFieldNames['endDatetime']])) {$validateData['isActive']      = $row[$this->userFieldNames['isActive']];$validateData['startDatetime'] = $row[$this->userFieldNames['startDatetime']];$validateData['endDatetime']   = $row[$this->userFieldNames['endDatetime']];}
$failedReason = $this->_validateLoginData($validateData);if ($failedReason === TRUE) {$failedReason = parent::_validateLogin($row);$isUserError    = FALSE; } else {$isUserError    = TRUE;}
if ($failedReason === TRUE) {$this->errorMsg = '';} else {$this->errorMsg = $failedReason;break; }
$isOk = TRUE;} while(FALSE);if (!$isOk) {$looksLikeHack = $this->looksLikeHack($password, $username);$loginFailureData = $this->getNumLoginFailures();if (isEx($loginFailureData)) {} else {if ($loginFailureData[0] >= 3) {$t = $GLOBALS['Bs_TextUtil']->getLanguageDependentValue($this->manyFailuresErrorMsg, $this->language);$t = str_replace('[numFailures]', $loginFailureData[0] +1, $t);$t = str_replace('[timeoutNext]', $loginFailureData[2] *2, $t);$this->errorMsg = $t;if ($looksLikeHack && ($loginFailureData[2] == 2)) {$this->_hackAlert();}
}
}
} else {$looksLikeHack = FALSE;}
if (is_array($row)) {$realData['realUserID']    = $row['ID'];$realData['realUsername']  = $row[$this->userFieldNames['user']];$realData['realPassword']  = $row[$this->userFieldNames['pass']];} else {$realData = NULL;}
$this->_logAttempt($isOk, $failedReason, $isUserError, $looksLikeHack, $realData);if ($this->unixLikeDoubleTimeout && (isSet($loginFailureData[2]) && is_array($loginFailureData))) {$this->_timeoutOnLoginFailure($loginFailureData[2]);}
return $isOk;}
function _logAttempt($isOk, $failedReason='', $isUserError=TRUE, $looksLikeHack=FALSE, $realData=NULL) {if (($this->logAttempts == 1) || ((!$isOk) && ($this->logAttempts == 2)) || ($isOk && ($this->logAttempts == 3))) {$logArray = $this->_prepareLogData($isOk, $failedReason, $isUserError, $looksLikeHack, $realData);$dbString = $this->getDbString('log');if ($dbString === FALSE) return; if (!$this->_bsDb->dbPing()) return; $sqlI = "INSERT INTO {$dbString} SET " . $this->_bsDb->quoteArgs($logArray, ', ');$status = $this->_bsDb->write($sqlI);if ($status !== TRUE) {if ($this->checkLogDbTable() === FALSE) {$status = $this->_bsDb->write($sqlI);}
}
if (isEx($status)) {$status->stackTrace('was here in _logAttempt()', __FILE__, __LINE__);XR_dump($status->stackDump('return'), __LINE__, '', __FILE__);}
}
}
function checkLogDbTable() {$fields = array(
'realUserID'    => array(
'type'          => 'varchar', 
'length'        => '26', 
'default'       => '', 
'notNull'       => TRUE, 
'multipleKey'   => TRUE, 
), 
'realUsername'  => array(
'type'          => 'varchar', 
'length'        => '20', 
'default'       => '', 
'notNull'       => TRUE, 
'multipleKey'   => FALSE, 
), 
'realPassword'  => array(
'type'          => 'varchar', 
'length'        => '20', 
'default'       => '', 
'notNull'       => TRUE, 
'multipleKey'   => FALSE, 
), 
'sentUsername'  => array(
'type'          => 'varchar', 
'length'        => '20', 
'default'       => '', 
'notNull'       => TRUE, 
'multipleKey'   => TRUE, 
), 
'sentPassword'  => array(
'type'          => 'varchar', 
'length'        => '20', 
'default'       => '', 
'notNull'       => TRUE, 
'multipleKey'   => TRUE, 
), 
'isOk'          => array(
'type'          => 'int', 
'default'       => '0', 
'notNull'       => TRUE, 
'multipleKey'   => TRUE, 
), 
'failedReason'  => array(
'type'          => 'varchar', 
'length'        => '255', 
'default'       => '', 
'notNull'       => TRUE, 
'multipleKey'   => FALSE, 
), 
'isUserError'   => array(
'type'          => 'int', 
'default'       => '1', 
'notNull'       => TRUE, 
'multipleKey'   => FALSE, 
), 
'looksLikeHack' => array(
'type'          => 'int', 
'default'       => '0', 
'notNull'       => TRUE, 
'multipleKey'   => FALSE, 
), 
'sessionId'     => array(
'type'          => 'varchar', 
'length'        => '100', 
'default'       => '', 
'notNull'       => TRUE, 
'multipleKey'   => FALSE, 
), 
'ip'            => array(
'type'          => 'char', 
'length'        => '15', 
'default'       => '', 
'notNull'       => TRUE, 
'multipleKey'   => TRUE, 
), 
'ipResolved'    => array(
'type'          => 'varchar', 
'length'        => '80', 
'default'       => '', 
'notNull'       => TRUE, 
'multipleKey'   => FALSE, 
), 
'userAgent'     => array(
'type'          => 'varchar', 
'length'        => '80', 
'default'       => '', 
'notNull'       => TRUE, 
'multipleKey'   => FALSE, 
), 
'httpReferer'   => array(
'type'          => 'varchar', 
'length'        => '255', 
'default'       => '', 
'notNull'       => TRUE, 
'multipleKey'   => FALSE, 
), 
'formTimeTaken' => array(
'type'          => 'int', 
'default'       => '0', 
'notNull'       => TRUE, 
'multipleKey'   => FALSE, 
), 
'formLanguage'  => array(
'type'          => 'varchar', 
'length'        => '7', 
'default'       => '', 
'notNull'       => TRUE, 
'multipleKey'   => FALSE, 
), 
'eventDatetime' => array(
'type'          => 'datetime', 
'default'       => '', 
'notNull'       => TRUE, 
'multipleKey'   => TRUE, 
)
);$status = $this->_bsDb->updateTableStructure($fields, $this->logTableName, $this->logDbName, 0);if (isEx($status)) {$status->stackTrace('in checkLogDbTable()', __FILE__, __LINE__);return $status;} else {return !$status;}
}
function getDbString($which='user') {$dbString = '';if ($which == 'log') {if (!isSet($this->logTableName) || empty($this->logTableName)) return FALSE; if (!empty($this->logDbName)) {$dbString = $this->logDbName . '.';}
$dbString .= $this->logTableName;} else {if (!isSet($this->userTableName) || empty($this->userTableName)) return FALSE; if (!empty($this->userDbName)) {$dbString = $this->userDbName . '.';}
$dbString .= $this->userTableName;}
return $dbString;}
function getOldSids($username, $numDays=2) {$compareEventDatetime = date('Y-m-d H:i:s', mktime(date('H'), date('i'), date('s'), date('m'), date('d')-$numDays, date('Y')));$sql  = "SELECT sessionId FROM " . $this->getDbString('log') . ' WHERE';$sql .= " realUsername LIKE '{$username}'";$sql .= " AND isOk=1";$sql .= " AND eventDatetime >= '{$compareEventDatetime}'";$sql .= " AND sessionId <> ''";$sql .= " AND sessionId <> '" . $this->bsSession->getSid() . "'";$sql .= " GROUP BY sessionId";$row  = $this->_bsDb->getCol($sql);if (is_null($row)) $row = array();return $row;}
function killOldSessions($username, $numDays=2) {$sids = $this->getOldSids($username, $numDays);if (isEx($sids)) return $sids;$numKilled = 0;foreach($sids as $sid) {$status = $this->bsSession->destroySID($sid); if ($status) $numKilled++;}
return $numKilled;}
function getNumLoginFailures($numDays=3) {$ret = array(0, 0, 0);if ($this->logAttempts != 1)     return $ret; if (!isSet($this->logTableName)) return $ret; $sqlQ  = "SELECT isOk, isUserError, looksLikeHack FROM " . $this->getDbString('log');$sqlQ .= " WHERE ip='{$GLOBALS['HTTP_SERVER_VARS']['REMOTE_ADDR']}'";if ($numDays > 0) {$compareEventDatetime = date('Y-m-d H:i:s', mktime(date('H'), date('i'), date('s'), date('m'), date('d')-$numDays, date('Y')));$sqlQ .= " AND eventDatetime >= '{$compareEventDatetime}'";}
$sqlQ .= ' ORDER BY eventDatetime DESC LIMIT 100'; $status = $this->_bsDb->getAll($sqlQ);if (isEx($status)) {$status->stackTrace('was here in getNumLoginFailures()', __FILE__, __LINE__);return $status;}
$timeoutNext = 1;$numHacks    = 0;while (list($k) = each($status)) {if (FALSE) { } else {if ($status[$k]['isOk'] || (isSet($status[$k]['isUserError']) && (!$status[$k]['isUserError']))) break;}
$ret[0]++;if ($ret[2] == 0) {$ret[2] = 1;} else {$ret[2] *= 2;}
if ($status[$k]['looksLikeHack']) $ret[1]++;}
return $ret;}
function recoverPasswordByUsername($username, $automail=TRUE) {$sql =  "SELECT {$this->userFieldNames['user']}, {$this->userFieldNames['pass']}, {$this->userFieldNames['email']} ";$sql .= "FROM " . $this->getDbString('user');$sql .= " WHERE {$this->userFieldNames['user']} LIKE '" . $this->_bsDb->escapeString($username) . "'";$row = $this->_bsDb->getRow($sql);if (is_null($row)) {$isOk           = FALSE;$failedReason   = 'username';$this->errorMsg = 'No such username.';return FALSE;} elseif (isEx($row)) {$isOk           = FALSE;$failedReason   = 'exception';$this->errorMsg = 'Internal System Problem. Please try again or contact the system administrator.';return $row; } else {if (($this->checkCaseSensitive == BS_CUG_CASE_SENSITIVE_ONLY_PASSWORD) && ($this->checkCaseSensitive == BS_CUG_CASE_SENSITIVE_NO)) {$isOk = TRUE; } else {$isOk = ($username === $row[$this->userFieldNames['user']]);}
if (!$isOk) {$this->errorMsg = 'Case of username wrong. Please try again.';} else {if ($automail) {$eSubject = 'Your login data';$eMsg    .= "password: " . $row[$this->userFieldNames['pass']] . "\n";$status = mail($row[$this->userFieldNames['email']], $eSubject, $eMsg);if (!$status) {$failedReason   = 'email';$this->errorMsg = 'Sending of email message to ' . $row[$this->userFieldNames['email']] . ' failed.';}
return $status;} else {return $row;}
}
}
}
function resetPasswordByUsername($username, $automail=TRUE, $newPassword=NULL) {if (is_null($newPassword)) $newPassword = $this->createPassword();$sql = "UPDATE " . $this->getDbString() . " SET {$this->userFieldNames['pass']}='" . $this->_bsDb->escapeString($newPassword) . "'";$sqlWhere = ' WHERE ' . $this->userFieldNames['user'] . "='" . $this->_bsDb->escapeString($username) . "'";$sql .= $sqlWhere;$numRecs = $this->_bsDb->countWrite($sql);if (isEx($numRecs)) {return $numRecs;} else {if (is_int($numRecs) && ($numRecs > 0)) {if ($automail) {$sql = "SELECT {$this->userFieldNames['email']} FROM " . $this->getDbString();$sql .= $sqlWhere;$eAddr = $this->_bsDb->getOne($sql);if (isEx($eAddr)) {return $eAddr;}
$eSubject = 'Your new password';$eMsg     = "username: {$username}\n";$eMsg     = "password: {$newPassword}\n";return @mail($eAddr, $eSubject, $eMsg);}
return $newPassword;} else {return FALSE;}
}
}
function resetPasswordByEmail($email, $automail=TRUE) {}
function setSignupForm($form) {$this->_signupForm = $form;}
function _loadSignupForm() {if (isSet($this->_signupForm)) return; }
}
?>
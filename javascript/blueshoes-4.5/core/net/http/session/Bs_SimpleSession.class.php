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
define('BS_SIMPLESESSION_VERSION',      '4.5.$Revision: 1.14 $');require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');$bs_sessInfoList = array();class Bs_SimpleSession extends Bs_Object {var $_sessStarted = NULL;var $_sessInfoList = NULL; var $_sessData = array();     var $_userProps = array();var $_sessPropTemplate = array (  'version'        => 0,        'path'           => "",       'maxStandbyTime' => 180,      'maxLifeTime'    => 0,        );var $_runProperties = array (
'gc'               => 1,        'garbage_lifetime' => 43200,    );var $_sessPrefix   = 'bs_sess_'; var $_isValid      = array();    var $_souceVersion = 0;          function &Bs_SimpleSession() {@ini_set('session.bug_compat_warn', 0);if (isSet($GLOBALS['Bs_SimpleSession'])) return $GLOBALS['Bs_SimpleSession'];parent::Bs_Object();mt_srand((double)microtime() * 1000000);if ($this->_pathCheck($this->_sessPropTemplate['path'])) {bs_registerShutdownMethod(__LINE__, __FILE__, $this, 'write_close');}
}
function start($runProperties=array()) {if (!empty($runProperties)) {if (isSet($runProperties['gc'])) $this->_runProperties['gc'] = $runProperties['gc'];if (isSet($runProperties['garbage_lifetime'])) $this->_runProperties['garbage_lifetime'] = $runProperties['garbage_lifetime'];}
if ($this->_sessStarted !== NULL) return $this->_sessStarted;$this->_sessStarted = session_start();if (isSet($_SESSION)) {if (!isSet($_SESSION['bs_sessInfoList'])) {$_SESSION['bs_sessInfoList']=NULL;}
$this->_sessInfoList =& $_SESSION['bs_sessInfoList'];} else {global $HTTP_SESSION_VARS;if (!isSet($HTTP_SESSION_VARS['bs_sessInfoList'])) {session_register('bs_sessInfoList');}
$this->_sessInfoList =& $HTTP_SESSION_VARS['bs_sessInfoList'];}
}
function register($key, &$value, $sessName='default') {if (!isSet($this->_isValid[$sessName])) $this->_init($sessName);if ($this->_isValid[$sessName]) {if (isSet($this->_sessData[$sessName]['data'][$key])) {$value = $this->_sessData[$sessName]['data'][$key];unset($this->_sessData[$sessName]['data'][$key]);$this->_sessData[$sessName]['data'][$key] =& $value;} else {$this->_sessData[$sessName]['data'][$key] =& $value;$this->_sessInfoList[$sessName]['state']['forceWrite'] = TRUE;}
return TRUE;}
return FALSE;}
function unRegister($key, $sessName = 'default') {if (!isSet($this->_isValid[$sessName])) $this->_init($sessName);if ($this->_isValid[$sessName]) {unset($this->_sessData[$sessName]['data'][$key]);$this->_sessInfoList[$sessName]['state']['forceWrite'] = TRUE;return TRUE;}
return FALSE;}
function isRegistered($key, $sessName = 'default') {if (!isSet($this->_isValid[$sessName])) $this->_init($sessName);if ($this->_isValid[$sessName]) {return (bool)isSet($this->_sessData[$sessName]['data'][$key]);}
return FALSE;}
function &getVar($key, $sessName='default') {if (!isSet($this->_isValid[$sessName])) $this->_init($sessName);if ($this->_isValid[$sessName]) {if (isSet($this->_sessData[$sessName]['data'][$key])) {return $this->_sessData[$sessName]['data'][$key];}
}
return NULL;}
function destroy($sessName='default') {   do {if (empty($this->_sessInfoList[$sessName])) break; $prop  = $this->_sessInfoList[$sessName]['prop'];$state = $this->_sessInfoList[$sessName]['state'];if (empty($state['fileName']))  break; $filePath = $prop['path'] . $state['fileName'];@unlink($filePath);} while(FALSE);unset($this->_sessInfoList[$sessName]);unset($this->_sessData[$sessName]);unset($this->_isValid[$sessName]);return TRUE;}
function destroySID($sid, $destroySessName=NULL) {$status = FALSE;foreach ($this->_sessInfoList as $sessName => $sessStruct) {if ($sessName['state']['SID'] === $sid) {if (!empty($destroySessName)) { if ($destroySessName !== $sessName) continue; }
$this->destroy($sessName);$status = TRUE;}
}
if (!$status) {$fullPath = session_save_path() . '/sess_' . $sid;return @unlink($fullPath);}
return $status;}
function reset($sessName='default') {if (!empty($this->_sessInfoList[$sessName])) {$this->_sessInfoList[$sessName]['state']['createTime'] = time();$this->_sessInfoList[$sessName]['state']['accessTime'] = time();$this->_sessInfoList[$sessName]['state']['forceWrite'] = TRUE;$this->_sessData[$sessName] = array('data'=>NULL);}
return TRUE;}
function setProperty($prop, $sessName='default') {$__func__ = 'setProperty';$status = FALSE;do {if (!is_array($prop)) {Bs_Error::setError('Invalid frist parameter, is not a array.', 'ERROR', __LINE__, $__func__, __FILE__);break; }
$this->_userProps[$sessName] = $prop;$status = TRUE;} while(FALSE);return $status;}
function getSid() {return session_id();}
function write_close() {$__func__ = 'write_close';$status = TRUE;foreach ($this->_sessData as $sessName => $sessStruct) {if (empty($this->_sessInfoList)) break;$filePath = $this->_sessInfoList[$sessName]['prop']['path'] . $this->_sessInfoList[$sessName]['state']['fileName'];$dataStream = serialize($sessStruct['data']);$md5 = md5($dataStream);if (empty($this->_sessInfoList[$sessName]['state']['forceWrite'])) {if ($this->_sessInfoList[$sessName]['state']['md5'] == $md5) continue;}
$this->_sessInfoList[$sessName]['state']['forceWrite'] = FALSE;$this->_sessInfoList[$sessName]['state']['md5'] = $md5;if (!empty($fp)) @fclose($fp);if (($fp = @fopen($filePath, 'wb')) === FALSE) {Bs_Error::setError("Failed to open sesssion-file '{$filePath}' for write.", 'ERROR', __LINE__, $__func__, __FILE__);$status = FALSE;continue;}
if (!@fwrite($fp, $dataStream, strLen($dataStream))) {@unlink($filePath);Bs_Error::setError("Failed to write to file '{$filePath}'.", 'ERROR', __LINE__, $__func__, __FILE__);$status = FALSE;continue;}
}
@fclose($fp);session_write_close();$this->_sessStarted = NULL;return $status;}
function gc() {$__func__ = 'gc';$status = TRUE;if ($this->_runProperties['garbage_lifetime'] == 0) return TRUE;global $APP;require_once($APP['path']['core'] . 'file/Bs_Dir.class.php');$listParams = array(
'regEx'      => ':^' . $this->_sessPrefix.':', 
'regFunction'=> 'preg_match',
'depth'      => 0, 
'returnType' => 'subpath'
);$alreadyParsed = array();$now = time();foreach ($this->_sessInfoList as $sessName => $sessStruct) {$prop = $this->_sessInfoList[$sessName]['prop'];if (isSet($alreadyParsed[$prop['path']])) continue; else $alreadyParsed[$prop['path']] = TRUE;$dir =& new Bs_Dir($prop['path']);$fileList = $dir->getFileList($listParams);if (isEx($fileList)) {$status = FALSE;Bs_Error::setError("File list fail with: " . $fileList->stackDump('last msg'), 'ERROR', __LINE__, $__func__, __FILE__);continue;}
foreach ($fileList as $fileName) {$filePath = $prop['path'] . $fileName;if (!($fileModTime = @filemtime($filePath))) {$status = FALSE;Bs_Error::setError("Failed to get file  mod-time from: " . $filePath, 'ERROR', __LINE__, $__func__, __FILE__);break; }
$age = $now - $fileModTime;if ($age > ($this->_runProperties['garbage_lifetime']*60)) {@unlink($filePath);}
}
}
return $status;}
function _htmlBar($delta, $h, $color='green') {if ($h==0) $h=1;$p = round(100*$delta/$h);if ($p==0) $p=1;$out =<<<EOD
    <span style="font-size: 10px;">{$p}%</span>
    <table width="100%" border=1>
    <tr>
        <td width="{$p}%" style="background: {$color}; font-size: 9px;">&nbsp;</td>
        <td style="background: Silver; border-right: thin; border-right-color: Black;"></td>
    </tr>
    </table>
    <span style="font-size: 10px;">{$delta}sec of {$h}sec</span>
EOD;
return $out;}
function toHtml($sessName='default') {$sData = $this->_sessInfoList[$sessName];$out = "<hr>";$out .= "<div style='font-size:12px;border:thin solid'>Sessions currently in use: <br>";foreach ($this->_sessInfoList as $key => $dev0) {$out .= $key.', ';}
$out .= "</div>";$out .= "<H3>Session Name: '{$sessName}'</H3>";if ($sData['prop']['maxStandbyTime']>0) {$passedStandByTime = $sData['state']['lastAccessTime']-time() + $sData['prop']['maxStandbyTime']*60;if ($passedStandByTime>0) {$passedStandByTime = $this->_htmlBar($passedStandByTime, ($sData['prop']['maxStandbyTime']*60));} else {$passedStandByTime = $this->_htmlBar(1,100);}
} else {$passedStandByTime = $this->_htmlBar(1);}
if($sData['prop']['maxLifeTime']>0) {$endTime = $sData['state']['createTime'] + $sData['prop']['maxLifeTime'] *60;$deltaTime = $endTime - time();$sessionEnd = $this->_htmlBar($deltaTime, ($sData['prop']['maxLifeTime'] *60));} else {$endTime = $deltaTime = 0;$sessionEnd = "[Until browser closes]";}
$out .= "<TABLE width='50%'>\n"
."<TR><TD width='30%' nowrap>Session Create Time:</TD><TD>" . date('H:i:s', $sData['state']['createTime']) . "</TD></TR>\n"
."<TR><TD nowrap>Session Last Access Time:</TD><TD>" . date('H:i:s', $sData['state']['accessTime'])  . "</TD></TR>\n"
."<TR><TD style='font-size: 10px;'>&nbsp;</TD></TR>"
."<TR><TD nowrap>Standby Time :</TD><TD>". $sData['prop']['maxStandbyTime'] ." min</TD></TR>\n"
."<TR><TD nowrap>Standby Time left:</TD><TD>". $passedStandByTime ."</TD></TR>\n"
."<TR><TD style='font-size: 10px;'>&nbsp;</TD></TR>"
."<TR><TD nowrap>Session Time:</TD><TD>". $sData['prop']['maxLifeTime'] ." min</TD></TR>\n"
."<TR><TD nowrap>Session Time left:</TD><TD>". $sessionEnd  ."</TD></TR>\n"
."</TABLE><BR>"
;$out .= "<TABLE border='1'>\n"
."<TR><TH align='left' colspan='2'>Run Properties:</TH></TR>\n"
."<TR><TD>Chances that Garbage Collector will run (100% would mean on every request)</TD><TD>{$this->_runProperties['gc']}%</TD></TR>\n"
."<TR><TD>The age (in min) that a session file may have befor it's assumed garbage and is deleted.<br> 0 means never.</TD><TD>{$this->_runProperties['garbage_lifetime']}min</TD></TR>\n"
."</TABLE><br>\n"
;$out .= "<TABLE border='1'>\n";$out .= "<Tr><TH align='left' colspan='2'>Session Properties:</TH></TR>\n";foreach ($sData['prop'] as $key => $val) {if (strpos($key, 'Time')!==FALSE) $val = ($val==0) ? "Until browser closes" : "{$val} min";$out .= "<TR><TD>{$key}</TD><TD>{$val}</TD></TR>\n";}
$out .= "</TABLE><BR>\n";$out .= "<TABLE border='1'>\n";$out .= "<Tr><TH align='left' colspan='2'>Session State:</TH></TR>\n";foreach ($sData['state'] as $key => $val) {if (strpos($key, 'Time')!==FALSE) $val = date('Y-m-d H:i:s', $val) . " (= {$val})";$out .= "<TR><TD>{$key}</TD><TD>{$val}</TD></TR>\n";}
$out .= "</TABLE><HR>\n";return $out;}
function _calcPropertyLimits($prop=NULL) {$cleanProp = array();if (empty($prop)) {$cleanProp = $this->_sessPropTemplate;} else {foreach ($this->_sessPropTemplate as $key => $val) {$cleanProp[$key] = isSet($prop[$key]) ? $prop[$key] : $val;}
}
$systemMaxLifeTime = ini_get('session.gc_maxlifetime');if (!is_numeric($systemMaxLifeTime)) {$systemMaxLifeTime = 0;}
if ($systemMaxLifeTime >0) {if (($cleanProp['maxLifeTime']==0) OR ($cleanProp['maxLifeTime']*60 >$systemMaxLifeTime)) {ini_set('session.gc_maxlifetime', 60*$cleanProp['maxLifeTime']);}
}
if ($cleanProp['maxLifeTime']>0 AND $this->_runProperties['garbage_lifetime']>0) {if ($cleanProp['maxLifeTime'] > $this->_runProperties['garbage_lifetime']) {$cleanProp['maxLifeTime'] = $this->_runProperties['garbage_lifetime'];}
}
if ($cleanProp['maxLifeTime']>0 AND $cleanProp['maxStandbyTime']>0) {if ($cleanProp['maxLifeTime'] < $cleanProp['maxStandbyTime']) {$cleanProp['maxStandbyTime'] = $cleanProp['maxLifeTime']; }
}
return $cleanProp;}
function _setup($sessName) {   if (isSet($this->_userProps[$sessName])) {$this->_sessInfoList[$sessName]['prop'] = $this->_calcPropertyLimits($this->_userProps[$sessName]);} else {$this->_sessInfoList[$sessName]['prop'] = $this->_calcPropertyLimits();}
$sid = md5(uniqid(mt_rand()));$prefix = $this->_sessPrefix . $sessName .'_';$this->_sessInfoList[$sessName]['state'] = array (
'SID'         => $sid,        'filePrefix'  => $prefix,     'fileName'    => $prefix . $sid,
'createTime'  => time(),      'accessTime'  => time(),      'lastAccessTime' => time(),   'md5'         => '',          'forceWrite'  => FALSE        );$this->_sessData[$sessName] = array (
'data'        => NULL
);return TRUE;}
function _init($sessName) {$__func__ = '_init';if (isSet($this->_isValid[$sessName]) AND ($this->_isValid[$sessName] !== NULL)) {return $this->_isValid[$sessName];}
$status = FALSE;do {if (!$this->start()) {Bs_Error::setError("Unable to start PHP session handler session_start()", 'ERROR', __LINE__, $__func__, __FILE__);break; }
if (empty($this->_sessInfoList[$sessName])) {$status = $this->_setup($sessName);break; }
$prop  = $this->_sessInfoList[$sessName]['prop'];$state = $this->_sessInfoList[$sessName]['state'];if ($prop['version'] != $this->_souceVersion)  {$this->destroy($sessName);$status = $this->_setup($sessName);break; }
if ($this->_runProperties['gc']>0) {$randVal = mt_rand(1, 100);if ($randVal <= $this->_runProperties['gc']) $this->gc();}
$now = time();if ($prop['maxLifeTime'] > 0) {if (($now - $state['createTime']) > ($prop['maxLifeTime']*60)) break; }
if ($prop['maxStandbyTime'] > 0) {if (($now - $state['accessTime']) > ($prop['maxStandbyTime']*60)) break; }
if (!$this->_fetch($sessName))  break; $status = TRUE;} while(FALSE);if ($status) {$this->_sessInfoList[$sessName]['state']['lastAccessTime'] = $this->_sessInfoList[$sessName]['state']['accessTime'];$this->_sessInfoList[$sessName]['state']['accessTime'] = time();} else {$this->_isValid[$sessName] = TRUE; $status = $this->reset($sessName);}
$this->_isValid[$sessName] = $status;}
function _fetch($sessName) {  $__func__ = '_fetch';$status = FALSE;$filePath = $this->_sessInfoList[$sessName]['prop']['path'] . $this->_sessInfoList[$sessName]['state']['fileName'];do {if (!($fp = @fopen($filePath, 'rb'))) {Bs_Error::setError("Failed to open sesssion-file '{$filePath}' for read.", 'ERROR', __LINE__, $__func__, __FILE__);break;}
$dataStream = @fread($fp, filesize($filePath));if (empty($dataStream)) {Bs_Error::setError("Failed to read sesssion-file '{$filePath}' OR empty.", 'ERROR', __LINE__, $__func__, __FILE__);break;}
$this->_sessData[$sessName] = array (
'data' => @unserialize($dataStream)
);if (empty($this->_sessData[$sessName]['data'])) {Bs_Error::setError("Empty data or failed to unserialize sesssion-data fome file '{$filePath}'.", 'ERROR', __LINE__, $__func__, __FILE__);}
$status = TRUE;} while(FALSE);return $status;}
function _pathCheck(&$path) {$__func__ = '_pathCheck';if (empty($path)) $path = ini_get('session.save_path');$path = str_replace("\\", '/', trim($path));  if (substr($path, -1) != '/') $path .= '/';$status = FALSE;do { if (!is_dir($path)) {if (!@mkdir($path, 0770)) {$msg = "Faild to make the DIR [$path] to store session data.";break; }
}
if (!is_dir($path) OR !is_writeable($path)) {$msg = "Unable to store session data. Invalid dir: '{$path}'. ";if (!is_dir($path)) {$msg .= "Does not exsist.";} else {$msg .= "No write access.";}
break; }
$status = TRUE;} while(FALSE);if (!$status) {Bs_Error::setError($msg, 'ERROR', __LINE__, $__func__, __FILE__);trigger_error($msg, E_USER_WARNING);}
return $status;}
}
$GLOBALS['Bs_SimpleSession'] =& new Bs_SimpleSession(); $runProperties = array();if (isSet($GLOBALS['APP']) AND isSet($GLOBALS['APP']['sess'])) {$runProperties = $GLOBALS['APP']['sess'];}
$GLOBALS['Bs_SimpleSession']->start($runProperties);$GLOBALS['Bs_SimpleSession']->setProperty($runProperties);?>
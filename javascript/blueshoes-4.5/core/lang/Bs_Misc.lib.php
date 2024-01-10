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
function bsSessionBrowscap() {global $APP;do {if (@is_object($GLOBALS['bsSession'])) {$bsSession =& $GLOBALS['bsSession'];} elseif (@is_object($GLOBALS['Bs_SimpleSession'])) {$bsSession =& $GLOBALS['Bs_SimpleSession'];} else {break;}
include_once($APP['path']['core'] . 'net/http/Bs_Browscap.class.php');if ($bsSession->isRegistered('browscap')) {$GLOBALS['Bs_Browscap']->data = $bsSession->getVar('browscap');break; }
$GLOBALS['Bs_Browscap']->detectUserType();if ((@$_GET['bcRun']) || (($GLOBALS['Bs_Browscap']->data['userType']['type'] != 'user') && ($GLOBALS['Bs_Browscap']->data['userType']['type'] != 'proxy'))) {$GLOBALS['Bs_Browscap']->compute();$bsSession->register('browscap', $GLOBALS['Bs_Browscap']->data);if (@$_GET['bcRun']) {$postFullPath = getTmp() . $bsSession->getSid() . '.post';if (file_exists($postFullPath)) {$postData = unserialize(join('', file($postFullPath)));$_POST = $postData;$gpcChars = explode(' ', chunk_split(strToUpper(ini_get('gpc_order')), 1, ' '));$_REQUEST = array();foreach($gpcChars as $gpcChar) {switch ($gpcChar) {case 'G':
if (!empty($_GET)) $_REQUEST = array_merge($_GET, $_REQUEST);break;case 'P':
if (!empty($_POST)) $_REQUEST = array_merge($_POST, $_REQUEST);break;case 'C':
if (!empty($_COOKIE)) $_REQUEST = array_merge($_COOKIE, $_REQUEST);break;}
}
@unlink($postFullPath);}
}
} else {if (!empty($_POST)) {do {$postFileContent = serialize($_POST);$postFullPath = getTmp() . $bsSession->getSid() . '.post';$fp = @fopen($postFullPath, 'wb');if (!$fp) break;$status = @fwrite($fp, $postFileContent);@fclose($fp); } while (FALSE);}
if (@$APP['browscap']['template']) $GLOBALS['Bs_Browscap']->runTestTemplate = $APP['browscap']['template'];if (@$APP['browscap']['timeout'])  $GLOBALS['Bs_Browscap']->runTestTimeout  = $APP['browscap']['timeout'];$GLOBALS['Bs_Browscap']->runTest();}
} while(FALSE);}
function redirect($url) {header('Location: '. $url);if (false) {header("HTTP/1.1 303 REDIRECT");} else {header("HTTP/1.0 302 Moved Temporarily");}
exit;}
function bs_addIncludePath($path) {global $APP;include_once($APP['path']['core'] . 'util/Bs_System.class.php');global $Bs_System;if ($Bs_System->isWindows()) {$separator = ';';} else {$separator = ':';}
$pathList = explode($separator, ini_get('include_path'));$pathList[] = $path;ini_set('include_path', join($separator, $pathList));}
function rewriteUrlSession($string, $params) {static $sid;$sid = $GLOBALS['bsSession']->getSid();static $tags;$tags = &$APP['sess']['tags'];static $domains;$domains = &$APP['sess']['domains'];return $string;}
function registerOutputHandler($function, $params=array()) {$GLOBALS['bs_outputHandler'][$function] = &$params;}
function &applyOutputHandlers(&$string) {if ((is_array($GLOBALS['bs_outputHandler'])) && (!empty($GLOBALS['bs_outputHandler']))) {$t = &$GLOBALS['bs_outputHandler'];reset($t);while (list($k) = each($t)) {$string = $k($string, $t[$k]);}
}
}
function spit($string) {$string = &applyOutputHandlers($string);echo $string;}
function bsSetCookie($name, $value='', $expire=NULL, $path=NULL, $domain=NULL, $secure=NULL) {if (!is_null($expire)) $expire = (double)$expire; if (is_null($expire)) {return setcookie($name, $value);} elseif (is_null($path)) {return setcookie($name, $value, $expire);} elseif (is_null($domain)) {return setcookie($name, $value, $expire, $path);} elseif (is_null($secure)) {return setcookie($name, $value, $expire, $path, $domain);} else {return setcookie($name, $value, $expire, $path, $domain, $secure);}
}
function arrayToHtml($array) {if (is_array($array) OR is_object($array)) {$type = getType($array);$ret = "<br>LOOPING  {$type}<br>";if (sizeOf($array) == 0) {$ret .= 'array is empty<br>';} else {reset($array);while (list($k) = each($array)) {if (is_array($array)) {$ret .= "{$k} => {$array[$k]}<br>\n";} else {$ret .= "{$k} => {$array->$k}<br>\n";}
}
}
} else {$ret .= 'not an array<br>';}
$ret .= '<br>';return $ret;}
function dump(&$param, $noEcho=FALSE) {ob_start();var_dump($param);$out = ob_get_contents();ob_end_clean();$out = '<pre>' . str_replace("=>\n  ", '=>', $out) . '</pre>';if ($noEcho) {return $out;}
echo $out;return '';}
function bs_dump($param, $noEcho=FALSE) {ob_start();var_dump($param);$out = ob_get_contents();ob_end_clean();$s = '';if (PHPVERSION() >= 4.3) {$traceArr = debug_backtrace();$trace = array_shift($traceArr);$s =  "<b>DUMP called from: " . basename($trace['file']) . "[{$trace['line']}]</b>\n";}
$out = '<pre>' . $s . htmlspecialchars(str_replace("=>\n  ", '=>', $out)) . '</pre>';if ($noEcho) {return $out;}
echo $out;return '';}
function getAbsoluteWebPath() {$t = getAbsoluteWebFile();if (substr($t, -1) == '/') return $t;$pos = strrpos($t, '/');return substr($t, 0, $pos +1);}
function getAbsoluteWebFile() {$scheme = 'http://';if (isSet($GLOBALS['HTTP_SERVER_VARS']['HTTPS'])) {$t = strToLower($GLOBALS['HTTP_SERVER_VARS']['HTTPS']);if (($t == 'on') || ($t == 'true') || ($t == 'yes')) $scheme = 'https://';}
$ret = $scheme . $_SERVER['SERVER_NAME'];if (($_SERVER['SERVER_PORT'] != '80') && (!empty($_SERVER['SERVER_PORT']))) {$ret .= ':' . $_SERVER['SERVER_PORT'];}
$ret .= $_SERVER['REQUEST_URI'];return $ret;}
function getAbsoluteWebFileQuery() {if ((isSet($_SERVER['QUERY_STRING'])) && (!empty($_SERVER['QUERY_STRING']))) {return getAbsoluteWebFile() . '?' . $_SERVER['QUERY_STRING'];} else {return getAbsoluteWebFile();}
}
function getAbsolutePath() {$t = $GLOBALS['HTTP_SERVER_VARS']['SCRIPT_FILENAME'];if (substr($t, -1) == '/') return $t;$pos = strrpos($t, '/');if ($pos !== FALSE) {return substr($t, 0, $pos +1);}
return '/';}
function getAbsoluteFile() {return $GLOBALS['HTTP_SERVER_VARS']['SCRIPT_FILENAME'];}
function isTrue($value) {static $trueVals = array('true','on','y','yes',1,'1','ja','oui');if (empty($value)) return FALSE;if (is_string($value))  $value = strToLower($value);if (($value === TRUE) || in_array($value, $trueVals)) {return TRUE;} else {return FALSE;}
}
function boolToString($param) {if ($param) {return 'true';} else {return 'false';}
}
function isAlphaNumeric(&$var) {switch (gettype($var)) {case 'string':
case 'integer':
case 'double':
return TRUE;}
return FALSE;}
function isInvisible($string) {return (bool)preg_match('/^\s*$/', $string);}
function isWhite($string) {return (bool)preg_match('/^\s*$/', $string);}
$MISC_errorHandler_lastError = array();function misc_ErrorHandler($errNr, $errMsg, $errFile, $errLine, $errContext) {global $MISC_errorHandler_lastError;$errType = '';switch ($errNr) {case E_ERROR   : $errType = 'ERROR'; break;case E_WARNING : $errType = 'WARNING'; break;case E_PARSE   : $errType = 'PARSE ERROR'; break;case E_NOTICE  : $errType = 'NOTICE'; break;case E_USER_ERROR   : $errType = 'USER_ERROR'; break;case E_USER_WARNING : $errType = 'USER_WARNING'; break;case E_USER_NOTICE  : $errType = 'USER_NOTICE'; break;case E_CORE_ERROR      : $errType = 'CORE_ERROR'; break;case E_CORE_WARNING    : $errType = 'CORE_WARNING'; break;case E_COMPILE_ERROR   : $errType = 'COMPILE_ERROR'; break;case E_COMPILE_WARNING : $errType = 'COMPILE_WARNING'; break;default : $errType = 'ERROE Unknown';}
$MISC_errorHandler_lastError = array( 
'errNr'   => $errNr,
'errType' => $errType,
'errMsg'  => $errMsg,
'errFile' => $errFile,
'errLine' => $errLine,
'toHtml' => "[{$errFile}:{$errLine}] <strong>{$errType}</strong>: {$errMsg}"
);}
$MISC_evalWrapperContextDefault = array(
'display_errors' => TRUE,   'sourceFile'    => '',      'file'          => '',      'class'         => '',      'function'      => '',      'line'          => 'xx',    'security'      => 'high',  'error'       => 0,       'errNr'       => -1,      'errType'     => '',      'errMsg'      => '',      'errLine'     => '',      );function evalWrapper($phpCode, &$context, $params=array()) {global $MISC_evalWrapperContextDefault;global $MISC_errorHandler_lastError;$MISC_evalWrapper =& $context;foreach ($MISC_evalWrapperContextDefault as $key => $val) {if (!isSet($context[$key])) $context[$key] = $val;}
if (PHPVERSION() >= 4.3) {$traceArr = debug_backtrace();$trace = array_shift($traceArr);$context['file'] = basename($trace['file']);$context['class'] = isSet($trace['class']) ? $trace['class'] : '';$context['function'] = $trace['function'];$context['line'] = $trace['line'];}
foreach($params as $key => $dev0) {${$key} =& $params[$key];}
$regex  = isTrue(ini_get('short_open_tag')) ? '/\<\?(php|)/iU' : '/\<\?php/iU';if (preg_match($regex, $phpCode)) {if (preg_match('/^\s*\?\>/U', $phpCode)) {} else {$phpCode = '?>' .$phpCode;}
}
$oldTrackErr = ini_set('track_errors', 1);$oldErrLevel = error_reporting (E_ALL);ob_start();                                set_error_handler('misc_ErrorHandler');  $MISC_errorHandler_lastError = NULL;     $php_errormsg = NULL;                    $ret = eval($phpCode);                   restore_error_handler();                 $out = ob_get_contents();                ob_end_clean(); ini_set('track_errors', $oldTrackErr);error_reporting ($oldErrLevel);$error = FALSE;do {if (!empty($MISC_errorHandler_lastError)) {$context['error']   = 1;$context['errNr']   = $MISC_errorHandler_lastError['errNr'];$context['errType'] = $MISC_errorHandler_lastError['errType'];$context['errMsg']  = $MISC_errorHandler_lastError['errMsg'];$context['errLine'] = $MISC_errorHandler_lastError['errLine'];$error = TRUE;break;}
if (!empty($php_errormsg)) {preg_match("/\d+/", substr($out, -16), $errLine);$context['error']   = 2;$context['errNr']   = E_PARSE;$context['errType'] = 'PARSE ERROR';$context['errMsg']  = $php_errormsg;$context['errLine'] = $errLine[0];$out = ''; $error = TRUE;break;}
} while (FALSE);if ($context['display_errors'] AND $error) {$bb = $be = '';if (ini_get('html_errors')) {$bb = '<b>'; $be = '</b>';} 
$out .= "\n {$bb}{$context['errType']} during eval():{$be} {$context['errMsg']}. ";if (!empty($context['sourceFile'])) {$out .= "PHP-source was {$bb}'{$context['sourceFile']}:({$context['errLine']})'{$be}. ";}
$out .= "Called from '{$context['file']}::{$context['function']}({$context['line']})\n";}
return $out;}
function bs_storeVar($theVar, $fullFilePath) {$phpDataStr = var_export($theVar, TRUE);$status = FALSE;do {if (!$fp = @fopen($fullFilePath , 'wb')) break; if (FALSE === @fwrite($fp, $phpDataStr)) break; $status = TRUE;} while(FALSE);@fclose($fp); return $status;}
function bs_loadVar(&$theVar, $fullFilePath) {$status = FALSE;$result = NULL;do {if (!$fp = @fopen($fullFilePath , 'r')) break; set_error_handler('misc_ErrorHandler');  $MISC_errorHandler_lastError = NULL;     $phpData = fread($fp, filesize($fullFilePath));$result = eval('return '.$phpData.';');restore_error_handler();                 if (!empty($MISC_errorHandler_lastError)) {$result  = $MISC_errorHandler_lastError['errMsg'];break; }
$status = TRUE;} while(FALSE);@fclose($fp); $theVar = $result;return $status;}
function evalErrorHandler($errNo, $errStr, $errFile, $errLine) {$msg = $errStr . " [ERROR {$errNo}] in " . basename($errFile) . "({$errLine}). <br>\n Evaluated Code: '" . htmlspecialchars($GLOBALS['MISC_evalWrapCode']) ."'";trigger_error($msg, E_USER_WARNING);  }
function evalWrap($phpCode, $security='high', $suppressErrors=FALSE, $params=array()) {$GLOBALS['MISC_evalWrapCode'] = $phpCode;foreach($params as $key => $dev0) {${$key} =& $params[$key];}
if (preg_match('/\<\?(php|)/iU', $phpCode)) {$phpCode = '?>' .$phpCode;}
ob_start(); if ($suppressErrors) {$ret = @eval($phpCode);} else {$oldErrVal = error_reporting(E_ALL);set_error_handler('evalErrorHandler');$ret = eval($phpCode);restore_error_handler();error_reporting ($oldErrVal);}
$out = ob_get_contents();ob_end_clean(); unset($GLOBALS['MISC_evalWrapCode']);if (empty($out)) $out = $ret;return $out;}
function bs_registerShutdownMethod($line, $file, &$object, $methodName, $params=NULL) {global  $bsMisc_shutdownArr;$status = FALSE;do { $err = "";if (!is_object($object)) {$err = "Not an object!";break; }
if (!method_exists($object, $methodName)) {$err = "Non existing method in the supplied object! method name: '{$methodName}'.";break; }
if (empty($params)) { $bsMisc_shutdownArr[] = array(&$object, $methodName);} else {$bsMisc_shutdownArr[] = array(&$object, $methodName, $params);}
$status = TRUE;} while (FALSE);if (!$status) {$file = basename($file);trigger_error("In [{$file}:{$line}] Failed to register '" . get_class($object) ."->". $methodName ."'. {$err}" , E_USER_ERROR);}
return $status;}
$bsMisc_shutdownArr = array();function bs_testShutdown(&$object, $methodName) {global  $bsMisc_shutdownArr;$status = FALSE;if (!empty($bsMisc_shutdownArr)) {$sdaSize = sizeOf($bsMisc_shutdownArr);for ($k=0; $k<$sdaSize; $k++) {if ( ( $bsMisc_shutdownArr[$k][0] === $object )
AND ( $bsMisc_shutdownArr[$k][1] === $methodName ) ) {$status = TRUE;break;}
}
if ($status) _bs_shutdown($k);}
return $status;}
function _bs_shutdown($triggerNr=-1) {global  $bsMisc_shutdownArr;if (!empty($bsMisc_shutdownArr)) {$sdaSize = sizeOf($bsMisc_shutdownArr);for ($k=0; $k<$sdaSize; $k++) {if ($triggerNr>0 AND $k!==$triggerNr) continue; $object =& $bsMisc_shutdownArr[$k][0];$method =  $bsMisc_shutdownArr[$k][1];if (!empty($bsMisc_shutdownArr[$k][2])) {$params = $bsMisc_shutdownArr[$k][2];$t = array();$pSize = sizeOf($params);for ($j=0; $j<$pSize; $j++) {$t[] = "\$params[$j]";}
$evalStr = "\$object->{$method}(" . join(', ', $t) . ");";eval($evalStr);} else {$object->$method();}
}
}
}
register_shutdown_function("_bs_shutdown");function &classHyrachy(&$classOrObject) {$objHyrachy = array();$nr = 0;if (is_object($classOrObject)) {$className = get_class($classOrObject);$objHyrachy[$nr] = array (
'name'    => $className,
'vars'       => get_object_vars  ($classOrObject),
'methods'    => get_class_methods($className),
'allVars'    => get_object_vars  ($classOrObject),
'allMethods' => get_class_methods($className)
);} elseif (class_exists($classOrObject)) {$className = $classOrObject;$objHyrachy[$nr] = array (
'name'       => $className,
'vars'       => get_class_vars($className),
'methods'    => get_class_methods($className),
'allVars'    => get_class_vars($className),
'allMethods' => get_class_methods($className)
);} else {return FALSE;}
$className = get_parent_class($className);while (!empty($className)) {$objHyrachy[++$nr] = array (
'name'       => $className,
'vars'       => get_class_vars($className),
'methods'    => get_class_methods($className),
'allVars'    => &$objHyrachy[0]['allVars'],    'allMethods' => &$objHyrachy[0]['allMethods']  );$className = get_parent_class($className);}
$levelSize = sizeOf($objHyrachy)-1;for ($i=0; $i<$levelSize; $i++ ) {$parentClass = &$objHyrachy[$i+1];$childClass  = &$objHyrachy[$i];$ownVars = array();$parentVarsArray = array_keys($parentClass['vars']);reset($childClass['vars']);while (list($varName) = each($childClass['vars'])) {if (in_array($varName, $parentVarsArray)) continue;    $ownVars[$varName] = $childClass['vars'][$varName];}
$childClass['vars'] = $ownVars;$ownMethods = array();$parentMethodsArray = array_keys($parentClass['methods']);reset($childClass['methods']);while (list($methodName) = each($childClass['methods'])) {if (in_array($methodName, $parentMethodsArray)) continue;    $ownMethods[$methodName] = $childClass['methods'][$methodName];}
$childClass['methods'] = $ownMethods;}
$rObjHyrachy = array();$size = sizeOf($objHyrachy);for ($i=0; $i<$size; $i++ ) {$rObjHyrachy[$i] = &$objHyrachy[$size-1-$i];}
return $rObjHyrachy;}
function parseUid($UID) {if (strpos($UID, ';') === FALSE) {if ($firstDot = strpos($UID, '.')) {$ret['name'] = substr($UID, 0, $firstDot);$lastDot = strrpos($UID, '.');$partAfterLastDot = substr($UID, $lastDot);if (is_numeric($partAfterLastDot)) {$ret['version'] = substr($UID, $firstDot +1);} else {$ret['version'] = substr($UID, $firstDot +1, $lastDot);$ret['mime']    = substr($UID, $lastDot);}
} else {$ret['name'] = $UID;}
} else {$t = explode(';', $UID);while (list($k) = each($t)) {$t2 = explode('=', $t[$k]);switch ($t2[0]) {case 'n':
$ret['name'] = $t2[1];break;case 'p':
$ret['part'] = $t2[1];break;case 'l':
$ret['language'] = $t2[1];break;case 'v':
$ret['version'] = $t2[1];break;case 'm':
$ret['mime'] = $t2[1];break;default:
}
}
}
return $ret;}
function uidArrayToString($UID) {if (isSet($UID['name']))     $ret[] = 'n=' . $UID['name'];if (isSet($UID['part']))     $ret[] = 'p=' . $UID['part'];if (isSet($UID['language'])) $ret[] = 'l=' . $UID['language'];if (isSet($UID['version']))  $ret[] = 'v=' . $UID['version'];if (isSet($UID['mime']))     $ret[] = 'm=' . $UID['mime'];return join(';', $ret);}
function getTmp() {global $APP;if (isSet($APP['path']['tmp']) && !is_null($APP['path']['tmp']) && is_dir($APP['path']['tmp'])) return $APP['path']['tmp'];include_once($APP['path']['core'] . 'util/Bs_System.class.php');global $Bs_System;if ($Bs_System->isWindows()) {if (!empty($_ENV['TEMP'])) {$ret = str_replace('\\', '/', $_ENV['TEMP']) . '/';} elseif (!empty($_ENV['TMP'])) {$ret = str_replace('\\', '/', $_ENV['TMP']) . '/';}
if (isSet($ret)) {if (!is_dir($ret)) {$status = @mkdir($ret);if ((!$status) || (!is_readable($ret))) $ret = null;} elseif (!is_readable($ret)) {$ret = null;}
}
if (!isSet($ret)) {if (is_dir('/tmp')) {$ret = '/tmp/';} else {$status = @mkdir('/tmp');if ($status) {$ret = '/tmp/';} else {return FALSE;}
}
}
} else {if (is_dir('/tmp')) {$ret = '/tmp/';} else {return FALSE;}
}
$APP['path']['tmp'] = $ret;return $ret;}
function bs_lazyLoadClass($classPath, $useCorePath=TRUE) {global $APP; if ($useCorePath) {$classPath = $APP['path']['core'] . $classPath;}
ob_start();include_once($classPath);$error = ob_get_contents();ob_end_clean();if (!empty($error)) return FALSE;return TRUE;}
function bs_lazyLoadPackage($packageName, $useCorePath=TRUE) {switch ($packageName) {case 'html/form':
$incl = array('html/form/Bs_Form.class.php');break;case 'html/form/domapi':
$incl = array(
'html/form/domapi/Bs_DaFormFieldColorPicker2.class.php', 
'html/form/domapi/Bs_DaFormFieldComboBox.class.php', 
'html/form/domapi/Bs_DaFormFieldDatePicker.class.php', 
'html/form/domapi/Bs_DaFormFieldListBox.class.php', 
'html/form/domapi/Bs_DaFormFieldSpinEdit.class.php', 
'html/form/domapi/Bs_DaFormPageControl.class.php', 
);break;case 'html/form/specialfields':
$incl = array(
'html/form/specialfields/Bs_FormFieldChVisa.class.php', 
'html/form/specialfields/Bs_FormFieldCountryList.class.php', 
'html/form/specialfields/Bs_FormFieldDatePicker.class.php', 
'html/form/specialfields/Bs_FormFieldEmail.class.php', 
'html/form/specialfields/Bs_FormFieldFileBrowser.class.php', 
'html/form/specialfields/Bs_FormFieldFirstname.class.php', 
'html/form/specialfields/Bs_FormFieldLastname.class.php', 
'html/form/specialfields/Bs_FormFieldSex.class.php', 
'html/form/specialfields/Bs_FormFieldSlider.class.php', 
'html/form/specialfields/Bs_FormFieldSpreadsheet.class.php', 
'html/form/specialfields/Bs_FormFieldTree.class.php', 
'html/form/specialfields/Bs_FormFieldEditor.class.php', 
'html/form/specialfields/Bs_FormFieldWysiwyg.class.php', 
'html/form/specialfields/Bs_FormFieldCheckboxJs.class.php', 
'html/form/specialfields/Bs_FormFieldRadioJs.class.php', 
);break;default:
return FALSE;}
while (list(,$classPath) = each($incl)) {if (!bs_lazyLoadClass($classPath, $useCorePath)) return FALSE;}
return TRUE;}
function levenshteinPercent($one, $two) {$oneLen = strlen($one);$twoLen = strlen($two);if ($oneLen > $twoLen) {$len = $oneLen;} else {$len = $twoLen;}
$lev = levenshtein($one, $two);return (int)($lev / $len * 100);}
function bs_undoMagicQuotes() {if (get_magic_quotes_gpc()) {bs_recursiveStripSlashes($_POST);bs_recursiveStripSlashes($_GET);bs_recursiveStripSlashes($_COOKIE);bs_recursiveStripSlashes($_REQUEST);ini_set('magic_quotes_gpc', '0'); }
}
function bs_recursiveStripSlashes(&$toStrip) {if (@is_array($toStrip)) {$keys = array_keys($toStrip);$kSize = sizeOf($keys);for ($i=0; $i<$kSize; $i++) {$val =& $toStrip[$keys[$i]];if (@is_array($val)) {bs_recursiveStripSlashes($val);} elseif (@is_string($val)) {$val = stripslashes($val);} else {}
}
} elseif (@is_string($toStrip)) {$toStrip = stripslashes($toStrip);}
}
function &classHyrachyToHtml(&$param) {$objHyrachy = NULL;if ( is_object($param) OR ( is_string($param) AND class_exists($param) ) ) {$objHyrachy = &classHyrachy($param);} elseif (is_array($param)) {$objHyrachy = &$param;} else {return 'The passed param [' .$param. '] is not a known Class nor an Object.';}
$size = sizeOf($objHyrachy);$parentClass = '';$out = '';for ($i=0; $i<$size; $i++ ) {$class = &$objHyrachy[$i];$out .= '<table border="1" style="font-family:verdana; font-size:11px; background:#ceeef8;">'
.   '<tr aline="center" style="font-size:14px; background:#bcd6f1;">'
.      '<td colspan=2>class <strong>' . $class['name'] . '</strong>'. (empty($parentClass) ? '' : " extends {$parentClass}") .'</td>'
.   '</tr><tr>'
.     '<th>Methods</th><th>Vars</th>'
.   '</tr><tr>'
.      '<td>';while (list(,$methName) = each($class['methods'])) {$out .=        $methName. '</br>';}
$out .=      '</td><td>';while (list($varName, $val) = each($class['vars'])) {$out .=        $varName. '="' .$val. '"<br>';}
$out .=     ' </td>'
.   '</tr>'
. '</table><br>';$parentClass = $class['name'];}
return $out;}
?>
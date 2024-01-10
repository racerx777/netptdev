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
define('BS_OBJECT_VERSION',  '4.2.$Revision: 1.3 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'lang/Bs_Error.class.php');function isEx($o) {return (bool) (@is_object($o) && ((get_class($o) == 'bs_exception') || (is_subclass_of($o, 'bs_exception'))));}
function isException($o) {return isEx($o); }  class Bs_Object {function Bs_Object() {}
function isException($o) {return isEx($o);}
function toString() {ob_start();var_dump($this);$dump .= ob_get_contents();ob_end_clean();$dump = str_replace("=>\n  ",'=>',$dump);$indent = "\n    ";return "\n" . str_replace("\n", $indent, $dump);}
function toHtml() {return str_replace(' ', '&nbsp;', nl2br(htmlspecialchars($this->toString())));}
function setError($msg, $msgType, $line=0, $func='', $file='') {if (PHPVERSION() >= 4.3) {$traceArr = debug_backtrace();$trace = array_shift($traceArr);$line  = $trace['line'];$func  = isSet($trace['class']) ? $trace['class'].'::' : '';$func .= $trace['function'];$file = $trace['file'];}
Bs_Error::setError($msg, $msgType, $line, $func, $file);}
function getLastError() {return Bs_Error::getLastError();}
function getLastErrors() {return Bs_Error::getLastErrors();}
function getErrors() {return Bs_Error::getErrors();}
function persist($objId='') {$status = FALSE;$path = empty($objId) ? tempnam('/tmp', 'bso') : $objId;$objData = serialize($this);do {if (empty($objData)) {Bs_Error::setError("Failed to serialze [". get_class($this) ."].", "ERROR");break;}
if (!file_exists($path)) {Bs_Error::setError("Failed to create/find file [{$path}].", "ERROR");break;}
if (!$fp = fopen($path, 'wb')) {Bs_Error::setError("Failed to open file [{$path}].", "ERROR");break;}
fwrite($fp, $objData);@fclose($fp);$status = $path;} while(FALSE);return $status;}
function unpersist($objId) {if (!is_string($objId)) return NULL;if (!$fp = fopen($objId, 'rb')) return NULL;$objData = fread ($fd, filesize($objId));return unserialize($objData);}
}
?>
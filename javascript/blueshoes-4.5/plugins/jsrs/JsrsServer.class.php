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
class JsrsServer {var $lastErr = '';var $propagateList = array();function propagateMethod(&$object, $methodName) {$status = FALSE;$err = '';$methodName = strtoLower($methodName);do { if (empty($object)) {$methodID = $methodName;} else {if (!method_exists($object, $methodName)) {$err = "Not an Object or not existing method in the suplied object!";break; }
$methodID = get_class($object) . '.' . $methodName;}
$this->propagateList[$methodID] = array('object'=>&$object, 'method'=>$methodName);$status = TRUE;} while (FALSE);if (!$status) {$file = basename($file);user_error("In [{$file}:{$line}] Failed to register '" . get_class($object) ."->". $methodName ."'. {$err}" , E_USER_ERROR);}
return $status;}
function propagateFunction($functionName) {return $this->propagateMethod($dummy=NULL, $functionName);}
function start() {$status = FALSE;$err = '';$ret = null;$squeezedWDDX = array('<a l','</a>','<r t','<r>','</r>','<v n','</v>','<b v','<s>','</s>','<n>','</n>','<dT>','</dT>');$expandedWDDX = array('<array length','</array>','<struct type','<struct>','</struct>','<var name','</var>','<boolean value','<string>','</string>','<number>','</number>','<dateTime>','</dateTime>');if (!isSet($_REQUEST)) { global $HTTP_POST_VARS;global $HTTP_GET_VARS;if (!isSet($HTTP_POST_VARS)) $HTTP_POST_VARS = array();if (!isSet($HTTP_GET_VARS))  $HTTP_GET_VARS = array();$_REQUEST = array_merge($HTTP_GET_VARS, $HTTP_POST_VARS);}
$callID     = empty($_REQUEST['jsrsC']) ? ''     : $_REQUEST['jsrsC'];$methodID   = empty($_REQUEST['jsrsF']) ? ''     : strtoLower($_REQUEST['jsrsF']);$params     = empty($_REQUEST['jsrsP']) ? ''     : $_REQUEST['jsrsP'];$squeezWDDX = isSet($_REQUEST['jsrsZ']) ? TRUE   : FALSE;$returnLang = empty($_REQUEST['jsrsR']) ? 'html' : strtoLower($_REQUEST['jsrsR']);if (ini_get('magic_quotes_gpc')) $params = stripslashes($params);do { if (!function_exists("wddx_deserialize")) {$err = "Server ERROR: Sorry, the PHP WDDX-package is currently *not* instaled on this SERVER.\n(Web-Master: See PHP manual for installation of WDDX.)";break; }
if (!empty($params)) {$params = str_replace($squeezedWDDX, $expandedWDDX, $params);$params = wddx_deserialize($params);}
if (!isSet($this->propagateList[$methodID])) {$err = $methodID . " is not a known function or method.\n";if (sizeOf($this->propagateList)) {$err .=  "propagated methods:\n";foreach($this->propagateList as $methodName=>$val) {$err .= "\t" . $methodName ."\n";}
} else {$err .=  "*NO* methods propagated!";}
break; }
$methodDesc = $this->propagateList[$methodID];if (empty($methodDesc['object'])) {$ret = empty($params) ?  $methodID() : call_user_func_array($methodID, $params);} else {$objectID = array(&$methodDesc['object'], $methodDesc['method']);$ret = empty($params) ? call_user_func($objectID) :  call_user_func_array($objectID, $params);}
$status = TRUE;} while(FALSE);if ($status) {$retWddx = wddx_serialize_value($ret);if ($squeezWDDX) $retWddx = str_replace($expandedWDDX, $squeezedWDDX, $retWddx);$retWddxEsc = urlencode($retWddx);} else {$retWddx    = '';$retWddxEsc = '';}
if ($err) $err = urlencode($err);$jsCall = "window.parent.jsrsReturn('{$callID}','{$retWddxEsc}','{$err}');";switch ($returnLang){case 'js':
$out = $jsCall;break;default :
$out =<<<EOD
          <html>
          <body onload="{$jsCall}">
          Remote Return Value (for debug purpose only):<br>
          <form name="jsrs_Form">
            <textarea cols="80" rows="10" name="jsrsWddxReturn">
              {$retWddx}
            </textarea>
          </form>
          </body></html>
EOD;
}echo $out;exit;}
}
$JsrsServer = new JsrsServer();?>

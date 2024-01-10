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
define('BS_EXCEPTION_VERSION',      '4.5.$Revision: 1.2 $');class Bs_Exception extends Bs_Object {var $_weight; var $_errCode;var $_errText;var $_errType;var $_stack;var $_globals;function Bs_Exception($message='', $file='', $line='', $errCode=NULL, $weight='') {parent::Bs_Object(); $this->_stack[]   = $this->_processParams($message, $file, $line);if ($weight != '') $this->_weight = $weight;if (!is_null($errCode)) {$t = explode(':', $errCode);$this->_errType = $t[0];$this->_errCode = isSet($t[1]) ?  $t[1] : -1;if ($this->_errCode>=0) {$this->_errText = isSet($t[2]) ?  $t[2] : '-no error text given-';} else {$this->_errText = '-no additional error info given-';}
}
}
function setStackParam($key, $val) {switch($key) {case 'functionArgs':
case 'vars':
case 'objects':
end($this->_stack);$pos = key($this->_stack);if ($key == 'objects') {if ((! is_array($val)) && (is_object($val))) $val = array($val);if (is_array($val)) {while(list($k) = each($val)) {$v = &$objects[$k];if (is_object($v)) 
$val2[] = array('class'       => get_class($v), 
'parentClass' => get_parent_class($v), 
'objectVars'  => get_object_vars($v));}
}
$val = $val2;} $this->_stack[$pos][$key] = $val;return TRUE;break;default:
return FALSE;}
}
function _processParams($message, $file, $line) {$ret['timestamp']       = date("Y/m/d H:i:s");$ret['message']         = $message;$ret['file']            = $file;$ret['line']            = $line;$ret['functionArgs']    = '';$ret['vars']            = '';$ret['objects']         = '';return $ret;}
function stackTrace($message='', $file='', $line='', $weight='') {$this->_stack[] = $this->_processParams($message, $file, $line);if ($weight != '') $this->_weight = $weight;}
function stackDump($what='') {switch ($what) {case 'alert':
trigger_error($this->_toHtml(), E_USER_WARNING);  break;case 'echo':
echo $this->_toHtml();break;case 'die':
echo $this->_toHtml();exit();break;case 'return':
return $this->_toHtml();break;case 'log':
trigger_error($this->_toHtml(), E_USER_WARNING);  break;case 'last msg': $msg = '';if (!empty($this->_stack)) {$msg = $this->_stack[sizeOf($this->_stack)-1]['message'];}
return $msg;break;default:
trigger_error($this->_toHtml(), E_USER_WARNING);  }
}
function seedGlobals() {if (is_array($this->_globals)) return;$this->_globals['postVars']   = $GLOBALS['HTTP_POST_VARS'];$this->_globals['getVars']    = $GLOBALS['HTTP_GET_VARS'];$this->_globals['cookieVars'] = $GLOBALS['HTTP_COOKIE_VARS'];$this->_globals['serverVars'] = $GLOBALS['HTTP_SERVER_VARS'];$this->_globals['envVars']    = $GLOBALS['HTTP_ENV_VARS'];$this->_globals['postFiles']  = $GLOBALS['HTTP_POST_FILES'];$this->_globals['appVars']    = $this->_varDump($GLOBALS['APP']);}
function toString() {$ret  = '';if (isSet($this->_weight))  $ret .= "Weight: {$this->_weight}\n";if (isSet($this->_errType)) $ret .= "Type  : {$this->_errType}\n";if (isSet($this->_errCode)) $ret .= "Code  : {$this->_errCode}";if (isSet($this->_errText)) $ret .= " ({$this->_errText})";$ret .= "\n";if (is_array($this->_stack)) {reset($this->_stack);while(list($k) = each($this->_stack)) {$array = &$this->_stack[$k];$ret .= "____________________________________STACK_TRACE_{$k}____________________________________\n";if ($array['message'] != '')   $ret .= "Message          : {$array['message']} \n";if ($array['file'] != '')      $ret .= "File             : {$array['file']} \n";if ($array['line'] != '')      $ret .= "Line             : {$array['line']} \n";if ($array['timestamp'] != '') $ret .= "Timestamp        : {$array['timestamp']} \n";if (is_array($array['functionArgs'])) {$ret .= "Function Args   : \n";while(list($k) = each($array['functionArgs'])) {$v = &$array['functionArgs'][$k];$ret .= "   $k   : " . $v . "\n";if (is_array($v)) {$ret .= "var_dump() of $v:\n";$ret .= $this->_varDump($v);}
}
}
if (is_array($array['vars'])) {$ret .= "Vars Passed   : \n";while(list($k) = each($array['vars'])) {$v = &$array['vars'][$k];$ret .= "      $k : $v} \n ";if (is_array($v)) {$ret .= "var_dump() of $k:\n";$ret .= $this->_varDump($v);}
}
}
if (is_array($array['objects'])) {$ret .= "Objects Passed   : \n";while(list($objKey) = each($array['objects'])) {$ret .= "   Class        : " . $array['objects'][$objKey]['class'] . "\n";$ret .= "   Parent Class : " . $array['objects'][$objKey]['parentClass'] . "\n";$ret .= "   Object Vars  : \n";while(list($k) = each($array['objects'][$objKey]['objectVars'])) {$v = &$array['objects'][$objKey]['objectVars'][$k];$ret .= "      $k : $v \n ";if (is_array($v)) {$ret .= "var_dump() of $k:\n";$ret .= $this->_varDump($v);}
}
}
}
$ret .= "\n\n\n\n";}
}
return $ret;}
function _toHtml() {return "<pre>\n" . $this->toString() . "</pre>\n";}
function _varDump($param) {ob_start();var_dump($param);$ret .= ob_get_contents();ob_end_clean();}
}
?>
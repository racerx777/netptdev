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
$XR_isOn = FALSE;$GLOBALS['_XR_funcStack_'] = array();$GLOBALS['_XR_funcHistory_'] = array();class Bs_XRay {function activateOnIP($IPs = array()) {if (!is_array($IPs)) $IPs = array($IPs);foreach ($IPs as $ip) {if ($ip === 'localhost') $ip ='127.0.0.1';$ip = str_replace(array('.','*'), array('\.','\d{1,3}'), $ip);if (preg_match('/'.$ip.'.*/', $_SERVER['REMOTE_ADDR'])) {$GLOBALS['XR_isOn'] = TRUE;break;}
}
if ($GLOBALS['XR_isOn']) {ini_set('error_reporting', E_ALL);ini_set('display_errors', 1);}
}
function echoHtml($msg, $_line_='', $_func_='', $_file_='') {if (!$GLOBALS['XR_isOn']) return;$out = '<fieldset style="border:solid thin blue; padding:5"><legend><b>'
. Bs_XRay::_msgInfoLine($_line_, $_func_, $_file_);$out .= '</b></legend>' . $msg . "</fieldset>\n";echo $out;}
function echoPre($msg, $_line_='', $_func_='', $_file_='') {if (!$GLOBALS['XR_isOn']) return;$out = '<fieldset style="border:solid thin blue; padding:5"><legend><b>'
. Bs_XRay::_msgInfoLine($_line_, $_func_, $_file_);$out .= '</b></legend><pre>' . $msg . "</pre></fieldset>\n";echo $out;}
function dump($msg, $_line_='', $_func_='', $_file_='') {if (!$GLOBALS['XR_isOn']) return;$out = '<fieldset style="border:solid thin blue; padding:5"><legend><b>'
. Bs_XRay::_msgInfoLine($_line_, $_func_, $_file_);$out .= '</b></legend><pre>';ob_start();var_dump($msg);$dump = ob_get_contents();$dump = str_replace("=>\n", '=>', $dump);ob_end_clean();$out .= $dump . "</pre></fieldset>\n";echo $out;}
function _msgInfoLine($_line_, $_func_, $_file_) {$out = '';$out .= (empty($_line_)) ? '' : "Line:[$_line_] ";$out .= (empty($_file_)) ? '' : basename($_file_) . '::';$out .= (empty($_func_)) ? '{unknown function}' : $_func_;return $out;}
function funcStart($_line_='', $_func_='', $_file_='', $comment) {if (!$GLOBALS['XR_isOn']) return;$level = sizeOf($GLOBALS['_XR_funcStack_']);$tmp = array('line'=>$_line_, 'func'=>$_func_, 'file'=>$_file_, 'comment'=>$comment, 
'stackLevel'=>$level, 'ts'=>explode(' ', microtime()));$GLOBALS['_XR_funcStack_'][$level] = $tmp;$GLOBALS['_XR_funcHistory_'][] = $tmp;}
function funcEnd($_line_='', $_func_='', $_file_='', $comment) {if (!$GLOBALS['XR_isOn']) return;array_pop ($GLOBALS['_XR_funcStack_']);}
}
Bs_XRay::activateOnIP('localhost');function XR_isOn() {return $GLOBALS['XR_isOn'];}
function XR_echo($msg, $_line_='', $_func_='', $_file_='') {Bs_XRay::echoHtml($msg, $_line_, $_func_, $_file_);}
function XR_echoPre($msg, $_line_='', $_func_='', $_file_='') {Bs_XRay::echoPre($msg, $_line_, $_func_, $_file_);}
function XR_dump($foo, $_line_='', $_func_='', $_file_='') {Bs_XRay::dump($foo, $_line_, $_func_, $_file_);}
if (basename($_SERVER['PHP_SELF']) == 'Bs_XRay.class.php') {echo $_SERVER['REMOTE_ADDR'];echo "XR_isOn() == " . (XR_isOn() ? "YES" : "NO") . "<br>";echo "\$XR_isOn == " . ($XR_isOn ? "YES" : "NO") . "<br>";XR_echoPre('test', __LINE__, 'main', __FILE__);XR_echo('test', __LINE__, 'main', __FILE__);$x = array(new Bs_XRay(), '***'=>10);XR_dump($GLOBALS, __LINE__, 'main', __FILE__);}
?>

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
define('BS_STRIPPER_VERSION',      '4.5.$Revision: 1.2 $');if (!isSet($GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'])) $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] = '../';if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core']      . 'html/Bs_JsCruncher.class.php');define('BS_STRIP_ALL',             0xFFFF);   define('BS_STRIP_COMMENT_SLASH'  , 1);define('BS_STRIP_COMMENT_ASTERIX', 2);define('BS_STRIP_COMMENT_HASH'   , 4);define('BS_STRIP_COMMENT_ALL'    , (BS_STRIP_COMMENT_SLASH | BS_STRIP_COMMENT_ASTERIX | BS_STRIP_COMMENT_HASH));define('BS_STRIP_FORMAT'         , 8);class Bs_Stripper extends Bs_Object {var $useSpecializedPhpStripper = TRUE;var $useSpecializedJsStripper  = TRUE;var $_preserveCodeAsIs = FALSE;     var $_preserveCodeHash = array();   var $_highlightColor = '';var $_replaceMatchStr = '';function Bs_Stripper() {$inis = ini_get_all();$this->colorCodes = array(
'comment' => $inis['highlight.comment']['global_value'], 'default' => $inis['highlight.default']['global_value'], 'html'    => $inis['highlight.html']['global_value'],    'keyword' => $inis['highlight.keyword']['global_value'], 'string'   => $inis['highlight.string']['global_value'], );}
function stripPhp($toStripTxt, $stripModifier=BS_STRIP_ALL, $debug=FALSE) {$this->_highlightColor = '';$this->_preserveCodeAsIs = FALSE;$this->_preserveCodeHash = array();$this->_replaceMatchStr  = '';if ($stripModifier & (BS_STRIP_COMMENT_SLASH | BS_STRIP_COMMENT_HASH | BS_STRIP_COMMENT_ASTERIX) ) {$trans = get_html_translation_table(HTML_SPECIALCHARS, ENT_NOQUOTES);$trans = array_flip($trans);$trans['&nbsp;'] = ' ';$trans['<br />'] = "\n";$toStripTxt = highlight_string($toStripTxt, TRUE);$regs = array(
'~^<code>\s*<font color="' . $this->colorCodes['comment'] .'">\s*~s', '~\s*</font>\s*</code>$~s',
'~^<code>\s*~',
'~\s*</code>$~s',
);$toStripTxt = preg_replace($regs, '', $toStripTxt);if ($debug) {$this->_highlightColor = "<span style='background-color:lightgreen'>";$toStripTxt = preg_replace_callback('~<font color="' . $this->colorCodes['comment'] .'">.*</font>~Us', array(&$this, '_matchHandler'), $toStripTxt);$regs = array('~<font .*>~Us', '~</font>~s',);foreach($this->_preserveCodeHash as $key => $data) {$tmp = strtr($data, $trans);$tmp = preg_replace($regs, '', $tmp);$tmp = strtr($tmp, $trans);$this->_preserveCodeHash[$key] = $tmp;}
}
$regs = array(
'~<font color="' . $this->colorCodes['comment'] .'">.*</font>~Us',
'~<font .*>~Us',
'~</font>~s',
);$toStripTxt = preg_replace($regs, '', $toStripTxt);$toStripTxt = strtr($toStripTxt,$trans);}
if ($stripModifier & BS_STRIP_FORMAT) {$toStripTxt = $this->strip($toStripTxt, BS_STRIP_FORMAT, $debug, $internalCall=TRUE);} elseif ($debug) {$toStripTxt = "<pre>\n" . htmlspecialchars($toStripTxt) . "</pre>";$toStripTxt = strtr($toStripTxt, $this->_preserveCodeHash);}
return $toStripTxt;}
function strip($toStripTxt, $stripModifier=BS_STRIP_ALL, $debug=FALSE, $internalCall=FALSE) {if (!$internalCall) {$this->_highlightColor   = '';$this->_preserveCodeAsIs = FALSE;$this->_preserveCodeHash = array();$this->_replaceMatchStr  = '';}
$this->_preserveCodeAsIs = TRUE;if ($debug) $this->_highlightColor = "<span style='font-weight: bolder; color: Silver;'>";$toStripTxt = preg_replace_callback('~<<<\s*([a-zA-Z0-9_]+)(\n|\r|\r\n).*\n\1.*(?=\n)~Us', array(&$this, '_matchHandler'), $toStripTxt);$this->_preserveCodeAsIs = FALSE;if ($stripModifier & BS_STRIP_COMMENT_SLASH) {if ($debug) $this->_highlightColor = "<span style='background-color:lime'>";$toStripTxt = preg_replace_callback('|\s+//.*$|m', array(&$this, '_matchHandler'), $toStripTxt);}
if ($stripModifier & BS_STRIP_COMMENT_HASH) {if ($debug) $this->_highlightColor = "<span style='background-color:lime'>";$toStripTxt = preg_replace_callback('|\s+#.*$|m', array(&$this, '_matchHandler'), $toStripTxt);}
if ($stripModifier & BS_STRIP_COMMENT_ASTERIX) {if ($debug) $this->_highlightColor = "<span style='background-color:lightgreen'>";$toStripTxt = preg_replace_callback('|\s*/\*+\s*\n.*\*/|Us', array(&$this, '_matchHandler'), $toStripTxt);}
if ($stripModifier & BS_STRIP_FORMAT) {if ($debug) $this->_highlightColor = "<span style='background-color:#ffccff'>";$toStripTxt = preg_replace_callback('|^\s+|m', array(&$this, '_matchHandler'), $toStripTxt);if ($debug) {$this->_replaceMatchStr ='\n'; $this->_highlightColor = "<span style='background-color:light--blue'>";}
$toStripTxt = preg_replace_callback('/(?<=;|{)(\s*\n\s*)/', array(&$this, '_matchHandler'), $toStripTxt);$this->_replaceMatchStr ='';}
if ($debug) {$toStripTxt = "<pre>\n" . htmlspecialchars($toStripTxt) . "</pre>";return  strtr($toStripTxt, $this->_preserveCodeHash);}
return strtr($toStripTxt, $this->_preserveCodeHash);}
function stripFile($sourceFile, $targetFile, $stripModifier=BS_STRIP_ALL, $header='', $debug=FALSE) {$_func_ = 'stripFile';if (!file_exists($sourceFile)) {Bs_Error::setError('no such file: ' . $sourceFile, 'ERROR', __LINE__, $_func_, __FILE__);return FALSE;}
if (!is_readable($sourceFile)) {Bs_Error::setError('file not readable: ' . $sourceFile, 'ERROR', __LINE__, $_func_, __FILE__);return FALSE;}
$fileContent = join('', file($sourceFile));if ($this->useSpecializedPhpStripper && preg_match('/\.php$/', $sourceFile)) {$stripped = $this->stripPhp($fileContent, $stripModifier, $debug);} elseif ($this->useSpecializedJsStripper && preg_match('/\.js$/', $sourceFile)) {$stripped = $this->stripJs($fileContent, $stripModifier, $debug);} else {$stripped = $this->strip($fileContent, $stripModifier, $debug);}
$fp = @fopen($targetFile, 'w');if (!$fp) {Bs_Error::setError('could not open file for writing: ' . $targetFile, 'ERROR', __LINE__, $_func_, __FILE__);return FALSE;}
$status = @fwrite($fp, $header . $stripped);@fclose($fp); if (!$status) {Bs_Error::setError('could not write to file: ' . $targetFile, 'ERROR', __LINE__, $_func_, __FILE__);return FALSE;}
return TRUE;}
function stripJs($string, $stripModifier=BS_STRIP_ALL, $debug=FALSE) {$cruncher =& new Bs_JsCruncher();return $cruncher->crunch($string);}
function _matchHandler($matchArray) {$id = uniqid('bsStripID_');do { if (!empty($this->_highlightColor)) {$this->_preserveCodeHash[$id] = $this->_highlightColor . htmlspecialchars($matchArray[0]) .'</span>';if (!empty($this->_replaceMatchStr)) {$this->_preserveCodeHash[$id] .= '<span style="background-color:red">' . htmlspecialchars($this->_replaceMatchStr).'</span>';}
break; }
if ($this->_preserveCodeAsIs) {$this->_preserveCodeHash[$id] = $matchArray[0];} else {$id = $this->_replaceMatchStr;}
} while(FALSE);return $id;}
}
if (basename($_SERVER['PHP_SELF']) == 'Bs_Stripper.class.php') {}
?>
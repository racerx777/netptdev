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
if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');class Bs_JsCruncher extends Bs_Object {var $doRemoveComments     = TRUE;var $doCompressWhiteSpace = TRUE;var $_literalStrings;function Bs_JsCruncher() {parent::Bs_Object();}
function crunch($input) {$output = $input;$output = str_replace("\r\n", "\n", $output);$output = str_replace("\r", "\n", $output);$output = $this->_replaceLiteralStrings($output);if ($this->doRemoveComments) $output = $this->removeComments($output);if ($this->doCompressWhiteSpace) $output = $this->compressWhiteSpace($output);$output = $this->_combineLiteralStrings($output);$output = $this->_restoreLiteralStrings($output);return $output;}
function removeComments($s) {$lines = explode("\n", $s);$t = '';$linesSize = sizeOf($lines);for ($i=0; $i<$linesSize; $i++) {$t .= preg_replace('/([^\x2f]*)\x2f\x2f.*$/', '$1', $lines[$i]) . "\n";}
$t = str_replace("\n", '__blueshoes_newline__', $t);$lines = explode('*/', $t);$t = '';$linesSize = sizeOf($lines);for ($i=0; $i<$linesSize; $i++) {$t .= preg_replace('/(.*)\x2f\x2a(.*)$/', '$1 ', $lines[$i]);}
$t = str_replace('__blueshoes_newline__', "\n", $t);return $t;}
function compressWhiteSpace($s) {$t = explode("\n", $s);$newS     = '';foreach ($t as $line) {if (!isWhite($line)) {$lastLine = preg_replace("/^\s+(.*)\s+$/", '$1', $line);$lastLine = trim($lastLine);if (substr($lastLine, -1) !== ';') $lastLine .= "\n";$newS .= $lastLine;}
}
$s = $newS;return $s;}
function _replaceLiteralStrings($s) {$this->_literalStrings = array();$t = '';$lines = explode("\n", $s);$linesSize = sizeOf($lines);for ($i=0; $i<$linesSize; $i++) {$j = 0;$inQuote = FALSE;if ((strpos($lines[$i], '.replace(') !== FALSE) || (strpos($lines[$i], '.match(') !== FALSE)  || (strpos($lines[$i], '.replace(') !== FALSE)) {$t .= '__bsLit_' . sizeOf($this->_literalStrings) . '__' . "\n";$this->_literalStrings[] = $lines[$i];continue;}
while ($j < strlen($lines[$i])) {$c = $lines[$i][$j];if (!$inQuote) {if (($c == '"') || ($c == "'")) {do {$posOfReplace = strpos($lines[$i], '.replace(');if ($posOfReplace !== FALSE) {$posOfComma = strpos($lines[$i], ',', $posOfReplace);if ($posOfComma !== FALSE) {if ($posOfComma > $j) {break;}
}
}
$inQuote   = TRUE;$escaped   = FALSE;$quoteChar = $c;$literal   = $c;} while (FALSE);if (!$inQuote) $t .= $c;} else {$t .= $c;}
} else {if (($c == $quoteChar) && !$escaped) {$inQuote = FALSE;$literal .= $quoteChar;$t .= '__bsLit_' . sizeOf($this->_literalStrings) . '__';$this->_literalStrings[] = $literal;} else if (($c == "\\") && !$escaped) {$escaped = TRUE;} else {$escaped = FALSE;}
$literal .= $c;}
$j++;}
$t .= "\n";}
return $t;}
function _combineLiteralStrings($s) {$s = preg_replace('/"\+"/', '', $s);$s = preg_replace("/'\+'/", '', $s);return $s;}
function _restoreLiteralStrings($s) {$litLen = sizeOf($this->_literalStrings);for ($i=0; $i<$litLen; $i++) {$s = preg_replace('/__bsLit_' . $i . '__/', $this->_literalStrings[$i], $s);}
return $s;}
}
?>
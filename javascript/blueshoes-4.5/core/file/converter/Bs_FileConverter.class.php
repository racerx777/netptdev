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
define('BS_FILECONVERTER_VERSION',      '4.5.$Revision: 1.4 $');require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'util/Bs_System.class.php');class Bs_FileConverter extends Bs_Object {var $_APP;var $_Bs_System = NULL;var $_Bs_Html   = NULL;var $_Bs_File;function Bs_FileConverter() {parent::Bs_Object(); $this->_APP       = &$GLOBALS['APP'];$this->_Bs_System = &$GLOBALS['Bs_System'];}
function loadBsHtmlUtil() {if (!isSet($GLOBALS['Bs_HtmlUtil'])) {include_once($this->_APP['path']['core'] . 'Html/Bs_HtmlUtil.class.php');}
$this->_Bs_HtmlUtil = &$GLOBALS['Bs_HtmlUtil'];}
function capable($functionName) {switch (strToLower($functionName)) {case 'htmltotext':
return TRUE;default:
return NULL;}
return TRUE;}
function &htmlToText($htmlString) {if (is_null($this->_Bs_HtmlUtil)) $this->loadBsHtmlUtil();$htmlString = strip_tags($htmlString);return $this->_Bs_HtmlUtil->htmlEntities2($htmlString);}
function htmlToWord() {}
function htmlToPdf() {}
function &_wordConvert($from, $filter, $to=NULL) {if (strpos($from, ' ') !== FALSE) $from = '"' . $from . '"'; $cmd = $this->_APP['path']['wvware'] . 'wvWare -x ' . $filter . ' ' . $from; exec($cmd, $ret, $status);if (is_array($ret)) {if (sizeOf($ret) == 1) {$e =& new Bs_Exception($ret[0], __FILE__, __LINE__, '');return $e;} elseif (sizeOf($ret) > 1) {if (is_null($to)) {return join("\n", $ret);} else {if (!is_object($this->_Bs_File)) $this->_Bs_File =& new Bs_File();$status = $this->_Bs_File->onewayWrite(join("\n", $ret), $to);if ($status === TRUE) {return TRUE;} else {$e =& new Bs_Exception('could not save file as ' . $to, __FILE__, __LINE__, '');return $e;}
}
}
}
$e =& new Bs_Exception('', __FILE__, __LINE__, '');return $e;}
function &wordToText($from, $to=NULL) {$filter = $this->_APP['path']['wvware'] . 'share/wv/wvText.xml';$ret = &$this->_wordConvert($from, $filter);if (isEx($ret)) {$ret->stackTrace('was here in wordToText()', __FILE__, __LINE__);return $ret;} else {$t   = &$this->htmlToText($ret);if (is_null($to)) {return $t;} else {if (!is_object($this->_Bs_File)) $this->_Bs_File =& new Bs_File();$status = $this->_Bs_File->onewayWrite($t, $to);if ($status === TRUE) {return TRUE;} else {$e =& new Bs_Exception('could not save file as ' . $to, __FILE__, __LINE__, '');return $e;}
} }
}
function wordToHtml($from, $to=NULL) {if (is_null($to)) {return $this->wordStreamToHtmlString($from);} else {return $this->wordStreamToHtmlStream($from, $to);}
}
function wordStreamToHtmlStream($source, $destination) {$filter = $this->_APP['path']['wvware'] . 'share/wv/wvHtml.xml';$ret = &$this->_wordConvert($source, $filter, $destination);if (isEx($ret)) {$ret->stackTrace('was here in wordStreamToHtmlStream()', __FILE__, __LINE__);return $ret;}
return $ret;}
function wordStreamToHtmlString($source) {$filter = $this->_APP['path']['wvware'] . 'share/wv/wvHtml.xml';$ret = &$this->_wordConvert($source, $filter, NULL);if (isEx($ret)) {$ret->stackTrace('was here in wordStreamToHtmlString()', __FILE__, __LINE__);return $ret;}
return $ret;}
function &wordToWml($from, $to=NULL) {$filter = $this->_APP['path']['wvware'] . 'share/wv/wvWml.xml';$ret = &$this->_wordConvert($from, $filter, $to);if (isEx($ret)) {$ret->stackTrace('was here in wordToWml()', __FILE__, __LINE__);return $ret;}
return $ret;}
function &wordToLatex($from, $to=NULL, $clean=FALSE) {$xml = ($clean) ? 'wvCleanLaTeX.xml' : 'wvLaTeX.xml';$filter = $this->_APP['path']['wvware'] . 'share/wv/' . $xml;$ret = &$this->_wordConvert($from, $filter, $to);if (isEx($ret)) {$ret->stackTrace('was here in wordToLatex()', __FILE__, __LINE__);return $ret;}
return $ret;}
function &wordToAbi($from, $to=NULL) {$filter = $this->_APP['path']['wvware'] . 'share/wv/wvAbw.xml';$ret = &$this->_wordConvert($from, $filter, $to);if (isEx($ret)) {$ret->stackTrace('was here in wordToAbi()', __FILE__, __LINE__);return $ret;}
return $ret;}
function wordSummary($filePath) {$cmd = $this->_APP['path']['wvware'] . 'wvSummary ' . $filePath;exec($cmd, $t, $status);if (!is_array($t)) {return FALSE;} else {$ret = array( 'title'=>'', 'subject'=>'', 'author'=>'', 'keywords'=>'', 'comments'=>'', 'template'=>'', 
'lastAuthor'=>'', 'rev'=>'', 'appName'=>'', 'pageCount'=>'', 'wordCount'=>'', 
'charCount'=>'', 'security'=>'', 'codepage'=>''
);while (list($k) = each($t)) {switch (strToLower(substr($t[$k], 0, 11))) {case 'the title i': $ret['title'] = substr($t[$k], 13);break;case 'the subject': $ret['subject'] = substr($t[$k], 15);break;case 'the author ': $ret['author'] = substr($t[$k], 14);break;case 'the keyword': $ret['keywords'] = substr($t[$k], 17);break;case 'the comment': $ret['comments'] = substr($t[$k], 17);break;case 'the templat': $ret['template'] = substr($t[$k], 17);break;case 'the last au': $ret['lastAuthor'] = substr($t[$k], 20);break;case 'the rev # w': $ret['rev'] = substr($t[$k], 14);break;case 'the app nam': $ret['appName'] = substr($t[$k], 17);break;case 'pagecount i': $ret['pageCount'] = substr($t[$k], 13);break;case 'wordcount i': $ret['wordCount'] = substr($t[$k], 13);break;case 'charcount i': $ret['charCount'] = substr($t[$k], 13);break;case 'security is': $ret['security'] = substr($t[$k], 12);break;case 'codepage is': $ret['codepage'] = substr($t[$k], 12);break;default:
}
}
return $ret;}
}
function wordVersion($filePath) {$cmd = $this->_APP['path']['wvware'] . 'wvVersion ' . $filePath;exec($cmd, $t, $status);if (is_array($t) && (sizeOf($t) == 1)) {$temp2 = explode(', ', $t[0]);$ret['version']   = substr($temp2[0], 9);$ret['encrypted'] = isTrue(substr($temp2[1], 11));return $ret;} else {return FALSE;}
}
}
?>
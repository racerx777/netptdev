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
define("BS_HEADCOLLECTOR_VERSION",      '4.5.$Revision: 1.4 $');define ('BS_HEADCOLLECTOR_INCLUDE',        1);define ('BS_HEADCOLLECTOR_ONLOAD' ,        2);define ('BS_HEADCOLLECTOR_ONLOAD_NOTAGS' , 4);define ('BS_HEADCOLLECTOR_HEAD'   ,        8);define ('BS_HEADCOLLECTOR_DEFAULT', BS_HEADCOLLECTOR_INCLUDE | BS_HEADCOLLECTOR_ONLOAD | BS_HEADCOLLECTOR_HEAD);if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');class Bs_HeadCollector extends Bs_Object {var $_onLoadCode  = array();var $_includeOnce = array();var $_intoHead    = array();function Bs_HeadCollector() {}
function spit($what=BS_HEADCOLLECTOR_DEFAULT) {$ret = '';if ($what & BS_HEADCOLLECTOR_INCLUDE) {if (!empty($this->_includeOnce)) {while (list($url) = each($this->_includeOnce)) {$fileType = (substr($url, -4) == '.css') ? 'css' : 'js';switch ($fileType) {case 'js':
$ret .= '<script type="text/javascript" language="JavaScript" src="' . $url . '"></script>';break;case 'css':
$ret .= '<link rel="stylesheet" href="' . $url . '" />';break;}
}
}
}
if ($what & BS_HEADCOLLECTOR_ONLOAD) {if (!empty($this->_onLoadCode)) {$ret .= "\n<script><!--\n";$ret .= "onload = function() {\n";while (list(,$line) = each($this->_onLoadCode)) {$ret .= $line . "\n";}
$ret .= "}\n";$ret .= "\n//--></script>\n";}
}
if ($what & BS_HEADCOLLECTOR_ONLOAD_NOTAGS) {if (!empty($this->_onLoadCode)) {while (list(,$line) = each($this->_onLoadCode)) {$ret .= $line . "\n";}
}
}
if ($what & BS_HEADCOLLECTOR_HEAD) {if (!empty($this->_intoHead)) {$ret .= join("\n", $this->_intoHead);}
}
return $ret;}
function addOnLoadCode($code) {if (is_array($code)) {array_merge($this->_onLoadCode, $code);} else {$this->_onLoadCode[] = $code;}
}
function addIncludeOnce($url) {if (is_array($url)) {foreach ($url as $newUrl) {$this->_includeOnce[$newUrl] = TRUE;}
} else {$this->_includeOnce[$url] = TRUE;}
}
function addIntoHead($content) {$this->_intoHead[] = $content;}
}
$GLOBALS['Bs_HeadCollector'] =& new Bs_HeadCollector();?>
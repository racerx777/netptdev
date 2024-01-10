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
define('BS_EMAILUTIL_VERSION',      '4.5.$Revision: 1.3 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'html/Bs_HtmlUtil.class.php');class Bs_EmailUtil extends Bs_Object {var $Bs_HtmlUtil;function Bs_EmailUtil() {parent::Bs_Object(); $this->Bs_HtmlUtil = &$GLOBALS['Bs_HtmlUtil'];}
function parse($email) {do {$pos = strpos($email, '@');if ($pos === FALSE) break;$user = substr($email, 0, $pos);$host = substr($email, $pos +1);return array($user, $host);} while (FALSE);return FALSE;}
function hideEmailWithJsDocumentWrite($email, $name=NULL, $cssClass=NULL) {$parsed = $this->parse($email);if (!$parsed) return FALSE; $aCss = (is_null($cssClass)) ? '' : ' class="'.$cssClass.'"';$ret  = "<script language='javascript'><!--\n";$ret .= "document.write('<a {$aCss} href=\"mailto:{$parsed[0]}');\n";$ret .= "document.write('@');\n";$ret .= "document.write('{$parsed[1]}\">');\n";if (is_null($name)) {$ret .= "document.write('{$parsed[0]}');\n";$ret .= "document.write('@');\n";$ret .= "document.write('{$parsed[1]}');\n";} else {$ret .= "document.write('{$name}');\n";}
$ret .= "document.write('</a>');\n";$ret .= "//--></script>\n";$ret .= "<noscript>\n";$ret .= "<nobr>";$ret .= $this->htmlEncode($this->toPronounceable($email));$ret .= "</nobr>\n";$ret .= "</noscript>\n";return $ret;}
function emailHidingWithJsOnMouseOver($email) {$parsed = $this->parse($email);if (!$parsed) return FALSE; $spanId = substr(uniqid('e', TRUE), 0, 8);$ret  = "<span id='{$spanId}' onmouseover='fnc_{$spanId}();'>\n";$ret .= $this->htmlEncode($this->toPronounceable($email));$ret .= "<script language='JavaScript'><!--\n";$ret .= "function fnc_{$spanId}() {\n";$ret .= "  if (document.getElementById) {\n";$ret .= "    var fld  = document.getElementById('{$spanId}');\n";$ret .= "    var str  = \"<a href='mailto:{$parsed[0]}@\";\n";$ret .= "    str     += \"{$parsed[1]}'>{$parsed[0]}@\";\n";$ret .= "    str     += \"{$parsed[1]}</a>\";\n";$ret .= "    fld.innerHTML = str; alert(str);\n";$ret .= "    fld.onmouseover = '';\n"; $ret .= "  }\n";$ret .= "}\n";$ret .= "//--></script>\n";$ret .= '</span>';return $ret;}
function toPronounceable($email, $lang='en') {$from = array('@',    '.',     '_',            '-');if (is_array($lang)) {$to = $lang;} else {switch ($lang) {case 'de':
$to = array(' AT ', ' PUNKT ', ' UNTERSTRICH ', ' STRICH ');break;case 'de2':
$to = array(' AFFENSCHWANZ ', ' PUNKT ', ' UNTERSTRICH ', ' MINUS ');break;default: $to = array(' AT ', ' DOT ', ' UNDERSCORE ', ' DASH ');}
}
return str_replace($from, $to, $email);}
function htmlEncode($email) {return $this->Bs_HtmlUtil->charToHtml($email);}
}
$GLOBALS['Bs_EmailUtil'] =& new Bs_EmailUtil();?>
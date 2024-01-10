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
define('BS_URL_VERSION',      '4.5.$Revision: 1.3 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');class Bs_Url extends Bs_Object {function Bs_Url() {parent::Bs_Object(); }
function checkSyntax($url) {$t = @parse_url($url);return (bool)((is_array($t)) && (isSet($t['scheme'])) && (isSet($t['host'])));}
function validate($url) {if (!$this->checkSyntax($url)) return FALSE;$t = @fopen($url, 'r');$ret = ($t) ? TRUE : FALSE;@fclose($t);return $ret;}
function ipToNumber($ip) {return ip2long($ip);}
function numberToIp($num) {return long2ip($num);}
function explodeIp($ip, $zerofill=FALSE) {$ret = explode('.', $ip);if ((is_array($ret)) && (sizeOf($ret) == 4)) {if ($zerofill) {for ($i=0; $i<=3; $i++) {$t = strlen($ret[$i]);switch ($t) {case 0:
$ret[$i] = '000';break;case 1:
$ret[$i] = '00' . $ret[$i];break;case 2:
$ret[$i] = '0' . $ret[$i];break;}
}
}
return $ret;} else {return FALSE;}
}
function parseUrlExtended($url) {$ret = @parse_url($url);if ((is_array($ret)) && (isSet($ret['host']))) {$ret['domain']    = Bs_Url::getDomain4url($url, 2);$ret['directory'] = Bs_Url::getDirectory4url($url);if (empty($ret['directory'])) unset($ret['directory']);$ret['file']      = Bs_Url::getFile4url($url);if (empty($ret['file'])) {unset($ret['file']);} else {$pos = strrpos($ret['file'], '.');if ($pos) $ret['extension'] = substr($ret['file'], $pos +1);}
} else {return FALSE;}
return $ret;}
function getUrlChunk($junk, $url=NULL) {if (!is_array($url)) {if (is_null($url)) {$scheme = 'http://';if (isSet($GLOBALS['HTTP_SERVER_VARS']['HTTPS'])) { $t = strToLower($GLOBALS['HTTP_SERVER_VARS']['HTTPS']);if (isTrue($t)) $scheme = 'https://';}
$url = $scheme . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];}
$url = Bs_Url::parseUrlExtended($url);if ($url === FALSE) return FALSE;}
$len = strlen($junk);$ret = '';for ($i=0; $i<$len; $i++) {switch ($junk[$i]) {case 's':
if (!empty($url['scheme'])) {$ret .= $url['scheme'];switch ($junk[$i +1]) {case 'u':
case 'h':
case 'd':
$ret .= '://';}
}
break;case 'u':
if (!empty($url['user'])) {$ret .= $url['user'];}
break;case 'P':
if (!empty($url['pass'])) {if ($junk[$i -1] == 'u') $ret .= ':';$ret .= $url['pass'];}
break;case 'h':
if (!empty($url['host'])) {if ((($junk[$i -1] == 'u') || ($junk[$i -1] == 'P')) && (!empty($url['user']) || !empty($url['pass']))) {$ret .= '@';}
$ret .= $url['host'];}
break;case 'd':
if (!empty($url['domain'])) {$ret .= $url['domain'];}
break;case 'o':
if (!empty($url['port'])) {if (!empty($url['port'])) {if (($junk[$i -1] == 'h') || ($junk[$i -1] == 'd')) $ret .= ':';$ret .= $url['port'];}
}
break;case 'O':
if ((!empty($url['port'])) && ($url['port'] != '80')) {if (($junk[$i -1] == 'h') || ($junk[$i -1] == 'd')) $ret .= ':';$ret .= $url['port'];}
break;case 'p':
if (!empty($url['path'])) {$ret .= $url['path'];}
break;case 'i':
if (!empty($url['directory'])) {$ret .= $url['directory'];}
break;case 'f':
if (!empty($url['file'])) {$ret .= $url['file'];}
break;case 'q':
if (!empty($url['query'])) {switch ($junk[$i -1]) {case 'h':
case 'd':
case 'o':
case 'O':
case 'p':
case 'i':
case 'f':
case '3':
case '4':
case '5':
case '8':
case '9':
$ret .= '?';}
$ret .= $url['query'];}
break;case 'F':
if (!empty($url['fragment'])) {switch ($junk[$i -1]) {case 'h':
case 'd':
case 'o':
case 'O':
case 'p':
case 'i':
case 'f':
case 'q':
case '2':
case '3':
case '4':
case '5':
case '7':
case '8':
case '9':
$ret .= '#';}
$ret .= $url['fragment'];}
break;case '1':
case '2':
case '3':
case '4':
case '5':
$ret .= $url['scheme'] . '://';if (!empty($url['user'])) {$ret .= $url['user'];if (!empty($url['pass'])) $ret .= ':' . $url['pass'];$ret .= '@';}
$ret .= $url['host'] . ':' . $url['port'];if ($junk[$i] <= 3) {$ret .= $url['path'];if ($junk[$i] <= 2) {if (!empty($url['query'])) $ret .= '?' . $url['query'];if ($junk[$i] == 1) {if (!empty($url['fragment'])) $ret .= '#' . $url['fragment'];}
}
} elseif ($junk[$i] == 4) {$ret .= $url['directory'];}
break;case '6':
case '7':
case '8':
$ret .= $url['path'];if ($junk[$i] <= 7) {if (!empty($url['query'])) $ret .= '?' . $url['query'];if ($junk[$i] == 6) {if (!empty($url['fragment'])) $ret .= '#' . $url['fragment'];}
}
break;case '9':
$ret .= $url['directory'];break;default:
}
}
return $ret;}
function getUrlJunk($junk, $url=NULL) {return Bs_Url::getUrlChunk($junk, $url);}
function glueUrl($url) {if ((!is_array($url)) || (!isSet($url['host']))) return FALSE;$uri = (!empty($url['scheme'])) ? $url['scheme'] . '://' : '';if (!empty($url['user'])) $uri .= $url['user'] . ':' . $url['pass'] . '@';$uri .= $url['host'];if (!empty($url['port'])) $uri .= ':' . $url['port'];$uri .= $url['path'];if (isset($url['query'])) $uri .= '?' . $url['query'];if (isset($url['fragment'])) $uri .= '#'.$url['fragment'];return $uri;}
function getDomain4url($url, $num=2) {$t = @parse_url($url);if ((!is_array($t)) || (!isSet($t['host']))) return FALSE;if ($num < 0) return $t['host'];$tmp = '.' . $t['host'];for ($i=0; $i<$num; $i++) {$pos = strrpos($tmp, '.');if ($pos === false) {break;} else {$lastPos = $pos;$tmp     = substr($tmp, 0, $lastPos);}
}
return substr($t['host'], $lastPos);}
function getDirectory4url($url) {$t = @parse_url($url);if ((!is_array($t)) || (!isSet($t['host']))) return FALSE;if ((!isSet($t['path'])) || (empty($t['path']))) return '/';  $t = $t['path']; if (substr($t, -1) == '/') return $t; $pos = strrpos($t, '/');if ($pos === false) {return '/';} else {return substr($t, 0, $pos +1);}
}
function getFile4url($url) {$t = @parse_url($url);if ((!is_array($t)) || (!isSet($t['host']))) return FALSE;if ((!isSet($t['path'])) || (empty($t['path']))) return '';  $t = $t['path']; if (substr($t, -1) == '/') return ''; $pos = strrpos($t, '/');if ($pos === false) {return '';} else {return substr($t, $pos +1);}
}
function realUrl($url) {$pos = strpos($url, '://');if ($pos) {$pos += 3;$baseUrl = substr($url, 0, $pos);$url     = substr($url, $pos);} else {$baseUrl = '';}
$url = ereg_replace('/+', '/', $url);$patharray = explode('/', $url);$path      = '';$count     = sizeOf($patharray);for ($i=0; $i<$count; $i++) {if (($patharray[$i] == '.') || ($patharray[$i] == '..') || ( (($i+1) < $count) && ($patharray[$i+1]== '..')) ) {} else {$path .= $patharray[$i] .'/';}
}
if (!empty($path)) $path = substr($path, 0, -1);return $baseUrl . $path;}
function enableUrl($str) {$str = eregi_replace("((f|ht)tp:\/\/[a-z0-9~#%@\&:=?\/\._-]+)", "<a href=\"\\1\" target=\"_blank\">\\1</a>", $str); $str = eregi_replace("([[:space:]a-z0-9()\"'\[~#%@\&:=?\._-])(www.[a-z0-9~#%@\&:=?\/\._-]+)", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $str); $str = eregi_replace("([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})","<a href=\"mailto:\\1\">\\1</a>", $str); return $str;}
function hashArrayToQueryString(&$hashArray, $prefix='', $firstSeparator='&') {if (is_array($hashArray)) {$ret = '';$beenHere = FALSE;foreach ($hashArray as $key => $val) {if ($prefix != '') $key = $prefix . "[$key]";if (is_array($val)) {$ret .= $this->hashArrayToQueryString($val, $key);} else {if (getType($val) === 'string') $val = urlencode($val);if ((!$beenHere) && ($firstSeparator != '&')) {$beenHere = TRUE;$ret .= "{$firstSeparator}{$key}=" . $val;} else {$ret .= "&{$key}=" . $val;}
}
}
} else {$ret = '';}
return $ret;}
function hashArrayToHiddenFields(&$hashArray, $prefix='') {if (is_array($hashArray)) {reset($hashArray);while (list($k, $v) = each($hashArray)) {if ($prefix != '') $k = $prefix . "[$k]";if (is_array($v)) {$ret .= $this->hashArrayToQueryString($v, $k);} else {$ret .= "<input type='hidden' name=\"{$k}\" value=\"" . urlencode($v) . "\">\n";}
}
} else {$ret = '';}
return $ret;}
function addQueryParam($url, $key, $val) {$t = parse_url($url);$value = $key . '=' . urlEncode($val);$t['query'] = (isSet($t['query'])) ? $t['query'] . '&' . $value : $value;return $this->glueUrl($t);}
function removeQueryParam() {}
function modifyQueryParam($url, $key, $val, $force=TRUE) {if (ereg("(\\?|&)$key=([^&]*)(&|$)", $url)) {$val = urlencode($val);$new_url = ereg_replace("(\\?|&)$key=([^\\&]*)(&|$)","\\1$key=$val\\3", $url);$new_url = ereg_replace("\\?\\?","?", $new_url);return $new_url;} else if ($force) {return $this->addQueryParam($url, $key, $val);} else {return $url;}
}
function breadCrumb($url=NULL) {if (is_null($url)) $url = getAbsoluteWebFile();$t = @parse_url($url);$originalpath = $t['path'];                        $urlbase      = $t['scheme'] . '://' . $t['host']; $roottitle    = 'Home'; $seperator    = ' > '; $ignore       = 'Index'; $path = explode ("/", $originalpath);$totalelements = count ($path);printf("<a href=\"%s\">%s</a>", $urlbase, $roottitle);for ($number=1; $number<$totalelements ; $number++) {$urlbase = $urlbase . "/" . $path[$number];$path[$number] = str_replace("___", "_&_", $path[$number]);$path[$number] = str_replace("_", " ", $path[$number]);$path[$number] = str_replace("~", "?", $path[$number]);$path[$number] = str_replace(".php", "", $path[$number]);$path[$number] = ucwords($path[$number]);if ($path[$number] != $ignore) {printf("%s<a href=\"%s\">%s</a>", $seperator, $urlbase, $path[$number]);}
}
}
function similar($urlOne, $urlTwo) {$urlOne = strToLower($urlOne);$urlTwo = strToLower($urlTwo);if ($urlOne == $urlTwo) return TRUE;$urlOneLen = strlen($urlOne);$urlTwoLen = strlen($urlTwo);$lenDiff   = ($urlOneLen - $urlTwoLen);if ($lenDiff == 1) {if (substr($urlOne, 0, -1) == $urlTwo) return TRUE;} elseif ($lenDiff == -1) {if ($urlOne == substr($urlTwo, 0, -1)) return TRUE;}
$urlOneShort = (($urlOne == '/') || ($urlOne == ''));$urlTwoShort = (($urlTwo == '/') || ($urlTwo == ''));if ($urlOneShort && $urlTwoShort) {return TRUE;} elseif ($urlOneShort || $urlTwoShort) {return FALSE;}
do {$urlOneNoFile = $urlTwoNoFile = '';if ($urlOne[$urlOneLen -1] == '/') {$urlOneNoFile = $urlOne;} else {$t = strrpos($urlOne, '/');$urlOneNoFile = substr($urlOneNoFile, 0, $t);}
if ($urlTwo[$urlTwoLen -1] == '/') {$urlTwoNoFile = $urlTwo;} else {$t = strrpos($urlTwo, '/');$urlTwoNoFile = substr($urlTwoNoFile, 0, $t);}
$urlOneLastDir = Bs_Url::getLastDir($urlOne);$urlTwoLastDir = Bs_Url::getLastDir($urlTwo);if (is_null($urlOneLastDir) || is_null($urlTwoLastDir)) break;if (is_string($urlOneLastDir)) $urlOneLastDir = array($urlOneLastDir);if (is_string($urlTwoLastDir)) $urlTwoLastDir = array($urlTwoLastDir);while (list($k) = each($urlOneLastDir)) {while (list($k2) = each($urlTwoLastDir)) {if ($urlTwoLastDir[$k2] == $urlOneLastDir[$k]) return TRUE;if (soundex($urlTwoLastDir[$k2]) == soundex($urlOneLastDir[$k])) return TRUE;}
}
} while (FALSE);$urlOneJunks = explode('/', $urlOne);$urlTwoJunks = explode('/', $urlTwo);if (sizeOf($urlOneJunks) == sizeOf($urlTwoJunks)) {$isOk = TRUE;while (list($k) = each($urlOneJunks)) {if (soundex($urlOneJunks[$k]) != soundex($urlTwoJunks[$k])) {$isOk = FALSE;break;}
}
if ($isOk) return TRUE;}
return FALSE;}
function crossUrlDecode($source) {$decodedStr = '';$pos = 0;$len = strlen($source);while ($pos < $len) {$charAt = substr ($source, $pos, 1);if ($charAt == 'Ã') {$char2 = substr($source, $pos, 2);$decodedStr .= htmlentities(utf8_decode($char2),ENT_QUOTES,'ISO-8859-1');$pos += 2;} elseif (ord($charAt) > 127) {$decodedStr .= "&#".ord($charAt).";";$pos++;} elseif ($charAt == '%') {$pos++;$hex2 = substr($source, $pos, 2);$dechex = chr(hexdec($hex2));if ($dechex == 'Ã') {$pos += 2;if (substr($source, $pos, 1) == '%') {$pos++;$char2a = chr(hexdec(substr($source, $pos, 2)));$decodedStr .= htmlentities(utf8_decode($dechex . $char2a),ENT_QUOTES,'ISO-8859-1');} else {$decodedStr .= htmlentities(utf8_decode($dechex));}
} else {$decodedStr .= $dechex;}
$pos += 2;} else {$decodedStr .= $charAt;$pos++;}
}
return $decodedStr;}
function getLastDir($url) {list($url, $dir) = Bs_Url::_removeFile($url);if (is_null($url)) {if (is_null($dir)) {return NULL;} else {return $dir;}
}
$lastSlash = strrpos($url, '/');if ($lastSlash === FALSE) {$dir2 = $url;} else {$dir2 = substr($url, $lastSlash +1);}
if (!is_null($dir)) {return array($dir, $dir2);} else {return $dir2;}
}
function _removeFile($url) {if (substr($url, -1) == '/') {$url = substr($url, 0, -1); return array($url, NULL);} else {$lastSlash = strrpos($url, '/');if ($lastSlash === FALSE) {$restUrl = NULL;$workUrl = $url;} else {$restUrl = substr($url, 0, $lastSlash);$workUrl = substr($url, $lastSlash +1);if ($restUrl == '/') $restUrl = NULL;}
if (strpos($workUrl, '.') === FALSE) {if (strlen($workUrl) > 0) return array($restUrl, $workUrl);return array($restUrl, NULL); } else {return array($restUrl, NULL);}
}
}
}
$GLOBALS['Bs_Url'] =& new Bs_Url(); ?>
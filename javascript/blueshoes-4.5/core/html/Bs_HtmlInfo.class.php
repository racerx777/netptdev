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
define('BS_HTMLINFO_VERSION',      '4.5.$Revision: 1.5 $');if (!isSet($GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'])) $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] = '../';if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'html/Bs_HtmlUtil.class.php');class Bs_HtmlInfo extends Bs_Object {var $Bs_HtmlUtil;var $_html;function Bs_HtmlInfo() {parent::Bs_Object();$this->Bs_HtmlUtil = &$GLOBALS['Bs_HtmlUtil'];}
function initByString($str) {$this->_html = $str;return TRUE;}
function initByPath($fullPath) {if (!file_exists($fullPath)) return FALSE;if (!is_readable($fullPath)) return FALSE;$this->_html = join('', file($fullPath));return TRUE;}
function initByUrl($url) {$this->_html = join('', file($url));return TRUE;}
function fetchBody($html=FALSE) {$strLower = strToLower($this->_html);$posStart = strpos($strLower, '<body');if ($posStart === FALSE) return '';$posStart = strpos($strLower, '>', $posStart);if ($posStart === FALSE) return '';$posStart++;$posEnd = strpos($strLower, '</body>');if ($posEnd === FALSE) {$ret = substr($this->_html, $posStart);} else {$ret = substr($this->_html, $posStart, $posEnd - $posStart);}
if ($html) return $ret;return $this->htmlToText($ret);}
function fetchTitle($html=FALSE) {preg_match_all("|<title>(.*)</title>|i", $this->_html, $matches, PREG_PATTERN_ORDER);if (empty($matches[1][0])) return '';if ($html) return trim($matches[1][0]);return $this->htmlToText($matches[1][0]);}
function fetchDescription($html=FALSE) {$t = $this->fetchMetaData();foreach($t as $hash) {if ($hash['name'] === 'description') {if ($html) return $hash['content'];return $this->htmlToText($hash['content']);}
}
return '';}
function fetchKeywords($html=FALSE) {$t = $this->fetchMetaData();foreach($t as $hash) {if ($hash['name'] === 'keywords') {if ($html) return $hash['content'];return $this->htmlToText($hash['content']);}
}
return '';}
function fetchStringsByTagNameStupid($tagName, $string=NULL, $html=FALSE) {if (is_null($string)) $string = $this->_html;$pattern= "/<{$tagName}[\s.>|>](.*)<\/{$tagName}>/Uim";preg_match_all($pattern, $string, $matches, PREG_PATTERN_ORDER);if (empty($matches[1])) return array();$ret = array();foreach ($matches[1] as $line) {if (strpos($line, '<') !== FALSE) continue;if ($html) {if (!empty($line)) $ret[] = $line;} else {$t = $this->htmlToText($line);if (!empty($t)) $ret[] = $t;}
}
return $ret;}
function fetchStringsByTagNamesStupid($tagNames, $string=NULL, $html=FALSE) {$ret = array();foreach($tagNames as $tagName) {$ret = array_merge($this->fetchStringsByTagNameStupid($tagName, $string, $html), $ret);}
return $ret;}
function fetchLinks($dublicates=2, $urlOnly=FALSE, $useBaseTag=FALSE, $removeAnker=TRUE, $ignoreInvalid=TRUE) {$ret       = array();$hrefCache = array();$string    = $this->_html;$matches = NULL;preg_match_all("|<a(.*)>(.*)</a>|iU", $string, $matches, PREG_SET_ORDER);foreach ($matches as $arr) {$href    = $this->getTagParam('href',   $arr[1] . '>'); if (is_null($href)) continue; if ($ignoreInvalid) {if (substr(strToLower($href), 0, 11) === 'javascript:') continue;if (substr(strToLower($href), 0, 7)  === 'mailto:') continue;if (substr(strToLower($href), 0, 6)  === 'about:') continue; }
if ($removeAnker) {$pos = strpos($href, '#');if ($pos === 0) continue; if ($pos >= 1) $href = substr($href, 0, $pos);}
if (($dublicates === 0) && isSet($hrefCache[$href])) continue; if ($urlOnly) {$ret[] = $href;} else {$caption = @strip_tags($arr[2]);$caption = preg_replace('/^(&nbsp;)*(.*?)(&nbsp;)*$/', '$2', $caption);if (empty($caption)) {$matches = NULL;preg_match_all("|<img(.*)>|iU", $arr[2], $matches, PREG_SET_ORDER);if (isSet($matches[0][1])) {$caption = $this->getTagParam('alt',   $matches[0][1] . '>'); if (is_null($caption)) {$caption = $this->getTagParam('title',   $matches[0][1] . '>'); if (is_null($caption)) {$caption = '';}
}
}
}
$caption = $this->htmlToText($caption);if (($dublicates === 1) && isSet($hrefCache[$href]) && $hrefCache[$href] === $caption) continue; $target  = $this->getTagParam('target', $arr[1] . '>'); if ($dublicates !== 2) $hrefCache[$href] = $caption; $ret[] = array(
'href'    => $href, 
'caption' => $caption, 
'target'  => $target, 
);}
}
return $ret;}
function fetchIframeUrls($dublicates=TRUE, $useBaseTag=FALSE, $removeAnker=TRUE, $ignoreInvalid=TRUE) {$ret = array();preg_match_all("|<iframe(.*)>|iU", $this->_html, $matches, PREG_PATTERN_ORDER);if (!empty($matches[1])) {foreach($matches[1] as $matchLine) {$href = $this->getTagParam('src', $matchLine);if (is_null($href)) continue;if ($ignoreInvalid) {if (substr(strToLower($href), 0, 11) === 'javascript:') continue;if (substr(strToLower($href), 0, 7)  === 'mailto:') continue;if (substr(strToLower($href), 0, 6)  === 'about:') continue; }
if ($removeAnker) {$pos = strpos($href, '#');if ($pos === 0) continue; if ($pos >= 1) $href = substr($href, 0, $pos);}
if (!$dublicates && isSet($hrefCache[$href])) continue; $ret[] = $href;}
}
return $ret;}
function fetchBaseTag() {$ret = array('href'=>NULL, 'target'=>NULL);preg_match_all("|<base(.*)>|U", $string, $matches, PREG_SET_ORDER);if (isSet($matches[0][1])) {$ret['href']   = $this->getTagParam('href',   $matches[0][1] . '>'); $ret['target'] = $this->getTagParam('target', $matches[0][1] . '>'); }
return $ret;}
function fetchMetaData($html=FALSE, $withHttpEquiv=TRUE) {$ret = array();$string = $this->_html;preg_match_all("|<meta(.*)>|iU", $string, $matches, PREG_PATTERN_ORDER);if (!empty($matches[1])) {foreach($matches[1] as $matchLine) {$find = 'name';do {preg_match("|{$find}[\s]*\=[\s]*\"(.*)\"|iU", $matchLine, $nameMatch);if (!empty($nameMatch[1])) break;preg_match("|{$find}[\s]*\=[\s]*\'(.*)\'|iU", $matchLine, $nameMatch);if (!empty($nameMatch[1])) break;preg_match("|{$find}[\s]*\=[\s]*(.*)\s+|iU", $matchLine, $nameMatch);if (!empty($nameMatch[1])) break;if (!$withHttpEquiv) break;if ($find == 'HTTP-EQUIV') break;$find = 'HTTP-EQUIV';} while (TRUE); if (empty($nameMatch[1])) continue; $metaName = strToLower($nameMatch[1]);do {preg_match("|[\s]+content[\s]*\=[\s]*\"(.*)\"|iU", $matchLine, $contentMatch);if (!empty($contentMatch[1])) break;preg_match("|[\s]+content[\s]*\=[\s]*\'(.*)\'|iU", $matchLine, $contentMatch);if (!empty($contentMatch[1])) break;preg_match("/[\s]+content[\s]*\=[\s]*(.*)[>|\s|$]/iU", $matchLine, $contentMatch);if (!empty($contentMatch[1])) break;} while (FALSE);$metaContent = !empty($contentMatch[1]) ? $this->htmlToText($contentMatch[1]) : '';$ret[] = array('name'=>$metaName, 'content'=>$metaContent);}
}
return $ret;}
function fetchImageTexts($returnType='array') {$string = $this->_html;preg_match_all('|<img (.*)>|iU', $string, $matches, PREG_PATTERN_ORDER);$ret = array();if (!empty($matches[1]) && is_array($matches[1])) {foreach ($matches[1] as $imageProperties) {$alt   = $this->getTagParam('alt',   $imageProperties);$title = $this->getTagParam('title', $imageProperties);if ($returnType == 'string') {if (!empty($alt))   $ret[] = $alt;if (!empty($title)) $ret[] = $title;} else {if (!empty($alt) || !empty($title)) {$ret[] = array('alt'=>$alt, 'title'=>$title);}
}
}
}
if ($returnType == 'string') {return join(' . ', $ret);} else {return $ret;}
}
function _glueUrl($base, $relative) {if (strpos($relative, '://')) return $relative;if (substr($base, -1) === '/') {if (substr($relative, 0, 1) === '/') {return substr($base, 0, -1) . $relative;} else {return $base . $relative;}
} else {if (substr($relative, 0, 1) === '/') {return $base . $relative;} else {return $base . '/' . $relative;}
}
}
function get_strings_in_tags($tags, $string){for($i=0;$i<count($tags);$i++){$k=0;$pattern="|".$tags[$i]['open']."(.*)".$tags[$i]['close']."|U";preg_match_all($pattern,$string,$matches, PREG_PATTERN_ORDER);for($j=0;$j<count($tags);$j++){if($tags[$j]['open']!=$tags[$i]['open'] && $tags[$j]['close']!=$tags[$i]['close']){$new_tags[$k]=$tags[$j];$k++;}
}
for($j=0;$j<count($matches[1]);$j++){$new_string=$matches[1][$j];}
if(count($tags)==1){for ($j=0;$j<count($matches[1]);$j++){$end_matches[$j]=strip_tags($matches[1][$j]);}
return $end_matches;} else {for($j=0;$j<count($matches[1]);$j++){$new_string=$matches[1][$j];$end_matches=array_merge($this->get_strings_in_tags($new_tags,$new_string),$end_matches);}
}
}
return $end_matches;}
function get_strings_in_tag($start_tag,$end_tag,$string){$pattern="|".$start_tag."(.*)".$end_tag."|U";preg_match_all($pattern,$string,$matches, PREG_PATTERN_ORDER);for($j=0;$j<count($matches[1]);$j++){$array[$j]=$matches[1][$j];}
return $array;}
function get_strings_headed($from_headnumber,$till_headnumber){$count_headers=$till_headnumber-$from_headnumber;$result_arr=array();for($i=$from_headnumber;$i<=$till_headnumber;$i++){$results=$this->get_strings_in_tag("<h$i>","</h$i>",$this->string);if($results!=""){$result_arr=array_merge($result_arr,$results);}
}
return $result_arr;}
function getTagParam($param, $tag){preg_match_all("|$param\=\"(.*)\"|iU", $tag, $matches, PREG_PATTERN_ORDER);if (!isSet($matches[1][0])) {preg_match_all("|$param\=\'(.*)\'|iU", $tag, $matches, PREG_PATTERN_ORDER);}
if (!isSet($matches[1][0])) {preg_match_all("/$param\=(.*)[>|\s|$]/iU", $tag, $matches, PREG_PATTERN_ORDER);}
if (isSet($matches[1][0])) return $matches[1][0];return NULL;}
function htmlToText($string) {$commentingThisOutFucksHomesiteColors = array ("'<script[^>]*?>.*?</script>'si");  $commentingThisOutFucksHomesiteColors = "'<[\/\!]*?[^<>]*?>'si";           $string = preg_replace ("'<script[^>]*?>.*?</script>'si", '', $string);$string = str_replace('<', ' <', $string); $string = strip_tags($string);$string = preg_replace ("|[\s]+|si", ' ', $string);$string = $this->Bs_HtmlUtil->htmlEntitiesUndo($string);return trim($string);}
}
if (basename($_SERVER['PHP_SELF']) == 'Bs_HtmlInfo.class.php') {$hi =& new Bs_HtmlInfo();$hi->initByPath('c:/del.html');dump($hi->fetchStringsByTagNameStupid('b'));}
?>
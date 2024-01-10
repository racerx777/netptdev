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
define("BS_HTMLENTITIES_VERSION",      '4.5.$Revision: 1.3 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'html/Bs_HtmlUtil.class.php');class Bs_HtmlEntities extends Bs_Object {var $Bs_HtmlUtil;var $_data;function Bs_HtmlEntities() {parent::Bs_Object(); $this->Bs_HtmlUtil = &$GLOBALS['Bs_HtmlUtil'];$this->_initData();}
function toDhtml($fullPage=TRUE) {$ret = '';if ($fullPage) {$ret .= '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>BlueShoes Html-Special-Chars Selector</title>
';}
$ret .= '
<style>
.bsEnvE {font-family : Arial, Helvetica, sans-serif;width:20;height:20;border:1px solid silver;margin:0px;}
.bsEnvLoupe {display:none;position:absolute;width:40;height:40;border:1px solid gray;background-color:antiquewhite;font-family : Arial, Helvetica, sans-serif;font-size: 30px;text-align : center;}
</style>
';$ret .= '<script>';$ret .= $this->Bs_HtmlUtil->arrayToJsArray($this->_data, 'bsEnvData');$ret .= "
function bsEntOver(obj) {document.getElementById('bsEntHtmlCodeDiv').innerText    = bsEnvData[obj.eCode]['entity'];document.getElementById('bsEntDescriptionDiv').innerText = bsEnvData[obj.eCode]['desc-en'];//return;var l = document.getElementById('bsEnvLoupe');l.innerHTML     = obj.innerHTML;l.style.top     = parseInt(obj.offsetTop)  -10;l.style.left    = parseInt(obj.offsetLeft) -10;l.style.display = 'block';}
function bsEntOut() {return;var l = document.getElementById('bsEnvLoupe');l.innerHTML     = '';l.style.display = 'none';}
function bsEntClick() {}
</script>
";if ($fullPage) {$ret .= '
</head>
<body bgcolor="white">
';}
$spanLineArr = array();;foreach($this->_data as $key => $arr) {$spanLineArr[] = '<span class="bsEnvE" eCode="' . $key . '" onMouseEnter="bsEntOver(this);" onMouseLeave="bsEntOut();" onClick="bsEntClick();">&' . $arr['entity'] . ';</span>';}
$spanLine = join('', $spanLineArr);$ret .= '
<div style="position:relative; width:400; height:160; overflow:auto; border:1px solid black; cursor:default; padding:10px;">
' . $spanLine . '
<div id="bsEnvLoupe" class="bsEnvLoupe"></div>
</div>
<div style="font-family: Arial, Helvetica, sans-serif; font-size:11px; color:gray">
HTML-Code: <span id="bsEntHtmlCodeDiv" style="color:black; width:40;"></span> 
Description: <span id="bsEntDescriptionDiv" style="color:black;"></span>
</div>
';if ($fullPage) {$ret .= '
</body>
</html>
';}
return $ret;}
function _initData_SORTED_FOR_CHAR_PICKER() {if (isSet($data)) return;static $data = array();$this->_data = &$data;$data['257']['entity'] = "";$data['257']['char'] = "$";$data['257']['desc-en'] = "dollar";$data['256']['entity'] = "euro";$data['256']['char'] = "�";$data['256']['desc-en'] = "euro";$data['163']['entity'] = "pound";$data['163']['char'] = "�";$data['163']['desc-en'] = "pound sterling";$data['165']['entity'] = "yen";$data['165']['char'] = "�";$data['165']['desc-en'] = "yen sign";$data['162']['entity'] = "cent";$data['162']['char'] = "�";$data['162']['desc-en'] = "cent sign";$data['164']['entity'] = "curren";$data['164']['char'] = "�";$data['164']['desc-en'] = "general currency sign";$data['169']['entity'] = "copy";$data['169']['char'] = "�";$data['169']['desc-en'] = "copyright";$data['174']['entity'] = "reg";$data['174']['char'] = "�";$data['174']['desc-en'] = "registered trademark";$data['171']['entity'] = "laquo";$data['171']['char'] = "�";$data['171']['desc-en'] = "left angle quote, guillemotleft";$data['187']['entity'] = "raquo";$data['187']['char'] = "�";$data['187']['desc-en'] = "right angle quote, guillemotright";$data['166']['entity'] = "brvbar";$data['166']['char'] = "�";$data['166']['desc-en'] = "broken vertical bar";$data['167']['entity'] = "sect";$data['167']['char'] = "�";$data['167']['desc-en'] = "section sign";$data['176']['entity'] = "deg";$data['176']['char'] = "�";$data['176']['desc-en'] = "degree sign";$data['182']['entity'] = "para";$data['182']['char'] = "�";$data['182']['desc-en'] = "paragraph sign";$data['183']['entity'] = "middot";$data['183']['char'] = "�";$data['183']['desc-en'] = "middle dot";$data['215']['entity'] = "times";$data['215']['char'] = "�";$data['215']['desc-en'] = "multiply sign";$data['247']['entity'] = "divide";$data['247']['char'] = "�";$data['247']['desc-en'] = "division sign";$data['177']['entity'] = "plusmn";$data['177']['char'] = "�";$data['177']['desc-en'] = "plus or minus";$data['172']['entity'] = "not";$data['172']['char'] = "�";$data['172']['desc-en'] = "not sign";$data['170']['entity'] = "ordf";$data['170']['char'] = "�";$data['170']['desc-en'] = "feminine ordinal";$data['186']['entity'] = "ordm";$data['186']['char'] = "�";$data['186']['desc-en'] = "masculine ordinal";$data['188']['entity'] = "frac14";$data['188']['char'] = "�";$data['188']['desc-en'] = "fraction one-fourth";$data['189']['entity'] = "frac12";$data['189']['char'] = "�";$data['189']['desc-en'] = "fraction one-half";$data['190']['entity'] = "frac34";$data['190']['char'] = "�";$data['190']['desc-en'] = "fraction three-fourths";$data['185']['entity'] = "sup1";$data['185']['char'] = "�";$data['185']['desc-en'] = "superscript one";$data['178']['entity'] = "sup2";$data['178']['char'] = "�";$data['178']['desc-en'] = "superscript two";$data['179']['entity'] = "sup3";$data['179']['char'] = "�";$data['179']['desc-en'] = "superscript three";$data['161']['entity'] = "iexcl";$data['161']['char'] = "�";$data['161']['desc-en'] = "inverted exclamation";$data['168']['entity'] = "uml";$data['168']['char'] = "�";$data['168']['desc-en'] = "umlaut (dieresis)";$data['173']['entity'] = "shy";$data['173']['char'] = "�";$data['173']['desc-en'] = "soft hyphen";$data['175']['entity'] = "macr";$data['175']['char'] = "�";$data['175']['desc-en'] = "macron accent";$data['180']['entity'] = "acute";$data['180']['char'] = "�";$data['180']['desc-en'] = "acute accent";$data['181']['entity'] = "micro";$data['181']['char'] = "�";$data['181']['desc-en'] = "micro sign";$data['184']['entity'] = "cedil";$data['184']['char'] = "�";$data['184']['desc-en'] = "cedilla";$data['191']['entity'] = "iquest";$data['191']['char'] = "�";$data['191']['desc-en'] = "inverted question mark";$data['192']['entity'] = "Agrave";$data['192']['char'] = "�";$data['192']['desc-en'] = "capital A, grave accent";$data['193']['entity'] = "Aacute";$data['193']['char'] = "�";$data['193']['desc-en'] = "capital A, acute accent";$data['194']['entity'] = "Acirc";$data['194']['char'] = "�";$data['194']['desc-en'] = "capital A, circumflex accent";$data['195']['entity'] = "Atilde";$data['195']['char'] = "�";$data['195']['desc-en'] = "capital A, tilde";$data['196']['entity'] = "Auml";$data['196']['char'] = "�";$data['196']['desc-en'] = "capital A, dieresis or umlaut mark";$data['197']['entity'] = "Aring";$data['197']['char'] = "�";$data['197']['desc-en'] = "capital A, ring";$data['198']['entity'] = "AElig";$data['198']['char'] = "�";$data['198']['desc-en'] = "capital AE diphthong (ligature)";$data['199']['entity'] = "Ccedil";$data['199']['char'] = "�";$data['199']['desc-en'] = "capital C, cedilla";$data['200']['entity'] = "Egrave";$data['200']['char'] = "�";$data['200']['desc-en'] = "capital E, grave accent";$data['201']['entity'] = "Eacute";$data['201']['char'] = "�";$data['201']['desc-en'] = "capital E, acute accent";$data['202']['entity'] = "Ecirc";$data['202']['char'] = "�";$data['202']['desc-en'] = "capital E, circumflex accent";$data['203']['entity'] = "Euml";$data['203']['char'] = "�";$data['203']['desc-en'] = "capital E, dieresis or umlaut mark";$data['204']['entity'] = "Igrave";$data['204']['char'] = "�";$data['204']['desc-en'] = "capital I, grave accent";$data['205']['entity'] = "Iacute";$data['205']['char'] = "�";$data['205']['desc-en'] = "capital I, acute accent";$data['206']['entity'] = "Icirc";$data['206']['char'] = "�";$data['206']['desc-en'] = "capital I, circumflex accent";$data['207']['entity'] = "Iuml";$data['207']['char'] = "�";$data['207']['desc-en'] = "capital I, dieresis or umlaut mark";$data['208']['entity'] = "ETH";$data['208']['char'] = "�";$data['208']['desc-en'] = "capital Eth, Icelandic";$data['209']['entity'] = "Ntilde";$data['209']['char'] = "�";$data['209']['desc-en'] = "capital N, tilde";$data['210']['entity'] = "Ograve";$data['210']['char'] = "�";$data['210']['desc-en'] = "capital O, grave accent";$data['211']['entity'] = "Oacute";$data['211']['char'] = "�";$data['211']['desc-en'] = "capital O, acute accent";$data['212']['entity'] = "Ocirc";$data['212']['char'] = "�";$data['212']['desc-en'] = "capital O, circumflex accent";$data['213']['entity'] = "Otilde";$data['213']['char'] = "�";$data['213']['desc-en'] = "capital O, tilde";$data['214']['entity'] = "Ouml";$data['214']['char'] = "�";$data['214']['desc-en'] = "capital O, dieresis or umlaut mark";$data['216']['entity'] = "Oslash";$data['216']['char'] = "�";$data['216']['desc-en'] = "capital O, slash";$data['217']['entity'] = "Ugrave";$data['217']['char'] = "�";$data['217']['desc-en'] = "capital U, grave accent";$data['218']['entity'] = "Uacute";$data['218']['char'] = "�";$data['218']['desc-en'] = "capital U, acute accent";$data['219']['entity'] = "Ucirc";$data['219']['char'] = "�";$data['219']['desc-en'] = "capital U, circumflex accent";$data['220']['entity'] = "Uuml";$data['220']['char'] = "�";$data['220']['desc-en'] = "capital U, dieresis or umlaut mark";$data['221']['entity'] = "Yacute";$data['221']['char'] = "�";$data['221']['desc-en'] = "capital Y, acute accent";$data['222']['entity'] = "THORN";$data['222']['char'] = "�";$data['222']['desc-en'] = "capital THORN, Icelandic";$data['223']['entity'] = "szlig";$data['223']['char'] = "�";$data['223']['desc-en'] = "small sharp s, German (sz ligature)";$data['224']['entity'] = "agrave";$data['224']['char'] = "�";$data['224']['desc-en'] = "small a, grave accent";$data['225']['entity'] = "aacute";$data['225']['char'] = "�";$data['225']['desc-en'] = "small a, acute accent";$data['226']['entity'] = "acirc";$data['226']['char'] = "�";$data['226']['desc-en'] = "small a, circumflex accent";$data['227']['entity'] = "atilde";$data['227']['char'] = "�";$data['227']['desc-en'] = "small a, tilde";$data['228']['entity'] = "auml";$data['228']['char'] = "�";$data['228']['desc-en'] = "small a, dieresis or umlaut mark";$data['229']['entity'] = "aring";$data['229']['char'] = "�";$data['229']['desc-en'] = "small a, ring";$data['230']['entity'] = "aelig";$data['230']['char'] = "�";$data['230']['desc-en'] = "small ae diphthong (ligature)";$data['231']['entity'] = "ccedil";$data['231']['char'] = "�";$data['231']['desc-en'] = "small c, cedilla";$data['232']['entity'] = "egrave";$data['232']['char'] = "�";$data['232']['desc-en'] = "small e, grave accent";$data['233']['entity'] = "eacute";$data['233']['char'] = "�";$data['233']['desc-en'] = "small e, acute accent";$data['234']['entity'] = "ecirc";$data['234']['char'] = "�";$data['234']['desc-en'] = "small e, circumflex accent";$data['235']['entity'] = "euml";$data['235']['char'] = "�";$data['235']['desc-en'] = "small e, dieresis or umlaut mark";$data['236']['entity'] = "igrave";$data['236']['char'] = "�";$data['236']['desc-en'] = "small i, grave accent";$data['237']['entity'] = "iacute";$data['237']['char'] = "�";$data['237']['desc-en'] = "small i, acute accent";$data['238']['entity'] = "icirc";$data['238']['char'] = "�";$data['238']['desc-en'] = "small i, circumflex accent";$data['239']['entity'] = "iuml";$data['239']['char'] = "�";$data['239']['desc-en'] = "small i, dieresis or umlaut mark";$data['240']['entity'] = "eth";$data['240']['char'] = "�";$data['240']['desc-en'] = "small eth, Icelandic";$data['241']['entity'] = "ntilde";$data['241']['char'] = "�";$data['241']['desc-en'] = "small n, tilde";$data['242']['entity'] = "ograve";$data['242']['char'] = "�";$data['242']['desc-en'] = "small o, grave accent";$data['243']['entity'] = "oacute";$data['243']['char'] = "�";$data['243']['desc-en'] = "small o, acute accent";$data['244']['entity'] = "ocirc";$data['244']['char'] = "�";$data['244']['desc-en'] = "small o, circumflex accent";$data['245']['entity'] = "otilde";$data['245']['char'] = "�";$data['245']['desc-en'] = "small o, tilde";$data['246']['entity'] = "ouml";$data['246']['char'] = "�";$data['246']['desc-en'] = "small o, dieresis or umlaut mark";$data['248']['entity'] = "oslash";$data['248']['char'] = "�";$data['248']['desc-en'] = "small o, slash";$data['249']['entity'] = "ugrave";$data['249']['char'] = "�";$data['249']['desc-en'] = "small u, grave accent";$data['250']['entity'] = "uacute";$data['250']['char'] = "�";$data['250']['desc-en'] = "small u, acute accent";$data['251']['entity'] = "ucirc";$data['251']['char'] = "�";$data['251']['desc-en'] = "small u, circumflex accent";$data['252']['entity'] = "uuml";$data['252']['char'] = "�";$data['252']['desc-en'] = "small u, dieresis or umlaut mark";$data['253']['entity'] = "yacute";$data['253']['char'] = "�";$data['253']['desc-en'] = "small y, acute accent";$data['254']['entity'] = "thorn";$data['254']['char'] = "�";$data['254']['desc-en'] = "small thorn, Icelandic";$data['255']['entity'] = "yuml";$data['255']['char'] = "�";$data['255']['desc-en'] = "small y, dieresis or umlaut mark";}
function _initData() {if (isSet($data)) return;static $data = array();$this->_data = &$data;$data['161']['entity'] = "iexcl";$data['161']['char'] = "�";$data['161']['desc-en'] = "inverted exclamation";$data['162']['entity'] = "cent";$data['162']['char'] = "�";$data['162']['desc-en'] = "cent sign";$data['163']['entity'] = "pound";$data['163']['char'] = "�";$data['163']['desc-en'] = "pound sterling";$data['164']['entity'] = "curren";$data['164']['char'] = "�";$data['164']['desc-en'] = "general currency sign";$data['165']['entity'] = "yen";$data['165']['char'] = "�";$data['165']['desc-en'] = "yen sign";$data['166']['entity'] = "brvbar";$data['166']['char'] = "�";$data['166']['desc-en'] = "broken vertical bar";$data['167']['entity'] = "sect";$data['167']['char'] = "�";$data['167']['desc-en'] = "section sign";$data['168']['entity'] = "uml";$data['168']['char'] = "�";$data['168']['desc-en'] = "umlaut (dieresis)";$data['169']['entity'] = "copy";$data['169']['char'] = "�";$data['169']['desc-en'] = "copyright";$data['170']['entity'] = "ordf";$data['170']['char'] = "�";$data['170']['desc-en'] = "feminine ordinal";$data['171']['entity'] = "laquo";$data['171']['char'] = "�";$data['171']['desc-en'] = "left angle quote, guillemotleft";$data['172']['entity'] = "not";$data['172']['char'] = "�";$data['172']['desc-en'] = "not sign";$data['173']['entity'] = "shy";$data['173']['char'] = "�";$data['173']['desc-en'] = "soft hyphen";$data['174']['entity'] = "reg";$data['174']['char'] = "�";$data['174']['desc-en'] = "registered trademark";$data['175']['entity'] = "macr";$data['175']['char'] = "�";$data['175']['desc-en'] = "macron accent";$data['176']['entity'] = "deg";$data['176']['char'] = "�";$data['176']['desc-en'] = "degree sign";$data['177']['entity'] = "plusmn";$data['177']['char'] = "�";$data['177']['desc-en'] = "plus or minus";$data['178']['entity'] = "sup2";$data['178']['char'] = "�";$data['178']['desc-en'] = "superscript two";$data['179']['entity'] = "sup3";$data['179']['char'] = "�";$data['179']['desc-en'] = "superscript three";$data['180']['entity'] = "acute";$data['180']['char'] = "�";$data['180']['desc-en'] = "acute accent";$data['181']['entity'] = "micro";$data['181']['char'] = "�";$data['181']['desc-en'] = "micro sign";$data['182']['entity'] = "para";$data['182']['char'] = "�";$data['182']['desc-en'] = "paragraph sign";$data['183']['entity'] = "middot";$data['183']['char'] = "�";$data['183']['desc-en'] = "middle dot";$data['184']['entity'] = "cedil";$data['184']['char'] = "�";$data['184']['desc-en'] = "cedilla";$data['185']['entity'] = "sup1";$data['185']['char'] = "�";$data['185']['desc-en'] = "superscript one";$data['186']['entity'] = "ordm";$data['186']['char'] = "�";$data['186']['desc-en'] = "masculine ordinal";$data['187']['entity'] = "raquo";$data['187']['char'] = "�";$data['187']['desc-en'] = "right angle quote, guillemotright";$data['188']['entity'] = "frac14";$data['188']['char'] = "�";$data['188']['desc-en'] = "fraction one-fourth";$data['189']['entity'] = "frac12";$data['189']['char'] = "�";$data['189']['desc-en'] = "fraction one-half";$data['190']['entity'] = "frac34";$data['190']['char'] = "�";$data['190']['desc-en'] = "fraction three-fourths";$data['191']['entity'] = "iquest";$data['191']['char'] = "�";$data['191']['desc-en'] = "inverted question mark";$data['192']['entity'] = "Agrave";$data['192']['char'] = "�";$data['192']['desc-en'] = "capital A, grave accent";$data['193']['entity'] = "Aacute";$data['193']['char'] = "�";$data['193']['desc-en'] = "capital A, acute accent";$data['194']['entity'] = "Acirc";$data['194']['char'] = "�";$data['194']['desc-en'] = "capital A, circumflex accent";$data['195']['entity'] = "Atilde";$data['195']['char'] = "�";$data['195']['desc-en'] = "capital A, tilde";$data['196']['entity'] = "Auml";$data['196']['char'] = "�";$data['196']['desc-en'] = "capital A, dieresis or umlaut mark";$data['197']['entity'] = "Aring";$data['197']['char'] = "�";$data['197']['desc-en'] = "capital A, ring";$data['198']['entity'] = "AElig";$data['198']['char'] = "�";$data['198']['desc-en'] = "capital AE diphthong (ligature)";$data['199']['entity'] = "Ccedil";$data['199']['char'] = "�";$data['199']['desc-en'] = "capital C, cedilla";$data['200']['entity'] = "Egrave";$data['200']['char'] = "�";$data['200']['desc-en'] = "capital E, grave accent";$data['201']['entity'] = "Eacute";$data['201']['char'] = "�";$data['201']['desc-en'] = "capital E, acute accent";$data['202']['entity'] = "Ecirc";$data['202']['char'] = "�";$data['202']['desc-en'] = "capital E, circumflex accent";$data['203']['entity'] = "Euml";$data['203']['char'] = "�";$data['203']['desc-en'] = "capital E, dieresis or umlaut mark";$data['204']['entity'] = "Igrave";$data['204']['char'] = "�";$data['204']['desc-en'] = "capital I, grave accent";$data['205']['entity'] = "Iacute";$data['205']['char'] = "�";$data['205']['desc-en'] = "capital I, acute accent";$data['206']['entity'] = "Icirc";$data['206']['char'] = "�";$data['206']['desc-en'] = "capital I, circumflex accent";$data['207']['entity'] = "Iuml";$data['207']['char'] = "�";$data['207']['desc-en'] = "capital I, dieresis or umlaut mark";$data['208']['entity'] = "ETH";$data['208']['char'] = "�";$data['208']['desc-en'] = "capital Eth, Icelandic";$data['209']['entity'] = "Ntilde";$data['209']['char'] = "�";$data['209']['desc-en'] = "capital N, tilde";$data['210']['entity'] = "Ograve";$data['210']['char'] = "�";$data['210']['desc-en'] = "capital O, grave accent";$data['211']['entity'] = "Oacute";$data['211']['char'] = "�";$data['211']['desc-en'] = "capital O, acute accent";$data['212']['entity'] = "Ocirc";$data['212']['char'] = "�";$data['212']['desc-en'] = "capital O, circumflex accent";$data['213']['entity'] = "Otilde";$data['213']['char'] = "�";$data['213']['desc-en'] = "capital O, tilde";$data['214']['entity'] = "Ouml";$data['214']['char'] = "�";$data['214']['desc-en'] = "capital O, dieresis or umlaut mark";$data['215']['entity'] = "times";$data['215']['char'] = "�";$data['215']['desc-en'] = "multiply sign";$data['216']['entity'] = "Oslash";$data['216']['char'] = "�";$data['216']['desc-en'] = "capital O, slash";$data['217']['entity'] = "Ugrave";$data['217']['char'] = "�";$data['217']['desc-en'] = "capital U, grave accent";$data['218']['entity'] = "Uacute";$data['218']['char'] = "�";$data['218']['desc-en'] = "capital U, acute accent";$data['219']['entity'] = "Ucirc";$data['219']['char'] = "�";$data['219']['desc-en'] = "capital U, circumflex accent";$data['220']['entity'] = "Uuml";$data['220']['char'] = "�";$data['220']['desc-en'] = "capital U, dieresis or umlaut mark";$data['221']['entity'] = "Yacute";$data['221']['char'] = "�";$data['221']['desc-en'] = "capital Y, acute accent";$data['222']['entity'] = "THORN";$data['222']['char'] = "�";$data['222']['desc-en'] = "capital THORN, Icelandic";$data['223']['entity'] = "szlig";$data['223']['char'] = "�";$data['223']['desc-en'] = "small sharp s, German (sz ligature)";$data['224']['entity'] = "agrave";$data['224']['char'] = "�";$data['224']['desc-en'] = "small a, grave accent";$data['225']['entity'] = "aacute";$data['225']['char'] = "�";$data['225']['desc-en'] = "small a, acute accent";$data['226']['entity'] = "acirc";$data['226']['char'] = "�";$data['226']['desc-en'] = "small a, circumflex accent";$data['227']['entity'] = "atilde";$data['227']['char'] = "�";$data['227']['desc-en'] = "small a, tilde";$data['228']['entity'] = "auml";$data['228']['char'] = "�";$data['228']['desc-en'] = "small a, dieresis or umlaut mark";$data['229']['entity'] = "aring";$data['229']['char'] = "�";$data['229']['desc-en'] = "small a, ring";$data['230']['entity'] = "aelig";$data['230']['char'] = "�";$data['230']['desc-en'] = "small ae diphthong (ligature)";$data['231']['entity'] = "ccedil";$data['231']['char'] = "�";$data['231']['desc-en'] = "small c, cedilla";$data['232']['entity'] = "egrave";$data['232']['char'] = "�";$data['232']['desc-en'] = "small e, grave accent";$data['233']['entity'] = "eacute";$data['233']['char'] = "�";$data['233']['desc-en'] = "small e, acute accent";$data['234']['entity'] = "ecirc";$data['234']['char'] = "�";$data['234']['desc-en'] = "small e, circumflex accent";$data['235']['entity'] = "euml";$data['235']['char'] = "�";$data['235']['desc-en'] = "small e, dieresis or umlaut mark";$data['236']['entity'] = "igrave";$data['236']['char'] = "�";$data['236']['desc-en'] = "small i, grave accent";$data['237']['entity'] = "iacute";$data['237']['char'] = "�";$data['237']['desc-en'] = "small i, acute accent";$data['238']['entity'] = "icirc";$data['238']['char'] = "�";$data['238']['desc-en'] = "small i, circumflex accent";$data['239']['entity'] = "iuml";$data['239']['char'] = "�";$data['239']['desc-en'] = "small i, dieresis or umlaut mark";$data['240']['entity'] = "eth";$data['240']['char'] = "�";$data['240']['desc-en'] = "small eth, Icelandic";$data['241']['entity'] = "ntilde";$data['241']['char'] = "�";$data['241']['desc-en'] = "small n, tilde";$data['242']['entity'] = "ograve";$data['242']['char'] = "�";$data['242']['desc-en'] = "small o, grave accent";$data['243']['entity'] = "oacute";$data['243']['char'] = "�";$data['243']['desc-en'] = "small o, acute accent";$data['244']['entity'] = "ocirc";$data['244']['char'] = "�";$data['244']['desc-en'] = "small o, circumflex accent";$data['245']['entity'] = "otilde";$data['245']['char'] = "�";$data['245']['desc-en'] = "small o, tilde";$data['246']['entity'] = "ouml";$data['246']['char'] = "�";$data['246']['desc-en'] = "small o, dieresis or umlaut mark";$data['247']['entity'] = "divide";$data['247']['char'] = "�";$data['247']['desc-en'] = "division sign";$data['248']['entity'] = "oslash";$data['248']['char'] = "�";$data['248']['desc-en'] = "small o, slash";$data['249']['entity'] = "ugrave";$data['249']['char'] = "�";$data['249']['desc-en'] = "small u, grave accent";$data['250']['entity'] = "uacute";$data['250']['char'] = "�";$data['250']['desc-en'] = "small u, acute accent";$data['251']['entity'] = "ucirc";$data['251']['char'] = "�";$data['251']['desc-en'] = "small u, circumflex accent";$data['252']['entity'] = "uuml";$data['252']['char'] = "�";$data['252']['desc-en'] = "small u, dieresis or umlaut mark";$data['253']['entity'] = "yacute";$data['253']['char'] = "�";$data['253']['desc-en'] = "small y, acute accent";$data['254']['entity'] = "thorn";$data['254']['char'] = "�";$data['254']['desc-en'] = "small thorn, Icelandic";$data['255']['entity'] = "yuml";$data['255']['char'] = "�";$data['255']['desc-en'] = "small y, dieresis or umlaut mark";}
function _importHtmlSpecialChars() {$list = <<< EOD
inverted exclamation                 �    &amp;#161; --> &#161;    &amp;iexcl;  --> &iexcl;
cent sign                            �    &amp;#162; --> &#162;    &amp;cent;   --> &cent;
pound sterling                       �    &amp;#163; --> &#163;    &amp;pound;  --> &pound;
general currency sign                �    &amp;#164; --> &#164;    &amp;curren; --> &curren;
yen sign                             �    &amp;#165; --> &#165;    &amp;yen;    --> &yen;
broken vertical bar                  �    &amp;#166; --> &#166;    &amp;brvbar; --> &brvbar;
                                                          &amp;brkbar; --> &brkbar;
section sign                         �    &amp;#167; --> &#167;    &amp;sect;   --> &sect;
umlaut (dieresis)                    �    &amp;#168; --> &#168;    &amp;uml;    --> &uml;
                                                          &amp;die;    --> &die;
copyright                            �    &amp;#169; --> &#169;    &amp;copy;   --> &copy;
feminine ordinal                     �    &amp;#170; --> &#170;    &amp;ordf;   --> &ordf;
left angle quote, guillemotleft      �    &amp;#171; --> &#171;    &amp;laquo;  --> &laquo;
not sign                             �    &amp;#172; --> &#172;    &amp;not;    --> &not;
soft hyphen                          �    &amp;#173; --> &#173;    &amp;shy;    --> &shy;
registered trademark                 �    &amp;#174; --> &#174;    &amp;reg;    --> &reg;
macron accent                        �    &amp;#175; --> &#175;    &amp;macr;   --> &macr;
                                                          &amp;hibar;  --> &hibar;
degree sign                          �    &amp;#176; --> &#176;    &amp;deg;    --> &deg;
plus or minus                        �    &amp;#177; --> &#177;    &amp;plusmn; --> &plusmn;
superscript two                      �    &amp;#178; --> &#178;    &amp;sup2;   --> &sup2;
superscript three                    �    &amp;#179; --> &#179;    &amp;sup3;   --> &sup3;
acute accent                         �    &amp;#180; --> &#180;    &amp;acute;  --> &acute;
micro sign                           �    &amp;#181; --> &#181;    &amp;micro;  --> &micro;
paragraph sign                       �    &amp;#182; --> &#182;    &amp;para;   --> &para;
middle dot                           �    &amp;#183; --> &#183;    &amp;middot; --> &middot;
cedilla                              �    &amp;#184; --> &#184;    &amp;cedil;  --> &cedil;
superscript one                      �    &amp;#185; --> &#185;    &amp;sup1;   --> &sup1;
masculine ordinal                    �    &amp;#186; --> &#186;    &amp;ordm;   --> &ordm;
right angle quote, guillemotright    �    &amp;#187; --> &#187;    &amp;raquo;  --> &raquo;
fraction one-fourth                  �    &amp;#188; --> &#188;    &amp;frac14; --> &frac14;
fraction one-half                    �    &amp;#189; --> &#189;    &amp;frac12; --> &frac12;
fraction three-fourths               �    &amp;#190; --> &#190;    &amp;frac34; --> &frac34;
inverted question mark               �    &amp;#191; --> &#191;    &amp;iquest; --> &iquest;
capital A, grave accent              �    &amp;#192; --> &#192;    &amp;Agrave; --> &Agrave;
capital A, acute accent              �    &amp;#193; --> &#193;    &amp;Aacute; --> &Aacute;
capital A, circumflex accent         �    &amp;#194; --> &#194;    &amp;Acirc;  --> &Acirc;
capital A, tilde                     �    &amp;#195; --> &#195;    &amp;Atilde; --> &Atilde;
capital A, dieresis or umlaut mark   �    &amp;#196; --> &#196;    &amp;Auml;   --> &Auml;
capital A, ring                      �    &amp;#197; --> &#197;    &amp;Aring;  --> &Aring;
capital AE diphthong (ligature)      �    &amp;#198; --> &#198;    &amp;AElig;  --> &AElig;
capital C, cedilla                   �    &amp;#199; --> &#199;    &amp;Ccedil; --> &Ccedil;
capital E, grave accent              �    &amp;#200; --> &#200;    &amp;Egrave; --> &Egrave;
capital E, acute accent              �    &amp;#201; --> &#201;    &amp;Eacute; --> &Eacute;
capital E, circumflex accent         �    &amp;#202; --> &#202;    &amp;Ecirc;  --> &Ecirc;
capital E, dieresis or umlaut mark   �    &amp;#203; --> &#203;    &amp;Euml;   --> &Euml;
capital I, grave accent              �    &amp;#204; --> &#204;    &amp;Igrave; --> &Igrave;
capital I, acute accent              �    &amp;#205; --> &#205;    &amp;Iacute; --> &Iacute;
capital I, circumflex accent         �    &amp;#206; --> &#206;    &amp;Icirc;  --> &Icirc;
capital I, dieresis or umlaut mark   �    &amp;#207; --> &#207;    &amp;Iuml;   --> &Iuml;
capital Eth, Icelandic               �    &amp;#208; --> &#208;    &amp;ETH;    --> &ETH;
                                                          &amp;Dstrok; --> &Dstrok;
capital N, tilde                     �    &amp;#209; --> &#209;    &amp;Ntilde; --> &Ntilde;
capital O, grave accent              �    &amp;#210; --> &#210;    &amp;Ograve; --> &Ograve;
capital O, acute accent              �    &amp;#211; --> &#211;    &amp;Oacute; --> &Oacute;
capital O, circumflex accent         �    &amp;#212; --> &#212;    &amp;Ocirc;  --> &Ocirc;
capital O, tilde                     �    &amp;#213; --> &#213;    &amp;Otilde; --> &Otilde;
capital O, dieresis or umlaut mark   �    &amp;#214; --> &#214;    &amp;Ouml;   --> &Ouml;
multiply sign                        �    &amp;#215; --> &#215;    &amp;times;  --> &times;
capital O, slash                     �    &amp;#216; --> &#216;    &amp;Oslash; --> &Oslash;
capital U, grave accent              �    &amp;#217; --> &#217;    &amp;Ugrave; --> &Ugrave;
capital U, acute accent              �    &amp;#218; --> &#218;    &amp;Uacute; --> &Uacute;
capital U, circumflex accent         �    &amp;#219; --> &#219;    &amp;Ucirc;  --> &Ucirc;
capital U, dieresis or umlaut mark   �    &amp;#220; --> &#220;    &amp;Uuml;   --> &Uuml;
capital Y, acute accent              �    &amp;#221; --> &#221;    &amp;Yacute; --> &Yacute;
capital THORN, Icelandic             �    &amp;#222; --> &#222;    &amp;THORN;  --> &THORN;
small sharp s, German (sz ligature)  �    &amp;#223; --> &#223;    &amp;szlig;  --> &szlig;
small a, grave accent                �    &amp;#224; --> &#224;    &amp;agrave; --> &agrave;
small a, acute accent                �    &amp;#225; --> &#225;    &amp;aacute; --> &aacute;
small a, circumflex accent           �    &amp;#226; --> &#226;    &amp;acirc;  --> &acirc;
small a, tilde                       �    &amp;#227; --> &#227;    &amp;atilde; --> &atilde;
small a, dieresis or umlaut mark     �    &amp;#228; --> &#228;    &amp;auml;   --> &auml;
small a, ring                        �    &amp;#229; --> &#229;    &amp;aring;  --> &aring;
small ae diphthong (ligature)        �    &amp;#230; --> &#230;    &amp;aelig;  --> &aelig;
small c, cedilla                     �    &amp;#231; --> &#231;    &amp;ccedil; --> &ccedil;
small e, grave accent                �    &amp;#232; --> &#232;    &amp;egrave; --> &egrave;
small e, acute accent                �    &amp;#233; --> &#233;    &amp;eacute; --> &eacute;
small e, circumflex accent           �    &amp;#234; --> &#234;    &amp;ecirc;  --> &ecirc;
small e, dieresis or umlaut mark     �    &amp;#235; --> &#235;    &amp;euml;   --> &euml;
small i, grave accent                �    &amp;#236; --> &#236;    &amp;igrave; --> &igrave;
small i, acute accent                �    &amp;#237; --> &#237;    &amp;iacute; --> &iacute;
small i, circumflex accent           �    &amp;#238; --> &#238;    &amp;icirc;  --> &icirc;
small i, dieresis or umlaut mark     �    &amp;#239; --> &#239;    &amp;iuml;   --> &iuml;
small eth, Icelandic                 �    &amp;#240; --> &#240;    &amp;eth;    --> &eth;
small n, tilde                       �    &amp;#241; --> &#241;    &amp;ntilde; --> &ntilde;
small o, grave accent                �    &amp;#242; --> &#242;    &amp;ograve; --> &ograve;
small o, acute accent                �    &amp;#243; --> &#243;    &amp;oacute; --> &oacute;
small o, circumflex accent           �    &amp;#244; --> &#244;    &amp;ocirc;  --> &ocirc;
small o, tilde                       �    &amp;#245; --> &#245;    &amp;otilde; --> &otilde;
small o, dieresis or umlaut mark     �    &amp;#246; --> &#246;    &amp;ouml;   --> &ouml;
division sign                        �    &amp;#247; --> &#247;    &amp;divide; --> &divide;
small o, slash                       �    &amp;#248; --> &#248;    &amp;oslash; --> &oslash;
small u, grave accent                �    &amp;#249; --> &#249;    &amp;ugrave; --> &ugrave;
small u, acute accent                �    &amp;#250; --> &#250;    &amp;uacute; --> &uacute;
small u, circumflex accent           �    &amp;#251; --> &#251;    &amp;ucirc;  --> &ucirc;
small u, dieresis or umlaut mark     �    &amp;#252; --> &#252;    &amp;uuml;   --> &uuml;
small y, acute accent                �    &amp;#253; --> &#253;    &amp;yacute; --> &yacute;
small thorn, Icelandic               �    &amp;#254; --> &#254;    &amp;thorn;  --> &thorn;
small y, dieresis or umlaut mark     �    &amp;#255; --> &#255;    &amp;yuml;   --> &yuml;
EOD;
$data = array();$e = explode("\n", $list);while (list(,$line) = each($e)) {if ($line[0] == ' ') continue;$desc   = trim(substr($line, 0, 37));$char   = substr($line, 37, 1);$code   = substr($line, 59, 3);$entity = substr(substr($line, 85), 0, -1);$data[$code] = array(
'entity'  => $entity, 
'char'    => $char, 
'desc-en' => $desc, 
);}
global $APP;require_once($APP['path']['core'] . 'util/Bs_Array.class.php');echo $GLOBALS['Bs_Array']->arrayToCode(&$data, $name='$data');}
}
$x =& new Bs_HtmlEntities;echo $x->toDhtml();?>
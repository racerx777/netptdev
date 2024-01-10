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
if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');class Bs_HtmlNavigation extends Bs_Object {var $_data;var $_style;var $_requestUrl;function Bs_HtmlNavigation() {parent::Bs_Object();}
function setData($data) {if (!is_array($data)) $data = array();$this->_data = &$data;$this->_addUniqueId($this->_data);$this->_addParent($this->_data, $tmp=NULL);reset($this->_data);}
function setCurrentPage($requestUrl) {$this->_requestUrl = $requestUrl;$this->_locateCurrentPage($this->_data);}
function debugData($data, $prefix='') {foreach ($data as $val) {echo $prefix . $val['caption'] . " ({$val['active']})" . getType($val['active']) . "<br>\n";if (!empty($val['children']) && is_array($val['children'])) {$this->debugData($val['children'], $prefix . '&nbsp;&nbsp;&nbsp;');}
}
}
function _locateCurrentPage(&$arr, $isFirstCall=TRUE) {$fallbackChild = NULL;while (list($key, $val) = each($arr)) {if ( (isSet($val['key']) AND ($val['key'] === $this->_requestUrl)) OR 
(!isSet($val['key']) AND isSet($val['url']) AND ($val['url'] === $this->_requestUrl)) ) {  if (isSet($val['parent'])) {$this->_walkTreeUp($arr[$key]['parent']);$this->_walkSibling($arr[$key]['parent']['children']);} else {$this->_walkSibling($this->_data);}
if (isSet($val['children'])) {$this->_walkTreeDown($arr[$key]['children']);}
$arr[$key]['active'] = 'self';reset($arr);return;}
if (isSet($val['children'])) {$this->_locateCurrentPage($arr[$key]['children'], FALSE);} else {$sub = substr($this->_requestUrl, 0, strlen($val['key']));if (($sub === $val['key']) || ($sub === $val['url'])) {$fallbackChild = &$arr[$key];}
}
}
if (!is_null($fallbackChild)) {$fallbackChild['active'] = 'child';}
reset($arr);}
function _walkTreeUp(&$arr) {$arr['active'] = 'child';if (isSet($arr['parent']['children'])) {$this->_walkSibling($arr['parent']['children'], 'sibling-of-line-up');}
if (isSet($arr['parent'])) $this->_walkTreeUp($arr['parent']);}
function _walkTreeDown(&$arr) {reset($arr);while (list($k) = each($arr)) {$arr[$k]['active'] = 'parent';if (isSet($arr[$k]['children'])) {$this->_walkTreeDown($arr[$k]['children']);}
}
reset($arr);}
function _walkSibling(&$arr, $what='sibling') {if (getType($arr) == 'array') {reset($arr); while (list($k) = each($arr)) {if (empty($arr[$k]['active']) || (substr($arr[$k]['active'], 0, 15) == 'sibling-of-line')) {$arr[$k]['active'] = $what;}
}
}
}
function _addUniqueId(&$data, $idPrefix='') {$id = 1;while (list($k) = each($data)) {if (!isSet($data[$k]['url'])) $data[$k]['url'] = '';if (empty($data[$k]['key'])) $data[$k]['key'] = $data[$k]['url'];$data[$k]['id'] = $idPrefix . $id;if (isSet($data[$k]['children'])) {$this->_addUniqueId($data[$k]['children'], $idPrefix . $id . 'x');}
$id++;}
reset($data);}
function _addParent(&$data, &$parent) {while (list($k) = each($data)) {if (!is_null($parent)) {$data[$k]['parent'] = &$parent;}
if (isSet($data[$k]['children'])) {$this->_addParent($data[$k]['children'], $data[$k]);}
}
reset($data);}
function setStyle($style) {$this->_style = &$style;}
function _setDefaultStyle() {$this->_style = array(
'head'     => '<table>', 
'foot'     => '</table>', 
'empty'    => 'no navigation data', 
'level'      => array(
'1+'         => array(
'head'       => '', 
'foot'       => '<tr><td>&nbsp;</td></tr>', 
'link'       => array(
'default'              =>   '<tr><td><?php for ($i=1; $i<$level; $i++) { echo "&nbsp;&nbsp;&nbsp;&nbsp;"; }?>__LINK__</td></tr>', 
'defaultActiveSelf'    =>   '<tr><td><?php for ($i=1; $i<$level; $i++) { echo "&nbsp;&nbsp;&nbsp;&nbsp;"; }?><b>__LINK__</b></td></tr>', 
'defaultActiveChild'   =>   '<tr><td><?php for ($i=1; $i<$level; $i++) { echo "&nbsp;&nbsp;&nbsp;&nbsp;"; }?><b>__LINK__</b></td></tr>', 
), 
), 
), 
);$this->_style = array(
'lifecycle'  => array(), 
'apiVersion' => '2', 
'head'       => '', 
'foot'       => '', 
'empty'      => '', 
'level'      => array(
'1+'         => array(
'head'       => '', 
'foot'       => '', 
'empty'      => '', 
'options'    => array(
array(
'active'   => 'self', 
'row'      => 'any', 
'children' => '', 
'php'      => '', 
'head'     => '', 
'foot'     => '', 
'empty'    => '', 
'link'     => '__CAPTION__', 
), 
), 
), 
), 
);}
function parseLinkStyle($linkStyle, $data, $linkFormat=NULL, $noLinkFormat=NULL) {$target  = (isSet($data['target'])) ? $data['target'] : '_self';if (is_null($linkFormat)) {if (isSet($data['url'])) {$linkFormat = '<a href="__URL__" target="__TARGET__">__CAPTION__</a>';} else {$linkFormat = '__CAPTION__';}
}
$ret = $linkStyle;$ret = str_replace('__LINK__',       $linkFormat     , $ret);$ret = str_replace('__KEY__',        $data['key']    , $ret);$ret = str_replace('__URL__',        $data['url']    , $ret);$ret = str_replace('__TARGET__',     $target         , $ret);$ret = str_replace('__CAPTION_URLENCODE__',  rawurlencode($data['caption']), $ret);$ret = str_replace('__CAPTION_UPPER__',      strToUpper($data['caption']),   $ret);$ret = str_replace('__CAPTION_LOWER__',      strToLower($data['caption']),   $ret);$ret = str_replace('__CAPTION__',    $data['caption'], $ret);$ret = str_replace('__ID__',         $data['id']     , $ret);$sSize = strLen('__MY_');foreach($data as $key => $val) {if (0 === strncmp('__MY_', $key, $sSize)) {$ret = str_replace($key, $val, $ret);}
}
$parentId = $data['id'];$pos = strrpos($parentId, 'x');if ($pos === FALSE) {$parentId = '';} else {$parentId = substr($parentId, 0, $pos);}
$ret = str_replace('__PARENT_ID__',  $parentId      , $ret);return $ret;}
function parseHeadFootStyle($style, $parentId) {return $this->_localEval(str_replace('__PARENT_ID__', $parentId, $style));}
function _getStyleForLevel($level) {if (isSet($this->_style['level'][$level])) {return $this->_style['level'][$level];} elseif (isSet($this->_style['level'][$level . '+'])) {return $this->_style['level'][$level . '+'];} else {for ($i=$level; $i>0; $i--) {if (isSet($this->_style['level'][$i . '+'])) {return $this->_style['level'][$i . '+'];}
}
}
return FALSE;}
function _toHtmlHelper($data, $level=1) {$ret  = '';$style = $this->_getStyleForLevel($level);if ($style === FALSE) return '';if (isSet($style['head'])) {$parentId = $data[0]['id'];$pos = strrpos($parentId, 'x');if ($pos === FALSE) {$parentId = '';} else {$parentId = substr($parentId, 0, $pos);}
$ret .= $this->parseHeadFootStyle($style['head'], $parentId);}
foreach ($data as $e) {do {if (empty($this->_style['apiVersion'])) {$linkStyle = @$style['link']['default'];if (empty($e['active'])) {break;}
if ($e['active'] == 'self') {if (!empty($e['children'])) {if (isSet($style['link']['childrenActiveSelf'])) {$linkStyle = $style['link']['childrenActiveSelf'];break;}
}
if (isSet($style['link']['defaultActiveSelf'])) {$linkStyle = $style['link']['defaultActiveSelf'];break;}
}
if ($e['active'] == 'sibling') {if (!empty($e['children'])) {if (isSet($style['link']['childrenActiveSibling'])) {$linkStyle = $style['link']['childrenActiveSibling'];break;}
}
if (isSet($style['link']['defaultActiveSibling'])) {$linkStyle = $style['link']['defaultActiveSibling'];break;}
}
if ($e['active'] == 'child') {if (!empty($e['children'])) {if (isSet($style['link']['childrenActiveChild'])) {$linkStyle = $style['link']['childrenActiveChild'];break;}
}
if (isSet($style['link']['defaultActiveChild'])) {$linkStyle = $style['link']['defaultActiveChild'];break;}
}
if ($e['active'] == 'parent') {if (!empty($e['children'])) {if (isSet($style['link']['childrenActiveParent'])) {$linkStyle = $style['link']['childrenActiveParent'];break;}
}
if (isSet($style['link']['defaultActiveParent'])) {$linkStyle = $style['link']['defaultActiveParent'];break;}
}
if (!empty($e['children'])) {if (isSet($style['link']['childrenActive'])) {$linkStyle = $style['link']['childrenActive'];break;}
}
if (isSet($style['link']['defaultActive'])) {$linkStyle = $style['link']['defaultActive'];break;}
if (!empty($e['children'])) {if (isSet($style['link']['children'])) {$linkStyle = $style['link']['children'];break;}
}
} else {$linkStyle = '';foreach ($style['options'] as $optionArr) {$doContinue = FALSE;if (!empty($optionArr['active'])) {switch ($optionArr['active']) {case 'any':
break;case 'self':
if ($e['active'] == 'self') {break;} else {$doContinue = TRUE;break;}
case 'parent':
if ($e['active'] == 'parent') {break;} else {$doContinue = TRUE;break;}
case 'child':
if ($e['active'] == 'child') {break;} else {$doContinue = TRUE;break;}
case 'sibling':
if ($e['active'] == 'sibling') {break;} else {$doContinue = TRUE;break;}
case 'line':
if (($e['active'] == 'self') || ($e['active'] == 'parent') || ($e['active'] == 'child')) {break;} else {$doContinue = TRUE;break;}
case 'line-or-sibling':
if (($e['active'] == 'self') || ($e['active'] == 'parent') || ($e['active'] == 'child') || ($e['active'] == 'sibling')) {break;} else {$doContinue = TRUE;break;}
case 'line-or-sibling-of-line':
if (($e['active'] == 'self') || ($e['active'] == 'parent') || ($e['active'] == 'child') || ($e['active'] == 'sibling') || ($e['active'] == 'sibling-of-line-up') || ($e['active'] == 'sibling-of-line-down')) {break;} else {$doContinue = TRUE;break;}
default:
}
}
if ($doContinue) continue;if (isSet($optionArr['children']) && ($optionArr['children'] != '')) {if ((empty($e['children']) && !$optionArr['children']) || (!empty($e['children']) && $optionArr['children'])) {} else {continue;}
}
if (!empty($optionArr['row'])) {switch ($optionArr['row']) {case 'any':
break;default:
}
}
$linkStyle = $optionArr['link'];break;}
}
} while (FALSE);$linkText  = $this->parseLinkStyle($linkStyle, $e, (isSet($style['linkFormat'])) ? $style['linkFormat'] : NULL);if (strpos($linkText, '<?') !== FALSE) {$evalWrapperContext = array(
'file'          => 'Bs_HtmlNavigatioin.class.php',      'function'      => '_toHtmlHelper()',      'line'          => __LINE__,    'sourceFile'    => __FILE__,      'security'      => 'high',  'display_errors' => FALSE,   );$evalParams = array(
'level' => $level, 
);$linkText = evalWrapper($linkText, $evalWrapperContext, $evalParams);if ($evalWrapperContext['error'] == 2) {$ret .= "\n<br> {$evalWrapperContext['errType']} during eval():{$be} {$evalWrapperContext['errMsg']}. ";if (!empty($evalWrapperContext['sourceFile'])) {$ret .= "'{$evalWrapperContext['sourceFile']}:({$evalWrapperContext['errLine']})'. ";}
$ret .= "Called from '{$evalWrapperContext['file']}:{$evalWrapperContext['function']}({$evalWrapperContext['line']})<br>\n";$ret .= "<br>\n";} else {$ret .= $linkText;}
} else {$ret .= $linkText;}
if (!empty($e['children']) AND is_array($e['children'])) {$ret .= $this->_toHtmlHelper($e['children'], $level +1);}
}
if (isSet($style['foot'])) {if (!isSet($parentId)) {$parentId = $data[0]['id'];$pos = strrpos($parentId, 'x');if ($pos === FALSE) {$parentId = '';} else {$parentId = substr($parentId, 0, $pos);}
}
$ret .= $this->parseHeadFootStyle($style['foot'], $parentId);}
return $ret;}
function _localEval($str) {if (strpos($str, '<?') !== FALSE) {$ret = '';$evalWrapperContext = array(
'file'          => 'Bs_HtmlNavigatioin.class.php',      'function'      => '_localEval()',      'line'          => __LINE__,    'sourceFile'    => __FILE__,      'security'      => 'high',  'display_errors' => FALSE,   );$evalParams = array();$str = evalWrapper($str, $evalWrapperContext, $evalParams);if ($evalWrapperContext['error'] == 2) {$ret .= "\n<br> {$evalWrapperContext['errType']} during eval():{$be} {$evalWrapperContext['errMsg']}. ";if (!empty($evalWrapperContext['sourceFile'])) {$ret .= "'{$evalWrapperContext['sourceFile']}:({$evalWrapperContext['errLine']})'. ";}
$ret .= "Called from '{$evalWrapperContext['file']}:{$evalWrapperContext['function']}({$evalWrapperContext['line']})<br>\n";$ret .= "<br>\n";} else {$ret .= $str;}
return $ret;}
return $str;}
function toHtml() {if ((!isSet($this->_style)) || (!is_array($this->_style))) {$this->_setDefaultStyle();}
if ((!is_array($this->_data)) || (empty($this->_data))) {if (!empty($this->_style['empty'])) return $this->_style['empty'];return '';}
$ret  = '';if (isSet($this->_style['head'])) $ret .= $this->_style['head'];$ret .= $this->_toHtmlHelper($this->_data);if (isSet($this->_style['foot'])) $ret .= $this->_style['foot'];return $ret;}
}
?>
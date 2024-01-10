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
define('BS_FORMTEMPLATEPARSER_VERSION',      '4.5.$Revision: 1.4 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'util/Bs_String.class.php');require_once($APP['path']['core'] . 'file/Bs_Dir.class.php');require_once($APP['path']['core'] . 'xml/Bs_XmlParser.class.php');require_once($APP['path']['core'] . 'html/form/Bs_FormHandler.class.php');class Bs_FormTemplateParser extends Bs_XmlParser {var $Bs_String;var $pageID;var $_page;var $templateString;var $tags;var $_offset = 0;var $templatePath;var $caseFolding = FALSE;function Bs_FormTemplateParser($pageID=0, $page=NULL) {parent::Bs_XmlParser(); $this->Bs_String = &$GLOBALS['Bs_String'];$this->pageID    = $pageID;$this->_page     = &$page;}
function loadTemplate($template, $type='page', $options=array(), $walkSubdirs=FALSE) {if (strpos($template, '../') !== FALSE) return FALSE; if (strpos($template, '.') === FALSE) {$dir =& new Bs_Dir();if ($type == 'formcontainer') {$regEx = '^' . $template . '_' . $options['name'] . '_.*_.*_.*_.*\.' . $type . '\.tpl\.(html|php)$';} else {$regEx = '^' . $template . '_.*_.*_.*_.*\.' . $type . '\.tpl\.(html|php)$';}
$t = array(
'regEx'       => $regEx, 
'fileDirLink' => array('file'=>TRUE, 'dir'=>FALSE, 'filelink'=>TRUE, 'dirlink'=>FALSE), 
'followLinks' => TRUE, 
'returnType'  => 'subdir/file', 
'fullPath'    => $this->_getTemplatePath(), 
'depth'       => ($walkSubdirs) ? BS_DIR_UNLIM_DEPTH : 0, 
);$fileList = $dir->getFileList($t);$template = '';do { if (sizeOf($fileList) > 0) {while (list($k) = each($fileList)) {$mode  = '(' . $options['mode']  . ')?'; $state = '(' . $options['state'] . ')?'; if ($type == 'formcontainer') {$regEx = '^.*_.*' . $mode . '_' . $state . '_(.*)_(.*)\.formcontainer\.tpl\.';} else {$regEx = '^.*_' . $mode . '_' . $state . '_(.*)_(.*)\.form\.tpl\.';}
if (ereg($regEx, $fileList[$k]['file'], $regs)) {$fileList[$k]['user']     = $regs[1];$fileList[$k]['language'] = $regs[2];if ($fileList[$k]['language'] == $options['language']) {$template = $fileList[$k]['dir'] . $fileList[$k]['file'];break 2;}
} else {unset($fileList[$k]);continue;}
}
if (is_array($fileList) && (sizeOf($fileList) > 0)) {reset($fileList);$currentElement = current($fileList);$template = $currentElement['dir'] . $currentElement['file'];} else {return FALSE;}
} else {return FALSE;}
} while (FALSE);}
if (empty($template)) return FALSE;$fullPath = $this->_getTemplatePath() . $template;return $this->loadTemplateFromFullpath($fullPath);}
function loadTemplateFromFullpath($fullPath) {if (!file_exists($fullPath)) {return new Bs_Exception('template not found: ' . $fullPath, __FILE__, __LINE__, '');} elseif (!is_readable($fullPath)) {return new Bs_Exception('template not readable: ' . $fullPath, __FILE__, __LINE__, '');}
$t = file($fullPath);$this->templateString = join ('', $t);return TRUE;}
function loadTemplateFromString($str) {$this->templateString = $str;return TRUE;}
function _getTemplatePath() {if (isSet($this->templatePath) && is_string($this->templatePath)) {return $this->templatePath;} else {return getAbsolutePath() . '../templates/' . $template;}
}
function parse($debug=FALSE) {$posEnd = 0;$tags = array();$bs_input = -1;$i = 0;do { do { $posStart  = strpos($this->templateString, '<bs',  $posEnd); $posStart2 = strpos($this->templateString, '</bs', $posEnd); if ($posStart === FALSE) {if ($posStart2 === FALSE) {break 2; } else {$posStart = $posStart2;}
} elseif ($posStart2 === FALSE) {} else {$posStart = min($posStart, $posStart2);}
$posEnd   = strpos($this->templateString, '>', $posStart);if ($posEnd === FALSE) break 2; $tagString = substr($this->templateString, $posStart, $posEnd - $posStart +1);if ($debug) echo '<br>TAG FOUND<br>tag is: ' . htmlSpecialChars($tagString) . '<br>';if ($this->isCommentedOut($tagString)) break; $tagName    = $this->getTagName($tagString);if ($debug) echo 'tagName is: ' . $tagName . '<br>';$properties = $this->parseTag($tagString);if ($debug) {echo 'properties are: <br>';var_dump($properties);echo '<br>';}
$tempTag['tag']        = &$tagName;$tempTag['properties'] = &$properties;$tempTag['posStart']   = $posStart;$tempTag['posEnd']     = $posEnd;unset($tagName);unset($properties);switch ($tempTag['tag']) {case 'bs':
case 'bs_content':
case 'bs_navigation':
$tags[$i] = &$tempTag;$i++;break;case 'bs_form':
$bs_input = $i;$tags[$i] = &$tempTag;$i++;break;case 'bs_input':
case 'bs_forminfo':
$tags[$bs_input]['subTags'][] = &$tempTag;$tags[$i] = &$tempTag;$i++;break;case 'bs_formerrors':
$tags[$bs_input]['subTags'][] = &$tempTag;$tags[$i] = &$tempTag;$i++;break;case 'bs_formbuttons':
$tags[$bs_input]['subTags'][] = &$tempTag;$tags[$i] = &$tempTag;$i++;break;case '/bs_form':
$bs_input = -1;$tags[$i] = &$tempTag;$i++;break;default:
if ($debug) echo "*MURPHY*<br>\n";}
unset($tempTag);if ($debug) echo '<br>';} while (FALSE);} while (TRUE);$this->tags = $tags;}
function apply($language=NULL, $form=NULL, $editable=FALSE) {reset($this->tags);while (list($k) = each($this->tags)) {$tag = &$this->tags[$k]; switch (@$tag['tag']) {case 'bs_navigation':
$nav =& new Bs_NavigationSite();if ((isSet($tag['properties']['function'])) && ( method_exists($nav, $tag['properties']['function']))) {$this->applyTag($k, $nav->$tag['properties']['function']());} else {}
break;case 'bs_content':
$contentBlock =& new Bs_ContentBlock();$blockVersion = ($this->_page->publishMode == 'published') ? '-1' : '-2';$status = $contentBlock->loadBlock($tag['properties']['name'], $this->pageID, $blockVersion);if (isEx($status)) {$status->stackTrace('was here in apply()', __FILE__, __LINE__);if ($this->_page->publishMode == 'published') {$status->stackDump('log');return '';} else {return $status;}
} else {if ($editable) {$t  = "<table border=0 cellpadding=0 cellspacing=0 ID='bs_cb_{$tag['properties']['name']}' class='highLight' onMouseOver=\"layerActive('bs_cb_{$tag['properties']['name']}')\" onMouseOut=\"layerPassive('bs_cb_{$tag['properties']['name']}')\" onClick=\"layerEditStart('bs_cb_{$tag['properties']['name']}', 'bs_cb_span_{$tag['properties']['name']}')\">\n";$t .= "<tr><td valign=top>\n";$t .= "<span id='bs_cb_span_{$tag['properties']['name']}' contentEditable='false'>\n";$this->applyTag($k, $t . $contentBlock->getValue() . "\n</span></td></tr></table>\n");} else {$this->applyTag($k, $contentBlock->getValue());}
}
break;case 'bs_form':
if ((!is_null($form)) && ((!isSet($tag['properties']['name'])) || ($form->internalName == $tag['properties']['name']))) {} elseif (isSet($tag['properties']['name'])) {$formHandler =& new Bs_FormHandler($tag['properties']['name']);if (!is_null($language)) $formHandler->setLanguage($language);if (isSet($tag['properties']['mode'])) $formHandler->setMode($tag['properties']['mode']);$status = $formHandler->go();if (isEx($status)) {$status->stackDump('echo'); break;}
$form = &$formHandler->form;} else {break;}
if ($form->state != 'done') {$this->applyTag($k, $form->getFormHead());break;}
$this->applyTag($k, '');break;case 'bs_input':
if (isSet($form)) {$type = $tag['properties']['type'];switch ($type) {case 'caption':
case 'text':
case 'error':
case 'help':
$this->applyTag($k, $form->getElement($tag['properties']['name'], $type));break;default: if (isSet($tag['properties']['elementlist'])) {$tag['properties']['elementlist'] = explode(',', $tag['properties']['elementlist']);$this->applyTag($k, $form->getElement($tag['properties']['name'], 'element', $tag['properties']['elementlist']));} else {$this->applyTag($k, $form->getElement($tag['properties']['name'], 'element'));}
}
}
break;case 'bs_forminfo':
if (isSet($form)) {switch ($tag['properties']['type']) {case 'recordId':
$this->applyTag($k, $form->recordId);break;case 'usedTime':
$this->applyTag($k, $form->usedTime);break;case 'viewCount':
$this->applyTag($k, $form->viewCount);break;default:
$this->applyTag($k, '');}
} else {$this->applyTag($k, '');}
break;case 'bs_formerrors':
if (isSet($form)) {if (isSet($tag['properties']['caption'])) {$this->applyTag($k, $form->getErrorTable($tag['properties']['caption']));} else {$this->applyTag($k, $form->getErrorTable('Errors occured'));}
} else {$this->applyTag($k, '');}
break;case 'bs_formbuttons':
if (isSet($form)) {$this->applyTag($k, $form->_getButtonString());} else {$this->applyTag($k, '');}
break;case '/bs_form':
if (isSet($form)) {if ($form->state != 'done') {$this->applyTag($k, $form->getFormFoot());break;}
}
$this->applyTag($k, '');break;default:
}
}
return TRUE;}
function applyTag($tagKey, $tagString) {if (!isSet($this->tags[$tagKey])) return FALSE;if ($this->Bs_String->insert($this->templateString, $tagString, $this->tags[$tagKey]['posEnd'] +$this->_offset +1)) {$this->tags[$tagKey]['posStart'] += $this->_offset;$this->tags[$tagKey]['posEnd']   += $this->_offset;$this->_offset += strlen($tagString);}
return TRUE;}
}
?>
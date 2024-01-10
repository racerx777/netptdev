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
define('BS_DAFORMFIELDPAGECONTROL_VERSION',      '4.5.$Revision: 1.4 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'html/form/Bs_FormContainer.class.php');class Bs_DaFormPageControl extends Bs_FormContainer {var $domApiDoRollover = TRUE;var $domApiEnabled    = TRUE;var $domApiAlign     = 'top';var $domApiWidth     = 400;var $domApiHeight    = 300;var $_domApiTabsReady;var $domApiDefaultOpen;function Bs_DaFormPageControl() {parent::Bs_FormContainer(); $this->elementType = 'container'; $tempArray = array('mode'=>'stream');$this->persisterVarSettings['domApiDoRollover']   = &$tempArray;$this->persisterVarSettings['domApiEnabled']      = &$tempArray;$this->persisterVarSettings['domApiAlign']        = &$tempArray;$this->persisterVarSettings['domApiWidth']        = &$tempArray;$this->persisterVarSettings['domApiHeight']       = &$tempArray;$this->persister->setVarSettings(&$this->persisterVarSettings);}
function setTab($name, $caption, &$content, $type='DIV') {$this->_domApiTabsReady[$name] = array(
'caption' => $caption, 
'type'    => $type, 
'content' => &$content, 
);if (is_object($content)) {$content->setFormObject($this->_form);$content->container = &$this;$this->formElements[$content->name] = &$content; }
}
function &getElement() {if (!is_array($this->_domApiTabsReady) || empty($this->_domApiTabsReady)) {return '';}
$ret = '';$spanId          = $this->name . 'SpanId';$elementName     = $this->name . 'Elm';$divId           = $this->name . 'DivId';reset($this->_domApiTabsReady);$tabsOutput = '';while (list($tabName) = each($this->_domApiTabsReady)) {$caption = $this->getLanguageDependentValue($this->_domApiTabsReady[$tabName]['caption']);$type    = $this->_domApiTabsReady[$tabName]['type'];if ($type == 'IFRAME') {$url  = $this->_domApiTabsReady[$tabName]['content'];$tabsOutput .= "{$elementName}.addPage(\"{$caption}\", \"{$type}\", \"{$url}\");\n";} else {$container = &$this->_domApiTabsReady[$tabName]['content'];$ret .= '<div id=' . $divId . '>';$ret .= $container->getElement();$ret .= '</div>';$tabsOutput .= "{$elementName}.assignElement(\"{$divId}\", null, null, \"{$caption}\");\n";}
}
$onLoadCode = "
{$elementName}=PageControl(core.getElm('{$spanId}'),null,null,null,{$this->domApiWidth},{$this->domApiHeight});{$elementName}.enabled = "    . boolToString($this->domApiEnabled)    . "; //not setEnabled() here.
{$elementName}.doRollover = " . boolToString($this->domApiDoRollover) . "; //not setDoRollover here.
{$elementName}.tabPos = '{$this->domApiAlign}';{$tabsOutput}
";$this->_form->addOnLoadCode($onLoadCode);$this->_form->addIncludeOnce('/_libDomapi/core_c.js');$this->_form->addIncludeOnce('/_libDomapi/gui/pagecontrol_c.js'); $ret .= '<span id="' . $spanId . '"></span>';$ret .= '<script language="JavaScript"><!-- core.loadUnit("pagecontrol"); //--></script>';return $ret;}
}
?>
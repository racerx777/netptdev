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
define('BS_FORMFIELDFILEBROWSER_VERSION',      '4.5.$Revision: 1.4 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');class Bs_FormFieldFileBrowser extends Bs_FormFieldText {var $fileBrowserUrl;function Bs_FormFieldFileBrowser() {$this->Bs_FormFieldText(); $this->caption    = 'File';$this->persister->setVarSettings(&$this->persisterVarSettings);}
function &getField($explodeKey=NULL, $addEnforceCheckbox=TRUE) {$ret = parent::getField($explodeKey, $addEnforceCheckbox);if (($this->getVisibility() === 'normal') && !empty($this->fileBrowserUrl)) {$fieldName = $this->_getFieldNameForHtml($this->name);$jsFuncName = $fieldName . '_imgBrwClbk'; $url        = $this->fileBrowserUrl . '?callbackFunction=' . $jsFuncName;$ret .= ' <img src="/_bsImages/buttons/bs_open.gif"';$ret .= ' border="0" style="cursor:hand;cursor:pointer;"';$ret .= ' onClick="window.open(\'' . $url . '\', \'Bs_ImageBrowser\', \'dependent=yes,width=500,height=300,location=no,menubar=no,scrollbars=no,status=no,toolbar=no\');"';$ret .= '>';$jsCode = "
<script>
function {$jsFuncName}(newVal) {var fld = document.getElementById('{$fieldName}');if (fld) fld.value = newVal;}
</script>
";$this->_form->addIntoHead($jsCode);}
return $ret;}
}
?>
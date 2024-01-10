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
define('BS_FORMFIELDFILE_VERSION',      '4.5.$Revision: 1.6 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'html/form/Bs_FormFieldTxt.class.php');require_once($APP['path']['core'] . 'file/Bs_FileSystem.class.php');class Bs_FormFieldFile extends Bs_FormFieldTxt {var $allowedTypes;var $maxFileSize;var $minFileSize;var $fileSizeUpdateIni = TRUE;var $fileInfo = 0;var $diskStorage = NULL;var $showCurrentFile = 1;var $showCurrentFilePrefixPath = '';var $_isFromPreviousSubmit = FALSE;function Bs_FormFieldFile() {$this->Bs_FormFieldTxt(); $this->fieldType = 'file';$this->persister->setVarSettings(&$this->persisterVarSettings);}
function &getField($explodeKey=NULL, $addEnforceCheckbox=TRUE) {$ret = '';$fieldName = $this->_getFieldNameForHtml($this->name);switch ($this->getVisibility()) {case 'omit':
return $ret;break;case 'invisible':
$this->_markAsUsed();return $this->getFieldAsHidden();break;case 'read':
case 'readonly':
$this->_markAsUsed();$fieldName .= '_readonly';$ret .= $this->getFieldAsHidden();$ret .= $this->_getTagStringValue($explodeKey);return $ret;break;case 'show':
$ret .= $this->_getTagStringValue($explodeKey);return $ret;break;default: $this->_markAsUsed();$ret .= "<input type=\"{$this->fieldType}\"";}
if (!is_null($explodeKey)) {$ret .= " name=\"{$fieldName}[{$explodeKey}]\"";} else {$ret .= " name=\"{$fieldName}\"";}
if (!empty($this->size)) $ret .= " size=\"{$this->size}\"";if (!empty($this->direction)) $ret .= " dir=\"{$this->direction}\"";$ret .= $this->_getTagStringStyles();$this->applyOnEnterBehavior();$ret .= $this->_getTagStringEvents();if (!is_null($t = $this->_getMaxLength())) $ret .= " maxlength=\"{$t}\"";$ret .= $this->_getTagStringAdditionalTags();$ret .= '>';if ($this->_form->step == 2) {if (($this->fileInfo['errorCode'] == 0) && !isSet($this->errorMessage)) {if (!is_null($explodeKey)) {$fieldNameHiddenData = $fieldName . '_pData[' . $explodeKey . ']';$fieldNameHiddenKey  = $fieldName . '_pKey['  . $explodeKey . ']';} else {$fieldNameHiddenData = $fieldName . '_pData';$fieldNameHiddenKey  = $fieldName . '_pKey';}
$serialized = serialize($this->fileInfo); $serKey     = md5($serialized . $this->_form->md5Key);$ret .= '<input type="hidden" name="' . $fieldNameHiddenData . '" value="' . $this->_Bs_HtmlUtil->filterForHtml($serialized) . '">';$ret .= '<input type="hidden" name="' . $fieldNameHiddenKey  . '" value="' . $this->_Bs_HtmlUtil->filterForHtml($serKey)     . '">';$ret .= '<br>already got your file ' . basename($this->fileInfo['origFullPath']) . ', no need to resubmit. but you can overwrite it if you like.';}
} else {if (!empty($this->valueDefault)) {switch ($this->showCurrentFile) {case 2:
case 3: $ret .= '<br>Current file: <a href="' . $this->showCurrentFilePrefixPath . $this->valueDefault . '" target="_blank" title="Open file in new window">' . $this->valueDefault . '</a>';break;case 4:
$ret .= '<br>Current file: ' . $this->valueDefault . '<br>';$ret .= '<img src="' . $this->showCurrentFilePrefixPath . $this->valueDefault . '" border="0">';break;case 3: case 2:
default: $ret .= '<br>Current file: ' . $this->valueDefault;}
}
}
if ($addEnforceCheckbox) $ret .= $this->addEnforceCheckbox();return $this->_doElementStringFormat($ret);}
function setValueFromPreviousSubmit($dataSerialized, $md5) {$this->_isFromPreviousSubmit = TRUE;$isOk = FALSE;do {$compareMd5 = md5($dataSerialized . $this->_form->md5Key);if ($md5 === $compareMd5) {$fileInfo = @unserialize($dataSerialized);if (is_array($fileInfo)) {$this->fileInfo = $fileInfo;$this->valueReceived = $fileInfo['origFullPath'];$isOk = TRUE;}
} else {}
} while (FALSE);if (!$isOk) {$this->valueReceived = '';}
}
function inputValidate($paramValue=NULL) {if ((is_null($paramValue)) && ($this->isExplodable()) && (is_array($this->valueInternal))) {while (list($k) = each($this->valueInternal)) {$status = $this->inputValidate($this->valueInternal[$k]);if ($status !== TRUE) return $status;}
return TRUE;} elseif (!is_null($paramValue)) {$v = &$paramValue;} else {$v = $this->valueInternal;}
if ($this->_isFromPreviousSubmit) {if (!is_array($this->fileInfo)) {return $this->errorMessage = 'We got a file from a previous submit, but the data got lost somehow. Please resubmit your file.';}
if (!file_exists($this->fileInfo['tmpFullPath'])) {return $this->errorMessage = 'We had a file from a previous submit, but somehow it does not exist anymore on the server. Please resubmit your file.';}
return TRUE;} else {$status = $this->_keepUploadedFile();if (is_string($status)) {return $status;} elseif ($status) {if (isSet($this->allowedTypes)) {if (is_array($this->allowedTypes)) {if (!in_array($this->fileInfo['extension'], $this->allowedTypes)) {return $this->errorMessage = 'Wrong file type: ' . $this->fileInfo['extension'] . '. Allowed are: ' . join($this->allowedTypes);}
} elseif (is_numeric($this->allowedTypes)) {if ($this->allowedTypes == 1) {$a = array('jpg', 'jpeg', 'gif', 'png');if (!in_array($this->fileInfo['extension'], $a)) {return $this->errorMessage = 'Wrong file type: ' . $this->fileInfo['extension'] . '. Allowed are only images of the types: ' . join($a);}
}
}
}
}
$vLength = strlen($v);unset($this->errorMessage);if (is_string($status = $this->validateMust             ($v, $vLength))) return $status;if (is_string($status = $this->validateOnlyOneOf        ($v)))           return $status;if (is_string($status = $this->validateOnlyIf           ($v, $vLength))) return $status;if (is_string($status = $this->validateMinLength        ($v, $vLength))) return $status;if (is_string($status = $this->validateMaxLength        ($v, $vLength))) return $status;if (is_string($status = $this->validateMustStartWith    ($v)))           return $status;if (is_string($status = $this->validateNotStartWith     ($v)))           return $status;if (is_string($status = $this->validateMustEndWith      ($v)))           return $status;if (is_string($status = $this->validateNotEndWith       ($v)))           return $status;if (is_string($status = $this->validateMustContain      ($v)))           return $status;if (is_string($status = $this->validateNotContain       ($v)))           return $status;if (is_string($status = $this->validateEqualTo          ($v)))           return $status;if (is_string($status = $this->validateNotEqualTo       ($v)))           return $status;if (is_string($status = $this->validateRegularExpression($v)))           return $status;if (is_string($status = $this->validateAdditionalCheck  ($v, $vLength))) return $status;return TRUE;}
}
function postReceiveTrigger() {parent::postReceiveTrigger();}
function _keepUploadedFile() {$this->fileInfo = array();$this->fileInfo['origFullPath'] = $_FILES[$this->name]['name'];$this->fileInfo['tmpFullPath']  = '';$this->fileInfo['mimeType']     = $_FILES[$this->name]['type'];$this->fileInfo['sizeBytes']    = $_FILES[$this->name]['size'];if (isSet($_FILES[$this->name]['error'])) {$this->fileInfo['errorCode']  = $_FILES[$this->name]['error'];} else {$this->fileInfo['errorCode']  = 0; }
$this->fileInfo['errorText']    = '';$this->fileInfo['extension']    = '';if (($this->fileInfo['errorCode'] === 0) && is_uploaded_file($_FILES[$this->name]['tmp_name'])) { $tmpDir         = getTmp();$tmpFilePrefix  = 'bsFrmUpl_';$tmpFileName    = md5($_FILES[$this->name]['name']);$tmpFullPath = $this->_makeTempFullPath($tmpDir, $tmpFilePrefix, $tmpFileName);$status = @move_uploaded_file($_FILES[$this->name]['tmp_name'], $tmpFullPath);if (!$status) {$this->fileInfo['errorCode'] = 99; return $this->errorMessage = $this->fileInfo['errorText'] = 'System problem: Failed copying the uploaded file. Please try uploading again, and if it fails contact the server administrator.';} else {$this->fileInfo['tmpFullPath']  = $tmpFullPath;$Bs_FileSystem =& new Bs_FileSystem();$this->fileInfo['extension']    = $Bs_FileSystem->getFileExtension($_FILES[$this->name]['name']);return TRUE;}
} elseif ($this->fileInfo['errorCode'] === 4) { return FALSE; } else {switch ($this->fileInfo['errorCode']) {case 0: if (!is_array($_FILES[$this->name]) || ($_FILES[$this->name] === '') || ($_FILES[$this->name]['tmp_name'] === '') || ($_FILES[$this->name]['tmp_name'] === 'empty')) {$this->fileInfo['errorCode'] = 4; return FALSE; }
$this->fileInfo['errorCode'] = 9;$this->fileInfo['errorText'] = 'Unknown error occured. Please check the file size and try again.';break;case 1: return $this->errorMessage = $this->fileInfo['errorText'] = 'The uploaded file exceeds the maximum allowed file size.';break;case 2: return $this->errorMessage = $this->fileInfo['errorText'] = 'The uploaded file exceeds the maximum allowed file size.';break;case 3: return $this->errorMessage = $this->fileInfo['errorText'] = 'The upload is broken, the file was only received partially.';break;case 5: return $this->errorMessage = $this->fileInfo['errorText'] = 'The size of the uploaded file is 0 bytes, the file is empty.';break;default:
return $this->errorMessage = $this->fileInfo['errorText'] = 'Undefined error occured.';}
}
}
function _makeTempFullPath($dir, $filePrefix, $fileName, $fileSuffix=0) {$fullPath = $dir . $filePrefix . $fileName;if ($fileSuffix > 0) $fullPath .= '_' . $fileSuffix;if (!file_exists($fullPath)) return $fullPath;if ($fileSuffix > 100) return $fullPath . '_foo'; return $this->_makeTempFullPath($dir, $filePrefix, $fileName, ++$fileSuffix);}
}
?>
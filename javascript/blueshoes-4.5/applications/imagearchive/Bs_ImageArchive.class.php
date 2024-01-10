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
require_once($APP['path']['core'] . 'file/Bs_FileSystem.class.php');require_once($APP['path']['core'] . 'file/Bs_Dir.class.php');require_once($APP['path']['core'] . 'util/Bs_UnitConverter.class.php');require_once($APP['path']['core'] . 'gfx/Bs_ImageUtil.class.php');require_once($APP['path']['core'] . 'date/Bs_Date.class.php');require_once($APP['path']['core'] . 'util/Bs_IniHandler.class.php');require_once($APP['path']['core'] . 'html/Bs_HtmlNavigation.class.php');require_once($APP['path']['core'] . 'html/Bs_HtmlUtil.class.php');class Bs_ImageArchive extends Bs_Object {var $Bs_FileSystem;var $Bs_ImageUtil;var $Bs_Date;var $Bs_HtmlUtil;var $serverBasePath;var $webBasePath;var $currentDirectory = '';var $offset = 0;var $allowedTypes = array('gif', 'jpg', 'jpeg');var $ignoreDir    = array('CVS');var $thumbSettings = array(
'prefix' => 'thumb_', 
'width'  => 150, 
'height' => 150, 
);var $overviewSettings = array(
'tdWidth'   => 160, 
'tdHeight'  => 160, 
'tdColor'   => 'silver', 
'cols'      => 3, 
'rows'      => 5, 
);var $thumbnailStyleString;var $thumbnailTableString = '<table width="100%" border="0" cellspacing="0" cellpadding="0">';var $navigationStyle;var $accessFileName = '.htBsFaAccess';var $sessionUser;var $numberOfImages;function Bs_ImageArchive() {parent::Bs_Object(); if (!@is_object($GLOBALS['Bs_FileSystem'])) $GLOBALS['Bs_FileSystem'] =& new Bs_FileSystem();$this->Bs_FileSystem = &$GLOBALS['Bs_FileSystem'];if (!@is_object($GLOBALS['Bs_ImageUtil']))  $GLOBALS['Bs_ImageUtil']  =& new Bs_ImageUtil();$this->Bs_ImageUtil  = &$GLOBALS['Bs_ImageUtil'];if (!@is_object($GLOBALS['Bs_Date']))       $GLOBALS['Bs_Date']       =& new Bs_Date();$this->Bs_Date       = &$GLOBALS['Bs_Date'];$this->Bs_HtmlUtil   = &$GLOBALS['Bs_HtmlUtil'];}
function getPageList($separator=', ', $cssClass=NULL, $cssStyle=NULL) {$imagesPerPage = $this->overviewSettings['cols'] * $this->overviewSettings['rows'];if ($this->numberOfImages <= $imagesPerPage) return '';$numberOfPages = ceil($this->numberOfImages / $imagesPerPage);$ret = array();for ($i=0; $i<$numberOfPages; $i++) {if ($this->offset == $i) {$t = '<b';if (!is_null($cssClass)) $t .= ' class="' . $cssClass . '"';if (!is_null($cssStyle)) $t .= ' style="' . $cssStyle . '"';$t .= '>' . ($i +1) . '</b>';$ret[] = $t;} else {$t  = '<a href="' . $_SERVER['PHP_SELF'] . '?currentDirectory=' . $this->currentDirectory . '&offset=' . $i . '"';if (!is_null($cssClass)) $t .= ' class="' . $cssClass . '"';if (!is_null($cssStyle)) $t .= ' style="' . $cssStyle . '"';$t .= '>';$t .= ($i +1) . '</a>';$ret[] = $t;}
}
return join($separator, $ret);}
function getOverview() {if (!$this->_mayAccess()) {return 'you may not access this directory.';}
$ret  = '';$dir =& new Bs_Dir($this->serverBasePath . $this->currentDirectory);$params = array(
'regEx'       => '^' . $this->thumbSettings['prefix'], 
'regWhere'    => 'file', 
'fileDirLink' => array('file'=>TRUE, 'dir'=>FALSE, 'filelink'=>TRUE, 'dirlink'=>FALSE), 
'depth'       => 0, 
'returnType'  => 'subpath', 
);$unit =& new Bs_UnitConverter();$fileList = $dir->getFileList($params);$this->numberOfImages = sizeOf($fileList);if ($this->offset > 0) {$imagesPerPage = $this->overviewSettings['cols'] * $this->overviewSettings['rows'];$skipImages    = $imagesPerPage * $this->offset;for ($i=0; $i<$skipImages; $i++) {while (list(,$imageName) = each($fileList)) {break;}
}
}
$ret .= $this->thumbnailTableString; $ret .= "<tr>";$i = 0; $j = 0; while (list(,$imageName) = each($fileList)) {$typ = $this->Bs_FileSystem->getFileExtension($imageName);$typ = strToLower($typ);if (in_array($typ, $this->allowedTypes)) {if ($i >= $this->overviewSettings['cols']) {$i = 0;$j++;if ($j >= $this->overviewSettings['rows']) {$ret .= "</tr>";break;} else {$ret .= "</tr><tr>";}
}
$bigImageName = substr($imageName, 6);$xxlImageName = 'xxl_' . $bigImageName;$bigImageServerFullpath = $this->serverBasePath . $this->currentDirectory . $bigImageName;if (!file_exists($bigImageServerFullpath)) continue;$i++;if (file_exists($bigImageServerFullpath)) {$imgInfo = getimagesize($bigImageServerFullpath);$fileSizeBytes    = filesize($bigImageServerFullpath);$fileSizeReadable = $unit->toUsefulBitAndByteString($fileSizeBytes);$datetime         = $this->Bs_Date->formatUnixTimestamp('eu-1', filectime($bigImageServerFullpath));$imgType          = $this->Bs_ImageUtil->getImgInfoType($imgInfo[2]);$imgWebpathBig    = $this->webBasePath . $this->currentDirectory . $bigImageName;$imgWebpathThumb  = $this->webBasePath . $this->currentDirectory . $imageName;$imgWebpathXxl    = $this->webBasePath . $this->currentDirectory . $xxlImageName;}
if (isSet($this->thumbnailStyleString)) {$styleStr = $this->thumbnailStyleString;if (strpos($styleStr, '__FULL_ALTTEXT__') !== FALSE) {if (!isSet($fullAltText)) {$fullAltText  = '';$fullAltText .= "Name:   __IMAGE_NAME_BIG__\n";$fullAltText .= "Upload: __UPLOAD_DATETIME__\n";$fullAltText .= "Width:  __IMAGE_WIDTH_BIG__\n";$fullAltText .= "Height: __IMAGE_HEIGHT_BIG__\n";$fullAltText .= "Type:   __IMAGE_TYPE_BIG__\n";$fullAltText .= "Size:   __IMAGE_SIZE_READABLE_BIG__";}
$styleStr = str_replace('__FULL_ALTTEXT__', $fullAltText, $styleStr);}
$styleStr = str_replace('__IMAGE_NAME_BIG__',          $bigImageName,     $styleStr);$styleStr = str_replace('__UPLOAD_DATETIME__',         $datetime,         $styleStr);$styleStr = str_replace('__IMAGE_WIDTH_BIG__',         $imgInfo[0],       $styleStr);$styleStr = str_replace('__IMAGE_HEIGHT_BIG__',        $imgInfo[1],       $styleStr);$styleStr = str_replace('__IMAGE_TYPE_BIG__',          $imgType,          $styleStr);$styleStr = str_replace('__IMAGE_SIZE_READABLE_BIG__', $fileSizeReadable, $styleStr);$styleStr = str_replace('__IMAGE_WEBPATH_BIG__',       $imgWebpathBig,    $styleStr);$styleStr = str_replace('__IMAGE_WEBPATH_THUMB__',     $imgWebpathThumb,  $styleStr);$styleStr = str_replace('__IMAGE_WEBPATH_XXL__',       $imgWebpathXxl,    $styleStr);$description = '';if (strpos($styleStr, '__DESCRIPTION_') !== FALSE) {$description = '';$txtPath = $this->serverBasePath . $this->currentDirectory . $this->Bs_FileSystem->getWithoutFileExtension($bigImageName) . '.txt';if (file_exists($txtPath)) {$descriptionPlain = join('', file($txtPath));if (strpos($descriptionPlain, '<') === FALSE) {$descriptionPlain = nl2br($descriptionPlain);}
$descriptionHtml  = $this->Bs_HtmlUtil->filterForHtml($descriptionPlain);$descriptionJs    = $this->Bs_HtmlUtil->filterForJavaScript($descriptionPlain);$styleStr = str_replace('__DESCRIPTION_PLAIN__',     $descriptionPlain, $styleStr);$styleStr = str_replace('__DESCRIPTION_HTML__',      $descriptionHtml,  $styleStr);$styleStr = str_replace('__DESCRIPTION_JS__',        $descriptionJs,    $styleStr);} else {$styleStr = str_replace('__DESCRIPTION_PLAIN__',     '', $styleStr);$styleStr = str_replace('__DESCRIPTION_HTML__',      '', $styleStr);$styleStr = str_replace('__DESCRIPTION_JS__',        '', $styleStr);}
}
$ret .= $styleStr;} else {$altText  = '';$altText .= "Name:   " . $bigImageName . "\n";$altText .= "Upload: " . $datetime . "\n";$altText .= "Width:  " . $imgInfo[0]   . "\n";$altText .= "Height: " . $imgInfo[1]   . "\n";$altText .= "Type:   " . $imgType . "\n";$altText .= "Size:   " . $fileSizeReadable;$ret .= "<td valign='middle' align='center' bgcolor='{$this->overviewSettings['tdColor']}'";$ret .= " width='{$this->overviewSettings['tdWidth']}'";$ret .= " height='{$this->overviewSettings['tdHeight']}'";$ret .= ">";$ret .= "<a href='"  . $imgWebpathBig . "'>";$ret .= "<img src='" . $imgWebpathThumb . "' border='0' alt='{$altText}'><br>";$ret .= "</a>";$ret .= "</td>";}
}
}
$ret .= "</tr>";$ret .= "</table>";return $ret;}
function getDirectoryControl() {$dir =& new Bs_Dir($this->serverBasePath); $params = array(
'fileDirLink' => array('file'=>FALSE, 'dir'=>TRUE, 'filelink'=>FALSE, 'dirlink'=>TRUE), 
'returnType'  => 'nested', 
);$dirList = $dir->getFileList($params);$navData = $this->_prepareDirectoryData($dirList);$n =& new Bs_HtmlNavigation();if (isSet($this->navigationStyle)) $n->setStyle($this->navigationStyle);$n->setData($navData);$requestUrl = $_SERVER['PHP_SELF'];if (isSet($_GET['currentDirectory'])) {$requestUrl .= '?currentDirectory=' . $_GET['currentDirectory'];}
$n->setCurrentPage($requestUrl);return $n->toHtml();}
function _prepareDirectoryData($dirList, $currentDir='') {$navData = array();foreach ($dirList as $dir => $children) {if (is_array($this->ignoreDir) && !empty($this->ignoreDir)) {if (in_array($dir, $this->ignoreDir)) continue; }
$t = array(
'url'     => $_SERVER['PHP_SELF'] . '?currentDirectory=' . $currentDir . $dir . '/', 
'caption' => $dir, 
);if (!empty($children) && is_array($children)) {$t['children'] = $this->_prepareDirectoryData($children, $currentDir . $dir . '/');}
$navData[] = $t;}
return $navData;}
function _prepareDirectoryControl($dirList, $_cDir='') {$ret  = '';$numIndent = substr_count($_cDir, '/');$indentString = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $numIndent);while (list($k) = each($dirList)) {$_cDir2 = $_cDir . $k . '/';if (is_array($this->ignoreDir) && !empty($this->ignoreDir)) {if (in_array($k, $this->ignoreDir)) continue; }
if (!$this->_mayAccess('view', $_cDir2)) {unset($dirList[$k]);continue;}
$caption = $k;$ret   .= $indentString . "<a href='{$_SERVER['PHP_SELF']}?currentDirectory={$_cDir2}' class='nav{$numIndent}'>{$caption}</a><br>";if ((is_array($dirList[$k])) && !empty($dirList[$k])) {$ret .= $this->_prepareDirectoryControl($dirList[$k], $_cDir2);}
}
return $ret;}
function getBreadcrumbNav() {$ret  = '';$fullPath = 'Home/' . $this->currentDirectory;$junks = explode('/', $fullPath);$cwd   = ''; $i = 0;while (list($k) = each($junks)) {if (empty($junks[$k])) {unset($junks[$k]);continue;}
if ($i > 0) $cwd .= $junks[$k] . '/'; $junks[$k] = "<a href='{$_SERVER['PHP_SELF']}?currentDirectory={$cwd}'>{$junks[$k]}</a>";$i++;}
$ret .= join(', ', $junks);return $ret;}
function treatUploadedFile() {if (!$this->_mayAccess('add')) return;if (isSet($_FILES) && is_array($_FILES) && (isSet($_FILES['bsfauserfile1']['tmp_name']) && ($_FILES['bsfauserfile1']['tmp_name'] != 'none'))) {$typ = $this->Bs_FileSystem->getFileExtension($_FILES['bsfauserfile1']['name']);$typ = strToLower($typ);if (in_array($typ, $this->allowedTypes)) {$path   = $this->serverBasePath . $this->currentDirectory . $_FILES['bsfauserfile1']['name'];$status = @move_uploaded_file($_FILES['bsfauserfile1']['tmp_name'], $path);$this->Bs_ImageUtil->resizeToFile($path, $this->thumbSettings['width'], $this->thumbSettings['height'], $this->serverBasePath . $this->currentDirectory . $this->thumbSettings['prefix'] . $_FILES['bsfauserfile1']['name'], 100);if ($status) {echo "dankeeeeee alles okeeeeee :)<br><br>";} else {echo "speicherung fehlgeschlagen";}
} else {echo "sie haben keine gültige datei heraufgeladen.";}
} else {}
}
function getFileUploadForm($maxFileSize=1000000, $numSimultanousFiles=1) {if (!$this->_mayAccess('add')) return '';$ret = '';$ret .= '<form enctype="multipart/form-data" action="' . $_SERVER['PHP_SELF'] . '" method="post">';$ret .= '<input type="hidden" name="currentDirectory" value="' . $this->currentDirectory . '">';$ret .= '<input type="hidden" name="MAX_FILE_SIZE" value="' . $maxFileSize . '">';for ($i=$numSimultanousFiles; $i<=$numSimultanousFiles; $i++) {$ret .= 'file: <input name="bsfauserfile' . $i . '" type="file">';}
$ret .= '<input type="submit" value="Submit"></form>';return $ret;}
function _loadAccessRights() {}
function _getAccessRights($dir=null) {if (is_null($dir)) $dir = $this->currentDirectory;$fileExists = TRUE;do {if (substr($dir, -1) == '/') $dir = substr($dir, 0, -1); $dirJunks = explode('/', $dir);$numDirJunks = count($dirJunks);for ($i=0; $i<$numDirJunks; $i++) {$iniPath = $this->serverBasePath . join('/', $dirJunks) . '/' . $this->accessFileName;if (file_exists($iniPath)) {break 2;}
unset($dirJunks[$numDirJunks -$i -1]); }
$fileExists = FALSE;} while (FALSE);if ($fileExists) {$ini =& new Bs_IniHandler($iniPath);$ret = array();if ($ini->has('access', 'view')) {$ret['view'] = $ini->get('access', 'view');if (empty($ret['view'])) $ret['view'] = 'none';} else {$ret['view'] = 'none';}
if ($ini->has('access', 'add')) {$ret['add'] = $ini->get('access', 'add');if (empty($ret['add'])) $ret['add'] = 'none';} else {$ret['add'] = 'none';}
if ($ini->has('access', 'edit')) {$ret['edit'] = $ini->get('access', 'edit');if (empty($ret['edit'])) $ret['edit'] = 'none';} else {$ret['edit'] = 'none';}
if ($ini->has('access', 'delete')) {$ret['delete'] = $ini->get('access', 'delete');if (empty($ret['delete'])) $ret['delete'] = 'none';} else {$ret['delete'] = 'none';}
return $ret;} else {return array(
'view'    => 'all', 
'add'     => 'all', 
'edit'    => 'all', 
'delete'  => 'all', 
);}
}
function _mayAccess($accessType='view', $dir=null) {$accessRights = $this->_getAccessRights($dir);switch ($accessRights[$accessType]) {case 'all':
return TRUE;case 'none':
return FALSE;case 'login':
return (isSet($this->sessionUser));default:
return (strpos($accessRights[$accessType], 'u:' . $this->sessionUser) !== FALSE);}
}
}
?>
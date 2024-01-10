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
require_once($_SERVER['DOCUMENT_ROOT']  . '../global.conf.php');require_once($GLOBALS['APP']['path']['core'] . 'lang/Bs_ToDo.lib.php');require_once($GLOBALS['APP']['path']['core'] . 'file/Bs_Dir.class.php');require_once($GLOBALS['APP']['path']['core'] . 'file/Bs_FileSystem.class.php');require_once($GLOBALS['APP']['path']['core'] . 'html/Bs_HtmlUtil.class.php');require_once($GLOBALS['APP']['path']['core'] . 'html/Bs_HtmlNavigation.class.php');require_once($GLOBALS['APP']['path']['core'] . 'html/form/Bs_FormItAble.class.php');require_once($GLOBALS['APP']['path']['core'] . 'util/Bs_Stripper.class.php');define('PACKCOL_SUBDIR', 'conf/');define('PACKCOL_EXTENTION', '.conf');define('PACKCOL_FRAME_META', 'frameMeta');define('PACKCOL_FRAME_NAVI', 'frameNavi');define('PACKCOL_FRAME_MAIN', 'frameMain');set_time_limit(600); ini_set('memory_limit', '50M'); $phpStartTag = '<?php';$phpEndTag   = '?>';$BS_PACKAGE_HEADERS_DEFAULT =<<<EOD
/********************************************************************************************
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
*/
EOD;
class PackageCollector extends Bs_Object {var $_fld_mainFilter = '';var $_fld_packageName         = 'Bs_Package';var $_fld_targetDir           = '/';var $_fld_baseDir             = '/';var $_fld_subDirs             = '';var $_fld_subDirsNotRecursive = '';var $_fld_mainFilter          = '';var $_fld_mainResultArr       = array();var $_fld_noChangeFilter      = '';var $_fld_noChangeResultArr   = array();var $_fld_unCommentFilter     = '';var $_fld_unCommentResultArr  = array();var $_fld_obfuseFilter        = '';var $_fld_obfuseResultArr     = array();var $_mainFilterHash = NULL;var $_noChangeFilterHash = NULL;var $_unCommentFilterHash = NULL;var $_obfuseFilterHash = NULL;var $_mainResults = NULL;var $_noChangeResults = NULL;var $_unCommentResults = NULL;var $_obfuseResults = NULL;var $_Bs_Dir = NULL; var $_subDirs;var $_subDirsNotRecursive;function PackageCollector() {$this->_Bs_Dir =& new Bs_Dir();$this->_todo = bs_requestToDo();}
function go() {if (empty($this->_todo[BS_TODO_NEXT_SCREEN])) $this->_todo[BS_TODO_NEXT_SCREEN] = 'START';switch ($this->_todo[BS_TODO_NEXT_SCREEN]) {case 'START': 
$this->_mainFrameLayout('Meta Screen', 'Sub-Frame');break;case 'Meta Screen': 
$this->_metaNaviScreen();break;case 'Sub-Frame':
if (empty($this->_todo[BS_TODO_DATAHASH]) OR empty($this->_todo[BS_TODO_DATAHASH]['menu'])) $this->_todo[BS_TODO_DATAHASH]['menu'] = 'PackageFilterSetup';switch ($this->_todo[BS_TODO_DATAHASH]['menu']) {case 'PackageFilterSetup':
$this->_subFrameLayout('Package List Screen', 'Filter Form Screen');break;case 'BuildPackage':
$this->_subFrameLayout('Package List Screen', 'Show Files Screen');break;default:
echo "Unknown 'menu' :" . $this->_todo[BS_TODO_DATAHASH]['menu'];die();}
break;case 'Package List Screen': 
if (empty($this->_todo[BS_TODO_DATAHASH]) OR empty($this->_todo[BS_TODO_DATAHASH]['menu'])) $this->_todo[BS_TODO_DATAHASH]['menu'] = 'PackageFilterSetup';switch ($this->_todo[BS_TODO_DATAHASH]['menu']) {case 'PackageFilterSetup':
$this->_packageNaviScreen($naviTargetScreen = 'Filter Form Screen');break;case 'BuildPackage':
$this->_packageNaviScreen($naviTargetScreen = 'Show Files Screen');break;default:
echo "Unknown 'menu' :" . $this->_todo[BS_TODO_DATAHASH]['menu'];die();}
break;case 'Filter Form Screen':
$this->_filterFormScreen();break;case 'Show Files Screen':
$this->_showFilesScreen();break;default:
echo "Unknown NEXT SCREEN to use:" . $this->_todo[BS_TODO_NEXT_SCREEN];die();}
}
function _setup($loadConfigFile=FALSE) {$err = '';do {if ($loadConfigFile) {if (!$this->_load($loadConfigFile)) {$err = "Unable to load the requested config file: [{$loadConfigFile}]!";break; }
}
if (!$tmp = $GLOBALS['Bs_FileSystem']->getRealPath($this->_fld_baseDir)) {$err = "Base-Dir: [{$this->_fld_baseDir}] does not exsist!";break; }
$this->_fld_baseDir = $tmp;if (!$tmp = $GLOBALS['Bs_FileSystem']->getRealPath($this->_fld_targetDir)) {$dir =& new Bs_Dir;$status = $dir->mkpath($this->_fld_targetDir);if (!$tmp = $GLOBALS['Bs_FileSystem']->getRealPath($this->_fld_targetDir)) {$err = "Target-Dir: [{$this->_fld_targetDir}] does not exsist!";break; }
}
$this->_fld_targetDir = $tmp;$this->_fld_subDirs = trim($this->_fld_subDirs);if (empty($this->_fld_subDirs)) {$this->_subDirs = array();} else {$tmpDirs = explode("\n", $this->_fld_subDirs);foreach($tmpDirs as $subDir) {$subDir = trim($subDir);if (empty($subDir)) continue;if (!$tmp = $GLOBALS['Bs_FileSystem']->getRealPath($this->_fld_baseDir . $subDir)) {$err = "Sub-Dir: [{$this->_fld_baseDir}{$subDir}] does not exsist!";break 2; }
$this->_subDirs[] = substr($tmp,strLen($this->_fld_baseDir));}
}
$this->_fld_subDirsNotRecursive = trim($this->_fld_subDirsNotRecursive);if (empty($this->_fld_subDirsNotRecursive)) {$this->_subDirsNotRecursive = array();} else {$tmpDirs = explode("\n", $this->_fld_subDirsNotRecursive);foreach($tmpDirs as $subDir) {$subDir = trim($subDir);if (empty($subDir)) continue;if (!$tmp = $GLOBALS['Bs_FileSystem']->getRealPath($this->_fld_baseDir . $subDir)) {$err = "Sub-Dir: [{$this->_fld_baseDir}{$subDir}] does not exsist!";break 2; }
$this->_subDirsNotRecursive[] = substr($tmp,strLen($this->_fld_baseDir));}
}
$this->_mainFilterHash = $this->_extractFilterFromTextField($this->_fld_mainFilter);$this->_noChangeFilterHash = $this->_extractFilterFromTextField($this->_fld_noChangeFilter);$this->_unCommentFilterHash = $this->_extractFilterFromTextField($this->_fld_unCommentFilter);$this->_obfuseFilterHash = $this->_extractFilterFromTextField($this->_fld_obfuseFilter);} while(FALSE);if (!empty($err)) {Bs_Error::setError($err, 'WARNING', __LINE__,'', __FILE__);return FALSE;}
return TRUE;}
function _runFileFilter() {$allFiles = $this->_gatherAllFiles();$this->_filterIt($this->_mainFilterHash, $allFiles, $this->_mainResults, $dummy);$this->_filterIt($this->_noChangeFilterHash, $this->_mainResults, $this->_noChangeResults, $fileListRest);$this->_filterIt($this->_unCommentFilterHash, $fileListRest, $this->_unCommentResults, $fileListRest);$this->_filterIt($this->_obfuseFilterHash, $fileListRest, $this->_obfuseResults, $fileListRest);}
function _filterFormScreen() {$form = '-- NOTHING --';$err = FALSE;do {$form = $this->_buildForm($_POST);if (FALSE === $form) break; $this->_store();$loadConfigFile = FALSE;if (@$this->_todo[BS_TODO_DATAHASH] AND @$this->_todo[BS_TODO_DATAHASH]['packageName']) {$loadConfigFile = $this->_todo[BS_TODO_DATAHASH]['packageName'];}
if (FALSE === $tmp = $this->_setup($loadConfigFile)) {$err = TRUE;break; }
$this->_runFileFilter();$form = $this->_buildForm('');} while(FALSE);if ($err) {$form = join('<br>',Bs_Error::getErrors());}
$out=<<<EOD
      <html>
      <head>
      </head>
      <body bgcolor="#C0C0C0">
       {$form}
      </body>
      </html>
EOD;
echo $out;die();}
function _doIt($packageName) {$err = FALSE;do {$pack_noChange = $pack_unComment = $pack_obfuse = FALSE;$loadConfigFile = FALSE;if (@$this->_todo[BS_TODO_DATAHASH]) {$pack_noChange  = ('noChange'  == @$this->_todo[BS_TODO_DATAHASH]['pack_doWhat']);$pack_unComment = ('unComment' == @$this->_todo[BS_TODO_DATAHASH]['pack_doWhat']);$pack_obfuse    = ('obfuse'    == @$this->_todo[BS_TODO_DATAHASH]['pack_doWhat']);if ('doALL' == @$this->_todo[BS_TODO_DATAHASH]['pack_doWhat']) {$pack_noChange = $pack_unComment = $pack_obfuse = TRUE;}
}
if (FALSE === $tmp=$this->_setup($packageName)) {$err = TRUE;break; }
$this->_runFileFilter();$preViewFile = FALSE;if (@$this->_todo[BS_TODO_DATAHASH]['preview']) {$preViewFile = $this->_todo[BS_TODO_DATAHASH]['preview'];}
if ($this->_fld_baseDir == $this->_fld_targetDir) {Bs_Error::setError('Target- and Base- dir may not be the same!', 'WARNING', __LINE__,'',__FILE__);$err = TRUE;break; } else {$fileObj =& new Bs_File();$stripper = new Bs_Stripper();if ($pack_noChange) {foreach($this->_noChangeResults as $path) {$this->_Bs_Dir->mkpath(dirname($this->_fld_targetDir . $path));copy($this->_fld_baseDir . $path, $this->_fld_targetDir . $path);}
}
if ($pack_unComment OR $preViewFile) {foreach($this->_unCommentResults as $path) {if ($preViewFile AND ($preViewFile != $path)) continue; $sourceFile = $this->_fld_baseDir   . $path;$targetFile = $this->_fld_targetDir . $path;$this->_Bs_Dir->mkpath(dirname($targetFile));if (preg_match('/\.php$/', $path)) {$stripFlags = BS_STRIP_COMMENT_ALL;$header = $GLOBALS['phpStartTag'] . $GLOBALS['BS_PACKAGE_HEADERS_DEFAULT'] . $GLOBALS['phpEndTag'];} elseif (preg_match('/\.js$/', $path)) {$stripFlags = (BS_STRIP_COMMENT_SLASH | BS_STRIP_COMMENT_ASTERIX );$header = $GLOBALS['BS_PACKAGE_HEADERS_DEFAULT'] . "\n";} else {$stripFlags = (BS_STRIP_COMMENT_SLASH | BS_STRIP_COMMENT_ASTERIX | BS_STRIP_COMMENT_HASH);$header = '';}
if ($preViewFile) {if (!$fileData = $fileObj->readAll($sourceFile)) {XR_dump($tmp = "Failed to read file: " . $this->_fld_targetDir . $path, __LINE__,'', __FILE__);die();}
if (preg_match('/\.php$/', $path)) {echo $stripper->stripPhp($fileData, $stripFlags, $debug=TRUE);echo "<hr><pre>";echo htmlspecialchars($stripper->stripPhp($fileData, $stripFlags));} else {echo $stripper->strip($fileData, $stripFlags, $debug=TRUE);echo "<hr><pre>";echo htmlspecialchars($stripper->strip($fileData, $stripFlags));}
echo "</pre>";die();}
if (!$stripper->stripFile($sourceFile, $targetFile, $stripFlags, $header)) {XR_dump($tmp = Bs_Error::getErrors(), __LINE__,'', __FILE__);continue;}
}
}
if ($pack_obfuse OR $preViewFile) {foreach($this->_obfuseResults as $path) {if ($preViewFile AND ($preViewFile != $path)) continue; $sourceFile = $this->_fld_baseDir . $path;$targetFile = $this->_fld_targetDir . $path;$this->_Bs_Dir->mkpath(dirname($targetFile));if (preg_match('/\.php$/', $path)) {$stripFlags = BS_STRIP_COMMENT_ALL;$header = $GLOBALS['phpStartTag'] . $GLOBALS['BS_PACKAGE_HEADERS_DEFAULT'] . $GLOBALS['phpEndTag'];} elseif (preg_match('/\.js$/', $path)) {$stripFlags = (BS_STRIP_COMMENT_SLASH | BS_STRIP_COMMENT_ASTERIX );$header = $GLOBALS['BS_PACKAGE_HEADERS_DEFAULT'] . "\n";} else {$stripFlags = (BS_STRIP_COMMENT_SLASH | BS_STRIP_COMMENT_ASTERIX | BS_STRIP_COMMENT_HASH);$header = '';}
if ($preViewFile) {if (!$fileData = $fileObj->readAll($sourceFile)) {XR_dump($tmp = "Failed to read file: " . $this->_fld_targetDir . $path, __LINE__,'', __FILE__);die();}
if (preg_match('/\.php$/', $path)) {echo $stripper->stripPhp($fileData, $stripFlags, $debug=TRUE);echo "<hr><pre>";echo htmlspecialchars($stripper->stripPhp($fileData, $stripFlags));} elseif (preg_match('/\.js$/', $path)) {echo $stripper->stripJs($fileData, $stripFlags, $debug=TRUE);echo "<hr><pre>";echo htmlspecialchars($stripper->stripJs($fileData, $stripFlags));} else {echo $stripper->strip($fileData, $stripFlags, $debug=TRUE);echo "<hr><pre>";echo htmlspecialchars($stripper->strip($fileData, $stripFlags));}
echo "</pre>";die();}
if (!$stripper->stripFile($sourceFile, $targetFile, ($stripFlags | BS_STRIP_FORMAT), $header)) {XR_dump($tmp = Bs_Error::getErrors(), __LINE__,'', __FILE__);continue;}
}
}
if (('doALL' == @$this->_todo[BS_TODO_DATAHASH]['pack_doWhat'])) {$zipFullPath = $this->_fld_targetDir;if (substr($zipFullPath, -1) == '/') {$zipFullPath = substr($zipFullPath, 0, strlen($zipFullPath) -1);}
$params = array(
'fullPath'    => $this->_fld_targetDir, 
'returnType'  => 'subpath', 
);$dir =& new Bs_Dir();$fileList = $dir->getFileList($params);$cmd = "C:/usr/local/lib/util/zip23x/zip -r c:{$zipFullPath}.zip c:{$this->_fld_targetDir} 2>&1";dump($cmd);dump(shell_exec($cmd));require_once("Archive/Tar.php");@unlink($zipFullPath . '.tgz');$tar =& new Archive_Tar($zipFullPath . '.tgz', 'gz'); $status = $tar->createModify($this->_fld_targetDir, '', $this->_fld_targetDir);}
}
} while(FALSE);$noChangeOut = join('<br>',$this->_noChangeResults);$unCommentOut = '';foreach ($this->_unCommentResults as $path) {$this->_todo[BS_TODO_DATAHASH]['preview'] = $path;$unCommentOut .= '<a href="' . $_SERVER['PHP_SELF'] . "?" . bs_remakeTodoQueryStringFromToDo($this->_todo) .'" >preview</a> ' . $path . '<br />';}
$obfuseOut = '';foreach ($this->_obfuseResults as $path) {$this->_todo[BS_TODO_DATAHASH]['preview'] = $path;$obfuseOut .= '<a href="' . $_SERVER['PHP_SELF'] . "?" . bs_remakeTodoQueryStringFromToDo($this->_todo) .'" >preview</a> ' . $path . '<br />';}
unset($this->_todo[BS_TODO_DATAHASH]['preview']);$modifiedTodo = $this->_todo;$modifiedTodo[BS_TODO_DATAHASH]['pack_doWhat'] = 'doALL';$doAllLink = $_SERVER['PHP_SELF'] . "?" . bs_remakeTodoQueryStringFromToDo($modifiedTodo);$modifiedTodo[BS_TODO_DATAHASH]['pack_doWhat'] = 'noChange';$noChangeLink = $_SERVER['PHP_SELF'] . "?" . bs_remakeTodoQueryStringFromToDo($modifiedTodo);$modifiedTodo[BS_TODO_DATAHASH]['pack_doWhat'] = 'unComment';$unCommenLink = $_SERVER['PHP_SELF'] . "?" . bs_remakeTodoQueryStringFromToDo($modifiedTodo);$modifiedTodo[BS_TODO_DATAHASH]['pack_doWhat'] = 'obfuse';$obfuseLink   = $_SERVER['PHP_SELF'] . "?" . bs_remakeTodoQueryStringFromToDo($modifiedTodo);$out=<<<EOD
      <html>
      <head>
      </head>
      <body bgcolor="#C0C0C0" style="font: x-small Arial, Helvetica, sans-serif;">
       <h1>{$packageName}</h1>
       <a href="{$doAllLink}">start all</a><hr />
       <h2>No Change</h2> <a href="{$noChangeLink}">start</a><hr />
       {$noChangeOut}
       <h2>Uncomment</h2> <a href="{$unCommenLink}">start</a><hr />
       {$unCommentOut}
       <h2>Obfuse</h2> <a href="{$obfuseLink}">start</a><hr />
       {$obfuseOut}
      </body>
      </html>
EOD;
return $out;}
function _showFilesScreen() {$packageName = '';if (@$this->_todo[BS_TODO_DATAHASH]['packageName']) $packageName = $this->_todo[BS_TODO_DATAHASH]['packageName'];$packageName = explode(';', $packageName);if (sizeOf($packageName) > 1) {foreach ($packageName as $p) {echo 'doing package: ' . $p . ' ... ';$status = $this->_doIt($p);echo '<b>done</b><br>';}
} else {$status = $this->_doIt($packageName[0]);echo $status;}
die();}
function _metaNaviScreen() {$linkToFilter = $_SERVER['PHP_SELF'] . '?' . bs_makeTodoQueryString($exitScreen='', $exitActions=0, $nextScreen='Sub-Frame', $nextActions=0, $dataHash=array('menu'=>'PackageFilterSetup'));$packageFilter = "<a href='$linkToFilter' target='subframe'>Package Filter</a>";$linkToBuild = $_SERVER['PHP_SELF'] . '?' . bs_makeTodoQueryString($exitScreen='', $exitActions=0, $nextScreen='Sub-Frame', $nextActions=0, $dataHash=array('menu'=>'BuildPackage'));$packageBuilt  = "<a href='$linkToBuild' target='subframe'>Build Package</a>";$out=<<<EOD
      <html>
      <head>
      </head>
      <body bgcolor="#C0C0C0">
       <table><tr><td>$packageFilter</td> <td>$packageBuilt</td></tr></table>
      </body>
      </html>
EOD;
echo $out;die();}
function _packageNaviScreen($naviTargetScreen) {$packageEditLink      = $_SERVER['PHP_SELF'] . '?' . bs_makeTodoQueryString($exitScreen='', $exitActions=0, $nextScreen=$naviTargetScreen, $nextActions=0, $dataHash=array('packageName'=>'__MY_packName'));$emptyEditLink        = $_SERVER['PHP_SELF'] . '?' . bs_makeTodoQueryString($exitScreen='', $exitActions=0, $nextScreen=$naviTargetScreen, $nextActions=0, $dataHash=array('doWhat'=>'doALL', 'pack_doWhat'=>'doALL', 'packageName'=>''));$packageDeleteLink    = $_SERVER['PHP_SELF'] . '?' . bs_makeTodoQueryString($exitScreen='', $exitActions=0, $nextScreen='Package List Screen', $nextActions=0, $dataHash=array('doWhat'=>'delete', 'packageName'=>'__MY_packName'));if ($this->_todo[BS_TODO_DATAHASH]['menu'] == 'PackageFilterSetup') {$checkbox = "";} else {$checkbox = "<?php \$GLOBALS['iterator']++;?><!input type='hidden' value='__MY_packName' name='hid<?php echo \$GLOBALS['iterator'];?>'><input type='checkbox' name='chk<?php echo \$GLOBALS['iterator'];?>' value='__MY_packName'> ";}
$navStyle = array(
'head'     => '<table width="100%">',
'foot'     => '</table>',
'empty'    => 'no navigation data',
'level'      => array(
'1'         => array( 
'head'       => '',  
'foot'       => '',  
'link'       => array( 
'default'  => "<tr><td>{$checkbox}<a href='{$packageEditLink}'   target='" . PACKCOL_FRAME_MAIN . "'>__CAPTION__</a></td>"
. "<td>&nbsp;&nbsp;&nbsp;</td>"
. "<td><a href='{$packageDeleteLink}'><img src='/_bsImages/applications/filemanager/delete.gif' border='0'></a></td></tr>",
)
)
),
);$dirToStore = $this->_Bs_Dir->getPathStem(__FILE__) . PACKCOL_SUBDIR;$this->_Bs_Dir;$params = array(
'fullPath'    => $dirToStore, 
'regFunction' => 'preg_match', 
'regEx'       => '/'. preg_quote(PACKCOL_EXTENTION)  .'$/i',
'fileDirLink' => array('dir'=>FALSE), 
'sort'        => TRUE,
'returnType'  => 'fulldir/file', 
);$list = $this->_Bs_Dir->getFileList($params);$navData = array();$iterator = 1;foreach ($list as $dir) {if (@$this->_todo[BS_TODO_DATAHASH] AND ('delete' == @$this->_todo[BS_TODO_DATAHASH]['doWhat'])) {if ($dir['file'] === $this->_todo[BS_TODO_DATAHASH]['packageName']) {@unlink($dir['dir'] . $dir['file']);continue;}
}
$navData[] = array('key'=>$dir['file'], 'caption'=>$dir['file'], '__MY_packName'=>$dir['file']);$iterator++;}
$startLinkKey = 'test';$n =& new Bs_HtmlNavigation();$n->setStyle($navStyle);$n->setData($navData);$nav = $n->toHtml();$script = '</scr' . 'ipt>';$target = PACKCOL_FRAME_MAIN;$out=<<<EOD
      <html>
      <head>
      </head>
      <body bgcolor="#C0C0C0">
       {$nav}
             
             <br><br><input type="button" name="btnGo" value="start all" onclick="startAll();">
             <script>
             var emptyEditLink = '{$emptyEditLink}';
             var target        = '{$target}';
             function startAll() {
               var elm = document.getElementsByTagName('input');
                 var doArr = new Array();
                 for (var i=0; i<elm.length; i++) {
                   if (elm[i].checked) {
                       //alert(elm[i].value);
                         doArr[doArr.length] = elm[i].value;
                     }
                 }
                 parent.frames[target].location.href = emptyEditLink + doArr.join(';');
             }
             {$script}
      </body>
      </html>
EOD;
echo $out;die();}
function _buildForm($postData='') {$fia =& new Bs_FormItAble();$err = FALSE;do {if (!$form = &$fia->buildForm($this)) {$err = "-- See previous error.";break; }
if (@$postData['bs_form']['step'] == '2') {$formParts = $form->doItYourself($postData);if (is_array($formParts)) break; } else {$formParts = $form->doItYourself($postData);break; }
$formData = $form->getValuesArray(TRUE, 'valueInternal');foreach($formData as $var => $value) {$this->$var = $value;}
} while(FALSE);if ($err) {Bs_Error::setError($err, 'ERROR', __LINE__, $_func_, __FILE__);return FALSE;}
if (TRUE === $formParts) {return TRUE;} else {$head = '';$head .= $form->includeOnceToHtml($formParts['include']);$head .= $formParts['head'];$head .= $form->onLoadCodeToHtml($formParts['onLoad']);$errors = empty($formParts['errors']) ? '' : $formParts['errors'];$todoInfo = bs_makeHiddenToDoFields($this->_todo[BS_TODO_EXIT_SCREEN], '', $this->_todo[BS_TODO_NEXT_SCREEN], '', $this->_todo[BS_TODO_DATAHASH]);$formParts['form'] = str_replace('<bs_before_formclose_tag/>', $todoInfo, $formParts['form']);$out=<<<EOD
      <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
      
      <html>
      <head>
        {$head}
      </head>
      
      <body>
      {$errors}
      {$formParts['form']}
      </body>
      </html>
EOD;
}
return $out;}
function _extractFilterFromTextField($textField) {$textField = trim($textField);if (empty($textField)) {$filterHash = array();} else {$tmpFilter = explode("\n", $textField);foreach($tmpFilter as $filter) {$filter = trim($filter);if (empty($filter)) continue;$do = '+';if ('-' === $filter[0]) {$do = '-';$filter = trim(substr($filter, 1));} elseif ('+' === $filter[0]) {$do = '+';$filter = trim(substr($filter, 1));} 
$filterHash[] = array('do'=>$do, 'filter'=>$filter);}
}
return $filterHash;}
function makeTree() {$params = array(
'fullPath'    => $GLOBALS['APP']['path']['core'], 
'regFunction' => 'preg_match', 
'regEx'       => '/.*\.php$/', 
'fileDirLink' => array('dir'=>FALSE), 
'sort'        => TRUE,
'returnType'  => 'nested2', 
);$dir =& new Bs_Dir();$fileList = $dir->getFileList($params);$root = array('ROOT'=>$fileList);$tree = $this->_recursiveBuidTreeStruct($root);$out  = $GLOBALS['Bs_HtmlUtil']->arrayToJsArray($tree, 'myArray');return $out;}
function _recursiveBuidTreeStruct($fileList) {$ret = array();$i = -1;foreach($fileList as $key => $val) {if (is_array($val)) {$subDirs = $this->_recursiveBuidTreeStruct($val);if (!empty($subDirs)) {$i++;$ret[$i]['caption'] = $key;$ret[$i]['children'] = $subDirs;}
} else {if ('.#' === substr($val,0,2)) continue;$i++;$ret[$i]['caption'] = $val;}
}
return $ret;}
function bs_fia_getHints($fiaAgent) {$fia_hints = array (
'props' => array(
'internalName'     => 'smEvent', 
'name'             => 'mainForm',
'buttons'          => 'default',
'mode'             => 'add', 
'language'         => 'en',
'useAccessKeys'    => TRUE,
'useTemplate'      => FALSE,
'jumpToFirstError' => TRUE,
),
'groups' => array(
'grp_initData'   => array('caption' =>'Init Data',  'mayToggle' => TRUE,),
'grp_mainFilter' => array('caption' =>'Main File Filter',  'mayToggle' => TRUE,),
'grp_devideFilter' => array('caption' =>'Devide Files',  'mayToggle' => TRUE,),
),
'fields' => array(
'_fld_packageName' => array(
'caption'         => 'Package Name',
'name'            => '_fld_packageName',
'group'           => 'grp_initData', 
'fieldType'       => 'Bs_FormFieldText', 
'must'            => TRUE, 
'editability'     => 'always', 
'valueDefault'    => $this->_fld_packageName, 
'bsDataType'      => 'text', 
'bsDataInfo'      => 1,
'size'            => 60,
'maxLength'       => 120,
),
'_fld_targetDir' => array(
'caption'         => 'Target Dir',
'name'            => '_fld_targetDir',
'group'           => 'grp_initData', 
'fieldType'       => 'Bs_FormFieldText', 
'must'            => TRUE, 
'editability'     => 'always', 
'valueDefault'    => $this->_fld_targetDir, 
'bsDataType'      => 'text', 
'bsDataInfo'      => 1,
'size'            => 60,
'maxLength'       => 120,
),
'_fld_baseDir' => array(
'caption'         => 'Base Dir',
'name'            => '_fld_baseDir',
'group'           => 'grp_initData', 
'fieldType'       => 'Bs_FormFieldText', 
'must'            => TRUE, 
'editability'     => 'always', 
'valueDefault'    => $this->_fld_baseDir, 
'bsDataType'      => 'text', 
'bsDataInfo'      => 1,
'size'            => 60,
'maxLength'       => 120,
),
'_fld_subDirs' => array(
'caption'         => 'Additional Subdirs',
'name'            => '_fld_subDirs',
'group'           => 'grp_initData', 
'fieldType'       => 'Bs_FormFieldTextarea', 
'must'            => FALSE, 
'editability'     => 'always', 
'valueDefault'    => $this->_fld_subDirs, 
'bsDataType'      => 'text', 
'bsDataInfo'      => 1,
'rows'            => 6,
'cols'            => 50,
'maxLength'       => 2000,
),
'_fld_subDirsNotRecursive' => array(
'caption'         => 'Additional Subdirs Not Recursive',
'name'            => '_fld_subDirsNotRecursive',
'group'           => 'grp_initData', 
'fieldType'       => 'Bs_FormFieldTextarea', 
'must'            => FALSE, 
'editability'     => 'always', 
'valueDefault'    => $this->_fld_subDirsNotRecursive, 
'bsDataType'      => 'text', 
'bsDataInfo'      => 1,
'rows'            => 6,
'cols'            => 50,
'maxLength'       => 2000,
),
'_fld_mainFilter' => array(
'caption'         => 'Main Filter',
'name'            => '_fld_mainFilter',
'group'           => 'grp_mainFilter', 
'fieldType'       => 'Bs_FormFieldTextarea', 
'must'            => FALSE, 
'editability'     => 'always', 
'valueDefault'    => $this->_fld_mainFilter, 
'bsDataType'      => 'text', 
'bsDataInfo'      => 1,
'rows'            => 6,
'cols'            => 50,
'maxLength'       => 2000,
'elementLayout'   => "<tr><td nowrap valign='top' align='left' width='20%'>__CAPTION_FOR_FORM_OUTPUT__</td><td>__ELEMENT__ Add regex</td></tr>",
),
'_fld_mainResultArr' => array(
'caption'         => 'Main Filter Result: ' . (empty($this->_mainResults) ? '0' : sizeOf($this->_mainResults)) ,
'name'            => '_fld_mainResultArr',
'group'           => 'grp_mainFilter', 
'fieldType'       => 'Bs_FormFieldSelect', 
'must'            => FALSE, 
'editability'     => 'always', 
'size'            => 10,
'optionsHard'     => $this->_mainResults,
),
'_fld_noChangeFilter' => array(
'caption'         => 'No Change Filter',
'name'            => '_fld_noChangeFilter',
'group'           => 'grp_devideFilter', 
'fieldType'       => 'Bs_FormFieldTextarea', 
'must'            => FALSE, 
'caption'         => 'No Change Filter',
'editability'     => 'always', 
'valueDefault'    => $this->_fld_noChangeFilter, 
'bsDataType'      => 'text', 
'bsDataInfo'      => 1,
'rows'            => 6,
'cols'            => 50,
'maxLength'       => 2000,
),
'_fld_noChangeResultArr' => array(
'caption'         => 'No Change Result: ' . (empty($this->_noChangeResults) ? '0' : sizeOf($this->_noChangeResults)) ,
'name'            => '_fld_noChangeResultArr',
'group'           => 'grp_devideFilter', 
'fieldType'       => 'Bs_FormFieldSelect', 
'must'            => FALSE, 
'editability'     => 'always', 
'size'            => 10,
'optionsHard'     => $this->_noChangeResults,
),
'_fld_unCommentFilter' => array(
'caption'         => 'Uncomment Filter',
'name'            => '_fld_unCommentFilter',
'group'           => 'grp_devideFilter', 
'fieldType'       => 'Bs_FormFieldTextarea', 
'must'            => FALSE, 
'editability'     => 'always', 
'valueDefault'    => $this->_fld_unCommentFilter, 
'bsDataType'      => 'text', 
'bsDataInfo'      => 1,
'rows'            => 6,
'cols'            => 50,
'maxLength'       => 2000,
),
'_fld_unCommentResultArr' => array(
'caption'         => 'Uncomment Result: ' . (empty($this->_unCommentResults) ? '0' : sizeOf($this->_unCommentResults)) ,
'name'            => '_fld_unCommentResultArr',
'group'           => 'grp_devideFilter', 
'fieldType'       => 'Bs_FormFieldSelect', 
'must'            => FALSE, 
'editability'     => 'always', 
'size'            => 10,
'optionsHard'     => $this->_unCommentResults,
),
'_fld_obfuseFilter' => array(
'caption'         => 'Obfusecate Filter',
'name'            => '_fld_obfuseFilter',
'group'           => 'grp_devideFilter', 
'fieldType'       => 'Bs_FormFieldTextarea', 
'must'            => FALSE, 
'editability'     => 'always', 
'valueDefault'    => $this->_fld_obfuseFilter, 
'bsDataType'      => 'text', 
'bsDataInfo'      => 1,
'rows'            => 6,
'cols'            => 50,
'maxLength'       => 2000,
),
'_fld_obfuseResultArr' => array(
'caption'         => 'Obfusecate Result: ' . (empty($this->_obfuseResults) ? '0' : sizeOf($this->_obfuseResults)) ,
'name'            => '_fld_obfuseResultArr',
'group'           => 'grp_devideFilter', 
'fieldType'       => 'Bs_FormFieldSelect', 
'must'            => FALSE, 
'editability'     => 'always', 
'size'            => 10,
'optionsHard'     => $this->_obfuseResults,
),
),
);return $fia_hints;}
function _gatherAllFiles() {$ret = array();$allFiles = array();$err = '';$params = array(
'fullPath'    => '/', 
'regFunction' => '', 'regEx'       => '', 'fileDirLink' => array('dir'=>FALSE), 
'sort'        => FALSE,
'returnType'  => 'fullpath', 
);foreach ($this->_subDirs as $subDir) {$params['fullPath'] = $this->_fld_baseDir . $subDir;$list = $this->_Bs_Dir->getFileList($params);$allFiles = array_merge($allFiles, $list);}
$params['depth'] = 0;foreach ($this->_subDirsNotRecursive as $subDir) {$params['fullPath'] = $this->_fld_baseDir . $subDir;$list = $this->_Bs_Dir->getFileList($params);$allFiles = array_merge($allFiles, $list);}
$baseDirLeng = strlen($this->_fld_baseDir);foreach ($allFiles as $path) {$ret[] = substr($path, $baseDirLeng);}
return $ret;}
function _mainFrameLayout($topScreen, $bottomScreen) {$frameName_1 = PACKCOL_FRAME_META;$frameName_2 = 'subframe';$this->_todo[BS_TODO_NEXT_SCREEN] = $topScreen;$selfMeta = $_SERVER['PHP_SELF'] . "?" . bs_remakeTodoQueryStringFromToDo($this->_todo);$this->_todo[BS_TODO_NEXT_SCREEN] = $bottomScreen;$selfSub = $_SERVER['PHP_SELF'] . "?" . bs_remakeTodoQueryStringFromToDo($this->_todo);$out =<<<EOD
      <html>
      <frameset  rows="50px,*">
        <frame name="{$frameName_1}" src="{$selfMeta}" marginwidth="10" marginheight="10" scrolling="auto" frameborder="1">
        <frame name="{$frameName_2}"  src="{$selfSub}"  marginwidth="10" marginheight="10" scrolling="auto" frameborder="0">
      </frameset>
      </html>
EOD;
echo $out;die();}
function _subFrameLayout($leftScreen, $rightScreen) {$frameName_1 = PACKCOL_FRAME_NAVI;$frameName_2 = PACKCOL_FRAME_MAIN;$this->_todo[BS_TODO_NEXT_SCREEN] = $leftScreen;$selfNavi = $_SERVER['PHP_SELF'] . "?" . bs_remakeTodoQueryStringFromToDo($this->_todo);$this->_todo[BS_TODO_NEXT_SCREEN] = $rightScreen;$selfMain = $_SERVER['PHP_SELF'] . "?" . bs_remakeTodoQueryStringFromToDo($this->_todo);$out =<<<EOD
      <html>
      <frameset  cols="400px,*">
        <frame name="{$frameName_1}" src="{$selfNavi}" marginwidth="10" marginheight="10" scrolling="auto" frameborder="1">
        <frame name="{$frameName_2}" src="{$selfMain}" marginwidth="10" marginheight="10" scrolling="auto" frameborder="0">
      </frameset>
      </html>
EOD;
echo $out;die();}
function _filterIt($filterHash, $fullFileList, &$result, &$inverseResult) {$resultHash = $fullFileHash = array_flip($fullFileList);$firstFilter = TRUE;foreach ($filterHash as $filter) {if ('+' === $filter['do']) {if ($firstFilter) {$resultHash = array();} 
foreach ($fullFileHash as $file => $foo) {if (@preg_match($filter['filter'], $file)) {$resultHash[$file] = TRUE;}
}
} elseif ('-' === $filter['do']) {$tmp = array();foreach ($resultHash as $file => $foo) {if (@preg_match($filter['filter'], $file)) {} else {$tmp[$file] = TRUE;}
}
$resultHash = $tmp;}
$firstFilter = FALSE;}
$inverseResult = array();if (empty($resultHash)) {$result = array();$inverseResult = $fullFileList;} else {foreach ($fullFileHash as $file => $foo) {if (!isSet($resultHash[$file])) $inverseResult[] = $file;}
$result = array_keys($resultHash);}
return TRUE;}
function _store() {$storeHash = array();$storeHash['_fld_packageName']             = $this->_fld_packageName;$storeHash['_fld_targetDir']               = $this->_fld_targetDir;$storeHash['_fld_baseDir']                 = $this->_fld_baseDir;$storeHash['_fld_subDirs']                 = $this->_fld_subDirs;$storeHash['_fld_subDirsNotRecursive']     = $this->_fld_subDirsNotRecursive;$storeHash['_fld_mainFilter']              = $this->_fld_mainFilter;$storeHash['_fld_noChangeFilter']          = $this->_fld_noChangeFilter;$storeHash['_fld_unCommentFilter']         = $this->_fld_unCommentFilter;$storeHash['_fld_obfuseFilter']            = $this->_fld_obfuseFilter;$dirToStore = $this->_Bs_Dir->getPathStem(__FILE__) . PACKCOL_SUBDIR;$fullPath = $dirToStore . $this->_fld_packageName . PACKCOL_EXTENTION;return bs_storeVar($storeHash, $fullPath);}
function _load($fileName) {$ret = TRUE;$dirToStore = $this->_Bs_Dir->getPathStem(__FILE__) . PACKCOL_SUBDIR;$fullPath = $dirToStore . $fileName;$storeHash = NULL;if (bs_loadVar($storeHash, $fullPath)) {$this->_fld_packageName         = $storeHash['_fld_packageName'];$this->_fld_targetDir           = $storeHash['_fld_targetDir'];$this->_fld_baseDir             = $storeHash['_fld_baseDir'];$this->_fld_subDirs             = $storeHash['_fld_subDirs'];$this->_fld_subDirsNotRecursive = @$storeHash['_fld_subDirsNotRecursive'];$this->_fld_mainFilter          = $storeHash['_fld_mainFilter'];$this->_fld_noChangeFilter      = $storeHash['_fld_noChangeFilter'];$this->_fld_unCommentFilter     = $storeHash['_fld_unCommentFilter'];$this->_fld_obfuseFilter        = $storeHash['_fld_obfuseFilter'];} else {XR_dump($storeHash, __LINE__,'', __FILE__);$ret = FALSE;}
return $ret;}
}
$pColl =& new PackageCollector();$out   = $pColl->go();echo $out;?>
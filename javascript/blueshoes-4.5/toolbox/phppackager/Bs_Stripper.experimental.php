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
require_once($_SERVER['DOCUMENT_ROOT']  . '../global.conf.php');require_once($APP['path']['core'] . 'util/Bs_Stripper.class.php');require_once($APP['path']['core'] . 'util/Bs_IniHandler.class.php');require_once($APP['path']['core'] . 'file/Bs_Dir.class.php');require_once($APP['path']['core'] . 'file/Bs_File.class.php');set_time_limit(180);define('BS_PACKAGE_DEFAULT', 'default');define('BS_PACKAGE_DEVELOPER', 'developer');$driveLetter = 'c:'; $config = array();$config[BS_PACKAGE_DEFAULT] =<<<EOD
    #
    #  MAX strip.
    #
    
    #########################################################################
    #  0)  Set Debug on or off
    #      If debug is on, the result will be a collorized html output of 
    #      what would have been stripped.
    #########################################################################
    #debug = true
    
    #########################################################################
    #  1)  Set source- and tartget-dir 
    #      - Set the 'source dir'. The starting dir, incl. all subdirs.
    #      - Set the 'target dir'. Where the handled files are placed.
    #########################################################################
    sourceDir = '{$driveLetter}/usr/local/lib/php/blueshoes-4.2/'
    targetDir = '{$driveLetter}/tmp/'
    
    #########################################################################
    #  2)  Select the files that are to be in the package
    #      To control the files that are (or aren't) to be in the target-dir 
    #      with in- and ex-cludeRegex. 
    #      Default is include all and exclude nothing.
    #########################################################################
    includeRegex  = '~.*~'
    #excludeRegex  = '~CVS/|biff/|JpGraph/|\.#~'
    excludeRegex  = '~CVS/|\.#~'
    
    #########################################################################
    #  3)  Strip- or nostrip
    #      To control what files from step 2) are (or aren't) to be stripped 
    #      AND which rule to use first: strip or nostrip (= default)
    #      Default is to strip *.js and *.php files only.
    #########################################################################
    order = 'nostrip'
    noStripRegEx  = '~/examples|/ecg~i'
    stripRegEx  = '~\.php|\.js~'
EOD;
$config[BS_PACKAGE_DEVELOPER] =<<<EOD
    #
    #  DEVELOPER strip.
    #
    
    #########################################################################
    #  0)  Set Debug on or off
    #      If debug is on, the result will be a collorized html output off 
    #      what would have been stripped.
    #########################################################################
    #debug = true
    
    #########################################################################
    #  1)  Set source- and tartget-dir 
    #      - Set the 'source dir'. The starting dir, incl. all subdirs.
    #      - Set the 'target dir'. Where the handled files are placed.
    #########################################################################
    sourceDir = '{$driveLetter}/usr/local/lib/php/blueshoes-4.2/'
    targetDir = '{$driveLetter}/tmp/'
    
    #########################################################################
    #  2)  Select the files that are to be in the package
    #      To control the files that are (or aren't) to be in the target-dir 
    #      with in- and ex-cludeRegex. 
    #      Default is include all and exclude nothing.
    #########################################################################
    includeRegex  = '~.*~'
    #excludeRegex  = '~CVS/|biff/|JpGraph/|\.#~'
    excludeRegex  = '~CVS/|\.#~'
    
    #########################################################################
    #  3)  Strip- or nostrip
    #      To control what files from step 2) are (or aren't) to be stripped 
    #      AND which rule to use first: strip or nostrip (= default)
    #      Default is to strip *.js and *.php files only.
    #########################################################################
    order = 'nostrip'
    noStripRegEx  = '~/examples|/ecg~i'
    #stripRegEx  = '~(Form|Persister|IndexServer|Onom|CMS|Debe|smartshop|FileManager|Spread|Wysiwyg).*(\.php|\.js)~Ui'
    stripRegEx  = '~(/form|/storage|/filemanager|/smartshop|/spreadsheet|/wysiwyg|/onom|/indexserver|/cms|/debe).*(\.php|\.js)~Ui'
EOD;
$currentPackage = BS_PACKAGE_DEFAULT; $iniHandler =& new Bs_IniHandler();$iniHandler->loadString($config[$currentPackage]);$configData = $iniHandler->get();$configData = $configData[''];$defautConfig = array(
'debug' => 'false',
'includeRegex'  => '~.*~',
'excludeRegex'  => '',
'order'         => 'nostrip',
'noStripRegEx'  => '', 
'stripRegEx'    => '~\.php|\.js~',
);foreach ($defautConfig as $key => $val) {if (empty($configData[$key])) $configData[$key] = $val;}
XR_dump($configData, __LINE__, '', __FILE__);$_debug = isTrue($configData['debug']);if (empty($configData['sourceDir'])) {echo "no 'sourceDir' given"; die();}
if (empty($configData['targetDir'])) {echo "no 'tartgetDir' given"; die();}
$dirObj =& new Bs_Dir();$fileObj =& new Bs_File();$param = array(
'fullPath' => $configData['sourceDir'],
'regFunction' => 'preg_match',
'regEx' => $configData['includeRegex'],
'returnType' => 'subdir/file',
);if (isEx( $tmpList=$dirObj->getFileList($param) )) {$tmpList->stackDump('alert');die();}
if (FALSE === ($pathHash=Bs_FileSystem::getRealPathSplit($configData['sourceDir']) )) {echo "Failed to open dir [{$configData['sourceDir']}]";die();}
$fatherDirName = empty($pathHash['tailDir']) ? '' : $pathHash['tailDir'] . '/';$fileList = array();$tmpDirToCreate = array();foreach($tmpList as $file) {if (empty($file['file'])) continue;if (empty($configData['excludeRegex'])) {$fileList[] = $file;} else {if (preg_match($configData['excludeRegex'], $file['dir'] . $file['file'])) continue;$fileList[] = $file;}
if (!isSet($_tmpDirToCreate[$file['dir']])) {$targetFulldir = $configData['targetDir'] . $fatherDirName . $file['dir'];$dirObj->mkpath($targetFulldir);$tmpDirToCreate[$file['dir']] = TRUE;}
}
$noStripFileList = array();$stripCommentFileList = array();$stripAllFileList = array();foreach($fileList as $file) {$sourceSubpathName = $file['dir'] . $file['file'];$targetSubpathName = $fatherDirName . $file['dir'] . $file['file'];if ($configData['order'] == 'strip') {$match_1 = $configData['stripRegEx'];$list_1  =& $stripAllFileList;$match_2 = $configData['noStripRegEx'];$list_2  =& $noStripFileList;} else {$match_2 = $configData['stripRegEx'];$list_2  =& $stripAllFileList;$match_1 = $configData['noStripRegEx'];$list_1  =& $noStripFileList;}
if (!empty($match_1)) {if (preg_match($match_1, $sourceSubpathName)) {$list_1[$sourceSubpathName] = $targetSubpathName;continue;}
}
if (!empty($match_2)) {if (preg_match($match_2, $sourceSubpathName)) {$list_2[$sourceSubpathName] = $targetSubpathName;continue;}
}
$noStripFileList[$sourceSubpathName] = $targetSubpathName;}
$headers = array();$headers[BS_PACKAGE_DEFAULT] =<<<EOD
/********************************************************************************************
* BlueShoes Framework; This file is part of the php application framework.
* NOTE: This code is stripped (obfuscated). To get the clean documented code goto 
*       www.blueshoes.org and register for the free open source *DEVELOPER* version or 
*       buy the commercial version.
* 
* @copyright see www.blueshoes.org
* @author    Samuel Blume <sam at blueshoes dot org>
* @author    Andrej Arn <andrej at blueshoes dot org>
*/
EOD;
$headers[BS_PACKAGE_DEVELOPER] =<<<EOD
/********************************************************************************************
* BlueShoes Framework; This file is part of the php application framework.
* NOTE: This code is stripped (obfuscated). This is one of the few
*       packages/classes that is only available to *PAYING* customers.
*       To get it go to www.blueshoes.org and buy a commercial version.
* 
* @copyright see www.blueshoes.org
* @author    Samuel Blume <sam at blueshoes dot org>
* @author    Andrej Arn <andrej at blueshoes dot org>
*/
EOD;
$stdHeader = $headers[$currentPackage];echo "<hr>";$stripper = new Bs_Stripper();$counter = 1;foreach($stripAllFileList as $source => $target) {echo $counter++ . ".) Stripping All: $source <br>\n";if ($fileData = $fileObj->readAll($configData['sourceDir'] . $source)) {if (preg_match('/\.php$/', $target)) {$commentStripFlag =(BS_STRIP_COMMENT_SLASH | BS_STRIP_COMMENT_ASTERIX | BS_STRIP_COMMENT_HASH);$qustionMark = '?';$header = "<{$qustionMark}php\n" . $stdHeader . "{$qustionMark}>" ;} elseif (preg_match('/\.js$/', $target)) {$commentStripFlag = (BS_STRIP_COMMENT_SLASH | BS_STRIP_COMMENT_ASTERIX );$header = $stdHeader;} else {$commentStripFlag =(BS_STRIP_COMMENT_SLASH | BS_STRIP_COMMENT_ASTERIX | BS_STRIP_COMMENT_HASH);$header = '';}
$fileData = $stripper->strip($fileData, (BS_STRIP_FORMAT | $commentStripFlag), $header, $_debug);if ($_debug ) {echo $fileData . '<hr>';} else {if (!$fileObj->onewayWrite($fileData, $configData['targetDir'] . $target)) {echo "<span style='color:red'>ERROR: failed to write to target file: ".$configData['targetDir'] . $target ."</span> <br>\n";}
}
} else {echo "<span style='color:red'>ERROR: failed to read source file: ".$configData['sourceDir']  . $source ."</span> <br>\n";}
}
echo "<hr>";$counter = 1;foreach($noStripFileList as $source => $target) {echo $counter++ . ".) Coping (no strip): $source <br>\n";if ($fileData = $fileObj->readAll($configData['sourceDir'] . $source)) {if (!$fileObj->onewayWrite($fileData, $configData['targetDir'] . $target)) {echo "<span style='color:red'>ERROR: failed to write to target file: ".$configData['targetDir'] . $target ."</span> <br>\n";}
} else {echo "<span style='color:red'>ERROR: failed to read source file: ".$configData['sourceDir']  . $source ."</span> <br>\n";}
}
echo "<hr><strong>Finished Stipping</strong><hr>"
?>
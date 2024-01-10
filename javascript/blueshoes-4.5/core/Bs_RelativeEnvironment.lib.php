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
function setupRelativeEnvironment($useThis_bsSubDir=NULL, $useThisUri=NULL) {$debug = FALSE;global $HTTP_SERVER_VARS;$localWebRoot_bs = empty($useThis_bsSubDir) ?  getAbsolutePath() : $useThis_bsSubDir;$splitPath = explode('/', $localWebRoot_bs);array_pop($splitPath);array_pop($splitPath);$localWebRoot = implode('/', $splitPath) . '/';    $onlineOfflineDir = array_pop($splitPath);$isOnline = (strcasecmp('online', $onlineOfflineDir) === 0);$localSite = implode('/', $splitPath) . '/';       $localWebRoot_prot = $localSite . 'protected/' . $onlineOfflineDir . '/';   $uri = empty($useThisUri) ?  $HTTP_SERVER_VARS['REQUEST_URI'] : $useThisUri;$qmPos = strpos($uri, '?');if ($qmPos !== FALSE) {$uri = substr($uri, 0, $qmPos);  }
$slPos = strpos($uri, '/', 1);if ($slPos) {$uriLang = substr($uri, 1, $slPos-1);} else {$uriLang = substr($uri, 1);}
if (preg_match('/^([a-z]{2})(?:-|$)(.*)/', $uriLang, $match)) {$uriLangBase = $match[1];$uriLandExt  = $match[2];$noLangUri = substr($uri, strlen($uriLang)+1);if (strlen($uriLandExt)==0) $uriLang = $uriLangBase;} else {$uriLangBase = '';$uriLandExt  = '';$noLangUri = $uri;$uriLang = '';}
$lastSl = strrpos($noLangUri, '/');if ($lastSl !== FALSE) {$nodeUni     = substr($noLangUri, 0, $lastSl+1);$requestFile = substr($noLangUri, $lastSl+1);} else {$nodeUni     = '/' . $noLangUri;$requestFile = $noLangUri;}
$append_nodeUni = substr($nodeUni, 1); $localRequestDir      = $localWebRoot      . $append_nodeUni;  $localRequestDir_bs   = $localWebRoot_bs   . $append_nodeUni;  $localRequestDir_prot = $localWebRoot_prot . $append_nodeUni;  do { $isFile = FALSE;$isFileProtected = FALSE;if ($requestFile == "") break; $lookForFile = $localRequestDir_bs . $requestFile;if (file_exists($lookForFile)) {$isFile = TRUE;break; }
$lookForFile = $localRequestDir_prot . $requestFile;if (file_exists($lookForFile)) {$isFile = TRUE;$isFileProtected = TRUE;break; }
} while (FALSE); if ($isFile) {if (!is_file($lookForFile)) {$isFile = FALSE;$tmpSubDir = $requestFile . '/';$nodeUni .= $tmpSubDir;$localRequestDir .= $tmpSubDir;$localRequestDir_bs .= $tmpSubDir;$localRequestDir_prot .= $tmpSubDir;$lookForFile = "";}
} else {$lookForFile = "";}
$GLOBALS['bsEnv'] = array(
'localSite'             => $localSite,            'localWebRoot'          => $localWebRoot,         'localWebRoot_bs'       => $localWebRoot_bs,      'localWebRoot_prot'     => $localWebRoot_prot,    'localRequestDir'       => $localRequestDir,      'localRequestDir_bs'    => $localRequestDir_bs,   'localRequestDir_prot'  => $localRequestDir_prot, 'nodeUni'               => $nodeUni,              'requestFile'           => $requestFile,          'requestFilePath'       => $lookForFile,          'requestIsFile'         => $isFile,               'isFileProtected'       => $isFileProtected,      'uriLang'               => $uriLang,              'uriLangBase'           => $uriLangBase,          'useLang'               => '',                    'isOnline'              => $isOnline              );if ($debug) {$out = '<table border=1>';foreach ($GLOBALS['bsEnv'] as $key => $val){if (is_bool($val)) $val = $val ? "TRUE" : "FALSE";$out .= "<tr><td>{$key} </td><td>$val</td></tr>";}
$out .= '</table><br>';BB_echo($out, __LINE__, $_func_='', __FILE__);exit;}
}
?>
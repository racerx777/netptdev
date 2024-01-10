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
define('BS_FILECONVERTERHTML_VERSION',      '4.5.$Revision: 1.3 $');require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'html/Bs_HtmlInfo.class.php');class Bs_Is_ConverterHtml extends Bs_Object {var $Bs_HtmlInfo;var $indexWeights = array(
'title'       => 100, 
'description' => 60, 
'keywords'    => 20, 
'links'       => 100, 
'h1'          => 80, 
'h2'          => 70, 
'h3'          => 60, 
'h4'          => 50, 
'h5'          => 40, 
'h6'          => 30, 
'h7'          => 20, 
'h8'          => 10, 
'b'           => 10, 'i'           => 10, 
'u'           => 10, 
'body'        => 5, 
);function Bs_Is_ConverterHtml() {parent::Bs_Object(); $this->Bs_HtmlInfo =& new Bs_HtmlInfo();}
function streamToArray($fileFullPath) {if (function_exists('file_get_contents')) {$data = file_get_contents($fileFullPath);} else {$data = join('', file($fileFullPath));}
return $this->stringToArray($data);}
function stringToArray($string) {$hi = &$this->Bs_HtmlInfo;$hi->initByString($string);$ret = array();$ret['title']       = $hi->fetchTitle();$ret['description'] = $hi->fetchDescription();$ret['keywords']    = $hi->fetchKeywords();$ret['links']       = ''; $ret['h1']          = join(' ', $hi->fetchStringsByTagNameStupid('h1'));$ret['h2']          = join(' ', $hi->fetchStringsByTagNameStupid('h2'));$ret['h3']          = join(' ', $hi->fetchStringsByTagNameStupid('h3'));$ret['h4']          = join(' ', $hi->fetchStringsByTagNameStupid('h4'));$ret['h5']          = join(' ', $hi->fetchStringsByTagNameStupid('h5'));$ret['h6']          = join(' ', $hi->fetchStringsByTagNameStupid('h6'));$ret['h7']          = join(' ', $hi->fetchStringsByTagNameStupid('h7'));$ret['h8']          = join(' ', $hi->fetchStringsByTagNameStupid('h8'));$ret['b']           = join(' ', $hi->fetchStringsByTagNamesStupid(array('b', 'strong')));$ret['i']           = join(' ', $hi->fetchStringsByTagNameStupid('i'));$ret['u']           = join(' ', $hi->fetchStringsByTagNameStupid('u'));$ret['body']        = $hi->fetchBody();return array('values'=>$ret, 'weights'=>$this->indexWeights);}
}
?>
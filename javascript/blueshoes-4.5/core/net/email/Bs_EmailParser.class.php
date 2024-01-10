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
define('BS_EMAILPARSER_VERSION',      '4.5.$Revision: 1.2 $');class Bs_EmailParser extends Bs_Object {function Bs_EmailParser() {parent::Bs_Object(); }
function parseFile($fullPath) {$fContent = file($fullPath);$ret = array();$inHeader      = TRUE;$currentHeader = '';while (list(,$line) = each($fContent)) {if ($inHeader) {if ($line == "\n") {$inHeader = FALSE;} elseif ($line[0] == "\t") {} else {$pos = strpos($line, ':');$key = strToLower(substr($line, 0, $pos));$data = trim(substr($line, $pos +1));if ( ($data[0] == '<') && (substr($data, -1) == '>') ) $data = substr($data, 1, -1); $ret[$key] = $data;}
} else {}
}
return $ret;}
}
?>
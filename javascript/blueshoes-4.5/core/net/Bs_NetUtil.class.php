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
define('BS_NETUTIL_VERSION',      '4.5.$Revision: 1.3 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');class Bs_NetUtil extends Bs_Object {function Bs_NetUtil() {parent::Bs_Object(); }
function checkDnsRr_winNT($host, $type='') {if (!empty($host)) {if ($type == '') $type = "MX";@exec("nslookup -type=$type $host", $output);while(list($k, $line) = each($output)) {if (eregi("^$host", $line)) {return true;}
}
return false;}
}
function getMxRr_winNT($hostname, &$mxhosts) {if (!is_array($mxhosts)) $mxhosts = array();if (!empty($hostname)) {@exec("nslookup -type=MX $hostname", $output, $ret);while(list($k, $line) = each($output)) {if (ereg( "^$hostname\tMX preference = ([0-9]+), mail exchanger = (.*)$", $line, $parts)) {$mxhosts[ $parts[1] ] = $parts[2];}
}
if (count($mxhosts)) {reset($mxhosts);ksort($mxhosts);$i = 0;while(list($pref, $host) = each($mxhosts)) {$mxhosts2[$i] = $host;$i++;}
$mxhosts = $mxhosts2;return true;} else {return false;}
}
}
}
$GLOBALS['Bs_NetUtil'] =& new Bs_NetUtil(); ?>
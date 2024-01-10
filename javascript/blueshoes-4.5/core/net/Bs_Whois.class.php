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
define('BS_WHOIS_VERSION',      '4.5.$Revision: 1.3 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');class Bs_Whois extends Bs_NetApplication {function Bs_Whois() {$this->Bs_NetApplication(); $this->_Bs_System    = &$GLOBALS['Bs_System'];$this->serverName    = $GLOBALS['HTTP_SERVER_VARS']['SERVER_NAME'];$this->senderAddress = 'dummy@' . $GLOBALS['HTTP_SERVER_VARS']['SERVER_NAME'];$this->regExp = "^[0-9a-z_]([-_.]?[0-9a-z])*@[0-9a-z][-.0-9a-z]*\\.[a-z]{2,3}[.]?$";}
function whoisGeektools($domain) {$x = new Bs_SocketClient();$x->connect('whois.geektools.com', 43, FALSE, 180);$x->disconnect();}
Class whois {function lookup($lookup){$whois = "whois.geektools.com";$fp = fsockopen($whois, 43, &$errno, &$errstr, 30);if (!$fp){printf("Error: %s (%s)", $errstr, $errno);$data = 0;} else {$lookup .= "\n";fputs($fp, $lookup);$data = fread( $fp, 16384 );fclose($fp);} 
return $data;} 
} 
}
?>
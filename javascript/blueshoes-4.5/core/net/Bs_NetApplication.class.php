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
define('BS_NETAPPLICATION_VERSION',      '4.5.$Revision: 1.2 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'net/Bs_SocketClient.class.php');require_once($APP['path']['core'] . 'net/Bs_Url.class.php');class Bs_NetApplication extends Bs_Object {var $Bs_Url;var $_Bs_SocketClient = NULL;var $_Bs_SocketClient_Temp = NULL;var $_useTempConnection = FALSE;var $host = NULL;var $port = NULL;function Bs_NetApplication() {parent::Bs_Object(); $this->Bs_Url = &$GLOBALS['Bs_Url'];}
function connect($host, $port=NULL, $persistent=FALSE, $timeOut=NULL, $blocking=TRUE) {if ($this->_useTempConnection) {$connObj  = &$this->_Bs_SocketClient_Temp;if (is_null($timeOut)) $timeOut = 0;} else {$connObj  = &$this->_Bs_SocketClient;if (is_null($timeOut)) $timeOut = 30;}
if (is_null($port)) $port = $this->port;if ((is_null($host)) || (is_null($port))) 
return $this->_raiseError(BS_SOCKETCLIENT_ERROR_NEED_MORE_DATA, NULL, "missing data in connect(). host was: '{$host}', port was: '{$port}'.", __FILE__, __LINE__);if (!is_object($connObj)) $connObj = new Bs_SocketClient();$status = $connObj->connect($host, $port, $persistent, $timeOut, $blocking);if (isEx($status)) {$status->stackTrace('was here in connect()', __FILE__, __LINE__);return $status;}
if (!$this->_useTempConnection) {$this->host = $host; $this->port = $port; }
return TRUE;}
function disconnect() {if ($this->_useTempConnection) {$this->_Bs_SocketClient_Temp->disconnect();} else {$this->_Bs_SocketClient->disconnect();}
}
function _raiseError() {echo 'you have to overwrite this method.';}
}
?>
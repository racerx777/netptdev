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
require_once($APP['path']['plugins'] . 'indexserver/Bs_Is_IndexServer.class.php');require_once($APP['path']['plugins'] . 'indexserver/Bs_Is_Indexer.class.php');require_once($APP['path']['plugins'] . 'indexserver/Bs_Is_Profile.class.php');class Bs_Is_Indexer_PhpUnit extends Bs_TestCase {var $_Bs_Is_Indexer;function Bs_Is_Indexer_PhpUnit($name) {$this->Bs_TestCase($name);$Bs_Is_IndexServer =& new Bs_Is_IndexServer();$profile           =& new Bs_Is_Profile();$bsDb = null;$this->_Bs_Is_Indexer =& new Bs_Is_Indexer($Bs_Is_IndexServer, $profile, $bsDb);}
}
?>
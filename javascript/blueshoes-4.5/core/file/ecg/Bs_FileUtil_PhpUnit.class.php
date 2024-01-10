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
require_once($APP['path']['core'] . 'file/Bs_FileUtil.class.php');class Bs_FileUtil_PhpUnit extends Bs_TestCase {var $_Bs_FileUtil;function Bs_FileUtil_PhpUnit($name) {$this->Bs_TestCase($name);$this->_Bs_FileUtil = new Bs_FileUtil();}
function __Bs_FileUtil_encodeFilename() {$expected = "asdf";$actual   = $this->_Bs_FileUtil->encodeFilename('asdf');$this->assertEquals($expected, $actual, '');$expected = "as_ndf__adf";$actual   = $this->_Bs_FileUtil->encodeFilename("as\ndf_adf");$this->assertEquals($expected, $actual, '');$expected = "asdf_r_e_tasdf_sasfd_badsf";$actual   = $this->_Bs_FileUtil->encodeFilename("asdf\r \tasdf/asfd\\adsf");$this->assertEquals($expected, $actual, '');}
}
?>
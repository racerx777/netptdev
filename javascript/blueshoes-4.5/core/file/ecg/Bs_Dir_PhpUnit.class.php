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
require_once($APP['path']['core'] . 'file/Bs_FileSystem.class.php');class Bs_Dir_PhpUnit extends Bs_TestCase {var $_Bs_Dir;function Bs_Dir_PhpUnit($name) {$this->Bs_TestCase($name);$this->_Bs_Dir = new Bs_Dir();}
function __Bs_Dir_getFileList() {$param = array('fullPath' => '/tmp/subdir');$param['returnType'] = 'fullpath';XR_dump($this->_Bs_Dir->getFileList($param),__LINE__, '__Bs_Dir_getFileList', __FILE__);$param['returnType'] = 'subpath';XR_dump($this->_Bs_Dir->getFileList($param),__LINE__, '__Bs_Dir_getFileList', __FILE__);$param['returnType'] = 'nested';XR_dump($this->_Bs_Dir->getFileList($param),__LINE__, '__Bs_Dir_getFileList', __FILE__);$param['returnType'] = 'nested2';XR_dump($this->_Bs_Dir->getFileList($param),__LINE__, '__Bs_Dir_getFileList', __FILE__);$param['returnType'] = 'fulldir/file';XR_dump($this->_Bs_Dir->getFileList($param),__LINE__, '__Bs_Dir_getFileList', __FILE__);$param['returnType'] = 'subdir/file';XR_dump($this->_Bs_Dir->getFileList($param),__LINE__, '__Bs_Dir_getFileList', __FILE__);$param['returnType'] = 'subdir/file2';XR_dump($this->_Bs_Dir->getFileList($param),__LINE__, '__Bs_Dir_getFileList', __FILE__);}
}
?>
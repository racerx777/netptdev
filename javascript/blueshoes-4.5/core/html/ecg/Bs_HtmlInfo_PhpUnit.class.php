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
require_once($APP['path']['core'] . 'html/Bs_HtmlInfo.class.php');class Bs_HtmlInfo_PhpUnit extends Bs_TestCase {var $_Bs_HtmlInfo;var $_Bs_HtmlUtil;function Bs_HtmlInfo_PhpUnit($name) {$this->Bs_TestCase($name);$this->_Bs_HtmlInfo =& new Bs_HtmlInfo();$this->_Bs_HtmlUtil = &$GLOBALS['Bs_HtmlUtil'];}
function __Bs_HtmlInfo_fetchLinks() {$str = "adsf <a href='foo.html'>&nbsp;<img src='foo.gif' alt='lalala'>&nbsp;</a> asdf";$this->_Bs_HtmlInfo->initByString($str);$expected = array(array('href'=>'foo.html', 'caption'=>'lalala', 'target'=>NULL));$actual   = $this->_Bs_HtmlInfo->fetchLinks($dublicates=1, $urlOnly=FALSE, $useBaseTag=FALSE, $removeAnker=TRUE, $ignoreInvalid=TRUE);$this->assertEquals($expected, $actual, "the data was: " . $this->_Bs_HtmlUtil->filterForHtml($str));$str = "adsf <a href='foo.html'>&nbsp;&nbsp;<img src='foo.gif' alt='lalala'>&nbsp;&nbsp;</a> asdf";$this->_Bs_HtmlInfo->initByString($str);$expected = array(array('href'=>'foo.html', 'caption'=>'lalala', 'target'=>NULL));$actual   = $this->_Bs_HtmlInfo->fetchLinks($dublicates=1, $urlOnly=FALSE, $useBaseTag=FALSE, $removeAnker=TRUE, $ignoreInvalid=TRUE);$this->assertEquals($expected, $actual, "the data was: " . $this->_Bs_HtmlUtil->filterForHtml($str));$str = "adsf <a href='foo.html'>&nbsp;&nbsp;<img src='foo.gif' alt='lalala'>XXX&nbsp;&nbsp;</a> asdf";$this->_Bs_HtmlInfo->initByString($str);$expected = array(array('href'=>'foo.html', 'caption'=>'XXX', 'target'=>NULL));$actual   = $this->_Bs_HtmlInfo->fetchLinks($dublicates=1, $urlOnly=FALSE, $useBaseTag=FALSE, $removeAnker=TRUE, $ignoreInvalid=TRUE);$this->assertEquals($expected, $actual, "the data was: " . $this->_Bs_HtmlUtil->filterForHtml($str));$str = "adsf <a href='foo.html'>&nbsp;&nbsp;<img src='foo.gif' title='lalala' border='0'>TITLE&nbsp;&nbsp;</a> asdf";$this->_Bs_HtmlInfo->initByString($str);$expected = array(array('href'=>'foo.html', 'caption'=>'TITLE', 'target'=>NULL));$actual   = $this->_Bs_HtmlInfo->fetchLinks($dublicates=1, $urlOnly=FALSE, $useBaseTag=FALSE, $removeAnker=TRUE, $ignoreInvalid=TRUE);$this->assertEquals($expected, $actual, "the data was: " . $this->_Bs_HtmlUtil->filterForHtml($str));}
}
?>
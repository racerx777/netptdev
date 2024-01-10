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
require_once($APP['path']['core'] . 'net/email/Bs_EmailValidator.class.php');require_once($APP['path']['core'] . 'net/Bs_Url.class.php');class Bs_EmailValidator_PhpUnit extends Bs_TestCase {var $_APP;var $_Bs_EmailValidator;var $_Bs_Url;function Bs_EmailValidator_PhpUnit($name) {$this->Bs_TestCase($name);$this->_APP               = &$GLOBALS['APP'];$this->_Bs_EmailValidator =& new Bs_EmailValidator();$this->_Bs_Url            = &$GLOBALS['Bs_Url'];}
function __Bs_EmailValidator_validateSyntax() {$expected = TRUE;$actual = $this->_Bs_EmailValidator->validateSyntax($email='nospam@blueshoes.org');$this->assertEquals($expected, $actual, "$email");$expected = TRUE;$actual = $this->_Bs_EmailValidator->validateSyntax($email='x.Y.z.@domain.arpa');$this->assertEquals($expected, $actual, "$email");$expected = TRUE;$actual = $this->_Bs_EmailValidator->validateSyntax($email='this.IS.me@i.love.you.com');$this->assertEquals($expected, $actual, "$email");$expected = TRUE;$actual = $this->_Bs_EmailValidator->validateSyntax($email="_under_score_@host-dot.com");$this->assertEquals($expected, $actual, "$email");$expected = TRUE;$actual = $this->_Bs_EmailValidator->validateSyntax($email="dash-dash-@host-dot.com");$this->assertEquals($expected, $actual, "$email");}
function __Bs_EmailValidator_validateHost() {$expected = TRUE;$actual = $this->_Bs_EmailValidator->validateHost('info@blueshoes.org');if ($actual != BS_EMAILVALIDATOR_ERROR_NOT_CAPABLE) 
$this->assertEquals($expected, $actual, '');$expected = BS_EMAILVALIDATOR_ERROR_SYNTAX;$actual = $this->_Bs_EmailValidator->validateHost('...@digitalsolutions.ch');if ($actual != BS_EMAILVALIDATOR_ERROR_NOT_CAPABLE) 
$this->assertEquals($expected, $actual, '');$expected = BS_EMAILVALIDATOR_ERROR_HOST;$actual = $this->_Bs_EmailValidator->validateHost('asdf@host-not-exist-7497414.pl');if ($actual != BS_EMAILVALIDATOR_ERROR_NOT_CAPABLE) 
$this->assertEquals($expected, $actual, '');}
function __Bs_EmailValidator_validateMailbox() {$expected = TRUE;$actual = $this->_Bs_EmailValidator->validateMailbox($email='info@blueshoes.org');$this->assertEquals($expected, $actual, "$email");$expected = BS_EMAILVALIDATOR_ERROR_NO_SUCH_USER;$actual = $this->_Bs_EmailValidator->validateMailbox($email='xxx@hung.ch');$this->assertEquals($expected, $actual, "$email");$expected = 'bs_exception';$actual = $this->_Bs_EmailValidator->validateMailbox($email='asdf@host-not-exist-7497414.pl');$this->assertInstanceOf($expected, $actual, "$email");}
}
?>
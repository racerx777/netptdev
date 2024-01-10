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
define('BS_PHPUNIT_VERSION',      '4.5.$Revision: 1.2 $');require_once($APP['path']['core'] . 'crypt/Bs_Rc4Crypt.class.php');class Bs_Rc4Crypt_PhpUnit extends Bs_TestCase {var $_Bs_Rc4Crypt;function Bs_Rc4Crypt_PhpUnit($name) {$this->Bs_TestCase($name);$this->_Bs_Rc4Crypt = &$GLOBALS['Bs_Rc4Crypt'];}
function __Bs_Rc4Crypt_EnDecrypt_1() {$key  = 'This is may Key';$expected = 'Top secret: An inspired calligrapher can create pages of beauty using stick ink, quill, brush, pick-axe, buzz saw, or even strawberry jam.';$enc_data = $this->_Bs_Rc4Crypt->crypt($key, $expected);$actual = $this->_Bs_Rc4Crypt->crypt($key, $enc_data);$this->assertEquals($expected, $actual, 'Data [Simple string] Key [Simple string]  encryption - decryption failed');}
function __Bs_Rc4Crypt_EnDecrypt_2() {$key  = 'This is may Key';$expected = '';for ($i = 0; $i <= 255; $i++) {$expected .= chr($i);}
$enc_data = $this->_Bs_Rc4Crypt->crypt($key, $expected);$actual = $this->_Bs_Rc4Crypt->crypt($key, $enc_data);$this->assertEquals($expected, $actual, 'Data [Using all 256 ASCII char] Key [Simple string]  encryption - decryption failed');}
function __Bs_Rc4Crypt_EnDecrypt_3() {$key  = '';$expected = 'Top secret: An inspired calligrapher can create pages of beauty using stick ink, quill, brush, pick-axe, buzz saw, or even strawberry jam.';for ($i = 0; $i <= 255; $i++) {$key .= chr($i);}
$enc_data = $this->_Bs_Rc4Crypt->crypt($key, $expected);$actual = $this->_Bs_Rc4Crypt->crypt($key, $enc_data);$this->assertEquals($expected, $actual, 'Data [Simple string] Key [Using all 256 ASCII char]  encryption - decryption failed');}
function __Bs_Rc4Crypt_EnDecrypt_4() {$key  = '';$expected = '';for ($i = 0; $i <= 255; $i++) {$key .= chr($i);}
for ($i = 255; $i >= 0; $i--) {$expected .= chr($i);}
$enc_data = $this->_Bs_Rc4Crypt->crypt($key, $expected);$actual = $this->_Bs_Rc4Crypt->crypt($key, $enc_data);$this->assertEquals($expected, $actual, 'Data [Using all 256 ASCII char] Key [Using all 256 ASCII char]  encryption - decryption failed');}
}
?>
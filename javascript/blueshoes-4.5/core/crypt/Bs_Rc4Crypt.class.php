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
define('BS_RC4CRYPT_VERSION',      '4.5.$Revision: 1.2 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');class Bs_Rc4Crypt extends Bs_Object {function Bs_Rc4Crypt() {parent::Bs_Object();}
function crypt($pwd, $data) {$key[] = $box[] = $temp_swap = '';$pwd_length = strlen($pwd);for ($i = 0; $i <= 255; $i++) {$key[$i] = ord($pwd[$i % $pwd_length]);$box[$i] = $i;}
$x = 0;for ($i = 0; $i <= 255; $i++) {$x = ($x + $box[$i] + $key[$i]) % 256;$temp_swap = $box[$i];$box[$i] = $box[$x];$box[$x] = $temp_swap;}
$temp = $k = '';$cipherby = $cipher = '';$a = $j = 0;$data_length = strlen($data);for ($i = 0; $i < $data_length; $i++) {$a = ($a + 1) % 256;$j = ($j + $box[$a]) % 256;$temp = $box[$a];$box[$a] = $box[$j];$box[$j] = $temp;$k = $box[(($box[$a] + $box[$j]) % 256)];$cipherby = ord($data[$i]) ^ $k;$cipher .= chr($cipherby);}
return $cipher;}
function decrypt($pwd, $data) {return $this->crypt($pwd, $data);}
function encrypt($pwd, $data) {return $this->crypt($pwd, $data);}
}
$GLOBALS['Bs_Rc4Crypt'] =& new Bs_Rc4Crypt(); ?>
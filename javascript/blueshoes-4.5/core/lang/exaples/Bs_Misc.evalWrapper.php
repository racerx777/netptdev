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
require_once($_SERVER['DOCUMENT_ROOT'] . '../global.conf.php');$phpCode =<<<EOD
  \$out = '';
  \$someText = "This is some text";
  for (\$i=0; \$i<3; \$i++) {
    \$out .= \$i . ' ' . \$someText . '<br>';
  }
  echo \$out;
EOD;
$context = array('display_errors' => TRUE, 'sourceFile' => 'Inline Code');$ret = evalWrapper($phpCode, $context);echo $ret;bs_dump($context);echo "<hr>";$phpCode =<<<EOD
  // Division by zero
  \$out = 3/0;
EOD;
unset($context);$context = array('display_errors' => TRUE, 'sourceFile' => 'Inline Code');$ret = evalWrapper($phpCode, $context);echo $ret;bs_dump($context);echo "<hr>";$phpCode =<<<EOD
  \$out = '';
  \$someText = "This is some text"
  for (\$i=0; \$i<3; \$i++) {
    \$out .= \$i . ' ' . \$someText . '<br>';
  }
  echo \$out;
EOD;
unset($context);$context = array('display_errors' => TRUE, 'sourceFile' => 'Inline Code');$ret = evalWrapper($phpCode, $context);echo $ret;bs_dump($context);?>
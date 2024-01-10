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
$bs_logger_property[] = array(
'active'        => FALSE,
'hitNquit'      => FALSE,
'target'        => 'file',
'targetName'    => 'doDelTest.log',
'matcher'       => 'preg_match',
'msg_reg'       => '',
'mt_cond'       => 'OR',
'type_reg'      => '',
'tp_cond'       => 'OR',
'phpFile_reg'   => '',
'pf_cond'       => 'OR',
'freeStyle_reg' => 'TRUE',
'regEx'         => 'return preg_match("/^erro/i", $msgType);'
);$bs_logger_property[] = array(
'active'        => TRUE,
'hitNquit'      => FALSE,
'target'        => 'db',
'targetName'    => 'testlog',
'matcher'       => 'preg_match',
'msg_reg'       => '',
'mt_cond'       => 'OR',
'type_reg'      => '',
'tp_cond'       => 'OR',
'phpFile_reg'   => '',
'pf_cond'       => 'OR',
'freeStyle_reg' => 'TRUE',
'regEx'         => 'return TRUE;'
);?>
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
class Waiter {var $_ooDbProperty = array ( 
'caption' => array('mode'=>'lonely', 'metaType'=>'string', 'index'=>TRUE ),
'address' => array('mode'=>'stream', 'streamName'=>'' ),
'salery' => array('mode'=>'stream', 'streamName'=>'' ),
'orderSheets' => array('mode'=>'object',  'useScope'=>''  , 'reference'=>'weak' ),
);var $caption = '';var $address = '';var $salery  = 0.0;var $orderSheets = array();}
?>
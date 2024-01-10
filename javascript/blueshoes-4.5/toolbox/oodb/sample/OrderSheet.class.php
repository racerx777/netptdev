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
class OrderSheet {var $_ooDbProperty = array ( 
'oderDatetime' => array('mode'=>'lonely', 'metaType'=>'string', 'index'=>TRUE ),
'urgent' => array('mode'=>'lonely', 'metaType'=>'boolean', 'index'=>FALSE ),
'memo' => array('mode'=>'lonely', 'metaType'=>'string', 'index'=>FALSE ),
'waiter' => array('mode'=>'object',  'useScope'=>''   ),
'MenuItem' => array('mode'=>'object',  'useScope'=>''   ),
'tableNr' => array('mode'=>'lonely', 'metaType'=>'integer', 'index'=>FALSE ),
);var $oderDatetime = '';var $urgent = FALSE;var $memo = '';var $waiter = NULL;var $waiter = NULL;var $MenuItem = array();var $tableNr = 0;}
?>
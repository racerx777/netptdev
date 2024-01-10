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
define('BS_FORMLINE_VERSION',      '4.5.$Revision: 1.4 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'html/form/Bs_FormElement.class.php');class Bs_FormLine extends Bs_FormElement {var $align;var $width;var $size;var $color;var $noshade;function Bs_FormLine() {parent::Bs_FormElement(); $this->elementType = 'line';$this->hideCaption = 2; $tempArray = array('mode'=>'stream');$this->persisterVarSettings['align']   = &$tempArray;$this->persisterVarSettings['width']   = &$tempArray;$this->persisterVarSettings['size']    = &$tempArray;$this->persisterVarSettings['color']   = &$tempArray;$this->persisterVarSettings['noshade'] = &$tempArray;$this->persister->setVarSettings(&$this->persisterVarSettings);}
function &getElement() {$ret = "<hr";if ((isSet($this->align)) && (!empty($this->align)))   $ret .= " align=\"{$this->align}\"";if ((isSet($this->width)) && (!empty($this->width)))   $ret .= " width=\"{$this->width}\"";if ((isSet($this->size))  && (!empty($this->size)))    $ret .= " size=\"{$this->size}\"";if ((isSet($this->color)) && (!empty($this->color)))   $ret .= " color=\"{$this->color}\"";if ((isSet($this->noshade)) && ($this->noshade === TRUE)) $ret .= " noshade";$ret .= ">";return $this->_doElementStringFormat($ret);}
}
?>
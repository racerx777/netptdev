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
define('BS_FORMIMAGE_VERSION',      '4.5.$Revision: 1.3 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'html/form/Bs_FormElement.class.php');class Bs_FormImage extends Bs_FormElement {var $src;var $alt;var $align;var $border;var $width;var $height;var $hspace;var $vspace;var $usemap;var $ismap;function Bs_FormImage() {parent::Bs_FormElement(); $this->elementType = 'image';$this->hideCaption = 2; $tempArray = array('mode'=>'stream');$this->persisterVarSettings['src']     = &$tempArray;$this->persisterVarSettings['align']   = &$tempArray;$this->persisterVarSettings['border']  = &$tempArray;$this->persisterVarSettings['width']   = &$tempArray;$this->persisterVarSettings['height']  = &$tempArray;$this->persisterVarSettings['hspace']  = &$tempArray;$this->persisterVarSettings['vspace']  = &$tempArray;$this->persisterVarSettings['alt']     = &$tempArray;$this->persisterVarSettings['usemap']  = &$tempArray;$this->persisterVarSettings['ismap']   = &$tempArray;$this->persister->setVarSettings(&$this->persisterVarSettings);}
function &getElement() {$ret = "<img src=\"{$this->src}\"";if (isSet($this->width))  $ret .= " width=\"{$this->width}\"";if (isSet($this->height)) $ret .= " height=\"{$this->height}\"";if (isSet($this->border)) $ret .= " border=\"{$this->border}\"";if (isSet($this->alt))    $ret .= " alt=\"{$this->alt}\"";if (isSet($this->hspace)) $ret .= " hspace=\"{$this->hspace}\"";if (isSet($this->vspace)) $ret .= " vspace=\"{$this->vspace}\"";if (isSet($this->align))  $ret .= " align=\"{$this->align}\"";if (isSet($this->usemap)) $ret .= " usemap=\"{$this->usemap}\"";if ((isSet($this->ismap)) && ($this->ismap === TRUE)) $ret .= " ismap";$ret .= ">";return $this->_doElementStringFormat($ret);}
}
?>
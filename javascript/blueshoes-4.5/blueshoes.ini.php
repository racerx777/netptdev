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
$bsRoot = $APP['path']['bsRoot'];$APP['path']['core']            = $bsRoot . 'core/';$APP['path']['lib']             = $bsRoot . 'lib/';$APP['path']['plugins']         = $bsRoot . 'plugins/';$APP['path']['applications']    = $bsRoot . 'applications/';$APP['path']['toolbox']         = $bsRoot . 'toolbox/';$APP['path']['haystack']        = $bsRoot . 'haystack/';$APP['path']['XPath']           = $bsRoot . 'lib/XPath/';$APP['path']['pear']            = $bsRoot . 'lib/pear/';$APP['plugins'][] = array('name' => 'eCards',  'version' => array('major' => '1', 'minor' => '0', 'bugfix' => '0'));$APP['plugins'][] = array('name' => 'WebForm', 'version' => array('major' => '1', 'minor' => '0', 'bugfix' => '0'));$APP['plugins'][] = array('name' => 'Poll',    'version' => array('major' => '1', 'minor' => '0', 'bugfix' => '0'));$APP['siteName']    = 'blueshoes';$APP['url']['shoeFactory']    = 'http://www.blueshoes.org/';if (!isSet($APP['path']['diffFromDocRootToGlobalConf'])) $APP['path']['diffFromDocRootToGlobalConf'] = '../';require_once($APP['path']['core'] . 'lang/Bs_XRay.class.php');  require_once($APP['path']['core'] . 'lang/Bs_Misc.lib.php');    require_once($APP['path']['core'] . 'lang/Bs_Error.class.php'); require_once($APP['path']['core'] . 'lang/Bs_Logger.class.php');bs_undoMagicQuotes();require_once($APP['path']['core'] . 'Bs_Object.class.php');require_once($APP['path']['core'] . 'lang/Bs_Exception.class.php');bs_addIncludePath($APP['path']['bsRoot']);?>
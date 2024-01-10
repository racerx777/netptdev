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
require_once($_SERVER["DOCUMENT_ROOT"]    . "../global.conf.php");require_once($APP['path']['core'] . 'util/Bs_String.class.php');Class Bs_GeoServer extends Bs_Object {var $_bsDb;function Bs_GeoServer() {if (isSet($GLOBALS['bsDb'])) {$this->_bsDb = &$GLOBALS['bsDb'];}
}
function setDb(&$dbObj) {$this->_bsDb = &$dbObj;}
function getCountryCodeByCountryString($countryString) {$directMatchStr = $this->_bsDb->escapeString($countryString);$sql = "SELECT code FROM BsKb.Country WHERE (en LIKE '{$directMatchStr}' OR de LIKE '{$directMatchStr}' OR fr LIKE '{$directMatchStr}') LIMIT 1";$status = $this->_bsDb->getOne($sql);if (is_string($status)) return $status;$sql = "SELECT code FROM BsKb.CountrySynonym WHERE caption LIKE '{$directMatchStr}' LIMIT 1";$status = $this->_bsDb->getOne($sql);if (is_string($status)) return $status;$sql = "SELECT code FROM BsKb.Country WHERE (en LIKE '{$directMatchStr}%' OR de LIKE '{$directMatchStr}%' OR fr LIKE '{$directMatchStr}%') LIMIT 1";$status = $this->_bsDb->getOne($sql);if (is_string($status)) return $status;$sql = "SELECT code FROM BsKb.CountrySynonym WHERE caption LIKE '{$directMatchStr}%' LIMIT 1";$status = $this->_bsDb->getOne($sql);if (is_string($status)) return $status;return NULL;}
function getCountryCodeByLocationId($locationId) {}
function getCapital($countryCode) {$sql = "SELECT cityCaptionEn FROM BsKb.CountryCapital WHERE Country_code LIKE '{$countryCode}' LIMIT 1";$capital = $this->_bsDb->getOne($sql);return $capital;}
function getCoordinatesForCountry($countryCode) {}
function getCoordForLocationByLocationId($locationId) {}
function getCoordForLocationByLocationName($locationName, $country) {}
function getCountryList($lang) {}
function getLocationList($countryCode, $lang='en') {}
}
?>
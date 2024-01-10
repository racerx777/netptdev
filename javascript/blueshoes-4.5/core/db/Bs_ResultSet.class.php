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
define('BS_RESULTSET_VERSION',      '4.5.$Revision: 1.1.1.1 $');class Bs_ResultSet extends Bs_Object {var $_dbh;var $_result;function Bs_ResultSet(&$dbh, &$result) {$this->_dbh    = &$dbh;$this->_result = &$result;}
function fetchRow($fetchMode=NULL) {return $this->_dbh->fetchRow($this->_result, $fetchMode);}
function numCols() {return $this->_dbh->numCols($this->_result);}
function numRows() {return $this->_dbh->numRows($this->_result);}
function free() {$this->_dbh->freeResult($this->_result);}
}
?>
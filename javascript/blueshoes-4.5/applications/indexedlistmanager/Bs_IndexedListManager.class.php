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
require_once($_SERVER["DOCUMENT_ROOT"]       . '../global.conf.php');class Bs_IndexedListManager extends Bs_Object {var $tableName;var $fieldNameKey = 'ID';var $fieldNameValue = 'caption';var $unique = 2;var $minLength = 1;var $maxLength = 255;var $_bsDb;function Bs_IndexedListManager() {parent::Bs_Object(); $this->_bsDb     = &$GLOBALS['bsDb'];}
function setDb($dbObj) {unset($this->_bsDb);$this->_bsDb = &$dbObj;}
function add($value) {$ret = array('key'=>null, 'value'=>$value, 'status'=>FALSE, 'error'=>null);do {$valueIsOk = $this->checkValue($value);if (getType($valueIsOk) == 'string') {$ret['error'] = $valueIsOk;break;}
$dbIsOk = $this->checkDb();if (getType($dbIsOk) == 'string') {$ret['error'] = $dbIsOk;break;}
$key = $this->_bsDb->idWrite("INSERT INTO {$this->tableName} ({$this->fieldNameValue}) VALUES('" . $this->_bsDb->escapeString($value) . "')");if (isEx($key)) {$ret['error'] = 'Failed inserting the record into the database. Check your value and try it again.';break;}
$ret['status'] = TRUE;$ret['key']    = $key;} while (FALSE);return $ret;}
function edit($key, $newValue) {$ret = array('key'=>null, 'value'=>$newValue, 'status'=>FALSE, 'error'=>null);do {$valueIsOk = $this->checkValue($newValue);if (getType($valueIsOk) == 'string') {$ret['error'] = $valueIsOk;break;}
$dbIsOk = $this->checkDb();if (getType($dbIsOk) == 'string') {$ret['error'] = $dbIsOk;break;}
$keyString = is_numeric($key) ? $key : "'{$key}'";$status = $this->_bsDb->write("UPDATE {$this->tableName} SET {$this->fieldNameValue} = '" . $this->_bsDb->escapeString($newValue) . "' WHERE {$this->fieldNameKey}={$keyString}");if (isEx($status)) {$ret['error'] = 'Failed updating the record in the database. Check your value and try it again.';break;}
$ret['status'] = TRUE;$ret['key']    = $key;} while (FALSE);return $ret;}
function delete($key) {$ret = array('key'=>null, 'status'=>FALSE, 'error'=>null);do {$dbIsOk = $this->checkDb();if (getType($dbIsOk) == 'string') {$ret['error'] = $dbIsOk;break;}
$keyString = is_numeric($key) ? $key : "'{$key}'";$status = $this->_bsDb->write("DELETE FROM {$this->tableName} WHERE {$this->fieldNameKey}={$keyString}");if (isEx($status)) {$ret['error'] = 'Failed deleting the record in the database. Maybe it has already been deleted?';break;}
$ret['status'] = TRUE;$ret['key']    = $key;} while (FALSE);return $ret;}
function getList() {$dbIsOk = $this->checkDb();$sql    = "SELECT {$this->fieldNameKey}, {$this->fieldNameValue} FROM {$this->tableName}";$list   = $this->_bsDb->getAssoc($sql);return $list;}
function checkValue($val) {if (empty($val)) {return 'The given value was empty.';}
if ($this->unique == 2) {} elseif ($this->unique == 1) {}
return TRUE;}
function checkDb() {if (!$this->_bsDb->tableExists($this->tableName)) {$sql = "
create table {$this->tableName} (
{$this->fieldNameKey}      INT NOT NULL DEFAULT 0 AUTO_INCREMENT, 
{$this->fieldNameValue} varchar({$this->maxLength}) NOT NULL DEFAULT '', 
PRIMARY KEY {$this->fieldNameKey} ({$this->fieldNameKey}), 
key {$this->fieldNameValue} ({$this->fieldNameValue})
)
";$status = $this->_bsDb->write($sql);if (isEx($status)) {return "The database table '{$this->tableName}' did not exist, and creating it failed.";}
} else {return TRUE;}
}
function renderPage($serverUrl, $addScript='') {$ret  = '';$ret .= '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><head>';$ret .= $this->_renderHead();$ret .= "<script>\n";$ret .= "var serverUrl = '{$serverUrl}';";$ret .= $addScript;$ret .= "</script>\n";$ret .= '</head>';$ret .= '<body onload="init();">';$ret .= $this->_renderBody();$ret .= '</body></html>';return $ret;}
function _renderBody() {$ret =<<<EOD
<form name="ilmForm">
<fieldset class="ilmOuterFieldset">
<legend>Indexed List Manager</legend>
<table border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td valign="top">
      List<br>
      <select name="fldSelect" id="fldSelect" size="20" class="ilmFieldSelect" onclick="clickSelectField();">
      </select>
    </td>
    <td valign="top">
      <fieldset class="ilmFieldset">
        <legend>Add</legend>
        <div class="ilmFieldsetContainer">
          <input type="text" name="txtAdd" size="20" class="ilmFieldText ilmFieldTextAdd"> <input type="button" name="btnAdd" value="Add" onclick="ilm_add();" class="ilmButton ilmButtonAdd">
        </div>
      </fieldset>
      
      <fieldset class="ilmFieldset">
        <legend>Edit</legend>
        <div class="ilmFieldsetContainer">
          <input type="hidden" name="hidEdit">
          <input type="text" name="txtEdit" size="20" class="ilmFieldText ilmFieldTextEdit"> <input type="button" name="btnEdit" value="Edit" onclick="ilm_edit();" class="ilmButton ilmButtonEdit">
        </div>
      </fieldset>
      
      <fieldset class="ilmFieldset">
        <legend>Delete</legend>
        <div class="ilmFieldsetContainer">
          <input type="hidden" name="hidDelete">
          <input type="text" name="txtDelete" size="20" class="ilmFieldText ilmFieldTextDelete" readonly> <input type="button" name="btnDelete" value="Delete" onclick="ilm_delete();" class="ilmButton ilmButtonDelete">
        </div>
      </fieldset>
    </td>
  </tr>
</table>
</fieldset>
</form>
EOD;
return $ret;}
function _renderHead() {$ret =<<<EOD
    <title>Indexed List Manager</title>
  <script type="text/javascript" src="/_bsJavascript/core/lang/Bs_Misc.lib.js"></script>
  <script type="text/javascript" src="/_bsJavascript/plugins/jsrs/JsrsCore.lib.js"></script> 
  <script type="text/javascript" src="/_bsJavascript/core/form/Bs_FormFieldSelect.class.js"></script>
  <script type="text/javascript" src="/_bsApplications/indexedlistmanager/functions.js"></script>
  <link rel="stylesheet" type="text/css" href="/_bsApplications/indexedlistmanager/default.css">
EOD;
return $ret;}
}
?>
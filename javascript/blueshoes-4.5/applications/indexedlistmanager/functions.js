/********************************************************************************************
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
*/
function init() {
elmFldSelect = document.getElementById('fldSelect');ffs = new Bs_FormFieldSelect();ffs.init(elmFldSelect);var key = document.ilmForm.txtAdd.value    = '';var val = document.ilmForm.txtEdit.value   = '';var val = document.ilmForm.txtDelete.value = '';var callId    = jsrsCall(serverUrl, "ilm_callbackGetList", "Bs_IndexedListManager.getList");}
function ilm_callbackGetList(list) {
elmFldSelect.addElementsByHash(list);}
function ilm_add() {
var val       = document.ilmForm.txtAdd.value;var callId    = jsrsCall(serverUrl, "ilm_callbackAdd", "Bs_IndexedListManager.add", val);}
function ilm_edit() {
var key    = document.ilmForm.hidEdit.value;var val    = document.ilmForm.txtEdit.value;var callId = jsrsCall(serverUrl, "ilm_callbackEdit", "Bs_IndexedListManager.edit", key, val);}
function ilm_delete() {
var key    = document.ilmForm.hidDelete.value;var callId = jsrsCall(serverUrl, "ilm_callbackDelete", "Bs_IndexedListManager.delete", key);}
function ilm_callbackAdd(ret) {
if (ret['status'] == false) {
alert("Inserting of '" + ret['value'] + "' failed. Reason: '" + ret['error'] + "'.");} else {
var newOpt = new Option(ret['value'], ret['key'], false, false);elmFldSelect.options[elmFldSelect.options.length] = newOpt;elmFldSelect.sortByText();}
}
function ilm_callbackEdit(ret) {
if (ret['status'] == false) {
alert("Updating of '" + ret['value'] + "' failed. Reason: '" + ret['error'] + "'.");} else {
elmFldSelect.setText(ret['key'], ret['value']);}
}
function ilm_callbackDelete(ret) {
if (ret['status'] == false) {
alert("Deleting of '" + ret['value'] + "' failed. Reason: '" + ret['error'] + "'.");} else {
elmFldSelect.removeElement(ret['key']);}
}
function clickSelectField() {
_onClickAvailableOption();}
var _lastOnClickOptionElm;function _onClickAvailableOption(b) {
if (b == true) {
try {
var srcElm = (!bs_isNull(event) && !bs_isNull(event.srcElement)) ? event.srcElement : _lastOnClickOptionElm;var opt    = srcElm.options[srcElm.selectedIndex];document.ilmForm.hidEdit.value   = opt.value;document.ilmForm.txtEdit.value   = opt.text;document.ilmForm.hidDelete.value = opt.value;document.ilmForm.txtDelete.value = opt.text;} catch (e) {
}
} else {
this._lastOnClickOptionElm = event.srcElement;setTimeout('_onClickAvailableOption(true)', 50);return;}
}

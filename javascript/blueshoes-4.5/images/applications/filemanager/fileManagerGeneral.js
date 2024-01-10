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
function deleteFile() {
if (parent.fileSelected.length == 0) {
alert("Cannot delete - no file(s) selected.");return;} else if (parent.fileSelected.length == 1) {
    q = "Are you sure you want to delete the file '" + parent.fileSelected["0"].innerText.replace(/ /, "") + "'?";
} else {
q = "Are you sure you want to delete these " + parent.fileSelected.length + " items?";}
doIt = confirm(q);if (doIt == false) return;var obj  = parent.fileSelected["0"];  var file = obj.innerText.replace(/ /, ""); //we would need a trim function instead of replace.
parent.frameCommand.location.href = encodeURI(php_self + "?showPage=command&do=delete&path=" + relPath + "&file=" + file);}
function renameFile(obj) {
  var file = obj.innerText.replace(/ /, ""); //we would need a trim function instead of replace.
var newFile = window.prompt("Neuer Dateiname", file);if ((newFile != null) && (typeof(newFile) != 'undefined') && (newFile) && (newFile != "") && (newFile != file)) {
parent.frameCommand.location.href = encodeURI(php_self + "?showPage=command&do=rename&path=" + relPath + "&file=" + file + "&newFile=" + newFile);}
}
function editFile(obj) {
  var file = obj.innerText.replace(/ /, ""); //we would need a trim function instead of replace.
window.location.href = encodeURI(php_self + "?showPage=editor&path=" + relPath + "&file=" + file);}
function viewFile(obj) {
  var file = obj.innerText.replace(/ /, ""); //we would need a trim function instead of replace.
var path = encodeURI(php_self + "?showPage=viewer&do=download&path=" + relPath + "&file=" + file);window.open(path, "window1", "width=600,height=400, left=50, top=50, menubar=yes, location=yes, resizable=yes, scrollbars=yes, status=yes, toolbar=yes");}
function downloadFile(obj) {
  var file = obj.innerText.replace(/ /, ""); //we would need a trim function instead of replace.
parent.frameCommand.location.href = encodeURI(php_self + "?showPage=command&do=download&path=" + relPath + "&file=" + file);}
function cut() {
_cutAndCopy('cut');}
function copy() {
_cutAndCopy('copy');}
function _cutAndCopy(what) {
var obj  = parent.fileSelected["0"];  var file = obj.innerText.replace(/ /, ""); //we would need a trim function instead of replace.
var elm = new Object();elm['relPath'] = relPath;elm['element'] = file;elm['do']      = what;parent.frameTop.virtualClipboard = new Array(elm);}
function paste() {
var virtualClipboard = parent.frameTop.virtualClipboard;for (var i=0; i<virtualClipboard.length; i++) {
var doWhat = (virtualClipboard[i]['do'] == 'cut') ? 'move' : 'copy';parent.frameCommand.location.href = encodeURI(php_self + "?showPage=command&do=" + doWhat + "&pathOld=" + virtualClipboard[i]['relPath'] + "&pathNew=" + relPath + "&element=" + virtualClipboard[i]['element']);break;}
parent.frameTop.virtualClipboard = new Array();}

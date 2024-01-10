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
var wysiwygWin;function startWysiwyg() {
if (!mySpreadSheet._currentCell) {
alert("Please select a cell first.");return;}
wysiwygWin = window.open("/_admin/wysiwyg/popup.html", "spreadsheetWysiwyg", "width=610,height=500, left=50, top=50, resizable=yes, menubar=no, toolbar=no, dependent=yes");}
function callbackWysiwygLoaded() {
wysiwygWin.callbackSave = 'callbackWysiwygSave';wysiwygWin.document.frames.bs_wysiwygEditor.setValue(mySpreadSheet._currentCell.innerHTML);}
function callbackWysiwygSave(value) {
mySpreadSheet._currentCell.innerHTML = value;mySpreadSheet.editCellEnd(mySpreadSheet._currentCell);}
function formatAlign() {
mySpreadSheet.formatAlign((btnAlignLeft.getStatus() == 2), (btnAlignCenter.getStatus() == 2), (btnAlignRight.getStatus() == 2));}
function updateStatusBar(txt) {
document.getElementById('statusBar').innerText = txt;}
function resizeColStart(idName) {
obj = document.getElementById(idName);posH = window.event.clientX;widthOrig = obj.width;diffOrigWidthToPosH = widthOrig - posH;}
function resizeColEnd(idName) {
obj = document.getElementById(idName);var number = parseInt(idName.substr(15));mySpreadSheet._cellWidth[number -1] = parseInt(obj.width);}
function resizeCol(idName) {
try {
obj = document.getElementById(idName);var diff = window.event.clientX - posH;obj.width = posH + diff + diffOrigWidthToPosH;} catch (e) {
}
}
function resizeRowStart(idName) {
obj = document.getElementById(idName);posH = window.event.clientY;heightOrig = obj.height;diffOrigHeightToPosH = heightOrig - posH;}
function resizeRowEnd(idName) {
obj = document.getElementById(idName);var number = parseInt(idName.substr(15));mySpreadSheet._cellHeight[number -1] = parseInt(obj.height);}
function resizeRow(idName) {
try {
obj = document.getElementById(idName);var diff = window.event.clientY - posH;obj.height = posH + diff + diffOrigHeightToPosH;} catch (e) {
}
}
function addRow(above) {
var number = parseInt(lastContextMenuObject.substr(15));if (above) number--;mySpreadSheet.addRow(number);}
function removeRow() {
var number = parseInt(lastContextMenuObject.substr(15)) -1;mySpreadSheet.removeRow(number);}
function addCol(left) {
var number = parseInt(lastContextMenuObject.substr(15));if (left) number--;mySpreadSheet.addCol(number);}
function removeCol() {
var number = parseInt(lastContextMenuObject.substr(15)) -1;mySpreadSheet.removeCol(number);}
var lastContextMenuObject;var menuRow;var menuCol;function menubar() {
menuRow=Menu(null);menuRow.moveTo(150,150);menuRow.addItem("Remove Row","removeRow()");menuRow.addItem("Insert Row");menuRow.childNodes[1].addItem("Above", "addRow(true)");menuRow.childNodes[1].addItem("Below", "addRow(false)");menuRow.addSeparator();attachMenuRow();menuCol=Menu(null);menuCol.moveTo(150,150);menuCol.addItem("Remove Col","removeCol()");menuCol.addItem("Insert Col");menuCol.childNodes[1].addItem("Left", "addCol(true)");menuCol.childNodes[1].addItem("Right", "addCol(false)");menuCol.addSeparator();attachMenuCol();}
function attachMenuRow() {
var numDataLines = mySpreadSheet.getNumRows();for (i=1; i<=numDataLines; i++) {
var elm2=core.getElm('row_autonumber_' + i);if(core.isIE){
elm2.oncontextmenu=popupMenuRow;elm2.onclick=function(){menuRow.hide()}
} else {
elm2.onclick=popupMenuRow;}
}
}
function attachMenuCol() {
var numCols = mySpreadSheet.getNumCols();for (i=1; i<=numCols; i++) {
var elm2=core.getElm('col_autonumber_' + i);if(core.isIE){
elm2.oncontextmenu=popupMenuCol;elm2.onclick=function(){menuCol.hide()}
} else {
elm2.onclick=popupMenuCol;}
}
}
function popupMenu(e, obj) {
core.preventBubble(e);if (core.isIE) {
obj.moveTo(event.clientX,event.clientY);obj.show();lastContextMenuObject = event.srcElement.id;if (lastContextMenuObject.substr(lastContextMenuObject.length -5) == '_hack') {
lastContextMenuObject = lastContextMenuObject.substr(0, lastContextMenuObject.length -5);}
return false;} else {
obj.hide();if(e.which==3){
obj.moveTo(e.pageX,e.pageY);obj.show();return false;}
}
}
function popupMenuRow(e) {
return popupMenu(e, menuRow);}
function popupMenuCol(e) {
return popupMenu(e, menuCol);}
function invokeSave() {
mySpreadSheet.save();}

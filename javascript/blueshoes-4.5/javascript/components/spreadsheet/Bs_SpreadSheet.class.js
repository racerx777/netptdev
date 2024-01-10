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
function bs_ss_pageLoaded() {
mySpreadSheet = new Bs_SpreadSheet;try {
if (opener) parent = opener;parent.bs_ss_init();} catch (e) {
}
}
function Bs_SpreadSheet() {
this.objectName;this.debug = false;this.showLineNumbers = true;this.firstRowTitle = false;this.firstColTitle = false;this.useToolbar = true;this.mayUseClipboard = true;this.mayUseFormat = true;this.mayUseAlign = true;this.mayUseWysiwyg = true;this._drawStyle = 'hidden';this.numCols = 6;this.numRows = 10;this._data;this._callbackFunction;this.returnType = 'array';this._currentCell;this._currentState;this._currentCellLastValue;this.defaultCellWidth = 80;this._cellWidth;this.defaultCellHeight = 20;this._cellHeight;this.sheetWidth  = 450;this.sheedHeight = 230;this.editorI;this.editorJ;this._drawTagId;this.buttonsPath = '/_bsImages/buttons/';this.setMousePointer = function(work) {
var body = document.getElementById('body');if (body) {
if (work) {
body.style.cursor = 'hand';} else {
body.style.cursor = 'hand';}
}
}
this._getNewCell = function() {
var ret = new Array();ret['value']     = '';ret['bold']      = false;ret['italic']    = false;ret['underline'] = false;ret['align']     = '';return ret;}
this.addCol = function(pos, noRedraw) {
try {
if (typeof(pos) == 'boolean') {
if (pos == false) {
pos = this.getCol(this._currentCell.id) +1;} else {
pos = this.getCol(this._currentCell.id);}
} else if ((pos == null) || (typeof(pos) == 'undefined')) {
pos = this._data[0].length +1;}
if (this.debug) alert("insert col at position " + pos);this.updateDataFromFields();var t = new Array();for (var i=0; i<this._cellWidth.length; i++) {
if (pos == i) {
t.push(this.defaultCellWidth);}
t.push(this._cellWidth[i]);}
this._cellWidth = t;for (var i=0; i<this._data.length; i++) {
var numCols = this._data[i].length;var t = null;t = new Array();var doneAdd = false;for (var j=0; j<numCols; j++) {
if (pos == j) {
t.push(this._getNewCell());doneAdd = true;}
t.push(this._data[i][j]);}
if (!doneAdd) {
t.push(this._getNewCell());}
this._data[i] = t;}
if (!noRedraw) this.draw();} catch (e) {
}
}
this.addRow = function(pos, noRedraw) {
try {
if (typeof(pos) == 'boolean') {
if (pos == false) {
pos = this.getRow(this._currentCell.id) +1;} else {
pos = this.getRow(this._currentCell.id);}
} else if ((pos == null) || (typeof(pos) == 'undefined')) {
pos = this._data.length +1;}
if (this.debug) alert("insert row at position " + pos);this.updateDataFromFields();var t = new Array();for (var i=0; i<this._cellHeight.length; i++) {
if (pos == i) {
t.push(this.defaultCellHeight);}
t.push(this._cellHeight[i]);}
this._cellHeight = t;var doneAdd = false;var t = new Array();for (var i=0; i<this._data.length; i++) {
if (pos == i) {
var tt = new Array;for (var j=0; j<this._data[i].length; j++) {
tt[j] = this._getNewCell();}
t.push(tt);doneAdd = true;}
t.push(this._data[i]);}
if (!doneAdd) {
var maxCols = bs_array_maxSizeOfLevel(this._data, 2);var tt = new Array;for (var j=0; j<maxCols; j++) {
tt[j] = this._getNewCell();}
t.push(tt);}
this._data = t;if (!noRedraw) this.draw();} catch (e) {
}
}
this.removeCol = function(pos) {
if (isNaN(pos)) {
pos = this.getCol(this._currentCell.id);}
if (this.debug) alert("remove col at position " + pos);this.updateDataFromFields();var t = new Array();for (var i=0; i<this._cellWidth.length; i++) {
if (pos == i) continue;t.push(this._cellWidth[i]);}
this._cellWidth = t;for (var i=0; i<this._data.length; i++) {
var t = new Array();for (var j=0; j<this._data[i].length; j++) {
if (pos == j) continue;t.push(this._data[i][j]);}
this._data[i] = t;}
this.draw();}
this.removeRow = function(pos) {
if (isNaN(pos)) {
pos = this.getRow(this._currentCell.id);}
if (this.debug) alert("remove row at position " + pos);this.updateDataFromFields();var t = new Array();for (var i=0; i<this._cellHeight.length; i++) {
if (pos == i) continue;t.push(this._cellHeight[i]);}
this._cellHeight = t;var t = new Array();for (var i=0; i<this._data.length; i++) {
if (pos == i) continue;t.push(this._data[i]);}
this._data = t;this.draw();}
this.init = function(data, drawTagId, callbackFunction) {
this._importData(data);this._drawTagId = drawTagId;if (callbackFunction != null) this._callbackFunction = callbackFunction;this.draw();}
this._importData = function(data) {
var maxCols = bs_array_maxSizeOfLevel(data, 2);if (this.numCols > maxCols) maxCols = this.numCols;var numRows = data.length;if (this.numRows > numRows) numRows = this.numRows;for (var i=0; i<numRows; i++) {
for (var j=0; j<maxCols; j++) {
if (typeof(data[i]) == 'undefined') data[i] = new Array;try {
if (typeof(data[i][j]) != 'object') {
var tmpVal = data[i][j];} else {
var tmpVal = '';}
} catch (e) {
var tmpVal = '';}
data[i][j] = new Object;data[i][j]['value']     = tmpVal;data[i][j]["bold"]      = false;data[i][j]['italic']    = false;data[i][j]['underline'] = false;data[i][j]['align']     = '';}
}
this._data = data;}
this.exportDataToCsv = function() {
var clean = new Array;for (var i=0; i<this._data.length; i++) {
clean[i] = new Array;for (var j=0; j<this._data[i].length; j++) {
clean[i][j] = this._data[i][j]['value'];}
}
return bs_array_toCsv(this._data, ';');}
this._exportData = function() {
}
this.draw = function() {
var containerElm    = document.getElementById(this._drawTagId);var containerWidth  = parseInt(containerElm.currentStyle.width);var containerHeight = parseInt(containerElm.currentStyle.height);var containerPos    = getAbsolutePos(containerElm, true);var layout = '';if (moz) {
layout += '<div id="bs_ss_pasteLayerDiv" style="z-index:15; display:none; position:absolute; background-color:#D6D3CE; padding:5px; border:1px solid black;">';layout += '<textarea name="bs_ss_pasteLayerTxt" id="bs_ss_pasteLayerTxt" cols="30" rows="4"></textarea><br>';layout += '<input type="button" name="bs_ss_pasteLayerOk" value="OK" onclick="' + this.objectName + '.pasteValue(document.getElementById(\'bs_ss_pasteLayerTxt\').value); document.getElementById(\'bs_ss_pasteLayerTxt\').value = \'\'; document.getElementById(\'bs_ss_pasteLayerDiv\').style.display = \'none\';"> ';layout += '<input type="button" name="bs_ss_pasteLayerCancel" value="Cancel" onclick="document.getElementById(\'bs_ss_pasteLayerTxt\').value = \'\'; document.getElementById(\'bs_ss_pasteLayerDiv\').style.display = \'none\';">';layout += '</div>';}
layout    += '<div id="' + this.objectName + '_toolbarDiv" style="width:100%;"></div>';layout    += '<div id="' + this.objectName + '_formulaDiv" style="width:100%; background-color:#D6D3CE; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#404040;">';layout    += '<div style="position:absolute; left:' + (containerPos.x + 8) + 'px;">Field: <span id="' + this.objectName + '_fieldSpan" style="color:#000000;"></span></div>';layout    += '<div style="position:relative; left:80px;">Value: ';layout    += '<div id="' + this.objectName + '_valueSpan" style="position:absolute; width:' + (containerWidth -120) + 'px; color:#000000; background-color:#D6D3CE;"></div>';layout    += '</div>';layout    += '</div>';if ((containerElm.currentStyle.overflow == 'auto') || (containerElm.currentStyle.overflow == 'scroll')) {
var sheetDivOverflow = 'visible';var sheetDivHeight   = '100%';} else {
var sheetDivOverflow = 'auto';var sheetDivHeight   = (containerHeight -38) + 'px';}
layout    += '<div id="' + this.objectName + '_spreadSheetDiv" style="width:100%; height:' + sheetDivHeight + '; overflow:' + sheetDivOverflow + ';"></div>';document.getElementById(this._drawTagId).innerHTML = layout;if (this.useToolbar) this._loadToolbar();if ((this._currentCell) && (this._currentState == 'active')) {
var reactivateCurrentCell = this._currentCell.id;}
var fullTableWidth = 30;this.calculateCellWidth();this.calculateCellHeight();var numCols = this.getNumCols();if (this.debug) alert("building table with " + numCols + " cols and " + this._data.length + " rows.");var titleRow = new Array;titleRow[titleRow.length] = '<tr>';titleRow[titleRow.length] = '<td width="25" bgcolor="#DEDBD6" style="border-bottom-color: Black; border-bottom-style: solid; border-bottom-width: 1px; border-right-color: Black; border-right-style: solid; border-right-width: 1px;">&nbsp;</td>';for (var i=0; i<numCols; i++) {
var tdId = 'col_autonumber_' + (i+1);titleRow[titleRow.length] = '<td id="' + tdId + '" width="' + this._cellWidth[i] + '" bgcolor="#DEDBD6" align="middle" style="border-bottom-color: Black; border-bottom-style: solid; border-bottom-width: 1px; border-right-color: Black; border-right-style: solid; border-right-width: 1px;">';titleRow[titleRow.length] = '<table width="100%" height="100%" cellpadding="0" cellspacing="0"><tr><td align="middle" id="' + tdId + '_hack">';titleRow[titleRow.length] = String.fromCharCode(65 +i);titleRow[titleRow.length] = '</td><td align="right" width="3">';titleRow[titleRow.length] = '<span style="cursor:col-resize;" ondragstart="resizeColStart(\'' + tdId + '\')" ondrag="resizeCol(\'' + tdId + '\')" ondragend="resizeColEnd(\'' + tdId + '\')"><img src="/_bsImages/spacer.gif" width="3" height="10"></span>';titleRow[titleRow.length] = '</td></tr></table>';titleRow[titleRow.length] = '</td>';}
titleRow[titleRow.length] = '</tr>';var out = new Array;var addRowLine = '';for (var i=0; i<this._data.length; i++) {
var lineNumber = i+1;var outLine = new Array;outLine[outLine.length] = '<tr>\n';var outWhile = new Array;var tdId = 'row_autonumber_' + lineNumber;outWhile[outWhile.length] = '<td id="' + tdId + '" width="25" height="' + this._cellHeight[i] + '"valign="bottom" align="right" bgcolor="#DEDBD6" style="border-bottom-color: Black; border-bottom-style: solid; border-bottom-width: 1px;">';outWhile[outWhile.length] = '<table width="100%" height="100%" cellpadding="0" cellspacing="0"><tr><td valign="bottom" align="right" id="' + tdId + '_hack">';outWhile[outWhile.length] = lineNumber + '&nbsp;';outWhile[outWhile.length] = '</td></tr><tr height="3"><td align="middle" valign="bottom" height="3">';outWhile[outWhile.length] = '<span style="cursor:row-resize;" ondragstart="resizeRowStart(\'' + tdId + '\')" ondrag="resizeRow(\'' + tdId + '\')" ondragend="resizeRowEnd(\'' + tdId + '\')"><img src="/_bsImages/spacer.gif" width="10" height="3"></span>';outWhile[outWhile.length] = '</td></tr></table>';outWhile[outWhile.length] = '</td>';for (var j=0; j<numCols; j++) {
var colNumber = j+1;try {
var cellValue   = (this._data[i][j]['value']) ? this._data[i][j]['value'] : '';var formatStyle = this._getStyleStringForCell(i, j);} catch (e) {
continue;}
if (i==0) fullTableWidth += this._cellWidth[j] +1;outWhile[outWhile.length] = '<td id="td_data[' + i + '][' + j + ']" width=' + this._cellWidth[j] + ' height=' + this._cellHeight[i] + ' style="width:' + this._cellWidth[j] + '; height:' + this._cellHeight[i] + ';">';outWhile[outWhile.length] = '<div name="data[' + i + '][' + j + ']" id="data[' + i + '][' + j + ']" ';outWhile[outWhile.length] = 'onkeydown="return ' + this.objectName + '.typing();" ';outWhile[outWhile.length] = 'onclick="'    + this.objectName + '.cellSelect(this);" ';outWhile[outWhile.length] = 'ondblclick="' + this.objectName + '.editCellStart(this);" ';outWhile[outWhile.length] = 'onpaste="'    + this.objectName + '.onPaste(this);" ';outWhile[outWhile.length] = 'style="' + formatStyle + ' position:relative; left:0; top:0; width:100%; height:100%; background-color:white; overflow:hidden; z-index:60;">';outWhile[outWhile.length] = cellValue;outWhile[outWhile.length] = '</div>';outWhile[outWhile.length] = '</td>';}
outLine[outLine.length] = outWhile.join('');outLine[outLine.length] = "</tr>\n";out[out.length] = outLine.join('');addRowLine = '';kSave = i;}
var tableString = '<div style="z-index:50; width:' + this.sheetWidth + 'px; height:' + this.sheedHeight + 'px; overflow: visible;"><table width="' + fullTableWidth + '" id="dataTable" border="1" cellspacing="0" cellpadding="0" bordercolor="#DEDBD6" bgcolor="#FFFFFF">\n';tableString += titleRow.join('');tableString += out.join('');tableString += '</table></div>\n';document.getElementById(this.objectName + '_spreadSheetDiv').innerHTML = tableString;if (reactivateCurrentCell) {
var cell = document.getElementById(reactivateCurrentCell);if (cell) {
this.cellSelect(cell);}
}
}
this._loadToolbar = function() {
bar = new Bs_ButtonBar();bar.useHelpBar = false;btnAddRowAbove = new Bs_Button();btnAddRowAbove.objectName = 'btnAddRowAbove';btnAddRowAbove.title = 'Insert row above';btnAddRowAbove.imgName = 'bs_tblInsertRowAbove';btnAddRowAbove.imgPath = this.buttonsPath;btnAddRowAbove.attachEvent(function(){mySpreadSheet.addRow(true)});bar.addButton(btnAddRowAbove, 'Insert row above');btnAddRowBelow = new Bs_Button();btnAddRowBelow.objectName = 'btnAddRowBelow';btnAddRowBelow.title = 'Insert row below';btnAddRowBelow.imgName = 'bs_tblInsertRowBelow';btnAddRowBelow.imgPath = this.buttonsPath;btnAddRowBelow.attachEvent(function(){mySpreadSheet.addRow(false)});bar.addButton(btnAddRowBelow, 'Insert row below');btnRemoveRow = new Bs_Button();btnRemoveRow.objectName = 'btnRemoveRow';btnRemoveRow.title = 'Remove row';btnRemoveRow.imgName = 'bs_tblRemoveRow';btnRemoveRow.imgPath = this.buttonsPath;btnRemoveRow.attachEvent(function(){mySpreadSheet.removeRow()});bar.addButton(btnRemoveRow, 'Remove row');bar.newGroup();btnAddColLeft = new Bs_Button();btnAddColLeft.objectName = 'btnAddColLeft';btnAddColLeft.title = 'Insert col left';btnAddColLeft.imgName = 'bs_tblInsertColLeft';btnAddColLeft.imgPath = this.buttonsPath;btnAddColLeft.attachEvent(function(){mySpreadSheet.addCol(true)});bar.addButton(btnAddColLeft, 'Insert col left');btnAddColRight = new Bs_Button();btnAddColRight.objectName = 'btnAddColRight';btnAddColRight.title = 'Insert col right';btnAddColRight.imgName = 'bs_tblInsertColRight';btnAddColRight.imgPath = this.buttonsPath;btnAddColRight.attachEvent(function(){mySpreadSheet.addCol(false)});bar.addButton(btnAddColRight, 'Insert col right');btnRemoveCol = new Bs_Button();btnRemoveCol.objectName = 'btnRemoveCol';btnRemoveCol.title = 'Remove col';btnRemoveCol.imgName = 'bs_tblRemoveCol';btnRemoveCol.imgPath = this.buttonsPath;btnRemoveCol.attachEvent(function(){mySpreadSheet.removeCol()});bar.addButton(btnRemoveCol, 'Remove col');bar.newGroup();btnSortAsc = new Bs_Button();btnSortAsc.objectName = 'btnSortAsc';btnSortAsc.title = 'Sort Ascending';btnSortAsc.imgName = 'bs_sortAsc';btnSortAsc.imgPath = this.buttonsPath;btnSortAsc.attachEvent(function(){mySpreadSheet.sortAsc()});bar.addButton(btnSortAsc, 'Sort Ascending');btnSortDesc = new Bs_Button();btnSortDesc.objectName = 'btnSortDesc';btnSortDesc.title = 'Sort Descending';btnSortDesc.imgName = 'bs_sortDesc';btnSortDesc.imgPath = this.buttonsPath;btnSortDesc.attachEvent(function(){mySpreadSheet.sortDesc()});bar.addButton(btnSortDesc, 'Sort Descending');if (this.mayUseClipboard) {
bar.newGroup();btnPaste = new Bs_Button();btnPaste.objectName = 'btnPaste';btnPaste.title = 'Paste';btnPaste.imgName = 'bs_paste';btnPaste.imgPath = this.buttonsPath;btnPaste.attachEvent(function(){mySpreadSheet.onGlobalPaste()});bar.addButton(btnPaste, 'Paste the clipboard at the current position.');}
if (this.mayUseFormat) {
bar.newGroup();btnBold = new Bs_Button();btnBold.objectName = 'btnBold';btnBold.title = 'Bold';btnBold.imgName = 'bs_formatBold_en';btnBold.imgPath = this.buttonsPath;btnBold.group   = 'bold';btnBold.attachEvent(function(){mySpreadSheet.formatBold(true)});bar.addButton(btnBold, 'Format the selected cell as bold.');btnItalic = new Bs_Button();btnItalic.objectName = 'btnItalic';btnItalic.title = 'Italic';btnItalic.imgName = 'bs_formatItalic_en';btnItalic.imgPath = this.buttonsPath;btnItalic.group   = 'italic';btnItalic.attachEvent(function(){mySpreadSheet.formatItalic(true)});bar.addButton(btnItalic, 'Format the selected cell as italic.');btnUnderline = new Bs_Button();btnUnderline.objectName = 'btnUnderline';btnUnderline.title = 'Underline';btnUnderline.imgName = 'bs_formatUnderline';btnUnderline.imgPath = this.buttonsPath;btnUnderline.group   = 'underline';btnUnderline.attachEvent(function(){mySpreadSheet.formatUnderline(true)});bar.addButton(btnUnderline, 'Format the selected cell as underlined.');}
if (this.mayUseAlign) {
bar.newGroup();btnAlignLeft = new Bs_Button();btnAlignLeft.objectName = 'btnAlignLeft';btnAlignLeft.title = 'Align left';btnAlignLeft.imgName = 'bs_alignLeft';btnAlignLeft.imgPath = this.buttonsPath;btnAlignLeft.group   = 'align';btnAlignLeft.attachEvent(formatAlign);bar.addButton(btnAlignLeft, 'Align the selected cell to the left.');btnAlignCenter = new Bs_Button();btnAlignCenter.objectName = 'btnAlignCenter';btnAlignCenter.title = 'Align center';btnAlignCenter.imgName = 'bs_alignCenter';btnAlignCenter.imgPath = this.buttonsPath;btnAlignCenter.group   = 'align';btnAlignCenter.attachEvent(formatAlign);bar.addButton(btnAlignCenter, 'Align the selected cell to the middle.');btnAlignRight = new Bs_Button();btnAlignRight.objectName = 'btnAlignRight';btnAlignRight.title = 'Align right';btnAlignRight.imgName = 'bs_alignRight';btnAlignRight.imgPath = this.buttonsPath;btnAlignRight.group   = 'align';btnAlignRight.attachEvent(formatAlign);bar.addButton(btnAlignRight, 'Align the selected cell to the right.');}
if (this.mayUseWysiwyg) {
bar.newGroup();btnWysiwyg = new Bs_Button();btnWysiwyg.objectName = 'btnWysiwyg';btnWysiwyg.title = 'Wysiwyg Editor';btnWysiwyg.imgName = 'bs_webEdit';btnWysiwyg.imgPath = this.buttonsPath;btnWysiwyg.attachEvent(function(){startWysiwyg()});bar.addButton(btnWysiwyg, 'Open the editor to visually edit this cells content.');}
bar.drawInto(this.objectName + '_toolbarDiv');}
this._getStyleStringForCell = function(row, col) {
var formatStyle = '';if (this._data[row][col]['bold']) {
formatStyle += ' font-weight:bold;'
}
if (this._data[row][col]['italic']) {
formatStyle += ' font-style:italic;'
}
if (this._data[row][col]['underline']) {
formatStyle += ' text-decoration:underline;'
}
if ((this._data[row][col]['align']) && (this._data[row][col]['align'] != '')) {
formatStyle += ' text-align:' + this._data[row][col]['align'] + ';';}
return formatStyle;}
this.updateDataFromFields = function() {
}
this.setDrawStyle = function(drawStyle) {
this._drawStyle = drawStyle;this.updateDataFromFields();this.draw();}
this.save = function() {
if (this.returnType == 'csv') {
var ret = bs_array_toCsv(this._data, ';');} else {
var ret = this._data;}
try {
var evalStr = 'parent.' + this._callbackFunction + '(ret);';eval(evalStr);} catch (e) {
try {
var evalStr = this._callbackFunction + '(ret);';eval(evalStr);} catch (e) {
alert("Could not call callback function.");}
}
}
this.cellSelect = function(cell) {
if (cell != this._currentCell) {
this._cellDeactivate(this._currentCell);}
this._currentCell  = cell;this._currentState = 'active';this._cellActivate(cell);}
this._cellActivate = function(cell) {
var td = document.getElementById('td_' + cell.id);if (td) td.style.border = "2px solid Black";this._updateStyleButtons();var y = this.getCol(cell.id) +1;var x = this.getRow(cell.id) +1;var colTitle = document.getElementById('col_autonumber_' + y);if (colTitle) colTitle.bgColor = '#B5BED6';var rowTitle = document.getElementById('row_autonumber_' + x);if (rowTitle) rowTitle.bgColor = '#B5BED6';document.getElementById(this.objectName + '_fieldSpan').innerText = String.fromCharCode(64 +y) + '' + x;document.getElementById(this.objectName + '_valueSpan').innerText = cell.innerText;}
this._cellDeactivate = function(cell) {
if (cell) {
if (this._currentState == 'edit') {
this.editCellEnd(cell);}
var td = document.getElementById('td_' + cell.id);if (td) td.style.border = "1px solid #DEDBD6";var colTitle = document.getElementById('col_autonumber_' + (this.getCol(cell.id) +1));if (colTitle) colTitle.bgColor = '#DEDBD6';var rowTitle = document.getElementById('row_autonumber_' + (this.getRow(cell.id) +1));if (rowTitle) rowTitle.bgColor = '#DEDBD6';}
}
this.editCellStart = function(cell) {
if (cell != this._currentCell) {
this.cellDeactivate(this._currentCell);}
this._currentCell  = cell;this._currentState = 'edit';this._currentCellLastValue = cell.innerHTML;if (ie) {
cell.style.left = cell.offsetLeft;cell.style.top  = cell.offsetTop;cell.style.position = "absolute";cell.style.overflow = "visible";cell.style.width = 500;cell.style.zIndex = 700;cell.contentEditable = true;cell.focus();} else {
var newVal = window.prompt('Type in the new value', this._currentCellLastValue);if ((newVal == false) || (newVal == null)) return;cell.innerHTML = newVal;this.editCellEnd(cell);}
}
this.editCellEnd = function(cell) {
var row = this.getRow(cell.id);var col = this.getCol(cell.id);this._data[row][col]['value'] = cell.innerHTML;cell.style.position = "relative";cell.style.overflow = "hidden";cell.style.left = 0;cell.style.top = 0;cell.style.width = this._cellWidth[this.getCol(cell.id)];cell.style.zIndex = 50;cell.contentEditable = false;this._currentState = 'active';document.getElementById(this.objectName + '_valueSpan').innerText = cell.innerText;}
this.typing = function() {
if (this._currentState == 'edit') {
var goOn = false;switch (window.event.keyCode) {
case 13:
if (event.shiftKey) {
return true;}
this.editCellEnd(this._currentCell);return false;case 27:
this._currentCell.innerHTML = this._currentCellLastValue;this.editCellEnd(this._currentCell);this.cellSelect(this._currentCell);return false;case 40:
case 9:
case 39:
case 38:
case 37:
this.editCellEnd(this._currentCell);goOn = true;}
if (!goOn) {
return true;}
}
if (event.ctrlKey) {
if (window.event.keyCode == 66) {
this.formatBold(true);this._updateStyleButtons();return false;} else if (window.event.keyCode == 73) {
this.formatItalic(true);this._updateStyleButtons();return false;} else if (window.event.keyCode == 85) {
this.formatUnderline(true);this._updateStyleButtons();return false;} else if (window.event.keyCode == 86) {
this.onGlobalPaste(this._currentCell);return false;}
return true;}
switch (window.event.keyCode) {
case 13:
case 40:
var row = this.getRow(this._currentCell.id);var col = this.getCol(this._currentCell.id);var t = document.getElementById('data[' + (row +1) + '][' + col + ']');if (t) {
this.cellSelect(t);} else {
t = document.getElementById('data[0][' + col + ']');this.cellSelect(t);}
return false;case 9:
case 39:
var row = this.getRow(this._currentCell.id);var col = this.getCol(this._currentCell.id);var t = document.getElementById('data[' + row + '][' + (col +1) + ']');if (t) {
this.cellSelect(t);} else {
if (window.event.keyCode == 9) {
t = document.getElementById('data[' + (row +1) + '][0]');} else {
t = document.getElementById('data[' + (row) + '][0]');}
if (t) {
this.cellSelect(t);} else {
t = document.getElementById('data[0][0]');this.cellSelect(t);}
}
return false;case 38:
case 13:
var row = this.getRow(this._currentCell.id);var col = this.getCol(this._currentCell.id);var t = document.getElementById('data[' + (row -1) + '][' + col + ']');if (t) {
this.cellSelect(t);} else {
t = document.getElementById('data[' + (this.getNumRows() -1) + '][' + col + ']');this.cellSelect(t);}
return false;case 37:
var row = this.getRow(this._currentCell.id);var col = this.getCol(this._currentCell.id);var t = document.getElementById('data[' + row + '][' + (col -1) + ']');if (t) {
this.cellSelect(t);} else {
t = document.getElementById('data[' + (row) + '][' + (this.getNumCols() -1) + ']');if (t) {
this.cellSelect(t);} else {
t = document.getElementById('data[' + (this.getNumRows() -1) + '][' + (this.getNumCols() -1) + ']');this.cellSelect(t);}
}
return false;case 113:
this.editCellStart(this._currentCell);return true;default:
if ((window.event.keyCode >= 32) && (window.event.keyCode <= 126)) {
this.editCellStart(this._currentCell);this.removeContent(this._currentCell);return true;}
}
return false;}
this.onPaste = function(cell) {
event.returnValue = true;}
this.onGlobalPaste = function(cell) {
if (typeof(cell) == 'undefined') cell = this._currentCell;if (bs_isNull(cell)) {
alert('Select a cell first!');return;}
if (ie) {
var clipValOrig = this._readFromClipboard("Text");this.pasteValue(clipValOrig, cell);} else {
var layerDiv = document.getElementById('bs_ss_pasteLayerDiv');layerDiv.style.display = 'block';var pos = getAbsolutePos(document.getElementById(btnPaste._getId()), true);layerDiv.style.left = pos.x + 'px';layerDiv.style.top  = pos.y + 20 + 'px';document.getElementById('bs_ss_pasteLayerTxt').focus();}
}
this.pasteValue = function(value, cell) {
if (typeof(cell) == 'undefined') cell = this._currentCell;if ((value.indexOf("\t") >= 0) || (value.indexOf("\n") >= 0)) {
var cvsUtil = new Bs_CsvUtil();var clipArr = cvsUtil.csvStringToArray(value, 'auto', false, false, false, true);var startRow = this.getRow(cell.id);var startCol = this.getCol(cell.id);for (var i=0; i<clipArr.length; i++) {
for (var j=0; j<clipArr[i].length; j++) {
this.setValue(clipArr[i][j], startRow +i, startCol +j, true);}
}
this.draw();} else {
this.setValue(value, this.getRow(cell.id), this.getCol(cell.id));}
}
this.removeContent = function(cell) {
cell.innerHTML = '';}
this.getRow = function(cellName) {
var posTwo = cellName.indexOf(']');return parseInt(cellName.substring(5, posTwo));}
this.getCol = function(cellName) {
var posTwo = cellName.lastIndexOf('[');return parseInt(cellName.substr(posTwo +1, cellName.length));}
this.getCellCaption = function(cellId) {
return String.fromCharCode(65 + this.getCol(cellId)) + (this.getRow(cellId) +1);}
this.getNumRows = function() {
try {
return this._data.length;} catch (e) {
return 0;}
}
this.getNumCols = function() {
return bs_array_maxSizeOfLevel(this._data, 2);}
this.calculateCellWidth = function() {
var numCols = this.getNumCols();if (typeof(this._cellWidth) == 'undefined') {
this._cellWidth = new Array();}
for (var i=0; i<numCols; i++) {
if (typeof(this._cellWidth[i]) != 'undefined') continue;var t = document.getElementById('col_autonumber_' + (i+1));if (t != null) {
this._cellWidth[i] = t.width;} else {
this._cellWidth[i] = this.defaultCellWidth;}
}
}
this.calculateCellHeight = function() {
var numRows = this.getNumRows();if (typeof(this._cellHeight) == 'undefined') {
this._cellHeight = new Array();}
for (var i=0; i<numRows; i++) {
if (typeof(this._cellHeight[i]) != 'undefined') continue;var t = document.getElementById('row_autonumber_' + (i+1));if (t != null) {
this._cellHeight[i] = t.height;} else {
this._cellHeight[i] = this.defaultCellHeight;}
}
}
this.calculateCellSizes = function() {
var numCols = this.getNumCols();var numRows = this.getNumRows();this._cellWidth  = new Array();this._cellHeight = new Array();var width  = this.defaultCellWidth;var height = this.defaultCellHeight;for (var i=0; i<numCols; i++) {
for (var j=0; j<numRows; j++) {
var cell = document.getElementById('data[' + i + '][' + j + ']');if (cell) {
width  = cell.clientWidth;height = cell.clientHeight;}
if ((!this._cellWidth[i]) || (width > this._cellWidth[i])) {
this._cellWidth[i] = width;}
if ((!this._cellHeight[j]) || (height > this._cellHeight[j])) {
this._cellHeight[j] = height;}
}
}
}
this.emptyValue = function(row, col) {
this.setValue('', row, col);}
this.setValue = function(val, row, col, noRedraw) {
if (this._data.length <= row) {
for (var i=this._data.length; i<=row; i++) {
this.addRow(null, noRedraw);}
}
if (this._data[0].length <= col) {
for (var i=this._data[0].length; i<=col; i++) {
this.addCol(null, noRedraw);}
}
this._data[row][col]['value'] = val;var cell = document.getElementById('data[' + row + '][' + col + ']');if (cell) {
cell.innerHTML = val;this.editCellEnd(cell);this.cellSelect(cell);}
this.updateDataFromFields();if (!noRedraw) this.draw();}
this.formatBold = function(val) {
try {
if (!this._currentCell) throw 'noCellSelected';var row = this.getRow(this._currentCell.id);var col = this.getCol(this._currentCell.id);if (this._setStyle(row, col, 'bold', val)) {
var cell = document.getElementById('data[' + row + '][' + col + ']');val = (val) ? 'bold' : 'normal';if (cell) cell.style.fontWeight = val;}
} catch (e) {
alert(e);}
}
this.formatItalic = function(val) {
try {
if (!this._currentCell) throw 'noCellSelected';var row = this.getRow(this._currentCell.id);var col = this.getCol(this._currentCell.id);if (this._setStyle(row, col, 'italic', val)) {
var cell = document.getElementById('data[' + row + '][' + col + ']');val = (val) ? 'italic' : 'normal';if (cell) cell.style.fontStyle = val;}
} catch (e) {
alert(e);}
}
this.formatUnderline = function(val) {
try {
if (!this._currentCell) throw 'noCellSelected';var row = this.getRow(this._currentCell.id);var col = this.getCol(this._currentCell.id);if (this._setStyle(row, col, 'underline', val)) {
var cell = document.getElementById('data[' + row + '][' + col + ']');val = (val) ? 'underline' : 'none';if (cell) cell.style.textDecoration = val;}
} catch (e) {
alert(e);}
}
this.formatAlign = function(left, center, right) {
var val = '';if (left) {
val = 'left';} else if (center) {
val = 'center';} else if (right) {
val = 'right';}
try {
if (!this._currentCell) throw 'noCellSelected';var row = this.getRow(this._currentCell.id);var col = this.getCol(this._currentCell.id);if (this._setStyle(row, col, 'align', val)) {
if (val != '') {
var cell = document.getElementById('data[' + row + '][' + col + ']');if (cell) cell.style.textAlign = val;}
}
} catch (e) {
alert(e);}
}
this._setStyle = function(row, col, style, val) {
this._data[row][col][style] = val;return true;}
this._updateStyleButtons = function() {
var row = this.getRow(this._currentCell.id);var col = this.getCol(this._currentCell.id);var valBold      = false;var valItalic    = false;var valUnderline = false;var valAlign     = '';try {
if (this._data[row][col]) {
valBold      = (this._data[row][col]['bold']);valItalic    = (this._data[row][col]['italic']);valUnderline = (this._data[row][col]['underline']);valAlign     = (this._data[row][col]['align']);}
} catch (e) {
}
try {
btnBold.setStatus(valBold ? 2 : 1);btnItalic.setStatus(valItalic ? 2 : 1);btnUnderline.setStatus(valUnderline ? 2 : 1);} catch (e) {
}
try {
btnAlignLeft.setStatus((valAlign   == 'left')   ? 2 : 1);btnAlignCenter.setStatus((valAlign == 'center') ? 2 : 1);btnAlignLeft.setStatus((valAlign   == 'right')  ? 2 : 1);} catch (e) {
}
}
this.sortAsc = function() {
if (this._currentCell) {
var colNumber = this.getCol(this._currentCell.id);this._sortData(colNumber, true);this.draw();}
}
this.sortDesc = function() {
if (this._currentCell) {
var colNumber = this.getCol(this._currentCell.id);this._sortData(colNumber, false);this.draw();}
}
this._sortData = function(colNumber, asc) {
this.updateDataFromFields();var tmpArray = new Array();var dataLength = this.getNumRows();var subLength  = this.getNumCols();for (var i=0; i<dataLength; i++) {
tmpArray[i] = this._data[i][colNumber]['value'] + '[?!*]' + i;}
tmpArray.sort();var tmpArrayRank = new Array();for (var i=0; i<dataLength; i++) {
pos = tmpArray[i].lastIndexOf('[?!*]') + 5;tmpArrayRank[i] = tmpArray[i].substr(pos);}
if (!asc) tmpArrayRank.reverse();var tmpData = this._data;this._data = new Array();for (var i=0; i<dataLength; i++) {
this._data[i] = Array();for (var j=0; j<subLength; j++) {
this._data[i][j] = tmpData[tmpArrayRank[i]][j];}
}
}
this.toHtml = function(withStyle) {
if (this.debug) alert("exporting table to html");this.updateDataFromFields();var numCols = this.getNumCols();var out = "<table>\n";for (var i=0; i<this._data.length; i++) {
out += "  <tr>\n";for (var j=0; j<numCols; j++) {
var cellValue = this._data[i][j]['data'];var formatStyle = this._getStyleStringForCell(i, j);out += '    <td style="' + formatStyle + '">' + cellValue + '</td>\n';}
out += "  </tr>\n";}
return out;}
this._readFromClipboard = function(key) {
if (window.clipboardData) {
return window.clipboardData.getData(key);} else {
netscape.security.PrivilegeManager.enablePrivilege('UniversalXPConnect');var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);if (!clip) return;var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);if (!trans) return;trans.addDataFlavor('text/unicode');clip.getData(trans,clip.kGlobalClipboard);var str = new Object();var len = new Object();try { trans.getTransferData('text/unicode',str,len); }
catch(error) { return; }
if (str) {
if (Components.interfaces.nsISupportsWString) str=str.value.QueryInterface(Components.interfaces.nsISupportsWString);else if (Components.interfaces.nsISupportsString) str=str.value.QueryInterface(Components.interfaces.nsISupportsString);else str = null;}
if (str) return(str.data.substring(0,len.value / 2));}
}
}
function editorStart(i, j) {
divEditor.style.display = 'block';formField = document.getElementById('data[' + i + '][' + j + ']');txtEditor = document.getElementById('txtEditor');txtEditor.value = formField.value;editorI = i;editorJ = j;}
function editorSave() {
formField = document.getElementById('data[' + editorI + '][' + editorJ + ']');txtEditor = document.getElementById('txtEditor');formField.value = txtEditor.value;txtEditor.value = '';divEditor.style.display = 'none';}
function editorCancel() {
txtEditor.value = '';divEditor.style.display = 'none';}

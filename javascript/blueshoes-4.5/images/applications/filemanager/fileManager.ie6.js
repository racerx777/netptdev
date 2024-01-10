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
var sunkTitle;var outputFunction = "outputFilesLine";var numCount = false;var viewStyle = 'details';function sinkTitle() {
var curObj = event.srcElement;while (curObj.tagName != "TD" && curObj.tagName != "BODY") curObj=curObj.parentElement;if (curObj.tagName=="BODY") return false;if (curObj.className=="tblSortable") {
curObj.style.borderStyle="inset";sunkTitle = curObj;}
}
function unsinkTitle() {
if (sunkTitle) {
sunkTitle.style.borderStyle = "outset";sunkTitle = null;}
}
var TopElements={
categories:[],
elements:[],
sortCat:1,
sortDir:1
}
function Element(isDir, isLink, name, dir, icon, type, size, lastmod, read) {
this.isDir      = isDir;this.isLink     = isLink;this.name       = name;this.dir        = dir;this.icon       = icon;this.type       = type;this.size       = size;this.lastmod    = lastmod;this.read       = read;}
function Category(field, title, width, tip, sortable, sorter) {
this.field    = field;this.title    = title;this.width    = width;this.tip      = tip;this.sortable = sortable;this.sorter   = sorter;}
function numericSort(a,b) {
return a[TopElements.categories[TopElements.sortCat].field]-b[TopElements.categories[TopElements.sortCat].field];}
function stringSort(a,b) {
aVal=a[TopElements.categories[TopElements.sortCat].field].toLowerCase();bVal=b[TopElements.categories[TopElements.sortCat].field].toLowerCase();if (aVal<bVal) return 1;if (aVal>bVal) return -1;return 0;}
function fileSizeSort(a,b) {
var aVal        = a[TopElements.categories[TopElements.sortCat].field].toLowerCase();var bVal        = b[TopElements.categories[TopElements.sortCat].field].toLowerCase();  var aMeasure    = aVal.substr(aVal.length -2).replace(/ /, ""); //eg "kb" or "b"
  var bMeasure    = bVal.substr(bVal.length -2).replace(/ /, "");
var aMeasureNum = fileSizeSortHelper(aMeasure);var bMeasureNum = fileSizeSortHelper(bMeasure);if (aMeasureNum < bMeasureNum) {
return 1;} else if (aMeasureNum > bMeasureNum) {
return -1;} else {
var aInt = parseFloat(aVal);var bInt = parseFloat(bVal);if (aInt < bInt) {
return 1;} else if (aInt > bInt) {
return -1;} else {
return 0;}
}
}
function fileSizeSortHelper(measure) {
if (measure == "b") return 1;if (measure == "kb") return 2;if (measure == "mb") return 3;if (measure == "gb") return 4;if (measure == "tb") return 5;if (measure == "pb") return 6;if (measure == "eb") return 7;return 0;}
function sortBy(field,targetObj) {
if (field==TopElements.sortCat)
TopElements.sortDir=TopElements.sortDir ? 0 : 1;else {
TopElements.sortDir=1;TopElements.sortCat=field;}
TopElements.elements=TopElements.elements.sort(TopElements.categories[field].sorter);if (TopElements.sortDir) TopElements.elements=TopElements.elements.reverse();drawFileList(document.all[targetObj], viewStyle);}
function drawFileList(targetElement, style) {
if (!style) style = viewStyle;switch (style) {
case 'details':
default:
drawFileListDetails(targetElement);}
}
function drawFileListDetails(target) {
if (TopElements.categories && TopElements.elements) {
var tblSrc = new Array();tblSrc[tblSrc.length] = "<Table width='100%' cellspacing='0' cellpadding='0' border='0' style='cursor: default;'>";tblSrc[tblSrc.length] = "<TR>";if (numCount) tblSrc[tblSrc.length] = "<TD Width=35 Title='Order' Class=tblStatic>Num</TD>";for (var cCat=0; cCat<TopElements.categories.length; cCat++) {
tblSrc[tblSrc.length] = "<TD Width="+TopElements.categories[cCat].width+" NoWrap Class="
+(TopElements.categories[cCat].sortable ? "tblSortable" : "tblStatic")
+" Title='"+TopElements.categories[cCat].tip+"' "
+(TopElements.categories[cCat].sortable ? "onClick='sortBy("+cCat+",\""+target.id+"\")'" : "")+"><NOBR>"
+TopElements.categories[cCat].title
+"<Span Style='font-size:1px;cursor:hand;width:17px;text-align:right'><Img Width=8 Height=7 Src=/_bsImages/applications/filemanager/down.gif Class="
+(TopElements.sortCat==cCat ? (TopElements.sortDir ? "arrDn" : "arrUp")+">" : "arrHide")
+"></Span></NOBR></TD>";}
tblSrc[tblSrc.length] = "</TR>";if (outputFunction != '') {
tblSrc[tblSrc.length] = outputFilesLine();} else {
for (var cElement=0;cElement<TopElements.elements.length;cElement++) {
tblSrc[tblSrc.length] = "<TR>";if (numCount) tblSrc[tblSrc.length] = "<TD Align=Right Class=tblData>"+(cElement+1)+"</TD>";for (var cCat=0; cCat<TopElements.categories.length; cCat++) {
tblSrc[tblSrc.length] = "<TD Class=tblData Align="
+(!isNaN(TopElements.elements[cElement][TopElements.categories[cCat].field]) ? "Right" : "Left")+">"
+TopElements.elements[cElement][TopElements.categories[cCat].field]+"</TD>";}
tblSrc[tblSrc.length] = "</TR>";}
}
tblSrc[tblSrc.length] = "</Table>";target.innerHTML = tblSrc.join('');}
}
function outputFilesLine() {
var ret = new Array();for (var cElement=0;cElement<TopElements.elements.length;cElement++) {
ret[ret.length] = "<TR>";if (numCount) ret[ret.length] = "<TD Align=Right Class=tblData>"+(cElement+1)+"</TD>";ret[ret.length] = "<TD class='fileList' bgcolor='white'>&nbsp;";if (TopElements.elements[cElement].isDir) {
var isEditable = false;} else {
var isEditable = true;}
ret[ret.length] = "<SPAN id='fileElementSpan' style='zoom:100%; background-color:white;' onClick='clickOnItem(this, " + isEditable + ");return false;' onMouseOver='mouseOnItem(this);' onMouseOut='mouseOffItem(this);' oncontextmenu='displayMenu(this); return false;'";if (TopElements.elements[cElement].isDir) {
ret[ret.length] = " onDblClick='location.href=\"" + php_self + "?showPage=file&path=" + relPath + TopElements.elements[cElement].name + "\"; return false; alert(\"not implemented. please open that folder in the tree on the left side.\");'";} else {
ret[ret.length] = " onDblClick='viewFile(this);'";ret[ret.length] = ' alt="Type: ' + TopElements.elements[cElement].type + '\nSize: ' + TopElements.elements[cElement].size + '"';}
ret[ret.length] = ">";var linkk = (TopElements.elements[cElement].isLink) ? '_link' : '';ret[ret.length] = "<img id='fileElementImage' src='" + TopElements.elements[cElement].icon + linkk + ".gif' border='0' align='absmiddle'> " + TopElements.elements[cElement].name;ret[ret.length] = "</SPAN>";ret[ret.length] = "&nbsp;</TD>";if (TopElements.elements[cElement].dir.length > 0) {
ret[ret.length] = "<TD class='fileList' align='left'>&nbsp;" + TopElements.elements[cElement].dir    + "&nbsp;</TD>";}
ret[ret.length] = "<TD class='fileList' align='right'>&nbsp;" + TopElements.elements[cElement].size    + "&nbsp;</TD>";ret[ret.length] = "<TD class='fileList' align='left' >&nbsp;" + TopElements.elements[cElement].type    + "&nbsp;</TD>";ret[ret.length] = "<TD class='fileList' align='left' >&nbsp;" + TopElements.elements[cElement].lastmod + "&nbsp;</TD>";ret[ret.length] = "<TD class='fileList' align='left' >&nbsp;" + TopElements.elements[cElement].read    + "&nbsp;</TD>";ret[ret.length] = "</TR>";}
return ret.join('');}
function displayMenu(obj) {
clickOnItem(obj);menu1.style.leftPos+=10;menu1.style.posLeft=event.clientX;menu1.style.posTop=event.clientY;menu1.style.display="block";fileContextMenuActive = true;menu1.setCapture();}
function rightMouseMenuMouseClick() {
menu1.releaseCapture();menu1.style.display = "none";obj = event.srcElement;if (obj.id.substring(0, 3) != 'mnu') return;if (obj.id == "mnuView") {
viewFile(parent.fileSelected["0"]);} else if (obj.id == "mnuDownload") {
downloadFile(parent.fileSelected["0"]);} else if (obj.id == "mnuDelete") {
deleteFile();} else if (obj.id == "mnuRename") {
renameFile(parent.fileSelected["0"]);} else if (obj.id == "mnuEdit") {
editFile(parent.fileSelected["0"]);} else if (obj.id == "mnuCut") {
cut();} else if (obj.id == "mnuCopy") {
copy();}
}
function isFileEditable() {
}
function clickMenu() {
menu1.releaseCapture();if (fileContextMenuActive) {
menu1.style.display = "none";fileContextMenuActive = false;} else {
unSelectAllItems();}
}
function getRelevantObject() {
var obj = event.srcElement;if ((obj.id.substr(0, 3) == 'mnu') && (obj.id.substr(obj.id.length -3) == 'Img')) {
obj = document.getElementById(obj.id.substr(0, obj.id.length -3));}
return obj;}
function clickOnItem(obj, isEditable) {
unSelectAllItems();selectItem(obj);if (isEditable) {
}
}
function selectItem(obj) {
obj.style.filter = "progid:DXImageTransform.Microsoft.BasicImage(Invert=1)";parent.fileSelected = new Array(obj);parent.frameTop.enableButtonsForFiles();}
function unSelectItem(obj) {
obj.style.filter = "";}
function unSelectAllItems() {
for (var i=0; i < parent.fileSelected.length; ++i) {
parent.fileSelected[i].style.filter = "";}
parent.fileSelected = new Array();parent.frameTop.disableButtonsForFiles();}
function mouseOnItem(obj) {
return;if (true) {
selectItem(obj);}
}
function mouseOffItem(obj) {
return;if (true) {
unSelectItem(obj);}
}
function rightMouseMenuMouseOver() {
var obj = getRelevantObject()
if (obj.id.substr(0, 3) != 'mnu') return;obj.style.filter = "progid:DXImageTransform.Microsoft.BasicImage(Invert=1)";}
function rightMouseMenuMouseOut() {
var obj = getRelevantObject()
if (obj.id.substr(0, 3) != 'mnu') return;obj.style.filter = "";}
function bodyOnClick() {
var obj = event.srcElement;if (obj.id.substr(0, 11) == 'fileElement') {
} else {
unSelectAllItems();menu1.style.display = "none";fileContextMenuActive = false;}
}
function bodyOnContextMenu() {
menu1.setCapture();}

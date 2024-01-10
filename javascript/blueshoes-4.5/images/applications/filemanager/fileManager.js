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
var outputFunction = "outputFilesLine";var numCount = false;var viewStyle = 'details';function drawFileList(targetElement, style) {
if (!style) style = viewStyle;switch (style) {
case 'details':
default:
drawFileListDetails(targetElement);}
}
function drawFileListDetails(target) {
var out = new Array;if (fileList) {
out[out.length] = "";out[out.length] = "";out[out.length] = "";out[out.length] = "";out[out.length] = "";out[out.length] = "<table>";out[out.length] = "<tr>";out[out.length] = "<td colspan='2'>Name</td>";out[out.length] = "<td>Size</td>";out[out.length] = "<td>Type</td>";out[out.length] = "<td>Modified</td>";out[out.length] = "<td>Read</td>";out[out.length] = "</tr>";for (var i=0; i<fileList.length; i++) {
out[out.length] = "<tr>";out[out.length] = "<td><img src='" + fileList[i]['icon'] + ".gif' border='0'></td>";out[out.length] = "<td>" + fileList[i]['name'] + "</td>";out[out.length] = "<td>" + fileList[i]['size'] + "</td>";out[out.length] = "<td>" + fileList[i]['type'] + "</td>";out[out.length] = "<td>" + fileList[i]['lastmod'] + "</td>";out[out.length] = "<td>" + fileList[i]['read'] + "</td>";out[out.length] = "</tr>";}
out[out.length] = "</table>";document.getElementById(target).innerHTML = out.join('');}
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
function bodyOnClick() {
}
function bodyOnContextMenu() {
}

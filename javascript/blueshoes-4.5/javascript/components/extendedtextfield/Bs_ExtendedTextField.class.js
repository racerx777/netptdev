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
function Bs_ExtendedTextField() {
var objectName;this.string;this.values;this.byString = false;this.inputFieldName;this.inputFieldId;this.constructor = function() {
}
this.initByArray = function(values) {
this.inputFieldName = 'bs_etf_hiddenField';this.inputFieldId   = 'bs_etf_hiddenField';this.values = values;}
this.initByString = function(string, array) {
this.inputFieldName = 'bs_etf_hiddenField';this.inputFieldId   = 'bs_etf_hiddenField';this.byString = true;this.string   = string;this.values   = array;}
this.initByField = function(fieldId, array) {
var inputFieldObj   = document.getElementById(fieldId);this.inputFieldName = inputFieldObj.name;this.inputFieldId   = fieldId;this.byString = true;this.string   = inputFieldObj.value
this.values   = array;}
this.renderInto = function(tagId) {
var out = this.render();document.getElementById(tagId).innerHTML = out;}
this.renderReplace = function(tagId) {
var out = this.render();var prevElm = document.getElementById(tagId);var width  = prevElm.offsetWidth;var height = prevElm.offsetHeight;prevElm.outerHTML = out;var newElm = document.getElementById('bs_etf_div');newElm.style.width  = width;newElm.style.height = height;}
this.render = function() {
var out = '';var outWordsHtml = new Array;var outWords     = new Array;if (this.byString) {
var outString = this.string;}
var i = 0;for (var word in this.values) {
outWords[outWords.length] = word;var outWordStr = '';if (this.values[word].length > 0) {
outWordStr += '<span id="bs_etf_spanWord_' + i + '">' + word + '</span>';outWordStr += '<span onclick="' + this.objectName + '.toggleSelect(\'' + i + '\');" contentEditable="false" style="vertical-align: middle;"><img src="/_bsJavascript/components/extendedtextfield/img/dropdownArrow.gif" border="0" align="middle" alt=""></span> ';if (this.byString) {
do {
var pos = outString.search(word);if (pos == -1) break;if (pos > 0) {
var charCode = outString.charCodeAt(pos -1)
if ((charCode >= 97) && (charCode <= 122)) break;if ((charCode >= 65) && (charCode <= 90))  break;if ((charCode >= 48) && (charCode <= 57))  break;}
if (pos < outString.length - word.length) {
var charCode = outString.charCodeAt(pos + word.length)
if ((charCode >= 97) && (charCode <= 122)) break;if ((charCode >= 65) && (charCode <= 90))  break;if ((charCode >= 48) && (charCode <= 57))  break;}
						//outString = outString.replace(word, outWordStr);
outString = outString.substr(0, pos) + outWordStr + outString.substr(pos + word.length);break;} while (true);}
var layer = '';layer += '<div id="bs_etf_divWord_' + i + '" style="display:none; position:absolute; border:1px solid black; background-color:white; cursor:hand; cursor:pointer; padding:0px;" onclick="' + this.objectName + '.chooseWord(\'' + i + '\');">';layer += '<div id="bs_etf_divWord_' + i + '_0" style="padding-left:3px; padding-right:3px; padding-top:1px; padding-bottom:1px;" onmouseover="' + this.objectName + '.optionWordMouseOver('+i+', 0);" onmouseout="' + this.objectName + '.optionWordMouseOut('+i+', 0);">' + word + '</div>';for (var j=1; j<=this.values[word].length; j++) {
if (word == this.values[word][j -1]) continue;layer += '<div id="bs_etf_divWord_' + i + '_' + j + '" style="position:relative; padding-left:3px; padding-right:3px; padding-top:1px; padding-bottom:1px;" onmouseover="' + this.objectName + '.optionWordMouseOver('+i+', '+j+');" onmouseout="' + this.objectName + '.optionWordMouseOut('+i+', '+j+');">' + this.values[word][j -1] + '</div>';}
layer += '</div>';var body = document.getElementsByTagName('body').item(0);body.insertAdjacentHTML('beforeEnd', layer);} else {
outWordStr += word;}
outWordsHtml[outWordsHtml.length] = outWordStr;i++;}
out += outWordsHtml.join(' ');var endOut = '';endOut += '<div id="bs_etf_div" style="border:1px solid black; background-color:white;" contentEditable="true" onblur="' + this.objectName + '.updateHiddenField();" onkeydown="return ' + this.objectName + '.typing();">';if (this.byString) {
endOut += outString;} else {
endOut += out;}
endOut += '</div>';endOut += '<input type="hidden" name="' + this.inputFieldName + '" id="' + this.inputFieldId + '" size="50" value="';if (this.byString) {
endOut += bs_filterForHtml(this.string);} else {
endOut += outWords.join(' ');}
endOut += '">';return endOut;}
this.updateHiddenField = function() {
document.getElementById(this.inputFieldId).value = document.getElementById('bs_etf_div').innerText;}
this.toggleSelect = function(id) {
var divId      = 'bs_etf_divWord_' + id;var wordSpanId = 'bs_etf_spanWord_' + id;var myDiv = document.getElementById(divId);if (myDiv.style.display == 'none') {
myDiv.style.display = 'block';var wordSpanTag = document.getElementById(wordSpanId);var pos = getAbsolutePos(wordSpanTag, false);myDiv.style.top     = pos.y + wordSpanTag.offsetHeight;myDiv.style.left    = pos.x;} else {
myDiv.style.display = 'none';}
}
this.chooseWord = function(id) {
divId  = 'bs_etf_divWord_'  + id;spanId = 'bs_etf_spanWord_' + id;var replaceWith = window.event.srcElement.innerText;document.getElementById(spanId).innerText    = replaceWith;document.getElementById(divId).style.display = 'none';this.updateHiddenField();}
this.optionWordMouseOver = function(i, j) {
divId = 'bs_etf_divWord_' + i + '_' + j;var divElm = document.getElementById(divId);divElm.style.backgroundColor = '#08246B';divElm.style.color = 'white';}
this.optionWordMouseOut = function(i, j) {
divId = 'bs_etf_divWord_' + i + '_' + j;var divElm = document.getElementById(divId);divElm.style.backgroundColor = 'white';divElm.style.color = 'black';}
this.typing = function() {
switch (window.event.keyCode) {
case 13:
return false;default:
return true;}
}
}

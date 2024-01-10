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
var bs_move_elm;var bs_move_startX;var bs_move_startY;var bs_move_left;var bs_move_top;function bs_move_moveStart(elmId) {
bs_move_elm = document.getElementById(elmId);bs_move_startX = event.clientX;bs_move_startY = event.clientY;bs_move_left   = bs_move_elm.offsetLeft;bs_move_top    = bs_move_elm.offsetTop;document.body.attachEvent('onmousemove', bs_move_move);document.body.attachEvent('onmouseup',   bs_move_moveEnd);}
function bs_move_moveEnd() {
document.body.detachEvent('onmousemove', bs_move_move);document.body.detachEvent('onmouseup',   bs_move_moveEnd);}
function bs_move_move() {
var diffLeft = event.clientX - bs_move_startX;var diffTop  = event.clientY - bs_move_startY;var newLeft  = bs_move_left  + diffLeft;var newTop   = bs_move_top   + diffTop;bs_move_elm.style.left = newLeft;bs_move_elm.style.top  = newTop;}
if (typeof(bsWysiwygInstances) == 'undefined') {
var bsWysiwygInstances = new Object;}
if (typeof(bsWysiwygDivToInstanceArray) == 'undefined') {
var bsWysiwygDivToInstanceArray = new Object;}
function bsWysiwygEditorOnBlurWrapper(tagId) {
bsWysiwygDivToInstanceArray[tagId].fireOnBlur();}
function bsWysiwygEditorOnFocusWrapper(tagId) {
bsWysiwygDivToInstanceArray[tagId].fireOnFocus();}
function bsWysiwygEditorEventWrapper(ev, tagId) {
switch (ev) {
case 'editareaMouseOver':
case 'editareaMouseOut':
bsWysiwygDivToInstanceArray[tagId].fireEvent(ev);break;case 'editareaPaste':
return bsWysiwygDivToInstanceArray[tagId]._fireEditareaOnPaste(Event);break;}
}
function bsWysiwygEditorObdWrapper(tagId) {
}
var bsWysiwygSpecialCharsWindow;function bsSpecialCharsOnloadCallback() {
bsWysiwygSpecialCharsOpener.specialCharsOnloadCallback();}
function bsSpecialCharsDoneCallback(code) {
bsWysiwygSpecialCharsOpener.specialCharsDoneCallback(code);}
function Bs_Editor(objectName) {
this._objectName;this.dataType = 'whtml';this.editareaOnPaste = 5;this.style = 'inline';this._inHtmlMode = false;this._value = '';this.formFieldName;this.wysiwygElm;this.wysiwygDoc;this.htmlElm;this.htmlDoc;this.workingElm;this.workingDoc;this.iframeElm;this.outrendered = false;this.lastSelection;this.dataContainer;this.hrefSelector = new Array('/_bsJavascript/components/editor/windowHref.html', 490, 350);this.imageSelector = new Array('/_bsJavascript/components/editor/windowImage.html', 525, 330);this.fgColorSelector = new Array('/_bsJavascript/components/colorpicker/windowColor.html', 500, 600);this.fontSelector = new Array('/_bsJavascript/components/editor/windowFont.html', 420, 300);this.specialCharSelector = new Array('/_bsJavascript/components/editor/windowSpecialChar.html', 450, 300);this.editorCssFile;this.editorCssString = "body { \n      font-family:arial,helvetica; \n      font-size: 12px; \n    }\n    td { \n      font-size: 12px; \n    } \n  ";this.mayResize = true;this.maxWidth;this.maxHeight;this.buttonsWysiwyg;this.buttonsHtml;this.buttonsText;this.buttonsDefaultPath = '/_bsImages/buttons/';this.bsImgPath = '/_bsImages/';this._toolbarStartActivated = false;this._tabset;this._attachedEvents;this.drawInto = function(tagId) {
var tag = document.getElementById(tagId);if (tag == null) return false;tag.innerHTML = this._renderBoxed(tagId);this._afterRender(tagId);return true;}
this.convertField = function(tagId) {
var tag = document.getElementById(tagId);if (tag == null) return false;this.formFieldName = (!bs_isEmpty(tag.name)) ? tag.name : tagId;this._value = tag.value;var width  = tag.offsetWidth;var height = tag.offsetHeight;if (width  == 0) width  = parseInt(tag.currentStyle.width);if (height == 0) height = parseInt(tag.currentStyle.height);tag.outerHTML = this._renderBoxed(tagId, width, height);this._afterRender(tagId);return true;}
this._renderBoxed = function(tagId, width, height) {
var out = new Array;var outerDivId = this._objectName + '_outerDiv';if (typeof(width)  == 'undefined') width  = '100%';if (typeof(height) == 'undefined') height = '100%';out[out.length] = '<div id="' + outerDivId + '" style="position:relative; width:' + width + '; height:' + height + '; border:2px solid outset; background-color:white;">';out[out.length] = this._getDrawnFormField();out[out.length] = this._getDrawnButtonBar();out[out.length] = this._getDrawnIframe(tagId);out[out.length] = this._drawStatusBar();out[out.length] = '</div>';return out.join('')
}
this._afterRender = function(tagId) {
var outerDivId = this._objectName + '_outerDiv';this.iframeElm  = document.getElementById(this._objectName + '_iframe');this.wysiwygDoc = this.iframeElm.contentWindow.document;this.htmlDoc    = this.wysiwygDoc;if (!moz) {
this.wysiwygDoc.designMode = "on";this.wysiwygDoc = this.iframeElm.contentWindow.document;}
this._setupIframeDoc();this._setupResizeGrip(outerDivId);if (moz) {
this.wysiwygDoc.designMode = "on";}
this.wysiwygElm = this.wysiwygDoc.getElementsByTagName('body').item(0);this.htmlElm    = this.wysiwygElm;this.workingElm = this.wysiwygElm;this.workingDoc = this.wysiwygDoc;this._afterDrawStuff(false);this._attachEditorEvents(tagId);}
this.drawAsToolbar = function(editableAreaId, startActivated) {
this.style = 'toolbar';this.wysiwygElm = document.getElementById(editableAreaId);this.workingElm = this.wysiwygElm;this.wysiwygDoc = document;if (this.workingElm == null) return false;if (startActivated) this._toolbarStartActivated = true;this._value = this.wysiwygElm.innerHTML;this._attachEditorEvents(editableAreaId);this.mayResize = false;var out = new Array;var outerDivId = this._objectName + '_outerDiv';out[out.length] = '<div id="' + outerDivId + '"';out[out.length] = ' style="';if (this._toolbarStartActivated) {
out[out.length] = ' display:block;';} else {
out[out.length] = ' display:none;';}
out[out.length] = ' position:absolute; left;50; top:50; width:446;';out[out.length] = ' border:1px solid gray;"';out[out.length] = '>';out[out.length] = '<div id="' + this._objectName + '_titleBar" style="width:100%; padding:3px; cursor:default; background-color:#08246B; color:white; font-family:verdana,arial; font-size:12px; font-weight:bold;">';out[out.length] = '<div onclick="' + this._objectName + '.toolbarButtonClose();" style="position:absolute; width:20px; display:inline; text-align:right; right:5px;">x</div>';out[out.length] = '<div ondragstart="return false;" onmousedown="bs_move_moveStart(\'' + outerDivId + '\');"><nobr>BlueShoes Wysiwyg Toolbar</nobr></div>';out[out.length] = '</div>';out[out.length] = this._getDrawnFormField();out[out.length] = this._getDrawnButtonBar();out[out.length] = this._getDrawnIframe();out[out.length] = this._drawStatusBar();var body = document.getElementsByTagName('body').item(0);body.insertAdjacentHTML('beforeEnd', out.join(''));this.iframeElm  = document.getElementById(this._objectName + '_iframe');this.htmlDoc = this.iframeElm.contentWindow.document;this._setupIframeDoc();this._setupResizeGrip(outerDivId);this.wysiwygElm = document.getElementById(editableAreaId);this.workingElm = this.wysiwygElm;this.wysiwygDoc = document;this.workingDoc = this.wysiwygDoc;this.htmlElm = this.htmlDoc.getElementsByTagName('body').item(0);this._afterDrawStuff(true);return true;}
this.setValue = function(str) {
this._value = str;if (this.outrendered) {
if ((this.dataType == 'text') || this._inHtmlMode) {
this.wysiwygElm.innerText = str;} else {
this.wysiwygElm.innerHTML = str;}
}
return true;}
this.getValue = function() {
return this._value;}
this.insertSpecialChar = function(code) {
var r = this.workingDoc.selection.createRange();if (this._inHtmlMode) {
r.text = code;} else {
r.pasteHTML(code);}
}
this.insertString = function(str) {
var r = this.workingDoc.selection.createRange();if (this._inHtmlMode) {
r.text = str;} else {
r.pasteHTML(str);}
}
this.fireOnBlur = function() {
if (!bs_isNull(this.wysiwygElm)) {
if (this._inHtmlMode) {
this._value = this.htmlElm.innerText;this.wysiwygElm.innerHTML = this._value;} else {
this._value = this.wysiwygElm.innerHTML;}
if (typeof(this.formFieldName) != 'undefined') {
var fld = document.getElementById(this.formFieldName);if (typeof(fld) != 'unknown') fld.value = this._value;}
}
this.fireEvent('editEnd');}
this.fireOnFocus = function() {
if (this.style == 'toolbar') {
this.toggleToolbar(false);}
this.fireEvent('editStart');}
this._fireEditareaOnPaste = function(ev) {
if (typeof(this.editareaOnPaste) == 'function') {
var status = this.editareaOnPaste();if (typeof(status) == 'string') {
this.insertString(clipValStrip)
}
ev.returnValue = false;return false;} else {
switch (this.editareaOnPaste) {
case 1:
alert('Sorry, no pasting allowed.');case 0:
ev.returnValue = false;return false;break;case 5:
ev.returnValue = true;return true;break;default:
var clipValOrig  = window.clipboardData.getData("Text");var clipValStrip = bs_stripTags(clipValOrig);if ((clipValOrig == clipValStrip) || (this.editareaOnPaste == 2)) {
ev.returnValue = true;return true;} else {
switch (this.editareaOnPaste) {
case 3:
alert("Formatting tags have been removed from the clipboard.");case 2:
this.insertString(clipValStrip)
ev.returnValue = false;return false;break;case 4:
var status = confirm("The pasted content includes formatting tags. Click OK to paste your content with them. Click cancel to have them removed.");if (!status) {
this.insertString(clipValStrip)
ev.returnValue = false;return false;} else {
ev.returnValue = true;}
break;}
}
}
}
return true;}
this.createLink = function(paramObj) {
var propArr   = new Array('href', 'target', 'name', 'title', 'id', 'class', 'style');var tempRange = this.workingDoc.selection.createRange();if ((this.dataType == 'whtml') && !this._inHtmlMode) {
if (bs_isEmpty(paramObj.href)) {
tempRange.execCommand('UnLink', false, null);return;}
var tempElm = findWrappingElement('A', tempRange);if (tempElm == false) {
if (bs_isEmpty(paramObj['value'])) {
if (tempRange.htmlText == '') {
tempRange.expand('word');}
var htmlText = tempRange.htmlText;if (typeof(htmlText) == 'string') {
if (htmlText.substr(htmlText.length -1) == ' ') {
tempRange.moveEnd('character', -1)
}
}
paramObj['value'] = tempRange.htmlText;}
tempRange.execCommand('CreateLink', false, paramObj.href);tempElm = findWrappingElement('A', tempRange);}
if (tempElm == false) {
if (bs_isEmpty(paramObj['value'])) {
if (tempRange.htmlText == '') {
tempRange.expand('word');}
var htmlText = tempRange.htmlText;if (typeof(htmlText) == 'string') {
if (htmlText.substr(htmlText.length -1) == ' ') {
tempRange.moveEnd('character', -1)
}
}
paramObj['value'] = tempRange.htmlText;}
var code = '<a';for (var i=0; i<propArr.length; i++) {
if (!bs_isEmpty(paramObj[propArr[i]])) code += ' ' + propArr[i] + '="' + paramObj[propArr[i]] + '"';}
code += '>' + paramObj['value'] + '</a>';tempRange.pasteHTML(code);tempRange.select();} else {
for (var i=0; i<propArr.length; i++) {
if (!bs_isEmpty(paramObj[propArr[i]])) tempElm.setAttribute(propArr[i], paramObj[propArr[i]]);}
if (!bs_isEmpty(paramObj['value'])) tempElm.innerHTML = paramObj['value'];}
} else {
var r2 = expandSelectionToSimpleTag(tempRange, 'a');if (r2.text != tempRange.text) tempRange = r2;if (bs_isEmpty(paramObj.href)) {
var code = '';} else {
var code = '<a';for (var i=0; i<propArr.length; i++) {
if (!bs_isEmpty(paramObj[propArr[i]])) code += ' ' + propArr[i] + '="' + paramObj[propArr[i]] + '"';}
code += '>';}
tempRange.text = code;tempRange.select();}
}
this.createImage = function(paramObj) {
var propArr   = new Array('src', 'alt', 'width', 'height', 'hspace', 'vspace', 'border', 'align', 'name', 'title', 'id', 'class', 'style');var tempRange = this.workingDoc.selection.createRange();if ((this.dataType == 'whtml') && !this._inHtmlMode) {
if (this.workingDoc.selection.type == "Control") {
} else {
tempRange.execCommand('InsertImage', false, paramObj.src);var tempRange = this.workingDoc.selection.createRange();}
tempElm = tempRange(0);if (tempElm != false) {
for (var i=0; i<propArr.length; i++) {
if (!bs_isEmpty(paramObj[propArr[i]])) tempElm.setAttribute(propArr[i], paramObj[propArr[i]]);}
}
} else {
var r2 = expandSelectionToSimpleTag(tempRange, 'img');if (r2.text != tempRange.text) tempRange = r2;var code = '<img';for (var i=0; i<propArr.length; i++) {
if (!bs_isEmpty(paramObj[propArr[i]])) code += ' ' + propArr[i] + '="' + paramObj[propArr[i]] + '"';}
code += '>';tempRange.text = code;tempRange.select();}
}
this.setFgColor = function(hexCode) {
this.workingDoc.execCommand('ForeColor', false, hexCode);}
this.setFontOption = function(option, b) {
var current = this.workingDoc.queryCommandValue(option);if (current && !b) {
this.workingDoc.execCommand(option, false, false);} else if (!current && b) {
this.workingDoc.execCommand(option, false, true);}
}
this.setFont = function(paramObj) {
if (!bs_isEmpty(paramObj.fontFace)) this.workingDoc.execCommand('FontName', false, paramObj.fontFace);if (!bs_isEmpty(paramObj.fontSize)) this.workingDoc.execCommand('FontSize', false, paramObj.fontSize);this.setFontOption('Bold',          (paramObj.fontStyle.indexOf('Bold')   != -1));this.setFontOption('Italic',        (paramObj.fontStyle.indexOf('Italic') != -1));this.setFontOption('Underline',     paramObj.underline);this.setFontOption('StrikeThrough', paramObj.strikeThrough);this.setFontOption('SuperScript',   paramObj.superScript);this.setFontOption('SubScript',     paramObj.subScript);}
this.callbackWindowHref = function() {
var paramObj  = new Object;var tempRange = this.workingDoc.selection.createRange();if ((this.dataType == 'html') || this._inHtmlMode) {
var r2 = expandSelectionToSimpleTag(tempRange, 'a');if ((r2.text != tempRange.text) && !bs_isEmpty(r2.text)) {
r2.select();paramObj = bs_parseSimpleTagProps(r2.text);paramObj['value'] = 'ggg';}
} else {
var tempElm = findWrappingElement('A', tempRange);if (tempElm != false) {
paramObj.href     = (!bs_isEmpty(tempElm.getAttribute('href')))   ? tempElm.getAttribute('href')   : '';paramObj.target   = (!bs_isEmpty(tempElm.getAttribute('target'))) ? tempElm.getAttribute('target') : '';paramObj.name     = (!bs_isEmpty(tempElm.getAttribute('name')))   ? tempElm.getAttribute('name')   : '';paramObj.title    = (!bs_isEmpty(tempElm.getAttribute('title')))  ? tempElm.getAttribute('title')  : '';paramObj.id       = (!bs_isEmpty(tempElm.getAttribute('id')))     ? tempElm.getAttribute('id')     : '';paramObj['class'] = (!bs_isEmpty(tempElm.getAttribute('class')))  ? tempElm.getAttribute('class')  : '';paramObj.style    = (!bs_isEmpty(tempElm.getAttribute('style')))  ? tempElm.getAttribute('style')  : '';paramObj['value'] = tempElm.innerHTML;} else {
if (typeof(tempRange.text) == 'undefined') {
paramObj['value'] = tempRange.item(0).outerHTML;} else {
paramObj['value'] = tempRange.text;}
}
}
return paramObj;}
this.callbackWindowImage = function() {
var paramObj = new Object;var tempRange = this.workingDoc.selection.createRange();if (typeof(tempRange.text) == 'undefined') {
var tempElm = tempRange(0);if (tempElm != false) {
paramObj.src      = (!bs_isEmpty(tempElm.getAttribute('src')))       ? tempElm.getAttribute('src')    : '';paramObj.alt      = (!bs_isEmpty(tempElm.getAttribute('alt')))       ? tempElm.getAttribute('alt')    : '';paramObj.width    = (!bs_isEmpty(tempElm.getAttribute('width')))     ? tempElm.getAttribute('width')  : '';paramObj.height   = (!bs_isEmpty(tempElm.getAttribute('height')))    ? tempElm.getAttribute('height') : '';paramObj.hspace   = (!bs_isEmpty(tempElm.getAttribute('hspace')))    ? tempElm.getAttribute('hspace') : '';paramObj.vspace   = (!bs_isEmpty(tempElm.getAttribute('vspace')))    ? tempElm.getAttribute('vspace') : '';paramObj.border   = (!bs_isEmpty(tempElm.getAttribute('border')))    ? tempElm.getAttribute('border') : '';paramObj.align    = (!bs_isEmpty(tempElm.getAttribute('align')))     ? tempElm.getAttribute('align')  : '';paramObj.name     = (!bs_isEmpty(tempElm.getAttribute('name')))      ? tempElm.getAttribute('name')   : '';paramObj.title    = (!bs_isEmpty(tempElm.getAttribute('title')))     ? tempElm.getAttribute('title')  : '';paramObj.id       = (!bs_isEmpty(tempElm.getAttribute('id')))        ? tempElm.getAttribute('id')     : '';paramObj['class'] = (!bs_isEmpty(tempElm.getAttribute('class')))     ? tempElm.getAttribute('class')  : '';paramObj.style    = (!bs_isEmpty(tempElm.getAttribute('style')))     ? tempElm.getAttribute('style')  : '';}
} else {
if ((this.dataType == 'html') || this._inHtmlMode) {
var r2 = expandSelectionToSimpleTag(tempRange, 'img');if ((r2.text != tempRange.text) && !bs_isEmpty(r2.text)) {
paramObj = bs_parseSimpleTagProps(r2.text);r2.select();}
}
}
if (typeof(this.imageSelector[3]) != 'undefined') {
paramObj.imageBrowser = this.imageSelector[3];}
return paramObj;}
this.callbackWindowColor = function() {
var currentColor = this.workingDoc.queryCommandValue('ForeColor');currentColor = currentColor.toString(16);for (var i=6-currentColor.length; i>0; i--) {
currentColor = '0' + '' + currentColor;}
var paramObj = new Object;paramObj.color = currentColor;return paramObj;}
this.callbackWindowFont = function() {
var paramObj = new Object;paramObj.fontFace      = this.workingDoc.queryCommandValue('FontName');var fontStyle = new Array;if (this.workingDoc.queryCommandValue('Bold'))   fontStyle[fontStyle.length] = 'Bold';if (this.workingDoc.queryCommandValue('Italic')) fontStyle[fontStyle.length] = 'Italic';paramObj.fontStyle     = fontStyle.join(' ');paramObj.fontSize      = this.workingDoc.queryCommandValue('FontSize');paramObj.underline     = this.workingDoc.queryCommandValue('Underline');paramObj.strikeThrough = this.workingDoc.queryCommandValue('StrikeThrough');paramObj.superScript   = this.workingDoc.queryCommandValue('Superscript');paramObj.subScript     = this.workingDoc.queryCommandValue('Subscript');try {
if (this.lastSelection.text == '') {
this.lastSelection.expand('word');}
paramObj.previewText = this.lastSelection.text;} catch (e) {
}
return paramObj;}
this.attachEvent = function(trigger, yourEvent) {
if (typeof(this._attachedEvents) == 'undefined') {
this._attachedEvents = new Array();}
if (typeof(this._attachedEvents[trigger]) == 'undefined') {
this._attachedEvents[trigger] = new Array(yourEvent);} else {
this._attachedEvents[trigger][this._attachedEvents[trigger].length] = yourEvent;}
}
this.hasEventAttached = function(trigger) {
return (this._attachedEvents && this._attachedEvents[trigger]);}
this.fireEvent = function(trigger) {
if (this._attachedEvents && this._attachedEvents[trigger]) {
var e = this._attachedEvents[trigger];if ((typeof(e) == 'string') || (typeof(e) == 'function')) {
e = new Array(e);}
for (var i=0; i<e.length; i++) {
if (typeof(e[i]) == 'function') {
e[i](this);} else if (typeof(e[i]) == 'string') {
eval(e[i]);}
}
}
}
this.toolbarButtonClicked = function(btnObj) {
try {
if (moz) {
this.workingElm.contentWindow.focus();} else {
this.workingElm.focus();}
} catch (e) {
}
switch (btnObj.action) {
case 'Bold':
case 'Italic':
case 'Underline':
case 'Undo':
case 'Redo':
case 'Cut':
case 'Copy':
case 'Paste':
case 'JustifyLeft':
case 'JustifyCenter':
case 'JustifyRight':
case 'InsertOrderedList':
case 'InsertUnorderedList':
case 'Outdent':
case 'Indent':
case 'UnLink':
try {
this.workingDoc.execCommand(btnObj.action, false, null);} catch (e) {
}
break;case 'CreateLink':
var url = this.hrefSelector[0] + '?objectName=' + this._objectName;url += '&callbackLoad=callbackWindowHref&callbackSave=createLink';if (moz) {
window.open(url, "HrefSelector", "width=" + this.hrefSelector[1] + ",height=" + this.hrefSelector[2] + ",left=0,top=0,scrollbars=no,status=no,toolbar=no,resizable=no,location=no,hotkeys=no,dependent=yes");} else {
var ret = window.showModalDialog(url, this.callbackWindowHref(), "dialogWidth:"  + this.hrefSelector[1] + "px; dialogHeight:" + this.hrefSelector[2] + "px;");if (ret) this.createLink(ret);}
break;case 'InsertImage':
var url = this.imageSelector[0] + '?objectName=' + this._objectName;url += '&callbackLoad=callbackWindowImage&callbackSave=createImage';if (moz) {
window.open(url, "ImageSelector", "width=" + this.imageSelector[1] + ",height=" + this.imageSelector[2] + ",left=0,top=0,scrollbars=no,status=no,toolbar=no,resizable=no,location=no,hotkeys=no,dependent=yes");} else {
var ret = window.showModalDialog(url, this.callbackWindowImage(), "dialogWidth:"  + this.imageSelector[1] + "px; dialogHeight:" + this.imageSelector[2] + "px;");if (ret) this.createImage(ret);}
break;case 'ForeColor':
var url = this.fgColorSelector[0] + '?objectName=' + this._objectName;url += '&callbackLoad=callbackWindowColor&callbackSave=setFgColor';if (moz) {
window.open(url, "FgColorSelector", "width=" + this.fgColorSelector[1] + ",height=" + this.fgColorSelector[2] + ",left=0,top=0,scrollbars=no,status=no,toolbar=no,resizable=no,location=no,hotkeys=no,dependent=yes");} else {
var ret = window.showModalDialog(url, this.callbackWindowColor(), "dialogWidth:"  + this.fgColorSelector[1] + "px; dialogHeight:" + this.fgColorSelector[2] + "px;");if (ret) this.setFgColor(ret);}
break;case 'Color':
var url = this.fgColorSelector[0] + '?objectName=' + this._objectName;url += '&callbackLoad=false&callbackSave=insertString';if (moz) {
window.open(url, "ColorSelector", "width=" + this.fgColorSelector[1] + ",height=" + this.fgColorSelector[2] + ",left=0,top=0,scrollbars=no,status=no,toolbar=no,resizable=no,location=no,hotkeys=no,dependent=yes");} else {
var ret = window.showModalDialog(url, this.callbackWindowColor(), "dialogWidth:"  + this.fgColorSelector[1] + "px; dialogHeight:" + this.fgColorSelector[2] + "px;");if (ret) this.insertString(ret);}
break;case 'Font':
this.lastSelection = this.workingDoc.selection.createRange();var url = this.fontSelector[0] + '?objectName=' + this._objectName;url += '&callbackLoad=callbackWindowFont&callbackSave=setFont';if (moz) {
window.open(url, "fontSelector", "width=" + this.fontSelector[1] + ",height=" + this.fontSelector[2] + ",left=0,top=0,scrollbars=no,status=no,toolbar=no,resizable=no,location=no,hotkeys=no,dependent=yes");} else {
var ret = window.showModalDialog(url, this.callbackWindowFont(), "dialogWidth:"  + this.fontSelector[1] + "px; dialogHeight:" + this.fontSelector[2] + "px;");if (ret) this.setFont(ret);}
break;case 'SpecialChars':
var url = this.specialCharSelector[0] + '?objectName=' + this._objectName;url += '&callbackLoad=callbackWindowSpecialChars&callbackSave=insertSpecialChar';if (moz) {
window.open(url, "specialCharSelector", "width=" + this.specialCharSelector[1] + ",height=" + this.specialCharSelector[2] + ",left=0,top=0,scrollbars=no,status=no,toolbar=no,resizable=no,location=no,hotkeys=no,dependent=yes");} else {
var ret = window.showModalDialog(url, true, "dialogWidth:"  + this.specialCharSelector[1] + "px; dialogHeight:" + this.specialCharSelector[2] + "px;");if (ret) this.insertSpecialChar(ret);}
break;case 'x':
this.workingDoc.execCommand('FontName', true, 'arial');break;default:
window[btnObj.action]();}
}
this.loadButtonsText = function() {
var buttons = new Array();this.buttonsText = buttons;buttons['cut'] = new Array();buttons['cut'].action   = 'Cut';buttons['cut'].imgName  = 'bs_cut';buttons['copy'] = new Array();buttons['copy'].action   = 'Copy';buttons['copy'].imgName  = 'bs_copy';buttons['paste'] = new Array();buttons['paste'].action   = 'Paste';buttons['paste'].imgName  = 'bs_paste';var i = buttons.length;buttons[i] = '__SEPARATOR__';buttons['undo'] = new Array();buttons['undo'].action   = 'Undo';buttons['undo'].imgName  = 'bs_undo';buttons['redo'] = new Array();buttons['redo'].action   = 'Redo';buttons['redo'].imgName  = 'bs_redo';var i = buttons.length;buttons[i] = '__SEPARATOR__';}
this._drawButtonBarText = function() {
toolbarText = new Bs_ButtonBar();toolbarText.useHelpBar = 'toolbarHelptext';if (typeof(this.buttonsText) == 'undefined') this.loadButtonsText();this._drawButtonBarHelper(this.buttonsText, 'toolbarText', 'text');}
this.loadButtonsWysiwyg = function() {
var buttons = new Array();this.buttonsWysiwyg = buttons;buttons['cut'] = new Array();buttons['cut'].action   = 'Cut';buttons['cut'].imgName  = 'bs_cut';buttons['copy'] = new Array();buttons['copy'].action   = 'Copy';buttons['copy'].imgName  = 'bs_copy';buttons['paste'] = new Array();buttons['paste'].action   = 'Paste';buttons['paste'].imgName  = 'bs_paste';var i = buttons.length;buttons[i] = '__SEPARATOR__';buttons['undo'] = new Array();buttons['undo'].action   = 'Undo';buttons['undo'].imgName  = 'bs_undo';buttons['redo'] = new Array();buttons['redo'].action   = 'Redo';buttons['redo'].imgName  = 'bs_redo';var i = buttons.length;buttons[i] = '__SEPARATOR__';buttons['bold'] = new Array();buttons['bold'].action   = 'Bold';buttons['italic'] = new Array();buttons['italic'].action   = 'Italic';buttons['underline'] = new Array();buttons['underline'].action   = 'Underline';buttons['underline'].imgName  = 'bs_formatUnderline';var i = buttons.length;buttons[i] = '__SEPARATOR__';buttons['font'] = new Array();buttons['font'].action   = 'Font';buttons['font'].imgName  = 'bs_font';buttons['forecolor'] = new Array();buttons['forecolor'].action   = 'ForeColor';buttons['forecolor'].imgName  = 'bs_fgColor';var i = buttons.length;buttons[i] = '__SEPARATOR__';buttons['justifyleft'] = new Array();buttons['justifyleft'].action   = 'JustifyLeft';buttons['justifyleft'].imgName  = 'bs_alignLeft';buttons['justifycenter'] = new Array();buttons['justifycenter'].action   = 'JustifyCenter';buttons['justifycenter'].imgName  = 'bs_alignCenter';buttons['justifyright'] = new Array();buttons['justifyright'].action   = 'JustifyRight';buttons['justifyright'].imgName  = 'bs_alignRight';var i = buttons.length;buttons[i] = '__SEPARATOR__';buttons['insertorderedlist'] = new Array();buttons['insertorderedlist'].action   = 'InsertOrderedList';buttons['insertorderedlist'].imgName  = 'bs_ol';buttons['insertunorderedlist'] = new Array();buttons['insertunorderedlist'].action   = 'InsertUnorderedList';buttons['insertunorderedlist'].imgName  = 'bs_ul';var i = buttons.length;buttons[i] = '__SEPARATOR__';buttons['outdent'] = new Array();buttons['outdent'].action   = 'Outdent';buttons['outdent'].imgName  = 'bs_outdent';buttons['indent'] = new Array();buttons['indent'].action   = 'Indent';buttons['indent'].imgName  = 'bs_indent';var i = buttons.length;buttons[i] = '__SEPARATOR__';buttons['createlink'] = new Array();buttons['createlink'].action   = 'CreateLink';buttons['createlink'].imgName  = 'bs_createLink';buttons['unlink'] = new Array();buttons['unlink'].action   = 'UnLink';buttons['unlink'].imgName  = 'bs_unLink';var i = buttons.length;buttons[i] = '__SEPARATOR__';buttons['insertimage'] = new Array();buttons['insertimage'].action   = 'InsertImage';buttons['insertimage'].imgName  = 'bs_image';buttons['specialchars'] = new Array();buttons['specialchars'].action   = 'SpecialChars';buttons['specialchars'].imgName  = 'bs_specialChars';var i = buttons.length;buttons[i] = '__SEPARATOR__';}
this._drawButtonBarWysiwyg = function() {
toolbarWysiwyg = new Bs_ButtonBar();toolbarWysiwyg.useHelpBar = 'toolbarHelptext';if (typeof(this.buttonsWysiwyg) == 'undefined') this.loadButtonsWysiwyg();this._drawButtonBarHelper(this.buttonsWysiwyg, 'toolbarWysiwyg', 'wysiwyg');}
this.loadButtonsHtml = function() {
var buttons = new Array();this.buttonsHtml = buttons;buttons['cut'] = new Array();buttons['cut'].action   = 'Cut';buttons['cut'].imgName  = 'bs_cut';buttons['copy'] = new Array();buttons['copy'].action   = 'Copy';buttons['copy'].imgName  = 'bs_copy';buttons['paste'] = new Array();buttons['paste'].action   = 'Paste';buttons['paste'].imgName  = 'bs_paste';var i = buttons.length;buttons[i] = '__SEPARATOR__';buttons['undo'] = new Array();buttons['undo'].action   = 'Undo';buttons['undo'].imgName  = 'bs_undo';buttons['redo'] = new Array();buttons['redo'].action   = 'Redo';buttons['redo'].imgName  = 'bs_redo';var i = buttons.length;buttons[i] = '__SEPARATOR__';buttons['createlink'] = new Array();buttons['createlink'].action   = 'CreateLink';buttons['createlink'].imgName  = 'bs_createLink';buttons['insertimage'] = new Array();buttons['insertimage'].action   = 'InsertImage';buttons['insertimage'].imgName  = 'bs_image';buttons['specialchars'] = new Array();buttons['specialchars'].action   = 'SpecialChars';buttons['specialchars'].imgName  = 'bs_specialChars';buttons['color'] = new Array();buttons['color'].action   = 'Color';buttons['color'].imgName  = 'bs_bgColor';}
this._drawButtonBarHtml = function() {
toolbarHtml = new Bs_ButtonBar();toolbarHtml.useHelpBar = 'toolbarHelptext';if (typeof(this.buttonsHtml) == 'undefined') this.loadButtonsHtml();this._drawButtonBarHelper(this.buttonsHtml, 'toolbarHtml', 'html');}
this._drawButtonBarHelper = function(buttonArr, toolbarName, buttonNamePrefix) {
var lastWasSeparator = true;for (var i in buttonArr) {
if (bs_isNull(buttonArr[i]) || (typeof(buttonArr[i]) != 'object')) {
continue;} else if (buttonArr[i] == '__SEPARATOR__') {
if (lastWasSeparator) continue;eval(toolbarName + ".newGroup();");lastWasSeparator = true;} else {
lastWasSeparator = false;if (typeof(buttonArr[i]['title']) == 'undefined') {
if (!bs_isEmpty(bsWyLa['btn'][i + '_title'])) buttonArr[i]['title'] = bsWyLa['btn'][i + '_title'];}
if (typeof(buttonArr[i]['helpText']) == 'undefined') {
if (!bs_isEmpty(bsWyLa['btn'][i + '_helpText'])) buttonArr[i]['helpText'] = bsWyLa['btn'][i + '_helpText'];}
if (typeof(buttonArr[i]['imgName']) == 'undefined') {
if (!bs_isEmpty(bsWyLa['btn'][i + '_imgName'])) buttonArr[i]['imgName'] = bsWyLa['btn'][i + '_imgName'];}
var varName = this._objectName + '_' + buttonNamePrefix + 'Button' + i;var tempButton = new Bs_Button();tempButton.objectName = varName;tempButton.action  = buttonArr[i]['action'];tempButton.title   = buttonArr[i]['title'];tempButton.imgName = buttonArr[i]['imgName'];if (typeof(buttonArr[i]['imgPath']) != 'undefined') {
tempButton.imgPath = buttonArr[i]['imgPath'];} else {
tempButton.imgPath = this.buttonsDefaultPath;}
if (typeof(buttonArr[i]['group']) != 'undefined') {
tempButton.group = buttonArr[i]['group'];}
tempButton.attachEvent(this._objectName + ".toolbarButtonClicked(this);");eval(varName + ' = tempButton;');eval(toolbarName + ".addButton(" + varName + ", \"" + buttonArr[i]['helpText'] + "\");");}
}
}
this._getDrawnButtonBar = function() {
var out = new Array();out[out.length] = '<div id="' + this._objectName + '_buttonbar" style="background-color:menu; padding:3px;">';if (this.dataType == 'whtml') {
out[out.length] = '<div id="' + this._objectName + '_tabset" style="background-color:menu;">';out[out.length] = '	<div id="' + this._objectName + '_tabset_tabs"></div>';out[out.length] = '	<div id="' + this._objectName + '_tabset_content" class="tabsetContentDiv">';}
switch (this.dataType) {
case 'whtml':
out[out.length] = '<div id="' + this._objectName + '_registerWysiwygButtons" style="display:inline;"></div>';out[out.length] = '<div id="' + this._objectName + '_registerHtmlButtons" style="display:inline;"></div>';this._drawButtonBarWysiwyg();this._drawButtonBarHtml();break;case 'html':
out[out.length] = '<div id="' + this._objectName + '_registerHtmlButtons"></div>';this._drawButtonBarHtml();break;case 'xhtml':
case 'xml':
case 'text':
out[out.length] = '<div id="' + this._objectName + '_registerTextButtons"></div>';this._drawButtonBarText();break;}
if (this.dataType == 'whtml') {
out[out.length] = '	</div>';out[out.length] = '</div>';}
out[out.length] = '</div>';return out.join('');}
this._getDrawnFormField = function() {
if (typeof(this.formFieldName) == 'undefined') return '';return '<input type="hidden" name="' + this.formFieldName + '" id="' + this.formFieldName + '" value="' + bs_filterForHtml(this._value) + '">';}
this._updateButtons = function() {
if (moz) return;if ((this.dataType == 'text') || (this.dataType == 'xhtml') || (this.dataType == 'xml')) {
} else if ((this.dataType == 'html') || this._inHtmlMode) {
} else {
this._updateButtonsHelper('wysiwygButton', 'bold');this._updateButtonsHelper('wysiwygButton', 'italic');this._updateButtonsHelper('wysiwygButton', 'underline');this._updateButtonsHelper('wysiwygButton', 'justifyleft');this._updateButtonsHelper('wysiwygButton', 'justifycenter');this._updateButtonsHelper('wysiwygButton', 'justifyright');this._updateButtonsHelper('wysiwygButton', 'insertorderedlist');this._updateButtonsHelper('wysiwygButton', 'insertunorderedlist');try {
var tempRange = this.workingDoc.selection.createRange().duplicate();var tempElm   = findWrappingElement('A', tempRange);if (typeof(wysiwygButtonunlink) != 'undefined') {
wysiwygButtonunlink.setStatus((tempElm == false) ? 0 : 1);}
var t = new Array('font', 'specialchars', 'forecolor');for (var i=0; i<t.length; i++) {
var varName = this._objectName + '_' + 'wysiwygButton' + t[i];if (typeof(window[varName]) != 'undefined') window[varName].setStatus(1);}
} catch (e) {
var t = new Array('bold', 'italic', 'underline', 'insertorderedlist', 'insertunorderedlist', 'font', 'specialchars', 'forecolor');for (var i=0; i<t.length; i++) {
var varName = this._objectName + '_' + 'wysiwygButton' + t[i];if (typeof(window[varName]) != 'undefined') window[varName].setStatus(0);}
}
}
}
this._updateButtonsHelper = function(cat, what) {
if (typeof(window[this._objectName + '_' + cat + what]) != 'undefined') {
try {
window[this._objectName + '_' + cat + what].setStatus(window[this._objectName].wysiwygDoc.queryCommandValue(what) ? 2 : 1);} catch (e) {
}
}
}
this.switchToWysiwygTab = function() {
if (!this._inHtmlMode) return;this._inHtmlMode          = false;this._value               = this.htmlElm.innerText;this.wysiwygElm.innerHTML = this._value;this.workingDoc = this.wysiwygDoc;this.workingElm = this.wysiwygElm;if (this.style == 'toolbar') {
this.htmlElm.contentEditable = false;this.iframeElm.height = 0;this.wysiwygElm.contentEditable = true;this.wysiwygElm.focus();}
}
this.switchToHtmlTab = function() {
if (this._inHtmlMode) return;this._inHtmlMode       = true;this._value            = this.wysiwygElm.innerHTML;this.htmlElm.innerText = this._value;this.workingDoc = this.htmlDoc;this.workingElm = this.htmlElm;if (this.style == 'toolbar') {
this.wysiwygElm.contentEditable = false;this.iframeElm.height = 200;this.htmlElm.contentEditable = true;this.iframeElm.contentWindow.focus();}
}
this.toggleToolbar = function(hide) {
var toolbarDiv = document.getElementById(this._objectName + '_outerDiv');if (bs_isNull(toolbarDiv)) return false;toolbarDiv.style.display = (hide) ? 'none' : 'block';}
this.toolbarButtonUndo = function() {
this.toggleToolbar(true);}
this.toolbarButtonSave = function() {
this.toggleToolbar(true);}
this.toolbarButtonClose = function() {
this.toggleToolbar(true);document.body.focus();this.fireOnBlur();this.fireEvent('toolbarButtonClose');}
this._setupResizeGrip = function(outerDivId) {
if (this.mayResize) {
var resizeGripObjName = this._objectName + '_rg';var rg = new Bs_ResizeGrip(resizeGripObjName, outerDivId);rg.gripIcon = this.bsImgPath + 'windows/resizeWindow.gif';rg.minWidth  = 150;rg.minHeight = 150;if (typeof(this.maxWidth)  != 'undefined') rg.maxWidth  = this.maxWidth;if (typeof(this.maxHeight) != 'undefined') rg.maxHeight = this.maxHeight;rg.onBeforeResizeStart = this._objectName + '.resizeWindowStart();';rg.onAfterResizeEnd    = this._objectName + '.resizeEnd();';eval(resizeGripObjName + ' = rg;');rg.draw();}
}
this.resizeWindowStart = function(elm) {
this.iframeElm.width  = 0;this.iframeElm.height = 0;this.iframeElm.style.display  = 'none';}
this.resizeEnd = function() {
this._updateIframeSize();this.iframeElm.style.display  = 'block';this.iframeElm.width          = '100%';}
this._updateIframeSize = function() {
var totalHeight  = document.getElementById(this._objectName + '_outerDiv').offsetHeight;var statusHeight = document.getElementById(this._objectName + '_statusbar').offsetHeight;var buttonHeight = document.getElementById(this._objectName + '_buttonbar').offsetHeight;this.iframeElm.style.height = totalHeight - statusHeight - buttonHeight + 'px';this.iframeElm.height       = totalHeight - statusHeight - buttonHeight;}
this._drawStatusBar = function() {
var out = new Array;out[out.length] = '<div style="height:20px; background-color:menu;">&nbsp;</div>';out[out.length] = '<div id="' + this._objectName + '_statusbar" style="position:absolute; bottom:0px; right:0px; width:100%; height:20px; background-color:menu;">';out[out.length] = '<div unselectable="On" style="z-index:5; position:absolute; display:inline; left:5px; bottom:3px; align:left; border-right:10px solid menu; background-color:menu; font-family:arial,helvetica; font-size:11px;" id="toolbarHelptext"></div>';out[out.length] = '<div unselectable="On" style="z-index:4; position:absolute; display:inline; width:110px; right:18px; bottom:3px; text-align:right;" class="wysiwygBaseline"><a href="http://www.blueshoes.org/en/javascript/editor/" target="_blank" style="color:black; text-decoration:none; font-family:arial,helvetica; font-size:11px;"><img src="' + this.bsImgPath + 'powered/blueshoes/shoe_xxs.gif" border="0" align="top"> BlueShoes Editor</a></div>';return out.join('');}
this._getDrawnIframe = function(tagId) {
if (typeof(tagId) != 'undefined') {
var initIframeHeight = document.getElementById(tagId).offsetHeight - 100;} else {
var initIframeHeight = 0;}
return '<iframe align="top" id="' + this._objectName + '_iframe' + '" width="100%" height="' + initIframeHeight + 'px"></iframe>';}
this._setupIframeDoc = function() {
var docHead = '';if (moz) {
docHead += "<script>";docHead += '	function convertTextToHTML(s) {\n';			docHead += '		s = s.replace(/\&/g, "&amp;")\n';
			docHead += '    s = s.replace(/</g, "&lt;")\n';
			docHead += '    s = s.replace(/>/g, "&gt;")\n';
			docHead += '    s = s.replace(/\n/g, "<BR>");\n';  //<= escaped \n here!!!
docHead += '		while (/\s\s/.test(s))\n';			docHead += '			s = s.replace(/\s\s/, "&nbsp; ");\n';
			docHead += '		return s.replace(/\s/g, " ");\n';
docHead += '	}\n';docHead += '	HTMLElement.prototype.__defineSetter__("innerText", function (sText) {';docHead += '		this.innerHTML = convertTextToHTML(sText);';docHead += '		return sText;		';docHead += '	});';docHead += '	var tmpGet;';docHead += '	HTMLElement.prototype.__defineGetter__("innerText", tmpGet = function () {';docHead += '		var r = this.ownerDocument.createRange();';docHead += '		r.selectNodeContents(this);';docHead += '		return r.toString();';docHead += '	});';docHead += "</script>\n";}
docHead += "<style>\n";docHead += this.editorCssString;docHead += "</style>\n";var emptyDoc = '';emptyDoc += '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">';emptyDoc += '<html><head>' + docHead + '</head>';emptyDoc += '<body>';if ((this.dataType == 'whtml') && !this._inHtmlMode) {
emptyDoc += this._value;} else {
emptyDoc += bs_filterForHtml(this._value);}
emptyDoc += '</body></html>';this.htmlDoc.write(emptyDoc);}
this._afterDrawStuff = function(isToolbar) {
switch (this.dataType) {
case 'whtml':
toolbarWysiwyg.drawInto(this._objectName + '_registerWysiwygButtons');toolbarHtml.drawInto(this._objectName + '_registerHtmlButtons');break;case 'html':
toolbarHtml.drawInto(this._objectName + '_registerHtmlButtons');break;case 'xhtml':
case 'xml':
case 'text':
toolbarText.drawInto(this._objectName + '_registerTextButtons');break;}
try {
this.wysiwygDoc.execCommand("undo", false, null);} catch (e) {
alert("This is not supported on your level of Mozilla. Please update.1");}
if (this.dataType == 'whtml') {
if ((this.buttonsWysiwyg != false) && (this.buttonsHtml != false)) {
this._tabset = new Bs_TabSet(this._objectName + '._tabset', this._objectName + '_tabset');var tabWysiwygObj = new Object;tabWysiwygObj.caption   = 'Wysiwyg';tabWysiwygObj.container = document.getElementById(this._objectName + '_registerWysiwygButtons');tabWysiwygObj.onFocus   = this._objectName + '.switchToWysiwygTab();';this._tabset.addTab(tabWysiwygObj);var tabHtmlObj = new Object;tabHtmlObj.caption   = 'HTML';tabHtmlObj.container = document.getElementById(this._objectName + '_registerHtmlButtons');tabHtmlObj.onFocus   = this._objectName + '.switchToHtmlTab();';this._tabset.addTab(tabHtmlObj);this._tabset.draw();}
}
if (!isToolbar) this._updateIframeSize();window.setInterval(this._objectName + "._updateButtons()", 200);this.outrendered = true;return true;}
this._attachEditorEvents = function(editableAreaId) {
bsWysiwygDivToInstanceArray[editableAreaId] = this;this.wysiwygElm.attachEvent('onbeforedeactivate', function() { bsWysiwygEditorObdWrapper(editableAreaId); } );this.wysiwygElm.attachEvent('onblur',             function() { bsWysiwygEditorOnBlurWrapper(editableAreaId); } );this.wysiwygElm.attachEvent('onfocus',            function() { bsWysiwygEditorOnFocusWrapper(editableAreaId); } );this.wysiwygElm.attachEvent('onmouseover',        function() { bsWysiwygEditorEventWrapper('editareaMouseOver', editableAreaId); } );this.wysiwygElm.attachEvent('onmouseout',         function() { bsWysiwygEditorEventWrapper('editareaMouseOut',  editableAreaId); } );this.wysiwygElm.attachEvent('onpaste',            function() { return bsWysiwygEditorEventWrapper('editareaPaste',     editableAreaId); } );}
this._objectName = objectName;bsWysiwygInstances[this._objectName] = this;}

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
bsWysiwygDivToInstanceArray[tagId]._fireEditareaOnPaste();break;}
}
function bsWysiwygEditorObdWrapper(tagId) {
bsWysiwygDivToInstanceArray[tagId].saveCursorPos();}
function bsWysiwygToolbarOnloadCallback() {
for (var objectName in bsWysiwygInstances) {
var page = document[objectName + '_toolbarIframe'];if (page) {
if ((page.meLoaded) && (!page.sawThatMeLoaded)) {
var evalStr = objectName + '.toolbarOnloadCallback();';eval(evalStr);break;}
}
}
}
var bsWysiwygSpecialCharsOpener;var bsWysiwygSpecialCharsWindow;function bsSpecialCharsOnloadCallback() {
bsWysiwygSpecialCharsOpener.specialCharsOnloadCallback();}
function bsSpecialCharsDoneCallback(code) {
bsWysiwygSpecialCharsOpener.specialCharsDoneCallback(code);}
function Bs_Wysiwyg(objectName) {
this._objectName;this.dataType = 'whtml';this.editareaOnPaste = 5;this.style = 'inline';this.inHtmlMode = false;this._value = '';this.formFieldName;this.useRegisterCustom1 = false;this.useRegisterText    = 'auto';this.useRegisterEasy    = 'auto';this.useRegisterPlus    = 'auto';this.useRegisterEditlive   = false;this.useRegisterHtml       = 'auto';this.useRegisterScreentype = 'auto';this.registerCustom1Content = new Array();this.registerCustom1Caption = 'Custom 1';this.toolbarDoc;this.toolbarElm;this.wysiwygElm;this.outrendered = false;this._imageBrowserUrl;this.specialCharsSelectorUrl = '/_bsJavascript/components/wysiwyg/specialCharsSelector.html';this._codeSnippets;this._editorCursorPos;this.useTabWysiwyg;this.tabWysiwygGroup = new Array;this.tabWysiwygGroup['clipboard']     = 2;this.tabWysiwygGroup['undo']          = 2;this.tabWysiwygGroup['align']         = 2;this.tabWysiwygGroup['bold']          = 2;this.tabWysiwygGroup['list']          = 2;this.tabWysiwygGroup['dent']          = 2;this.tabWysiwygGroup['link']          = 2;this.tabWysiwygGroup['image']         = 2;this.tabWysiwygGroup['specialFormat'] = 2;this.tabWysiwygGroup['color']         = 2;this.tabWysiwygGroup['font']          = 2;this.tabWysiwygGroup['percent']       = 2;this.tabWysiwygGroup['codeSnippets']  = 2;this.tabWysiwygGroup['specialChars']  = 2;this.dataContainer;this.toolbarShowButtonUndo = false;this.toolbarShowButtonSave = false;this.toolbarShowButtonClose = true;this._toolbarStartActivated = false;this._attachedEvents;this.attachEvent = function(trigger, yourEvent) {
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
this.toggleToolbar = function(hide) {
var toolbarDiv = document.getElementById(this._objectName + '_toolbarDiv');if (typeof(toolbarDiv) == 'undefined') return false;toolbarDiv.style.display = (hide) ? 'none' : 'block';}
this.toolbarButtonUndo = function() {
this.toggleToolbar(true);}
this.toolbarButtonSave = function() {
this.toggleToolbar(true);}
this.toolbarButtonClose = function() {
this.toggleToolbar(true);document.body.focus();this.fireOnBlur();this.fireEvent('toolbarButtonClose');}
this.drawAsToolbar = function(editableAreaId, startActivated) {
this.wysiwygElm = document.getElementById(editableAreaId);if (!this.wysiwygElm) return false;if (startActivated) this._toolbarStartActivated = true;this._value = this.wysiwygElm.innerHTML;bsWysiwygDivToInstanceArray[editableAreaId] = this;this.wysiwygElm.attachEvent('onbeforedeactivate', function() { bsWysiwygEditorObdWrapper(editableAreaId); } );this.wysiwygElm.attachEvent('onblur',             function() { bsWysiwygEditorOnBlurWrapper(editableAreaId); } );this.wysiwygElm.attachEvent('onfocus',            function() { bsWysiwygEditorOnFocusWrapper(editableAreaId); } );this.wysiwygElm.attachEvent('onmouseover',        function() { bsWysiwygEditorEventWrapper('editareaMouseOver', editableAreaId); } );this.wysiwygElm.attachEvent('onmouseout',         function() { bsWysiwygEditorEventWrapper('editareaMouseOut',  editableAreaId); } );this.wysiwygElm.attachEvent('onpaste',            function() { bsWysiwygEditorEventWrapper('editareaPaste',     editableAreaId); } );var out = new Array;out[out.length] = '<div id="' + this._objectName + '_toolbarDiv" style="behavior:url(\'/_bsJavascript/lib/winlessmovable.htc\'); display:none; cursor:hand; position:absolute; left;50; top:50; width:446; height=expression(parseInt(document.getElementById(\'' + this._objectName + '_toolbarIframe\').offsetHeight) + 25 + \'px\'); border:1px solid gray;">';out[out.length] = '<table width="100%" border="0" cellspacing="1" cellpadding="2" bgcolor="blue">';out[out.length] = '  <tr>';out[out.length] = '    <td><font face="arial" size="2" color="white"><b>BlueShoes Wysiwyg Toolbar</b></font></td>';if (this.toolbarShowButtonUndo)  out[out.length] = '    <td align="right" width="16"><img src="/_bsJavascript/components/wysiwyg/buttons/windows_undo.gif"  width="16" height="14" border="0" onClick="' + this._objectName + '.toolbarButtonUndo();"></td>';if (this.toolbarShowButtonSave)  out[out.length] = '    <td align="right" width="16"><img src="/_bsJavascript/components/wysiwyg/buttons/windows_save.gif"  width="16" height="14" border="0" onClick="' + this._objectName + '.toolbarButtonSave();"></td>';if (this.toolbarShowButtonClose) out[out.length] = '    <td align="right" width="16"><img src="/_bsJavascript/components/wysiwyg/buttons/windows_close.gif" width="16" height="14" border="0" onClick="' + this._objectName + '.toolbarButtonClose();"></td>';out[out.length] = '  </tr>';out[out.length] = '</table>';out[out.length] = this._getDrawnFormField();out[out.length] = '<iframe src="/_bsJavascript/components/wysiwyg/toolbar.html"';out[out.length] = ' name="' + this._objectName + '_toolbarIframe' + '"';out[out.length] = ' id="' + this._objectName + '_toolbarIframe' + '"';out[out.length] = ' style="border:lightgrey 0px inset; position:relative; top:0; left:0; width:100%; height:0; z-index:1;"';out[out.length] = '></iframe>';out[out.length] = '</div>';var body = document.getElementsByTagName('body').item(0);body.insertAdjacentHTML('beforeEnd', out.join(''));this.outrendered = true;return true;}
this.drawInto = function(tagId) {
var out = new Array;if (this.style == 'toolbar') {
out[out.length] = '<div style="position:absolute; left;50; top:50; width:442; height:200; border:1px solid gray;">';} else {
out[out.length] = '<div style="width:100%; height:100%; border:1px solid gray;">';}
out[out.length] = this._getDrawnFormField();out[out.length] = '<iframe src="/_bsJavascript/components/wysiwyg/toolbar.html"';out[out.length] = ' name="' + this._objectName + '_toolbarIframe' + '"';out[out.length] = ' id="' + this._objectName + '_toolbarIframe' + '"';out[out.length] = ' style="border:lightgrey 0px inset; position:relative; top:0; left:0; width:100%; height:0; z-index:1;"';out[out.length] = '></iframe>';out[out.length] = '<div id="' + this._objectName + '_wysiwyg' + '"';out[out.length] = ' style="background-color:white; border:1px gray solid; overflow:auto; width:100%;';var fullHeight = parseInt(document.getElementById(tagId).clientHeight);out[out.length] = ' height:expression(' + fullHeight + ' - parseInt(document.getElementById(\'' + this._objectName + '_toolbarIframe' + '\').clientHeight) + \'px\');"';out[out.length] = ' onBeforeDeactivate="' + this._objectName + '.saveCursorPos();"';out[out.length] = ' onBlur="'  + this._objectName + '.fireOnBlur();"';out[out.length] = ' onpaste="' + this._objectName + '._fireEditareaOnPaste();"';out[out.length] = '>' + this._value + '</div>';out[out.length] = '</div>';var tag = document.getElementById(tagId);tag.innerHTML = out.join('');this.outrendered = true;return true;}
this._getDrawnFormField = function() {
if (typeof(this.formFieldName) == 'undefined') return '';return '<input type="hidden" name="' + this.formFieldName + '" id="' + this.formFieldName + '" value="' + bs_filterForHtml(this._value) + '">';}
this.toolbarOnloadCallback = function() {
this.toolbarDoc = document[this._objectName + '_toolbarIframe'];this.toolbarElm = document.getElementById(this._objectName + '_toolbarIframe');if (this.style != 'toolbar') {
this.wysiwygElm = document.getElementById(this._objectName + '_wysiwyg');} else {
this.toolbarDoc.useInternalHtmlElement = true;this.toolbarDoc.registerSize['html']['height'] += 308;this.toolbarDoc.registerSizeNow['html']['height'] = this.toolbarDoc.registerSize['html']['height'];}
this.toolbarDoc.sawThatMeLoaded = true;this.toolbarDoc.wysiwygObj = this;this.toolbarDoc.init(this.dataType, this._toolbarStartActivated);this._initCodeSnippets();if (this._toolbarStartActivated) this.wysiwygElm.focus();}
this.setValue = function(str) {
this._value = str;if (this.outrendered) {
if (this.inHtmlMode) {
this.wysiwygElm.innerText = str;} else {
this.wysiwygElm.innerHTML = str;}
}
return true;}
this.getValue = function() {
return this._value;}
this.setImageBrowser = function(url) {
this._imageBrowserUrl = url;}
this.openImageBrowser = function() {
if (typeof(this._imageBrowserUrl) == 'undefined') return false;var url    = this._imageBrowserUrl + '?callbackFunction=' + this._objectName + '.imageBrowserCallback';var params = "dependent=yes,width=500,height=300,location=no,menubar=no,scrollbars=no,status=no,toolbar=no";imgBrowserPopup = window.open(url, "Bs_ImageBrowser", params);return true;}
this.imageBrowserCallback = function(url) {
var img = this.toolbarDoc.document.getElementById('imgSource');if (img) img.value = url;}
this.setCodeSnippets = function(arr) {
this._codeSnippets = arr;}
this._initCodeSnippets = function() {
if (this._codeSnippets) {
this.toolbarDoc.document.getElementById('btnSnippets').style.display = '';var formFieldSelect = new Bs_FormFieldSelect;var snippetGroupSelect = this.toolbarDoc.document.getElementById('snippetGroupSelect');formFieldSelect.init(snippetGroupSelect);for (var group in this._codeSnippets) {
var opt = new Option(group, group, false, false);snippetGroupSelect.options[snippetGroupSelect.length] = opt;}
var snippetNameSelect = this.toolbarDoc.document.getElementById('snippetNameSelect');formFieldSelect.init(snippetNameSelect);for (var group in this._codeSnippets) { break; }
if (group) {
this._snippetUpdateNames(group);}
}
}
this._snippetUpdateNames = function(groupName) {
var snippetNameSelect = this.toolbarDoc.document.getElementById('snippetNameSelect');snippetNameSelect.prune();for (var myName in this._codeSnippets[groupName]) {
var opt = new Option(myName, myName, false, false);snippetNameSelect.options[snippetNameSelect.length] = opt;}
}
this.snippetSwitchGroup = function(groupName) {
this._snippetUpdateNames(groupName);var snippetHtmlEditor       = this.toolbarDoc.document.getElementById('snippetHtmlEditor');snippetHtmlEditor.innerText = '';}
this.snippetShow = function(snippetName) {
var groupName              = this.toolbarDoc.document.getElementById('snippetGroupSelect').getValue();var snippetHtmlEditor      = this.toolbarDoc.document.getElementById('snippetHtmlEditor');var snippetPropModified    = this.toolbarDoc.document.getElementById('snippetPropModified');var snippetPropUser        = this.toolbarDoc.document.getElementById('snippetPropUser');var snippetPropDescription = this.toolbarDoc.document.getElementById('snippetPropDescription');var t = this._codeSnippets[groupName][snippetName];var value       = (t['value'])       ? t['value']       : '';var lastMod     = (t['lastMod'])     ? t['lastMod']     : '';var user        = (t['user'])        ? t['user']        : '';var description = (t['description']) ? t['description'] : '';snippetHtmlEditor.innerText      = value;snippetPropModified.innerHTML    = lastMod;snippetPropUser.innerHTML        = user;snippetPropDescription.innerHTML = description;}
this.specialCharsSelectorShow = function() {
if (!this.specialCharsSelectorUrl) return false;bsWysiwygSpecialCharsOpener = this;var params = "dependent=yes,width=420,height=200,location=no,menubar=no,scrollbars=no,status=no,toolbar=no";bsWysiwygSpecialCharsWindow = window.open(this.specialCharsSelectorUrl, "Bs_SpecialCharsSelector", params);return true;}
this.specialCharsOnloadCallback = function() {
}
this.specialCharsDoneCallback = function(code) {
if (this.inHtmlMode) {
this.insertHtml('&amp;' + code + ';');} else {
this.insertHtml('&' + code + ';');}
}
this.insertHtml = function(str) {
this._editorCursorPos.pasteHTML(str);}
this.saveCursorPos = function() {
try {
this._editorCursorPos = document.selection.createRange().duplicate();} catch (e) {
this._editorCursorPos = null;}
}
this.fireOnBlur = function() {
if (this.wysiwygElm) {
if (this.inHtmlMode) {
this._value = this.wysiwygElm.innerText;} else {
this._value = this.wysiwygElm.innerHTML;}
if (typeof(this.formFieldName) != 'undefined') {
var fld = document.getElementById(this.formFieldName);if (typeof(fld) != 'unknown') fld.value = this._value;}
}
this.fireEvent('editEnd');}
this.fireOnFocus = function() {
if (this.style == 'toolbar') {
this.toggleToolbar(false);}
this.fireEvent('editStart');}
this._fireEditareaOnPaste = function() {
if (typeof(this.editareaOnPaste) == 'function') {
var status = this.editareaOnPaste();if (typeof(status) == 'string') {
window.clipboardData.setData("Text", status);event.returnValue = true;} else {
event.returnValue = false;}
return;} else {
switch (this.editareaOnPaste) {
case 1:
alert('Sorry, no pasting allowed.');case 0:
event.returnValue = false;return;break;case 5:
event.returnValue = true;return;break;default:
var clipValOrig  = window.clipboardData.getData("Text");var clipValStrip = bs_stripTags(clipValOrig);if ((clipValOrig == clipValStrip) || (this.editareaOnPaste == 2)) {
event.returnValue = true;return;} else {
switch (this.editareaOnPaste) {
case 3:
alert("Formatting tags have been removed from the clipboard.");window.clipboardData.setData("Text", clipValStrip);event.returnValue = true;break;case 4:
var status = confirm("The pasted content includes formatting tags. Click OK to paste your content with them. Click cancel to have them removed.");if (!status) window.clipboardData.setData("Text", clipValStrip);event.returnValue = true;break;}
}
}
}
}
this.addToRegisterCustom1 = function(action, imgName, title, helpText) {
var i = this.registerCustom1Content.length;if ((action == 'undefined') || (typeof(action) != 'string')) {
this.registerCustom1Content[i] = '__SEPARATOR__';} else {
var i = this.registerCustom1Content.length;this.registerCustom1Content[i] = new Array();this.registerCustom1Content[i]['action']   = action;this.registerCustom1Content[i]['imgName']  = imgName;this.registerCustom1Content[i]['title']    = title;this.registerCustom1Content[i]['helpText'] = helpText;}
}
this._objectName = objectName;bsWysiwygInstances[this._objectName] = this;}

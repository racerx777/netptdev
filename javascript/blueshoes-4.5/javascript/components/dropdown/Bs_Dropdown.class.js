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
if (!Bs_Objects) {var Bs_Objects = [];};function Bs_Dropdown() {
this._tagId;this.autoAdd = true;this.rememberNewInCookie;this._cookieElements = new Array();this.maxLength;this.selectOrder = 1;this.disabled = false;this.guiNochange;this.imgDir = '/_bsJavascript/components/dropdown/img/win2k/';this.classInput;this.classSelect;this._fromTextObj;this._frmSelectObj;this._attachedEvents;this.isOutRendered = false;this._value = '';this._options = new Array();this._firstTimeToSelect = true;this._size;this._constructor = function() {
this._id = Bs_Objects.length;Bs_Objects[this._id] = this;this._tagId = "Bs_Dropdown_"+this._id+"_";}
this.getValue = function() {
return this._value;}
this.setValue = function(value, addToCookie) {
this._value = value;this.addOption(value, addToCookie);if (this.isOutRendered) {
this._getTextObj().value  = value;this._getSelectObj().setTo(value);this._updateHiddenField();}
}
this.addOption = function(str, addToCookie) {
if (this._options.indexOf(str) >= 0) {
return false;} else {
this._options[this._options.length] = str;if (this.isOutRendered) {
var newOpt = new Option(str, str);this._getSelectObj().options[this._getSelectObj().length] = newOpt;}
if (addToCookie && (typeof(this.rememberNewInCookie) == 'string')) {
this._saveCookieString(str);}
return true;}
}
this.setSize = function(value, unit) {
this._size = new Array(value, unit);}
this.render = function(tagId) {
if (!bs_isEmpty(tagId)) {
this._tagId = tagId;}
if (typeof(this.rememberNewInCookie) == 'string') {
this._readCookie();for (var i=0; i<this._cookieElements.length; i++) {
this.addOption(this._cookieElements[i], false);}
}
var out  = new Array();var outI = 0;out[outI++] = '<table border="0" cellspacing="0" cellpadding="0" style="vertical-align:middle; display:inline;"><tr><td align="right">';out[outI++] = '<input name="' + this._tagId + '" id="' + this._tagId + '" type="hidden" value="' + this._value + '">';out[outI++] = '<input name="' + this._tagId + '_input" id="' + this._tagId + '_input" type="text" value="' + this._value + '"';if ((typeof(this._size) == 'object') && (this._size[1] == 'char')) {
out[outI++] = ' size="' + this._size[0] + '"';}
out[outI++] = ' onblur="Bs_Objects['+this._id+']._textFieldOnBlur();"';out[outI++] = ' onkeydown="var _dd=Bs_Objects['+this._id+']; return _dd._textFieldOnKeyDown();"';if (!bs_isEmpty(this.classInput)) {
out[outI++] = ' class="' + this.classInput + '"';}
out[outI++] = ' style="border-right:none; margin-top:1px;';if ((typeof(this._size) == 'object') && (this._size[1] == 'pixel')) {
out[outI++] = ' width:' + (this._size[0] -20) + ';';}
out[outI++] = '"';if (!bs_isEmpty(this.maxLength)) out[outI++] = ' maxlength="' + this.maxLength + '"';out[outI++] = '>';out[outI++] = '<select name="' + this._tagId + '_select" id="' + this._tagId + '_select"';out[outI++] = ' onChange="Bs_Objects['+this._id+'].toInput();"';out[outI++] = ' onBlur="Bs_Objects['+this._id+'].toInput();"';out[outI++] = ' onDoubleClick="Bs_Objects['+this._id+'].toInput();"';out[outI++] = ' onkeydown="Bs_Objects['+this._id+']._selectFieldOnKeyDown();"';out[outI++] = ' style="display:none; margin-top:2px;';if ((typeof(this._size) == 'object') && (this._size[1] == 'pixel')) {
out[outI++] = ' width:' + this._size[0] + ';';}
out[outI++] = '"';if (!bs_isEmpty(this.classSelect)) {
out[outI++] = ' class="' + this.classSelect + '"';}
out[outI++] = '>';for (var i=0; i<this._options.length; i++) {
out[outI++] = '<option value="' + this._options[i] + '"';if (this._value == this._options[i]) {
out[outI++] = ' selected'
}
out[outI++] = '>' + this._options[i] + '</option>';}
out[outI++] = '</select>';out[outI++] = '</td><td>';out[outI++] = '<img id="' + this._tagId + '_arrow" src="' + this.imgDir + 'arrow.gif"';out[outI++] = ' onMouseOver="Bs_Objects['+this._id+'].toSelect();"';out[outI++] = ' style="margin-left:0px; margin-top:0px; border-top: 2px ridge #848284; border-right:1px solid #D6D3CE; border-bottom:1px solid #D6D3CE;"';out[outI++] = ' align="top"';out[outI++] = '>';out[outI++] = '</td></tr></table>';out[outI++] = '';this.isOutRendered = true;return out.join('');}
this.drawInto = function(tagId) {
document.getElementById(tagId).innerHTML = this.render(tagId);}
this.convertField = function(fieldId) {
var origFld = document.getElementById(fieldId);if (origFld.tagName == 'INPUT') {
this.setValue(origFld.value, false);if (!bs_isEmpty(origFld['size']))      this.setSize(origFld['size'], 'char');if (!bs_isEmpty(origFld['maxLength'])) this.maxLength = origFld['maxLength'];if (!bs_isEmpty(origFld['name']))      this._tagId = origFld['name'];} else if (origFld.tagName == 'SELECT') {
}
origFld.outerHTML = this.render();}
this.mouseOutTimer = function() {
}
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
this.activeTimer = function() {
if (typeof(document.activeElement.id) == 'string') {
if (
(document.activeElement.id == (this._tagId + '_select'))
||
(document.activeElement.id == (this._tagId + '_input'))
) {
return;}
}
this.toInput();}
this.toSelect = function() {
var textElm   = this._getTextObj();var selectElm = this._getSelectObj();var textWidth = textElm.offsetWidth;textElm.style.display  = 'none';document.getElementById(this._tagId + '_arrow').style.display  = 'none';if (this._firstTimeToSelect) {
if (bs_isEmpty(this.classSelect)) {
selectElm.style.width   = (textWidth + 20) + 'px';}
this._firstTimeToSelect = false;}
selectElm.style.display = 'block';selectElm.focus();}
this.toInput = function() {
this._getSelectObj().style.display = 'none';document.getElementById(this._tagId + '_arrow').style.display  = '';this._getTextObj().value          = this._getSelectObj().getValue();if (this._getTextObj().value == 'undefined') this._getTextObj().value = '';this._getTextObj().style.display  = '';if (this._value != this._getTextObj().value)  {
this._value = this._getTextObj().value;this._updateHiddenField();if (this.hasEventAttached('onChange')) this.fireEvent('onChange');}
}
this._updateHiddenField = function() {
document.getElementById(this._tagId).value = this._value;}
this._textFieldOnBlur = function() {
var txtVal = this._getTextObj().value;if (this._value != txtVal) {
if (this.autoAdd) {
this.setValue(txtVal, true);} else {
this._value = txtVal;}
this._updateHiddenField();this.fireEvent('onChange');}
}
this._textFieldOnKeyDown = function() {
try {
switch (window.event.keyCode) {
case 13:
this._textFieldOnBlur();return false;break;case 27:
this._getTextObj().value = this._value;return false;break;case 40:
this.toSelect();return false;break;}
} catch (e) {
}
return true;}
this._selectFieldOnKeyDown = function() {
try {
switch (window.event.keyCode) {
case 27:
case 113:
this.toInput();break;}
} catch (e) {
}
}
this._readCookie = function() {
var cookieName   = "bsdd_" + this.rememberNewInCookie;var list = new Array();if (document.cookie) {
var allCookies = document.cookie.split('; ');for (var i=0; i<allCookies.length; i++) {
if (allCookies[i].substr(0, cookieName.length) == cookieName) {
myCook = allCookies[i];var Wertstart = myCook.indexOf("=") + 1;if (Wertstart == 0) break;var Wertende = myCook.indexOf(";");if (Wertende == -1) Wertende = document.cookie.length;myListStr = myCook.substring(Wertstart, Wertende);var list = myListStr.split(',,,');}
}
}
this._cookieElements = list;}
this._saveCookieString = function(value) {
this._cookieElements[this._cookieElements.length] = value;var fullString   = this._cookieElements.join(',,,');var cookieName   = "bsdd_" + this.rememberNewInCookie;var lifetime     = 1000*60*60*24*365;var dateNow      = new Date();var dateEnd      = new Date(dateNow.getTime() + lifetime);var cookieString = cookieName + "=" + fullString + "; expires=" + dateEnd.toGMTString() + ";";document.cookie  = cookieString;}
this._getTextObj = function() {
if (typeof(this._fromTextObj) != 'object') {
this._fromTextObj = document.getElementById(this._tagId + '_input');}
return this._fromTextObj;}
this._getSelectObj = function() {
if (typeof(this._frmSelectObj) != 'object') {
this._frmSelectObj = document.getElementById(this._tagId + '_select');var tmp = new Bs_FormFieldSelect();tmp.init(this._frmSelectObj);}
return this._frmSelectObj;}
this._constructor();}

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
if (!Bs_Objects) {var Bs_Objects = [];};function bs_colPic_openWindow() {
try {
event.srcElement.pickerObj.openWindow();} catch (e) { }
}
function bs_colPic_blur() {
try {
event.srcElement.pickerObj.onBlur();} catch (e) { }
}
function Bs_ColorPicker(elementId) {
var a = arguments;this._elementId = (a.length>1) ? a[1] :  a[0];this._elm       = document.getElementById(this._elementId);this._objectId;this.colorizeBackground = false;this._color;this.jsBaseDir = '/_bsJavascript/';this.bsImgDir = '/_bsImages/';this.windowType = 'dialog';this.button = new Bs_Button();this._attachedEvents;this._constructor = function() {
this._id = Bs_Objects.length;Bs_Objects[this._id] = this;this._objectId = "Bs_ColorPicker_" + this._id;this._initButton();this.updateColorByField();}
this._initButton = function() {
this.button.objectName = this._objectId + '_btnObj';this.button.imgName         = 'bs_bgColor';this.button.title           = 'Pick a Color';this.button.cssClassDefault = 'bsBtnMouseOver';this.button.attachEvent('Bs_Objects['+this._id+'].openWindow();');eval(this._objectId + '_btnObj = this.button;');}
this.draw = function() {
if (!ie && !moz) return;var buttonDivId = this._objectId + '_btnDiv';var htmlCode = '<div id="' + buttonDivId + '" style="display:inline;"></div>';this._elm.insertAdjacentHTML('afterEnd', htmlCode);this.button.drawInto(buttonDivId);this._elm.pickerObj = this;this._elm.attachEvent('ondblclick', bs_colPic_openWindow);this._elm.attachEvent('onblur',     bs_colPic_blur);this._updateBackgroundColor();}
this.openWindow = function() {
var colorSelector = new Array(this.jsBaseDir + 'components/colorpicker/windowColor.html', 500, 600);var url = colorSelector[0] + '?objectId=' + this._objectId + '&id=' + this._id;url += '&callbackLoad=prepareSettings&callbackSave=setColorByPickerWindow';if (moz || (this.windowType == 'popup')) {
window.open(url, "colorSelector", "width=" + colorSelector[1] + ",height=" + colorSelector[2] + ",left=0,top=0,scrollbars=no,status=no,toolbar=no,resizable=no,location=no,hotkeys=no,dependent=yes");} else {
var ret = window.showModalDialog(url, this.prepareSettings(), "dialogWidth:"  + colorSelector[1] + "px; dialogHeight:" + colorSelector[2] + "px;");if (ret) this.setColorByPickerWindow(ret);}
}
this.setColorByPickerWindow = function(hex) {
if (!this.fireEvent('onBeforeChange')) return;this.setColorByHex(hex);this.fireEvent('onAfterChange');}
this.setColorByHex = function(hex) {
hex = this.cleanHexCode(hex);if (hex == false) {
hex = null;this._elm.value = '';} else {
this._elm.value = '#' + hex;}
this._color = hex;this._updateBackgroundColor();}
this.cleanHexCode = function(hex) {
if (bs_isEmpty(hex)) return false;if (hex.length == 7) hex = hex.substr(1);if (hex.length != 6) return false;return hex.toUpperCase();}
this.onBlur = function() {
var oldVal = this._color;var newVal = this.cleanHexCode(this._elm.value);if (oldVal.toUpperCase() != newVal.toUpperCase()) {
if (!this.fireEvent('onBeforeChange')) return;this.setColorByHex(newVal);this.fireEvent('onAfterChange');}
}
this._updateBackgroundColor = function() {
if (!this.colorizeBackground) return;if (bs_isEmpty(this._color)) {
var fgColor = 'black';var bgColor = 'white';} else {
var fgColor = isDarkColor(this._color) ? 'white' : 'black';var bgColor = this._color;}
this._elm.style.color           = fgColor;this._elm.style.backgroundColor = bgColor;}
this.getColorAsHex = function() {
return this._color;}
this.updateColorByField = function() {
this.setColorByHex(this._elm.value);}
this.prepareSettings = function() {
var params = new Object;if (!bs_isEmpty(this._color)) {
params.color = this._color;}
params.bsImgDir = this.bsImgDir;return params;}
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
var status = e[i](this);} else if (typeof(e[i]) == 'string') {
var status = eval(e[i]);}
if (status == false) return false;}
}
return true;}
this._constructor();}

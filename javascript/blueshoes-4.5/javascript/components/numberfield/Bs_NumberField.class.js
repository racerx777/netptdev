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
if (!Bs_Objects) {var Bs_Objects = [];};function bs_numberFieldKeyDown() {
return event.srcElement.bsObject.onKeyDown();}
function bs_numberFieldKeyUp() {
return event.srcElement.bsObject.onKeyUp();}
function bs_numberFieldFocus() {
return event.srcElement.bsObject.onFocus();}
function bs_numberFieldWheel() {
return event.srcElement.bsObject.onWheel();}
function bs_numberFieldBlur() {
return event.srcElement.bsObject.onBlur();}
function bs_numberFieldRedraw() {
for (var i=0; i<bs_numberFieldObjects.length; i++) {
try {
bs_numberFieldObjects[i].redraw();} catch (e) {
break;}
}
}
var bs_numberFieldObjects = new Array;try {
window.attachEvent('onresize', bs_numberFieldRedraw);} catch (e) {
}
window.setInterval(bs_numberFieldRedraw, 1000);function Bs_NumberField(elm) {
var a = arguments;this._elementId = (a.length>1) ? a[1] :  a[0];this._elm       = document.getElementById(this._elementId);this._objectId;this.maxValue;this.minValue = 0;this._value   = 0;this.zeroFill;this.buttonUp;this.buttonDown;this.drawButtonsInside = false;this._attachedEvents;this._constructor = function() {
this._id = Bs_Objects.length;Bs_Objects[this._id] = this;this._objectId = "Bs_NumberField_"+this._id;if (typeof(this._elm) == 'string') this._elm = document.getElementById(this._elm);bs_numberFieldObjects[bs_numberFieldObjects.length] = this;this._elm.bsObject = this;this._elm.attachEvent('onkeydown',    bs_numberFieldKeyDown);this._elm.attachEvent('onkeyup',      bs_numberFieldKeyUp);this._elm.attachEvent('onfocus',      bs_numberFieldFocus);this._elm.attachEvent('onmousewheel', bs_numberFieldWheel);this._elm.attachEvent('onblur',       bs_numberFieldBlur);var arrowUpObjName   = this._objectId + '_btnUpObj';var arrowUpDivName   = this._objectId + '_btnUpDiv';var arrowDownObjName = this._objectId + '_btnDownObj';var arrowDownDivName = this._objectId + '_btnDownDiv';var buttonHeights = this._getButtonHeights();this.buttonUp   = new Bs_Button();this.buttonUp.objectName = arrowUpObjName;this.buttonUp.imgPath = '/_bsJavascript/components/numberfield/img/';this.buttonUp.imgName = 'arrowBlackUp';this.buttonUp.title   = 'Increase';this.buttonUp.height  = buttonHeights.button1;this.buttonUp.width   = 13;this.buttonUp.cssClassDefault = 'bsBtnMouseOver';this.buttonUp.attachEvent('Bs_Objects['+this._id+'].onClickButtonUp();');this.buttonDown = new Bs_Button();this.buttonDown = new Bs_Button();this.buttonDown.objectName = arrowDownObjName;this.buttonDown.imgPath = '/_bsJavascript/components/numberfield/img/';this.buttonDown.imgName = 'arrowBlackDown';this.buttonDown.title   = 'Decrease';this.buttonDown.height  = buttonHeights.button2;this.buttonDown.width   = 13;this.buttonDown.cssClassDefault   = 'bsBtnMouseOver';this.buttonDown.attachEvent('Bs_Objects['+this._id+'].onClickButtonDown();');}
this.setValue = function(value) {
value = this.fixValue(value);this._value = value;this._elm.value = this._zeroFillValue(value);}
this.getValue = function() {
this.updateByField();return this._value;}
this.increase = function() {
this.updateByField();this.setValue(this._value +1);}
this.decrease = function() {
this.updateByField();this.setValue(this._value -1);}
this.render = function() {
var arrowUpDivName   = this._objectId + '_btnUpDiv';var arrowDownDivName = this._objectId + '_btnDownDiv';var fldPos        = getAbsolutePos(this._elm, true);var fldHeight     = this._elm.offsetHeight;var fldWidth      = this._elm.offsetWidth;var left          = fldPos.x + fldWidth;var top           = fldPos.y;if (this.drawButtonsInside) {
left      -= 15;top       += 2;fldHeight -= 4;}
var bDiv = new Array;bDiv[bDiv.length] = '<div id="' + this._objectId + '_btnContainer" style="position:absolute; left:' + left + 'px; top:' + top + 'px;';if (false) {
var zIndex = (this._elm.style.zIndex > 0) ? this._elm.style.zIndex : 1;bDiv[bDiv.length] = ' z-index:' + zIndex + ';';}
bDiv[bDiv.length] = '">';bDiv[bDiv.length] = this._renderButtonContainers(arrowUpDivName, arrowDownDivName);bDiv[bDiv.length] = '</div>';return bDiv.join('');}
this._getButtonHeights = function() {
var ret = new Object;var fldHeight = this._elm.offsetHeight;if (this.drawButtonsInside) {
fldHeight -= 4;}
ret.button1   = parseInt(fldHeight /2);ret.button2   = fldHeight - ret.button1;return ret;}
this.redraw = function() {
var fldPos        = getAbsolutePos(this._elm, true);var fldWidth      = this._elm.offsetWidth;var left          = fldPos.x + fldWidth;var top           = fldPos.y;if (this.drawButtonsInside) {
left      -= 15;top       += 2;}
var container = document.getElementById(this._objectId + '_btnContainer');if ((parseInt(container.style.left) == left) && (parseInt(container.style.top) == top)) {
return;}
container.style.left = left;container.style.top  = top;container.innerHTML  = this._renderButtonContainers();this._renderButtons();}
this.draw = function() {
this.updateByField();var htmlCode = this.render();this._elm.insertAdjacentHTML('afterEnd', htmlCode);this._renderButtons();}
this._renderButtons = function() {
var arrowUpDivName   = this._objectId + '_btnUpDiv';var arrowDownDivName = this._objectId + '_btnDownDiv';var arrowUpObjName   = this._objectId + '_btnUpObj';var arrowDownObjName = this._objectId + '_btnDownObj';var buttonHeights = this._getButtonHeights();this.buttonUp.height    = buttonHeights.button1;this.buttonDown.height  = buttonHeights.button2;this.buttonUp.drawInto(arrowUpDivName);eval(arrowUpObjName+' = Bs_Objects['+this._id+'].buttonUp;');this.buttonDown.drawInto(arrowDownDivName);eval(arrowDownObjName+' = Bs_Objects['+this._id+'].buttonDown;');}
this._renderButtonContainers = function(arrowUpDivName, arrowDownDivName) {
if (typeof(arrowUpDivName)   == 'undefined') arrowUpDivName   = this._objectId + '_btnUpDiv';if (typeof(arrowDownDivName) == 'undefined') arrowDownDivName = this._objectId + '_btnDownDiv';var bDiv = new Array;bDiv[bDiv.length] = '<div style="display:block;" id="' + arrowUpDivName   + '"></div>';bDiv[bDiv.length] = '<div style="display:block;" id="' + arrowDownDivName + '"></div>';return bDiv.join('');}
this.fixValue = function(value) {
value = parseInt(value, 10);if (isNaN(value)) {
if (!isNaN(this.minValue)) return this.minValue;return 0;}
if (!bs_isNull(this.minValue) && (value < this.minValue)) value = this.minValue;if (!bs_isNull(this.maxValue) && (value > this.maxValue)) value = this.maxValue;return value;}
this._zeroFillValue = function(value) {
if (typeof(this.zeroFill) != 'undefined') {
var numLength = (value + '').length;for (var i=numLength; i<this.zeroFill; i++) {
value = '0' + value;}
}
return value;}
this.isValidValue = function(value) {
if (isNaN(value)) return false;if (!bs_isNull(this.minValue) && (value < this.minValue)) return false;if (!bs_isNull(this.maxValue) && (value > this.maxValue)) return false;return true;}
this.onKeyDown = function() {
if ((window.event.keyCode <= 90) && (window.event.keyCode >= 65)) return false;switch (window.event.keyCode) {
case 40:
if (!this.fireEvent('onBeforeChange')) return false;this.decrease();this.fireEvent('onAfterChange');return false;break;case 38:
if (!this.fireEvent('onBeforeChange')) return false;this.increase();this.fireEvent('onAfterChange');return false;break;}
return true;}
this.onKeyUp = function() {
if (this.isValidValue(this._elm.value)) {
if (!this.fireEvent('onBeforeChange')) return;this.updateByField();this.fireEvent('onAfterChange');}
}
this.onClickButtonUp = function() {
if (!this.fireEvent('onBeforeChange')) return;this.increase();this.fireEvent('onAfterChange');}
this.onClickButtonDown = function() {
if (!this.fireEvent('onBeforeChange')) return;this.decrease();this.fireEvent('onAfterChange');}
this.onFocus = function() {
this.updateByField();this._elm.select();}
this.onWheel = function() {
if (!this.fireEvent('onBeforeChange')) return;if (event.wheelDelta > 0) {
this.increase();} else if (event.wheelDelta < 0) {
this.decrease();}
this.fireEvent('onAfterChange');}
this.onBlur = function() {
this.updateByField();}
this.updateByField = function() {
var oldVal = this._elm.value;if (isNaN(oldVal) || (oldVal == '') || (oldVal.length == 0)) oldVal = this.minValue;if ((this._value != oldVal)) {
this.setValue(oldVal);}
return (this._value != oldVal);}
this.toggleButtonDisplay = function(show) {
var elm = document.getElementById(this._objectId + '_btnContainer');elm.style.display = (show) ? 'block' : 'none';}
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

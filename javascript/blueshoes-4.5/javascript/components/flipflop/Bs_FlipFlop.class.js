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
if (!Bs_Objects) {var Bs_Objects = [];};function Bs_FlipFlop() {
this._disabled = false;this.buttonSelect;this.buttonSelectAll;this.buttonDeselect;this.buttonDeselectAll;this.fieldAvailableCssClass;this.fieldSelectedCssClass;this.textAvailable = 'Available:';this.textAvailableCssClass;this.textSelected = 'Selected:';this.textSelectedCssClass;this.showCaptionLine = false;this.captionLineClass;this.moveOnDblClick = true;this.moveOnClick    = false;this.maxSelectedNumber;this.maxSelectedWarning = 'No more than __maxSelectedNumber__ options can be selected.';this._renderTopToBottom = false;this.hiddenFieldName;this._fldAvailable;this._fldSelected;this._attachedEvents;this._lastOnClickOptionElm;this._constructor = function() {
this._id = Bs_Objects.length;Bs_Objects[this._id] = this;this._objectId = "Bs_FlipFlop_"+this._id;this.buttonSelect = new Bs_Button();this.buttonSelect.objectName      = this._objectId + '_btnSel';this.buttonSelect.imgName         = 'bs_forward.gif';this.buttonSelect.title           = 'Select';this.buttonSelect.cssClassDefault = 'bsBtnMouseOver';this.buttonSelect.attachEvent('Bs_Objects['+this._id+'].selectSelected();');this.buttonSelectAll = new Bs_Button();this.buttonSelectAll.objectName      = this._objectId + '_btnSelAll';this.buttonSelectAll.imgName         = 'bs_forforward.gif';this.buttonSelectAll.title           = 'Select All';this.buttonSelectAll.cssClassDefault = 'bsBtnMouseOver';this.buttonSelectAll.attachEvent('Bs_Objects['+this._id+'].selectAll();');this.buttonDeselect = new Bs_Button();this.buttonDeselect.objectName      = this._objectId + '_btnDe';this.buttonDeselect.imgName         = 'bs_back.gif';this.buttonDeselect.title           = 'Deselect';this.buttonDeselect.cssClassDefault = 'bsBtnMouseOver';this.buttonDeselect.attachEvent('Bs_Objects['+this._id+'].deselectSelected();');this.buttonDeselectAll = new Bs_Button();this.buttonDeselectAll.objectName      = this._objectId + '_btnDeAll';this.buttonDeselectAll.imgName         = 'bs_baback.gif';this.buttonDeselectAll.title           = 'Deselect All';this.buttonDeselectAll.cssClassDefault = 'bsBtnMouseOver';this.buttonDeselectAll.attachEvent('Bs_Objects['+this._id+'].deselectAll();');}
this.convertField = function(elementId) {
var origFld = document.getElementById(elementId);if (origFld.disabled) this.setDisabled(true);this.hiddenFieldName = origFld.name;if (this.hiddenFieldName.substr(this.hiddenFieldName.length -2) != '[]') this.hiddenFieldName += '[]';var s = new Bs_FormFieldSelect();s.init(origFld);var fldOne = '<select name="' + elementId + '_fldAvailable" id="' + elementId + '_fldAvailable" size="' + origFld.size + '" multiple';fldOne += ' onclick="Bs_Objects['+this._id+'].onClickAvailable(this);"';fldOne += ' ondblclick="Bs_Objects['+this._id+'].onDblClickAvailable();"';if (typeof(this.fieldAvailableCssClass) != 'undefined') {
fldOne += ' class="' + this.fieldAvailableCssClass + '"';}
if (this._disabled) fldOne += ' disabled';fldOne += '>';var fldTwo = '<select name="' + elementId + '_fldSelected" id="' + elementId + '_fldSelected" size="' + origFld.size + '" multiple';fldTwo += ' onclick="Bs_Objects['+this._id+'].onClickSelected(this);"';fldTwo += ' ondblclick="Bs_Objects['+this._id+'].onDblClickSelected();"';if (typeof(this.fieldSelectedCssClass) != 'undefined') {
fldTwo += ' class="' + this.fieldSelectedCssClass + '"';}
if (this._disabled) fldTwo += ' disabled';fldTwo += '>';var defaultsArr = new Array;for (var i=0; i<origFld.options.length; i++) {
var key = origFld.getValueOrText(i);if (origFld.options[i].selected) {
defaultsArr[defaultsArr.length] = key;fldTwo += '<option value="' + key + '">' + origFld.options[i].text + '</option>';} else {
fldOne += '<option value="' + key + '">' + origFld.options[i].text + '</option>';}
}
fldOne += '</select>';fldTwo += '</select>';var out = '';out += '<table border="0" cellspacing="0" cellpadding="0"><tr><td valign="top"';if (typeof(this.textAvailableCssClass) != 'undefined') out += ' class="' + this.textAvailableCssClass + '"';out += '>';out += this.textAvailable;out += '</td><td>&nbsp;</td>';var textSelectedWithTd = '';textSelectedWithTd += '<td valign="top"';if (typeof(this.textSelectedCssClass) != 'undefined') textSelectedWithTd += ' class="' + this.textSelectedCssClass + '"';textSelectedWithTd += '>';textSelectedWithTd += this.textSelected;textSelectedWithTd += '</td>';if (!this._renderTopToBottom) {
out += textSelectedWithTd;}
out += '</tr><tr><td valign="top">';out += fldOne;out += '</td>';if (this._renderTopToBottom) out += '</tr><tr>';var buttonDivStyle = (this._renderTopToBottom) ? ' style="display:inline;"' : '';out += '<td align="center" valign="middle">';out += '<div id="' + elementId + '_btnSel"'    + buttonDivStyle + '></div>';out += '<div id="' + elementId + '_btnSelAll"' + buttonDivStyle + '></div>';out += '<div id="' + elementId + '_btnDe"'     + buttonDivStyle + '></div>';out += '<div id="' + elementId + '_btnDeAll"'  + buttonDivStyle + '></div>';out += '</td>';if (this._renderTopToBottom) out += '</tr><tr>';if (this._renderTopToBottom) {
out += textSelectedWithTd;out += '</tr><tr>';}
out += '<td valign="top">';out += fldTwo;out += '</td></tr>';if (this.showCaptionLine) {
out += '<tr><td colspan="3"';if (typeof(this.captionLineClass) != 'undefined') {
out += 'class="' + this.captionLineClass + '"';}
out += ' id="' + this._objectId + '_captionLine"';out += '>';out += '</td></tr>';}
out += '</table>';out += '<span id="' + this._objectId + '_hiddenFldSpan">';out += this._renderHiddenField(defaultsArr);out += '<span>';origFld.outerHTML = out;this._fldAvailable = document.getElementById(elementId + '_fldAvailable');this._fldSelected  = document.getElementById(elementId + '_fldSelected');s.init(this._fldAvailable);s.init(this._fldSelected);if (!this.moveOnClick) {
this.buttonSelect.drawInto(elementId + '_btnSel');eval(this._objectId + '_btnSel' + ' = Bs_Objects['+this._id+'].buttonSelect;');this.buttonDeselect.drawInto(elementId + '_btnDe');eval(this._objectId + '_btnDe' + ' = Bs_Objects['+this._id+'].buttonDeselect;');}
if (typeof(this.maxSelectedNumber) == 'undefined') {
try {
this.buttonSelectAll.drawInto(elementId + '_btnSelAll');eval(this._objectId + '_btnSelAll' + ' = Bs_Objects['+this._id+'].buttonSelectAll;');this.buttonDeselectAll.drawInto(elementId + '_btnDeAll');eval(this._objectId + '_btnDeAll' + ' = Bs_Objects['+this._id+'].buttonDeselectAll;');} catch (e) {
}
}
}
this.getValue = function() {
return this._fldSelected.getAllKeys();}
this.setOptions = function(options) {
var selected = this._fldSelected.getAllKeys();this._fldAvailable.prune();this._fldSelected.prune();for (var i=0; i<selected.length; i++) {
if (typeof(options[selected[i]]) != 'undefined') {
var newOpt = new Option(options[selected[i]], selected[i], false, false);this._fldSelected.options[this._fldSelected.length] = newOpt;delete options[selected[i]];}
}
for (var key in options) {
var newOpt = new Option(options[key], key, false, false);this._fldAvailable.options[this._fldAvailable.length] = newOpt;}
}
this.selectSelected = function() {
this._fldAvailable.moveSelectedTo(this._fldSelected);this._updateHiddenField();}
this.selectAll = function() {
this._fldAvailable.moveAllTo(this._fldSelected);this._updateHiddenField();}
this.deselectSelected = function() {
this._fldSelected.moveSelectedTo(this._fldAvailable);this._updateHiddenField();}
this.deselectAll = function() {
this._fldSelected.moveAllTo(this._fldAvailable);this._updateHiddenField();}
this.moveAvailableOptionToSelected = function(optionValue) {
if (this.maxSelectionReached()) {
this.alertMaxSelectionWarning();} else {
var status = this._fldAvailable.moveTo(this._fldSelected, optionValue);this._updateHiddenField();return status;}
}
this.moveSelectedOptionToAvailable = function(optionValue) {
var status = this._fldSelected.moveTo(this._fldAvailable, optionValue);this._updateHiddenField();return status;}
this.onClickAvailable = function() {
if (this.disabled) return;this._onClickAvailableOption();}
this.onClickSelected = function() {
if (this.disabled) return;this._onClickSelectedOption();}
this.onDblClickAvailable = function() {
if (this.disabled) return;if (this.moveOnDblClick) this.moveAvailableOptionToSelected(event.srcElement.value);}
this.onDblClickSelected = function() {
if (this.disabled) return;if (this.moveOnDblClick) this.moveSelectedOptionToAvailable(event.srcElement.value);}
this._onClickAvailableOption = function(b) {
if (b == true) {
try {
var srcElm = (!bs_isNull(event) && !bs_isNull(event.srcElement)) ? event.srcElement : this._lastOnClickOptionElm;var opt    = srcElm.options[srcElm.selectedIndex];if (this.showCaptionLine) {
document.getElementById(this._objectId + '_captionLine').innerHTML = opt.value;}
} catch (e) {
}
if (this.moveOnClick) {
this.moveAvailableOptionToSelected(srcElm.value);}
} else {
this._lastOnClickOptionElm = event.srcElement;setTimeout('Bs_Objects['+this._id+']._onClickAvailableOption(true)', 50);return;}
}
this._onClickSelectedOption = function(b) {
if (b == true) {
try {
var srcElm = (!bs_isNull(event) && !bs_isNull(event.srcElement)) ? event.srcElement : this._lastOnClickOptionElm;var opt    = srcElm.options[srcElm.selectedIndex];if (this.showCaptionLine) {
document.getElementById(this._objectId + '_captionLine').innerHTML = opt.value;}
} catch (e) {
}
if (this.moveOnClick) {
this.moveSelectedOptionToAvailable(srcElm.value);}
} else {
this._lastOnClickOptionElm = event.srcElement;setTimeout('Bs_Objects['+this._id+']._onClickSelectedOption(true)', 50);return;}
}
this.setRenderOrientation = function(upDown) {
this._renderTopToBottom = upDown;this.buttonSelect.imgName         = (this._renderTopToBottom) ? 'bs_down.gif'   : 'bs_forward.gif';this.buttonSelectAll.imgName      = (this._renderTopToBottom) ? 'bs_dodown.gif' : 'bs_foforward.gif';this.buttonDeselect.imgName       = (this._renderTopToBottom) ? 'bs_up.gif'     : 'bs_back.gif';this.buttonDeselectAll.imgName    = (this._renderTopToBottom) ? 'bs_upup.gif'   : 'bs_baback.gif';}
this.maxSelectionReached = function() {
return (this.howManySelected() >= this.maxSelectedNumber);}
this.alertMaxSelectionWarning = function() {
		alert(this.maxSelectedWarning.replace('__maxSelectedNumber__', this.maxSelectedNumber));
}
this.howManySelected = function() {
return this._fldSelected.options.length;}
this.setDisabled = function(disabled) {
this._disabled = disabled;if (this._disabled) {
var btnValue = 0;} else {
var btnValue = 1;}
if (!bs_isNull(this.buttonSelect))      this.buttonSelect.setStatus(btnValue);if (!bs_isNull(this.buttonSelectAll))   this.buttonSelectAll.setStatus(btnValue);if (!bs_isNull(this.buttonDeselect))    this.buttonDeselect.setStatus(btnValue);if (!bs_isNull(this.buttonDeselectAll)) this.buttonDeselectAll.setStatus(btnValue);if (!bs_isNull(this._fldAvailable)) this._fldAvailable.disabled = this._disabled;if (!bs_isNull(this._fldSelected))  this._fldSelected.disabled  = this._disabled;}
this.getDisabled = function() {
return this._disabled;}
this._renderHiddenField = function(defaultsArr) {
var ret = new Array;for (var i=0; i<defaultsArr.length; i++) {
ret[ret.length] = '<input type="hidden" name="' + this.hiddenFieldName + '" value="' + defaultsArr[i] + '">';}
return ret.join('');}
this._updateHiddenField = function() {
var defaultsArr = this._fldSelected.getAllKeys();var str         = this._renderHiddenField(defaultsArr);document.getElementById(this._objectId + '_hiddenFldSpan').innerHTML = str;}
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

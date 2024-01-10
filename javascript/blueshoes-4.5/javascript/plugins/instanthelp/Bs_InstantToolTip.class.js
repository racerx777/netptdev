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
var  GLOBAL_itt_last_objectName = null;function Bs_InstantToolTip() {
this._objectName = '';this._ih = null;this.mode = false;this.eventX = false;this.eventY = false;this.highlightCursor = 'help';this.highlightMode = 'border';this.rasterImage = '/_bsImages/plugins/instanthelp/raster/yellow400.gif';this.toolTipLayerId = 'tthLayer';this.toolTipLayerContentId = 'tthLayerContent';this._layerRightDown;this._layerLeftDown;this._layerRightUp;this._layerLeftUp;this.init = function(objectName, ih) {
this._objectName  = objectName;this._ih          = ih;}
this.start = function() {
if (this.mode == true) {
this.release();return;}
this.mode = true;var e = document.getElementsByTagName("span");for (var i=0; i<e.length; i++) {
if (typeof(e[i]['dictName']) != 'undefined') {
var dictName = e[i]['dictName'];} else {
var dictName = e[i].getAttribute('dictName');if (typeof(dictName) == 'undefined') continue;if (dictName == null) continue;}
if (typeof(this.highlightMode) == 'string') {
var highlightMode = new Array();highlightMode[this.highlightMode] = true;} else {
var highlightMode = this.highlightMode;}
if (typeof(highlightMode['background']) != 'undefined') {
e[i].style.background = "yellow";}
if (highlightMode['raster']) {
for (var z=0; z<1; z++) {
var elmWidth  = parseInt(e[i].clientWidth);var elmHeight = parseInt(e[i].clientHeight);if (elmWidth <= 0) {
elmWidth  = parseInt(e[i].offsetWidth);elmHeight = parseInt(e[i].offsetHeight);}
var rasterId = this._getRasterId(e[i]);var rasterElm = document.getElementById(rasterId);if (!rasterElm) {
try {
var divTagStr = '<div id="' + rasterId + '" style="display:none; overflow:hidden;"><img src="' + this.rasterImage + '" border="0"></div>';var bodyTag = document.getElementsByTagName('body');bodyTag = bodyTag[0];bodyTag.insertAdjacentHTML('beforeEnd', divTagStr);} catch (e) {
break;}
var rasterElm = document.getElementById(rasterId);}
rasterElm.style.width    = elmWidth;rasterElm.style.height   = elmHeight;rasterElm.style.position = 'absolute';rasterElm.style.left     = getAbsolutePos(e[i]).x;rasterElm.style.top      = getAbsolutePos(e[i]).y;GLOBAL_itt_last_objectName      = this._objectName;rasterElm.onmouseover    = function () { this.style.display = 'none'; window.setTimeout("if (" + GLOBAL_itt_last_objectName + ".mode) document.getElementById('" + this.id + "').style.display = 'block';", 3000); };rasterElm.style.zIndex   = 10000;rasterElm.style.display  = 'block';}
}
if (highlightMode['border']) {
e[i].style.border = "1px solid yellow";}
if (this.highlightCursor == 'help') {
e[i].style.cursor = 'help';} else {
e[i].style.cursor = (typeof(e[i]['dictName']) != 'undefined') ? "hand" : 'pointer';}
}
}
this.release = function() {
this.mode = false;document.onclick = null;var l = document.getElementById(this.toolTipLayerId);if (l) l.style.display = "none";var e = document.getElementsByTagName("span");for (var i=0; i<e.length; i++) {
if (typeof(e[i]['dictName']) != 'undefined') {
var dictName = e[i]['dictName'];} else {
var dictName = e[i].getAttribute('dictName');if (typeof(dictName) == 'undefined') continue;if (dictName == null) continue;}
if (typeof(this.highlightMode) == 'string') {
var highlightMode = new Array();highlightMode[this.highlightMode] = true;} else {
var highlightMode = this.highlightMode;}
if (typeof(highlightMode['background']) != 'undefined') {
e[i].style.background = "";}
if (highlightMode['raster']) {
var rasterId = this._getRasterId(e[i]);var rasterElm = document.getElementById(rasterId);if (rasterElm) rasterElm.style.display = 'none';}
if (highlightMode['border']) {
e[i].style.border = "none";}
e[i].style.cursor     = "default";}
}
this.query = function(obj, event) {
var ret = false;if (this.mode) {
this.eventX = event.clientX;this.eventY = event.clientY;if (typeof(obj['dictName']) != 'undefined') {
var dictName = obj['dictName'];} else {
var dictName = obj.getAttribute('dictName');}
if (typeof(obj['strKey']) != 'undefined') {
var strKey = obj['strKey'];} else {
var strKey = obj.getAttribute('strKey');}
if (typeof(obj['lang']) != 'undefined') {
var lang = obj['lang'];} else {
var lang = obj.getAttribute('lang');}
this._ih.getText(dictName, strKey, lang, this._objectName + '.fireToolTip()');try {
event.cancelBubble = true;} catch (e) {
}
ret = true;}
return ret;}
this.fireToolTip = function(value) {
this._addLayer();var l = document.getElementById(this.toolTipLayerId);if (!l) {
alert('please add the layer named ' + this.toolTipLayerId + ' to your html code.');return;}
var cursorWidth  = 22;var windowWidth  = (window.innerWidth)  ? window.innerWidth  : document.body.offsetWidth;var windowHeight = (window.innerHeight) ? window.innerHeight : document.body.offsetHeight;var tipWidth     = parseInt(l.style.width);var tipHeight    = parseInt(l.style.height);var posX = this.eventX -cursorWidth;var posY = this.eventY;if ((tipWidth + posX > windowWidth) && (posX - tipWidth >= 0)) {
posX = posX - tipWidth + (2 * cursorWidth);this._fireToolTipTo('down-left', posX, posY, value);var doOnRight = false;} else {
this._fireToolTipTo('down-right', posX, posY, value);var doOnRight = true;}
var l = document.getElementById(this.toolTipLayerId);tipHeight = parseInt(l.clientHeight);if (tipHeight <= 0) {
tipHeight = parseInt(l.offsetHeight);}
if (tipHeight + posY + 20 > windowHeight) {
do {
posY -= tipHeight;if (posY < 0) {
break;}
if (doOnRight) {
this._fireToolTipTo('up-right', posX, posY, value);} else {
this._fireToolTipTo('up-left', posX, posY, value);}
} while (false);}
document.onclick = new Function(this._objectName + '.release()');}
this._fireToolTipTo = function(where, posX, posY, value) {
if (document.body) {
if (document.body.scrollTop  > 0) posY += document.body.scrollTop;if (document.body.scrollLeft > 0) posX += document.body.scrollLeft;}
switch (where) {
case 'down-right':
this._initLayerRightDown();break;case 'down-left':
this._initLayerLeftDown();break;case 'up-right':
this._initLayerRightUp();break;case 'up-left':
this._initLayerLeftUp();break;}
var d = document.getElementById(this.toolTipLayerId);switch (where) {
case 'down-right':
d.innerHTML = this._layerRightDown;break;case 'down-left':
d.innerHTML = this._layerLeftDown;break;case 'up-right':
d.innerHTML = this._layerRightUp;break;case 'up-left':
d.innerHTML = this._layerLeftUp;break;}
var l = document.getElementById(this.toolTipLayerId);l.style.left    = posX;l.style.top     = posY;l.style.display = "block";var lc = document.getElementById(this.toolTipLayerContentId);lc.innerHTML = value;}
this._getRasterId = function(elm) {
if (typeof(elm.id) != 'undefined' && (elm.id != '')) {
var rasterId = elm.id;} else {
if (elm.getAttribute) {
var rasterId = elm.getAttribute('dictName') + "_" + elm.getAttribute('strKey') + "_";var lang = elm.getAttribute('lang');if ((typeof(lang) != 'undefined') && (lang != null)) rasterId += lang;} else {
var rasterId = elm.dictName + "_" + elm.strKey + "_";if (typeof(elm.lang) != 'undefined') rasterId += elm.lang;}
}
rasterId += '_raster';		rasterId = rasterId.replace(/[^a-zA-Z0-9_-]/g, '');
return rasterId;}
this._addLayer = function() {
var d = document.getElementById(this.toolTipLayerId);if (!d) {
try {
var divTagStr = '<div id="' + this.toolTipLayerId + '"></div>';var bodyTag = document.getElementsByTagName('body');bodyTag = bodyTag[0];bodyTag.insertAdjacentHTML('beforeEnd', divTagStr);d = document.getElementById(this.toolTipLayerId);} catch (e) {
alert('webmaster: please add the layer named ' + this.toolTipLayerId + ' to your html code.');return;}
}
if (d.innerHTML == '') {
d.style.display    = 'none';d.style.position   = 'absolute';d.style.width      = '200px';d.style.height     = '40px';d.style.overflow   = 'visible';d.style.zIndex     = '999999';d.style.fontFamily = 'Arial,Helvetica,sans-serif';d.style.fontSize   = '12px';this._initLayerRightDown();d.innerHTML = this._layerRightDown;}
}
this._initLayerRightDown = function() {
var content = '';content += '<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">';content += '  <tr height="16">';content += '    <td height="16" width="3"><img src="/_bsImages/plugins/instanthelp/layerCornerTopLeft.gif" width="3" height="16"></td>';content += '    <td height="16"><table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="19" height="16"><img src="/_bsImages/plugins/instanthelp/layerPointer.gif" width="19" height="16"></td><td background="/_bsImages/plugins/instanthelp/layerLineTop.gif"><div><font size="1">&nbsp;</font></div></td></tr></table></td>';content += '    <td height="16" width="4"><img src="/_bsImages/plugins/instanthelp/layerCornerTopRight.gif" width="4" height="16"></td>';content += '  </tr>';content += '  <tr>';content += '    <td width="3"><img src="/_bsImages/plugins/instanthelp/layerLineLeft.gif" width="3" height="100%"></td>';content += '    <td bgcolor="#FFFFCE"><div id="tthLayerContent" style="padding:10px;"></div></td>';content += '    <td width="4" background="/_bsImages/plugins/instanthelp/layerLineRight.gif"></td>';content += '  </tr>';content += '  <tr height="4">';content += '    <td height="4" width="3"><img src="/_bsImages/plugins/instanthelp/layerCornerBottomLeft.gif" width="3" height="4"></td>';content += '    <td height="4" background="/_bsImages/plugins/instanthelp/layerLineBottom.gif"></td>';content += '    <td height="4" width="4"><img src="/_bsImages/plugins/instanthelp/layerCornerBottomRight.gif"></td>';content += '  </tr>';content += '</table>';this._layerRightDown = content;}
this._initLayerLeftDown = function() {
var content = '';content += '<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">';content += '  <tr height="16">';content += '    <td height="16" width="3"><img src="/_bsImages/plugins/instanthelp/layerCornerTopLeft.gif" width="3" height="16"></td>';content += '    <td height="16"><table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0"><tr><td background="/_bsImages/plugins/instanthelp/layerLineTop.gif"><div><font size="1">&nbsp;</font></div></td><td width="19" height="16"><img src="/_bsImages/plugins/instanthelp/layerPointerLeftUp.gif" width="19" height="16"></td></tr></table></td>';content += '    <td height="16" width="4"><img src="/_bsImages/plugins/instanthelp/layerCornerTopRight.gif" width="4" height="16"></td>';content += '  </tr>';content += '  <tr>';content += '    <td width="3"><img src="/_bsImages/plugins/instanthelp/layerLineLeft.gif" width="3" height="100%"></td>';content += '    <td bgcolor="#FFFFCE"><div id="tthLayerContent" style="padding:10px;"></div></td>';content += '    <td width="4" background="/_bsImages/plugins/instanthelp/layerLineRight.gif"></td>';content += '  </tr>';content += '  <tr height="4">';content += '    <td height="4" width="3"><img src="/_bsImages/plugins/instanthelp/layerCornerBottomLeft.gif" width="3" height="4"></td>';content += '    <td height="4" background="/_bsImages/plugins/instanthelp/layerLineBottom.gif"></td>';content += '    <td height="4" width="4"><img src="/_bsImages/plugins/instanthelp/layerCornerBottomRight.gif"></td>';content += '  </tr>';content += '</table>';this._layerLeftDown = content;}
this._initLayerRightUp = function() {
var content = '';content += '<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">';content += '  <tr height="4">';content += '    <td height="4" width="3"><img src="/_bsImages/plugins/instanthelp/layerCornerBottomLeft_Flipped.gif" width="3" height="4"></td>';content += '    <td height="4" background="/_bsImages/plugins/instanthelp/layerLineBottom_Flipped.gif"></td>';content += '    <td height="4" width="4"><img src="/_bsImages/plugins/instanthelp/layerCornerBottomRight_Flipped.gif"></td>';content += '  </tr>';content += '  <tr>';content += '    <td width="3"><img src="/_bsImages/plugins/instanthelp/layerLineLeft.gif" width="3" height="100%"></td>';content += '    <td bgcolor="#FFFFCE"><div id="tthLayerContent" style="padding:10px;"></div></td>';content += '    <td width="4" background="/_bsImages/plugins/instanthelp/layerLineRight.gif"></td>';content += '  </tr>';content += '  <tr height="16">';content += '    <td height="16" width="3"><img src="/_bsImages/plugins/instanthelp/layerCornerTopLeft_Flipped.gif" width="3" height="16"></td>';content += '    <td height="16"><table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="19" height="16"><img src="/_bsImages/plugins/instanthelp/layerPointerRightDown.gif" width="19" height="16"></td><td background="/_bsImages/plugins/instanthelp/layerLineTop_Flipped.gif"><div><font size="1">&nbsp;</font></div></td></tr></table></td>';content += '    <td height="16" width="4"><img src="/_bsImages/plugins/instanthelp/layerCornerTopRight_Flipped.gif" width="4" height="16"></td>';content += '  </tr>';content += '</table>';this._layerRightUp = content;}
this._initLayerLeftUp = function() {
var content = '';content += '<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">';content += '  <tr height="4">';content += '    <td height="4" width="3"><img src="/_bsImages/plugins/instanthelp/layerCornerBottomLeft_Flipped.gif" width="3" height="4"></td>';content += '    <td height="4" background="/_bsImages/plugins/instanthelp/layerLineBottom_Flipped.gif"></td>';content += '    <td height="4" width="4"><img src="/_bsImages/plugins/instanthelp/layerCornerBottomRight_Flipped.gif"></td>';content += '  </tr>';content += '  <tr>';content += '    <td width="3"><img src="/_bsImages/plugins/instanthelp/layerLineLeft.gif" width="3" height="100%"></td>';content += '    <td bgcolor="#FFFFCE"><div id="tthLayerContent" style="padding:10px;"></div></td>';content += '    <td width="4" background="/_bsImages/plugins/instanthelp/layerLineRight.gif"></td>';content += '  </tr>';content += '  <tr height="16">';content += '    <td height="16" width="3"><img src="/_bsImages/plugins/instanthelp/layerCornerTopLeft_Flipped.gif" width="3" height="16"></td>';content += '    <td height="16"><table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0"><tr><td background="/_bsImages/plugins/instanthelp/layerLineTop_Flipped.gif"><div><font size="1">&nbsp;</font></div></td><td width="19" height="16"><img src="/_bsImages/plugins/instanthelp/layerPointerLeftDown.gif" width="19" height="16"></td></tr></table></td>';content += '    <td height="16" width="4"><img src="/_bsImages/plugins/instanthelp/layerCornerTopRight_Flipped.gif" width="4" height="16"></td>';content += '  </tr>';content += '</table>';this._layerLeftUp = content;}
}

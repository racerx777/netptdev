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
var currentOuterR;var currentOuterG;var currentOuterB;var currentColor;var myColors = new Array();if (document.cookie) {
var allCookies = document.cookie.split('; ');for (var i=0; i<allCookies.length; i++) {
if (allCookies[i].substr(0,11) == 'bs_colSelMy') {
myCook = allCookies[i];var Wertstart = myCook.indexOf("=") + 1;if (Wertstart == 0) break;var Wertende = myCook.indexOf(";");if (Wertende == -1) Wertende = document.cookie.length;myColorStr = myCook.substring(Wertstart,Wertende);var myColors2 = myColorStr.split(',');for (var j=0; j<myColors2.length; j++) {
if (myColors2[j] != '') {
myColors[myColors.length] = hexStrToRgbArr(myColors2[j]);}
}
}
}
}
var wysiwygObj;var callbackLoad;var callbackSave;function init() {
t = new Bs_TabSet('t', 'tabset');var pickerTab = new Object;pickerTab.caption   = 'Picker';pickerTab.container = document.getElementById('ps');pickerTab.onFocus   = pickerTabFocus;pickerTab.onBlur    = pickerTabBlur;t.addTab(pickerTab);var webTab = new Object;webTab.caption   = 'Web Colors';webTab.container = document.getElementById('webSafe');webTab.onFocus   = webTabFocus;t.addTab(webTab);var myTab = new Object;myTab.caption   = 'My Colors';myTab.container = document.getElementById('tabMyColors');myTab.onFocus   = updateMyColors;t.addTab(myTab);t.draw();setColorByRgb(219, 27, 219);nf1 = new Bs_NumberField('nf1', 'rgb_r');nf1.buttonUp.imgPath   = '../../components/numberfield/img/';nf1.buttonDown.imgPath = '../../components/numberfield/img/';nf1.minValue = 0;nf1.maxValue = 255;nf1.draw();nf2 = new Bs_NumberField('nf2', 'rgb_g');nf2.buttonUp.imgPath   = '../../components/numberfield/img/';nf2.buttonDown.imgPath = '../../components/numberfield/img/';nf2.minValue = 0;nf2.maxValue = 255;nf2.draw();nf3 = new Bs_NumberField('nf3', 'rgb_b');nf3.buttonUp.imgPath   = '../../components/numberfield/img/';nf3.buttonDown.imgPath = '../../components/numberfield/img/';nf3.minValue = 0;nf3.maxValue = 255;nf3.draw();nf4 = new Bs_NumberField('nf4', 'hsb_h');nf4.buttonUp.imgPath   = '../../components/numberfield/img/';nf4.buttonDown.imgPath = '../../components/numberfield/img/';nf4.minValue = 0;nf4.maxValue = 360;nf4.draw();nf5 = new Bs_NumberField('nf5', 'hsb_s');nf5.buttonUp.imgPath   = '../../components/numberfield/img/';nf5.buttonDown.imgPath = '../../components/numberfield/img/';nf5.minValue = 0;nf5.maxValue = 100;nf5.draw();nf6 = new Bs_NumberField('nf6', 'hsb_b');nf6.buttonUp.imgPath   = '../../components/numberfield/img/';nf6.buttonDown.imgPath = '../../components/numberfield/img/';nf6.minValue = 0;nf6.maxValue = 100;nf6.draw();bs_ttt_initAll();var colorPickerCircleIcon = bsImgDir + 'cursors/colorPickerCircle.cur';document.getElementById('psColorSelector').style.cursor = colorPickerCircleIcon;document.getElementById('circle').style.cursor          = colorPickerCircleIcon;if (window.dialogArguments) {
setDefaultValues(window.dialogArguments);} else {
var qsArr      = queryStringToHash();if (typeof(qsArr['objectName']) != 'undefined') {
wysiwygObjName = qsArr['objectName'];} else if (typeof(qsArr['objectId']) != 'undefined') {
wysiwygObjName = qsArr['objectId'];} else {
wysiwygObjName = 'foo';}
callbackLoad   = qsArr['callbackLoad'];callbackSave   = qsArr['callbackSave'];var parentOpener = (opener) ? opener : parent;wysiwygObj = parentOpener['Bs_Objects'][qsArr['id']];if (!bs_isNull(wysiwygObj[callbackLoad])) {
setDefaultValues(wysiwygObj[callbackLoad]());}
}
}
function setDefaultValues(paramObj) {
if (!bs_isNull(paramObj.color)) {
setColorByHex(paramObj.color);}
}
function setBack() {
var hexCode = rgbArrToHexStr(currentColor);if (window.dialogArguments) {
window.returnValue = hexCode;} else {
wysiwygObj[callbackSave](hexCode);}
window.close();}
function pickerTabFocus() {
if (ie) {
document.getElementById('doubleArrow').style.display = 'block';document.getElementById('circle').style.display      = 'block';}
}
function pickerTabBlur() {
document.getElementById('doubleArrow').style.display = 'none';document.getElementById('circle').style.display      = 'none';}
function setColorByRgb(r, g, b, noUpdateForm) {
currentOuterR = r;currentOuterG = g;currentOuterB = b;var P=Point3;var hsb = P.RGBtoHSB(new Array(r, g, b));var rgb = P.HSBtoRGB(new Array(hsb.x, 1, 1));umByHue(hsb.x * 360);var rgb = new Array(r, g, b);updateCircle(rgb, hsb);if (!noUpdateForm) updateForm(rgb, new Array(parseInt(hsb.x * 360), parseInt(hsb.y * 100), parseInt(hsb.z * 100)));var hexCode = rgbArrToHexStr(rgb);updatePreviewBox(hexCode, rgb);}
function setColorByHsb(h, s, b, noUpdateForm) {
var P=Point3;var hsb = new Array(h /360, s /100, b /100)
hsb.x = hsb[0];hsb.y = hsb[1];hsb.z = hsb[2];var rgb = P.HSBtoRGB(hsb);currentOuterR = rgb.x;currentOuterG = rgb.y;currentOuterB = rgb.z;umByHue2(h / 360);updateCircle(rgb, hsb);if (!noUpdateForm) updateForm(rgb, new Array(h, s, b));var hexCode = rgbArrToHexStr(rgb);updatePreviewBox(hexCode, rgb);}
function setColorByHex(hex, noUpdateForm) {
var r = hexdec(hex.substr(0,2));var g = hexdec(hex.substr(2,2));var b = hexdec(hex.substr(4,2));setColorByRgb(r, g, b, noUpdateForm);}
function setColorByFormRgb() {
setColorByRgb(document.myForm.rgb_r.value, document.myForm.rgb_g.value, document.myForm.rgb_b.value, false);}
function setColorByFormHsb() {
setColorByHsb(document.myForm.hsb_h.value, document.myForm.hsb_s.value, document.myForm.hsb_b.value, false);}
function setColorByFormHex() {
setColorByHex(document.myForm.hex.value, false)
}
function updatePreviewBox(hex, rgb) {
if (hex.length <= 6) hex = '#' + hex;document.getElementById('colorPreview').style.backgroundColor = hex;if (typeof(rgb) == 'undefined') {
var r = hexdec(hex.substr(0,2));var g = hexdec(hex.substr(2,2));var b = hexdec(hex.substr(4,2));var rgb = new Array(r, g, b);}
if (typeof(rgb.x) != 'undefined') {
rgb[0] = rgb.x;rgb[1] = rgb.y;rgb[2] = rgb.z;}
document.getElementById('colorPreviewRgbR').innerHTML = rgb[0];document.getElementById('colorPreviewRgbG').innerHTML = rgb[1];document.getElementById('colorPreviewRgbB').innerHTML = rgb[2];document.getElementById('colorPreviewHex').innerHTML  = hex.substr(1);currentColor = rgb;}
function updateForm(rgb, hsb) {
if (typeof(rgb.x) != 'undefined') rgb[0] = rgb.x;if (typeof(rgb.y) != 'undefined') rgb[1] = rgb.y;if (typeof(rgb.z) != 'undefined') rgb[2] = rgb.z;document.myForm.rgb_r.value = rgb[0];document.myForm.rgb_g.value = rgb[1];document.myForm.rgb_b.value = rgb[2];document.myForm.hsb_h.value = hsb[0];document.myForm.hsb_s.value = hsb[1];document.myForm.hsb_b.value = hsb[2];document.myForm.hex.value = rgbArrToHexStr(rgb);}
function um(elm) {
color = elm.style.backgroundColor.substr(1);r = hexdec(color.substr(0,2));g = hexdec(color.substr(2,2));b = hexdec(color.substr(4,2));rgb = new Array(r, g, b);var P=Point3;hsb = P.RGBtoHSB(rgb);hue = hsb.x;setColorByHsb(parseInt(hue *360), document.myForm.hsb_s.value, document.myForm.hsb_b.value);}
function umByHue(hue360) {
var divId = 'colorBar_' + parseInt(hue360);var elm = document.getElementById(divId);umByElm(elm);}
function umByElm(elm) {
if (!ie) return;updateDoubleArrow(elm);color = elm.style.backgroundColor.substr(1);r = hexdec(color.substr(0,2));g = hexdec(color.substr(2,2));b = hexdec(color.substr(4,2));rgb = new Array(r, g, b);currentOuterR = rgb[0];currentOuterG = rgb[1];currentOuterB = rgb[2];var P=Point3;hsb = P.RGBtoHSB(rgb);var hue = hsb.x;umByHue2(hue);}
function updateDoubleArrow(elm) {
if (!ie) return;var pos = getAbsolutePos(elm);document.getElementById('doubleArrow').style.left = pos.x -9;document.getElementById('doubleArrow').style.top  = pos.y;}
function updateCircle(rgb, hsb) {
if (!ie) return;var pos = getAbsolutePos(document.getElementById('psColorSelector'));document.getElementById('circle').style.left = pos.x + (hsb.y * 255);document.getElementById('circle').style.top  = pos.y + (255 - (hsb.z * 255));var hexCode = rgbArrToHexStr(rgb);var imgPath = '../../components/editor/img/';document.getElementById('circleImage').src = imgPath + 'circle' + ((isDarkColor(hexCode)) ? 'Light' : 'Dark') + '.gif';}
function umByHue2(hue) {
var divId = 'colorBar_' + parseInt(hue *360);updateDoubleArrow(document.getElementById(divId));var P=Point3;var h = new Array;var lastR;var lastG;var lastB;var ii  = 0;var iii = 0;for (var i=0; i<=100; i++) {
hsb = new Array(hue, i /100, 1);rgb = P.HSBtoRGB(hsb);color = dechex(rgb.x) + '' + dechex(rgb.y) + '' + dechex(rgb.z);h[h.length] = '<SPAN id="" class="matrixLine" style="FILTER:progid:DXImageTransform.Microsoft.Gradient(gradientType=0,startColorStr=#' + color + ',endColorStr=#000000);"></SPAN>';h[h.length] = '<SPAN id="" class="matrixLine" style="FILTER:progid:DXImageTransform.Microsoft.Gradient(gradientType=0,startColorStr=#' + color + ',endColorStr=#000000);"></SPAN>';ii = ii + 0.55;iii++;if (((ii / 1) > 1) && (iii < 100)) {
h[h.length] = '<SPAN id="" class="matrixLine" style="FILTER:progid:DXImageTransform.Microsoft.Gradient(gradientType=0,startColorStr=#' + color + ',endColorStr=#000000);"></SPAN>';ii = ii -1;}
lastR = rgb.x;lastG = rgb.y;lastB = rgb.z;}
document.getElementById('psColorSelector_1').innerHTML = h.join('');}
function upc(elm) {
updatePreviewBox(elm.style.backgroundColor);}
function updateColorByPsSelector(color, noUpdateForm) {
x = event.offsetX;y = event.offsetY;var P=Point3;hsb = P.RGBtoHSB(new Array(currentOuterR, currentOuterG, currentOuterB));var clicked_hsb_s = x / 255;var clicked_hsb_b = (255 -y) / 255;rgb = P.HSBtoRGB(new Array(hsb.x, clicked_hsb_s, clicked_hsb_b));if (rgb.x < 0) rgb.x = 0;if (rgb.y < 0) rgb.y = 0;if (rgb.z < 0) rgb.z = 0;if (!noUpdateForm) updateForm(rgb, new Array(parseInt(hsb.x * 360), parseInt(clicked_hsb_s * 100), parseInt(clicked_hsb_b * 100)));var hexCode = dechex(rgb.x) + '' + dechex(rgb.y) + '' + dechex(rgb.z);updatePreviewBox(hexCode, rgb);document.getElementById('circle').style.left = event.clientX -7;document.getElementById('circle').style.top  = event.clientY -7;var imgPath = '../../components/editor/img/';document.getElementById('circleImage').src = imgPath + 'circle' + ((isDarkColor(hexCode)) ? 'Light' : 'Dark') + '.gif';}
var lastWebColorElm;function selectWebColor(elm, hex) {
if (lastWebColorElm) lastWebColorElm.style.border = '2px inset';lastWebColorElm = elm;elm.style.border = '2px outset';setColorByHex(hex);}
function webTabFocus() {
if (lastWebColorElm) lastWebColorElm.style.border = '2px inset';}
var lastMyColorElm;function selectMyColor(elm, hex) {
if (lastMyColorElm) lastMyColorElm.style.border = '2px inset';lastMyColorElm = elm;elm.style.border = '2px outset';setColorByHex(hex);}
function saveToMyColor() {
for (var i=0; i<myColors.length; i++) {
if ((myColors[i][0] == currentColor[0]) && (myColors[i][1] == currentColor[1]) && (myColors[i][2] == currentColor[2])) return;}
myColors[myColors.length] = currentColor;var colorArr = new Array;for (var i=0; i<myColors.length; i++) {
colorArr[colorArr.length] = rgbArrToHexStr(myColors[i]);}
saveCookieString(colorArr.join(','));}
function emptyMyColors() {
myColors = new Array();saveCookieString('');}
function saveCookieString(str) {
var lifetime = 1000*60*60*24*365;var dateNow = new Date();var dateEnd = new Date(dateNow.getTime() + lifetime);var cookieString = "bs_colSelMy=" + str + "; expires=" + dateEnd.toGMTString() + ";";document.cookie = cookieString;}
function rgbArrToHexStr(rgb) {
if (typeof(rgb) == 'undefined') return 'ffffff';if (typeof(rgb[0]) == 'undefined') {
rgb[0] = rgb.x;rgb[1] = rgb.y;rgb[2] = rgb.z;}
return dechex(rgb[0]) + '' + dechex(rgb[1]) + '' + dechex(rgb[2]);}
function hexStrToRgbArr(hex) {
if (typeof(hex) == 'undefined') return new Array(255, 255, 255);var r = hexdec(hex.substr(0,2));var g = hexdec(hex.substr(2,2));var b = hexdec(hex.substr(4,2));return new Array(r, g, b);}
function updateMyColors() {
var out = new Array;out[out.length] = '<br>These are your personally stored colors.<br><br>';if (myColors.length == 0) {
out[out.length] = 'No colors stored.';} else {
for (var i=0; i<myColors.length; i++) {
color = rgbArrToHexStr(myColors[i]);out[out.length] = '<div style="background-color:#' + color + '; width:300px; height:20px; border:2px inset; margin:2px; cursor:default;';if (isDarkColor(color)) {
out[out.length] = ' color:white;';}
out[out.length] = '"';out[out.length] = ' onclick="selectMyColor(this, \'' + color + '\');" title="#' + color + '"';out[out.length] = ' unselectable="on"';out[out.length] = '>';out[out.length] = 'Hex:' + color;out[out.length] = ' R:' + myColors[i][0];out[out.length] = ' G:' + myColors[i][1];out[out.length] = ' B:' + myColors[i][2];out[out.length] = '</div>';}
out[out.length] = '<input type="button" name="btnEmptyMyColors" value="Remove All" onclick="emptyMyColors(); updateMyColors();" class="inputButton">';out[out.length] = '';}
document.getElementById('tabMyColors').innerHTML = out.join('');}
function foo() {
alert('foo');}

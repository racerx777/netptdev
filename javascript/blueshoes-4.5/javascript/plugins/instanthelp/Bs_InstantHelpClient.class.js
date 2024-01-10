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
jsrsContextProp.useGet = false;function Bs_InstantHelpClient() {
this.objectName = '';this._issuedCalls = new Array();this.language = '';this.textMode = 'html';this.dbSettingName = '';this.serverUrl = '/_bsToolbox/instanthelp/communicator.php';this.init = function(objectName) {
this.objectName    = objectName;};this.getText = function(dictName, strKey, lang, htmlElementId) {
var serverUrl = this.serverUrl;if (typeof(this.dbSettingName)) {
serverUrl += '?dbSettingName=' + this.dbSettingName;}
var callId    = jsrsCall(serverUrl, this.objectName + "._callbackGetText", "Bs_Ih_InstantHelp.getText", strKey, lang, dictName);this._issuedCalls[callId] = new Array(dictName, strKey, lang, htmlElementId);}
this._callbackGetText = function(value, callId) {
if ((value != 'false') && (this._issuedCalls[callId])) {
if (this._issuedCalls[callId][3].indexOf("()") == -1) {
if (this.textMode == 'text') {
document.getElementById(this._issuedCalls[callId][3]).innerHTML = value;} else {
document.getElementById(this._issuedCalls[callId][3]).innerHTML = value;}
} else {
var t = this._issuedCalls[callId][3];try {
value += '';          value = value.replace(/\"/g,'\"'); //escape doublequote. it would break the code otherwise.
          value = value.replace(/\r/g,'\\r');
          value = value.replace(/\n/g,'\\n');
} catch (e) {
}
t = t.substr(0, t.length -2) + "(\"" + value + "\")";eval(t);}
}
}
this.queryMix = function() {
this.firstname = this.firstnameObj.value;this.lastname  = this.lastnameObj.value;if ((this.firstname != '') && (this.lastname != '')) {
var callId    = jsrsCall("OnoServer.php", this.objectName + "._callbackMix", "Bs_Om_OnomasticsServer.isOrderOk", this.firstname, this.lastname);}
}
this._callbackMix = function(value, callId) {
if ((value == 0) || (value == '0')) {
alert("are you sure you didn't mix firstname/lastname?");}
}
this._getRadioFieldValue = function(formName, fieldName) {
for (counter=0; counter<document.forms[formName].elements[fieldName].length; counter++) {
if (document.forms[formName].elements[fieldName][counter].checked) return document.forms[formName].elements[fieldName][counter].value;}
return false;}
}

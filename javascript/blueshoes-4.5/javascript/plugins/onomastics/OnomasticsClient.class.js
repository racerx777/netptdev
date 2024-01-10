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
jsrsContextProp.useGet = false;function OnomasticsClient() {
this.objectName;this.communicatorUrl = '/_bsToolbox/onomastics/communicator.php';this.formName;this.firstnameName;this.lastnameName;this.sexName;this.firstnameObj;this.lastnameObj;this.sexObj;this.firstname;this.lastname;this.sex;this.init = function(objectName, formName, firstnameId, lastnameId, sexId) {
this.objectName    = objectName;if (typeof(formName) != 'undefined') {
this.formName      = formName;this.firstnameName = firstnameId;this.lastnameName  = lastnameId;this.sexName       = sexId;this.firstnameObj = document.forms[formName].elements[firstnameId];this.lastnameObj  = document.forms[formName].elements[lastnameId];this.sexObj       = document.forms[formName].elements[sexId];}
};this.queryGender = function() {
this.firstname = this.firstnameObj.value;this.sex       = this._getRadioFieldValue(this.formName, this.sexName);if ((this.firstname != '') && (this.sex != '')) {
var callId    = jsrsCall(this.communicatorUrl, this.objectName + "._callbackGender", "Bs_Om_OnomasticsServer.getGender", this.firstname);}
}
this._callbackGender = function(value, callId) {
if (value != 'false') {
var firstname, sex;var firstname = this.firstnameObj.value;var sex       = this._getRadioFieldValue(this.formName, this.sexName);if (sex == 1) {
if (value < 0) alert("Are you sure that person is male?");} else if (sex == 2) {
if (value > 0) alert("Are you sure that person is female?");}
}
}
this.queryMix = function() {
this.firstname = this.firstnameObj.value;this.lastname  = this.lastnameObj.value;if ((this.firstname != '') && (this.lastname != '')) {
var callId    = jsrsCall(this.communicatorUrl, this.objectName + "._callbackMix", "Bs_Om_OnomasticsServer.isOrderOk", this.firstname, this.lastname);}
}
this._callbackMix = function(value, callId) {
if ((value == 0) || (value == '0')) {
alert("Are you sure you didn't mix firstname/lastname?");}
}
this._getRadioFieldValue = function(formName, fieldName) {
for (counter=0; counter<document.forms[formName].elements[fieldName].length; counter++) {
if (document.forms[formName].elements[fieldName][counter].checked) return document.forms[formName].elements[fieldName][counter].value;}
return false;}
this.addRelation = function(typeID, fromID, toID) {
var callId = jsrsCall(this.communicatorUrl, this.objectName + "._callbackAddRelation", "Bs_Om_OnomasticsServer.addRelation", typeID, fromID, toID);}
this._callbackAddRelation = function(value, callId) {
}
this.deleteRelation = function(typeID, fromID, toID) {
var callId = jsrsCall(this.communicatorUrl, this.objectName + "._callbackDeleteRelation", "Bs_Om_OnomasticsServer.deleteRelation", typeID, fromID, toID);}
this._callbackDeleteRelation = function(value, callId) {
}
this.findFirstname = function(name, gender, strict) {
if ((typeof(name) == 'undefined') || (name == '')) return false;if (typeof(gender) == 'undefined') gender = 0;if (typeof(strict) == 'undefined') strict = false;var callId = jsrsCall(this.communicatorUrl, this.objectName + "._callbackFindFirstname", "Bs_Om_OnomasticsServer.findFirstname", name, gender, strict, true);}
this._callbackFindFirstname = function(value, callId) {
for (id in value) {
alert(id);}
}
}

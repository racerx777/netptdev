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
var jsrsContextProp = new Object;jsrsContextProp.poolSize = 0;jsrsContextProp.maxPool  = 10;jsrsContextProp.pool     = new Array();jsrsContextProp.browser  = _jsrsBrowserSniff();jsrsContextProp.useGet   = false;jsrsContextProp.alertErr = true;jsrsContextProp.compressWDDX = true;jsrsContextProp.debug = false;jsrsContextProp.Bs_Wddx = new Bs_Wddx();function jsrsCall(remoteTarget, callback, remoteFunction){
if (!document.createElement) {
alert('Sorry, JSRS will not work with this browser (too old). It\'s missing the DOM functionality.');return false;}
var i;var params = null;if (arguments.length >3) {
params = new Array(); var k=0;for (i=3; i < arguments.length; i++) {
params[k++] = arguments[i];}
}
var freeContext = false; var contextObj;for (i=1; i <= jsrsContextProp.poolSize; i++){
contextObj = jsrsContextProp.pool['jsrs' + i];if (!contextObj.busy){
contextObj.busy = true;freeContext = contextObj;break;}
}
if (!freeContext && (jsrsContextProp.poolSize <= jsrsContextProp.maxPool)) {
jsrsContextProp.poolSize++;var contextID = 'jsrs' + jsrsContextProp.poolSize;freeContext = jsrsContextProp.pool[contextID] = new JsrsContext(contextID);}
if (freeContext) {
params = jsrsContextProp.Bs_Wddx.serialize(params);if (jsrsContextProp.compressWDDX) {
params = _WddxHelper.squeeze(params);}
if (jsrsContextProp.debug) freeContext._setVisibility(true);if (jsrsContextProp.useGet) {
freeContext._remoteCall_GET(callback, remoteTarget, remoteFunction, params);} else {
freeContext._remoteCall_POST(callback, remoteTarget, remoteFunction, params);}
return freeContext.id;} else {
alert( "jsrs Error: context pool full (Max is :"+jsrsContextProp.maxPool+")" );return false;}
}
function jsrsReturn(contextID, ret, errText){
var contextObj = jsrsContextProp.pool[contextID];if (errText && (errText.length > 0)) {
    errText = errText.replace(/\+/g, ' ');
errText = unescape(errText);if (jsrsContextProp.alertErr) alert('Error: '+errText);} else {
if (contextObj.callback){
      ret = ret.replace(/\+/g, ' ');
ret = unescape(ret);contextObj.ret = ret;if (jsrsContextProp.compressWDDX) ret = _WddxHelper.expand(ret);ret = jsrsContextProp.Bs_Wddx.deserialize(ret);_callback(contextObj.callback, ret, contextID);}
}
contextObj.callback = null;contextObj.busy = false;var elem = document.getElementById(contextObj.script.id);if (!bs_isNull(elem)) {
contextObj.head.removeChild(elem);contextObj.script.src = "";}
}
function JsrsContext(contextID){
this._remoteCall_GET = function (callback, remoteTarget, remoteFunction, params){
this.callback = callback;var url = new Array(); var b = 0;url[b++] = remoteTarget;url[b++] = (remoteTarget.indexOf('?') >= 0) ? '&' : '?';var d = new Date();url[b++] = "jsrsU=" + d.getTime()+ '' + Math.floor(1000 * Math.random());url[b++] = "&jsrsC=" + this.id;url[b++] = "&jsrsR=js";if (jsrsContextProp.compressWDDX) url[b++] = "&jsrsZ=zip";if (remoteFunction != null){
url[b++] = "&jsrsF=" + escape(remoteFunction);if (params != null) {
        url[b++] = "&jsrsP=" + params.replace(/%/g,'%25').replace(/&/g,'%26');
}
}
this.script.src = url.join('');this.head.appendChild(this.script);};this._remoteCall_POST = function (callback, remoteTarget, remoteFunction, params){
this.callback = callback;var d = new Date();var unique = d.getTime() + '' + Math.floor(1000 * Math.random());var out = new Array(); var b = 0;out[b++] = '<html><body>';out[b++] = '<form name="jsrsForm" method="post" target="" ';var action = remoteTarget;action += (remoteTarget.indexOf('?') >= 0) ? '&' : '?';action += 'jsrsU=' + unique;out[b++] = 'action="' + action + '">';out[b++] = '<input type="hidden" name="jsrsC" value="' + this.id + '">';if (jsrsContextProp.compressWDDX) out[b++] = '<input type="hidden" name="jsrsZ" value="zip">';if (remoteFunction != null){
out[b++] = '<input type="hidden" name="jsrsF" value="' + remoteFunction + '">';if (params){
        params = params.replace(/&/g,'&amp;').replace(/'/g,'&#039;').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
out[b++] = '<input type="hidden" name="jsrsP" value="' + params  + '">';}
}
out[b++] = '</form></body></html>';this.out = out.join('');_callToServer(this.id);};this._createContainer = function() {
var container = null;var span;switch(jsrsContextProp.browser) {
case 'IE':
document.body.insertAdjacentHTML( "afterBegin", '<span id="SPAN' + this.id + '"></span>' );span = document.all( "SPAN" + this.id );span.innerHTML = '<iframe name="' + this.id + '" src=""></iframe>';span.style.display = 'none';container = window.frames[this.id];container.theDoc = container.document;break;case 'MOZ':
span = document.createElement('SPAN');span.style.visibility = 'hidden';document.body.appendChild( span );var iframe = document.createElement('IFRAME');iframe.name = this.id;iframe.width = 0; iframe.height = 0;span.appendChild( iframe );container = iframe;break;}
this.container = container;};this._setVisibility = function (vis){
switch( jsrsContextProp.browser ) {
case 'IE':
document.all("SPAN" + this.id ).style.display = vis ? '' : 'none';break;case 'MOZ':
document.getElementById("SPAN" + this.id).style.visibility = vis ? '' : 'hidden';this.container.width = vis ? 250 : 0;this.container.height = vis ? 100 : 0;break;}
};this.id = contextID;this.busy = true;this.callback = null;this._createContainer();this.head = document.getElementsByTagName('head').item(0);this.script = document.createElement('script');this.script.type = 'text/javascript';this.script.id = "SCRIPT_"+this.id;this.script.defer = true;}
function _callToServer(contextID) {
var doc;var contextObj = jsrsContextProp.pool[contextID];if (jsrsContextProp.browser=='MOZ') {
if (!(contextObj.container.contentDocument)) {
setTimeout('_callToServer("'+contextID+'")', 100);return;}
contextObj.container.document = contextObj.container.contentDocument;}
doc = contextObj.container.document;doc.open();doc.write(contextObj.out);doc.close();doc.forms['jsrsForm'].submit();}
function _callback(callbackStr, retVal, contextID) {
var ret;if (callbackStr.indexOf('.') >= 0) {
var tmp = callbackStr.split('.');var theObject = eval(tmp[0]);ret = theObject[tmp[1]](retVal, contextID);} else {
var theFunction = eval(callbackStr);ret = theFunction(retVal, contextID);}
return ret;}
function _jsrsBrowserSniff(){
if ('undefined' != typeof(document.all)) return "IE";if ('undefined' != typeof(document.getElementById)) return "MOZ";return "OTHER";}
function _WddxHelper(){
var squeezList = new Object;squeezList['<r t'] = /<struct type/g;squeezList['<r>']  = /<struct>/g;squeezList['</r>'] = /<\/struct>/g;squeezList['<a l'] = /<array length/g;squeezList['</a>'] = /<\/array>/g;squeezList['<v n'] = /<var name/g;squeezList['</v>'] = /<\/var>/g;squeezList['<b v'] = /<boolean value/g;squeezList['<s>']  = /<string>/g;squeezList['</s>'] = /<\/string>/g;squeezList['<n>']  = /<number>/g;squeezList['</n>'] = /<\/number>/g;squeezList['<dT>'] = /<dateTime>/g;squeezList['</dT>']= /<\/dateTime>/g;this.squeezList = squeezList;var expandList = new Object;expandList['<struct type'] = /<r t/g;expandList['<struct>']   = /<r>/g;expandList['</struct>']  = /<\/r>/g;expandList['<array length'] = /<a l/g;expandList['</array>']   = /<\/a>/g;expandList['<var name']  = /<v n/g;expandList['</var>']     = /<\/v>/g;expandList['<boolean value'] = /<b v/g;expandList['<string>']   = /<s>/g;expandList['</string>']  = /<\/s>/g;expandList['<number>']   = /<n>/g;expandList['</number>']  = /<\/n>/g;expandList['<dateTime>'] = /<dT>/g;expandList['</dateTime>']= /<\/dT>/g;this.expandList = expandList;this.squeeze = function(stream) {
for (subst in this.squeezList) {
      stream = stream.replace(this.squeezList[subst], subst);
}
return stream;};this.expand = function(stream) {
for (subst in this.expandList) {
      stream = stream.replace(this.expandList[subst], subst);
}
return stream;};}
_WddxHelper = new _WddxHelper();function jsrsDebugInfo(){
var out = new Array(); var b = 0;out[b++] = 'Pool Size: ' + jsrsContextProp.poolSize + '<br><font face="arial" size="2"><b>';for( var i in jsrsContextProp.pool ){
var contextObj = jsrsContextProp.pool[i];var containerDoc = contextObj.container.document;var script = contextObj.script;var used_GET = script.src.length ? true : false;var url, uri, serverReturn;if (used_GET) {
var tmp = script.src.split('?');url = tmp[0];uri = '?'+tmp[1];      serverReturn = (!contextObj.ret) ? '-- No respons from Server --' : contextObj.ret.replace(/</g,'&lt').replace(/>/g,'&gt');
} else {
url = containerDoc.location.pathname;uri = containerDoc.location.search;serverReturn = (!containerDoc.body.innerHTML) ? '-- No respons from Server --' : containerDoc.body.innerHTML;}
var table = new Object();table['Context-ID'] = contextObj.id + ' : ' + (contextObj.busy ? 'busy' : 'available');table['Target URL'] = unescape(url);    table['URI readable'] = unescape(uri).replace(/</g,'&lt').replace(/>/g,'&gt');
table['Sent URL'] = url + uri;out[b++] = '<hr><b>Call via ' + (used_GET ? 'GET' : 'POST') + '</b>';out[b++] = '<TABLE border="1">';for(cap in table) {
out[b++] = '<TR><TD>'+cap+':</TD><TD>'+table[cap]+'</TD></TR>';}
out[b++] = '</TABLE><br>';out[b++] = 'Returned Content  : ';out[b++] = '<table border="1"><tr><td>';out[b++] = serverReturn;out[b++] = '</td></tr></table>';}
out[b++] = '</table>';var doc = window.open().document;doc.open;doc.write(out.join('\n'));doc.close();return false;}

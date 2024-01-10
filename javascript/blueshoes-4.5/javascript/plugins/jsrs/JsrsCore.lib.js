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
var jsrsLibs = new Object();jsrsLibs.path = _jsrsGetUnitPath('JsrsCore');function jsrsLoadUnit(name, useCompression){
if('undefined' != typeof(jsrsLibs[name])) return false;var fullPath = ((name.substr(0, 1) == '/') || (name.substr(0, 2) == './')) ? name : jsrsLibs.path + name;document.writeln('<script type="text/javascript" src="'+fullPath+'"></script>');jsrsLibs[name] = true;return true;};function _jsrsGetUnitPath(name){
var r=false;var i;var re=new RegExp("\/?"+name+"[\._]");var tags=document.getElementsByTagName("SCRIPT");for(var a=0;a<tags.length;a++){
i=tags[a].src.search(re);if(i>=0) r= (i==0) ? '' : tags[a].src.substr(0,i) +'/';}
return r;};jsrsLoadUnit('/_bsJavascript/core/lang/Bs_Misc.lib.js', false);jsrsLoadUnit('/_bsJavascript/core/util/Bs_XmlParser.class.js', false);jsrsLoadUnit('/_bsJavascript/core/util/Bs_Wddx.class.js', false);jsrsLoadUnit('JsrsClient.class.js', false);
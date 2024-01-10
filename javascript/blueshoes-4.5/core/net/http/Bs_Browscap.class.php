<?php/********************************************************************************************
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
*/?><?php
define('BS_BROWSCAP_VERSION',      '4.5.$Revision: 1.5 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');include_once($APP['path']['core'] . 'net/Bs_Url.class.php');class Bs_Browscap extends Bs_Object {var $bsDb;var $data = array();var $_getVars=NULL;var $runTestTemplate=NULL;var $runTestTimeout=NULL;function Bs_Browscap() {parent::Bs_Object(); $this->_getVars = &$_GET;}
function detectUserType() {$this->data['userType'] = array();if (isSet($this->bsDb)) {do {$ipExploded = explode('.', $_SERVER['REMOTE_ADDR']);unset($ipExploded[3]);$ipCnet     = join('.', $ipExploded);$sql  = "SELECT caption, url, spiderType, isNice FROM BsKb.CloakList WHERE valType='ip' AND (val='{$_SERVER['REMOTE_ADDR']}' or val='{$ipCnet}') LIMIT 1";$data = $this->bsDb->getRow($sql);if (is_array($data)) break;if (isSet($_SERVER['REMOTE_HOST'])) {$sql  = "SELECT caption, url, spiderType, isNice FROM BsKb.CloakList WHERE valType='host' AND val LIKE '%{$_SERVER['REMOTE_HOST']}' LIMIT 1";$data = $this->bsDb->getRow($sql);if (is_array($data)) break;}
if (isSet($_SERVER['HTTP_USER_AGENT'])) {$sql  = "SELECT caption, url, spiderType, isNice FROM BsKb.CloakList WHERE (valType='agen' OR valType='agent') AND val LIKE '{$_SERVER['HTTP_USER_AGENT']}' LIMIT 1";$data = $this->bsDb->getRow($sql);if (is_array($data)) break;}
} while (FALSE);if (is_array($data)) {switch ($data['spiderType']) {case 'mail':
$this->data['userType']['type'] = 'emailcrawler';break;default: $this->data['userType']['type'] = 'webcrawler';}
$this->data['userType']['nice']  = (bool)$data['isNice'];$this->data['userType']['name']  = $data['caption'];$this->data['userType']['url']   = $data['url'];return;}
} else {$userAgent = $_SERVER['HTTP_USER_AGENT'];$regExp = array(
'/Googlebot/i', 
'/scooter/i', 
'/ArchitextSpider/i', 
'/T-H-U-N-D-E-R-S-T-O-N-E/i', 
'/t-rex/i', 
'/lycos.*spider/i', 
'/spider.*lycos/i', 
);while (list(,$r) = each($regExp)) {if (preg_match($r, $userAgent)) {$this->data['userType']['type'] = 'webcrawler';$this->data['userType']['nice'] = TRUE;return TRUE;}
}
$regExp = array(
'/EmailSiphon/i', 
'/findEmail/i', 
'/ArchitextSpider/i', 
'/Advanced.*Email.*Extractor/i', 
);while (list(,$r) = each($regExp)) {if (preg_match($r, $userAgent)) {$this->data['userType']['type'] = 'emailcrawler';$this->data['userType']['nice'] = FALSE;return TRUE;}
}
}
$this->data['userType']['type'] = 'user';$this->data['userType']['nice'] = TRUE;}
function isWebCrawler($userAgent, $ip=NULL, $hostName=NULL) {if ($ip) {if (substr($ip, 0, 11) == '216.239.46.') return TRUE; if (substr($ip, 0, 11) == '216.239.51.') return TRUE; if ($ip == '195.141.85.146') return TRUE; }
$regExp = array(
'/T-H-U-N-D-E-R-S-T-O-N-E/i', 
);while (list(,$r) = each($regExp)) {if (preg_match($r, $userAgent)) {return TRUE;}
}
return FALSE;}
function isEmailCrawler($userAgent, $ip=NULL, $hostName=NULL) {return FALSE;}
function isCrawler($userAgent, $ip=NULL, $hostName=NULL) {return ($this->isWebCrawler($userAgent, $ip) || $this->isEmailCrawler($userAgent, $ip));}
function compute($userAgent=NULL) {$data = &$this->data; if (!is_null($userAgent)) {$data['userAgent'] = $userAgent;} elseif (!empty($this->_getVars['bcUa'])) {            $data['userAgent'] = $this->_getVars['bcUa'];} elseif (!empty($_SERVER['HTTP_USER_AGENT'])) {  $data['userAgent'] = $_SERVER['HTTP_USER_AGENT'];} else {$data['userAgent'] = '';}
list($data['browser'], 
$data['browserFullVersion'], 
$data['browserMajorVersion'], 
$data['browserMinorVersion'], 
$data['browserMinorVerlet'], 
$data['isGecko'], 
$data['browserBuild'], 
) = $this->_getBrowserInfo($data['userAgent']);$data['browserCodeName'] = isSet($this->_getVars['bcCn']) ? $this->_getVars['bcCn'] : '';$data['browserLanguages'] = array();$browserLangStr = isSet($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '';$browserLangArr = explode(',', $browserLangStr);foreach ($browserLangArr as $browserLangElm) {$browserLangElm2 = explode(';', $browserLangElm);if (sizeOf($browserLangElm2) == 2) {$data['browserLanguages'][$browserLangElm2[0]] = substr($browserLangElm2[1], 2);} else {$data['browserLanguages'][$browserLangElm2[0]] = '1';}
}
list($data['os'], $data['osVersion']) = $this->_getOsInfo($data['userAgent']);if (!empty($this->_getVars['bcCc'])) $data['cpuClass'] = $this->_getVars['bcCc'];$data['ip']        = $_SERVER['REMOTE_ADDR'];$data['country']   = NULL;if (isSet($_SERVER['REMOTE_HOST'])) {$data['ipResolved'] = $_SERVER['REMOTE_HOST'];$dotPos = strrpos($data['ipResolved'], '.');if ($dotPos !== FALSE) {$data['country'] = strToLower(substr($data['ipResolved'], $dotPos+1));}
}
if (!isSet($data['country'])) {if (isSet($data['browserLanguages']) && !empty($data['browserLanguages'])) {$firstLangCode = key($data['browserLanguages']);if (strlen($firstLangCode) == 5) {$data['country'] = strToLower(substr($firstLangCode, -2));}
}
}
if (!empty($this->_getVars['bcHr'])) {$data['referrer'] = $this->_getVars['bcHr'];} elseif (isSet($_SERVER['HTTP_REFERER']) && empty($this->_getVars['bcRun'])) {$data['referrer'] = $_SERVER['HTTP_REFERER'];} else {$data['referrer'] = '';}
if (isSet($_SERVER['HTTP_VIA'])) $data['via'] = $_SERVER['HTTP_VIA'];$data['cookiesSession']    = (isSet($GLOBALS['HTTP_COOKIE_VARS']['phpBcSession']))   ? TRUE : FALSE;$data['cookiesPermanent']  = (isSet($GLOBALS['HTTP_COOKIE_VARS']['phpBcPermanent'])) ? TRUE : FALSE;if (empty($this->_getVars['bcRun'])) {if (!$data['cookiesSession'])   $data['cookiesSession']   = NULL;if (!$data['cookiesPermanent']) $data['cookiesPermanent'] = NULL;}
if ($data['browser'] == 'ie') {$data['png'] = (bool) ($data['browserMajorVersion'] >= 5);} elseif ($data['browser'] == 'ns') {if (
($data['browserMajorVersion'] >= 5) 
|| 
(($data['browserMajorVersion'] >= 4) && ($data['browserMinorVersion'] >= 1)) 
||
(($data['browserMajorVersion'] >= 4) && ($data['browserMinorVerlet'] >= 4)) 
) {$data['png'] = TRUE;} else {$data['png'] = FALSE;}
} elseif ($data['browser'] == 'op') {if (
($data['browserMajorVersion'] >= 4) 
|| 
(($data['browserMajorVersion'] >= 3) && ($data['browserMinorVersion'] >= 6)) 
||
(($data['browserMajorVersion'] >= 3) && ($data['browserMinorVerlet'] >= 1)) 
) {$data['png'] = TRUE;} else {$data['png'] = FALSE;}
} else {$data['png'] = NULL; }
if (!isSet($this->data['userType'])) { $this->detectUserType();}
$data['javaScript']        = NULL;$data['javaScriptEnabled'] = NULL;if (empty($this->_getVars['bcRun']) || (isSet($this->_getVars['js']) && ($this->_getVars['js'] == 0))) { if (isSet($this->_getVars['js'])) {$data['javaScriptEnabled'] = FALSE;}
if (
(($data['browser'] == 'ie') && ($data['browserMajorVersion'] >= 5)) 
|| 
(($data['isGecko']) && ($data['browserBuild'] >= '20020530')) 
) {$data['dom'] = 'dom';} else {if (($data['browser'] == 'ie') && ($data['browserMajorVersion'] == 4)) {$data['dom'] = 'ie4';} elseif (($data['browser'] == 'ns') && ($data['browserMajorVersion'] == 4)) {$data['dom'] = 'ns4';} }
} else { $data['javaScript']        = TRUE;$data['javaScriptEnabled'] = TRUE;$_javascriptVersions = array(
'1.5' => array('IE6UP', 'NS6UP', 'NS5UP'), '1.3' => array('IE5UP', 'NS4.05UP', 'OP5UP'), '1.2' => array('NS4UP', 'IE4UP'),
'1.1' => array('NS3UP', 'OP'),
'1.0' => array('NS2UP', 'IE3UP')
);foreach($_javascriptVersions as $version => $browserList) {foreach($browserList as $nr => $browserType){if ($this->is('b:' . $browserType)) {$data['javaScriptVersion'] = $version;break 2;}
}
}
$data['isFramed']        = (bool)$this->_getVars['bcIsFmd'];$data['height']          = $this->_getVars['bcH'];$data['width']           = $this->_getVars['bcW'];$data['heightAvailable'] = $this->_getVars['bcHa'];$data['widthAvailable']  = $this->_getVars['bcWa'];$data['colorDepth']      = (int)$this->_getVars['bcCd']; $data['langUser']        = $this->_getVars['bcUl'];$data['langSystem']      = $this->_getVars['bcSl'];$data['timeZoneDiffGmt'] = $this->_getVars['bcTz'];$data['connectionType'] = $this->_getVars['bcCt'];$data['dom'] = $this->_getVars['bcDom'];if (isSet($this->_getVars['bcJe'])) {if ($this->_getVars['bcJe']) {$data['javaApplets']        = TRUE;$data['javaAppletsEnabled'] = TRUE;} else {$data['javaApplets']        = NULL; $data['javaAppletsEnabled'] = FALSE;}
}
$data['pluginFlash']        = NULL;$data['pluginFlashVersion'] = NULL;if (isSet($this->_getVars['bcFl'])) {if ($this->_getVars['bcFl'] > 0) {$data['pluginFlash'] = TRUE;if (@$this->_getVars['bcFlv'] > 0) $data['pluginFlashVersion'] = (int)$this->_getVars['bcFlv'];} elseif ($this->_getVars['bcFl'] == '0') {$data['pluginFlash'] = FALSE;}
}
$data['pluginSvg'] = NULL;if (isSet($this->_getVars['bcSvg'])) {if ($this->_getVars['bcSvg'] > 0) {$data['pluginSvg'] = TRUE;} elseif ($this->_getVars['bcSvg'] == '0') {$data['pluginSvg'] = FALSE;}
}
$data['pluginShockwaveDirector'] = NULL;if (isSet($this->_getVars['bcShD'])) {if ($this->_getVars['bcShD'] > 0) {$data['pluginShockwaveDirector'] = TRUE;} elseif ($this->_getVars['bcShD'] == '0') {$data['pluginShockwaveDirector'] = FALSE;}
}
$data['pluginAcrobat'] = NULL;if (isSet($this->_getVars['bcPdf'])) {if ($this->_getVars['bcPdf'] > 0) {$data['pluginAcrobat'] = TRUE;} elseif ($this->_getVars['bcPdf'] == '0') {$data['pluginAcrobat'] = FALSE;}
}
$data['pluginRealPlayer'] = NULL;if (isSet($this->_getVars['bcRP'])) {if ($this->_getVars['bcRP'] > 0) {$data['pluginRealPlayer'] = TRUE;} elseif ($this->_getVars['bcRP'] == '0') {$data['pluginRealPlayer'] = FALSE;}
}
$data['pluginQuickTime'] = NULL;if (isSet($this->_getVars['bcQT'])) {if ($this->_getVars['bcQT'] > 0) {$data['pluginQuickTime'] = TRUE;} elseif ($this->_getVars['bcQT'] == '0') {$data['pluginQuickTime'] = FALSE;}
}
$data['pluginWindowsMedia'] = NULL;if (isSet($this->_getVars['bcMP'])) {if ($this->_getVars['bcMP'] > 0) {$data['pluginWindowsMedia'] = TRUE;} elseif ($this->_getVars['bcMP'] == '0') {$data['pluginWindowsMedia'] = FALSE;}
}
}
}
function runTest() {bsSetCookie('phpBcSession',   'test', '', '/', '', 0);bsSetCookie('phpBcPermanent', 'test', time() + 900000, '/'); $redirectUrl = Bs_Url::getUrlJunk('7');if (strpos($redirectUrl, '?') !== FALSE) {$redirectUrl .= '&';} else {$redirectUrl .= '?';}
$redirectUrl .= 'bcRun=1';$redirectNoJs = $redirectUrl . '&js=0';$dummyHs = ''; if ($this->runTestTemplate) {$page = join('', file($this->runTestTemplate));} else {$page = <<< EDO
<HTML xmlns:IE>
<head>
__HEAD__
</head>
<body onLoad="bcCheck();">
one moment please...<br>
un instant svp...<br>
einen moment bitte...<br>
<br><br><br><br>
if you do not get redirected automatically in a few seconds, please <a href="{$redirectNoJs}">click here</a>.
__BODY__
</body>
</html>
EDO;
}
$secsEquivRefresh = 6;if ($this->runTestTimeout) {$secsEquivRefresh += $this->runTestTimeout;$redirectLine = "window.setTimeout(\"bsBcRedirect('\" + redirectUrl + \"')\", {$this->runTestTimeout}000);";} else {$redirectLine = "self.location.href = redirectUrl;";}
$httpReferrer = isSet($_SERVER['HTTP_REFERER']) ? urlencode($_SERVER['HTTP_REFERER']) : '';$head = <<< EDO
<noscript><meta http-equiv="refresh" content="1; url={$redirectNoJs}"></noscript>
<meta http-equiv="refresh" content="{$secsEquivRefresh}; url={$redirectNoJs}">
<STYLE> 
@media all {
   IE\:CLIENTCAPS {behavior:url(#default#clientCaps)}
}
</STYLE>
<script language='javascript'>
<!--
function mozillaCheckPlugin(mimeType) {
    if (typeof(navigator.mimeTypes[mimeType]) != 'undefined') {
        if (navigator.mimeTypes[mimeType].enabledPlugin != null) return true;
    }
    return false;
}
function bs_boolToInt(param) {
    return (param) ? 1 : 0;
}
function bcCheck() {
    var bcUa = escape(navigator.userAgent);
    var bcHr = "{$httpReferrer}";
    
  //if (window.screen) { //or if (typeof window.screen!="undefined") 
  var bcH   = (window.screen) ? screen.height : ''; //height
  var bcW   = (window.screen) ? screen.width  : ''; //width
  //}
  if (document.body) {
    var bcHa  = (document.body.clientHeight) ? document.body.clientHeight : ''; //height available
    var bcWa  = (document.body.clientWidth)  ? document.body.clientWidth  : ''; //width available
  } else {
    var bcHa  = ''; //height available
    var bcWa  = ''; //width available
  }
  var bcCd = (window.screen)         ? screen.colorDepth     : ''; //colorDepth
  var bcCc = (navigator.cpuClass)    ? navigator.cpuClass    : ''; //cpuClass
  var bcCn = (navigator.appCodeName) ? navigator.appCodeName : ''; //browserCodeName
  
  
  //check dom
  ///*
  if (document.documentElement) {
  //if (document.getElementById) {
    var bcDom = 'dom';
  } else if (document.all) {
    var bcDom = 'ie4';
  } else if (document.layers) {
    var bcDom = 'ns4';
  } else if (document.images) {
    var bcDom = 'basic';
  } else {
    var bcDom = '';
  }
  //*/
  
  
  if (window.clientInformation) {
    //navigator.systemLanguage
    //navigator.userLanguage
    var bcUl = (window.clientInformation.userLanguage)   ? window.clientInformation.userLanguage.toLowerCase()   : '';   //user language
    var bcSl = (window.clientInformation.systemLanguage) ? window.clientInformation.systemLanguage.toLowerCase() : ''; //system language
    var bcCp = (window.clientInformation.cookieEnabled)  ? 1 : 0;
        
        //connectionType is not in clientInformation. it was removed, or more probably, i was wrong.
    //var bcCt = (window.clientInformation.connectionType) ? window.clientInformation.connectionType : '';
    var bcCt = ((typeof(oClientCaps) != 'undefined') && (typeof(oClientCaps.connectionType) != 'undefined')) ? oClientCaps.connectionType : '';
  } else {
    var bcUl = ''; //user language
    var bcSl = ''; //system language
    var bcCp = '';
    var bcCt = '';
  }
  //var bcUl  = ''; //user language
  //var bcSl  = ''; //system language
  
  var d = new Date();
  var bcTz  = d.getTimezoneOffset() / 60;                //time zone diff
  var bcIsFmd = (top.location != self.location) ? 1 : 0; //isFramed. stupid javascript does not allow conversion of bool to int.
  
    //detecting plugins
  var bcFl   = ''; //flash
  var bcFlv  = ''; //version
    var bcSvg  = ''; //SVG Viewer
    var bcShD  = ''; //Shockwave Director
    var bcRP   = ''; //RealPlayer
    var bcQT   = ''; //QuickTime
    var bcMP   = ''; //Windows Media Player
    var bcPdf  = ''; //Acrobat Reader
  if ((navigator.appName == "Microsoft Internet Explorer") && (navigator.appVersion.indexOf("Mac") != -1)) {
    //should we keep that behavior?
    //IF THE USER IS RUNNING IE ON A MAC, EXTERNAL SCRIPTING WON'T WORK ON THIS PLATFORM
    //maybe we should ask the user if he has flash.
    //no change, keep var meaning as 'unknown'.
  } else {
        // http://work.www.blueshoes.org/en/javascript/datepicker/
    if (navigator.appName == "Microsoft Internet Explorer" && (navigator.userAgent.toLowerCase().indexOf('opera') == -1)) {
      //huh, what was that? if (checkPlugin("application/x-shockwave-flash", 4, "ShockwaveFlash.ShockwaveFlash.4")) {
      //looks like version numbers can be used, like "ShockwaveFlash.ShockwaveFlash.4"
      bcFl   = bs_boolToInt(ieCheckPlugin("ShockwaveFlash.ShockwaveFlash")); //Shockwave Flash
      bcFlv  = 0;
      bcSvg  = bs_boolToInt(ieCheckPlugin("Adobe.SVGCtl")); //SVG Viewer
      bcShD  = bs_boolToInt(ieCheckPlugin("SWCtl.SWCtl.1")); //Shockwave Director
      bcRP   = bs_boolToInt(ieCheckPlugin("rmocx.RealPlayer G2 Control.1")); //RealPlayer
      bcQT   = bs_boolToInt(ieCheckPlugin("QuickTimeCheckObject.QuickTimeCheck.1")); //QuickTime
      bcMP   = bs_boolToInt(ieCheckPlugin("MediaPlayer.MediaPlayer.1")); //Windows Media Player
      bcPdf  = bs_boolToInt(ieCheckPlugin("PDF.PdfCtrl.5")); //Acrobat Reader
        } else {
      //this does not work in explorer. ie does not fill the navigator.mimeTypes object.
            bcFl  = bs_boolToInt(mozillaCheckPlugin('application/x-shockwave-flash')); //Shockwave Flash
      bcFlv = 0;
            bcSvg = bs_boolToInt(mozillaCheckPlugin('image/svg-xml')); //SVG Viewer
            bcShD = bs_boolToInt(mozillaCheckPlugin('application/x-director')); //Shockwave Director
            bcRP  = bs_boolToInt(mozillaCheckPlugin('audio/x-pn-realaudio-plugin')); //RealPlayer
            bcQT  = bs_boolToInt(mozillaCheckPlugin('video/quicktime')); //QuickTime
            bcMP  = bs_boolToInt(mozillaCheckPlugin('application/x-mplayer2')); //Windows Media Player
            bcPdf = bs_boolToInt(mozillaCheckPlugin('application/pdf')); //Acrobat Reader
            /*
              expr  = '/  \d.*  /'; //not sure if that works with ''. used to be without. but ns2 does not like it. and remove the extra-spaces, had to add them because of homesite and code-coloring.
              if ((expr) && (expr.exec)) {
                bcFlv = parseFloat(expr.exec(navigator.mimeTypes["application/x-shockwave-flash"].description));
              }
            */
    }
    /*
    if (((navigator.appName == "Microsoft Internet Explorer") && 
         (navigator.appVersion.indexOf("Mac") == -1) && 
         (navigator.appVersion.indexOf("3.1") == -1)) || 
         (bcFl && 
          parseInt(plugin.description.substring(plugin.description.indexOf(".")-1))>= 
    4)) 
    */
  }
  
  var bcJe = '';
  if (navigator.appName == "Netscape") {
    //ie does not support this way of method checking...
    if (navigator.javaEnabled) {
      bcJe = (navigator.javaEnabled()) ? 1 : 0; //java (applet) enabled
    }
  } else {
    //typeof is supported in ns3+ and ie3+.
    //ns2 does not like this syntax (typeof):
    //if (typeof navigator.javaEnabled != "undefined") {
    //that's why we use eval (since js1 => ns2+ ie3+)
    if (eval('typeof navigator.javaEnabled != "undefined"')) {
      bcJe = (navigator.javaEnabled()) ? 1 : 0; //java (applet) enabled
    }
  }
  
  var redirectUrl       = '{$redirectUrl}';
  redirectUrl += '&bcCd='+bcCd+'&bcW='+bcW+'&bcH='+bcH+'&bcWa='+bcWa+'&bcHa='+bcHa+'&bcTz='+bcTz+'&bcSl='+bcSl+'&bcUl='+bcUl;
  redirectUrl += '&bcIsFmd='+bcIsFmd+'&bcDom='+bcDom;
  redirectUrl += '&bcFl='+bcFl+'&bcFlv='+bcFlv+'&bcSvg='+bcSvg+'&bcShD='+bcShD+'&bcRP='+bcRP+'&bcQT='+bcQT+'&bcMP='+bcMP+'&bcPdf='+bcPdf;
  redirectUrl += '&bcJe='+bcJe+'&bcCc='+bcCc+'&bcCn='+bcCn+'&bcCp='+bcCp+'&bcCt='+bcCt;
  redirectUrl += '&bcUa='+bcUa+'&bcHr='+bcHr;
  {$redirectLine}
}

function bsBcRedirect(url) {
  self.location.href = url;
}

if (navigator.appName == "Microsoft Internet Explorer") {
  document.writeln('<script language="VBScript" type="text/vbscript">');
  document.writeln('function ieCheckPlugin(pluginName)');
  document.writeln('  err.clear');
  document.writeln('  on error resume next');
  document.writeln('  dim plugin');
  document.writeln('  set plugin = createobject(pluginName)');
  document.writeln('  if err.number <> 0 then');
  document.writeln('    ieCheckPlugin = 0');
  document.writeln('  else');
  document.writeln('    ieCheckPlugin = 1');
  document.writeln('  end if');
  document.writeln('end function');
  document.writeln('</script>');
}

//currently not used.
function checkFlash() {
  //try {
    var flash = 'ShockwaveFlash.ShockwaveFlash';
    if (someshing(flash + '.5')) {
      return 5;
    } else if (someshing(flash + '.4')) {
      return 4;
    } else if (someshing(flash + '.3')) {
      return 3;
    } else if (someshing(flash)) {
      return 2;
    } else {
      return flash;
    }
  //} catch (e) {
    return -2;
  //}
}

//currently not used.
function something(param) {
  return TRUE;
}

// -->
<{$dummyHs}/script>
EDO;
$body = '<IE:CLIENTCAPS ID="oClientCaps" />';$page = str_replace('__HEAD__', $head, $page);$page = str_replace('__BODY__', $body, $page);$page = str_replace('<html>', '<HTML xmlns:IE>', $page);$page = str_replace('<HTML>', '<HTML xmlns:IE>', $page);echo $page;exit;}
function _getOsInfo(&$userAgent) {$regex_windows  = '/(win[dows]*)[\s]?([0-9a-z]*)[\w\s]?([a-z0-9.]*)/i';$regex_mac      = '/(68)[k0]{1,3}|[p\S]{1,5}(pc)/i';$regex_os2      = '/os\/2|ibm-webexplorer/i';$regex_sunos    = '/(sun|i86)[os\s]*([0-9]*)/i';$regex_irix     = '/(irix)[\s]*([0-9]*)/i';$regex_hpux     = '/(hp-ux)[\s]*([0-9]*)/i';$regex_aix      = '/aix([0-9]*)/i';$regex_dec      = '/dec|osfl|alphaserver|ultrix|alphastation/i';$regex_vms      = '/vax|openvms/i';$regex_sco      = '/sco|unix_sv/i';$regex_linux    = '/x11|inux/i';$regex_bsd      = '/(free)?(bsd)/i';if (preg_match_all($regex_windows, $userAgent,$match)) {$v  = $match[2][count($match[0])-1];$v2 = $match[3][count($match[0])-1];if (stristr($v, 'NT') && $v2 == 5) {$v = '2000';} elseif (stristr($v, 'NT') && $v2 > 5) {$v = 'xp';} elseif ($v.$v2 == '16bit') { $v = '31';} else { $v .= $v2;}
if (empty($v)) $v = 'win';return array('win', strtolower($v));} elseif(preg_match($regex_os2, $userAgent)) {return array('os2', 'os2');} elseif( preg_match($regex_mac, $userAgent,$match) ) {$os = !empty($match[1]) ? '68k' : '';$os = !empty($match[2]) ? 'ppc' : $os;return array('mac', $os);} elseif(preg_match($regex_sunos, $userAgent,$match)) {if(!stristr($match[1], 'sun')) $match[1] = 'sun'.$match[1];return array('*nix', $match[1].$match[2]);} elseif(preg_match($regex_irix, $userAgent,$match)) {return array('*nix', $match[1].$match[2]);} elseif(preg_match($regex_hpux, $userAgent,$match)) {$match[1] = str_replace('-','',$match[1]);$match[2] = (int) $match[2];return array('*nix', $match[1].$match[2]);} elseif(preg_match($regex_aix, $userAgent,$match)) {return array('*nix', 'aix'.$match[1]);} elseif(preg_match($regex_dec, $userAgent,$match)) {return array('*nix', 'dec');} elseif(preg_match($regex_vms, $userAgent,$match)) {return array('*nix', 'vms');} elseif(preg_match($regex_sco, $userAgent,$match)) {return array('*nix', 'sco');} elseif(stristr($userAgent, 'unix_system_v')) {return array('*nix', 'unixware');} elseif(stristr($userAgent, 'ncr')) {return array('*nix', 'mpras');} elseif(stristr($userAgent, 'reliantunix')) {return array('*nix', 'reliant');} elseif(stristr($userAgent, 'sinix')) {return array('*nix', 'sinix');} elseif(preg_match($regex_bsd, $userAgent,$match)) {return array('*nix', $match[1].$match[2]);} elseif(preg_match($regex_linux, $userAgent,$match)) {return array('*nix', 'linux');} else {return array('unknown', 'unknown');}
}
function _getBrowserInfo($userAgent) {$browsersArray = array(
'microsoft internet explorer' => 'ie',
'msie'                        => 'ie',
'netscape6'                   => 'ns',
'mozilla'                     => 'ns',
'opera'                       => 'op',
'konqueror'                   => 'kq',
'icab'                        => 'ic',
'lynx'                        => 'lx',
'ncsa mosaic'                 => 'mo',
'amaya'                       => 'ay',
'omniweb'                     => 'ow',
);$allowMasquerading = FALSE;$firstLoop = TRUE;$browsersString = '';foreach($browsersArray as $fullName => $abreviation) {$browsersString .=  $firstLoop ? $fullName : "|" . $fullName;if ($firstLoop) $firstLoop = FALSE;}
$versionString = "[\/\sa-z]*([0-9]+)([\.0-9a-z]+)";$regExp = "/($browsersString)$versionString/i";$gecko        = NULL;$browserBuild = NULL;if (preg_match_all($regExp, $userAgent, $results)) {$count = count($results[0])-1;if ($allowMasquerading && ($count > 0)) $count--;$browser = $browsersArray[strToLower($results[1][$count])];$major   = $results[2][$count];preg_match('/([.\0-9]+)([\.a-z0-9]+)?/i', $results[3][$count], $match);$minor   = substr($match[1], 1);$verlet  = (isSet($match[2])) ? $match[2] : '';$full    = $major . '.' . $minor . $verlet;$gecko = (bool)preg_match("/gecko/i", $userAgent);if ($gecko) {if (preg_match('/gecko\/([0-9]{8})/i', $userAgent, $geckoMatch)) { $browserBuild = $geckoMatch[1];}
}
return array($browser, $full, $major, $minor, $verlet, $gecko, $browserBuild);}
return array(null, null, null, null, null, $gecko, $browserBuild);}
function is($s) {$status = FALSE;do { if (preg_match('/l:([a-z-]{2,})/i', $s, $match)) {$status = $this->_performLanguageSearch($match);} elseif (preg_match('/b:([a-z]+)([0-9]*)([\.0-9]*)(up)?/i', $s, $match)) {$status = $this->_performBrowserSearch($match);}
} while(FALSE);return $status;}
function _performBrowserSearch($data) {$search['phrase']  = $data[0];               $search['name']    = strToLower($data[1]);   $search['maj_ver'] = $data[2];               if (!empty($data[3])) {                      $search['min_ver'] = substr($data[3], 1);} else {$search['min_ver'] = 0;}
$search['up']      = !empty($data[4]);      $looking_for = (double)($search['maj_ver'] . '.' . $search['min_ver']);if (($search['name'] == 'aol') || ($search['name'] == 'webtv')) {return stristr($this->data['userAgent'], $search['name']); } elseif ($this->data['browser'] == $search['name']) {$majv = $search['maj_ver'] ? $this->data['browserMajorVersion'] : '';$minv = $search['min_ver'] ? $this->data['browserMinorVersion'] : '';$what_we_are = (double)($majv . '.' . $minv);if ($search['up'] && ($what_we_are >= $looking_for)) {return TRUE;} elseif ($what_we_are == $looking_for) {return TRUE;}
}
return FALSE;}
function _performLanguageSearch($data) {$this->_getLanguages();return stristr($this->_browser_info['language'], $data[1]);}
function _getLanguages() {   if (!$this->_get_languages_ran_once){if ($languages = getenv('HTTP_ACCEPT_LANGUAGE')) {$languages = preg_replace('/(;q=[0-9]+.[0-9]+)/i','',$languages);} else {$languages = $this->_default_language;}
$this->_insert('language',$languages);$this->_get_languages_ran_once = true;}
}
function _get_ip() {$ip = ($tmp = getenv(HTTP_CLIENT_IP)) ? $tmp : getenv(REMOTE_ADDR);$this->_insert('ip', $ip);}
function _insert($k,$v){$this->_browser_info[strtolower($k)] = strtolower($v);}
function _test_cookies() {global $ctest,$phpSniff_testCookie;if($this->_check_cookies) {if ($ctest != 1) {SetCookie('phpSniff_testCookie','test',0,'/');$QS = getenv(QUERY_STRING);$script_path = ($tmp = getenv(PATH_INFO)) ? $tmp : getenv(SCRIPT_NAME);$location = $script_path . ($QS=="" ? "?ctest=1" : "?" . $QS . "&ctest=1");header("Location: $location");exit;}
elseif ($phpSniff_testCookie == "test") { $this->_insert('cookies',true);}
else {$this->_insert('cookies',false);}
}
else {$this->_insert('cookies',false);}  
}
}
$GLOBALS['Bs_Browscap'] =& new Bs_Browscap();if (isSet($GLOBALS['bsDb'])) $GLOBALS['Bs_Browscap']->bsDb = &$GLOBALS['bsDb'];?>
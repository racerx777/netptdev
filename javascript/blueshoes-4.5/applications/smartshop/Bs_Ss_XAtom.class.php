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
define('BS_SS_XATOM_VERSION',      '4.5.$Revision: 1.2 $');require_once($_SERVER['DOCUMENT_ROOT']    . '../global.conf.php');require_once($APP['path']['core'] . 'file/Bs_FileSystem.class.php');require_once($APP['path']['applications'] . 'smartshop/Bs_Ss_XStorage.class.php');require_once($APP['path']['applications'] . 'smartshop/Bs_Ss_XClearingHouse.class.php');class Bs_Ss_XAtom extends Bs_Object {var $_atomContainer = array(
'UID'           => NULL,      'storageMedia'  => NULL,      'fileExtension' => NULL,      'element'       => NULL, 
'isDataLoaded'  => FALSE,     'alive'         => array(     'isDeactivated'  =>  FALSE,                  'startDatetime'  =>  '0000-00-00 00:00:00',  'endDatetime'    =>  '0000-00-00 00:00:00'   ),
'cache'         => array (
'version'        => -1,     'storageMedia'   => 'file', 'xmlModTime'     => NULL,   'absPathToXml'   => NULL,   'absPathToXCache'=> NULL,   ),
'bsHead'       => array(      'version'     => '1.0',     ),
);var $_tempContainer = array(
'isValid'           => NULL , 'isAlive'           => NULL,  'xmlFileFound'      => FALSE, 'xCacheFileUptodate'=> FALSE, );var $_APP;var $_failedReason;var $fullTrace = TRUE;var $_traceMsg;var $_xStorage;var $_xClearingHouse;function Bs_Ss_XAtom($__file__) {parent::Bs_Object(); $this->_APP            = &$GLOBALS['APP'];static $_xStorage = NULL;$this->_xStorage = &$_xStorage;if (!is_object($this->_xStorage)) {$this->_xStorage =& new Bs_Ss_XStorage();}
}
function init($UID) {$status = FALSE;$tryBlock = 1;do { $this->_atomContainer['UID'] = $UID;if (!$this->_xStorage->loadXAtom($this)) {echo $this->getFailedReason();break $tryBlock; }
if (!$this->isAlive()) {break $tryBlock; }
$status = TRUE; } while(FALSE); if ($status) $this->_tempContainer['isValid'] = TRUE;return $status;}
function getUid() {return $this->_atomContainer['UID'];}
function reset() {$UID = $this->_atomContainer['UID'];$this->_atomContainer = $this->_emptyContainer['atom'];$this->_tempContainer = $this->_emptyContainer['temp'];$this->_dataContainer = $this->_emptyContainer['data'];$this->_failedReason  = NULL;}
function getFailedReason($verbose=FALSE) {if ($verbose) return (string)$this->_failedReason[2][0];return $this->_failedReason;}
function persist() {$this->_xStorage->persist($this);}
function datetimeInRange($startDatetime=NULL, $endDatetime=NULL, $now=NULL) {if (empty($now)) $now = gmdate('Y-m-d H:i:s');$ret = 0;do {if ($startDatetime === NULL) $startDatetime = $this->_atomContainer['alive']['startDatetime'];if ($endDatetime === NULL)   $endDatetime   = $this->_atomContainer['alive']['endDatetime'];if ((!empty($startDatetime)) && ($startDatetime != '0000-00-00 00:00:00')) {if ($now < $startDatetime) {$ret = -1;break;}
}
if ((!empty($endDatetime)) && ($endDatetime != '0000-00-00 00:00:00')) {if ($endDatetime <= $now) {$ret = 1;break;}
}
} while (FALSE);return $ret;}
function isAlive() {if (!isSet($this->_tempContainer['isAlive'])) {if ($this->_atomContainer['alive']['isDeactivated']) {$this->_tempContainer['isAlive'] = FALSE;} else {$this->_tempContainer['isAlive'] = ($this->datetimeInRange() === 0);}
}
return $this->_tempContainer['isAlive'];}
function toXml($htmlOut=FALSE) {$aC = $this->_atomContainer;$dC = $this->_dataContainer;$xmlDetails_1 = $xmlDetails_2 = '';switch ($dC['element']) {case 'node'  : 
$xmlDetails_1  = $this->_toXmlHelper(array('caption','title','description','keywords','author','visible','redirect'));$ref = $dC['node']['guardRef'];$xmlDetails_2  ='    <bs:guradRef name="' .$ref['name']. '" ref="' .$ref['ref']['parentUni'] . $ref['ref']['coreName']. '" version="' .$ref['version']. '"  source="' .$ref['source']. '" />' . "\n";break;case 'page'  : 
$xmlDetails_1  = $this->_toXmlHelper(array('weight','cacheExpire'));$ref = $dC['page']['layoutRef'];$xmlDetails_2 .='    <bs:layoutRef name="' .$ref['name']. '" ref="' .$ref['ref']['parentUni'] . $ref['ref']['coreName']. '" version="' .$ref['version']. '"  source="' .$ref['source']. '" />' . "\n";if (!empty($xmlDetails_2)) $xmlDetails_2 .= "\n";foreach($dC['page']['blockRef'] as $name => $ref) {$xmlDetails_2 .='    <bs:blockRef name="' .$ref['name']. '" ref="' .$ref['ref']['parentUni'] . $ref['ref']['coreName']. '" version="' .$ref['version']. '"  source="' .$ref['source']. '" />' . "\n";}
break;case 'layout': 
$xmlDetails_1  = $this->_toXmlHelper(array('access','cacheExpire'));$value = $dC['layout']['value'];$xmlDetails_2  .='    <bs:value  mime="' .$value['mime']. '" cruncher="' .$value['cruncher']. '" crunchDelay="' .$value['crunchdelay']. '" >' . "\n";$xmlDetails_2  .= "<![CDATA[";$xmlDetails_2  .= trim($value['data']);$xmlDetails_2  .= "]]>\n";$xmlDetails_2  .= "    </bs:value>\n";break;case 'block' : 
$xmlDetails_1  = $this->_toXmlHelper(array('access','cacheExpire'));foreach($dC['block']['part'] as $name=>$part) {$partError      = "";$xmlDetails_2  .="\n" .'    <bs:part name="' .$part['name']. '" mime="' .$part['mime']. '" cruncher="' .$part['cruncher']. '" crunchDelay="' .$part['crunchdelay']. '" >' . "\n";switch ($part['mime']) {case 'html':
case 'xhtml':
case 'xml':
case 'cdata':
$xmlDetails_2  .= "<![CDATA[\n";$xmlDetails_2  .= trim($part['data']) . "\n";$xmlDetails_2  .= "]]>\n";break;case 'image':
foreach($part['data'] as $attrName=>$imgVal) {$xmlDetails_2  .= '      <bs:attribute name="' .$attrName. '" value="' .htmlspecialchars($imgVal). '" />' . "\n";}
break;case 'text':
$xmlDetails_2  .= htmlspecialchars(trim($part['data'])) . "\n";break;default:
$partError      = "<!-- ERROR in part above! Unknown mime:'{$part['mime']}'. Handeled as Cdata. -->\n";$xmlDetails_2  .= "<![CDATA[\n";$xmlDetails_2  .= trim($part['data']) . "\n";$xmlDetails_2  .= "]]>\n";}
$xmlDetails_2  .= "    </bs:part>\n";if (!empty($partError)) $xmlDetails_2  .= "    " . $partError;}
break;default :
$xmlDetails_1 .= "ERROR: Unknown element: '{$dC['element']}'";$this->_logger->log("Non existing XAtom-element:'{$xAtomObj->_atomContainer['element']}'. ", BS_LOG_MURPHY_ERR, __LINE__, "loadXAtom", __FILE__);$staus = FALSE;}
$isDeactivated = $aC['alive']['isDeactivated'] ? 'TRUE' : 'FALSE';$element = $dC['element'];$out =<<< EOD
<blueshoes version="{$aC['bsHead']['version']}" copyright="bs-2002">
  <!-- For mashine use  -->
  <bs:{$element}
    ID            = "{$aC['UID']}"
    name          = "{$dC[$element]['name']}"
    version       = "{$dC[$element]['version']}"
    parentUni     = "{$aC['parentUni']}"
    lang          = "{$aC['lang']}"
    level         = "{$aC['level']}"
    condition     = "{$aC['condition']}"
    pubCycle      = "{$aC['pubCycle']}"
{$xmlDetails_1}
    >
    
    <bs:alive 
      isDeactivated = "{$isDeactivated}"
      startDatetime = "{$aC['alive']['startDatetime']}"
      endDatetime   = "{$aC['alive']['endDatetime']}"
    />
    
{$browscap}    
    
{$xmlDetails_2}
    
  </bs:{$dC['element']}>
</blueshoes>
EOD;
if ($htmlOut) {$out = "<pre>\n".htmlentities($out)."\n</pre>\n" ;}
return $out;}
function _toXmlHelper($keysArr, $indent="    ") {$xmlDetails = '';$first = TRUE;$element = $this->_dataContainer['element'];foreach($keysArr as $key) {$val = $this->_dataContainer[$element][$key];if (is_bool($val)) $val = $val ? "TRUE" : "FALSE";$val = is_null($val) ? "" : $val;$xmlDetails .= $indent . str_pad($key, 14, ' ', STR_PAD_RIGHT) . '= "' .htmlspecialchars($val). '"';if ($first) {$first = FALSE;$indent = "\n" . $indent;}
}
return $xmlDetails;}
function traceMsg($traceNr, $traceStr, $line, $methode, $file) {$this->_traceMsg[] = array (
'traceNr'   => $traceNr, 
'traceStr'  => $traceStr,
'line'      => $line,
'methode'   => $methode,
'file'      => basename($file)
);}
}
?>

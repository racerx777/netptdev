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
define('BS_LOGADMIN_VERSION',      '4.5.$Revision: 1.1.1.1 $');require_once($_SERVER["DOCUMENT_ROOT"] . "../global.conf.php");require_once($APP['path']['core'] . 'file/Bs_Dir.class.php');require_once($APP['path']['core'] . 'lang/Bs_Logger.class.php');require_once($APP['path']['core'] . 'db/Bs_MySql.class.php');require_once($APP['path']['core'] . 'html/Bs_HtmlUtil.class.php');require_once($APP['path']['core'] . 'html/table/Bs_HtmlTable.class.php');require_once($APP['path']['core'] . 'html/table/Bs_HtmlTableWindrose.class.php');$errorHandlerLog = array();function myErrorHandler ($errno, $errstr, $errfile, $errline) {global $errorHandlerLog;$errType = '';switch ($errno) {case E_ERROR   : $errType = 'ERROR'; break;case E_WARNING : $errType = 'WARNING'; break;case E_PARSE   : $errType = 'PARSE ERROR'; break;case E_NOTICE  : $errType = 'NOTICE'; break;case E_USER_ERROR   : $errType = 'USER_ERROR'; break;case E_USER_WARNING : $errType = 'USER_WARNING'; break;case E_USER_NOTICE  : $errType = 'USER_NOTICE'; break;case E_CORE_ERROR      : $errType = 'CORE_ERROR'; break;case E_CORE_WARNING    : $errType = 'CORE_WARNING'; break;case E_COMPILE_ERROR   : $errType = 'COMPILE_ERROR'; break;case E_COMPILE_WARNING : $errType = 'COMPILE_WARNING'; break;default : $errType = 'ERROE Unknown';}
$errorHandlerLog[] = "<strong>{$errType}: {$errstr}</strong><br>&nbsp;&nbsp;&nbsp;&nbsp;File: {$errfile} Line [$errline]<br><br>";}
class Bs_LogAdmin extends Bs_Object {var $_frameSetup   = '';var $_frameMain    = 'logMain';var $_frameNav     = 'logProp';var $_logProperty = array();var $bsDb = NULL;var $dsn = array('host'=>'localhost', 'name'=>'', 'connectOK'=>FALSE);var $_logPropertyTemplate = array(
'active'     => FALSE,          'hitNquit'   => FALSE,          'target'     => '',             'targetName' => '',             'regEx'      => 'return FALSE;','matcher'    => 'preg_match',   'msg_reg'    => '',             'mt_cond'    => 'OR',
'type_reg'   => '',             'tp_cond'    => 'OR',
'phpFile_reg'=> '' ,            'pf_cond'    => 'OR',
'freeStyle_reg'=> ''            );function Bs_LogAdmin() {parent::Bs_Object();$this->_init();}
function _init() {global $APP;$this->self      = $_SERVER['PHP_SELF'];$this->dirRoot   =& $APP['path']['core'];$this->bsDb      =& $GLOBALS['bsDb'];$this->htmlUtil  =& $GLOBALS['Bs_HtmlUtil'];$this->windrose  =& new Bs_HtmlTableWindrose();$this->windrose->read('./logAdmin.style');$this->htmlTbl   =& new Bs_HtmlTable();$this->htmlTbl->setWindroseStyle(&$this->windrose);}
function serialize() {unSet($this->bsDb);unSet($this->ooDb);unSet($this->htmlUtil);unSet($this->windrose);unSet($this->htmlTbl);return serialize($this);}
function handleEvent($rVars) {$this->_init();$htmlBody = '';do { $tryBlock=1;if (is_null($rVars)) { $htmlBody = <<< EOD
          <!-- frames -->
          <frameset  cols="20%,*">
              <frame name="{$this->_frameNav}" src="{$this->self}?rVars[action]=start&rVars[data][frame]={$this->_frameNav}" marginwidth="10" marginheight="10" scrolling="auto" frameborder="0">
              <frame name="{$this->_frameMain}"    src="{$this->self}?rVars[action]=start&rVars[data][frame]={$this->_frameMain}" marginwidth="10" marginheight="10" scrolling="auto" frameborder="0">
          </frameset>
EOD;
echo  $htmlBody;exit;}
$tmpSplit = split(';', $rVars['action']);if (!empty($tmpSplit[0])) $rVars['action'] = $tmpSplit[0];if (!empty($tmpSplit[1])) $rVars['do'] = $tmpSplit[1];$tryBlock++;switch ($rVars['action']) {case 'start':
if ($rVars['data']['frame']==$this->_frameNav) {$rVars['data']['dir']=$this->dirRoot;$htmlBody .= $this->_action_selectFile($rVars);} else {$htmlBody .= 'Nothing selected';}
break;case 'cd':
$this->_formErrors = array();$htmlBody .= $this->_action_selectFile($rVars);break;case 'showLogPropForm':
if (!empty($rVars['data']['db'])) $this->dsn['name'] = $rVars['data']['db'];$this->_formErrors = array();$this->_logProperty = &$this->_form2property($rVars);if (isSet($rVars['do']) AND ($rVars['do']=='Test')){$td =& $rVars['data']['form']['test'];$this->_propMakeRegEx($this->_logProperty);$this->_logProperty = $GLOBALS['Bs_Logger']->test($td['msg'], $td['msgType'], __LINE__, $td['phpFunc'] ,$td['phpFile'], $this->_logProperty);}
$htmlBody .= $this->_action_showLogPropForm($rVars);break;case 'displayError':
$htmlBody .= $this->_action_displayError($rVars);break;case 'dbConnect':
$htmlBody .= $this->_action_displayDbConnect($rVars);break;case 'dbUpdate':
$this->_logProperty = &$this->_form2property($rVars);$this->_action_dbUpdate($this->_logProperty);$rVars['do'] = 'show';$htmlBody .= $this->_action_showLogPropForm($rVars);break;default:
$htmlBody .=  "INVALID ACTION: '{$rVars['action']}'";}
$tryBlock--;} while(FALSE); $this->_echoHtmlPage($htmlBody);}
function &_getMenuTop($currentClassPath) {$className = basename($currentClassPath);if ($this->dsn['connectOK']) {$dbConnectedGif = 'dbOnline.gif';$dbConnectedTxt = "{$this->dsn['host']}:{$this->dsn['name']}";$dbUpdate = "<A HREF={$this->self}?rVars[action]=dbUpdate&rVars[data][class]={$currentClassPath} TARGET={$this->_frameMain}><img src='./pics/dbUpdate_hi.gif' border=0 alt='update Db'>";$dbUpdate .= "<br>[update Db]</A>";$objEdit = "<A HREF={$this->self}?rVars[action]=ooDbEdit&rVars[data][class]={$currentClassPath} TARGET={$this->_frameMain}><img src='./pics/objectEdit_hi.gif' border=0 alt='edit object'>";} else {$dbConnectedGif = 'dbOffline.gif';$dbConnectedTxt = 'No Db:Table selected';$dbUpdate = "<img src='./pics/dbUpdate_lo.gif' border=0 alt='update Db'><br><span style='color:silver'>[update Db]</span></A>";}
if (is_writeable($currentClassPath)) {$formEdit = "<A HREF={$this->self}?rVars[action]=showLogPropForm&rVars[do]=editProperty&rVars[data][class]={$currentClassPath} TARGET={$this->_frameMain}>".'<img src="./pics/property.gif" border="0" alt="edit property">'.
'<br>[edit property]</A>';} else {$formEdit = '<img src="./pics/property.gif" border="0" alt="edit property">'.
'<br>[read only]';}
$htmpOut = <<<EOD
       <hr>
       <TABLE  style='font-size:10px;'>
         <tr>
           <td align=center><img src='./pics/property.gif' border=0></td>
           <td><span style='font-size:16px; font-weight:bolder'>{$className}</span></td>
           <td>[{$currentClassPath}]</td>
         </tr><tr>
           <td align=center>
           <A HREF={$this->self}?rVars[action]=dbConnect&rVars[do]=dbLogin&rVars[data][class]={$currentClassPath} TARGET={$this->_frameMain}><img src="./pics/{$dbConnectedGif}" border="0" alt="db online status">
           <br>[connect]</A>
           </td>
           <td><span style='font-size:16px; font-weight:bolder'>{$dbConnectedTxt}</span></td>
         </tr>
       </TABLE>
       <hr>
       <TABLE  style='font-size:10px;'>
         <tr>
           <td align=center>
             <A HREF={$this->self}?rVars[action]=showLogPropForm&rVars[do]=show&rVars[data][class]={$currentClassPath} TARGET={$this->_frameMain}><img src="./pics/examine.gif" border="0" alt="examine">
             <br>[examine class]</A>
           </td>
           <td align=center>
             {$formEdit}
           </td>
           <td align=center>
             {$dbUpdate}
           </td>
         </tr>
       </TABLE>
EOD;
return $htmpOut;}
function &_action_checkForm(&$logProperties) {for ($i=0; $i<sizeOf($logProperties); $i++) {$prop = &$logProperties[$i];$prop['error'] = '';$matcher = $prop['matcher'];set_error_handler('myErrorHandler');global $errorHandlerLog;$errorHandlerLog = array();                  if (!empty($prop['msg_reg']))     $matcher($prop['msg_reg'], 'The quick red fox ran away');if (!empty($prop['type_reg']))    $matcher($prop['type_reg'], 'The quick red fox ran away');if (!empty($prop['phpFile_reg'])) $matcher($prop['phpFile_reg'], 'The quick red fox ran away');if (sizeOf($errorHandlerLog)) {$prop['error'] = implode('<br>', $errorHandlerLog);if ($prop['active']) {return FALSE;  }
}
restore_error_handler();if (($prop['target']=='db') AND ($prop['active'])) {if (!$this->dsn['connectOK']) {$prop['error'] = '<strong>Error: You are using a DB as target. Establish connection to DB first.</strong>';return FALSE;} else {$prop['exists'] = $this->bsDb->tableExists($prop['targetName']);}
} elseif ($prop['target']=='file') { $prop['exists'] = file_exists($prop['targetName']);}
}
return TRUE;}
function &_action_selectFile($rVars) {$startDir = $rVars['data']['dir'];$htmlOut = '';$splitedPath = array();$tmpSplitPath = explode('/', $startDir);$splitSize = sizeOf($tmpSplitPath);for ($i=0; $i<$splitSize; $i++) {if ($tmpSplitPath[$i] == '') continue;$splitedPath[] = $tmpSplitPath[$i];}
$Dir = new Bs_Dir($startDir);$absDir = '';for ($i=0; $i<sizeOf($splitedPath); $i++) {$absDir .= urlencode($splitedPath[$i] .'/');$htmlOut .= "<A HREF={$this->self}?rVars[action]=cd&rVars[data][dir]={$absDir} TARGET={$this->_frameNav}>{$splitedPath[$i]}</A> / ";}
$htmlOut .= "<br><br>\n";$array = array('depth'=>1, 'returnType'=>'subpath', 'regEx' => '', 'regFunction' => 'eregi', 'fileDirLink' => array('dir' => TRUE, 'file'=>FALSE));$relDirList = $Dir->getFileList($array);if (isEx($relDirList)) {$relDirList->stackTrace('', __FILE__, __LINE__);$relDirList->stackDump('echo');}
$array['returnType'] = 'fullpath';$absDirList = $Dir->getFileList($array);if (isEx($absDirList)) {$relDirList->stackTrace('', __FILE__, __LINE__);$absDirList->stackDump('echo');}
while(list($j) = each($relDirList)) {$absDir = urlencode($absDirList[$j]);$htmlOut .=  "<A HREF={$this->self}?rVars[action]=cd&rVars[data][dir]={$absDir} TARGET={$this->_frameNav}>" .
"<IMG src='./pics/dossier.gif' border='0' alt=''> " . $relDirList[$j] . "</A><br>\n";}
$array = array('depth'=>1, 'returnType'=>'subpath', 'regEx' => 'Bs_Logger.conf.php$', 'regFunction' => 'eregi', 'fileDirLink' => array('dir' =>FALSE, 'file'=>TRUE));$fileList = $Dir->getFileList($array);if (isEx($fileList)) {$relDirList->stackTrace('', __FILE__, __LINE__);$fileList->stackDump('echo');}
$htmlOut .= "<br><br>\n";while(list($j) = each($fileList)) {$absClassFile = urlencode($startDir . $fileList[$j]);$htmlOut .= "<A HREF={$this->self}?rVars[action]=showLogPropForm&rVars[do]=load&rVars[data][class]={$absClassFile} TARGET={$this->_frameMain}>" .
"<IMG src='./pics/property.gif' border='0' alt=''> "  . $fileList[$j]. "</A><br>\n";}
return $htmlOut;}
function &_action_showLogPropForm(&$rVars) {$currentClassPath = $rVars['data']['class'];$toDo = $rVars['do'];$parentRow = array();$chieldTbl =& new Bs_HtmlTable();$chieldTbl->setWindroseStyle(&$this->windrose);if ($toDo=='load') $toDo = 'show';$formDisable = ($toDo==='show') ? 'disabled' : '';$ReadyToSave = FALSE;if (!empty($this->_logProperty)) {$ReadyToSave  = $this->_action_checkForm($this->_logProperty);if (($toDo=='SaveProp') AND ($ReadyToSave)) { $this->savePropertyFile($currentClassPath);}
}
if ($toDo=='addNewDbProp') {$tmpTemp  = $this->_logPropertyTemplate;$tmpTemp['target'] = 'db';$this->_logProperty[] = $tmpTemp;} elseif ($toDo=='addNewFileProp') {$tmpTemp  = $this->_logPropertyTemplate;$tmpTemp['target'] = 'file';$this->_logProperty[] = $tmpTemp;}
$htmlObjHyrachy = '<strong>DB Properties :</strong><br>';$tblRow_db = $tblRow_file = array();$tblRow_db[0] = $tblRow_file[0] = array('Active', 'Status', 'Hit\'n Quit', 'Target Name', 'Matcher', 'Msg<br>($msg)', '', 'Type<br>($msgType)', '', 'Php File<br>($phpFile)', '', 'Any Php Expresion', 'The Regular Expression', 'Del');for ($rowNr=1; $rowNr<=sizeOf($this->_logProperty); $rowNr++) {$propPos = $rowNr-1;$logProp = &$this->_logProperty[$propPos];unset($tblRow);if ($logProp['target']=='db') {$tblRow = &$tblRow_db;} else {$tblRow = &$tblRow_file;}
$key = 'active';$checked = ($logProp[$key] == TRUE) ? 'checked' : '';$tblRow[$rowNr][] = "<div align='center'><input type='checkbox' {$checked} name='rVars[data][form][logProp][{$propPos}][{$key}]' {$formDisable} style='HEIGHT:14px'></div>";if (!empty($logProp['error'])) {$errKey = 'propErr'.$propPos;$this->_formErrors[$errKey] = $logProp['error'];$tblRow[$rowNr][] = "<A HREF=\"javascript:w=window.open('{$this->self}?rVars[action]=displayError&rVars[data][varName]={$errKey}', 'classErr',  'scrollbars,resizable,width=600,height=150,left=200,top=500'); w.focus(); void('');\" " .
'<span style="text-align:center; color:Red; font-weight:bold; font-size:14px; background:#FFFF99;">&nbsp;!&nbsp;</SPAN></A>&nbsp;';} else {if (isSet($logProp['exists'])) {if ($logProp['exists']) {$tblRow[$rowNr][] = "<span style='font-weight: bold; color: green;'>OK<span>";} else {$tblRow[$rowNr][] = "<span style='font-weight: bold; background-color: Yellow;'>Not found<span>";}
} else {$tblRow[$rowNr][] = "<span style='font-weight: bold; background-color: LightGrey;'>Not Checked<span>";}
}
$key = 'hitNquit';$checked = ($logProp[$key] == TRUE) ? 'checked' : '';$tblRow[$rowNr][] = "<div align='center'><input type='checkbox' {$checked} name='rVars[data][form][logProp][{$propPos}][{$key}]' {$formDisable} style='HEIGHT:14px'></div>";$key = 'targetName';$span = '';if (isSet($logProp['hit'])) {$span = $logProp['hit'] ? "style='background: Yellow;'":"style='background: Silver;'";}
$tblRow[$rowNr][] = "<input type='text' {$span} {$formDisable} name='rVars[data][form][logProp][{$propPos}][{$key}]' value='{$logProp[$key]}' size='12' >";$key = 'matcher';$tmp = "<select name='rVars[data][form][logProp][{$propPos}][{$key}]' {$formDisable}>";$tmp .= "<option value='preg_match'"  . ($logProp[$key]=='preg_match'  ? 'SELECTED' : ''). ">preg_match</option>";$tmp .= "<option value='ereg'" . ($logProp[$key]=='ereg' ? 'SELECTED' : ''). ">ereg</option>";$tmp .= "<option value='eregi'" . ($logProp[$key]=='eregi' ? 'SELECTED' : ''). ">eregi</option>";$tmp .= "</select>";$tblRow[$rowNr][] = $tmp;$key = 'msg_reg';$val = htmlspecialchars($logProp[$key], ENT_QUOTES);$tblRow[$rowNr][] = "<input type='text' {$formDisable} name='rVars[data][form][logProp][{$propPos}][{$key}]' value='{$val}' size='12' >";$key = 'mt_cond';$tmp = "<select name='rVars[data][form][logProp][{$propPos}][{$key}]' {$formDisable}>";$tmp .= "<option value='OR'"  . ($logProp[$key]=='OR'  ? 'SELECTED' : ''). ">OR</option>";$tmp .= "<option value='AND'" . ($logProp[$key]=='AND' ? 'SELECTED' : ''). ">AND</option>";$tmp .= "</select>";$tblRow[$rowNr][] = $tmp;$key = 'type_reg';$val = htmlspecialchars($logProp[$key], ENT_QUOTES);$tblRow[$rowNr][] = "<input type='text' {$formDisable} name='rVars[data][form][logProp][{$propPos}][{$key}]' value='{$val}' size='12' >";$key = 'tp_cond';$tmp = "<select name='rVars[data][form][logProp][{$propPos}][{$key}]' {$formDisable}>";$tmp .= "<option value='OR'"  . ($logProp[$key]=='OR'  ? 'SELECTED' : ''). ">OR</option>";$tmp .= "<option value='AND'" . ($logProp[$key]=='AND' ? 'SELECTED' : ''). ">AND</option>";$tmp .= "</select>";$tblRow[$rowNr][] = $tmp;$key = 'phpFile_reg';$val = htmlspecialchars($logProp[$key], ENT_QUOTES);$tblRow[$rowNr][] = "<input type='text' {$formDisable} name='rVars[data][form][logProp][{$propPos}][{$key}]' value='{$val}' size='12' >";$key = 'pf_cond';$tmp = "<select name='rVars[data][form][logProp][{$propPos}][{$key}]' {$formDisable}>";$tmp .= "<option value='OR'"  . ($logProp[$key]=='OR'  ? 'SELECTED' : ''). ">OR</option>";$tmp .= "<option value='AND'" . ($logProp[$key]=='AND' ? 'SELECTED' : ''). ">AND</option>";$tmp .= "</select>";$tblRow[$rowNr][] = $tmp;$key = 'freeStyle_reg';$val = htmlspecialchars($logProp[$key], ENT_QUOTES);$tblRow[$rowNr][] = "<input type='text' {$formDisable} name='rVars[data][form][logProp][{$propPos}][{$key}]' value='{$val}' size='12' >";$key = 'regEx';$val = htmlspecialchars($logProp[$key], ENT_QUOTES);$tblRow[$rowNr][] = $val;if (!empty($formDisable) OR !empty($logProp['error'])) {$tblRow[$rowNr][] = "<img src='./pics/cancel.gif' width='20' height='20' border='0' alt='Delete Log Target'>";} else {$tblRow[$rowNr][] = "<A HREF={$this->self}?rVars[action]=delLog&rVars[data][propNr]={$propPos}><img src='./pics/cancel.gif' width='20' height='20' border='0' alt='Delete Log Target'></A>";}
}
$this->htmlTbl->initByMatrix($tblRow_db);$htmlTbl_db = $this->htmlTbl->renderTable();if ($toDo != 'show') $htmlTbl_db .= "<input type=submit name='rVars[do]' value='addNewDbProp'>";$this->htmlTbl->initByMatrix($tblRow_file);$htmlTbl_file = $this->htmlTbl->renderTable();if ($toDo != 'show') $htmlTbl_file .= "<input type=submit name='rVars[do]' value='addNewFileProp'>";$toolTop = $this->_getMenuTop($currentClassPath);$buttons = '';if (($toDo != 'show')  AND ($ReadyToSave)) $buttons .= "<input type=submit name='rVars[do]' value='SaveProp'>";$testInput = <<<EOD
     <table width='100%'><tr><td align='center'>  
        <table>
        <tr>
          <td></td>
            <td>msg</td>
            <td>msgType</td>
            <td>phpFunction</td>
            <td>phpFile</td>
          <td></td>
        </tr>
        <tr>
          <td>Enter test data:</td>
            <td><input style='background : Yellow;' type='text' name='rVars[data][form][test][msg]'     value='The red fox' size='12' ></td>
            <td><input style='background : Yellow;' type='text' name='rVars[data][form][test][msgType]' value='ERROR' size='12' ></td>
            <td><input style='background : Yellow;' type='text' name='rVars[data][form][test][phpFunc]' value='init'  size='12' ></td>
          <td><input style='background : Yellow;' type='text' name='rVars[data][form][test][phpFile]' value='test.class.php' size='12' ></td>
          <td><input type=submit name='rVars[do]' value='Test'></td>
        </tr>
        </table><br>
    </td></tr></table>
EOD;
$htmlOut = <<<EOD
      <form action='{$this->self}' method='post'>
        <input type=hidden name='rVars[action]'  value='showLogPropForm'>
        <input type=hidden name='rVars[data][class]'  value='{$currentClassPath}'>
        {$toolTop}
        <hr>
        <br><strong>DB Log Properties</strong>
        {$htmlTbl_db}
        <br><strong>File Log Properties</strong>
        {$htmlTbl_file}
        <br><br>
        {$buttons}
        <br>
        <hr>
        {$testInput}
      </form>
EOD;
return $htmlOut;}
function &_action_dbUpdate(&$logProperties) {$status = TRUE;$logger = &$GLOBALS['Bs_Logger'];for ($i=0; $i<sizeOf($logProperties); $i++) {$prop = &$logProperties[$i];$prop['error'] = '';if (($prop['target']=='db') AND ($prop['active'])) {if (!eregi('log$', $prop['targetName'])) $prop['targetName'] .= 'Log';if (!$logger->setDB($this->dsn)) {$prop['error'] = '['.basename(__FILE__).':'.__LINE__.'] Error in _action_dbUpdate():' . Bs_Error::getLastError();$status = FALSE;} elseif (!$logger->createLogTable($prop['targetName'])) {$prop['error'] = '['.basename(__FILE__).':'.__LINE__.'] Error in _action_dbUpdate():' . Bs_Error::getLastError();$status = FALSE;} else {}
}
}
return $status;}
function _action_displayDbConnect($rVars, $errTxt='') {$currentClassPath = $rVars['data']['class'];$toDo = $rVars['do'];$htmlOut = 'nothing';do { $tryBlock = 1;$toolTop = $this->_getMenuTop($currentClassPath);if ($toDo == 'dbLogin') {$this->dsn['host'] = empty($this->dsn['host']) ? 'localhost' : $this->dsn['host'];$this->dsn['user'] = empty($this->dsn['user']) ? '' : $this->dsn['user'];$this->dsn['pass'] = empty($this->dsn['pass']) ? '' : $this->dsn['pass'];$htmlOut = <<< EOD
          <form action='{$this->self}' method='post'>
            <input type=hidden name='rVars[action]' value='dbConnect'>
            <input type=hidden name='rVars[data][class]'  value='{$currentClassPath}'>
            {$toolTop}
            <hr>
            <TABLE> 
            <TR>
              <TD>Host:</TD><TD><input type='text' name='rVars[data][form][host]' value='{$this->dsn['host']}'></TD>
            <TR>
            <TR>
              <TD>User:</TD><TD><input type='text' name='rVars[data][form][user]' value='{$this->dsn['user']}'></TD>
            <TR>
            <TR>
              <TD>Password:</TD><TD><input type='text' name='rVars[data][form][pass]' value='{$this->dsn['pass']}'></TD>
            <TR>
            </TABLE>
            <input type='submit' name='rVars[do]' value='connect'>
          </form>
EOD;
break $tryBlock;}
if ($toDo == 'connect') {$dsn = $rVars['data']['form'];$this->dsn['host'] = empty($dsn['host']) ? 'localhost' : $dsn['host'];$this->dsn['user'] = empty($dsn['user']) ? '' : $dsn['user'];$this->dsn['pass'] = empty($dsn['pass']) ? '' : $dsn['pass'];$this->dsn['name'] = empty($dsn['name']) ? '' : $dsn['name'];$ret = $this->bsDb->connect($this->dsn);if (isEx($ret)) {$this->dsn['connectOK'] = FALSE;$htmlOut = $ret->_toHtml();break $tryBlock;}
$this->dsn['connectOK'] = TRUE;$dbNames = &$this->bsDb->fetchDatabaseNames();$selectOptions = $this->htmlUtil->arrayToHtmlSelect($dbNames, $this->dsn['name']);$htmlOut = <<< EOD
          <form action='{$this->self}' method='post'>
            <input type=hidden name='rVars[action]' value='showLogPropForm'>
            <input type=hidden name='rVars[data][class]'  value='{$currentClassPath}'>
            <input type=hidden name='rVars[do]'  value='show'>
            {$toolTop}
            <hr>
            <TABLE> 
            <TR>
              <TD>Select the DB :</TD><TD>
                <select name='rVars[data][db]'>{$selectOptions}</select>
              </TD>
            <TR>
            </TABLE>
            <input type='submit' name='' value='Check'>
          </form>
EOD;
break $tryBlock;}
} while(FALSE); return $htmlOut;}
function _action_displayError($rVars) {$htmlOut = '';do { if (isSet($rVars['data']['nr'])) {$nr = $rVars['data']['nr'];$htmlOut = "<hr><span style='color:Red;'>";$htmlOut .= "Following errors occured during instanciation of <strong>'{$this->classHyrachy[$nr]['name']}'</strong> </span><br>";$htmlOut .= "(Usually 'Missing argument'-error because instantiation is done with *NO* arguments)<br>";$htmlOut .= "<hr>";$errList =& $this->classHyrachy[$nr]['error'];foreach ($errList as $key => $val) {$htmlOut .= $val;}
} elseif (isSet($rVars['data']['varName'])) {$varName = $rVars['data']['varName'];$htmlOut = "<span style='color:Red;'>";$htmlOut .= "<strong>WARNING: $varName</strong></span><br>";$htmlOut .= nl2br($this->_formErrors[$varName]);$htmlOut .= "<br>";} else {$htmlOut = "Can't display error(s). Missing a value";}
} while(FALSE); return $htmlOut;}
function _echoHtmlPage(&$htmlBody, $htmlHead='') {$htmlOut = <<< EOD
      <html>
      <head>
          <title>Untitled</title>
        {$htmlHead}
      </head>
      <body style="font-family: Verdana, Arial; font-size: 10px;" background="./pics/background.gif" link="#0000FF" vlink="#000080">
      {$htmlBody}
      </body>
      </html>
EOD;
echo $htmlOut;}
function &_form2property(&$rVars) {do {$toDo = isSet($rVars['do']) ? $rVars['do'] : '';if ($toDo=='load') {$currentClassPath = $rVars['data']['class'];include($currentClassPath);if (isSet($bs_logger_property)) {$logProperty = $bs_logger_property;} else {echo 'Error ';}
} else { $logProperty = (!empty($this->_logProperty)) ? $this->_logProperty : array();}
if (!isSet($rVars['data']['form']['logProp'])) break; $formData = $rVars['data']['form'];$form_data = &$formData['logProp'];reset($form_data);while(list($pos,$tupel) = each($form_data)) {$logProperty[$pos]['active'] = FALSE;$logProperty[$pos]['delLog'] = FALSE;$logProperty[$pos]['hitNquit'] = FALSE;reset($tupel);while(list($key) = each($tupel)) {if (($key=='active') OR ($key=='delLog') OR ($key=='hitNquit')) {$logProperty[$pos][$key] = TRUE;} else {$logProperty[$pos][$key] = $tupel[$key];}
}
}
} while (FALSE);$finalProperty = array();reset($logProperty);while(list($pos) = each($logProperty)) {if (empty($logProperty[$pos]['targetName']))  continue; if ($logProperty[$pos]['target']=='db') {if (!eregi('log$', $logProperty[$pos]['targetName'])) $logProperty[$pos]['targetName'] .= 'Log';}
$finalProperty[] = $logProperty[$pos];}
return $finalProperty;}
function _propMakeRegEx(&$logProperty) {$size = sizeOf($logProperty);for ($i=0; $i<$size; $i++) {$logProp = &$logProperty[$i];$reg = '';if ($logProp['msg_reg']!='') {$reg .= $logProp['matcher'] . '("' . str_replace('$', '\$', $logProp['msg_reg']) . '", $msg)';}
if ($logProp['type_reg']!='') {if ($reg != '') $reg .= ' ' . $logProp['mt_cond'] . ' ';$reg .= $logProp['matcher'] . '("' . str_replace('$', '\$', $logProp['type_reg']) . '", $msgType)';}
if ($logProp['phpFile_reg']!='') {if ($reg != '') $reg .= ' ' . $logProp['tp_cond'] . ' ';$reg .= $logProp['matcher'] . '("' . str_replace('$', '\$', $logProp['phpFile_reg']) . '", $phpFile)';}
if ($logProp['freeStyle_reg']!='') {if ($reg != '') {$reg .= ' ' . $logProp['pf_cond'] . ' (' . $logProp['freeStyle_reg'] . ')';} else {$reg .= $logProp['freeStyle_reg'];}
}
if ($reg == '') {$logProp['regEx'] = 'return FALSE;';} else {$logProp['regEx'] = 'return ( ' . $reg . ' );';}
}
}
function savePropertyFile($fileAndPath) {$status = FALSE;$out = '<'."?php\n"
. "/**\n"
. " * This is the Logger property file. \n"
. " * It was created and is maintained by the Bs_LogAdmin.class.php tool\n"
. " */\n";$this->_propMakeRegEx($this->_logProperty);$size = sizeOf($this->_logProperty);for ($i=0; $i<$size; $i++) {$logProp = $this->_logProperty[$i];$out .= "\n # Target: {$logProp['targetName']} \n";$out .= '  $bs_logger_property[] = array('."\n";$firstCall = TRUE;foreach($this->_logPropertyTemplate as $key => $dev0) {$val = $logProp[$key];if ($firstCall) $firstCall = FALSE; else $out .= ",\n";$out .= str_pad("    '$key' ", 18, ' ', STR_PAD_RIGHT) . '=> ';if (is_bool($val)) {$out .= ($val) ? 'TRUE' : 'FALSE';} else {$out .= "'" . $val . "'";}
}
$out .= "\n  );\n";}
$out .= '?'.'>';do { $fp = fopen($fileAndPath,  'wb');if (!$fp) {Bs_Error::setError("Failed open the file for writing: [{$fileAndPath}]", 'ERROR');break; }
if (!fwrite($fp, $out)){Bs_Error::setError("Failed to write (but was able to open) the file: [{$fileAndPath}]", 'ERROR');break; }
@fclose($fp);$status = TRUE;} while(FALSE);return $status;}
}
session_start();          if (isSet($_SESSION['logAdmin_stream'])) {$logAdmin = unserialize($_SESSION['logAdmin_stream']);} else {$logAdmin = new Bs_LogAdmin();}
$rVars = isSet($_REQUEST['rVars']) ? $_REQUEST['rVars'] : NULL;$logAdmin->handleEvent($rVars);$_SESSION['logAdmin_stream'] = $logAdmin->serialize();?>
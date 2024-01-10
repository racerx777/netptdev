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
require_once($_SERVER['DOCUMENT_ROOT'] . "../global.conf.php");require_once($APP['path']['core'] . 'file/Bs_Dir.class.php');require_once($APP['path']['core'] . 'util/Bs_String.class.php');require_once($APP['path']['core'] . 'db/Bs_MySql.class.php');require_once($APP['path']['core'] . 'storage/oodb/Bs_OoDbForMySql.class.php');require_once($APP['path']['core'] . 'html/Bs_HtmlUtil.class.php');require_once($APP['path']['core'] . 'html/table/Bs_HtmlTable.class.php');require_once($APP['path']['core'] . 'html/table/Bs_HtmlTableWindrose.class.php');class Bs_OoDbAble extends Bs_Object {var $self = '';var $dirRoot =  '';var $rVars = array();var $_frameSetup   = '';var $_frameMain    = 'classMain';var $_frameNav     = 'classNav';var $_htmlHead = '';var $_htmlBody = '';var $windrose = NULL;var $classHyrachy = NULL;var $_formErrors = array();var $_newClassData = '';var $bsDb = NULL;var $dsn = array('host'=>'localhost', 'name'=>'', 'user'=>'root', 'pass'=>'', 'connectOK'=>FALSE);function Bs_OoDbAble() {parent::Bs_Object();$this->_init();}
function _init() {global $APP;$this->self      = $_SERVER['PHP_SELF'];$this->dirRoot   =& $APP['path']['core'];$this->bsDb      =& $GLOBALS['bsDb'];$this->bsDb->connect($this->dsn);$this->ooDb      =& new Bs_OoDbForMySql(&$this->bsDb);$this->htmlUtil  =& $GLOBALS['Bs_HtmlUtil'];$this->windrose  =& new Bs_HtmlTableWindrose();$this->windrose->read('./ooDbAble.style');$this->htmlTbl   =& new Bs_HtmlTable();$this->htmlTbl->setWindroseStyle(&$this->windrose);}
function serialize() {unSet($this->bsDb);unSet($this->ooDb);unSet($this->htmlUtil);unSet($this->windrose);unSet($this->htmlTbl);return serialize($this);}
function handleEvent(&$rVars) {$this->_init();$htmlBody = '';do { $tryBlock=1;if (is_null($rVars)) { $htmlBody = <<< EOD
          <!-- frames -->
          <frameset  cols="20%,*">
              <frame name="{$this->_frameNav}"  src="{$this->self}?rVars[action]=start&rVars[data][frame]={$this->_frameNav}" marginwidth="10" marginheight="10" scrolling="auto" frameborder="0">
              <frame name="{$this->_frameMain}" src="{$this->self}?rVars[action]=start&rVars[data][frame]={$this->_frameMain}" marginwidth="10" marginheight="10" scrolling="auto" frameborder="0">
          </frameset>
EOD;
echo  $htmlBody;exit;}
$this->rVars = &$rVars;$tryBlock++;switch ($rVars['action']) {case 'start':
if ($rVars['data']['frame']==$this->_frameNav) {$rVars['data']['dir']=$this->dirRoot;$htmlBody .= $this->_action_selectFile($rVars);} else {$htmlBody .= 'Nothing selected';}
break;case 'cd':
$this->_formErrors = array();$htmlBody .= $this->_action_selectFile($rVars);break;case 'showPersistForm':
if (!empty($rVars['data']['db'])) $this->dsn['name'] = $rVars['data']['db'];$this->_formErrors = array();$htmlBody .= $this->_action_showPersistForm($rVars);break;case 'checkForm':
if  ($this->_action_checkForm($rVars, $tmpProperty)) {$htmlBody .= $this->_action_saveProperty_query($rVars);} else {$rVars['action'] = 'showPersistForm';$rVars['do'] = 'editProperty';$htmlBody .= $this->_action_showPersistForm($rVars, $tmpProperty);}
break;case 'saveProperty':
if  ($this->_action_saveProperty_commit($rVars, $errTxt)) {$rVars['action'] = 'showPersistForm';$rVars['do'] = 'show';$htmlBody .= $this->_action_showPersistForm($rVars);} else {$htmlBody .= 'Error<br>' . $errTxt;}
break;case 'displayError':
$htmlBody .= $this->_action_displayError($rVars);break;case 'dbConnect':
$htmlBody .= $this->_action_displayDbConnect($rVars, $errTxt);break;case 'ooDbAnalyse':
$htmlBody .= $this->_action_ooDbAnalyse(&$rVars, &$errTxt);break;case 'ooDbEdit':
$htmlBody .= $this->_action_ooDbEdit(&$rVars, &$errTxt);break;default:
$htmlBody .=  "INVALID ACTION: '{$rVars['action']}'";}
$tryBlock--;} while(FALSE); $this->_echoHtmlPage($htmlBody);}
function &_getMenuTop($currentClassPath) {$className = basename($currentClassPath);if ($this->dsn['connectOK']) {$dbConnectedGif = 'dbOnline.gif';$dbConnectedTxt = "{$this->dsn['host']}:{$this->dsn['name']}";$dbUpdate = "<A HREF={$this->self}?rVars[action]=ooDbAnalyse&rVars[data][class]={$currentClassPath} TARGET={$this->_frameMain}><img src='./pics/dbUpdate_hi.gif' border=0 alt='update Db'>";$dbUpdate .= "<br>[update Db]</A>";$objEdit = "<A HREF={$this->self}?rVars[action]=ooDbEdit&rVars[data][class]={$currentClassPath} TARGET={$this->_frameMain}><img src='./pics/objectEdit_hi.gif' border=0 alt='edit object'>";$objEdit .= "<br>[edit object]</A>";} else {$dbConnectedGif = 'dbOffline.gif';$dbConnectedTxt = 'No Db:Table selected';$dbUpdate = "<img src='./pics/dbUpdate_lo.gif' border=0 alt='update Db'><br><span style='color:silver'>[update Db]</span></A>";$objEdit = "<img src='./pics/objectEdit_lo.gif' border=0 alt='edit object'><br><span style='color:silver'>[edit object]</span></A>";}
$htmpOut = <<<EOD
       <hr>
       <TABLE  style='font-size:10px;'>
         <tr>
           <td align=center><img src='./pics/object_smal.gif' border=0></td>
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
             <A HREF={$this->self}?rVars[action]=showPersistForm&rVars[do]=show&rVars[data][class]={$currentClassPath} TARGET={$this->_frameMain}><img src="./pics/examine.gif" border="0" alt="examine">
             <br>[examine class]</A>
           </td>
           <td align=center>
             <A HREF={$this->self}?rVars[action]=showPersistForm&rVars[do]=editProperty&rVars[data][class]={$currentClassPath} TARGET={$this->_frameMain}><img src="./pics/property.gif" border="0" alt="edit property">
             <br>[edit property]</A>
           </td>
           <td align=center>
             {$dbUpdate}
           </td>
           <td align=center>
             {$objEdit}
           </td>
         </tr>
       </TABLE>
EOD;
return $htmpOut;}
function &_action_selectFile(&$rVars) {$startDir = $rVars['data']['dir'];$htmlOut = '';$splitedPath = array();$tmpSplitPath = explode('/', $startDir);$splitSize = sizeOf($tmpSplitPath);for ($i=0; $i<$splitSize; $i++) {if ($tmpSplitPath[$i] == '') continue;$splitedPath[] = $tmpSplitPath[$i];}
$Dir = new Bs_Dir($startDir);$absDir = '';for ($i=0; $i<sizeOf($splitedPath); $i++) {$absDir .= urlencode($splitedPath[$i] .'/');$htmlOut .= "<A HREF={$this->self}?rVars[action]=cd&rVars[data][dir]={$absDir} TARGET={$this->_frameNav}>{$splitedPath[$i]}</A> / ";}
$htmlOut .= "<br><br>\n";$array = array('depth'=>1, 'returnType'=>'subpath', 'regEx' => '', 'regFunction' => 'eregi', 'fileDirLink' => array('dir' => TRUE, 'file'=>FALSE));$relDirList = $Dir->getFileList($array);if (isEx($relDirList)) {$relDirList->stackTrace('', __FILE__, __LINE__);$relDirList->stackDump('echo');}
$array['returnType'] = 'fullpath';$absDirList = $Dir->getFileList($array);if (isEx($absDirList)) {$relDirList->stackTrace('', __FILE__, __LINE__);$absDirList->stackDump('echo');}
while(list($j) = each($relDirList)) {$absDir = urlencode($absDirList[$j]);$htmlOut .=  "<A HREF={$this->self}?rVars[action]=cd&rVars[data][dir]={$absDir} TARGET={$this->_frameNav}>" .
"<IMG src='./pics/dossier.gif' border='0' alt=''> " . $relDirList[$j] . "</A><br>\n";}
$array = array('depth'=>1, 'returnType'=>'subpath', 'regEx' => '.class.php$', 'regFunction' => 'eregi', 'fileDirLink' => array('dir' =>FALSE, 'file'=>TRUE));$fileList = $Dir->getFileList($array);if (isEx($fileList)) {$relDirList->stackTrace('', __FILE__, __LINE__);$fileList->stackDump('echo');}
$htmlOut .= "<br><br>\n";while(list($j) = each($fileList)) {$absClassFile = urlencode($startDir . $fileList[$j]);$htmlOut .= "<A HREF={$this->self}?rVars[action]=showPersistForm&rVars[do]=show&rVars[data][class]={$absClassFile} TARGET={$this->_frameMain}>" .
"<IMG src='./pics/object_smal.gif' border='0' alt=''> "  . $fileList[$j]. "</A><br>\n";}
return $htmlOut;}
function &_action_showPersistForm(&$rVars, $lastFormProperty=NULL) {$currentClassPath = $rVars['data']['class'];$toDo = $rVars['do'];$parentRow = array();$chieldTbl =& new Bs_HtmlTable();$chieldTbl->setWindroseStyle(&$this->windrose);$this->classHyrachy = &$this->_analyseClass($currentClassPath);$htmlObjHyrachy = '<strong>Object Hyrachy :</strong><br>';for ($i=0; $i<sizeOf($this->classHyrachy); $i++) {$indent = str_pad('', ($i+1)*2*strlen('&nbsp;'), '&nbsp;');if ($i==0) {$htmlObjHyrachy .= $indent . $this->classHyrachy[$i]['name'] . '<br>';} else {$htmlObjHyrachy .=  $indent  . '->' . $this->classHyrachy[$i]['name']. '<br>';}
}
$hyrachySize = sizeOf($this->classHyrachy);for ($i=0; $i<$hyrachySize; $i++) {$tblRow = array(); $rowNr=0;$class = &$this->classHyrachy[$i];$tblRow[$rowNr] = array($class['name']);if (sizeOf($class['error'])<=0) {$tblRow[$rowNr][] = '<span style="color:#00CC99; background:#FFFF99;">&nbsp;&nbsp;OK&nbsp;&nbsp; </SPAN>';} else {$tblRow[$rowNr][] = "<A HREF=\"javascript:w=window.open('{$this->self}?rVars[action]=displayError&rVars[data][nr]={$i}', 'classErr',  'scrollbars,resizable,width=600,height=150,left=200,top=500'); w.focus(); void('');\" " .
'<span style="color:Red; font-weight:bold; background:#FFFF99;">Errors</SPAN></A>';}
$formDisable = ($toDo==='show') ? 'disabled' : '';reset($class['vars']);while (list($varName, $val) = each($class['vars'])) {if (empty($lastFormProperty)) {if (empty($class['scaned_ooDbProperty'][$varName])) {unSet($pProperty);} else {$pProperty = &$class['scaned_ooDbProperty'][$varName];}
} else {if (empty($lastFormProperty[$varName])) continue;$pProperty = &$lastFormProperty[$varName];}
if ($rowNr==0) { $rowNr++;$tblRow[$rowNr] = array('varName','PHP-type','metaType<br>(lonely)','Persist ?','mode','index<br>(lonely)','streamName<br>(stream)', 'useScope<br>(object)','readOnly<br>(object)', 'weak ref.<br>(object)', 'ignor');}
$rowNr++;if (empty($this->_formErrors[$varName])) {$theVarName = $varName;} else {$theVarName = "<A HREF=\"javascript:w=window.open('{$this->self}?rVars[action]=displayError&rVars[data][varName]={$varName}', 'classErr',  'scrollbars,resizable,width=600,height=150,left=200,top=500'); w.focus(); void('');\" " .
'<span style="color:Red; font-weight:bold; font-size:14px; background:#FFFF99;">&nbsp;!&nbsp;</SPAN></A>&nbsp;';$theVarName .= $varName;}
$tblRow[$rowNr][] = $theVarName;$tblRow[$rowNr][] = strToLower(getType($val));$metaType = (isSet($pProperty['metaType'])) ? $pProperty['metaType'] : strToLower(getType($val));$tmp = "<select name='rVars[data][form][{$varName}][metaType]' {$formDisable}>";$tmp .= "<option value=''></option>";$tmp .= "<option value='string'"  . ($metaType=='string'  ? 'SELECTED' : ''). ">string</option>";$tmp .= "<option value='integer'" . ($metaType=='integer' ? 'SELECTED' : ''). ">integer</option>";$tmp .= "<option value='double'"  . ($metaType=='double'  ? 'SELECTED' : ''). ">double</option>";$tmp .= "<option value='boolean'" . ($metaType=='boolean' ? 'SELECTED' : ''). ">boolean</option>";$tmp .= "<option value='blob'"    . ($metaType=='blob'    ? 'SELECTED' : ''). ">blob</option>";$tmp .= "</select>";$tblRow[$rowNr][] = $tmp;$checked = isSet($pProperty) ? 'checked' : '';$tblRow[$rowNr][] = "<div align='center'><input type='checkbox' {$checked} name='rVars[data][form][{$varName}][persist]' {$formDisable} style='HEIGHT:14px'></div>";$mode = isSet($pProperty['mode']) ? $pProperty['mode'] : '';$tmp = "<select name='rVars[data][form][{$varName}][mode]' {$formDisable}>";$tmp .= "<option value=''></option>";$tmp .= "<option value='lonely'" .($mode=='lonely' ? 'SELECTED' : ''). ">lonely</option>";$tmp .= "<option value='stream'" .($mode=='stream' ? 'SELECTED' : ''). ">stream</option>";$tmp .= "<option value='object'" .($mode=='object' ? 'SELECTED' : ''). ">object</option>";$tmp .= "</select>";$tblRow[$rowNr][] = $tmp;$checked = (isSet($pProperty['index']) AND $pProperty['index']) ? 'checked' : '';$tblRow[$rowNr][] = "<div align='center'><input type='checkbox' {$checked} name='rVars[data][form][{$varName}][index]' {$formDisable} style='HEIGHT:14px'></div>";$streamName = (isSet($pProperty['mode']) AND ($pProperty['mode']=='stream')) ? $pProperty['streamName'] : '';$streamName = $streamName==BS_OODB_STREAM_DEFAULT_NAME ? '' : $streamName;$tblRow[$rowNr][] = "<input type='text' {$formDisable} name='rVars[data][form][{$varName}][streamName]' value='{$streamName}' size='12' >";$tblRow[$rowNr][] = "<input type='text' {$formDisable} name='rVars[data][form][{$varName}][useScope]' value='".(!empty($pProperty['useScope']) ? $pProperty['useScope'] : '')."' size='12' >";$checked = (isSet($pProperty['readOnly']) AND $pProperty['readOnly']) ? 'checked' : '';$tblRow[$rowNr][] = "<div align='center'><input type='checkbox' {$checked} name='rVars[data][form][{$varName}][readOnly]' {$formDisable} style='HEIGHT:14px'></div>";$checked = (isSet($pProperty['reference']) AND ($pProperty['reference'])=='weak') ? 'checked' : '';$tblRow[$rowNr][] = "<div align='center'><input type='checkbox' {$checked} name='rVars[data][form][{$varName}][reference]' {$formDisable} style='HEIGHT:14px'></div>";$checked = (isSet($pProperty['ignor']) AND $pProperty['ignor']) ? 'checked' : '';$tblRow[$rowNr][] = "<div align='center'><input type='checkbox' {$checked} name='rVars[data][form][{$varName}][ignor]' {$formDisable} style='HEIGHT:14px'></div>";}
$chieldTbl->initByMatrix($tblRow);$parentRow[] = $chieldTbl;}
$this->htmlTbl->initByMatrix($parentRow);$this->htmlTbl->flipData();$htmlTbl = $this->htmlTbl->renderTable();$toolTop = $this->_getMenuTop($currentClassPath);$buttons = '';if ($toDo=='editProperty')  {$buttons = "<input type='submit' name='rVars[do]' value='{$toDo}'>";}
$htmlOut = <<<EOD
      <form action='{$this->self}' method='post'>
        <input type=hidden name='rVars[action]' value='checkForm'>
        <input type=hidden name='rVars[data][class]'  value='{$currentClassPath}'>
        {$toolTop}
        <hr>
        <span style="font-family: courier; font-size: 16px;">
          {$htmlObjHyrachy}
        </span>
        <hr>
        {$htmlTbl}
        <br><br>
        {$buttons}
      </form>
EOD;
return $htmlOut;}
function &_action_checkForm(&$rVars, &$tmpProperty) {$formData = &$rVars['data']['form'];$this->_formErrors = array();$tmpProperty = array();$status = $this->_form2property($formData, $tmpProperty);return $status;}
function _action_saveProperty_query(&$rVars) {$status = TRUE;$currentClassPath = $rVars['data']['class'];$formData = $rVars['data']['form'];$toDo = $rVars['do'];$htmlOut = '';do { $tryBlock = 1;if (isSet($this->classHyrachy['interfaceStatus']) AND $this->classHyrachy['interfaceStatus']) {$htmlOut .= "It's OK (no change) <IMG src='./pics/OK.gif' border='0'>";$status = TRUE;break $tryBlock;}
$errTxt = '';if ($splittedFile = $this->_extractPropertyFromFile($currentClassPath, $errTxt)) {} else {$status = FALSE;$htmlOut .= 'ERROR <br>' . $errTxt;break $tryBlock;}
$tmpProperty = array();$this->_form2property($formData, $tmpProperty);$firstLoop = TRUE;$oldPropertyCode = $newPropertyCode = '';$newPropertyCode = "  var \$_ooDbProperty = array ( \n";reset($tmpProperty);while (list($varName) = each($tmpProperty)) {$property = &$tmpProperty[$varName];if ($firstLoop) $firstLoop=FALSE; else $newPropertyCode .= ",\n";switch($property['mode']) {case 'lonely':
$index = $property['index'] ? 'TRUE' : 'FALSE';$ignor     = $property['ignor'] ? ", 'ignor'=>TRUE" : '';$newPropertyCode .= "    '{$varName}' => array('mode'=>'{$property['mode']}', 'metaType'=>'{$property['metaType']}', 'index'=>{$index} {$ignor})";break;case 'stream':
$ignor     = $property['ignor'] ? ", 'ignor'=>TRUE" : '';$newPropertyCode .= "    '{$varName}' => array('mode'=>'{$property['mode']}', 'streamName'=>'{$property['streamName']}' {$ignor})";break;case 'object':
$readOnly  = $property['readOnly'] ? ", 'readOnly'=>TRUE" : '';$ignor     = $property['ignor'] ? ", 'ignor'=>TRUE" : '';$reference = $property['reference'] == 'weak' ? ", 'reference'=>'weak'" : '';$newPropertyCode .= "    '{$varName}' => array('mode'=>'{$property['mode']}',  'useScope'=>'{$property['useScope']}' {$readOnly} {$reference} {$ignor})";break;default:
continue; }
}
$newPropertyCode .= "\n    );\n";$this->_newClassData = implode('', $splittedFile['prefix']) . $newPropertyCode . implode('', $splittedFile['postfix']);$startLine = sizeOf($splittedFile['prefix']);$newPropertyCode_highlight = $oldPropertyCode_highlight = '';$oldPropertyCode = implode('', $splittedFile['core']);$oldPropertyCode = Bs_String::addLineNumbers($oldPropertyCode,$startLine+1);$newPropertyCode = Bs_String::addLineNumbers($newPropertyCode,$startLine+1);$somePrefixCode = "<?php // <-- *IGNOR* this php tag. It's only to force code coloring.\n";ob_start();highlight_string($somePrefixCode . $oldPropertyCode);$oldPropertyCode_highlight = ob_get_contents();ob_end_clean();ob_start();highlight_string($somePrefixCode . $newPropertyCode);$newPropertyCode_highlight = ob_get_contents();ob_end_clean();} while(FALSE); if ($status) {$toolTop = $this->_getMenuTop($currentClassPath);$htmlOut = <<<EOD
          {$toolTop}
          <hr>
          Will modify code of file<br>&nbsp;&nbsp;&nbsp;&nbsp;<strong>{$currentClassPath}<br><br>REPLACE:</strong><br>
          <hr>
          <span style='font-size:10px'>
            {$oldPropertyCode_highlight}
          </span>          
          <hr>
          <strong>WITH:</strong><br>
          <hr>
          <span style='font-size:10px'>
            {$newPropertyCode_highlight}
          </span>
          <hr>
          <br><br>
          <TABLE><TR>
            <TD align=center>
              <A HREF={$this->self}?rVars[action]=saveProperty&rVars[do]=save&rVars[data][class]={$currentClassPath} TARGET={$this->_frameMain}><img src="./pics/OK.gif" border="0" alt="save">
              <br>[save]</A>
            </TD>
            <TD align=center>
              <A HREF={$this->self}?rVars[action]=showPersistForm&rVars[do]=show&rVars[data][class]={$currentClassPath} TARGET={$this->_frameMain}><img src="./pics/cancel.gif" border="0" alt="cancel">
              <br>[cancel]</A>
            </TD>
          </TR></TABLE>
EOD;
} else {}
return $htmlOut;}
function _action_saveProperty_commit(&$rVars, &$errTxt) {$status = TRUE;$currentClassPath = $rVars['data']['class'];$toDo = $rVars['do'];do { $tryBlock = 1;if (copy($currentClassPath, $currentClassPath . '.bak')) {} else {$errTxt = "Failed to copy from {$currentClassPath} to {$currentClassPath}.bak " . basename(__FILE__).':'.__LINE__;$status = FALSE;break $tryBlock;}
$fp = fopen($currentClassPath, 'wb');if ($fp) {if (!empty($this->_newClassData)) {fwrite($fp, $this->_newClassData);} else {$errTxt = "Did nothing. No data to write. " . basename(__FILE__).':'.__LINE__;$status = FALSE;break $tryBlock;}
} else {$errTxt = "Did nothing. Could not open file. " . basename(__FILE__).':'.__LINE__;$status = FALSE;break $tryBlock;}
} while(FALSE); if (isSet($fp)) @fclose($fp);return $status;}
function _action_displayDbConnect(&$rVars, &$errTxt) {$currentClassPath = $rVars['data']['class'];$toDo = $rVars['do'];$htmlOut = 'nothing';do { $tryBlock = 1;$toolTop = $this->_getMenuTop($currentClassPath);if ($toDo == 'dbLogin') {$this->dsn['host'] = empty($this->dsn['host']) ? 'localhost' : $this->dsn['host'];$this->dsn['user'] = empty($this->dsn['user']) ? '' : $this->dsn['user'];$this->dsn['pass'] = empty($this->dsn['pass']) ? '' : $this->dsn['pass'];$htmlOut = <<< EOD
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
if ($toDo == 'connect') {$dsn = &$rVars['data']['form'];$this->dsn['host'] = empty($dsn['host']) ? 'localhost' : $dsn['host'];$this->dsn['user'] = empty($dsn['user']) ? '' : $dsn['user'];$this->dsn['pass'] = empty($dsn['pass']) ? '' : $dsn['pass'];$this->dsn['name'] = empty($dsn['name']) ? '' : $dsn['name'];$ret = $this->bsDb->connect($this->dsn);if (isEx($ret)) {$this->dsn['connectOK'] = FALSE;$htmlOut = $ret->_toHtml();break $tryBlock;}
$this->dsn['connectOK'] = TRUE;$dbNames = &$this->bsDb->fetchDatabaseNames();$selectOptions = $this->htmlUtil->arrayToHtmlSelect($dbNames, $this->dsn['name']);$htmlOut = <<< EOD
          <form action='{$this->self}' method='post'>
            <input type=hidden name='rVars[action]' value='showPersistForm'>
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
function _action_ooDbAnalyse(&$rVars, &$errTxt) {$currentClassPath = $rVars['data']['class'];$toDo = empty($rVars['do']) ? 'analyse' : $rVars['do'];$actionArray = array();$currentClassFilename = basename($currentClassPath);$fagment = explode('.',$currentClassFilename);$theClassName = strToLower($fagment[0]);$htmlOut = 'nothing';do { $tryBlock = 1;$toolTop = $this->_getMenuTop($currentClassPath);$ret = $this->bsDb->connect($this->dsn);if (isEx($ret)) {$htmlOut = $ret->_toHtml();break $tryBlock;}
if ($toDo=='analyse') {$theClassName_cih = $theClassName . BS_OODB_CLEAR_IN_HOUSE_EXTENTION;$actionArray[$theClassName]['exists']  = $this->bsDb->tableExists($theClassName);$actionArray[$theClassName_cih]['exists'] = $this->bsDb->tableExists($theClassName_cih);$actionArray[BS_OODB_CLEAR_IN_HOUSE_TBL]['exists'] = $this->bsDb->tableExists(BS_OODB_CLEAR_IN_HOUSE_TBL);$allOk = TRUE;reset($actionArray);while(list($tblName) = each($actionArray)) {if (isEx($actionArray[$tblName]['exists'])) {$actionArray[$tblName]['error'] = $actionArray[$tblName]->_toHtml();$actionArray[$tblName]['exists'] = FALSE;} elseif ($actionArray[$tblName]['exists']) {$actionArray[$tblName]['error'] = 'OK';} else {$actionArray[$tblName]['error'] = 'Missing';}
$allOk = ($allOk AND $actionArray[$tblName]['exists']);}
if ($allOk) {$toDo='compareTbl';} else {$tblRow = array();$tblRow[] = array('Table', 'Error');reset($actionArray);while(list($tblName) = each($actionArray)) {$tblRow[] = array($tblName,$actionArray[$tblName]['error']);}
$this->htmlTbl->initByMatrix($tblRow);$htmlTbl = &$this->htmlTbl->renderTable();$htmlOut = <<<EOD
            <form action='{$this->self}' method='post'>
              <input type=hidden name='rVars[action]' value='ooDbAnalyse'>
              <input type=hidden name='rVars[data][class]'  value='{$currentClassPath}'>
              {$toolTop}
              <hr>
              <strong>Had following errors:</strong>
              {$htmlTbl}
              <br>
              <input type='submit' name='rVars[do]' value='createTbl'>
            </form>
EOD;
break $tryBlock;}
}
if (($toDo=='createTbl') OR ($toDo=='dbUpdate')) {$ret = $this->ooDb->createClassTableFromName($currentClassPath, $this->dsn['name']);if (isEx($ret)) {$htmlOut = $ret->_toHtml();break $tryBlock;}
$toDo='compareTbl';}
if ($toDo=='compareTbl') {global $APP;require_once($currentClassPath);if (class_exists($theClassName)) {@$obj =& new $theClassName();} else {$htmlOut = " Class '{$theClassName}' does not exsist or is out of scope.". basename(__FILE__).':'.__LINE__;break $tryBlock; }
if (!is_object($obj)) {$htmlOut = " Class of type:'".getType($theClassName)."'. Object instanciation failed.". basename(__FILE__).':'.__LINE__;break $tryBlock; }
$this->ooDb->_setObject($obj);$filedNames = $this->ooDb->getFieldNames($useCach=FALSE);$matchArray = $this->ooDb->matchDbFields_Versus_OoDbProperty($this->dsn['name'], $theClassName, &$filedNames);$tblRow[] = array('varName','status', 'metaType', 'current DbType', 'NEW DbType');reset($matchArray);while(list($varName)=each($matchArray)) {$match = $matchArray[$varName];$txtColor = ($match['status']=='ok') ? 'green' : 'red';$match['status'] = "<span style='font-weight: bold; color: {$txtColor};'>{$match['status']} !<span>";$tblRow[] = array($varName, $match['status'], $match['metaType'], $match['currentDbType'], $match['newDbType']);}
$this->htmlTbl->initByMatrix($tblRow);$htmlTbl = $this->htmlTbl->renderTable();$htmlOut = <<< EOD
          <form action='{$this->self}' method='post'>
            <input type=hidden name='rVars[action]' value='ooDbAnalyse'>
            <input type=hidden name='rVars[data][class]'  value='{$currentClassPath}'>
            {$toolTop}
            <hr>
            $htmlTbl 
            <br>
            <input type='submit' name='rVars[do]' value='dbUpdate'>
          </form>
EOD;
break $tryBlock; } } while(FALSE); return $htmlOut;}
function _action_ooDbEdit(&$rVars, &$errTxt) {$currentClassPath = $rVars['data']['class'];$toDo = empty($rVars['do']) ? 'overview' : $rVars['do'];$actionArray = array();$currentClassFilename = basename($currentClassPath);$fagment = explode('.',$currentClassFilename);$theClassName = strToLower($fagment[0]);$htmlOut = 'nothing';do { $tryBlock = 1;$toolTop = $this->_getMenuTop($currentClassPath);global $APP;require_once($currentClassPath);if (class_exists($theClassName)) {@$obj =& new $theClassName();} else {$htmlOut = " Class '{$theClassName}' does not exsist or is out of scope.". basename(__FILE__).':'.__LINE__;break $tryBlock; }
if (!is_object($obj)) {$htmlOut = " Class of type:'".getType($theClassName)."'. Object instanciation failed.". basename(__FILE__).':'.__LINE__;break $tryBlock; }
$objProperty = &$this->classHyrachy[0]['scaned_ooDbProperty']; if ($toDo=='edit') {$objID = $rVars['data']['id'];$obj = &$this->ooDb->unpersist($theClassName, $objID);$tblRow[] = array($theClassName);reset($objProperty);while(list($varName) = each($objProperty)) {$varProperty = &$objProperty[$varName];switch ($varProperty['mode']) {case 'lonely':
if ($varProperty['metaType']=='boolean') {$checked = ($obj->$varName==TRUE) ? 'checked' : '';$tblRow[] = array($varName, "<input type=checkbox {$checked} name=rVars[data][form][{$varName}]>");} else {$tblRow[] = array($varName, "<input type=text name=rVars[data][form][{$varName}]  value='{$obj->$varName}'>");}
break;case 'stream':
break;case 'object':
break;default:
$tblRow[] = array($varName, '<strong>Unknown "mode" !</strong>');}
$this->htmlTbl->initByMatrix($tblRow);$this->htmlTbl->setTrStyle(1, array('font-weight'=>'bolder'));$this->htmlTbl->spanCol(0);$htmlTbl = &$this->htmlTbl->renderTable();$htmlOut = <<<EOD
          <form action='{$this->self}' method='post'>
            <input type='hidden' name='rVars[action]' value='ooDbEdit'>
            <input type='hidden' name='rVars[data][id]' value={$objID}>
            <input type='hidden' name='rVars[data][class]' value={$currentClassPath}>
            {$htmlTbl}
            <br>
            <input type='submit' name='rVars[do]' value='save'>
          </form>
EOD;
}
break $tryBlock;}
if ($toDo=='save') {$objID = empty($rVars['data']['id']) ? 0 : $rVars['data']['id'];$varNameList = &$rVars['data']['form'];if ($objID>0) {if ($this->ooDb->unpersist(&$obj, $objID) === FALSE) {$htmlOut = $this->oodb->errorDump();break $tryBlock;}
}
reset($objProperty);while(list($varName) = each($objProperty)) {if (isSet($objProperty[$varName]['metaType']) AND ($objProperty[$varName]['metaType']=='boolean')) {$obj->$varName = isSet($varNameList[$varName]);} else {if (!isSet($varNameList[$varName])) continue;$obj->$varName = $varNameList[$varName];}
}
if ($this->ooDb->persist($obj) === FALSE) {$htmlOut = $this->oodb->errorDump();break $tryBlock;} else {$htmlOut = 'SUCCESS';$toDo='overview';}
}
if ($toDo=='delete') {$objID = $rVars['data']['id'];if ($objID>0) {if ($this->ooDb->softDelete($theClassName, $objID) === FALSE) {$htmlOut = $this->ooDb->errorDump();break $tryBlock;} else {$htmlOut = 'SUCCESS';$toDo='overview';}
} else {$htmlOut = "Invalid ID:'{$objID}'";break $tryBlock;}
}
if ($toDo=='overview') {$objSet = &$this->ooDb->oQuery($theClassName, "SELECT ID FROM {$theClassName} LIMIT 40");reset($objProperty);while(list($varName) = each($objProperty)) {$varProperty = &$objProperty[$varName];if ($varProperty['mode'] == 'lonely') {$varsToShow[$varName] = &$objProperty[$varName];}
}
$rowNr = 0;$tblRow[$rowNr] = array($theClassName);$rowNr++;reset($varsToShow);$tblRow[$rowNr][] = 'ID';while(list($varName) = each($varsToShow)) {$tblRow[$rowNr][] = $varName;}
$rowNr++;for ($i=0; $i<sizeOf($objSet); $i++) {$rowNr++;$obj = &$objSet[$i];reset($varsToShow);$objID = $this->ooDb->getID($obj);$tblRow[$rowNr][] = "<A HREF={$this->self}?rVars[action]=ooDbEdit&rVars[do]=edit&rVars[data][class]={$currentClassPath}&rVars[data][id]={$objID} TARGET={$this->_frameMain}>{$objID}</A>";while(list($varName) = each($varsToShow)) {$tblRow[$rowNr][] = $obj->$varName;}
$tblRow[$rowNr][] = "<A HREF={$this->self}?rVars[action]=ooDbEdit&rVars[do]=delete&rVars[data][class]={$currentClassPath}&rVars[data][id]={$objID} TARGET={$this->_frameMain}>delete</A>";}
$rowNr++;$tblRow[$rowNr] = array('',"<A HREF={$this->self}?rVars[action]=ooDbEdit&rVars[do]=edit&rVars[data][class]={$currentClassPath}&rVars[data][id]=0 TARGET={$this->_frameMain}>Add</A>");$this->htmlTbl->initByMatrix($tblRow);$this->htmlTbl->setTrStyle(1, array('font-weight'=>'bolder'));$this->htmlTbl->spanCol(0);$htmlOut = &$this->htmlTbl->renderTable();break $tryBlock;} } while(FALSE); return $htmlOut;}
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
function &_analyseClass($currentClassPath) {global $APP, $errorHandlerLog;$objHyrachy = array();$nr = 0;$currentClassFilename = basename($currentClassPath);$fagment = explode('.',$currentClassFilename);$theClassName = strToLower($fagment[0]);set_error_handler('myErrorHandler');include_once($currentClassPath);do { $theObjToAnalyse =& new $theClassName();if (!is_Object($theObjToAnalyse)) break;$objHyrachy[$nr]['name']   = $theClassName;$objHyrachy[$nr]['vars']   = get_object_vars($theObjToAnalyse);$objHyrachy[$nr]['error']  = $errorHandlerLog;if (isSet($theObjToAnalyse->_ooDbProperty)) {$objHyrachy[$nr]['ooDbProperty'] = &$theObjToAnalyse->_ooDbProperty;} else {$objHyrachy[$nr]['ooDbProperty'] = array();}
if ($nr==0) { $objHyrachy[$nr]['allVars']   = get_object_vars($theObjToAnalyse);$this->ooDb->_setObject($theObjToAnalyse);$objHyrachy[$nr]['scaned_ooDbProperty'] = $this->ooDb->_getOoDbProperty();}
$theClassName = get_parent_class($theObjToAnalyse);   $errorHandlerLog = array();                  $nr++;unSet($theObjToAnalyse);} while ($theClassName AND ($theClassName !== ''));restore_error_handler();$levelSize = sizeOf($objHyrachy)-1;for ($i=0; $i<$levelSize; $i++ ) {$parentClass = &$objHyrachy[$i+1];$childClass  = &$objHyrachy[$i];$ownVars = array();$parentVarsArray = array_keys($parentClass['vars']);reset($childClass['vars']);while (list($varName) = each($childClass['vars'])) {if (in_array($varName, $parentVarsArray)) continue;    $ownVars[$varName] = $childClass['vars'][$varName];}
$childClass['vars'] = $ownVars;}
$retHyrachy = array();$levelSize = sizeOf($objHyrachy)-1;$j=0;for ($i=$levelSize; $i>=0; $i--) {$retHyrachy[$j] = &$objHyrachy[$i];if ($i>0) {$retHyrachy[$j]['allVars'] = &$objHyrachy[0]['allVars'];$retHyrachy[$j]['scaned_ooDbProperty'] = &$objHyrachy[0]['scaned_ooDbProperty'];}
$j++;}
return $retHyrachy;}
function _form2property(&$formData, &$tmpProperty) {$status = TRUE;reset($formData);while(list($varName) = each($formData)) {$propertyData = &$formData[$varName];if (empty($propertyData['persist'])) continue; $this->_formErrors[$varName] = '';if ($this->isReservedName($varName)) {$this->_formErrors[$varName] .= " - '{$varName}' is reserved word. It will (or may) cause errors when used with an SQL-storage.\n";}
switch ($propertyData['mode']) {case 'lonely':
$this->_formErrors[$varName] .= empty($propertyData['metaType']) ? " - You must set a metaType!\n":'';$this->_formErrors[$varName] .= (!empty($propertyData['streamName'])) ? " - Set 'streamName' only makes sens, if mode='stream'. REMOVED.\n":'';$this->_formErrors[$varName] .= (!empty($propertyData['useScope']))   ? " - Set 'useScope' only makes sens, if mode='object'. REMOVED.\n":'';$this->_formErrors[$varName] .= isSet($propertyData['readOnly']) ? " - Set 'readOnly' only makes sens, if mode='object'. REMOVED.\n":'';$this->_formErrors[$varName] .= isSet($propertyData['reference']) ? " - Set 'weak reference' only makes sens, if mode='object'. REMOVED.\n":'';if (strLen($this->_formErrors[$varName])) {$status = FALSE;}
$tmpProperty[$varName]['metaType'] = $propertyData['metaType'];$tmpProperty[$varName]['mode'] = $propertyData['mode'];$tmpProperty[$varName]['index'] = (bool)isSet($propertyData['index']);$tmpProperty[$varName]['ignor'] = (bool)isSet($propertyData['ignor']);break;case 'stream':
if (!empty($propertyData['metaType'])) {$this->_formErrors[$varName] .= " - MetaType of streams are alway blobs. Leave empty. REMOVED.\n";$tmpProperty[$varName]['metaType'] = '';}
$this->_formErrors[$varName] .= isSet($propertyData['index']) ? " - You can't set index on a stream. REMOVED.\n":'';$this->_formErrors[$varName] .= (!empty($propertyData['useScope'])) ? " - Set 'useScope' only makes sens, if mode='object'. REMOVED.\n":'';$this->_formErrors[$varName] .= isSet($propertyData['readOnly']) ? " - Set 'readOnly' only makes sens, if mode='object'. REMOVED.\n":'';$this->_formErrors[$varName] .= isSet($propertyData['reference']) ? " - Set 'weak reference' only makes sens, if mode='object'. REMOVED.\n":'';if (strLen($this->_formErrors[$varName])) {$status = FALSE;}
$tmpProperty[$varName]['mode'] = $propertyData['mode'];$tmpProperty[$varName]['streamName'] = empty($propertyData['streamName']) ? '' : $propertyData['streamName']; ;$tmpProperty[$varName]['ignor'] = (bool)isSet($propertyData['ignor']);break;case 'object':
$this->_formErrors[$varName] .= (!empty($propertyData['metaType'])) ? " - Objectes need no metaType. REMOVED.\n":'';$this->_formErrors[$varName] .= (!empty($propertyData['streamName']))  ? " - Set 'streamName' only makes sens, if mode='stream'. REMOVED.\n":'';$this->_formErrors[$varName] .= isSet($propertyData['index']) ? " - You can't set index on an object. REMOVED.\n":'';if ((!empty($propertyData['useScope'])) AND empty($propertyData['reference'])) {$propertyData['reference'] = TRUE;$this->_formErrors[$varName] .= " - You set to use the scope '{$propertyData['useScope']}'. If '{$propertyData['useScope']}' is not the default scope (wath I must assume), you must set a 'weak' ref. to a object in a foreign scope. HAS BEEN SET.";};if (strLen($this->_formErrors[$varName])) {$status = FALSE;}
$tmpProperty[$varName]['mode'] = $propertyData['mode'];$tmpProperty[$varName]['useScope'] = empty($propertyData['useScope']) ? '' : $propertyData['useScope'];$tmpProperty[$varName]['readOnly'] = (bool)isSet($propertyData['readOnly']);$tmpProperty[$varName]['ignor'] = (bool)isSet($propertyData['ignor']);$tmpProperty[$varName]['reference'] =  isSet($propertyData['reference']) ? 'weak' : 'hard';break;default:
continue; }
} return $status;}
function &_extractPropertyFromFile($currentClassPath, &$errTxt) {$currentClassFilename = basename($currentClassPath);$fagment = explode('.',$currentClassFilename);$theClassName = strToLower($fagment[0]);$hasA_ooDbProperty = isSet($this->classHyrachy[0]['allVars']['_ooDbProperty']);$theFile = array();$phpLines = file($currentClassPath);$errTxt = '';do { $tryBlock=1;$regex = "/class\s+{$theClassName}(\s+extends\s+\w+|)\s*{/Ui";$classFound = FALSE;$lineAmount = sizeOf($phpLines);for ($i=0; $i<$lineAmount; $i++) {$theFile['prefix'][$i] = &$phpLines[$i];if (!preg_match($regex, $phpLines[$i])) continue;$classFound = TRUE;break;}
if (!$classFound) {$errTxt .= "Sorry. Unable to find the 'CLASS' statment in '{$currentClassPath}'. Tryed it with regex '{$regex}'";break $tryBlock;}
if (!$hasA_ooDbProperty) {$theFile['core'] = array();} else {$varBeginFound = FALSE;$regex = '/var\s+\$_ooDbProperty\s*=\s*array/Ui';for ($i=($i+1); $i<$lineAmount; $i++) {if (!preg_match($regex, $phpLines[$i])) {$theFile['prefix'][$i] = @$phpLines[$i];continue;}
$varBeginFound = TRUE;break;}
if (!$varBeginFound) {$errTxt .= "Sorry. Unable to find the '_ooDbProperty'-var in '{$currentClassPath}'. Tryed it with regex '{$regex}'";break $tryBlock;}
$varEndFound = FALSE;$regex = '/\)\s*;/Ui';for ($i=$i; $i<$lineAmount; $i++) {$theFile['core'][$i] = @$phpLines[$i];if (!preg_match($regex, $phpLines[$i]))  continue;$varEndFound = TRUE;break;}
if (!$varEndFound) {$errTxt .= "Sorry. Unable to find the END of the '_ooDbProperty'-var in '{$currentClassPath}'. Tryed it with regex '{$regex}'";break $tryBlock;}
}
for ($i=($i+1); $i<$lineAmount; $i++) {$theFile['postfix'][$i] = @$phpLines[$i];}
} while(FALSE); if (!empty($errTxt)) {$theFile = FALSE;}
return $theFile;}
function _action_displayError(&$rVars) {$htmlOut = '';do { if (isSet($rVars['data']['nr'])) {$nr = $rVars['data']['nr'];$htmlOut = "<hr><span style='color:Red;'>";$htmlOut .= "Following errors occured during instanciation of <strong>'{$this->classHyrachy[$nr]['name']}'</strong> </span><br>";$htmlOut .= "(Usually 'Missing argument'-error because instantiation is done with *NO* arguments)<br>";$htmlOut .= "<hr>";$errList = &$this->classHyrachy[$nr]['error'];reset($errList);while (list($varName, $val) = each($errList)) {$htmlOut .= $val;}
} elseif (isSet($rVars['data']['varName'])) {$varName = &$rVars['data']['varName'];$htmlOut = "<span style='color:Red;'>";$htmlOut .= "<strong>WARNING:</strong></span><br>";$htmlOut .= nl2br($this->_formErrors[$varName]);$htmlOut .= "<br>";} else {$htmlOut = "Can't display error(s). Missing a value";}
} while(FALSE); return $htmlOut;}
function isReservedName($varName) {static $disallowed = '  
action  add  aggregate  all  
alter  after  and  as  
asc  avg  avg_row_length  auto_increment  
between  bigint  bit  binary  
blob  bool  both  by  
cascade  case  char  character  
change  check  checksum  column  
columns  comment  constraint  create  
cross  current_date  current_time  current_timestamp  
data  database  databases  date  
datetime  day  day_hour  day_minute  
day_second  dayofmonth  dayofweek  dayofyear  
dec  decimal  default  delayed  
delay_key_write  delete  desc  describe  
distinct  distinctrow  double  drop  
end  else  escape  escaped  
enclosed  enum  explain  exists  
fields  file  first  float  
float4  float8  flush  foreign  
from  for  full  function  
global  grant  grants  group  
having  heap  high_priority  hour  
hour_minute  hour_second  hosts  identified  
ignore  in  index  infile  
inner  insert  insert_id  int  
integer  interval  int1  int2  
int3  int4  int8  into  
if  is  isam  join  
key  keys  kill  last_insert_id  
leading  left  length  like  
lines  limit  load  local  
lock  logs  long  longblob  
longtext  low_priority  max  max_rows  
match  mediumblob  mediumtext  mediumint  
middleint  min_rows  minute  minute_second  
modify  month  monthname  myisam  
natural  numeric  no  not  
null  on  optimize  option  
optionally  or  order  outer  
outfile  pack_keys  partial  password  
precision  primary  procedure  process  
processlist  privileges  read  real  
references  reload  regexp  rename  
replace  restrict  returns  revoke  
rlike  row  rows  second  
select  set  show  shutdown  
smallint  soname  sql_big_tables  sql_big_selects  
sql_low_priority_updates  sql_log_off  sql_log_update  sql_select_limit  
sql_small_result  sql_big_result  sql_warnings  straight_join  
starting  status  string  table  
tables  temporary  terminated  text  
then  time  timestamp  tinyblob  
tinytext  tinyint  trailing  to  
type  use  using  unique  
unlock  unsigned  update  usage  
values  varchar  variables  varying  
varbinary  with  write  when  
where  year  year_month  zerofill  ';$pos = strpos($disallowed, '  ' . strToLower($varName) . '  ');return ($pos>0);}
}
session_start();          session_unregister('ooDbAble_stream'); unSet($ooDbAble_stream);session_register('ooDbAble_stream');if (isSet($ooDbAble_stream)) {$ooDbAble = unserialize($ooDbAble_stream);} else {$ooDbAble = new Bs_OoDbAble();}
$rVars = (isSet($_REQUEST['rVars'])) ? $_REQUEST['rVars'] : NULL;$ooDbAble->handleEvent($rVars);$ooDbAble_stream = $ooDbAble->serialize();$errorHandlerLog = array();function myErrorHandler ($errno, $errstr, $errfile, $errline) {global $errorHandlerLog;$errType = '';switch ($errno) {case E_ERROR   : $errType = 'ERROR'; break;case E_WARNING : $errType = 'WARNING'; break;case E_PARSE   : $errType = 'PARSE ERROR'; break;case E_NOTICE  : $errType = 'NOTICE'; break;case E_USER_ERROR   : $errType = 'USER_ERROR'; break;case E_USER_WARNING : $errType = 'USER_WARNING'; break;case E_USER_NOTICE  : $errType = 'USER_NOTICE'; break;case E_CORE_ERROR      : $errType = 'CORE_ERROR'; break;case E_CORE_WARNING    : $errType = 'CORE_WARNING'; break;case E_COMPILE_ERROR   : $errType = 'COMPILE_ERROR'; break;case E_COMPILE_WARNING : $errType = 'COMPILE_WARNING'; break;default : $errType = 'ERROE Unknown';}
$errorHandlerLog[] = "<strong>{$errType}: {$errstr}</strong><br>&nbsp;&nbsp;&nbsp;&nbsp;File: {$errfile} Line [$errline]<br><br>";}

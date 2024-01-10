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
require_once($_SERVER['DOCUMENT_ROOT']       . '../global.conf.php');require_once($GLOBALS['APP']['path']['core'] . 'net/http/session/Bs_SimpleSession.class.php');require_once($GLOBALS['APP']['path']['core'] . 'lang/Bs_ToDo.lib.php');Bs_XRay::activateOnIP('*');class EntryPoint extends Bs_Object {var $sessionData;           var $alertMsg = '';  function EntryPoint() {}
function init() {return TRUE;}
function go() {$_func_ = 'go';$err = ' -- See previous error --';$status = FALSE;$tryBlock_systemError = 1;do { $this->session =& $GLOBALS['Bs_SimpleSession'];$todo =& bs_requestToDo();if (empty($todo[BS_TODO_NEXT_SCREEN])) $todo[BS_TODO_NEXT_SCREEN] = 'STEP1';if (@$todo[BS_TODO_NEXT_SCREEN] === 'LOGOFF') {if (!empty($this->session)) $this->session->destroy();}
if (FALSE === $this->session->register('sessData', $this->sessionData)) {Bs_Error::setError('Failed register or load main session variable [sessData]', 'ERROR', __LINE__, $_func_, __FILE__);break; }
$status_exitScreen = FALSE;do { switch ($todo[BS_TODO_EXIT_SCREEN]) {case 'STEP1': break;case 'VOID': break;default:
bs_logIt("Unknown EXIT-screen '{$todo[BS_TODO_EXIT_SCREEN]}'", 'BOGUS', __LINE__, $_func_, __FILE__);}
$status_exitScreen = TRUE;} while(FALSE);if (!$status_exitScreen) {bs_logIt($err, 'ERROR', __LINE__, $_func_, __FILE__);XR_echo($err, __LINE__, $_func_, __FILE__);}
$tryBlock_systemError++;$status_nextScreen = FALSE;do { $tryBlock_systemError++;$tryBlock = 2;switch ($todo[BS_TODO_NEXT_SCREEN]) {case 'STEP1': $nextScreen =& new SET_Step1();$nextScreen->setSessionData($this->sessionData);break;default:
bs_logIt("Unknown NEXT-action '{$todo[BS_TODO_NEXT_SCREEN]}'", 'BOGUS', __LINE__, $_func_, __FILE__);$GLOBALS['Bs_Error']->appendAlert("Unknown NEXT-action '{$todo[BS_TODO_NEXT_SCREEN]}'", 'BOGUS', __LINE__, $_func_, __FILE__);$htmlBlocks['HtmlBlock_Data'][BS_TODO_NEXT_SCREEN] = 'SAM_STEP1';$htmlBlocks['HtmlBlock_Data']['alert']    =  $GLOBALS['Bs_Error']->getAlert();$htmlBlocks['HtmlBlock_Data']['errors']   =  $GLOBALS['Bs_Error']->toHtml();ob_start();XR_dump($todo, __LINE__, $_func_, __FILE__);if ($tmpErr = Bs_Error::getErrors()) XR_dump($tmpErr, __LINE__, $_func_, __FILE__);$htmlBlocks['HtmlBlock_Data']['dump'] = ob_get_contents();ob_end_clean();$nextScreen =& $GLOBALS['Sm_TplRenderer'];$nextScreen->prepare('SM_syserror', $htmlBlocks);}
$tryBlock_systemError--;$status_nextScreen = TRUE;} while(FALSE);$tryBlock_systemError--;if (!$status_nextScreen) {bs_logIt($err, 'ERROR', __LINE__, $_func_, __FILE__);XR_echo($err, __LINE__, $_func_, __FILE__);}
$status = TRUE;} while(FALSE);if (!$status) { Bs_Error::setError($err, 'ERROR', __LINE__, $_func_, __FILE__);return FALSE;}
if (!empty($this->alertMsg))  $GLOBALS['Bs_Error']->setAlert(htmlspecialchars($this->alertMsg));if (!$ret = $nextScreen->render()) {return FALSE;}
return $ret;}
}
$_func_ = 'main';$htmlOut = '';$status = FALSE;do {$ep =& new EntryPoint();if (FALSE === $ep->init()) {}
if (!$htmlOut = $ep->go()) break; $status = TRUE;} while(FALSE);if (!$status) {$err = bs_getErrors();bs_logIt($err, 'ERROR', __LINE__, $_func_, __FILE__);XR_dump($err, __LINE__, $_func_, __FILE__);echo "FATAL ERROR";} else {echo $htmlOut;}
?>
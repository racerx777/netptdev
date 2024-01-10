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
if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($GLOBALS['APP']['path']['core'] . 'html/Bs_HtmlUtil.class.php');require_once($GLOBALS['APP']['path']['core'] . 'net/Bs_Url.class.php');define('BS_TODO', 'bs_todo');define('BS_TODO_EXIT_SCREEN', 'exitScreen');define('BS_TODO_EXIT_ACTION', 'exitAction');define('BS_TODO_NEXT_SCREEN', 'nextScreen');define('BS_TODO_NEXT_ACTION', 'nextAction');define('BS_TODO_DATAHASH',    'dataHash');$bsToDoDefault = array (
BS_TODO_EXIT_SCREEN => '',
BS_TODO_EXIT_ACTION => NULL, BS_TODO_NEXT_SCREEN => '',
BS_TODO_NEXT_ACTION => NULL, BS_TODO_DATAHASH => NULL,
);function &bs_requestToDo() {GLOBAL $bsToDoDefault;if (empty($GLOBALS[BS_TODO])) {$GLOBALS[BS_TODO] = empty($_REQUEST[BS_TODO]) ? $bsToDoDefault : array_merge($bsToDoDefault, $_REQUEST[BS_TODO]);$todo =& $GLOBALS[BS_TODO];$tmpArray = array(BS_TODO_EXIT_ACTION, BS_TODO_NEXT_ACTION);foreach ($tmpArray as $action) {if (!isSet($todo[$action])) {$todo[$action] = NULL;} elseif (!is_array($todo[$action])) {$todo[$action] = bs_requestToDo_convertHelper($todo[$action]);} else {foreach ($todo[$action] as $key => $val) $todo[$action][$key] = bs_requestToDo_convertHelper($val);}
}
}
return $GLOBALS[BS_TODO];}
function bs_requestToDo_convertHelper($val) {if (('true'===$val) OR ('TRUE'===$val)) {return TRUE;} elseif (('false'===$val) OR ('FALSE'===$val)) {return FALSE;} elseif (('null'===$val) OR ('NULL'===$val)) {return NULL;} elseif (is_numeric($val)) {return (int)$val;} else {return $val;}
}
function bs_makeHiddenToDoFields($exitScreen='', $exitActions=array(), $nextScreen='', $nextActions=array(), $dataHash='') {$htmlUtil =& new Bs_HtmlUtil();$todoInfo = "\n";$todoInfo .=  '<input type="hidden" name="' . BS_TODO . '['.BS_TODO_EXIT_SCREEN.']' . '" value="'.$exitScreen."\">\n";$name = BS_TODO . '['.BS_TODO_EXIT_ACTION.']';$todoInfo .=  $htmlUtil->arrayToHiddenFormFields(array($name => $exitActions));$todoInfo .=  '<input type="hidden" name="' . BS_TODO . '['.BS_TODO_NEXT_SCREEN.']' . '" value="'.$nextScreen."\">\n";$name = BS_TODO . '['.BS_TODO_NEXT_ACTION.']';$todoInfo .=  $htmlUtil->arrayToHiddenFormFields(array($name => $nextActions));$name = BS_TODO . '['.BS_TODO_DATAHASH.']';$todoInfo .=  $htmlUtil->arrayToHiddenFormFields(array($name => $dataHash));return $todoInfo;}
function bs_makeTodoQueryString($exitScreen='', $exitActions=array(), $nextScreen='', $nextActions=array(), $dataHash='') {$hash = array(
BS_TODO . '[' . BS_TODO_EXIT_SCREEN . ']' => $exitScreen, 
BS_TODO . '[' . BS_TODO_EXIT_ACTION . ']' => $exitActions, 
BS_TODO . '[' . BS_TODO_NEXT_SCREEN . ']' => $nextScreen, 
BS_TODO . '[' . BS_TODO_NEXT_ACTION . ']' => $nextActions, 
BS_TODO . '[' . BS_TODO_DATAHASH    . ']' => $dataHash, 
);return $GLOBALS['Bs_Url']->hashArrayToQueryString($hash);}
function bs_remakeTodoQueryStringFromToDo($hash) {return bs_makeTodoQueryString($hash[BS_TODO_EXIT_SCREEN],$hash[BS_TODO_EXIT_ACTION],$hash[BS_TODO_NEXT_SCREEN],$hash[BS_TODO_NEXT_ACTION],$hash[BS_TODO_DATAHASH]);}
function bs_getEmtyToDoHash() {return $bsToDoDefault;}
?>
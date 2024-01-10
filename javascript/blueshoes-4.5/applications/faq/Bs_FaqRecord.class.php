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
class Bs_FaqRecord extends Bs_Object {var $ID             = '';var $questionGroup  = '';var $question       = '';var $answer         = '';var $fromEmail      = '';var $fromName       = '';var $addDatetime    = '';var $doShow        = FALSE;var $fia_hints;function Bs_FaqRecord() {}
function bs_sop_getHints($sopAgent) {static $perisit_hints = array(
'table'   => array('name'=>'faq', 'create'=>TRUE),
'debug'   => array('checkHintSyntax'=>TRUE, 'checkClassVars'=>FALSE),
'primary' => array('ID' => array('name'=>'ID', 'type'=>'auto_increment')),
'fields'  => array(
'question'         => array('name'=>'question',       'metaType'=>'text',    'size'=>'1000'),
'answer'           => array('name'=>'answer',         'metaType'=>'text',    'size'=>'10000'),
'fromEmail'        => array('name'=>'fromEmail',      'metaType'=>'string',  'size'=>'255'),
'fromName'         => array('name'=>'fromName',       'metaType'=>'string',  'size'=>'255'),
'addDatetime'      => array('name'=>'addDatetime',    'metaType'=>'string',  'size'=>'255'),
'doShow'           => array('name'=>'doShow',         'metaType'=>'boolean'),
),
);return $perisit_hints;}
function bs_fia_loadHints() {$sop_hints = $this->bs_sop_getHints(FALSE);$sop_hints = $sop_hints['fields'];  $accessOmit = array(
'user'      => 'omit', 
'admin'     => 'normal', 
);$this->fia_hints = array (
'props' => array(
'internalName'     => 'formFaq', 
'name'             => 'formFaq', 
'mode'             => 'add', 
'language'         => 'de', 
'useAccessKeys'    => TRUE,
'useTemplate'      => FALSE,
'jumpToFirstError' => TRUE,
'advancedStyles'   => array(
'captionMust'      => '', 
'captionMustOkay'  => '', 
'captionMustWrong' => 'formError', 
'captionMay'       => '', 
'captionMayOkay'   => '', 
'captionMayWrong'  => 'formError', 
'fieldMust'        => '', 
'fieldMustOkay'    => '', 
'fieldMustWrong'   => '', 
'fieldMay'         => '', 
'fieldMayOkay'     => '', 
'fieldMayWrong'    => '', 
),
),
'groups' => array(
'grpQuestion' => array(
'caption'   =>'Frage', 
), 
'grpFrom' => array(
'caption'   =>'Person', 
), 
'grpManagement' => array(
'caption'   =>'Management', 
), 
),
'fields' => array(
'ID' => array(
'name'            => 'ID',
'group'           => 'grpQuestion', 
'fieldType'       => 'Bs_FormFieldHidden',
'must'            => FALSE, 
'editability'     => 'always',
),
'question' => array(
'name'            => 'question',
'group'           => 'grpQuestion', 
'caption'         => array('en'=>'Question', 'de'=>'Frage'), 
'fieldType'       => 'Bs_FormFieldTextarea',
'must'            => TRUE, 
'editability'     => 'always',
),
'answer' => array(
'name'            => 'answer',
'group'           => 'grpQuestion', 
'accessRights'    => $accessOmit, 
'caption'         => array('en'=>'Answer', 'de'=>'Antwort'), 
'fieldType'       => 'Bs_FormFieldTextarea',
'must'            => TRUE, 
'editability'     => 'always',
),
'fromEmail' => array(
'name'            => 'fromEmail',
'group'           => 'grpFrom', 
'caption'         => array('en'=>'Email', 'de'=>'E-Mail'), 
'fieldType'       => 'Bs_FormFieldText',
'must'            => FALSE, 
'editability'     => 'always',
),
'fromName' => array(
'name'            => 'fromName',
'group'           => 'grpFrom', 
'caption'         => array('en'=>'Name', 'de'=>'Name'), 
'fieldType'       => 'Bs_FormFieldText',
'must'            => FALSE, 
'editability'     => 'always',
),
'addDatetime' => array(
'name'            => 'addDatetime',
'group'           => 'grpFrom', 
'accessRights'    => $accessOmit, 
'caption'         => array('en'=>'Date', 'de'=>'Datum'), 
'fieldType'       => 'Bs_FormFieldText',
'must'            => FALSE, 
'editability'     => 'always',
),
'doShow' => array(
'name'            => 'doShow',
'group'           => 'grpManagement', 
'accessRights'    => $accessOmit, 
'caption'         => array('en'=>'Show', 'de'=>'Anzeigen'), 
'text'            => array('en'=>'Show on Website', 'de'=>'Auf Website anzeigen'), 
'fieldType'       => 'Bs_FormFieldCheckbox',
'must'            => FALSE, 
'editability'     => 'always',
),
'send' => array(
'name'            => 'send',
'caption'         => array('en'=>'Send', 'de'=>'Abschicken'), 
'group'           => '', 
'fieldType'       => 'Bs_FormFieldSubmit',
'editability'     => 'always',
),
),
);}
function bs_fia_getHints($fiaAgent) {if (!isSet($this->fia_hints)) $this->bs_fia_loadHints();return $this->fia_hints;}
}
?>
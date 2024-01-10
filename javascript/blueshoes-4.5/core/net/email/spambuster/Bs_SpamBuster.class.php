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
define('BS_SPAMBUSTER_VERSION',      '4.5.$Revision: 1.2 $');define('BS_SPAMBUSTER_TXT_BLACKLIST',       1);define('BS_SPAMBUSTER_TXT_FREEMAIL',        2);define('BS_SPAMBUSTER_TXT_SPAM',            3);define('BS_SPAMBUSTER_TXT_HTML',            4);define('BS_SPAMBUSTER_TXT_ATTACHEMENT',     5);require_once($APP['path']['core'] . 'net/email/Bs_EmailParser.class.php');require_once($APP['path']['core'] . 'net/email/Bs_EmailValidator.class.php');require_once($APP['path']['core'] . 'net/email/Bs_Smtp.class.php');require_once($APP['path']['core'] . 'file/Bs_Dir.class.php');class Bs_SpamBuster extends Bs_Object {var $allowFreemail = FALSE;function Bs_SpamBuster() {parent::Bs_Object(); }
function checkDirectory($fullPath) {$fileList = $this->_getFileList($fullPath);if (isEx($fileList)) {$fileList->stackDump('die');}
$ep =& new Bs_EmailParser();$ev =& new Bs_EmailValidator();while (list(,$fileFullPath) = each($fileList)) {$eArray = $ep->parseFile($fileFullPath);if (!$this->allowFreemail) {if ($ev->usesFreemailProvider($eArray['from'])) {$this->_bust($eArray, $fileFullPath, BS_SPAMBUSTER_TXT_FREEMAIL);} elseif ($eArray['from'] != $eArray['return-path']) {if (!empty($eArray['return-path'])) {if ($ev->usesFreemailProvider($eArray['return-path'])) {$this->_bust($eArray, $fileFullPath, BS_SPAMBUSTER_TXT_FREEMAIL);}
}
}
}
}
}
function _bust($emailHash, $fileFullPath, $bustReason) {$mail =& new Bs_Smtp();$mail->host = 'smtp.zh.cybernet.ch';$mail->addFrom('spambuster@asteroid.ch', 'spambuster@asteroid.ch');$mail->addTo('andrej at blueshoes dot org', 'blah');$mail->subject = 'RE: ' . $emailHash['subject'] . ' [!FAILED!]';$txtArray = $this->_createReplyText($bustReason);$msg = join('', $txtArray);$msg .= "From: {$emailHash['from']}\r\n";if (isSet($emailHash['x-mdrcpt-to'])) { $msg .= "To:   {$emailHash['x-mdrcpt-to']}\r\n";} else { $msg .= "To:   {$emailHash['to']}\r\n";}
$msg .= "Date: {$emailHash['date']}\r\n";$mail->message = $msg;$status = $mail->send(); if (isEx($status)) {} else {$status = $this->_move($fileFullPath);}
}
function _createReplyText($bustReason) {switch ($bustReason) {case 1: $reasonEn = "Your email address is listed in our blacklist.";$reasonDe = "";$solveEn  = "Nothing. It looks like we don't want your mail.";$solveDe  = "";break;case 2: $reasonEn = "You sent the email from a freemail provider (like hotmail.com).";$reasonDe = "";$solveEn  = "Please use your personal email address (from your company, isp or whatever).";$solveDe  = "";break;case 3: $reasonEn = "Your mail message has been considered spam by the system.";$reasonDe = "";$solveEn  = "Don't send us spam or spamlike messages. Check your mail text.";$solveDe  = "";break;default:
$reasonEn = "Undefined reason.";$reasonDe = "";$solveEn  = "Don't know. Try sending for another address, don't send spam.";$solveDe  = "";}
$ret = array();$ret['en'] = "
----------- english -----------
!Failure notice!
Hello. Your original mail was rejected and deleted immediatly. 
The sender has not even seen it.
Do *not* reply to this email because all email to this address gets 
refused by the system.
Why has your email been blocked?
{$reasonEn}
What can you do about it?
{$solveEn}
";$ret['de'] = "
----------- deutsch -----------
!Fehler-Mitteilung!
Hallo. Ihre Nachricht wurde abgelehnt und unverzüglich gelöscht.
Der Empfänger hat sie nicht gesehen.
Antworten Sie *nicht* auf diese Nachricht. Alle Emails an diese Adresse werden 
vom System geblockt.
Wieso wurde die Zustellung Ihrer Nachricht verweigert?
{$reasonDe}
Was können Sie dagegen unternehmen?
{$solveDn}
";return $ret;}
function _move($fileFullPath) {$fs =& new Bs_FileSystem();$path = $fs->getPathStem($fileFullPath);@mkdir($path . 'busted'); return rename($fileFullPath, $path . 'busted/' . $fs->getFileName($fileFullPath));}
function _delete() {}
function _getFileList($fullPath) {$d =& new Bs_Dir($fullPath);$params = array(
'regEx' => '\.msg', 
'depth' => 0, 
);return $d->getFileList($params);}
}
?>
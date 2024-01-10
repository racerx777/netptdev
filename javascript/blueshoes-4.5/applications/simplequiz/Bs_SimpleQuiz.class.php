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
require_once($_SERVER["DOCUMENT_ROOT"]    . "../global.conf.php");require_once($APP['path']['core'] . 'text/Bs_TextUtil.class.php');require_once($APP['path']['core'] . 'html/form/Bs_Form.class.php');require_once($APP['path']['core'] . 'file/Bs_Dir.class.php');require_once($APP['path']['core'] . 'util/Bs_Array.class.php');class Bs_SimpleQuiz extends Bs_Object {var $Bs_TextUtil;var $_bsDb;var $_tblNameRecord;var $_tblNameQuestion;var $_logToDb;var $language = 'en';var $giveNegativePoints = FALSE;var $dontKnow = FALSE;var $percentForComplete = 50;var $questions;var $resultStyle = array(
'en' => '<li>Question: __QUESTION__<br>Possible answers: __OPTIONS_STRING__<br>Your answer: __ANSWER__<br><span class="quizResult__IS_OK__">Right is: __RESULT__</span><br><br></li>', 
'de' => '<li>Frage: __QUESTION__<br>Mögliche Antworten: __OPTIONS_STRING__<br>Ihre Antwort: __ANSWER__<br><span class="quizResult__IS_OK__">Korrekt ist: __RESULT__</span><br><br></li>', 
);var $text = array(
'en' => array(
'numQuestions'            => 'Number of questions: ', 
'correctAnswers'          => 'Correct answers: ', 
'userStatsForQuiz'        => 'User Statistics for the Quiz', 
'percentOfCorrectAnswers' => 'Percent of correct answers', 
'numberOfUsers'           => 'Number of users', 
'questionStatsForQuiz'    => 'Question Statistics for the Quiz', 
'questionNumber'          => 'Question number', 
'submit'                  => 'Submit', 
), 
'de' => array(
'numQuestions'            => 'Anzahl Fragen: ', 
'correctAnswers'          => 'Korrekte Antworten: ', 
'userStatsForQuiz'        => 'Benutzerstatistik fürs Quiz', 
'percentOfCorrectAnswers' => 'Prozent an korrekten Antworten', 
'numberOfUsers'           => 'Anzahl Benutzer', 
'questionStatsForQuiz'    => 'Quiz-Statistik pro Frage', 
'questionNumber'          => 'Frage-Nummer', 
'submit'                  => 'Abschicken', 
), 
);var $form;function Bs_SimpleQuiz() {parent::Bs_Object(); $this->Bs_TextUtil =& $GLOBALS['Bs_TextUtil'];$this->Bs_Array    =& $GLOBALS['Bs_Array'];}
function setDbInfo(&$bsDb, $tblNameRecord, $tblNameQuestion, $logToDb=TRUE) {$this->_bsDb            = &$bsDb;$this->_tblNameRecord   = $tblNameRecord;$this->_tblNameQuestion = $tblNameQuestion;$this->_logToDb         = $logToDb;}
function doItYourself() {$form = &$this->buildForm();$this->form = &$form;$status = $form->doItYourself();if (is_array($status)) return $status;$valueInternal = $form->getValuesArray(TRUE, 'valueInternal');$formInfo      = $form->getInfo();return $this->validateUserInput($valueInternal, $formInfo);}
function validateUserInput($userInput, $formInfo) {$ret         = '';$points      = 0;$rightWrongArray = array();$numRight    = 0;$numWrong    = 0;$numComplete = 0;$ret .= '<ol>';reset($this->questions);while (list($k) = each($this->questions)) {$q = &$this->questions[$k];$strQuestion = $this->Bs_TextUtil->getLanguageDependentValue($q['question'], $this->language);if (isSet($userInput['bsQuiz' . $k]) && !(is_null($userInput['bsQuiz' . $k])) && !(is_array($userInput['bsQuiz' . $k]) && empty($userInput['bsQuiz' . $k]))) {$strAnswer   = (string)$q['options'][$userInput['bsQuiz' . $k]];$numComplete++;} else {$strAnswer   = '';}
$strOptions  = join(', ', $this->Bs_TextUtil->getLanguageDependentValue($q['options'], $this->language));$strResult   = (string)$q['options'][$q['answers']];if ($strResult === $strAnswer) {$points += $q['pointsAllCorrect'];$rightWrongArray[$k] = TRUE;$numRight++;} else {$rightWrongArray[$k] = FALSE;$numWrong++;}
$t = $this->Bs_TextUtil->getLanguageDependentValue($this->resultStyle, $this->language);$t = str_replace('__QUESTION__', $strQuestion, $t);$t = str_replace('__ANSWER__',   $strAnswer, $t);$t = str_replace('__OPTIONS_STRING__',   $strOptions, $t);$t = str_replace('__RESULT__',   $strResult, $t);$t = str_replace('__IS_OK__',    (int)$rightWrongArray[$k], $t);$ret .= $t;}
$ret .= '</ol>';$numTotal        = $numRight + $numWrong;$percentRight    = (int)round(($numRight    * 100 / $numTotal));$percentComplete = (int)round(($numComplete * 100 / $numTotal));$isComplete   = ($percentComplete >= $this->percentForComplete);$secsNeeded   = $formInfo['usedTime'];$text = $this->Bs_TextUtil->getLanguageDependentValue($this->text, $this->language);$retHead  = $text['numQuestions'] . sizeOf($this->questions) . '<br>';$retHead .= $text['correctAnswers'] . $points . ' (' . $percentRight . '%)<br><br>';$this->logQuizInput($percentRight, $percentComplete, $numWrong, $numTotal, $rightWrongArray, $secsNeeded, $isComplete);return $retHead . $ret;}
function logQuizInput($percentRight, $percentFilled, $numWrong, $numQuestions, $rightWrongArray, $secsNeeded, $isComplete, $secondCall=FALSE) {if (!$this->_logToDb) return;$ip   = $_SERVER['REMOTE_ADDR'];$host = isSet($_SERVER['REMOTE_HOST']) ? substr($_SERVER['REMOTE_HOST'], 0, 120) : '';$sql = "INSERT INTO {$this->_tblNameRecord} 
(ip, host, numWrong, numQuestions, percentRight, percentFilled, secsNeeded, isComplete) 
VALUES ('{$ip}', '{$host}', {$numWrong}, {$numQuestions}, {$percentRight}, {$percentFilled}, {$secsNeeded}, " . (int)$isComplete . ")";$recordID = $this->_bsDb->idWrite($sql);if (isEx($recordID)) {if (!$secondCall) {$this->createLogTables();$this->logQuizInput($percentRight, $percentFilled, $numWrong, $numQuestions, $rightWrongArray, $secsNeeded, $isComplete, TRUE);return;}
return;}
while (list($k) = each($rightWrongArray)) {$qNumber = $k + 1;$sql = "INSERT INTO {$this->_tblNameQuestion} (recordID, questionID, isOk, isComplete) VALUES({$recordID}, {$qNumber}, " . (int)$rightWrongArray[$k] . ", " . (int)$isComplete . ")";$this->_bsDb->write($sql);}
}
function createLogTables() {if (!is_object($this->_bsDb)) return;$sql = "
CREATE TABLE IF NOT EXISTS {$this->_tblNameRecord} (
ID             INT          NOT NULL DEFAULT 0 AUTO_INCREMENT, 
ip             VARCHAR(15)  NOT NULL DEFAULT '', 
host           VARCHAR(120) NOT NULL DEFAULT '', 
numWrong       TINYINT      NOT NULL DEFAULT 0, 
numQuestions   TINYINT      NOT NULL DEFAULT 0, 
percentRight   TINYINT      NOT NULL DEFAULT 0, 
percentFilled  TINYINT      NOT NULL DEFAULT 0, 
secsNeeded     MEDIUMINT    NOT NULL DEFAULT 0, 
isComplete     TINYINT      NOT NULL DEFAULT 0, 
eventTimestamp TIMESTAMP, 
PRIMARY KEY ID (ID)
)
";$this->_bsDb->write($sql);$sql = "
CREATE TABLE IF NOT EXISTS {$this->_tblNameQuestion} (
ID             INT NOT NULL DEFAULT 0 AUTO_INCREMENT, 
recordID       INT NOT NULL DEFAULT 0, 
questionId     INT NOT NULL DEFAULT 0, 
isOk           TINYINT NOT NULL DEFAULT 0, 
isComplete     TINYINT NOT NULL DEFAULT 0, 
PRIMARY KEY ID (ID), 
key recordID   (recordID), 
key questionID (questionID)
)
";$this->_bsDb->write($sql);}
function &buildForm() {$text = $this->Bs_TextUtil->getLanguageDependentValue($this->text, $this->language);$form =& new Bs_Form();$form->mode                 = 'add';$form->language             = $this->language;$form->mustFieldsVisualMode = 'none';$container =& new Bs_FormContainer();$container->caption = array('en'=>'Questions', 'de'=>'Fragen');$form->elementContainer->addElement($container);$numQuestions = sizeOf($this->questions);reset($this->questions);while (list($k) = each($this->questions)) {$q = &$this->questions[$k];if ($q['multiple']) {$html =& new Bs_FormHtml();$html->name  = 'bsQuizQ' . $k;$html->html  = $this->Bs_TextUtil->getLanguageDependentValue($q['question'], $this->language);$container->addElement($html);$fieldType = 'Bs_FormFieldCheckbox';foreach ($q['options'] as $key => $value) {$value = $this->Bs_TextUtil->getLanguageDependentValue($value, $this->language);$fieldProps = array();$fieldProps['name']      = 'bsQuiz' . $k . '_' . $key;$fieldProps['fieldType'] = $fieldType;$fieldProps['must']      = FALSE;$fieldProps['hideCaption'] = 2;$fieldProps['caption']   = $value;$fieldProps['text']      = $value;$field = &bs_fabricateFormField($fieldProps);$container->addElement($field);}
if ($k < ($numQuestions -1)) {$line =& new Bs_FormLine();$line->name    = 'bsQuizLine' . $k . '_' . $key;$line->size    = 1;$line->noshade = TRUE;$container->addElement($line);}
} else {$fieldType = 'Bs_FormFieldRadio';$fieldProps = array();$fieldProps['name']          = 'bsQuiz' . $k;$fieldProps['fieldType']     = $fieldType;$fieldProps['caption']       = $this->Bs_TextUtil->getLanguageDependentValue($q['question'], $this->language);$fieldProps['must']          = (!$q['multiple'] && $this->dontKnow);$fieldProps['optionsHard']   = $q['options'];$fieldProps['elementLayout'] = '<tr><td valign="top" align="left" colspan="2">__CAPTION_FOR_FORM_OUTPUT__</td></tr><tr><td valign="top" colspan="2">__ELEMENT__</td></tr>';if ($k < ($numQuestions -1)) {$fieldProps['elementLayout'] .= '<tr><td colspan="2"><hr size="1" noshade></td></tr>';}
$field = &bs_fabricateFormField($fieldProps);$container->addElement($field);}
}
$btn =& new Bs_FormFieldSubmit();$btn->name = 'submit';$btn->caption = $text['submit']; $form->elementContainer->addElement($btn);return $form;}
function generateChartUsers($path, $file, $steps=10) {$text = $this->Bs_TextUtil->getLanguageDependentValue($this->text, $this->language);do {$sql = "SELECT percentRight FROM {$this->_tblNameRecord} WHERE isComplete=1 ORDER BY percentRight DESC";$chartData = $this->_bsDb->getCol($sql); if (!is_array($chartData) || empty($chartData)) break;$X_split = $steps;$X_steps = round(100 / $X_split);$results10arr = array_fill(0, $X_split, 0);foreach($chartData as $resVal) {$resVal = (int)round($resVal/$X_steps) -1;if ($resVal < 0) $resVal = 0;$results10arr[$resVal]++;}
$labels = array();for ($l=0; $l<$X_split; $l++) {$a=$l*$X_steps;$b=$a+$X_steps;$labels[] = "{$a}-{$b}%";}
$datay = $results10arr;$graph = new Graph(400,200, 'auto');$graph->img->SetMargin(40,40,40,80);$graph->img->SetAntiAliasing();$graph->SetScale("textlin");$graph->xaxis->SetTickLabels($labels);$graph->xaxis->SetLabelAngle(90);$graph->SetShadow();$graph->title->Set($text['userStatsForQuiz']);$graph->title->SetFont(FF_FONT1,FS_BOLD);$graph->xaxis->title->Set("\n\n" . $text['percentOfCorrectAnswers']);$graph->yaxis->title->Set($text['numberOfUsers']);$p1 = new LinePlot($datay);$p1->mark->SetType(MARK_FILLEDCIRCLE);$p1->mark->SetFillColor("red");$p1->mark->SetWidth(4);$p1->SetColor("blue");$p1->SetCenter();$graph->Add($p1);if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $path)) {$dir =& new Bs_Dir();$dir->mkpath($_SERVER['DOCUMENT_ROOT'] . $path);}
$fileFullPath = $_SERVER['DOCUMENT_ROOT'] . $path . $file . '.' . $this->language . '.png';$graph->Stroke($fileFullPath);if (file_exists($fileFullPath)) {return TRUE;}
} while (FALSE);return FALSE;}
function generateChartQuestions($path, $file) {$text = $this->Bs_TextUtil->getLanguageDependentValue($this->text, $this->language);do {$sql = "SELECT questionId, COUNT(*) AS numRecsRight FROM {$this->_tblNameQuestion} WHERE isOk=1 AND isComplete=1 GROUP BY questionId";$qRights = $this->_bsDb->getAssoc($sql, TRUE, TRUE);$sql = "SELECT questionId, COUNT(*) AS numRecsWrong FROM {$this->_tblNameQuestion} WHERE isOk=0 AND isComplete=1 GROUP BY questionId";$qWrongs = $this->_bsDb->getAssoc($sql, TRUE, TRUE);$qResults = $this->Bs_Array->array_merge_recursive($qRights, $qWrongs);$dataY = array();for ($i=0; $i<sizeOf($this->questions); $i++) {$qId = $i + 1;$numWrong     = isSet($qResults[$qId]['numRecsWrong']) ? $qResults[$qId]['numRecsWrong'] : 0;$numRight     = isSet($qResults[$qId]['numRecsRight']) ? $qResults[$qId]['numRecsRight'] : 0;$total        = $numRight + $numWrong;if ($total > 0) {$percentRight = (int)($numRight * 100 / $total);$dataY[]      = $percentRight;}
}
$graph = new Graph(400, 200, 'auto');$graph->img->SetMargin(40,40,40,40);$graph->img->SetAntiAliasing();$graph->SetScale("textlin");$graph->SetShadow();$graph->title->Set($text['questionStatsForQuiz']);$graph->title->SetFont(FF_FONT1,FS_BOLD);$graph->xaxis->title->Set($text['questionNumber']);$graph->yaxis->title->Set($text['percentOfCorrectAnswers']);$p1 = new LinePlot($dataY);$p1->mark->SetType(MARK_FILLEDCIRCLE);$p1->mark->SetFillColor("red");$p1->mark->SetWidth(4);$p1->SetColor("blue");$p1->SetCenter();$graph->Add($p1);if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $path)) {$dir =& new Bs_Dir();$dir->mkpath($_SERVER['DOCUMENT_ROOT'] . $path);}
$fileFullPath = $_SERVER['DOCUMENT_ROOT'] . $path . $file . '.' . $this->language . '.png';$graph->Stroke($fileFullPath);if (file_exists($fileFullPath)) {return TRUE;}
} while (FALSE);return FALSE;}
function loadJpGraph($jpGraphDir=NULL) {if (is_null($jpGraphDir)) $jpGraphDir = $GLOBALS['APP']['path']['bsRoot'] . '../jpgraph/src/';include ($jpGraphDir . 'jpgraph.php');include ($jpGraphDir . 'jpgraph_line.php');include ($jpGraphDir . 'jpgraph_error.php');}
}
?>
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
if (isSet($APP)) {require_once($APP['path']['core']    . 'html/form/Bs_Form.class.php');require_once($APP['path']['core']    . 'html/form/specialfields/Bs_FormFieldEmail.class.php');       require_once($APP['path']['core']    . 'util/Bs_Array.class.php');}
$GLOBALS['numErrors'] = 0;$empty = "";$null  = NULL;$bool  = FALSE;function checkCaseInt(&$actual, &$expected) {if ($actual === 'true') {$actual = TRUE;} elseif ($actual === 'false') {$actual = FALSE;} else {$actualStr = $actual;}
return (int)($actual === $expected);}
function checkCase(&$actual, &$expected, &$comments) {if ($actual === 'true') {$actual = TRUE;$actualStr = 'TRUE';} elseif ($actual === 'false') {$actual = FALSE;$actualStr = 'FALSE';} else {$actualStr = $actual;}
if ($expected === TRUE) {$expectedStr = 'TRUE';} elseif ($expected === FALSE) {$expectedStr = 'FALSE';} else {$expectedStr = $expected;}
$ret = '<br>';$ret .= "you said: {$actualStr}<br>
right is: {$expectedStr}<br>
result: ";if ($actual === $expected) {$ret .= "<font color=green><b>right</b></font><br>";} else {$GLOBALS['numErrors']++;$ret .= "<font color=red><b>wrong</b></font><br>";}
if (!empty($comments)) $ret .= '<strong>'. $comments . '</strong><br>';$ret .= '<br>';return $ret;}
function checkSaved(&$actual, &$expected, &$comments) {if ($expected === TRUE) {$expectedStr = 'TRUE';} elseif ($expected === FALSE) {$expectedStr = 'FALSE';} else {$expectedStr = $expected;}
$ret = '<br>';if ($actual) { $ret .= "you said: {$expectedStr}<br>";} else { $ret .= "you said: &lt;unknown&gt;<br>";}
$ret .= "right is: {$expectedStr}<br>";$ret .= "result: ";if ($actual) { $ret .= "<font color=green><b>right</b></font><br>";} else { $GLOBALS['numErrors']++;$ret .= "<font color=red><b>wrong</b></font><br>";}
if (!empty($comments)) $ret .= '<strong>'. $comments . '</strong><br>';$ret .= '<br>';return $ret;}
ob_start();?>
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post"> 
<input type=hidden name="examFormStep" value="2">
<input type=hidden name="startTimestamp" value="<?php echo time();?>">
The following vars are set:<br>
<pre><code>
var $empty       = '';var $null        = NULL;var $bool        = FALSE;var $notSet;var $array       = array();</code></pre>
<br>
<?php
$hasData = FALSE;if (@$_POST['examFormStep'] == '2') {$_results = $_POST;$hasData = TRUE;} elseif (isSet($_GET['id'])) {$sql = "SELECT questionId, isOk from SyntaxExamResultQuestions WHERE SyntaxExamResultsID=" . $_GET['id'];$data = $bsDb->getAssoc($sql);if (isEx($data) || is_null($data) || empty($data)) {echo '<h1>failed loading record.</h1>';} else {$_results = $data;$hasData = TRUE;}
}
if (isSet($APP)) {include $APP['path']['toolbox'] . 'PhpSyntax/phpSyntax.questions.php';} else {include './phpSyntax.questions.php';}
$resultPerQuestion = array();$questionNumberCounter = 1;while (list(,$myQ) = each($q)) {if (isSet($myQ['group'])) {echo '<br><h2>' . $myQ['group'] . '</h2><hr>';}
echo "<table border=0 cellpadding=2 cellspacing=0>";echo "<tr><td valign=top>{$questionNumberCounter}.&nbsp;</td><td valign=top>";$qStr = str_replace('=>', '=&gt;', $myQ['question']);echo $qStr;if ($hasData) {if (@$_POST['examFormStep'] == '2') {echo checkCase($myQ['actual'], $myQ['expected'], $myQ['comments']);$resultPerQuestion[$myQ['qId']] = checkCaseInt($myQ['actual'], $myQ['expected']);} else {echo checkSaved($myQ['actual'], $myQ['expected'], $myQ['comments']);$resultPerQuestion[$myQ['qId']] = (int)$myQ['actual'];}
}
echo "</td></tr></table><br>";$questionNumberCounter++;}
$examOut = ob_get_contents();ob_end_clean();if ($hasData) {$endTimestamp = time();$numQuestions = sizeOf($q);if (@$_POST['examFormStep'] == '2') {$secsNeeded   = ($endTimestamp - $_POST['startTimestamp']);} else {$sql = "SELECT secsNeeded from SyntaxExamResults WHERE ID=" . $_GET['id'];$secsNeeded = $bsDb->getOne($sql);}
$percentRight = ($numQuestions - $GLOBALS['numErrors']) / $numQuestions * 100; echo "<h2>Total errors: {$GLOBALS['numErrors']}/" . $numQuestions . "</h2>";echo "You needed " . $secsNeeded . " seconds.<br><br>";if ($bsDb) {do {if (@$_POST['examFormStep'] == '2') {do {$sql = "SELECT ID FROM SyntaxExamResults WHERE ip='{$_SERVER['REMOTE_ADDR']}' AND isComplete=1";$status = $bsDb->countRead($sql);if (isEx($status)) {break; }
if (($status == 0) || FALSE) { if (empty($_results['reference1']) || empty($_results['reference2']) || empty($_results['reference3']) || empty($_results['reference4'])) {echo "<font color='{$APP['color']['orange']}'><b>You did not fill in the whole exam, thus your results did not make it into the chart.</b></font><br><br>";$isComplete = 0;} else {$isComplete = 1;}
$sql = "INSERT INTO SyntaxExamResults (ip, numWrong, numQuestions, percentRight, secsNeeded, isComplete) 
VALUES ('{$_SERVER['REMOTE_ADDR']}', {$GLOBALS['numErrors']}, {$numQuestions}, 
{$percentRight}, {$secsNeeded}, {$isComplete})";$SyntaxExamResultsID = $bsDb->idWrite($sql);if ($SyntaxExamResultsID) {while (list($currentQuestionId) = each($resultPerQuestion)) {$sql = "INSERT INTO SyntaxExamResultQuestions (SyntaxExamResultsID, questionId, isOk, isComplete) 
VALUES ({$SyntaxExamResultsID}, '{$currentQuestionId}', {$resultPerQuestion[$currentQuestionId]}, {$isComplete})";$bsDb->write($sql);}
}
} else {}
} while (FALSE);}
$sql = "SELECT percentRight FROM SyntaxExamResults WHERE isComplete=1 ORDER BY percentRight DESC";$chartData = $bsDb->getCol($sql);if (!is_array($chartData) || empty($chartData)) break;$X_split = 20;$X_steps = round(100/$X_split);$results10arr = array_fill(0, $X_split, 0);foreach($chartData as $resVal) {$resVal = (int)round($resVal/$X_steps) -1;if ($resVal < 0) $resVal = 0;$results10arr[$resVal]++;}
$labels = array();for ($l=0; $l<$X_split; $l++) {$a=$l*$X_steps;$b=$a+$X_steps;$labels[] = "{$a}-{$b}%";}
include ($APP['path']['bsRoot'] . '../jpgraph/jpgraph.php');include ($APP['path']['bsRoot'] . '../jpgraph/jpgraph_line.php');include ($APP['path']['bsRoot'] . '../jpgraph/jpgraph_error.php');$datay = $results10arr;$graph = new Graph(700,200,"auto");$graph->img->SetMargin(40,40,40,80);$graph->img->SetAntiAliasing();$graph->SetScale("textlin");$graph->xaxis->SetTickLabels($labels);$graph->xaxis->SetLabelAngle(90);$graph->SetShadow();$graph->title->Set("User Statistics for the BlueShoes PHP Syntax Exam");$graph->title->SetFont(FF_FONT1,FS_BOLD);$graph->xaxis->title->Set("\n\nPercent of correct answers");$graph->yaxis->title->Set("Number of users");$p1 = new LinePlot($datay);$p1->mark->SetType(MARK_FILLEDCIRCLE);$p1->mark->SetFillColor("red");$p1->mark->SetWidth(4);$p1->SetColor("blue");$p1->SetCenter();$graph->Add($p1);$graph->Stroke($_SERVER['DOCUMENT_ROOT'] . '/img/syntaxExamCharts/results.png');echo "<img src='/img/syntaxExamCharts/results.png'  border=0>";echo '<br><br>';$sql = "SELECT questionId, COUNT(*) AS numRecsRight FROM SyntaxExamResultQuestions WHERE isOk=1 AND isComplete=1 GROUP BY questionId";$qRights = $bsDb->getAssoc($sql, TRUE, TRUE);$sql = "SELECT questionId, COUNT(*) AS numRecsWrong FROM SyntaxExamResultQuestions WHERE isOk=0 AND isComplete=1 GROUP BY questionId";$qWrongs = $bsDb->getAssoc($sql, TRUE, TRUE);$qResults = $GLOBALS['Bs_Array']->arrayMergeRecursive($qRights, $qWrongs);$dataY = array();reset($q);while (list($k) = each($q)) {$qId = $q[$k]['qId'];$numWrong     = isSet($qResults[$qId]['numRecsWrong']) ? $qResults[$qId]['numRecsWrong'] : 0;$numRight     = isSet($qResults[$qId]['numRecsRight']) ? $qResults[$qId]['numRecsRight'] : 0;$total        = $numRight + $numWrong;if ($total > 0) {$percentRight = (int)($numRight * 100 / $total);$dataY[]      = $percentRight;}
}
$graph = new Graph(700,200,"auto");$graph->img->SetMargin(40,40,40,40);$graph->img->SetAntiAliasing();$graph->SetScale("textlin");$graph->SetShadow();$graph->title->Set("Question Statistics for the BlueShoes PHP Syntax Exam");$graph->title->SetFont(FF_FONT1,FS_BOLD);$graph->xaxis->SetLabelAngle(90);$graph->xaxis->title->Set("Question number");$graph->yaxis->title->Set("Percent of correct answers");$p1 = new LinePlot($dataY);$p1->mark->SetType(MARK_FILLEDCIRCLE);$p1->mark->SetFillColor("red");$p1->mark->SetWidth(4);$p1->SetColor("blue");$p1->SetCenter();$graph->Add($p1);$graph->Stroke($_SERVER['DOCUMENT_ROOT'] . '/img/syntaxExamCharts/perQuestion.png');echo "<img src='/img/syntaxExamCharts/perQuestion.png'  border=0>";echo '<br><br>';} while (FALSE);echo 'Send your additional tests, updates and ideas to ';require_once($APP['path']['core'] . 'net/email/Bs_EmailUtil.class.php');echo $Bs_EmailUtil->hideEmailWithJsDocumentWrite('phpSyntaxExam@blueshoes.org');echo '<br><br>';echo 'You might want to check the <a href="http://www.blueshoes.org/en/developer/php_cheat_sheet/">PHP Cheat Sheet</a>. :-)<br><br>';echo '<hr><br>';echo 'Want to challenge a buddy?<br>';echo showChallengeForm();echo $examOut;}
} else {if (@$_POST['bs_form']['step'] == '2') {echo showChallengeForm();}
?>
Do you know what empty(false) evaluates to? or empty('0')? How about ('-1' == true)? 
Well see yourself :-)
<br><br>
This test was created based on php 4.0.5, and is updated for php 4.2.2. 
All this has been proofed on Win2k using PHP 4.2.2 using the PhpUnit test class "PhpSyntax_PhpUnit.class.php". 
It is included in the <a href="http://www.blueshoes.org/en/get/">download package</a>. 
Once you're done you might want to check the <a href="http://www.blueshoes.org/en/developer/php_cheat_sheet/">PHP Cheat Sheet</a>.
<!--author: andrej arn <andrej at blueshoes dot org>, based on ideas/code from Sam Blum sam at blueshoes dot org<br>
please send your additional tests, updates, ... but not your questions. <br><br>-->
<br><br><br>
<?php
echo $examOut;}
?>
<br><br>
good luck :)<br><br>
<input type=submit name=submit value="validate my input">
</form>
<?php
function showChallengeForm() {global $secsNeeded;global $numErrors;global $numQuestions;$ret  = '';$examChalForm =& new Bs_Form();$examChalForm->serializeType = 'php';$examChalForm->internalName         = 'syntaxExamChallengeForm';$examChalForm->name                 = 'syntaxExamChallengeForm';$examChalForm->saveToDb             = TRUE;$examChalForm->mustFieldsVisualMode = 'starRight';$examChalForm->useAccessKeys        = TRUE;$examChalForm->useJsFile            = TRUE;$examChalForm->jumpToFirstError     = TRUE;$examChalForm->useTemplate          = FALSE;$examChalForm->buttons              = FALSE;$examChalForm->language             = 'en';$examChalForm->mode                 = 'add';$examChalForm->onEnter              = 'tab';$examChalForm->advancedStyles       = array(
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
);unset($container);$container =& new Bs_FormContainer();$container->name         = "main";$container->caption      = array('en'=>'Challenge a friend');$container->orderId      = 1000;$container->mayToggle    = FALSE;$examChalForm->elementContainer->addElement($container);$element =& new Bs_FormFieldEmail();$element->name           = 'receiverEmail';$element->caption        = array('en'=>'Friends email address');$element->saveToDb       = TRUE;$element->editability    = 'always';$element->orderId        = 1000;$element->must           = TRUE;$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name           = 'senderName';$element->saveToDb         = TRUE;$element->caption        = array('en'=>'Your name');$element->editability    = 'always';$element->orderId        = 900;$element->must           = TRUE;$element->minLength      = 2;$container->addElement($element);unset($element);$element =& new Bs_FormFieldEmail();$element->name           = 'senderEmail';$element->caption        = array('en'=>'Your email address');$element->saveToDb       = TRUE;$element->editability    = 'always';$element->orderId        = 800;$element->must           = FALSE;$container->addElement($element);unset($element);$element =& new Bs_FormFieldTextarea();$element->name           = 'message';$element->saveToDb       = TRUE;$element->caption        = array('en'=>'Message');$element->valueDefault   = "Hello!\n\nI just finished the PHP Syntax Exam at http://www.blueshoes.org/en/developer/syntax_exam/ in {$secsNeeded} seconds and had {$GLOBALS['numErrors']} errors in {$numQuestions} questions.\n\nDo you think you can beat me? :-)\n\nGreetings";$element->editability    = 'always';$element->orderId        = 700;$element->must           = TRUE;$element->minLength      = 40;$element->enforce        = array('minLength'=>TRUE);$container->addElement($element);unset($element);$element =& new Bs_FormFieldSubmit();$element->name           = 'submit';$element->caption        = array('en'=>'Submit');$element->editability    = 'always';$element->orderId        = 600;$examChalForm->elementContainer->addElement($element);unset($element);$challengeRet = '';if (isSet($_POST['bs_form']['step']) && ($_POST['bs_form']['step'] == '2')) {$status = $examChalForm->setReceivedValues($_POST);if (isEx($status)) {$status->stackDump('die');}
$isOk = $examChalForm->validate();if ($isOk) {$status = $examChalForm->saveToDb();$formValues = $examChalForm->getValuesArray($shouldUseOnly=TRUE, 'valueInternal', TRUE, null, TRUE);if (!empty($formValues['senderName'][1])) {$chalMailSubject = $formValues['senderName'][1];} else {$chalMailSubject = $formValues['senderEmail'][1];}
$chalMailSubject .= ' is challenging you';if (!empty($formValues['senderEmail'][1])) {$headers = "From: " . $formValues['senderEmail'][1] . " <" . $formValues['senderEmail'][1] . ">\r\n";} else {$headers = "From: BlueShoes <phpSyntaxExam@blueshoes.org>\r\n";}
$mailStatus = @mail($formValues['receiverEmail'][1], $chalMailSubject, $formValues['message'][1], $headers);if ($mailStatus) {$challengeRet .= "<font color='green'><b>That mail has been sent. Let's see ... :-)</b></font>";} else {$challengeRet .= '<font color="red"><b>Transmission failed!</b></font>';}
} else {$challengeRet .= $examChalForm->getForm(TRUE);}
} else {$challengeRet .= $examChalForm->getForm();}
$ret .= $examChalForm->getIncludeOnce('string');$ret .= $examChalForm->getOnLoadCode();$ret .= $challengeRet;$ret .= '<br><hr><br><br>';return $ret;}
?>
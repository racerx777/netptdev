<?php
/**
* @package    applications_simplequiz
* @subpackage examples
*/

//$GLOBAL_CONF_TINY = TRUE;
require_once($_SERVER["DOCUMENT_ROOT"]    . '../global.conf.php');
require_once($APP['path']['applications'] . 'simplequiz/Bs_SimpleQuiz.class.php');

$sq =& new Bs_SimpleQuiz();
$sq->language = 'en';
//$sq->giveNegativePoints = TRUE;
//$sq->dontKnow           = FALSE;
$sq->questions = array(
  array(
    'question' => array(
			'en'=>'Who is the inventor of PHP?', 
			'de'=>'Wer ist der "Erfinder" von PHP?', 
		), 
    'options'  => array(
			'1'=>'Rasmus Leerdorf', 
			'2'=>'Andi Gutmans', 
			'3'=>'Zeev Suraski', 
		), 
    'answers'  => '1', 
    'multiple' => FALSE, 
    'pointsAllCorrect'  => 1, 
    //'pointsNegPerWrong' => 1, 
  ), 
  array(
    'question' => array(
			'en'=>'Where is Zend coming from?', 
			'de'=>'Woher kommt Zend?', 
		), 
    'options'  => array(
			'1'=>'USA', 
			'2'=>'Germany', 
			'3'=>'Israel', 
		), 
    'answers'  => '3', 
    'multiple' => TRUE, 
    'pointsAllCorrect'  => 1, 
    //'pointsNegPerWrong' => 1, 
  ), 
  array(
    'question' => array(
			'en'=>'Which are built-in PHP functions?', 
			'de'=>'Welches sind eingebaute PHP-Funktionen?', 
		), 
    'options'  => array(
			'1'=>'lcase()', 
			'2'=>'lowercase()', 
			'3'=>'tolowercase()', 
			'4'=>'strtolower()', 
			'5'=>'strpos()', 
			'6'=>'string_position()', 
			'7'=>'pos_of_string()', 
		), 
    'answers'  => array('4', '5'), 
    'multiple' => TRUE, 
    'pointsAllCorrect'  => 1, 
    //'pointsNegPerWrong' => 1, 
  ), 
);

$sq->resultStyle = '<li><b>Question: __QUESTION__</b><br>Possible answers: __OPTIONS_STRING__<br>Your answer: __ANSWER__<br><span class="quizResult__IS_OK__">Right is: __RESULT__</span><br><br></li>';

$sq->setDbInfo($GLOBALS['bsDb'], 'AsylQuizRecords', 'AsylQuizQuestions');
$sqOut = $sq->doItYourself();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>BlueShoes SimpleQuiz Example 1</title>
	<?php
	if (is_array($sqOut)) {
		echo $sq->form->includeOnceToHtml($sqOut['include']);
		echo $sq->form->onLoadCodeToHtml($sqOut['onLoad']);
	  echo @$sqOut['head'];
	}
	?>
	<style>
	body {
		font-family: arial;
		font-size: 11px;
	}
	td {
		font-size: 11px;
	}
	</style>
</head>

<body bgcolor="#FFFFFF">

<?php
if (is_array($sqOut)) {
  echo @$sqOut['errors'];
  echo $sqOut['form'];
} else {
  echo $sqOut;
	$sq->loadJpGraph($GLOBALS['APP']['path']['bsRoot'] . '../jpgraph-1.12.1/src/');
  $sq->generateChartUsers('img/quizCharts/', 'user', 10);
  echo "<img src='/img/quizCharts/user.{$sq->language}.png'  border=0>";
  echo '<br><br>';
  $sq->generateChartQuestions('img/quizCharts/', 'question');
  echo "<img src='/img/quizCharts/question.{$sq->language}.png'  border=0>";
  echo '<br><br>';
}
?>

</body>
</html>

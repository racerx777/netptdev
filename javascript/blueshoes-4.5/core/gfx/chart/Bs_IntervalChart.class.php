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
define('BS_INTERVALCHART_VERSION',         '4.5.$Revision: 1.3 $');if (!isSet($GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'])) $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] = '../';if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'util/Bs_Array.class.php');DEFINE("CACHE_DIR", $_SERVER['DOCUMENT_ROOT'] . 'jpgraph_cache/'); DEFINE("APACHE_CACHE_DIR", '/jpgraph_cache/'); DEFINE("USE_CACHE",TRUE); include($APP['path']['lib'] . 'JpGraph/jpgraph.php');include($APP['path']['lib'] . 'JpGraph/jpgraph_line.php');include($APP['path']['lib'] . 'JpGraph/jpgraph_bar.php');include($APP['path']['lib'] . 'JpGraph/jpgraph_scatter.php');include($APP['path']['lib'] . 'JpGraph/jpgraph_pie.php');class Bs_IntervalChart extends Bs_Object {var $chartWidth  = 600;var $chartHeight = 200;var $chartTitle;var $chartCaptionX;var $chartCaptionY;var $chartType = 'line';var $chartUseShadow = FALSE;var $Bs_Array;var $_data = NULL;var $_legend = NULL;var $_interval = 'day';function Bs_IntervalChart() {parent::Bs_Object(); $this->Bs_Array = &$GLOBALS['Bs_Array'];}
function setData($data, $legendData=NULL) {unset($this->_data);$this->_data   = &$data;$this->_legend = $legendData;}
function addRow($row, $legend=NULL) {if (!is_array($this->_data)) $this->_data = array();$this->_data[] = &$row;if (!is_null($legend)) {if (!is_array($this->_legend)) $this->_legend = array();$this->_legend[] = $legend;}
}
function setInterval($interval) {$this->_interval = $interval;}
function _groupDataByInterval() {reset($this->_data);while (list($k) = each($this->_data)) {$row = $this->_data[$k];$newData = array();foreach($row as $ts => $num) {if ($ts == -1) continue; switch ($this->_interval) {case 'month':
$newTs = mktime(0, 0, 0, date('m', $ts), 1, date('Y', $ts));if ($newTs == -1) continue; if (!isSet($newData[$newTs])) $newData[$newTs] = 0;$newData[$newTs] += $num;break;default: $newTs = mktime(0, 0, 0, date('m', $ts), date('d', $ts), date('Y', $ts));if ($newTs == -1) continue; if (!isSet($newData[$newTs])) $newData[$newTs] = 0;$newData[$newTs] += $num;}
}
$this->_data[$k] = $newData;}
}
function _fillDataGaps() {$someTs = key($this->_data[0]);reset($this->_data);while (list($k) = each($this->_data)) {$row = &$this->_data[$k];switch ($this->_interval) {case 'month':
$numElements = 12;for ($i=1; $i<=$numElements; $i++) {$ts = (string)mktime(0, 0, 0, $i, date('d', $someTs), date('Y', $someTs));if (!isSet($row[$ts])) $row[$ts] = 0;}
break;default: $numElements = 31; for ($i=1; $i<=$numElements; $i++) {$ts = (string)mktime(0, 0, 0, date('m', $someTs), $i, date('Y', $someTs));if (!isSet($row[$ts])) $row[$ts] = 0;}
}
ksort($row);$row = array_slice($row, 0, $numElements); }
}
function _getNextColor() {static $rgb_table;if (!is_array($rgb_table)) {$dummy = 'dummy';$dummy2 =& new RGB($dummy);$rgb_table = $dummy2->rgb_table;}
next($rgb_table);$key = key($rgb_table);if (($rgb_table[$key][0] > 150) && ($rgb_table[$key][1] > 150) && ($rgb_table[$key][2] > 150)) {return $this->_getNextColor();}
return key($rgb_table);}
function draw($name=NULL) {$this->_groupDataByInterval();$this->_fillDataGaps();if (is_null($name)) { $graph =& new Graph($this->chartWidth, $this->chartHeight);} else { $graph =& new Graph($this->chartWidth, $this->chartHeight, $name, 1, FALSE);}
$graph->SetScale('textlin'); if ($this->chartUseShadow) $graph->SetShadow();if (!empty($this->chartTitle))    $graph->title->Set($this->chartTitle);if (!empty($this->chartCaptionX)) $graph->xaxis->title->Set($this->chartCaptionX);if (!empty($this->chartCaptionY)) $graph->yaxis->title->Set($this->chartCaptionY);switch ($this->_interval) {case 'month':
$month=array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Okc","Nov","Dec");$graph->xaxis->SetTickLabels($month);$graph->xaxis->SetLabelAngle(90);break;default: $labels = array();for ($i=1; $i<32; $i++) $labels[] = $i;$graph->xaxis->SetTickLabels($labels);break;}
reset($this->_data);while (list($k) = each($this->_data)) {$this->_data[$k] = &$this->Bs_Array->reindex($this->_data[$k]);}
$hasLegend = FALSE;$i = 0;foreach($this->_data as $row) {switch ($this->chartType) {case 'bar':
$dataLineElement =& new BarPlot($row);$dataLineElement->SetFillColor($this->_getNextColor());$dataLineContainer[] = &$dataLineElement;$addMe =& new GroupBarPlot($dataLineContainer);break;default: $addMe =& new LinePlot($row);$addMe->SetColor($this->_getNextColor());}
if (@is_string($this->_legend[$i])) {$hasLegend = TRUE;$addMe->SetLegend($this->_legend[$i]);}
$graph->Add($addMe);$i++;}
if ($hasLegend) {$graph->legend->Pos(0.05, 0.05, 'right', 'top');$graph->img->SetMargin(40,140,60,30);}
$graph->Stroke();}
}
if (basename($_SERVER['PHP_SELF']) == 'Bs_IntervalChart.class.php') {$ts = (string)mktime();$data = array(
$ts => 55, 
($ts +10000)   => 40, 
($ts +200000)  => 60, 
($ts +2000000) => 60, 
($ts +4000000) => 80, 
);$ic =& new Bs_IntervalChart();$ic->chartTitle    = 'monthly chart';$ic->chartCaptionX = 'month';$ic->chartCaptionY = 'value';$ic->setInterval('month');$ic->setData($data);$ic->draw();}
?>
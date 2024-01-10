<style>
.sliderInput {
	height:20;
	width:40;
	font-family : Arial, Helvetica, sans-serif;
	font-size : 12px;
}
.smallTxt {
	font-size: 12px;
}
</style>
<script type="text/javascript" src="/javascript/blueshoes-4.5/javascript/lib/LibCrossBrowser.js"></script>
<script type="text/javascript" src="/javascript/blueshoes-4.5/javascript/lib/EventHandler.js"></script>
<script type="text/javascript" src="/javascript/blueshoes-4.5/javascript/core/form/Bs_FormUtil.lib.js"></script>
<script type="text/javascript" src="/javascript/blueshoes-4.5/javascript/core/gfx/Bs_ColorUtil.lib.js"></script>
<script type="text/javascript" src="/javascript/blueshoes-4.5/javascript/components/slider/Bs_Slider.class.js"></script>
<script type="text/javascript"><!--
function init(divname, instanceid) {
  // - Slider 1 -----------------------------------------
 mySlider = new Bs_Slider();
	mySlider.loadSkin('winxp-scrollbar-vertical');
  mySlider.height        = 100;
  mySlider.minVal        = 0;
  mySlider.maxVal        = 100;
  mySlider.valueDefault  = 50;
	mySlider.bgColor       = '#F9F9F7';
  mySlider.styleValueFieldClass = 'sliderInput';
  mySlider.drawInto(divname+instanceid);

//  mySlider = new Bs_Slider();
//  mySlider.attachOnChange(bsSliderChange);
//  mySlider.width         = 200;
//  mySlider.height        = 30;
//  mySlider.minVal        = 0;
//  mySlider.maxVal        = 100;
//  mySlider.valueInterval = 5;
//  mySlider.arrowAmount   = 2;
//	mySlider.arrowKeepFiringTimeout = 500;
//  mySlider.valueDefault  = 50;
//  mySlider.imgDir   = '/javascript/blueshoes-4.5/javascript/components/slider/img/';
//  mySlider.setBackgroundImage('bob/background.gif', 'no-repeat');
//  mySlider.setSliderIcon('bob/slider.gif', 13, 18);
//  mySlider.setArrowIconLeft('img/arrowLeft.gif', 16, 16);
//  mySlider.setArrowIconRight('img/arrowRight.gif', 16, 16);
//  mySlider.useInputField = 2;
//  mySlider.styleValueFieldClass = 'sliderInput';
//  mySlider.colorbar = new Object();
//  mySlider.colorbar['color']           = 'blue';
//  mySlider.colorbar['height']          = 5;
//  mySlider.colorbar['widthDifference'] = -12;
//  mySlider.colorbar['offsetLeft']      = 5;
//  mySlider.colorbar['offsetTop']       = 9;
//  mySlider.drawInto(divname+instanceid);
}

/**
* @param object sliderObj
* @param int val (the value)
*/
function bsSliderChange(sliderObj, val, newPos){ 
  test=sliderObj;
  testval=val;
  testnewPos=newPos;
//  document.test.attachedFieldValue.value = val;
}
/**
* @param object sliderObj
* @param int val (the value)
*/
function bsSliderStart(sliderObj, val, newPos){ 
  alert('Started at '+ val +' (Pos:'+ newPos +')');
}
/**
* @param object sliderObj
* @param int val (the value)
*/
function bsSliderEnd(sliderObj, val, newPos){ 
  sliderObj.valueInterval = 0.25;
  sliderObj.setValue(newPos);
  sliderObj.valueInterval = 0.05;
  document.test.attachedFieldValue.value = sliderObj.getValue();
}

// --></script>
<table>
	<tr>
		<th> Slider </th>
		<th> Value </th>
		<th> Note </th>
	</tr>
	<tr>
		<td><div id="SliderDiv1"></div></td>
		<td><input type="text" id="value" name="value" value="<?php echo $_REQUEST['value']; ?>"></td>
		<td><input type="text" id="note" name="note" value="<?php echo $_REQUEST['note']; ?>"></td>
	</tr>
</table>
//
<script type="text/javascript" language="javascript">
window.onload = function() {
	init();
};
</script>

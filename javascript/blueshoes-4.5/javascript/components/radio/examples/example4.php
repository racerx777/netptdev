<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<title>JavaScript Bs_Radio example 1</title>
<script type="text/javascript" src="/_bsJavascript/components/radio/Bs_Radio.class.js"></script>
<script type="text/javascript" src="/_bsJavascript/core/lang/Bs_Misc.lib.js"></script>


<script type="text/javascript">
  var radio_1;
  /**
  * Starting-Point after load fo the HTML-file 
  */
  this.onload = function() {
    radio_1 = new Bs_Radio();
    radio_1.objectName     = 'radio_1';
    radio_1.radioFieldName = 'pubState';
    radio_1.imgDir     = '/_bsJavascript/components/radio/img/bobby/';
    radio_1.imgWidth   = 12;
    radio_1.imgHeight  = 12;
    radio_1.cssStyle   = 'color:blue;';
    
    radio_1.value = "<?php echo (@$_REQUEST['pubState'] ? $_REQUEST['pubState'] : 'PUBLISH'); ?>";
    radio_1.addOption("PUBLISH", "Publish");
    radio_1.addOption("INACTIVE", "Inactive");
    var tmp = radio_1.renderAsTable('vertical', '<table cellpadding="0" cellspacing="0" border="0">');
    document.getElementById('radio_1').innerHTML = tmp;
  }
</script>
</head>

<body>

<h1>JavaScript Bs_Radio example 1</h1>

Simple usage of the radio control. Features used:
<ul>
  <li>Dynamically rendered</li>
  <li>User-defined icons.</li>
  <li>Caption is clickable aswell (default behavior).</li>
  <li>A CSS style attribute is used to set the text to blue.</li>
</ul>

<hr>
<br>

<form name="mainForm" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
  <table>
    <tr><td nowrap valign='top' align='left'><u>P</u>ublish State</td><td><span id="radio_1"></span></td></tr>
  </table>
  <input type="submit" name="Go" value="Go">
</form>
<hr>
<?php 
  if (!empty($_REQUEST)) {
    echo"<h3>Request Data received:</h3>";
    htmlspecialchars(var_dump($_REQUEST));
  }
?>

</body>
</html>

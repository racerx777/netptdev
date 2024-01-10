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
if ($_POST['step'] == '2') {require_once($_SERVER["DOCUMENT_ROOT"] . "../global.conf.php");require_once($APP['path']['core'] . 'html/Bs_JsCruncher.class.php');$j =& new Bs_JsCruncher();$output = $j->crunch($_POST['sourceInput']);} else {$output = '';}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Unbenannt</title>
</head>
<body>
<form action="" method="post">
<input type="hidden" name="step" value="2">
source input:<textarea name="sourceInput" cols="60" rows="10"><?php echo $_POST['sourceInput'];?></textarea><br><br>
source output:<textarea name="sourceOutput" cols="60" rows="10"><?php echo $output;?></textarea><br><br>
<input type="submit" name="submit" value="submit">
</form>
</body>
</html>

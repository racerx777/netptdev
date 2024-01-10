<?php
require_once("admin_common.php");
?>
<SCRIPT type="text/javascript">
  function setit(hexcolor){ 
  	<?php if($_GET['id']==1){ ?>
  	opener.self.document.changedepartment.topbackcolor.value = '#'+ hexcolor;
    <?php } ?>
  	<?php if($_GET['id']==2){ ?>
  	opener.self.document.changedepartment.midbackcolor.value = '#'+ hexcolor;
    <?php } ?>
  	<?php if($_GET['id']==3){ ?>
  	opener.self.document.changedepartment.botbackcolor.value = '#'+ hexcolor;
    <?php } ?>      
        window.close();
   }
 
   
</SCRIPT>
<table width=500>
<tr>
<?php

// we want to create a random color chart of Light colors consisting
// of C,D,E,F in Hex..
$highletters = array("C","D","E","F");

// rows
for($i=1; $i< 5; $i++){
 print "<tr>\n";
 // cols 
 for($j=1;$j<20;$j++){
    // generate random Hex..
    $randomhex = "";
    for ($index = 1; $index <= 6; $index++) {
       $randomindex = rand(0,3); 
       $randomhex .= $highletters[$randomindex];
    }	 
    print "<td width=20 bgcolor=$randomhex><a href=javascript:setit('$randomhex')><img src=images/blank.gif width=20 height=20 border=0></a></td>\n";
 }
 print "</tr>\n";
}
print "</table>\n";
?> 
<br>
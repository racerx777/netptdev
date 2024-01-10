<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
if(empty($_POST['pafname']))
	error('999','First Name is a required field, it cannot be blank.');
if(empty($_POST['palname']))
	error('999','Last Name is a required field, it cannot be blank.');
if(empty($_POST['passn']))
	error('999','Social Securty Number is a required field, it cannot be blank.');
?>
<?php 
header("HTTP/1.1 301 Moved Permanently");
header('Location: https://' . $_SERVER["SERVER_NAME"]);
?>
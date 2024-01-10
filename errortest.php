<?php

error_log(date('Y-m-d h:i:s').PHP_EOL, 3 , '/home/wsptn/logs/info.log');
error_log(print_r(debug_backtrace(), true).PHP_EOL, 3 , '/home/wsptn/logs/info.log');

?>
Hi
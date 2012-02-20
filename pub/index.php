<?php

define('MYSITE_BASE', dirname(__FILE__) . '/..');
require(MYSITE_BASE . '/cigi/cigi.php');

cigi_configure_from(MYSITE_BASE . '/site');
cigi_dispatch();

?>

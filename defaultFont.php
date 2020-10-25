<?php

error_reporting(0);

require_once __DIR__ . '/inc/common.php';

$file = "defaultFont{$GLOBALS['config']['fontSize']}.swf";
header('Content-type: application/x-shockwave-flash');
header('Content-Length: ' . filesize($file));
readfile($file);



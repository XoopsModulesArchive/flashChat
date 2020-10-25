<?php

require_once __DIR__ . '/inc/common.php';

session_start();

$_SESSION['userid'] = null;

require __DIR__ . '/inc/top.php';
?>
    <center>
        <h4>FlashChat Admin Panel Logout</h4>
        You've been logged out. <a href="admin.php">Click here to login</a>
    </center>
<?php
require __DIR__ . '/inc/bot.php';
?>

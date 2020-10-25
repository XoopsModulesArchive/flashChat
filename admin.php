<?php

if (isset($_REQUEST['module'])) {
    header('Refresh: 0; URL=modules/FlashChat/admin.php');

    exit;
}

require_once __DIR__ . '/inc/common.php';

session_start();

if (!isset($_SESSION['userid'])) {
    header('Refresh: 0; URL=login.php');

    exit;
}

$stmt = new Statement("SELECT count(*) as msgnumb FROM {$GLOBALS['config']['db']['pref']}messages WHERE command='msg' AND (userid IS NOT NULL OR roomid IS NOT NULL)");
$rs = $stmt->process();
$rec = $rs->next();
$msgnumb = $rec['msgnumb'];

require __DIR__ . '/inc/top.php';

if ($manageUsers) {
    $stmt = new Statement("SELECT count(*) as usrnumb FROM {$GLOBALS['config']['db']['pref']}users");

    $rs = $stmt->process();

    $rec = $rs->next();

    $usrnumb = $rec['usrnumb'];
} else {
    $usrnumb = 0;
}
?>
    <center><h4>FlashChat Administration Panel</h4></center>
    <p>This tool is designed to give FlashChat administrators multiple ways to view the chat logs, reset the chat logs, and add/edit/remove rooms. There are <?php if ($manageUsers) { ?><?= $usrnumb ?> registered users, and<?php } ?> <?= $msgnumb ?> logged messages. Configuration options for the chat
        can be set in the inc/config.php file, which comes with the FlashChat distribution.</p>
<?php
require __DIR__ . '/inc/bot.php';
?>

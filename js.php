<?php

require_once __DIR__ . '/inc/common.php';
?>

connid = 0;

function flashchat_DoFSCommand() {
}

function setFocus() {
var chatui = document.getElementById('flashchat');
if(chatui && chatui.focus) chatui.focus();
}

function doLogout() {
<?php
$base = '';
switch (get_class($GLOBALS['config']['cms'])) {
    case 'postnukecms':
    case 'phpnukecms':
        $base = 'modules/FlashChat/';
        break;
}
?>
<?php if ($GLOBALS['config']['showLogoutWindow']) { ?>
    width = 220;
    height = 30;

    wleft = (screen.width - width) / 2;
    wtop  = (screen.height - height) / 2 - 20;

    window.open("<?= $base ?>dologout.php?id=" + connid, "logout", "width=" + width + ",height=" + height + ",left=" + wleft + ",top=" + wtop + ",location=no,menubar=no,resizable=no,scrollbars=no,status=no,toolbar=no");
<?php } else { ?>
    img = new Image();
    img.src = "<?= $base ?>dologout.php?seed=<?= time() ?>&id=" + connid;
<?php } ?>
}

function setConnid(newconnid) {
connid = newconnid;
}

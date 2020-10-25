<?php

require_once __DIR__ . '/inc/common.php';

$id = 'flashchat';

$params = [];

if (isset($_REQUEST['username'])) {
    if ('__random__' == $_REQUEST['username']) {
        $_REQUEST['username'] = 'user_' . time();
    }

    if (!isset($_REQUEST['lang'])) {
        $_REQUEST['lang'] = $GLOBALS['config']['defaultLanguage'];
    }

    if (!isset($_REQUEST['password'])) {
        $_REQUEST['password'] = '';
    }

    $params = array_merge(
        $params,
        [
            'login' => $_REQUEST['username'],
'password' => $_REQUEST['password'],
'lang' => $_REQUEST['lang'],
        ]
    );
}
?>
<html>
<head>
    <title>FlashChat v<?= $GLOBALS['config']['version'] ?></title>
    <script type="text/javascript" src="js.php"></script>
</head>

<body marginwidth="0" marginheight="0" leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" scroll="no" onLoad="setFocus()" onUnload="doLogout()">
<center><?= flashTag('flashchat', 'preloader.swf', '100%', '100%', $params) ?></center>
</body>
</html>

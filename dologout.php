<?php

require_once __DIR__ . '/inc/common.php';

$msg = 'Logging out from the chat...';

$req = [
    'id' => $_REQUEST['id'],
'c' => 'lout',
];

$conn = &ChatServer::getConnection($_REQUEST);
$conn->process($req);

if (!$GLOBALS['config']['showLogoutWindow']) {
    header('Refresh: 0; URL=images/spacer.gif');

    exit;
}
    ?>
    <html>
    <head>
        <title><?= $msg ?></title>
        <script type="text/javascript">
            function autoclose() {
                setInterval('window.close()', <?=$GLOBALS['config']['logoutWindowDisplayTime']?> * 1000);
            }
        </script>
    </head>

    <style type="text/css">
        <!--
        BODY {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 11px;
            font-weight: bold;
            color: <?=htmlColor($GLOBALS['config']['themes'][$GLOBALS['config']['defaultTheme']]['enterRoomNotify'])?>;
        }

        -->
    </style>

    <body bgcolor="<?= htmlColor($GLOBALS['config']['themes'][$GLOBALS['config']['defaultTheme']]['publicLogBackground']) ?>" onLoad="autoclose()">
    <center><?= $msg ?></center>
    </body>
    </html>
<?php  ?>

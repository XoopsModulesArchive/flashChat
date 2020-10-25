<?php

require_once __DIR__ . '/inc/common.php';

$conn = &ChatServer::getConnection($_REQUEST);
$mqi = $conn->process($_REQUEST);

$users = [];
$rooms = [];

function getLocalMessage($messageid, $lang = null)
{
    if (!isset($lang)) {
        $lang = $GLOBALS['config']['defaultLanguage'];
    }

    $msg = $GLOBALS['config']['languages'][$lang]['messages'][$messageid];

    if (!$msg) {
        $msg = $GLOBALS['config']['languages'][$GLOBALS['config']['defaultLanguage']]['messages'][$messageid];
    }

    if (!$msg) {
        $msg = $GLOBALS['config']['languages']['en']['messages'][$messageid];
    }

    return $msg;
}

function parseMessage($msg, $userLabel, $roomLabel, $timestamp)
{
    global $users, $rooms;

    $search = [
        'USER_LABEL',
        'ROOM_LABEL',
        'TIMESTAMP',
    ];

    $replace = [
        $userLabel,
        $roomLabel,
        $timestamp,
    ];

    return str_replace($search, $replace, $msg);
}

function formatMessage($msg, $userLabel = '', $roomLabel = '', $timestamp = '')
{
    $color = htmlColor($GLOBALS['config']['themes'][$GLOBALS['config']['defaultTheme']]['enterRoomNotify']);

    return "<font color=\"$color\">" . parseMessage($msg, $userLabel, $roomLabel, $timestamp) . '</font><br>';
}

?>
<html>
<head>
    <title>Chat log</title>
    <meta http-equiv=Content-Type content="text/html;  charset=UTF-8">
</head>

<style type="text/css">
    <!--
    BODY {
        font-family: Verdana, Arial, Helvetica, sans-serif;
        font-size: <?=$GLOBALS['config']['fontSize']?>px;
    }

    -->
</style>


<body bgcolor="<?= htmlColor($GLOBALS['config']['themes'][$GLOBALS['config']['defaultTheme']]['publicLogBackground']) ?>" onLoad="window.focus()">
<?php
while ($mqi->hasNext()) {
    $m = $mqi->next();

    $m->created = format_Timestamp($m->created, $conn->tzoffset);

    switch ($m->command) {
        case 'msgu':
        case 'msgb':
        case 'msg':
            $color = ('msg' != $m->command) ? htmlColor($GLOBALS['config']['themes'][$GLOBALS['config']['defaultTheme']]['enterRoomNotify']) : $users[$m->userid][2];
            $login = ('msgb' == $m->command) ? $m->login : $users[$m->userid][0];
            echo("<font color=\"$color\">");
            if ($GLOBALS['config']['showTimeStamp'] && (TIMESTAMP_BEFORE == $GLOBALS['config']['timeStampPosition'])) {
                echo("{$m->created} ");
            }
            echo("[$login");
            if ($m->touserid) {
                echo("->{$users[$m->touserid][0]}");
            }
            echo(']');
            if ($GLOBALS['config']['showTimeStamp'] && (TIMESTAMP_AFTER == $GLOBALS['config']['timeStampPosition'])) {
                echo(" {$m->created}");
            }
            echo(": {$m->txt}</font><br>");
            break;
        case 'adu':
            $users[$m->userid] = [$m->txt, $m->roomid, htmlColor($GLOBALS['config']['themes'][$GLOBALS['config']['defaultTheme']]['recommendedUserColor'])];
            if (isset($users[$conn->userid]) && $users[$conn->userid][1] == $m->roomid) {
                echo(formatMessage(getLocalMessage(($m->userid == $conn->userid) ? 'selfenterroom' : 'enterroom', $conn->lang), $users[$m->userid][0], $rooms[$m->roomid], $m->created));
            }
            break;
        case 'uclc':
            $users[$m->userid][2] = htmlColor($m->txt);
            break;
        case 'mvu':
            if ($m->userid == $conn->userid) {
                echo(formatMessage(getLocalMessage('selfenterroom', $conn->lang), $users[$m->userid][0], $rooms[$m->roomid], $m->created));
            } else {
                if ($m->roomid == $users[$conn->userid][1]) {
                    echo(formatMessage(getLocalMessage('enterroom', $conn->lang), $users[$m->userid][0], $rooms[$m->roomid], $m->created));
                } else {
                    echo(formatMessage(getLocalMessage('leaveroom', $conn->lang), $users[$m->userid][0], $rooms[$users[$conn->userid][1]], $m->created));
                }
            }
            $users[$m->userid][1] = $m->roomid;
            break;
        case 'rmu':
            echo(formatMessage(getLocalMessage('leaveroom', $conn->lang), $users[$m->userid][0], $rooms[$users[$conn->userid][1]], $m->created));
            break;
        case 'adr':
            $rooms[$m->roomid] = $m->txt;
            break;
        case 'error':
            echo(formatMessage(getLocalMessage($m->txt, $conn->lang), $users[$m->userid][0], $rooms[$users[$conn->userid][1]], $m->created));
            break;
        case 'back':
            echo(formatMessage("/back {$m->roomid}"));
            break;
        case 'backt':
            echo(formatMessage("/backtime {$m->roomid}"));
            break;
    }
}
?>
</body>
</html>

<?php

/**
 * If this file is not in the FlashChat root folder, then change this
 * path to the location of the inc/common.php file.
 */
require_once __DIR__ . '/inc/common.php';

ChatServer::purgeExpired();

/**
 * Retrieves the number of users who are chatting in any room.
 * Leave the $room parameter empty to return the number of users in all room.
 * @param mixed $room
 * @return int
 * @return int
 */
function numusers($room = '')
{
    if ($room) {
        $stmt = new Statement("SELECT COUNT(*) AS numb FROM {$GLOBALS['config']['db']['pref']}connections WHERE userid IS NOT NULL AND roomid=?");

        $rs = $stmt->process($room);
    } else {
        $stmt = new Statement("SELECT COUNT(*) AS numb FROM {$GLOBALS['config']['db']['pref']}connections WHERE userid IS NOT NULL");

        $rs = $stmt->process();
    }

    $rec = $rs->next();

    return $rec ? $rec['numb'] : 0;
}

/**
 * Retrieves a list of the users (by login ID) who are in $room.
 * Leave the $room parameter empty to return a list of all users in all rooms.
 * @param mixed $room
 * @return array
 * @return array
 */
function usersinroom($room = '')
{
    $cms = $GLOBALS['config']['cms'];

    $list = [];

    if ($room) {
        $stmt = new Statement("SELECT userid, state, color, lang, roomid FROM {$GLOBALS['config']['db']['pref']}connections WHERE userid IS NOT NULL AND roomid=?");

        $rs = $stmt->process($room);
    } else {
        $stmt = new Statement("SELECT userid, state, color, lang, roomid FROM {$GLOBALS['config']['db']['pref']}connections WHERE userid IS NOT NULL");

        $rs = $stmt->process();
    }

    while ($rec = $rs->next()) {
        $list[] = array_merge($cms->getUser($rec['userid']), $rec);
    }

    return $list;
}

/**
 * Retrieves a list of all available rooms, as an array.
 */
function roomlist()
{
    $list = [];

    // populate $list with the names of all available rooms

    $stmt = new Statement("SELECT * FROM {$GLOBALS['config']['db']['pref']}rooms WHERE ispublic IS NOT NULL");

    $rs = $stmt->process();

    while ($rec = $rs->next()) {
        $list[] = $rec;
    }

    //result will be an array of arrays like ('id' => <room id>, 'updated' = <timestamp>, 'created' => <timestamp>, 'name' => <room name>, 'ispublic' => <public flag>, 'ispermanent' => <autoclose flag>)

    return $list;
}

$rooms = roomlist();
$roomnumb = count($rooms);
?>

<html>
<title>Who's in the chat?</title>
<head>
    <style type="text/css">
        <!--
        .normal {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 12px;
            font-weight: normal;
        }

        A {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #0000FF;
        }

        A:hover {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #FF0000;
        }

        -->
    </style>
</head>
<body>
<center>
    <p class=normal>There are <?= numusers() ?> users in <?= $roomnumb ?> rooms.</p>
    <?php if ($roomnumb) { ?>
        <table border="1" cellpadding="1" class="normal">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Count</th>
                <th>Users</th>
            </tr>
            <?php foreach ($rooms as $room) { ?>
                <tr>
                    <td><?= $room['id'] ?></td>
                    <td><?= $room['name'] ?></td>
                    <td><?= numusers($room['id']) ?></td>
                    <td><?php

                        $users = usersinroom($room['id']);

                        foreach ($users as $user) {
                            echo $user['login'] . '<br>';
                        }

                        ?> </td>
                </tr>
            <?php } ?>
        </table>
    <?php } ?>

    <p><a href="javascript:window.close()">Close</a></p>
    <center>
</body>
</html>

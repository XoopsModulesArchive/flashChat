<?php

require_once __DIR__ . '/inc/common.php';

session_start();

if (!isset($_SESSION['userid'])) {
    header('Refresh: 0; URL=login.php');

    exit;
}

function str2date($str)
{
    // MM/DD/YY

    $parts = preg_split('/', $str);

    return "{$parts[2]}-{$parts[0]}-{$parts[1]}";
}

$urs = $GLOBALS['config']['cms']->getUsers();

$stmt = new Statement("SELECT * FROM {$GLOBALS['config']['db']['pref']}rooms");
$rrs = $stmt->process();

if (!isset($_REQUEST['roomid']) || isset($_REQUEST['clear'])) {
    $_REQUEST['roomid'] = 0;
}
if (!isset($_REQUEST['userid']) || isset($_REQUEST['clear'])) {
    $_REQUEST['userid'] = 0;
}
if (!isset($_REQUEST['from']) || isset($_REQUEST['clear'])) {
    $_REQUEST['from'] = '';
}
if (!isset($_REQUEST['to']) || isset($_REQUEST['clear'])) {
    $_REQUEST['to'] = '';
}
if (!isset($_REQUEST['days']) || isset($_REQUEST['clear'])) {
    $_REQUEST['days'] = '';
}
if (!isset($_REQUEST['keyword']) || isset($_REQUEST['clear'])) {
    $_REQUEST['keyword'] = '';
}

$where = ['1=1'];
if ($_REQUEST['roomid']) {
    $where[] = "(msgs.roomid='{$_REQUEST['roomid']}' OR msgs.toroomid='{$_REQUEST['roomid']}')";
}
if ($_REQUEST['userid']) {
    $where[] = "(msgs.userid='{$_REQUEST['userid']}' OR msgs.touserid='{$_REQUEST['userid']}')";
}
if ($_REQUEST['days']) {
    $where[] = "msgs.created >= DATE_SUB(NOW(),INTERVAL {$_REQUEST['days']} DAY)";
}
if ($_REQUEST['from'] && preg_match('/^\d+\/\d+\/\d+$/', $_REQUEST['from'])) {
    $where[] = "msgs.created >= '" . str2date($_REQUEST['from']) . "'";
}
if ($_REQUEST['to'] && preg_match('/^\d+\/\d+\/\d+$/', $_REQUEST['to'])) {
    $where[] = "msgs.created <= '" . str2date($_REQUEST['to']) . "'";
}
if ($_REQUEST['keyword']) {
    $where[] = "msgs.txt LIKE '%{$_REQUEST['keyword']}%'";
}

$qry = "SELECT msgs.*, DATE_FORMAT(msgs.created, '%b %e, %Y %r') AS sent, torooms.name AS toroom, fromrooms.name AS fromroom FROM "
       . "{$GLOBALS['config']['db']['pref']}messages AS msgs LEFT JOIN {$GLOBALS['config']['db']['pref']}rooms AS fromrooms ON msgs.roomid=fromrooms.id "
       . "LEFT JOIN {$GLOBALS['config']['db']['pref']}rooms AS torooms ON msgs.toroomid=torooms.id "
       . "WHERE command='msg' AND (msgs.touserid IS NOT NULL OR msgs.toroomid IS NOT NULL) AND "
       . implode(' AND ', $where)
       . ' ORDER BY msgs.id';

$stmt = new Statement($qry);
$rs = $stmt->process();

$users = [];

function getUser($userid)
{
    global $users, $manageUsers;

    if (!isset($users[$userid])) {
        $user = $GLOBALS['config']['cms']->getUser($userid);

        if ($manageUsers) {
            $users[$userid] = "<a href=\"user.php?id={$user['id']}\">{$user['login']}</a>";
        } else {
            $users[$userid] = $user['login'];
        }
    }

    return $users[$userid];
}

require __DIR__ . '/inc/top.php';
?>
    <center>
        <h4>Messages</h4>
        <form name="msglist" action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="post">
            <table border="0">
                <tr>
                    <td align="right">in this room:</td>
                    <td>
                        <select name="roomid">
                            <option value="0">[ Any room ]
                                <?php while ($rec = $rrs->next()) { ?>
                            <option value="<?= $rec['id'] ?>" <?php if ($_REQUEST['roomid'] == $rec['id']) {
    echo('selected');
} ?>><?= $rec['name'] ?>
                                <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="right">between these dates:</td>
                    <td><input type="text" name="from" value="<?= $_REQUEST['from'] ?>" size="8"> and <input type="text" name="to" value="<?= $_REQUEST['to'] ?>" size="8">(MM/DD/YY)</td>
                </tr>
                <tr>
                    <td align="right">from the past X days:</td>
                    <td><input type="text" name="days" value="<?= $_REQUEST['days'] ?>" size="8"></td>
                </tr>
                <tr>
                    <td align="right">by this user:</td>
                    <td>
                        <select name="userid">
                            <option value="0">[ Any user ]
                                <?php while ($rec = $urs->next()) { ?>
                            <option value="<?= $rec['id'] ?>" <?php if ($_REQUEST['userid'] == $rec['id']) {
    echo('selected');
} ?>><?= $rec['login'] ?>
                                <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="right" width="200">containing this keyword:</td>
                    <td><input type="text" name="keyword" value="<?= $_REQUEST['keyword'] ?>" size="32"></td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <input type="submit" name="apply" value="Show messages">
                        <input type="submit" name="clear" value="Clear filter">
                        <!--<input type="submit" name="remove" value="Remove messages">-->
                    </td>
                </tr>
            </table>
        </form>
        <?php if ($rs->hasNext()) { ?>
        <table border="1">
            <tr>
                <th>id</th>
                <th>sent</th>
                <th>from user</th>
                <th>to room</th>
                <th>to user</th>
                <th>txt</th>
            </tr>
            <?php while ($rec = $rs->next()) { ?>
                <tr>
                    <td><?= $rec['id'] ?></td>
                    <td><?= $rec['sent'] ?></td>
                    <td><?= getUser($rec['userid']) ?></td>
                    <td><a href="room.php?id=<?= $rec['toroomid'] ?>"><?= $rec['toroom'] ?></a></td>
                    <td><?= getUser($rec['touserid']) ?></td>
                    <td><?= $rec['txt'] ?></td>
                </tr>
            <?php } ?>
            <?php } else { ?>
                No messages found
            <?php } ?>
    </center>
<?php
require __DIR__ . '/inc/bot.php';
?>

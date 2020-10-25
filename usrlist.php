<?php

require_once __DIR__ . '/inc/common.php';

session_start();

if (!isset($_SESSION['userid'])) {
    header('Refresh: 0; URL=login.php');

    exit;
}

$stmt = new Statement("SELECT * FROM {$GLOBALS['config']['db']['pref']}users");
$rs = $stmt->process();

function roles2str($roles)
{
    switch ($roles) {
        case ROLE_ADMIN:
            return 'admin';
        case ROLE_USER:
            return 'user';
        case ROLE_CUSTOMER:
            return 'customer';
        case ROLE_SPY:
            return 'spy';
    }
}

require __DIR__ . '/inc/top.php';
?>
    <center>
        <h4>Users</h4>
        <a href="user.php">Add new user</a><br>
        <br>
        <?php if ($rs->hasNext()) { ?>
        <table border="1">
            <tr>
                <th>id</th>
                <th>login</th>
                <th>password</th>
                <th>role</th>
            </tr>
            <?php while ($rec = $rs->next()) { ?>
                <tr>
                    <td><?= $rec['id'] ?></td>
                    <td><a href="user.php?id=<?= $rec['id'] ?>"><?= $rec['login'] ?></a></td>
                    <td><?= $rec['password'] ?></td>
                    <td><?= roles2str($rec['roles']) ?></td>
                </tr>
            <?php } ?>
            <?php } else { ?>
                No users found
            <?php } ?>
    </center>
<?php
require __DIR__ . '/inc/bot.php';
?>

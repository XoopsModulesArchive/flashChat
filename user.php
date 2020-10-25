<?php

require_once __DIR__ . '/inc/common.php';

session_start();

if (!isset($_SESSION['userid'])) {
    header('Refresh: 0; URL=login.php');
    exit;
}

$error  = '';
$notice = '';

if (isset($_REQUEST['add'])) {
    if (!$_REQUEST['login']) {
        $error = 'login cannot be empty';
    } else {
        $stmt           = new Statement("INSERT INTO {$GLOBALS['config']['db']['pref']}users (login, password, roles) VALUES (?, ?, ?)");
        $_REQUEST['id'] = $stmt->process($_REQUEST['login'], $_REQUEST['password'], $_REQUEST['roles']);
        $notice         = 'user added';
    }
} elseif (isset($_REQUEST['set'])) {
    if (!$_REQUEST['login']) {
        $error = 'login cannot be empty';
    } elseif (!$_REQUEST['id']) {
        $error = 'wrong user id';
    } else {
        $stmt = new Statement("UPDATE {$GLOBALS['config']['db']['pref']}users SET login=?, password=?, roles=? WHERE id=?");
        $stmt->process($_REQUEST['login'], $_REQUEST['password'], $_REQUEST['roles'], $_REQUEST['id']);
        $notice = 'user updated';
    }
} elseif (isset($_REQUEST['del'])) {
    if (!$_REQUEST['id']) {
        $error = 'wrong user id';
    } else {
        $stmt = new Statement("DELETE FROM {$GLOBALS['config']['db']['pref']}users WHERE id=?");
        $stmt->process($_REQUEST['id']);
        $notice         = 'user removed';
        $_REQUEST['id'] = null;
    }
}

$roles = [
    ROLE_USER     => 'user',
    ROLE_ADMIN    => 'admin',
    ROLE_SPY      => 'spy',
    ROLE_CUSTOMER => 'customer',
];

if (isset($_REQUEST['id'])) {
    $stmt     = new Statement("SELECT * FROM {$GLOBALS['config']['db']['pref']}users WHERE id=?");
    $rs       = $stmt->process($_REQUEST['id']);
    $_REQUEST = $rs->next();
} else {
    $_REQUEST['id']       = 0;
    $_REQUEST['login']    = '';
    $_REQUEST['password'] = '';
    $_REQUEST['roles']    = ROLE_USER;
}

require __DIR__ . '/inc/top.php';
?>
    <center>
        <?php if ($error) { ?><font color="red"><?= $error ?></font><?php } ?>
        <?php if ($notice) { ?><font color="green"><?= $notice ?></font><?php } ?>
        <h4>user</h4>
        <form name="user" action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="post">
            <input type="hidden" name="id" value="<?= $_REQUEST['id'] ?>">
            <table border="0">
                <tr>
                    <td align="right">login</td>
                    <td><input type="text" name="login" value="<?= $_REQUEST['login'] ?>"></td>
                </tr>
                <tr>
                    <td align="right">password</td>
                    <td><input type="text" name="password" value="<?= $_REQUEST['password'] ?>"></td>
                </tr>
                <tr>
                    <td align="right">role</td>
                    <td>
                        <select name="roles">
                            <?php foreach ($roles

                            as $k => $v) { ?>
                            <option value="<?= $k ?>" <?php if ($_REQUEST['roles'] == $k) {
                                echo('selected');
                            } ?>><?= $v ?>
                                <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <input type="submit" name="add" value="Add new user">
                        <input type="submit" name="set" value="Update user" <?php if (!$_REQUEST['id']) {
                            echo('disabled');
                        } ?>>
                        <input type="submit" name="del" value="Remove user" <?php if (!$_REQUEST['id']) {
                            echo('disabled');
                        } ?>>
                    </td>
                </tr>
            </table>
        </form>
    </center>
<?php
require __DIR__ . '/inc/bot.php';
?>

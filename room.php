<?php

require_once __DIR__ . '/inc/common.php';

session_start();

if (!isset($_SESSION['userid'])) {
    header('Refresh: 0; URL=login.php');
    exit;
}

$error  = '';
$notice = '';

if (!isset($_REQUEST['ispublic'])) {
    $_REQUEST['ispublic'] = null;
}
if (!isset($_REQUEST['ispermanent'])) {
    $_REQUEST['ispermanent'] = null;
}

if (isset($_REQUEST['add'])) {
    if (!$_REQUEST['name']) {
        $error = 'name cannot be empty';
    } else {
        $stmt           = new Statement("INSERT INTO {$GLOBALS['config']['db']['pref']}rooms (name, ispublic, ispermanent) VALUES (?, ?, ?)");
        $_REQUEST['id'] = $stmt->process($_REQUEST['name'], $_REQUEST['ispublic'], $_REQUEST['ispermanent']);
        $notice         = 'room added';
    }
} elseif (isset($_REQUEST['set'])) {
    if (!$_REQUEST['name']) {
        $error = 'name cannot be empty';
    } elseif (!$_REQUEST['id']) {
        $error = 'wrong room id';
    } else {
        $stmt = new Statement("UPDATE {$GLOBALS['config']['db']['pref']}rooms SET name=?, ispublic=?, ispermanent=? WHERE id=?");
        $stmt->process($_REQUEST['name'], $_REQUEST['ispublic'], $_REQUEST['ispermanent'], $_REQUEST['id']);
        $notice = 'room updated';
    }
} elseif (isset($_REQUEST['del'])) {
    if (!$_REQUEST['id']) {
        $error = 'wrong room id';
    } else {
        $stmt = new Statement("DELETE FROM {$GLOBALS['config']['db']['pref']}rooms WHERE id=?");
        $stmt->process($_REQUEST['id']);
        $notice         = 'room removed';
        $_REQUEST['id'] = null;
    }
}

if (isset($_REQUEST['id'])) {
    $stmt     = new Statement("SELECT * FROM {$GLOBALS['config']['db']['pref']}rooms WHERE id=?");
    $rs       = $stmt->process($_REQUEST['id']);
    $_REQUEST = $rs->next();
} else {
    $_REQUEST['id']          = 0;
    $_REQUEST['name']        = '';
    $_REQUEST['ispublic']    = 'y';
    $_REQUEST['ispermanent'] = 'y';
}

require __DIR__ . '/inc/top.php';
?>
    <center>
        <?php if ($error) { ?><font color="red"><?= $error ?></font><?php } ?>
        <?php if ($notice) { ?><font color="green"><?= $notice ?></font><?php } ?>
        <h4>Room</h4>
        <form name="room" action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="post">
            <input type="hidden" name="id" value="<?= $_REQUEST['id'] ?>">
            <table border="0">
                <tr>
                    <td align="right">name</td>
                    <td><input type="text" name="name" value="<?= $_REQUEST['name'] ?>"></td>
                </tr>
                <tr>
                    <td align="right">public</td>
                    <td><input type="checkbox" name="ispublic" value="<?= $_REQUEST['ispublic'] ?: 'y' ?>" <?php if ($_REQUEST['ispublic']) echo 'checked' ?>></td>
                </tr>
                <tr>
                    <td align="right">permanent</td>
                    <td><input type="checkbox" name="ispermanent" value="<?= $_REQUEST['ispermanent'] ?: 1 ?>" <?php if ($_REQUEST['ispermanent']) echo 'checked' ?>></td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <input type="submit" name="add" value="Add new room">
                        <input type="submit" name="set" value="Update room" <?php if (!$_REQUEST['id']) {
                            echo('disabled');
                        } ?>>
                        <input type="submit" name="del" value="Remove room" <?php if (!$_REQUEST['id']) {
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

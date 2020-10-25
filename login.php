<?php

require_once __DIR__ . '/inc/common.php';

session_start();

$error = '';

function doLogin($userid)
{
    $_SESSION['userid'] = $userid;
    header('Refresh: 0; URL=admin.php');
    exit;
}

if (($userid = ChatServer::isLoggedIn()) && ChatServer::userInRole($userid, ROLE_ADMIN)) {
    doLogin($userid);
}

if (isset($_REQUEST['do'])) {
    if (($userid = ChatServer::login($_REQUEST['login'], $_REQUEST['password'])) && ChatServer::userInRole($userid, ROLE_ADMIN)) {
        doLogin($userid);
    } else {
        $_SESSION['userid'] = null;
        $error              = 'Could not grant admin role for these login and password';
    }
} else {
    $_SESSION['userid']   = null;
    $_REQUEST['login']    = '';
    $_REQUEST['password'] = '';
}

require __DIR__ . '/inc/top.php';
?>
    <center>
        <?php if ($error) { ?><font color="red"><?= $error ?></font><?php } ?>
        <h4>FlashChat Admin Panel Login</h4>
        <form name="login" action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="post">
            <table border="0">
                <tr>
                    <td align="right">login</td>
                    <td><input type="text" name="login" value=""></td>
                </tr>
                <tr>
                    <td align="right">password</td>
                    <td><input type="password" name="password" value=""></td>
                </tr>
                <tr>
                    <td colspan="2" align="center"><input type="submit" name="do" value="Login"></td>
                </tr>
            </table>
        </form>
    </center>
<?php
require __DIR__ . '/inc/bot.php';
?>

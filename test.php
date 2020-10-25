<?php

require_once __DIR__ . '/inc/common.php';

error_reporting(E_ALL);

if (isset($_REQUEST['op'])) {
    switch ($_REQUEST['op']) {
        case 'login':
            $userid = $GLOBALS['config']['cms']->login($_REQUEST['login'], $_REQUEST['password']);
            break;
        case 'logout':
            $GLOBALS['config']['cms']->logout();
            $userid = null;
            break;
    }
}

$userid = $GLOBALS['config']['cms']->isLoggedIn();
?>
<html>
<body>

<form action="test.php?op=login" method="post">
    Login: <input type="text" name="login"><br>
    Password: <input type="password" name="password"><br>
    <input type="submit" value="Login">
</form>

<HR>
<?php if ($userid) { ?>
    User id: <?= $userid ?><br>
    User info:<br>
    <pre><?php print_r($GLOBALS['config']['cms']->getUser($userid)) ?></pre>
    <br>
    Is user: <?= $GLOBALS['config']['cms']->userInRole($userid, ROLE_USER) ? 'true' : 'false' ?><br>
    Is admin: <?= $GLOBALS['config']['cms']->userInRole($userid, ROLE_ADMIN) ? 'true' : 'false' ?><br>
<?php } else { ?>
    You are not logged in
<?php } ?>
<HR>

<a href="test.php?op=logout">Logout</a>

</body>
</html>

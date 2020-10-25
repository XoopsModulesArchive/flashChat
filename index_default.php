<?php

require_once __DIR__ . '/inc/common.php';
?>
<html>
<head>
    <title>FlashChat v<?= $GLOBALS['config']['version'] ?></title>
    <meta http-equiv=Content-Type content="text/html;  charset=UTF-8">
    <style type="text/css">
        <!--
        td {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 12px;
            font-weight: normal;
        }

        .small {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 10px;
            font-weight: normal;
        }

        .title {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 16px;
            font-weight: bold;
        }

        input {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 12px;
            font-weight: normal;
        }

        select {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 12px;
            font-weight: normal;
        }

        A {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            color: #0000FF;
        }

        A:hover {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            color: #FF0000;
        }

        -->
    </style>
    <script language="Javascript">
        <!--
        function formIsValid() {
            // check to make sure a valid username has been entered
            if (document.login.username.value == "") {
                alert('Please fill in username field');
                return false;
            }

            return true;
        }

        // a small poupup window to show who's in the chat at the current time
        function showInfo() {
            // the size of the popup window
            var width = 400;
            var height = 300;

            // the x,y position of the popup window
            // NOTE: this formula will auto-center the popup on the screen
            var y = (screen.height - height) / 2;
            var x = (screen.width - width) / 2;

            var url = "info.php";
            var options = "width=" + width + ",height=" + height + ",top=" + y + ",left=" + x + ",resizable";

            // open the info window as a popup, instead of embedded in webpage
            window.open(url, "info", options);
        }

        function basicLogin() {
            if (formIsValid()) document.login.submit();
        }

        function popupLogin() {
            // check to make sure a valid username has been entered
            if (!formIsValid()) return;

            var username = document.login.username.value;
            var password = document.login.password.value;
            var lang = document.login.lang.value;

            // the size of the popup window
            var width = 800;
            var height = 600;

            // the x,y position of the popup window
            // NOTE: this formula will auto-center the popup on the screen
            var y = (screen.height - height) / 2;
            var x = (screen.width - width) / 2;

            var url = "flashchat.php?username=" + username + "&password=" + password + "&lang=" + lang;
            var options = "width=" + width + ",height=" + height + ",top=" + y + ",left=" + x + ",resizable";

            // open the chat window as a popup, instead of embedded in webpage
            window.open(url, "chat", options);
        }

        //-->
    </script>
</head>
<body>
<table border="0" align="center" width=500 cellpadding="4">
    <tr>
        <td nowrap width=34%> <span class=title>Welcome to FlashChat v
      <?= $GLOBALS['config']['version'] ?>
      </span></td>
        <td width=66% align=right><a href="javascript:showInfo();">Who's in the chat?</a></a>
        </td>
    </tr>
    <form action="flashchat.php" method="post">
        <tr>
            <td width="34%" nowrap>Start FlashChat using built-in login</td>
            <td width="66%"><input name="submit" type="submit" id="submit" value="Login &gt;&gt;"></td>
        </tr>
    </form>
    <tr>
        <td colspan="2">
            <HR>
        </td>
    </tr>
    <form action="flashchat.php" method="post" name="login">
        <tr>
            <td colspan="2">Start FlashChat using HTML-based login</td>
        </tr>
        <tr>
            <td colspan="2" align="right">
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td>
                            <div align="right">Username:</div>
                        </td>
                        <td><input type="text" name="username"></td>
                    </tr>
                    <tr>
                        <td>
                            <div align="right">Password:</div>
                        </td>
                        <td><input type="password" name="password"></td>
                    </tr>
                    <tr>
                        <td>
                            <div align="right">Language:</div>
                        </td>
                        <td><select name="lang">
                                <?php foreach ($GLOBALS['config']['languages'] as $k => $v) { ?>
                                    <option value="<?= $k ?>" <?php if ($k == $GLOBALS['config']['defaultLanguage']) {
    echo('selected');
} ?>>
                                        <?= $v['name'] ?>
                                    </option>
                                <?php } ?>
                            </select></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td><input name="button" type="button" onClick="javascript:basicLogin();" value="Login &gt;&gt;"></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td><input name="button" type="button" onClick="javascript:popupLogin();" value="Popup Login &gt;&gt;"></td>
                    </tr>
                    <tr>
                        <td colspan="2">To login as a moderator, use <b>
                                <?= $GLOBALS['config']['adminPassword'] ?>
                            </b> as the password. To login as a spy, use <b>
                                <?= $GLOBALS['config']['spyPassword'] ?>
                            </b> as the password. These passwords can be set in the FlashChat
                            configuration file.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2" class=small>
                <HR>
            </td>
        </tr>
        <tr>
            <td width="30%" valign=top nowrap>Start FlashChat using a direct link</td>
            <td width="70%"><a target=login href="flashchat.php?username=__random__&lang=en">Standard
                    Auto-Login</a><br><a target=login href="flashchat.php?username=__random__&password=<?= $GLOBALS['config']['adminPassword'] ?>&lang=en">Moderator
                    Auto-Login</a><br><a target=login href="flashchat.php?username=__random__&password=<?= $GLOBALS['config']['spyPassword'] ?>&lang=en">Spy
                    Auto-Login</a></td>
        </tr>
        <tr>
            <td colspan="2" class=small>
                <HR>
            </td>
        </tr>
        <tr>
            <td colspan=2><a target=demo href="sample.php">ACME Widgets Demo</a>.
                This demo shows how you can integrate FlashChat into your website's template. ACME Widgets is a ficticous company.
            </td>
        </tr>
        <tr>
            <td colspan=2 class=small>
                <HR>
            </td>
        </tr>
        <tr>
            <td colspan=2><a href=http://www.tufat.com/chat.php target="purchase">Purchase
                    FlashChat for your website for $5!</a></td>
        </tr>
        <tr>
            <td class=small colspan=2>What you get: All PHP code, Flash SWF file, MySQL
                table structures, installation instructions, free technical support at
                the <a href=http://www.tufat.com/phpBB2/ target="support">TUFaT.com support
                    forum</a>.
            </td>
        </tr>
        <tr>
            <td class=small colspan=2>Minimum requirements: PHP 4.1.2, MySQL 3.23, Flash
                6. You do NOT need Flash Communication Server or any other server components.
            </td>
        </tr>
        <tr>
            <td class=small colspan=2>FlashChat is the copyright of Darren Gates and
                TUFaT.com. Re-sale of FlashChat is strictly prohibited. The purpose of
                FlashChat is to give companies, organizations, and individuals a simple
                way to add live chat capabilities to any PHP/MySQL-enabled website. Technical
                support is available on the TUFaT.com support forum, and upgrades are
                free.
            </td>
        </tr>
    </form>
</table>
</body>
</html>

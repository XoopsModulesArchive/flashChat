<?php

require __DIR__ . '/inc/common.php';

$cmsclass = get_class($GLOBALS['config']['cms']);
$manageUsers = ('defaultcms' == $cmsclass) || ('statelesscms' == $cmsclass);

function process()
{
    global $cmsclass, $manageUsers;

    //Check provided values

    if (!$_REQUEST['dbhost']) {
        return 'Please fill in database host name';
    }

    if (!$_REQUEST['dbuser']) {
        return 'Please fill in database login';
    }

    //		if(!$_REQUEST['dbpass']) return 'Please fill in database password';

    if (!$_REQUEST['dbbase']) {
        return 'Please fill in database name';
    }

    if (!$_REQUEST['rooms']) {
        return 'You must create at least one room';
    }

    //Try to connect to MySQL

    if ($conn = @mysql_pconnect($_REQUEST['dbhost'], $_REQUEST['dbuser'], $_REQUEST['dbpass'])) {
        //Try to select target database

        if (mysqli_select_db($GLOBALS['xoopsDB']->conn, $_REQUEST['dbbase'], $conn)) {
            //Write the system configuration

            //$filename = dirname($_SERVER['SCRIPT_FILENAME']) . '/inc/config.srv.php';

            $filename = 'inc/config.srv.php';

            if ($handle = fopen($filename, 'w+b')) {
                $str = "<?php\n";

                $str .= "\t\$GLOBALS['config']['db'] = array(\n";

                $str .= "\t\t'host' => '{$_REQUEST['dbhost']}',\n";

                $str .= "\t\t'user' => '{$_REQUEST['dbuser']}',\n";

                $str .= "\t\t'pass' => '{$_REQUEST['dbpass']}',\n";

                $str .= "\t\t'base' => '{$_REQUEST['dbbase']}',\n";

                $str .= "\t\t'pref' => '{$_REQUEST['dbpref']}',\n";

                $str .= "\t);\n";

                $str .= '?>';

                if (fwrite($handle, $str)) {
                    fclose($handle);
                } else {
                    return "<b>Could not write to '$filename' file</b>";
                }
            } else {
                return "<b>Could not open '$filename' file for writing</b>";
            }

            //Create DB tables

            $tables = [
                "CREATE TABLE {$_REQUEST['dbpref']}bans (created timestamp(6) NOT NULL, userid int(11) default NULL, banneduserid int(11) default NULL, roomid int(11) default NULL, ip varchar(16) default NULL, INDEX(userid), INDEX(created))",
                "CREATE TABLE {$_REQUEST['dbpref']}connections (id varchar(32) NOT NULL default '', updated timestamp(6) NOT NULL, created timestamp(6) NOT NULL, userid int(11) default NULL, roomid int(11) default NULL, state tinyint(4) NOT NULL default '1', color int(11) default NULL, start int(11) default NULL, lang char(2) default NULL, ip varchar(16) default NULL, tzoffset int(11) default 0, INDEX(userid), INDEX(roomid), INDEX(updated), PRIMARY KEY (id))",
                "CREATE TABLE {$_REQUEST['dbpref']}ignors (created timestamp(6) NOT NULL, userid int(11) default NULL, ignoreduserid int(11) default NULL, INDEX(userid), INDEX(ignoreduserid), INDEX(created))",
                "CREATE TABLE {$_REQUEST['dbpref']}messages (id int(11) NOT NULL auto_increment, created timestamp(6) NOT NULL, toconnid varchar(32) default NULL, touserid int(11) default NULL, toroomid int(11) default NULL, command varchar(255) NOT NULL default '', userid int(11) default NULL, roomid int(11) default NULL, txt text, INDEX(touserid), INDEX(toroomid), INDEX(toconnid), INDEX(created), PRIMARY KEY (id))",
                "CREATE TABLE {$_REQUEST['dbpref']}rooms (id int(11) NOT NULL auto_increment, updated timestamp(6) NOT NULL, created timestamp(6) NOT NULL, name varchar(32) NOT NULL default '', ispublic char(1) default NULL, ispermanent char(1) default NULL, INDEX(name), INDEX(ispublic), INDEX(ispermanent), INDEX(updated), PRIMARY KEY (id))",
            ];

            if ($manageUsers) {
                $tables[] = "CREATE TABLE {$_REQUEST['dbpref']}users (id int(11) NOT NULL auto_increment, login varchar(32) NOT NULL default '', password varchar(32) NOT NULL default '', roles int(11) NOT NULL default '0', INDEX(login), PRIMARY KEY  (id))";
            }

            foreach ($tables as $str) {
                if (false === $GLOBALS['xoopsDB']->queryF($str, $conn)) {
                    return '<b>Could not create DB tables</b><br>' . $GLOBALS['xoopsDB']->error();
                }
            }

            //Create chat rooms

            $error = '';

            $rms = preg_split(',\W*', $_REQUEST['rooms']);

            for ($i = 0, $iMax = count($rms); $i < $iMax; $i++) {
                if (!$GLOBALS['xoopsDB']->queryF("INSERT INTO {$_REQUEST['dbpref']}rooms (created, name, ispublic, ispermanent) VALUES (NOW(), '{$rms[$i]}', 'y', '" . ($i + 1) . "')", $conn)) {
                    $error .= "<b>Could not create room '{$rms[$i]}'</b><br>";
                }
            }

            return $error;
        }

        return "<b>Could not select '{$_REQUEST['dbbase']}' database - please make sure this database exists</b><br>" . $GLOBALS['xoopsDB']->error();
    }

    return '<b>Could not connect to MySQL database - please check database settings</b><br>' . $GLOBALS['xoopsDB']->error();
}

if (isset($_REQUEST['do'])) {
    $error = process();
} else {
    switch ($cmsclass) {
        case 'phpnukecms':
            if (file_exists(__DIR__ . '/../../../config.php')) {
                require dirname(__DIR__, 3) . '/config.php';
            } else {
                require dirname(__DIR__, 2) . '/config.php';
            }

            $_REQUEST['dbhost'] = $dbhost;
            $_REQUEST['dbuser'] = $dbuname;
            $_REQUEST['dbpass'] = $dbpass;
            $_REQUEST['dbbase'] = $dbname;
            $_REQUEST['dbpref'] = $prefix . '_';
            break;
        default:
            $_REQUEST['dbhost'] = $GLOBALS['config']['db']['host'];
            $_REQUEST['dbuser'] = $GLOBALS['config']['db']['user'];
            $_REQUEST['dbpass'] = $GLOBALS['config']['db']['pass'];
            $_REQUEST['dbbase'] = $GLOBALS['config']['db']['base'];
            $_REQUEST['dbpref'] = $GLOBALS['config']['db']['pref'];
    }

    $_REQUEST['rooms'] = 'The Lounge, Hollywood, Tech Talk, Current Events';

    $error = ' ';
}
?>
<html>
<head>
    <title>FlashChat Installation</title>
    <meta http-equiv=Content-Type content="text/html;  charset=UTF-8">

    <style type="text/css">
        <!--
        .title {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 18px;
            font-weight: bold;
        }

        .normal {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 11px;
        }

        .subtitle {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 12px;
            font-weight: bold;
        }

        .welcome {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 24px;
            font-weight: bold;
        }

        -->
    </style>
</head>

<body>
<center>
    <?php if ($error) { ?>
        <?php if (!isset($_REQUEST['do'])) { ?>
            <table width="500" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td>
                        <p align="center" class="title">Welcome to the FlashChat Installation</p>
                        <p align="center" class="subtitle"><b>Before you start:</b></p>
                        <ul>
                            <li class="normal">Please make sure the target database exists
                            <li class="normal">The databaes user has rights to create database tables and write to them
                            <li class="normal">Script can write system configuration to inc/config.srv.php file
                        </ul>
                    </td>
                </tr>
            </table>
            <br>
        <?php } ?>
        <font color="red"><?= $error ?></font>
        <form action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="post">
            <input type="hidden" name="do" value="1">
            <table width="500" border="0">
                <tr>
                    <th colspan="2" class="subtitle">Database connection settings:</th>
                </tr>
                <tr>
                    <td align="right" class="normal">Host:</td>
                    <td><input name="dbhost" type="text" class="normal" value="<?= $_REQUEST['dbhost'] ?>" size="30"></td>
                </tr>
                <tr>
                    <td align="right" class="normal">Login:</td>
                    <td><input name="dbuser" type="text" class="normal" value="<?= $_REQUEST['dbuser'] ?>" size="30"></td>
                </tr>
                <tr>
                    <td align="right" class="normal">Password:</td>
                    <td><input name="dbpass" type="text" class="normal" value="<?= $_REQUEST['dbpass'] ?>" size="30"></td>
                </tr>
                <tr>
                    <td align="right" class="normal">Database Name:</td>
                    <td><input name="dbbase" type="text" class="normal" value="<?= $_REQUEST['dbbase'] ?>" size="30"></td>
                </tr>
                <tr>
                    <td align="right" class="normal">Table Prefix:</td>
                    <td><input name="dbpref" type="text" class="normal" value="<?= $_REQUEST['dbpref'] ?>" size="30"></td>
                </tr>
                <tr>
                    <th colspan="2"><br><span class="subtitle">Room List (separated by commas):</span></th>
                </tr>
                <tr>
                    <td align="right"><span class="normal">Rooms</span>:</td>
                    <td><input name="rooms" type="text" class="normal" value="<?= $_REQUEST['rooms'] ?>" size="60"></td>
                </tr>
                <tr>
                    <td colspan="2" align="center"><br><input name="submit" type="submit" class="normal" value="Go!"></td>
                </tr>
            </table>
        </form>
    <?php } else { ?>
        <?php
        $link = 'index.php';
        switch ($cmsclass) {
            case 'phpnukecms':
                $link = '../../modules.php?name=FlashChat';
                break;
        }
        ?>
        <span class="normal">FlashChat was successfully installed - <a href="<?= $link ?>">click here to start the chat</a></span>
    <?php } ?>
</center>
</body>
</html>

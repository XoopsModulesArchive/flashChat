<?php

$cmsclass = get_class($GLOBALS['config']['cms']);
$manageUsers = ('defaultcms' == $cmsclass) || ('statelesscms' == $cmsclass);
?>
<html>
<head>
    <title>FlashChat Admin Panel</title>
    <meta http-equiv=Content-Type content="text/html;  charset=UTF-8">
</head>

<style type="text/css">
    <!--
    BODY {
        font-family: Verdana, Arial, Helvetica, sans-serif;
        font-size: 12px;
    }

    TD {
        font-family: Verdana, Arial, Helvetica, sans-serif;
        font-size: 12px;
    }

    TH {
        font-family: Verdana, Arial, Helvetica, sans-serif;
        font-size: 12px;
        font-weight: bold;
    }

    INPUT {
        font-family: Verdana, Arial, Helvetica, sans-serif;
        font-size: 12px;
    }

    SELECT {
        font-family: Verdana, Arial, Helvetica, sans-serif;
        font-size: 12px;
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

<body>
<center>
    <a href="admin.php">Home</a> |
    <a href="msglist.php">Messages</a> |
    <?php if ($manageUsers) { ?><a href="usrlist.php">Users</a> | <?php } ?>
    <a href="roomlist.php">Rooms</a> |
    <a href="logout.php">Logout</a>
</center>
<HR>

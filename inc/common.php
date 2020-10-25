<?php

header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

define('INC_DIR', __DIR__ . '/');

define('SPY_USERID', -1);

define('FONT_SMALL', 11);
define('FONT_NORMAL', 12);
define('FONT_BIG', 14);
define('FONT_LARGE', 14);

define('ROLE_NOBODY', 0);
define('ROLE_USER', 1);
define('ROLE_ADMIN', 2);
define('ROLE_SPY', 4);
define('ROLE_CUSTOMER', 8);
define('ROLE_ANY', -1);

define('BAN_BYROOMID', 1);
define('BAN_BYUSERID', 2);
define('BAN_BYIP', 3);

define('TIMESTAMP_BEFORE', 0);
define('TIMESTAMP_AFTER', 1);
define('TIMESTAMP_NORMAL', 0);
define('TIMESTAMP_MILITARY', 1);

require_once INC_DIR . 'config.php';
require_once INC_DIR . 'config.srv.php';

require_once INC_DIR . 'badwords.php';

require_once INC_DIR . 'layouts/admin.php';
require_once INC_DIR . 'layouts/spy.php';
require_once INC_DIR . 'layouts/user.php';
require_once INC_DIR . 'layouts/customer.php';

require_once INC_DIR . 'classes/db.php';
require_once INC_DIR . 'classes/messageQueue.php';
require_once INC_DIR . 'classes/message.php';
require_once INC_DIR . 'classes/connection.php';
require_once INC_DIR . 'classes/chatServer.php';

require_once INC_DIR . 'cmses/statelessCMS.php';
//require_once INC_DIR . 'cmses/defaultCMS.php';
//require_once INC_DIR . 'cmses/phpNukeCMS.php';
//require_once INC_DIR . 'cmses/postNukeCMS.php';
require_once INC_DIR . 'cmses/xoopsCMS.php';

$_REQUEST['errors'] = '';

function addError($error)
{
    $_REQUEST['errors'] .= "<error><![CDATA[{$error}]]></error>";
}

function getErrors()
{
    return $_REQUEST['errors'];
}

function htmlColor($color)
{
    return sprintf('#%06X', $color);
}

function flashTag($id, $movie, $width = '100%', $height = '100%', $params = [])
{
    $flashVars = [];

    foreach ($params as $name => $value) {
        if ($value) {
            $flashVars[] = "$name=" . urlencode($value);
        }
    }

    $fv = implode('&', $flashVars);

    $tag = "<OBJECT classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\" WIDTH=\"$width\" HEIGHT=\"$height\"  id=\"$id\" ALIGN=\"\">";

    $tag .= "<PARAM NAME=\"FlashVars\" VALUE=\"$fv\">";

    $tag .= "<PARAM NAME=\"movie\" VALUE=\"$movie\">";

    $tag .= '<PARAM NAME="quality" VALUE="high">';

    $tag .= '<PARAM NAME="menu" VALUE="false">';

    $tag .= '<PARAM NAME="scale" VALUE="noscale">';

    $tag .= '<PARAM NAME="salign" VALUE="LT">';

    $tag .= "<EMBED src=\"$movie\" FlashVars=\"$fv\" menu=\"false\" quality=\"high\" scale=\"noscale\" salign=\"LT\" WIDTH=\"$width\" HEIGHT=\"$height\" swLiveConnect=\"true\" NAME=\"$id\" ID=\"$id\" ALIGN=\"\" TYPE=\"application/x-shockwave-flash\" PLUGINSPAGE=\"http://www.macromedia.com/go/getflashplayer\"></EMBED>";

    $tag .= '</OBJECT>';

    return $tag;
}

function convert_timestamp($timestamp, $timezoneOffset = 0)
{
    return $timestamp ? mktime(
        mb_substr($timestamp, 8, 2),
        mb_substr($timestamp, 10, 2) - $timezoneOffset,
        mb_substr($timestamp, 12, 2),
        mb_substr($timestamp, 4, 2),
        mb_substr($timestamp, 6, 2),
        mb_substr($timestamp, 0, 4)
    ) : 0;
}

function format_Timestamp($timestamp, $tzoffset)
{
    return gmdate($GLOBALS['config']['timeStampFormat'], convert_timestamp($timestamp, $tzoffset));
}

// check for Turck MMCache
if (function_exists('mmcache_rm_page')) {
    mmcache_rm_page($_SERVER['PHP_SELF'] . '?GET=' . serialize($_GET));
}

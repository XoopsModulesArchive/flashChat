<?php

require dirname(__DIR__, 2) . '/mainfile.php';

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

    $tag .= "<EMBED src=\"$movie\" FlashVars=\"$fv\" menu=\"false\" quality=\"high\" scale=\"noscale\" salign=\"LT\" WIDTH=\"$width\" HEIGHT=\"$height\" NAME=\"$id\" ALIGN=\"\" TYPE=\"application/x-shockwave-flash\" PLUGINSPAGE=\"http://www.macromedia.com/go/getflashplayer\"></EMBED>";

    $tag .= '</OBJECT>';

    return $tag;
}

$id = 'flashchat';

$params = [];

// We must always set our main template before including the header
$GLOBALS['xoopsOption']['template_main'] = 'main.html';

// Include the page header
require XOOPS_ROOT_PATH . '/header.php';

$xoopsTpl->assign('flashTag', flashTag($id, 'preloader.swf', '650', '450', $params));

// Include the page footer
require XOOPS_ROOT_PATH . '/footer.php';

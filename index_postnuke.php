<?php

if (!defined('LOADED_AS_MODULE')) {
    die("You can't access this file directly...");
}

$module_name = basename(__DIR__);

$index = 0;

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

    $tag .= '<PARAM NAME="BASE" VALUE="modules/FlashChat">';

    $tag .= "<EMBED src=\"$movie\" FlashVars=\"$fv\" menu=\"false\" quality=\"high\" scale=\"noscale\" salign=\"LT\" WIDTH=\"$width\" HEIGHT=\"$height\" NAME=\"$id\" ALIGN=\"\" TYPE=\"application/x-shockwave-flash\" PLUGINSPAGE=\"http://www.macromedia.com/go/getflashplayer\" BASE=\"modules/FlashChat\"></EMBED>";

    $tag .= '</OBJECT>';

    return $tag;
}

$id = 'flashchat';

$params = [];

include 'header.php';
?>
    <center><?= flashTag($id, 'modules/FlashChat/preloader.swf', '650', '450', $params) ?></center>
    <script type="text/javascript" src="modules/FlashChat/js.php"></script>
    <script type="text/javascript">setFocus();
        window.onunload = doLogout;</script>
<?php
include 'footer.php';
?>

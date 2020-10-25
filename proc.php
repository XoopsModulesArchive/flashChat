<?php

error_reporting(0);

require_once __DIR__ . '/inc/common.php';

header('Pragma: public');
header('Expires: 0');
header('Content-type: text/xml');
//header('Content-type: text/plain');

ChatServer::purgeExpired();

$conn = &ChatServer::getConnection($_REQUEST);
$mqi = $conn->process($_REQUEST);
?>
<response id="<?= $conn->id ?>">
    <?php
    while ($mqi->hasNext()) {
        $m = $mqi->next();

        echo($m->toXML($conn->tzoffset));
    }
    ?>
</response>

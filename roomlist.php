<?php

require_once __DIR__ . '/inc/common.php';

session_start();

if (!isset($_SESSION['userid'])) {
    header('Refresh: 0; URL=login.php');

    exit;
}

function updatePriority($id, $order)
{
    $stmt = new Statement("SELECT * FROM {$GLOBALS['config']['db']['pref']}rooms WHERE id=?");

    if (($rs = $stmt->process($id)) && ($rec = $rs->next())) {
        $min = min($rec['ispermanent'], $order);

        $max = max($rec['ispermanent'], $order);

        $stmt = new Statement("SELECT * FROM {$GLOBALS['config']['db']['pref']}rooms WHERE ispermanent IS NOT NULL AND ispermanent BETWEEN ? AND ?  AND id <> ? ORDER BY ispermanent");

        $rs = $stmt->process($min, $max, $id);

        if ($order == $min) {
            $min++;
        }

        $save = new Statement("UPDATE {$GLOBALS['config']['db']['pref']}rooms SET ispermanent=? WHERE id=?");

        while ($r = $rs->next()) {
            $r['ispermanent'] = $min++;

            $save->process($r['ispermanent'], $r['id']);
        }

        $rec['ispermanent'] = $order;

        $save->process($rec['ispermanent'], $rec['id']);
    }
}

if (isset($_REQUEST['id']) && isset($_REQUEST['order'])) {
    updatePriority($_REQUEST['id'], $_REQUEST['order']);
}

$stmt = new Statement("SELECT count(*) as maxnumb FROM {$GLOBALS['config']['db']['pref']}rooms WHERE ispermanent IS NOT NULL");
if (($rs = $stmt->process()) && ($rec = $rs->next())) {
    $maxnumb = $rec['maxnumb'];
}

$stmt = new Statement("SELECT * FROM {$GLOBALS['config']['db']['pref']}rooms ORDER BY ispermanent");
$rs = $stmt->process();

require __DIR__ . '/inc/top.php';
?>
    <center>
        <h4>Rooms</h4>
        <a href="room.php">Add new room</a><br>
        <br>
        <?php if ($rs->hasNext()) { ?>
        <table border="1">
            <tr>
                <th>id</th>
                <th>name</th>
                <th>public</th>
                <th>permanent</th>
                <th>#</th>
            </tr>
            <?php while ($rec = $rs->next()) { ?>
                <tr>
                    <td><?= $rec['id'] ?></td>
                    <td><a href="room.php?id=<?= $rec['id'] ?>"><?= $rec['name'] ?></a></td>
                    <td align="center"><?= $rec['ispublic'] ? 'y' : 'n' ?></td>
                    <td align="center"><?= $rec['ispermanent'] ? 'y' : 'n' ?></td>
                    <?php if ($rec['ispermanent']) { ?>
                        <form action="<?= $SCRIPT_NAME ?>" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?= $rec['id'] ?>">
                            <td>
                                <select name="order" onChange="this.form.submit()">
                                    <?php for ($i = 1; $i <= $maxnumb; $i++) { ?>
                                        <option value="<?= $i ?>" <?php if ($i == $rec['ispermanent']) {
    echo('selected');
} ?>><?= $i ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </form>
                    <?php } else { ?>
                        <td></td>
                    <?php } ?>
                </tr>
            <?php } ?>
            <?php } else { ?>
                No rooms found
            <?php } ?>
    </center>
<?php
require __DIR__ . '/inc/bot.php';
?>

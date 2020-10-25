<?php

error_reporting(0);

require_once __DIR__ . '/inc/common.php';

header('Pragma: public');
header('Expires: 0');
header('Content-type: text/xml');
//header('Content-type: text/plain');

function array2attrs($arr)
{
    $ret = '';

    foreach ($arr as $k => $v) {
        if (!is_array($v)) {
            $ret .= " $k=\"$v\"";
        }
    }

    return $ret;
}

?>
<settings
        version="<?= $GLOBALS['config']['version'] ?>"
        liveSupportMode="<?= $GLOBALS['config']['liveSupportMode'] ?>"
        fontSize="<?= $GLOBALS['config']['fontSize'] ?>"
        showTimeStamp="<?= $GLOBALS['config']['showTimeStamp'] ?>"
        timeStampPosition="<?= $GLOBALS['config']['timeStampPosition'] ?>"
        maxMessageSize="<?= $GLOBALS['config']['maxMessageSize'] ?>"
        helpUrl="<?= $GLOBALS['config']['helpUrl'] ?>"
        isAdminMode="<?= $GLOBALS['config']['cms']->userInRole($GLOBALS['config']['cms']->isLoggedIn(), ROLE_ADMIN) ?>"
        msgRequestInterval="<?= $GLOBALS['config']['msgRequestInterval'] ?>"
        defaultTheme="<?= $GLOBALS['config']['defaultTheme'] ?>"
        defaultLanguage="<?= $GLOBALS['config']['defaultLanguage'] ?>">

    <?php foreach ($GLOBALS['config']['layouts'] as $k => $v) { ?>
        <layout role="<?= $k ?>" <?= array2attrs($v) ?>>
            <?php foreach ($v['constraints'] as $ck => $cv) { ?>
                <constraint id="<?= $ck ?>" <?= array2attrs($cv) ?>>
            <?php } ?>
        </layout>
    <?php } ?>
    <smiles <?= array2attrs($GLOBALS['config']['smiles']) ?>>
    <sound <?= array2attrs($GLOBALS['config']['sound']) ?>>
    <?php foreach ($GLOBALS['config']['themes'] as $k => $v) { ?>
        <theme id="<?= $k ?>" <?= array2attrs($v) ?>>
    <?php } ?>
    <?php foreach ($GLOBALS['config']['languages'] as $k => $v) { ?>
        <language id="<?= $k ?>" name="<?= $v['name'] ?>">
            <messages <?= array2attrs($v['messages']) ?>>
            <desktop <?= array2attrs($v['desktop']) ?>>
            <?php foreach ($v['dialog'] as $dk => $dv) { ?>
                <dialog id="<?= $dk ?>" <?= array2attrs($dv) ?>>
            <?php } ?>
            <status <?= array2attrs($v['status']) ?>>
            <usermenu <?= array2attrs($v['usermenu']) ?>>
        </language>
    <?php } ?>
</settings>

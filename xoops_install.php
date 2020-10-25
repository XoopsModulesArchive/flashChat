<?php

//require dirname(__DIR__, 2) . '/mainfile.php';

function xoops_module_install_flashChat($module)
{
    $gpermHandler = xoops_getHandler('groupperm');

    $mperm = $gpermHandler->create();

    $mperm->setVar('gperm_groupid', XOOPS_GROUP_ANONYMOUS);

    $mperm->setVar('gperm_itemid', $module->getVar('mid'));

    $mperm->setVar('gperm_name', 'module_read');

    $mperm->setVar('gperm_modid', 1);

    return $gpermHandler->insert($mperm);
}

<?php

// Any copyright notice, instructions, etc...
$modversion['name'] = 'FlashChat';
$modversion['version'] = 3.8;
$modversion['description'] = 'Provide chat rooms on the site';
$modversion['credits'] = 'http://www.tufat.com/';
$modversion['author'] = 'Darren Gates';
$modversion['official'] = 0;
$modversion['dirname'] = 'flashChat';
$modversion['image'] = 'flashChat_slogo.png';

$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';

$modversion['tables'][0] = 'bans';
$modversion['tables'][1] = 'connections';
$modversion['tables'][2] = 'ignors';
$modversion['tables'][3] = 'messages';
$modversion['tables'][4] = 'rooms';

// Admin
$modversion['hasAdmin'] = 0;
$modversion['adminmenu'] = '';

// Menu
$modversion['hasMain'] = 1;

// Templates
$modversion['templates'][1]['file'] = 'main.html';
$modversion['templates'][1]['description'] = 'FlashChat module template';

$modversion['onInstall'] = 'xoops_install.php';

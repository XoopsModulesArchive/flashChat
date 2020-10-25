<?php

$GLOBALS['config']['layouts'][ROLE_USER] = [
    'allowBan' => false,
'allowInvite' => true,
'allowIgnore' => true,
'allowProfile' => false,
'allowCreateRoom' => true,
'showOptionPanel' => true,
'showInputBox' => true,
'showPrivateLog' => true,
'showPublicLog' => true,
'showUserList' => true,
'showLogout' => true,
'isSingleRoomMode' => false, //if true room drop-down is visible

'allowPrivateMessage' => true,
'showAddressee' => true,

    'opShowStatus' => true,
'opShowSkin' => true,
'opShowSound' => true,
'opShowUserColor' => true,
'opShowSave' => true,
'opShowHelp' => true,
'opShowSmiles' => true,
'opShowBell' => false,

    // UI config

'constraints' => [
        'userList' => [
            'minWidth' => 50,    //minimal width of user list view, pixels

'width' => -1,       //exact width of userlist, pixels

'relWidth' => 30,    //relative width of userlist, percents
        ],
'publicLog' => [
            'minHeight' => 35,   //minimal height of public log, pixels

'height' => -1,      //exact height of public log, pixels

'relHeight' => 66,   //relative height of public log, percents
        ],
'privateLog' => [
            'minHeight' => 35,
'height' => -1,
'relHeight' => 25,
        ],
'inputBox' => [
            'minHeight' => 35,
'height' => -1,
'relHeight' => 8,
        ],
    ],
];

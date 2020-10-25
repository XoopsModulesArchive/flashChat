<?php

$GLOBALS['config']['layouts'][ROLE_SPY] = [
    'allowBan' => false,
'allowInvite' => false,
'allowIgnore' => false,
'allowProfile' => false,
'allowCreateRoom' => false,
'showOptionPanel' => false,
'showInputBox' => false,
'showPrivateLog' => false,
'showPublicLog' => true,
'showUserList' => true,
'showLogout' => true,
'isSingleRoomMode' => false, //if true room drop-down is visible

'allowPrivateMessage' => false,
'showAddressee' => true,

    'opShowStatus' => false,
'opShowSkin' => false,
'opShowSound' => false,
'opShowUserColor' => false,
'opShowSave' => false,
'opShowHelp' => false,
'opShowSmiles' => false,
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

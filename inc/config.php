<?php

$GLOBALS['config'] = [
    'version' => '3.8',
'liveSupportMode' => false,    //set to true to use chat in "Live Support" mode

'fontSize' => FONT_NORMAL,    //FONT_SMALL, FONT_NORMAL, or FONT_BIG

    'showTimeStamp' => true,    //set to true to show the time stamp with messages

'timeStampPosition' => TIMESTAMP_AFTER, //TIMESTAMP_BEFORE, TIMESTAMP_AFTER

'timeStampFormat' => 'g:i a', //pattern for PHP date function

    'maxMessageSize' => 500,    //maximum input text size, # characters

    'showLogoutWindow' => true,     // if false, then use only the ..src=lgout.php method, but do not use the popup method at all

'logoutWindowDisplayTime' => 3, // in seconds

    //Rooms config

'defaultRoom' => 1,          //primary key of room where all users go after login

'autoremoveAfter' => 300,    //number of seconds spent before room be removed

    // Roles config

'adminPassword' => 'adminpass', //allows any user login as admin

'spyPassword' => 'spypass',     //allows any user login as spy

    'layouts' => [],    // do not change this

    //Sound config

'sound' => [
        'pan' => 0,                // range from -100 to 100 (left ... right)

'volume' => 60,            // default sound volume, in percent

'muteAll' => false,        // true = checked by default, false = unchecked

'muteSubmitMessage' => false,
'muteReceiveMessage' => false,
'muteOtherUserEnters' => true,
'muteLeaveRoom' => true,
    ],

    //Themes config

'themes' => [],
'defaultTheme' => 'navy',

    //Smile settings
    //To disable any smilie, change appropriate value from true to false

'smiles' => [
        'smi_fuck' => false,
'smi_evil' => true,
'smi_kiss' => true,
'smi_smile' => true,
'smi_wink' => true,
'smi_laugh' => true,
'smi_cool' => true,
'smi_heart' => true,
'smi_sad' => true,
'smi_ask' => true,
'smi_slash' => true,
'smi_post_it' => true,
'smi_tongue' => true,
'smi_awe' => true,
'smi_baby' => true,
'smi_grin' => true,
'smi_newline' => true,
'smi_ninja' => true,
'smi_red' => true,
'smi_roll' => true,
'smi_roll_eyes' => true,
'smi_shocked' => true,
'smi_sleep' => true,
'smi_weird' => true,
'smi_whistle' => true,
    ],

    //Message processing

'msgRequestInterval' => 3,    //chat refresh time, seconds

'msgRemoveAfter' => 3600,    //messages removed after this time, seconds

    //Connection processing

'autologoutAfter' => 60,  //time of pooling inactivity after which user is considered logged off, seconds

'autocloseAfter' => 3600, //time of pooling inactivity after which connection is removed from database, seconds

'helpUrl' => 'help.php',

    //Ban processing

'autounbanAfter' => 36000,  //time after user became un-banned, seconds

    //Language options

'languages' => [],        //do not change this

'defaultLanguage' => 'en',    //two-letter code of the default language (see below)
];

//THEMES: To disable a theme, comment or delete the appropriate line

require_once INC_DIR . 'themes/navy.php';
require_once INC_DIR . 'themes/metallic.php';
require_once INC_DIR . 'themes/tropical.php';
//require_once INC_DIR . 'themes/ivory.php';
require_once INC_DIR . 'themes/aqua.php';
require_once INC_DIR . 'themes/olive.php';
require_once INC_DIR . 'themes/pink.php';
require_once INC_DIR . 'themes/oak.php';
require_once INC_DIR . 'themes/black.php';

//LANGUAGES: To disable a language, comment or delete the appropriate line

require_once INC_DIR . 'langs/en.php'; //English
require_once INC_DIR . 'langs/ro.php'; //Romanian
require_once INC_DIR . 'langs/pl.php'; //Polish
require_once INC_DIR . 'langs/ru.php'; //Russian
require_once INC_DIR . 'langs/it.php'; //Italian
require_once INC_DIR . 'langs/fr.php'; //French
require_once INC_DIR . 'langs/du.php'; //Dutch
require_once INC_DIR . 'langs/no.php'; //Norweigan
require_once INC_DIR . 'langs/hu.php'; //Hungarian
require_once INC_DIR . 'langs/sv.php';    //Sweedish
//	require_once INC_DIR . 'langs/ar.php';	//Arabic (not working yet)
//	require_once INC_DIR . 'langs/gr.php';	//Greek (not working yet)
require_once INC_DIR . 'langs/pt.php';    //Portuguese
require_once INC_DIR . 'langs/gm.php';    //German
require_once INC_DIR . 'langs/sp.php'; //Spanish
require_once INC_DIR . 'langs/tr.php'; //Turkish
//	require_once INC_DIR . 'langs/hi.php'; //Hindi (not working yet)
require_once INC_DIR . 'langs/fi.php'; //Finnish
require_once INC_DIR . 'langs/lt.php';    //Lithuanian
require_once INC_DIR . 'langs/sk.php'; //Slovak
require_once INC_DIR . 'langs/cz.php'; //Czech
require_once INC_DIR . 'langs/bg.php'; //Belgian
require_once INC_DIR . 'langs/kl.php'; //Klingon

// more languages to come!

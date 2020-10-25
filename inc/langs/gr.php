<?php

$GLOBALS['config']['languages']['gr'] = [
    'name' => 'Ελληνικά',

    'messages' => [
        'login' => 'Παρακαλώ συνδεθείτε στο chat',
'wrongPass' => 'Το όνομα του χρήστη ή ο κωδικός δεν ήταν σωστός. Παρακαλώ δοκιμάστε ξανά.',
'anotherlogin' => 'Άλλος χρήστης είναι συνδεδεμένος με αυτό το όνομα. Παρακαλώ δοκιμάστε ξανά.',
'expiredlogin' => 'H σύνδεση σας έχει λήξει. Παρακαλώ συνδεθείτε ξανά.',
'enterroom' => '[ROOM_LABEL]: Ο/Η USER_LABEL ήρθε στις TIMESTAMP',
'leaveroom' => '[ROOM_LABEL]: Ο/Η USER_LABEL έφυγε στις TIMESTAMP',
'selfenterroom' => 'Καλώς ορίσατε! Ήρθατε στο δωμάτιο [ROOM_LABEL] στις TIMESTAMP',
    ],

    'usermenu' => [
        'profile' => 'Λήψη προφίλ',
'unban' => 'Αναίρεση απαγόρευσης',
'ban' => 'Απαγόρευση',
'unignore' => 'Αναίρεση παράβλεψης',
'ignore' => 'Παράβλεψη',
'invite' => 'Πρόσκληση',
'privatemessage' => 'Προσωπικό μήνυμα',
    ],

    'status' => [
        'away' => 'Μακριά',
'busy' => 'Απασχολημένος',
'here' => 'Διαθέσιμος',
    ],

    'dialog' => [
        'misc' => [
            'usernotfound' => "Ο χρήστης 'USER_LABEL' δεν βρέθηκε",
'unbanned' => "Η απαγόρευση εισόδου σας, αναιρέθηκε από τον χρήστη 'USER_LABEL'",
'banned' => "Σας απαγορεύτηκε η είσοδος από τον χρήστη 'USER_LABEL'",
'unignored' => "Ο χρήστης 'USER_LABEL' αναίρεσε την παράβλεψη των μηνυμάτων σας.",
'ignored' => "Τα μηνύματα σας παραβλέπονται από τον χρήστη 'USER_LABEL'",
'invitationdeclined' => "Ο χρήστης 'USER_LABEL' απέρριψε την πρόσκληση σας για το δωμάτιο 'ROOM_LABEL':",
'invitationaccepted' => "Ο χρήστης 'USER_LABEL' δέχτηκε την πρόσκληση σας για το δωμάτιο 'ROOM_LABEL'",
'roomnotcreated' => 'Το δωμάτιο δεν δημιουργήθηκε:',
        ],

        'unignore' => [
            'unignoreBtn' => 'Αναίρεση παράβλεψης',
'unignoretext' => 'Εισάγετε το κείμενο της αναίρεσης παράβλεψης',
        ],

        'unban' => [
            'unbanBtn' => 'Αναίρεση απαγόρευσης',
'unbantext' => 'Εισάγετε το κείμενο της αναίρεσης απαγόρευσης',
        ],

        'sound' => [
            'sampleBtn' => 'Δείγμα',
'testBtn' => 'Δοκιμή',
'muteall' => 'Σίγαση όλων',
'submitmessage' => 'Αποστολή μηνύματος',
'reveivemessage' => 'Λήψη μηνύματος',
'enterroom' => 'Είσοδος σε δωμάτιο',
'leaveroom' => 'Αποχώρηση από δωμάτιο',
'pan' => 'Μπαλανς',
'volume' => 'Ένταση',
        ],

        'skin' => [
            'inputBoxBackground' => 'Φόντο στο χώρο πληκτρολόγησης των μηνυμάτων',
'privateLogBackground' => 'Φόντο στο χώρο των ιδιωτικών μηνυμάτων',
'publicLogBackground' => 'Φόντο στο χώρο των δημόσιων μηνυμάτων',
'enterRoomNotify' => 'Ανακοίνωση εισόδου σε δωμάτιο',
'roomText' => 'Κείμενο δωματίων',
'room' => 'Φόντο δωματίων',
'userListBackground' => 'Φόντο του καταλόγου των χρηστών',
'dialogTitle' => 'Τίτλοι διαλόγων',
'dialog' => 'Φόντο διαλόγων',
'buttonText' => 'Κείμενο κουμπιών',
'button' => 'Φόντο κουμπιών',
'bodyText' => 'Κυρίως κείμενο',
'background' => 'Κεντρικό φόντο',
        ],

        'login' => [
            'loginBtn' => 'Σύνδεση',
'language' => 'Language:',
'moderator' => '(Μόνο για υπερχρήστες)',
'password' => 'Κωδικός πρόσβασης:',
'username' => 'Όνομα χρήστη:',
        ],

        'invitenotify' => [
            'declineBtn' => 'Άρνηση',
'acceptBtn' => 'Αποδοχή',
'userinvited' => "Ο χρήστης 'USER_LABEL' σας προσκάλεσε στο δωμάτιο 'ROOM_LABEL'",
        ],

        'invite' => [
            'sendBtn' => 'Αποστολή',
'includemessage' => 'Κείμενο πρόσκλησης:',
'inviteto' => 'Προσκαλέστε τον χρήστη στο:',
        ],

        'ignore' => [
            'ignoreBtn' => 'Παράβλεψη',
'ignoretext' => 'Εισάγετε το κείμενο παράβλεψης',
        ],

        'createroom' => [
            'createBtn' => 'Δημιουργία',
'private' => 'Ιδιωτικό',
'public' => 'Κοινόχρηστο',
'entername' => 'Εισάγετε το όνομα του δωματίου',
        ],

        'ban' => [
            'banBtn' => 'Απαγόρευση',
'byIP' => 'με IP',
'fromChat' => 'από το chat',
'fromRoom' => 'από το δωμάτιο',
'banText' => 'Εισάγετε το κείμενο απαγόρευσης',
        ],

        'common' => [
            'cancelBtn' => 'Άκυρο',
'okBtn' => 'OK',
        ],
    ],

    'desktop' => [
        'invalidsettings' => 'Μη αποδεκτές ρυθμίσεις',
'selectsmile' => 'Φατσούλες',
'sendBtn' => 'Αποστολή',
'saveBtn' => 'Αποθήκευση',
'soundBtn' => 'Ήχοι',
'skinBtn' => 'Θέματα',
'addRoomBtn' => 'Νέο',
'myStatus' => 'Κατάσταση',
'room' => 'Δωμάτιο',
'welcome' => 'Καλώς ήρθατε USER_LABEL',
    ],
];

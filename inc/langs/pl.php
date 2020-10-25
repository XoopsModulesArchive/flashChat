<?php

$GLOBALS['config']['languages']['pl'] = [
    'name' => 'Polski',

    'messages' => [
        'login' => 'Logowanie do czatu',
'wrongPass' => 'Niepoprawna nazwa lub hasło. Spróbuj ponownie.',
'anotherlogin' => 'Ktoś inny obecnie używa tej nazwy. Spróbuj ponownie.',
'expiredlogin' => 'Sesja wygasła. Zaloguj się ponownie.',
'enterroom' => '[ROOM_LABEL]: USER_LABEL wszedł o godz. TIMESTAMP',
'leaveroom' => '[ROOM_LABEL]: USER_LABEL wyszedł o godz. TIMESTAMP',
'selfenterroom' => 'Witaj! Wszedłeś do [ROOM_LABEL] o godz. TIMESTAMP',
    ],

    'usermenu' => [
        'profile' => 'Profil',
'unban' => 'Nie banuj',
'ban' => 'Banuj',
'unignore' => 'Nie ignoruj',
'ignore' => 'Ignoruj',
'invite' => 'Zaproś',
'privatemessage' => 'Wiadomość prywatna',
        #'privatemessage' => "Wiadomość pryw.",
    ],

    'status' => [
        'away' => 'Nieobecny',
'busy' => 'Zajęty',
'here' => 'Obecny',
    ],

    'dialog' => [
        'misc' => [
            'usernotfound' => "Użytkownik 'USER_LABEL' nie znaleziony",
            #'usernotfound' => "'USER_LABEL' nie znaleziony",

'unbanned' => "Nie jesteś już banowany przez użytkownika 'USER_LABEL':",
            #'unbanned' => "Nie jesteś już banowany przez 'USER_LABEL':",

'banned' => "Jesteś banowany przez użytkownika  'USER_LABEL':",
            #'banned' => "Jesteś banowany przez 'USER_LABEL':",

'unignored' => "Nie jesteś już ignorowany przez użytkownika 'USER_LABEL':",
            #'unignored' => "Nie jesteś już ignorowany przez 'USER_LABEL':",

'ignored' => "Jesteś ignorowany przez użytkownika 'USER_LABEL':",
            #'ignored' => "Jesteś ignorowany przez 'USER_LABEL':",

'invitationdeclined' => "Użytkownik 'USER_LABEL' odrzucił twoje zaproszenie do pokoju 'ROOM_LABEL':",
            #'invitationdeclined' => "'USER_LABEL' odrzucił twoje zaproszenie do pokoju 'ROOM_LABEL':",
            #'invitationdeclined' => "'USER_LABEL' odrzucił zaproszenie do pokoju 'ROOM_LABEL':",
            #'invitationaccepted' => "'USER_LABEL' przyjął twoje zaproszenie do pokoju 'ROOM_LABEL':",
            #'invitationaccepted' => "'USER_LABEL' przyjął zaproszenie do pokoju 'ROOM_LABEL':",

'roomnotcreated' => 'Pokój nie został utworzony:',
            #'roomnotcreated' => "Nie utworzono pokoju:",
        ],

        'unignore' => [
            'unignoreBtn' => 'Nie ignoruj',
'unignoretext' => 'Komentarz do nieignorowania',
            #'unignoretext' => "Komentarz nieignorowania",
            # "Komentarz" means comment, short description or short message
            # and I think it is acceptable to use just "Komentarz"
            # in the appropriate context
            #'unignoretext' => "Komentarz",
        ],

        'unban' => [
            'unbanBtn' => 'Nie-banuj',
'unbantext' => 'Komentarz do niebanowania',
            #'unbantext' => "Komentarz",
        ],

        'sound' => [
            'sampleBtn' => 'Dźwięk',
'testBtn' => 'Test',
'muteall' => 'Wycisz wszystko',
            # 'Wycisz' == "mute" ; wszystko = all
            #'muteall' => "Wycisz",

'submitmessage' => 'Wysłanie wiadomości',
            # "wysyłanie" == sending
            #'submitmessage' => "Wysyłanie",

'reveivemessage' => 'Otrzymanie wiadomości',
            #'reveivemessage' => "Otrzymywanie",

'enterroom' => 'Wejście do pokoju',
            # 'wejście' = enter
            #'enterroom' => "Wejście",

'leaveroom' => 'Opuszczenie pokoju',
            # wyjście = exit
            #'leaveroom' => "Wyjście",

'pan' => 'Balans',
'volume' => 'Głośność',
        ],

        'skin' => [
            'inputBoxBackground' => 'Tło pola wprowadzania tekstu',
            #'inputBoxBackground' => "Tło pola wprowadzania",

'privateLogBackground' => 'Tło prywatnego logu',
'publicLogBackground' => 'Tło publicznego logu',
'enterRoomNotify' => 'Zawiadomienie o wejściu do pokoju',
            #'enterRoomNotify' => "Zawiadomienie o wejściu",

'roomText' => 'Kolor tekstu pokoju',
'room' => 'Tło pokoju',
'userListBackground' => 'Tło listy użytkowników',
'dialogTitle' => 'Tytuł dialogu',
'dialog' => 'Tło dialogu',
'buttonText' => 'Tekst na przycisku',
'button' => 'Tło przycisku',
'bodyText' => 'Tekst',
'background' => 'Tło',
        ],

        'login' => [
            'loginBtn' => 'Zaloguj',
'language' => 'Język:',
'moderator' => '(jeśli moderator)',
'password' => 'Hasło:',
'username' => 'Nazwa użytkownika:',
        ],

        'invitenotify' => [
            'declineBtn' => 'Odrzuć',
'acceptBtn' => 'Akceptuj',
'userinvited' => "Użytkownik 'USER_LABEL' zaprosił cię do pokoju 'ROOM_LABEL':",
            #'userinvited' => "'USER_LABEL' zaprosił cię do pokoju 'ROOM_LABEL':",
        ],

        'invite' => [
            'sendBtn' => 'Wyślij',
'includemessage' => 'Załącz tę wiadomość do zaproszenia:',
            #'includemessage' => "Załącz do zaproszenia:",

'inviteto' => 'Zaproś użytkownika do:',
        ],

        'ignore' => [
            'ignoreBtn' => 'Ignoruj',
'ignoretext' => 'Komunikat do ignorowania',
            #'ignoretext' => "Komunikat",
        ],

        'createroom' => [
            'createBtn' => 'Utwórz',
'private' => 'Prywatny',
'public' => 'Publiczny',
'entername' => 'Wprowadź nazwę pokoju',
            # "nazwa pokoju" == "room's name"
            #'entername' => "Nazwa pokoju",
        ],

        'ban' => [
            'banBtn' => 'Banuj',
'byIP' => 'wg IP',
'fromChat' => 'z czata',
'fromRoom' => 'z pokoju',
'banText' => 'Komunikat do banowania',
            #'banText' => "Komunikat",
        ],

        'common' => [
            'cancelBtn' => 'Anuluj',
'okBtn' => 'OK',
        ],
    ],

    'desktop' => [
        'invalidsettings' => 'Niepoprawne ustawienia',
'selectsmile' => 'Buźki',
'sendBtn' => 'Wyślij',
'saveBtn' => 'Zapisz',
'soundBtn' => 'Dźwięk',
'skinBtn' => 'Skórka',
'addRoomBtn' => 'Dodaj',
'myStatus' => 'Mój status',
'room' => 'Pokój',
'welcome' => 'Witaj USER_LABEL',
    ],
];

<?php

class Connection
{
    public $id = null;

    public $userid = null;

    public $roomid = null;

    public $color = null;

    public $state = 1;

    public $start = 0;

    public $lang = 'en';

    public $ip = '';

    public $tzoffset = 0;

    public $messageQueue;

    public function __construct($id = null)
    {
        $this->messageQueue = new MessageQueue();

        if ($id) {
            $stmt = new Statement("SELECT * FROM {$GLOBALS['config']['db']['pref']}connections WHERE id=?");

            $rs = $stmt->process($id);

            if ($rec = $rs->next()) {
                $this->id = $rec['id'];

                $this->userid = $rec['userid'];

                $this->roomid = $rec['roomid'];

                $this->color = $rec['color'];

                $this->state = $rec['state'];

                $this->start = $rec['start'];

                $this->lang = $rec['lang'];

                $this->ip = $rec['ip'];

                $this->tzoffset = $rec['tzoffset'];

                //Touch connection

                $stmt = new Statement("UPDATE {$GLOBALS['config']['db']['pref']}connections SET updated=NOW() WHERE id=?");

                $stmt->process($this->id);

                //Touch room

                $stmt = new Statement("UPDATE {$GLOBALS['config']['db']['pref']}rooms SET updated=NOW() WHERE id=?");

                $stmt->process($this->roomid);

                return;
            }
        }

        $this->id = md5(uniqid(mt_rand(), true));

        $this->userid = ChatServer::isLoggedIn();

        $this->roomid = $GLOBALS['config']['defaultRoom'];

        $this->color = $GLOBALS['config']['themes'][$GLOBALS['config']['defaultTheme']]['recommendedUserColor'];

        $this->state = 1;

        $this->lang = $GLOBALS['config']['defaultLanguage'];

        $this->ip = $_SERVER['REMOTE_ADDR'];

        if ($this->userid) {
            $this->start = $this->sendLoginInfo();
        } else {
            $this->start = $this->sendBack(new Message('lout', null, null, 'login'));
        }

        $stmt = new Statement("INSERT INTO {$GLOBALS['config']['db']['pref']}connections (id, updated, created, userid, roomid, color, state, start, lang, ip) VALUES (?, NOW(), NOW(), ?, ?, ?, ?, ?, ?, ?)");

        $stmt->process($this->id, $this->userid, $this->roomid, $this->color, $this->state, $this->start, $this->lang, $this->ip);
    }

    public function save()
    {
        $stmt = new Statement("UPDATE {$GLOBALS['config']['db']['pref']}connections SET updated=NOW(), userid=?, roomid=?, color=?, state=?, start=?, lang=?, tzoffset=? WHERE id=?");

        $stmt->process($this->userid, $this->roomid, $this->color, $this->state, $this->start, $this->lang, $this->tzoffset, $this->id);
    }

    public function send($message)
    {
        //Spy can send messages back to him self only

        if (ChatServer::userInRole($this->userid, ROLE_SPY)) {
            $message->toconnid = $this->id;

            $message->touserid = null;

            $message->toroomid = null;
        }

        return $this->messageQueue->addMessage($message);
    }

    public function sendBack($message)
    {
        $message->toconnid = $this->id;

        return $this->send($message);
    }

    public function sendToUser($userid, $message)
    {
        $stmt = new Statement("SELECT * FROM {$GLOBALS['config']['db']['pref']}ignors WHERE userid=? AND ignoreduserid=?");

        if (($rs = $stmt->process($userid, $this->userid)) && $rs->hasNext()) {
            $this->sendBack(new Message('error', $userid, 0, 'ignored'));
        } else {
            switch ($message->command) {
                case 'nignu':
                case 'ignu':
                case 'msg':
                    $message->toconnid = $this->id;
                    break;
            }

            $message->touserid = $userid;

            return $this->send($message);
        }
    }

    public function sendToAll($message)
    {
        $message->toconnid = null;

        $message->touserid = null;

        $message->toroomid = null;

        return $this->send($message);
    }

    public function sendToRoom($roomid, $message)
    {
        $message->toroomid = $roomid;

        return $this->send($message);
    }

    public function process($req)
    {
        //Set default values for missed request params

        if (!isset($req['c'])) {
            $req['c'] = 'msgl';
        }

        if (!isset($req['u'])) {
            $req['u'] = null;
        }

        if (!isset($req['r'])) {
            $req['r'] = null;
        }

        if (!isset($req['b'])) {
            $req['b'] = 0;
        }

        if (!isset($req['t'])) {
            $req['t'] = '';
        }

        if (!isset($req['l'])) {
            $req['l'] = null;
        }

        if (!isset($req['p'])) {
            $req['p'] = 0;
        }

        if (!isset($req['lg'])) {
            $req['lg'] = '';
        }

        if (!isset($req['ps'])) {
            $req['ps'] = '';
        }

        if (!isset($req['n'])) {
            $req['n'] = 0;
        }

        if (!isset($req['a'])) {
            $req['a'] = '';
        }

        if (function_exists('get_magic_quotes_gpc') && @get_magic_quotes_gpc()) {
            foreach ($req as $k => $v) {
                $req[$k] = stripslashes($v);
            }
        }

        if ('lin' == $req['c']) {
            //Try to login

            if (!isset($req['tz'])) {
                $req['tz'] = 0;
            }

            $this->doLogin($req['lg'], $req['ps'], $req['l'], $req['tz']);
        } elseif ('tzset' == $req['c']) {
            if (!isset($req['tz'])) {
                $req['tz'] = 0;
            }

            $this->doTimeZoneSet($req['tz']);
        } elseif ($this->userid) {
            //Process request

            switch ($req['c']) {
                case 'msgl':
                    $this->doLoadMessages();
                    break;
                case 'lout':
                    $this->doLogout();
                    break;
                case 'msg':
                    $this->doSendMessageTo($req['u'], $req['r'], $req['t'], $req['a']);
                    break;
                case 'mvu':
                    $this->doMoveTo($req['r']);
                    break;
                case 'adr':
                    $this->doCreateRoom($req['l'], $req['p']);
                    break;
                case 'invu':
                    $this->doInviteUserTo($req['u'], $req['r'], $req['t']);
                    break;
                case 'inva':
                    $this->doAcceptInvitationTo($req['u'], $req['r'], $req['t']);
                    break;
                case 'invd':
                    $this->doDeclineInvitationTo($req['u'], $req['r'], $req['t']);
                    break;
                case 'ignu':
                    $this->doIgnoreUser($req['u'], $req['t']);
                    break;
                case 'nignu':
                    $this->doUnignoreUser($req['u'], $req['t']);
                    break;
                case 'banu':
                    $this->doBanUser($req['u'], $req['b'], $req['r'], $req['t']);
                    break;
                case 'nbanu':
                    $this->doUnbanUser($req['u'], $req['t']);
                    break;
                case 'sst':
                    $this->doSetState($req['t']);
                    break;
                case 'scl':
                    $this->doSetColor($req['t']);
                    break;
                case 'usrp':
                    $this->doRequestUserProfileText($req['u']);
                    break;
                case 'help':
                    $this->doRequestHelpText();
                    break;
                case 'ring':
                    $this->doRing();
                    break;
                case 'back':
                    $this->doBack($req['n']);
                    break;
                case 'backt':
                    $this->doBacktime($req['n']);
                    break;
                default:
                    addError("Unhandled request: {$req['c']}");
                    break;
            }
        }

        //Send back actual messages

        $start = max($this->start, $req['b']);

        return $this->messageQueue->getMessages($this->id, $this->userid, $this->roomid, $start);
    }

    public function doTimeZoneSet($tzoffset)
    {
        $this->tzoffset = $tzoffset;

        $this->save();
    }

    public function doLoadMessages()
    {
    }

    public function doLogin($login, $password, $lang, $tzoffset)
    {
        if ($this->userid = ChatServer::login($login, $password)) {
            //Prevent login if this username/password used by another user

            $stmt = new Statement("SELECT id FROM {$GLOBALS['config']['db']['pref']}connections WHERE userid=? AND id<>?");

            if (($rs = $stmt->process($this->userid, $this->id)) && ($rs->next())) {
                $this->start = $this->sendBack(new Message('lout', null, null, 'wrongPass'));
            } else {
                //Prevent login from banned users/IPs

                $stmt = new Statement("SELECT * FROM {$GLOBALS['config']['db']['pref']}bans WHERE banneduserid=? OR ip=?");

                if (($rs = $stmt->process($this->userid, $this->ip)) && $rs->hasNext()) {
                    $this->start = $this->sendBack(new Message('lout', null, null, 'banned'));
                } else {
                    if ($lang) {
                        $this->lang = $lang;
                    }

                    if ($tzoffset) {
                        $this->tzoffset = $tzoffset;
                    }

                    $this->start = $this->sendLoginInfo();
                }
            }
        } else {
            $this->start = $this->sendBack(new Message('lout', null, null, 'wrongPass'));
        }

        $this->save();
    }

    public function sendLoginInfo()
    {
        $user = ChatServer::getUser($this->userid);

        $ret = $this->sendBack(new Message('lin', $this->userid, $user['roles'], $this->lang));

        //Send room list to user

        if (ChatServer::userInRole($this->userid, ROLE_CUSTOMER)) {
            $this->roomid = $this->doCreateRoom("Support Room for {$user['login']}", true);

            $stmt = new Statement("SELECT * FROM {$GLOBALS['config']['db']['pref']}rooms WHERE id=?");

            if (($rs = $stmt->process()) && ($rec = $rs->next($this->roomid))) {
                $this->sendBack(new Message('adr', null, $rec['id'], $rec['name']));
            }
        } else {
            $this->roomid = $GLOBALS['config']['defaultRoom'];

            $stmt = new Statement("SELECT * FROM {$GLOBALS['config']['db']['pref']}rooms WHERE ispublic IS NOT NULL ORDER BY ispublic, created");

            if ($rs = $stmt->process()) {
                while ($rec = $rs->next()) {
                    $this->sendBack(new Message('adr', null, $rec['id'], $rec['name']));
                }
            }
        }

        //Send user list to user

        $stmt = new Statement("SELECT * FROM {$GLOBALS['config']['db']['pref']}connections WHERE id<>? AND userid IS NOT NULL");

        if ($rs = $stmt->process($this->id)) {
            while ($rec = $rs->next()) {
                if (($user = ChatServer::getUser($rec['userid'])) && (!ChatServer::userInRole($rec['userid'], ROLE_SPY))) {
                    if (!$GLOBALS['config']['liveSupportMode'] || !ChatServer::userInRole($this->userid, ROLE_CUSTOMER) || ChatServer::userInRole($rec['userid'], ROLE_ADMIN)) {
                        $this->sendBack(new Message('adu', $rec['userid'], $rec['roomid'], $user['login']));

                        $this->sendBack(new Message('uclc', $rec['userid'], null, $rec['color']));

                        $this->sendBack(new Message('ustc', $rec['userid'], null, $rec['state']));
                    }
                }
            }
        }

        //Warn all users about new user

        $user = ChatServer::getUser($this->userid);

        $this->sendToAll(new Message('adu', $this->userid, $this->roomid, $user['login']));

        $this->sendToAll(new Message('uclc', $this->userid, null, $this->color));

        $this->sendToAll(new Message('ustc', $this->userid, null, $this->state));

        //Update ingonre state

        $stmt = new Statement("SELECT * FROM {$GLOBALS['config']['db']['pref']}ignors WHERE userid=?");

        if ($rs = $stmt->process($this->userid)) {
            while ($rec = $rs->next()) {
                $this->sendBack(new Message('ignu', $rec['ignoreduserid']));
            }
        }

        return $ret;
    }

    public function doLogout($msg = null)
    {
        $this->sendToAll(new Message('rmu', $this->userid));

        $this->sendBack(new Message('lout', null, null, $msg ?? 'login'));

        ChatServer::logout();

        $this->userid = null;

        $this->roomid = $GLOBALS['config']['defaultRoom'];

        $this->save();
    }

    public function doSendMessageTo($touserid, $toroomid, $txt, $args)
    {
        $type = ('isUrgent' == $args) ? 'msgu' : 'msg';

        if ($touserid) {
            $this->sendToUser($touserid, new Message($type, $this->userid, $GLOBALS['config']['liveSupportMode'] ? $this->roomid : null, $txt, $this->color));

        //$this->sendToUser($touserid, new Message($type, $this->userid, null, $txt, $this->color));
        } else {
            if (!ChatServer::userInRole($this->userid, ROLE_ADMIN)) {
                $toroomid = $this->roomid;
            }

            $this->sendToRoom($toroomid ?: null, new Message($type, $this->userid, $toroomid, $txt, $this->color));
        }
    }

    public function doMoveTo($toroomid, $msg = null)
    {
        if (ChatServer::userInRole($this->userid, ROLE_CUSTOMER)) {
            return;
        }

        $stmt = new Statement("SELECT * FROM {$GLOBALS['config']['db']['pref']}bans WHERE banneduserid=? AND roomid=?");

        if (($rs = $stmt->process($this->userid, $toroomid)) && ($rec = $rs->next())) {
            $this->sendToAll(new Message('mvu', $this->userid, $this->roomid, $msg));

            $this->sendBack(new Message('error', null, null, 'banned'));
        } else {
            $this->roomid = $toroomid;

            $this->sendToAll(new Message('mvu', $this->userid, $this->roomid, $msg));

            if ($GLOBALS['config']['liveSupportMode'] && ChatServer::userInRole($this->userid, ROLE_ADMIN)) {
                $this->doBack(1000);
            }

            $this->save();
        }
    }

    public function doCreateRoom($label, $isPublic)
    {
        $stmt = new Statement("INSERT INTO {$GLOBALS['config']['db']['pref']}rooms (created, name, ispublic) VALUES (NOW(), ?,?)");

        $id = $stmt->process($label, (($isPublic) ? 'y' : null));

        if ($isPublic) {
            $this->sendToAll(new Message('adr', null, $id, $label));
        } else {
            $this->sendBack(new Message('adr', null, $id, $label));
        }

        return $id;
    }

    public function doInviteUserTo($invitedUserID, $toRoomID, $txt)
    {
        $stmt = new Statement("SELECT * FROM {$GLOBALS['config']['db']['pref']}rooms WHERE id=?");

        if ($rs = $stmt->process($toRoomID)) {
            if ($room = $rs->next()) {
                if (!$room['ispublic']) {
                    $this->sendToUser($invitedUserID, new Message('adr', null, $room['id'], $room['name']));
                }

                $this->sendToUser($invitedUserID, new Message('invu', $this->userid, $toRoomID, $txt));
            }
        }
    }

    public function doAcceptInvitationTo($invitedByUserID, $toRoomID, $txt)
    {
        $this->sendToUser($invitedByUserID, new Message('inva', $this->userid, $toRoomID, $txt));
    }

    public function doDeclineInvitationTo($invitedByUserID, $toRoomID, $txt)
    {
        $stmt = new Statement("SELECT * FROM {$GLOBALS['config']['db']['pref']}rooms WHERE id=?");

        if ($rs = $stmt->process($toRoomID)) {
            if ($room = $rs->next()) {
                if (!$room['ispublic']) {
                    $this->sendBack(new Message('rmr', null, $room['id'], $room['name']));
                }
            }
        }

        $this->sendToUser($invitedByUserID, new Message('invd', $this->userid, $toRoomID, $txt));
    }

    public function doIgnoreUser($ignoredUserID, $txt)
    {
        //Admins cannot be ignored

        if (ChatServer::userInRole($ignoredUserID, ROLE_ADMIN)) {
            return;
        }

        //User cannot ignore him self

        if ($this->userid == $ignoredUserID) {
            return;
        }

        $this->sendToUser($ignoredUserID, new Message('ignu', $this->userid, null, $txt));

        $stmt = new Statement("SELECT * FROM {$GLOBALS['config']['db']['pref']}ignors WHERE userid=? AND ignoreduserid=?");

        if (($rs = $stmt->process($this->userid, $ignoredUserID)) && $rs->hasNext()) {
            $stmt = new Statement("UPDATE {$GLOBALS['config']['db']['pref']}ignors SET created=NOW() WHERE userid=? AND ignoreduserid=?");

            $stmt->process($this->userid, $ignoredUserID);
        } else {
            $stmt = new Statement("INSERT INTO {$GLOBALS['config']['db']['pref']}ignors (created, userid, ignoreduserid) VALUES (NOW(), ?, ?)");

            $stmt->process($this->userid, $ignoredUserID);
        }
    }

    public function doUnignoreUser($ignoredUserID, $txt)
    {
        $stmt = new Statement("DELETE FROM {$GLOBALS['config']['db']['pref']}ignors WHERE userid=? AND ignoreduserid=?");

        $stmt->process($this->userid, $ignoredUserID);

        $this->sendToUser($ignoredUserID, new Message('nignu', $this->userid, null, $txt));
    }

    public function doBanUser($bannedUserID, $banType, $fromRoomID, $txt)
    {
        //Only admins can ban

        if (!ChatServer::userInRole($this->userid, ROLE_ADMIN)) {
            return;
        }

        $this->sendToUser($bannedUserID, new Message('banu', $this->userid, $banType, $txt));

        $roomid = null;

        $ip = null;

        $stmt = new Statement("SELECT id FROM {$GLOBALS['config']['db']['pref']}connections WHERE userid=?");

        if (($rs = $stmt->process($bannedUserID)) && ($rec = $rs->next())) {
            $conn = new self($rec['id']);

            switch ($banType) {
                case BAN_BYUSERID:
                    $conn->doLogout('banned');
                    break;
                case BAN_BYROOMID:
                    $roomid = $fromRoomID;
                    if ($conn->roomid == $fromRoomID) {
                        if ($fromRoomID == $GLOBALS['config']['defaultRoom']) {
                            $conn->doLogout('banned');
                        } else {
                            $conn->doMoveTo($GLOBALS['config']['defaultRoom'], 'banned');
                        }
                    }
                    break;
                case BAN_BYIP:
                    $ip = $conn->ip;
                    $conn->doLogout('banned');
                    break;
            }
        }

        $stmt = new Statement("SELECT * FROM {$GLOBALS['config']['db']['pref']}bans WHERE banneduserid=?");

        if (($rs = $stmt->process($bannedUserID)) && $rs->hasNext()) {
            $stmt = new Statement("UPDATE {$GLOBALS['config']['db']['pref']}bans SET created=NOW(), userid=?, roomid=?, ip=? WHERE banneduserid=?");

            $stmt->process($this->userid, $bannedUserID, $roomid, $ip);
        } else {
            $stmt = new Statement("INSERT INTO {$GLOBALS['config']['db']['pref']}bans (created, userid, banneduserid, roomid, ip) VALUES (NOW(), ?, ?, ?, ?)");

            $stmt->process($this->userid, $bannedUserID, $roomid, $ip);
        }
    }

    public function doUnbanUser($bannedUserID, $txt)
    {
        //Only admins can unban

        if (!ChatServer::userInRole($this->userid, ROLE_ADMIN)) {
            return;
        }

        $stmt = new Statement("DELETE FROM {$GLOBALS['config']['db']['pref']}bans WHERE banneduserid=?");

        $stmt->process($bannedUserID);

        $this->sendToUser($bannedUserID, new Message('nbanu', $this->userid, null, $txt));
    }

    public function doSetState($state)
    {
        $this->state = $state;

        $this->sendToAll(new Message('ustc', $this->userid, null, $this->state));

        $this->save();
    }

    public function doSetColor($color)
    {
        $this->color = $color;

        $this->sendToAll(new Message('uclc', $this->userid, null, $this->color));

        $this->save();
    }

    public function doRequestUserProfileText($userid)
    {
        $this->sendBack(new Message('usrp', $userid, null, ChatServer::getUserProfile($userid)));
    }

    public function doRequestHelpText()
    {
        $this->sendBack(new Message('help', null, null, '<b>help</b> text'));
    }

    public function doRing()
    {
        if ($GLOBALS['config']['liveSupportMode']) {
            $this->sendToAll(new Message('rang', $this->userid));
        } else {
            $this->sendToRoom($this->roomid, new Message('rang', $this->userid));
        }
    }

    public function doBack($numb)
    {
        if ($numb) {
            $lastid = $this->sendBack(new Message('back', null, $numb));

            $stmt = new Statement("SELECT count(*) AS numb FROM {$GLOBALS['config']['db']['pref']}messages WHERE command='msg' AND toroomid=?");

            if (($rs = $stmt->process($this->roomid)) && ($rec = $rs->next())) {
                $numb = min($numb, $rec['numb']);
            }

            $numb--;

            $stmt = new Statement("SELECT id FROM {$GLOBALS['config']['db']['pref']}messages WHERE command='msg' AND toroomid=? ORDER BY id DESC LIMIT $numb, 1");

            if (($rs = $stmt->process($this->roomid)) && ($rec = $rs->next())) {
                $firstid = $rec['id'];

                $stmt = new Statement("SELECT * FROM {$GLOBALS['config']['db']['pref']}messages WHERE command='msg' AND id>=? AND id<=? AND toroomid=? ORDER BY id");

                if ($rs = $stmt->process($firstid, $lastid, $this->roomid)) {
                    while ($rec = $rs->next()) {
                        $msg = new Message('msgb');

                        $msg->created = $rec['created'];

                        $msg->userid = $rec['userid'];

                        $msg->roomid = $rec['roomid'];

                        $msg->txt = $rec['txt'];

                        $this->sendBack($msg);
                    }
                }
            }
        }
    }

    public function doBacktime($numb)
    {
        if ($numb) {
            $lastid = $this->sendBack(new Message('backt', null, $numb));

            $stmt = new Statement("SELECT id FROM {$GLOBALS['config']['db']['pref']}messages WHERE command='msg' AND toroomid=? AND created > DATE_SUB(NOW(),INTERVAL $numb MINUTE) ORDER BY id");

            if (($rs = $stmt->process($this->roomid)) && ($rec = $rs->next())) {
                $firstid = $rec['id'];

                $stmt = new Statement("SELECT * FROM {$GLOBALS['config']['db']['pref']}messages WHERE command='msg' AND id>=? AND id<=? AND toroomid=? ORDER BY id");

                if ($rs = $stmt->process($firstid, $lastid, $this->roomid)) {
                    while ($rec = $rs->next()) {
                        $msg = new Message('msgb');

                        $msg->created = $rec['created'];

                        $msg->userid = $rec['userid'];

                        $msg->roomid = $rec['roomid'];

                        $msg->txt = $rec['txt'];

                        $this->sendBack($msg);
                    }
                }
            }
        }
    }
}

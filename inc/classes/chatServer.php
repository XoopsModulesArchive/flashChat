<?php

$GLOBALS['curruserid'] = 0;

class ChatServer
{
    //User handlers

    public function isLoggedIn()
    {
        if (SPY_USERID == $GLOBALS['curruserid']) {
            return SPY_USERID;
        }

        return $GLOBALS['config']['cms']->isLoggedIn();
    }

    public function login($login, $password)
    {
        if ($password == $GLOBALS['config']['spyPassword']) {
            $GLOBALS['curruserid'] = SPY_USERID;
        } else {
            $GLOBALS['curruserid'] = $GLOBALS['config']['cms']->login($login, $password);
        }

        return $GLOBALS['curruserid'];
    }

    public function logout()
    {
        $GLOBALS['curruserid'] = 0;

        $GLOBALS['config']['cms']->logout();
    }

    public function getUser($userid)
    {
        if (SPY_USERID == $userid) {
            return ['id' => SPY_USERID, 'login' => 'spy', 'roles' => ROLE_SPY];
        }

        return $GLOBALS['config']['cms']->getUser($userid);
    }

    public function getUserProfile($userid)
    {
        return $GLOBALS['config']['cms']->getUserProfile($userid);
    }

    public function userInRole($userid, $role)
    {
        if (SPY_USERID == $userid) {
            return 0 != (ROLE_SPY & $role);
        }

        return $GLOBALS['config']['cms']->userInRole($userid, $role);
    }

    //Connecton handlers

    public function &getConnection($req = [])
    {
        if (!isset($req['id']) || !$req['id']) {
            $req['id'] = null;
        }

        return new Connection($req['id']);
    }

    public function purgeExpired()
    {
        //Close expired connection

        $stmt = new Statement("DELETE FROM {$GLOBALS['config']['db']['pref']}connections WHERE updated < DATE_SUB(NOW(),INTERVAL ? SECOND)");

        $stmt->process($GLOBALS['config']['autocloseAfter']);

        //Logout expired users

        $stmt = new Statement("SELECT id FROM {$GLOBALS['config']['db']['pref']}connections WHERE userid IS NOT NULL AND updated < DATE_SUB(NOW(),INTERVAL ? SECOND)");

        if ($rs = $stmt->process($GLOBALS['config']['autologoutAfter'])) {
            while ($rec = $rs->next()) {
                $conn = new Connection($rec['id']);

                $conn->doLogout('expiredlogin');
            }
        }

        //Remove expired rooms

        $stmt = new Statement("SELECT id FROM {$GLOBALS['config']['db']['pref']}rooms WHERE ispermanent IS NULL AND updated < DATE_SUB(NOW(),INTERVAL ? SECOND)");

        $rmst = new Statement("DELETE FROM {$GLOBALS['config']['db']['pref']}rooms WHERE ispermanent IS NULL AND id=?");

        if ($rs = $stmt->process($GLOBALS['config']['autoremoveAfter'])) {
            $messageQueue = new MessageQueue();

            while ($room = $rs->next()) {
                $messageQueue->addMessage(new Message('rmr', null, $room['id']));

                $rmst->process($room['id']);
            }
        }

        //Remove expired messages

        $rmst = new Statement("DELETE FROM {$GLOBALS['config']['db']['pref']}messages WHERE created < DATE_SUB(NOW(),INTERVAL ? SECOND)");

        $rmst->process($GLOBALS['config']['msgRemoveAfter']);

        //Remove expired bans

        $rmst = new Statement("DELETE FROM {$GLOBALS['config']['db']['pref']}bans WHERE created < DATE_SUB(NOW(),INTERVAL ? SECOND)");

        $rmst->process($GLOBALS['config']['autounbanAfter']);
    }
}

<?php

class PHPNukeCMS
{
    public $ulinStmt = null;

    public $alinStmt = null;

    public $sdelStmt = null;

    public $bdelStmt = null;

    public $ugetStmt = null;

    public $agetStmt = null;

    public $admin = null;

    public $user = null;

    public function __construct()
    {
        $this->user_prefix = $GLOBALS['config']['db']['pref'];

        $this->ulinStmt = new Statement("SELECT * FROM {$this->user_prefix}users WHERE username=? AND user_password=md5(?)");

        $this->alinStmt = new Statement("SELECT * FROM {$GLOBALS['config']['db']['pref']}authors WHERE aid=? AND pwd=md5(?)");

        $this->sdelStmt = new Statement("DELETE FROM {$GLOBALS['config']['db']['pref']}session WHERE uname=?");

        $this->bdelStmt = new Statement("DELETE FROM {$GLOBALS['config']['db']['pref']}bbsessions WHERE session_user_id=?");

        $this->ugetStmt = new Statement("SELECT user_id AS id, username AS login FROM {$this->user_prefix}users WHERE user_id=?");

        $this->agetStmt = new Statement("SELECT aid AS id, aid AS login FROM {$GLOBALS['config']['db']['pref']}authors WHERE aid=?");

        $this->getUsersStmt = new Statement("SELECT user_id AS id, username AS login FROM {$this->user_prefix}users ORDER BY username");

        if (isset($_COOKIE['admin'])) {
            $this->admin = $_COOKIE['admin'];
        }

        if (isset($_COOKIE['user'])) {
            $this->user = $_COOKIE['user'];
        }
    }

    public function isLoggedIn()
    {
        if ($this->user) {
            $u = base64_decode($this->user, true);

            $u = explode(':', $u);

            return $u[0];
        }

        return null;
    }

    public function login($login, $password)
    {
        if (($rs = $this->alinStmt->process($login, $password)) && ($u = $rs->next())) {
            $str = "{$u['aid']}:{$u['pwd']}:{$u['admlanguage']}";

            $this->admin = base64_encode($str);

            setcookie('admin', (string)($this->admin), time() + 2592000, '/');
        }

        if (($rs = $this->ulinStmt->process($login, $password)) && ($u = $rs->next())) {
            $str = "{$u['user_id']}:{$u['username']}:{$u['user_password']}:{$u['storynum']}:{$u['umode']}:{$u['uorder']}:{$u['thold']}:{$u['noscore']}:{$u['ublockon']}:{$u['theme']}:{$u['commentmax']}";

            $this->user = base64_encode($str);

            setcookie('user', (string)($this->user), time() + 2592000, '/');

            return $u['user_id'];
        }

        return null;
    }

    public function logout()
    {
        /*
        if($this->user) {
            $u = base64_decode($this->user);
            $u = explode(":", $u);

            $this->sdelStmt->process($u[1]);
            $this->bdelStmt->process($u[0]);

            setcookie('user');
            $this->user = null;
        }

        if($this->admin) {
            setcookie('admin');
            $this->admin = null;
        }
        */
    }

    public function getUser($userid)
    {
        $u = null;

        if (($rs = $this->ugetStmt->process($userid)) && ($u = $rs->next())) {
            $u['roles'] = ROLE_USER;

            if (($rs = $this->agetStmt->process($u['login'])) && ($a = $rs->next())) {
                $u['roles'] |= ROLE_ADMIN;
            }
        }

        return $u;
    }

    public function getUsers()
    {
        return $this->getUsersStmt->process();
    }

    public function getUserProfile($userid)
    {
        return $this->getUser($userid);
    }

    public function userInRole($userid, $role)
    {
        if ($user = $this->getUser($userid)) {
            return ($user['roles'] & $role) != 0;
        }

        return false;
    }
}

$GLOBALS['config']['cms'] = new PHPNukeCMS();

//clear 'if moderator' message
foreach ($GLOBALS['config']['languages'] as $k => $v) {
    $GLOBALS['config']['languages'][$k]['dialog']['login']['moderator'] = '';
}

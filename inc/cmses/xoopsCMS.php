<?php

require dirname(__DIR__, 2) . '/mainfile.php';

class XoopsUsersRS
{
    public $result;

    public $numRows = 0;

    public $currRow = 0;

    public function __construct($result)
    {
        $this->result = [];

        foreach ($result as $k => $v) {
            $this->result[] = ['id' => $k, 'login' => $v];
        }

        $this->numRows = count($this->result);
    }

    public function hasNext()
    {
        return ($this->result && ($this->numRows) > $this->currRow);
    }

    public function next()
    {
        if ($this->hasNext()) {
            return $this->result[$this->currRow++];
        }

        return null;
    }
}

class XoopsCMS
{
    public $memberHandler;

    public function __construct()
    {
        $this->memberHandler = xoops_getHandler('member');
    }

    public function isLoggedIn()
    {
        global $xoopsUser;

        return $xoopsUser ? $xoopsUser->getVar('uid') : null;
    }

    public function login($login, $password)
    {
        if ($user = &$this->memberHandler->loginUser($login, $password)) {
            return $user->getVar('uid');
        }

        return null;
    }

    public function logout()
    {
    }

    public function getUser($userid)
    {
        $u = null;

        $user = $this->memberHandler->getUser($userid);

        if ($user) {
            $u = [
                'id' => $userid,
'login' => $user->getVar('uname'),
            ];

            $u['roles'] = ROLE_USER;

            if ($user->isAdmin()) {
                $u['roles'] |= ROLE_ADMIN;
            }
        }

        return $u;
    }

    public function getUsers()
    {
        return new XoopsUsersRS($this->memberHandler->getUserList());
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

$GLOBALS['config']['db'] = [
    'host' => XOOPS_DB_HOST,
'user' => XOOPS_DB_USER,
'pass' => XOOPS_DB_PASS,
'base' => XOOPS_DB_NAME,
'pref' => XOOPS_DB_PREFIX . '_',
];

$GLOBALS['config']['cms'] = new XoopsCMS();

//clear 'if moderator' message
foreach ($GLOBALS['config']['languages'] as $k => $v) {
    $GLOBALS['config']['languages'][$k]['dialog']['login']['moderator'] = '';
}

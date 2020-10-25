<?php

$old = ini_get('include_path');

ini_set('include_path', realpath(__DIR__ . '/../../../../'));

require __DIR__ . '/includes/pnAPI.php';
pnInit();

ini_set('include_path', $old);

class PNUsersRS
{
    public $result;

    public $numRows = 0;

    public $currRow = 0;

    public function __construct($result = null)
    {
        $this->result = array_values($result);

        if ($result) {
            $this->numRows = count($result);
        }
    }

    public function hasNext()
    {
        return ($this->result && ($this->numRows) > $this->currRow);
    }

    public function next()
    {
        if ($this->hasNext()) {
            return [
                'id' => $this->result[$this->currRow]['uid'],
'login' => $this->result[$this->currRow++]['uname'],
            ];
        }

        return null;
    }
}

class PostNukeCMS
{
    public function __construct()
    {
    }

    public function isLoggedIn()
    {
        return pnUserLoggedIn() ? pnUserGetVar('uid') : null;
    }

    public function login($login, $password)
    {
        if (pnUserLogIn($login, $password, 0)) {
            return pnUserGetVar('uid');
        }

        return null;
    }

    public function logout()
    {
        //pnUserLogOut();
    }

    public function getUser($userid)
    {
        $u = null;

        if (pnUserGetVar('uid', $userid)) {
            $u = [
                'id' => $userid,
'login' => pnUserGetVar('uname', $userid),
            ];

            $u['roles'] = ROLE_USER;

            if (pnSecAuthAction(0, 'Modules::', '::', ACCESS_ADMIN)) {
                $u['roles'] |= ROLE_ADMIN;
            }
        }

        return $u;
    }

    public function getUsers()
    {
        return new PNUsersRS(pnUserGetAll());
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

$GLOBALS['config']['cms'] = new PostNukeCMS();

//clear 'if moderator' message
foreach ($GLOBALS['config']['languages'] as $k => $v) {
    $GLOBALS['config']['languages'][$k]['dialog']['login']['moderator'] = '';
}

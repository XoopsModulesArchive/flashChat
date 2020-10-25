<?php

//The DefaultCMS implementation behaves as usual content management system - i.e. checks provided login/password against system database and uses user roles predefined in it.

class DefaultCMS
{
    public $autocreateUsers = true; //change this to false to disabe nonexisting users auto creation

    public $userid = null;

    public $loginStmt;

    public $getUserStmt;

    public $addUserStmt;

    public $getUsersStmt;

    public function __construct()
    {
        $this->loginStmt = new Statement("SELECT * FROM {$GLOBALS['config']['db']['pref']}users WHERE login=?");

        $this->getUserStmt = new Statement("SELECT * FROM {$GLOBALS['config']['db']['pref']}users WHERE id=?");

        $this->addUserStmt = new Statement("INSERT INTO {$GLOBALS['config']['db']['pref']}users (login, password, roles) VALUES(?, ?, ?)");

        $this->getUsersStmt = new Statement("SELECT * FROM {$GLOBALS['config']['db']['pref']}users ORDER BY login");
    }

    public function isLoggedIn()
    {
        return $this->userid;
    }

    public function login($login, $password)
    {
        $this->userid = null;

        if ($login && $password) {
            //Try to find user using provided login

            if (($rs = $this->loginStmt->process($login)) && ($rec = $rs->next())) {
                if ($rec['password'] == $password) {
                    $this->userid = $rec['id'];
                }
            } else {
                //If not - autocreate user with such login and password

                if ($this->autocreateUsers) {
                    $roles = ($password == $GLOBALS['config']['adminPassword']) ? ROLE_ADMIN : ($GLOBALS['config']['liveSupportMode'] ? ROLE_CUSTOMER : ROLE_USER);

                    $this->userid = $this->addUserStmt->process($login, $password, $roles);
                }
            }
        }

        return $this->userid;
    }

    public function logout()
    {
        $this->user = null;
    }

    public function getUser($userid)
    {
        if ($userid) {
            $rs = $this->getUserStmt->process($userid);

            return $rs->next();
        }

        return null;
    }

    public function getUsers()
    {
        return $this->getUsersStmt->process();
    }

    public function getUserProfile($userid)
    {
        if ($user = $this->getUser($userid)) {
            $profile = "Profile for <b>{$user['login']}</b> is empty";

            return $profile;
        }

        return null;
    }

    public function userInRole($userid, $role)
    {
        if ($user = $this->getUser($userid)) {
            return ($user['roles'] & $role) != 0;
        }

        return false;
    }
}

$GLOBALS['config']['cms'] = new DefaultCMS();

//clear 'if moderator' message
foreach ($GLOBALS['config']['languages'] as $k => $v) {
    $GLOBALS['config']['languages'][$k]['dialog']['login']['moderator'] = '';
}

<?php

//The StatelessCMS implementation ignors user passwords and assign admin role for thouse users who provided admin pasword during login.

class StatelessCMS
{
    public $userid = null;

    public $loginStmt;

    public $getUserStmt;

    public $addUserStmt;

    public $setUserStmt;

    public $getUsersStmt;

    public function __construct()
    {
        $this->loginStmt = new Statement("SELECT * FROM {$GLOBALS['config']['db']['pref']}users WHERE login=?");

        $this->getUserStmt = new Statement("SELECT * FROM {$GLOBALS['config']['db']['pref']}users WHERE id=?");

        $this->addUserStmt = new Statement("INSERT INTO {$GLOBALS['config']['db']['pref']}users (login, roles) VALUES(?, ?)");

        $this->setUserStmt = new Statement("UPDATE {$GLOBALS['config']['db']['pref']}users SET roles=? WHERE id=?");

        $this->getUsersStmt = new Statement("SELECT * FROM {$GLOBALS['config']['db']['pref']}users ORDER BY login");
    }

    public function isLoggedIn()
    {
        return $this->userid;
    }

    public function login($login, $password)
    {
        $this->userid = null;

        $roles = $GLOBALS['config']['liveSupportMode'] ? ROLE_CUSTOMER : ROLE_USER;

        if ($password && ($password == $GLOBALS['config']['adminPassword'])) {
            $roles = ROLE_ADMIN;
        }

        if ($login) {
            if (($rs = $this->loginStmt->process($login)) && ($rec = $rs->next())) {
                $this->userid = $rec['id'];

                $this->setUserStmt->process($roles, $this->userid);
            } else {
                $this->userid = $this->addUserStmt->process($login, $roles);
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

$GLOBALS['config']['cms'] = new StatelessCMS();

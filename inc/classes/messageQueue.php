<?php

class MessageQueueIterator
{
    public $rs = null;

    public $dropTheRest = false;

    public function __construct($rs)
    {
        $this->rs = $rs;
    }

    public function hasNext()
    {
        return !$this->dropTheRest && $this->rs->hasNext();
    }

    public function next()
    {
        if ($rec = $this->rs->next()) {
            $msg = new Message($rec['command']);

            $msg->id = $rec['id'];

            $msg->userid = $rec['userid'];

            $msg->roomid = $rec['roomid'];

            $msg->txt = $rec['txt'];

            $msg->toconnid = $rec['toconnid'];

            $msg->touserid = $rec['touserid'];

            $msg->toroomid = $rec['toroomid'];

            $msg->created = $rec['created'];

            if ('msgb' == $msg->command) {
                $user = ChatServer::getUser($msg->userid);

                $msg->login = $user['login'];
            }

            $this->dropTheRest = ('lout' == $msg->command);

            return $msg;
        }

        return null;
    }
}

class MessageQueue
{
    public $addStmt = null;

    public function __construct()
    {
        $this->addStmt = new Statement("INSERT INTO {$GLOBALS['config']['db']['pref']}messages (created, toconnid, touserid, toroomid, command, userid, roomid, txt) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    }

    public function addMessage($message)
    {
        return $this->addStmt->process($message->created, $message->toconnid, $message->touserid, $message->toroomid, $message->command, $message->userid, $message->roomid, $message->txt);
    }

    public function getMessages($connid, $userid, $roomid, $start = 0)
    {
        if ($userid) {
            $getStmt = new Statement("SELECT * FROM {$GLOBALS['config']['db']['pref']}messages WHERE (toconnid=? OR touserid=? OR toroomid=? OR (toconnid IS NULL AND touserid IS NULL AND toroomid IS NULL)) AND id>=? ORDER BY id");

            return new MessageQueueIterator($getStmt->process($connid, $userid, $roomid, $start));
        }

        $getStmt = new Statement("SELECT * FROM {$GLOBALS['config']['db']['pref']}messages WHERE toconnid=? AND id>=? ORDER BY id");

        return new MessageQueueIterator($getStmt->process($connid, $start));
    }
}

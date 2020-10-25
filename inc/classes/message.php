<?php

class Message
{
    public $id = null;

    public $created = null;

    public $toconnid = null;

    public $touserid = null;

    public $toroomid = null;

    public $command = null;

    public $userid = null;

    public $roomid = null;

    public $txt = null;

    public $login = null;

    public $color = null;

    public function __construct($command, $userid = null, $roomid = null, $txt = null, $color = null)
    {
        $this->command = $command;

        $this->userid = $userid;

        $this->roomid = $roomid;

        $this->color = htmlColor($color);

        if ($txt) {
            $this->txt = $this->parse($txt);
        }
    }

    public function parse($txt)
    {
        $txt = $this->replaceBadWord(strip_tags($txt, '<u>'));

        if ('msg' == $this->command) {
            $txt = $this->parseURL($txt);
        }

        return $txt;
    }

    public function replaceBadWord($inputString)
    {
        return str_replace($GLOBALS['config']['badWords'], $GLOBALS['config']['badWordSubstitute'], $inputString);
    }

    public function parseURL($inputString)
    {
        $inputTokens = explode(' ', $inputString);

        $input = '';

        foreach ($inputTokens as $token) {
            //smallest URL assumpted as a@a.us

            if (mb_strlen($token) > 5) {
                // check for email address

                if (false !== mb_strpos($token, '@')) {
                    $token = "<u><a href=\"mailto:$token\" style=\"color:{$this->color}\">$token</a></u>";
                } // check for https://, http://

                elseif ((false !== mb_strpos($token, 'http://')) || (false !== mb_strpos($token, 'https://'))) {
                    $token = "<u><a href=\"$token\" target=\"_blank\" style=\"color:{$this->color}\">$token</a></u>";
                } // check for www.

                elseif (false !== mb_strpos($token, 'www.')) {
                    $token = "<u><a href=\"http://$token\" target=\"_blank\" style=\"color:{$this->color}\">$token</a></u>";
                }
            }

            $input .= $token . ' ';
        }

        return trim($input);
    }

    public function toXML($tzoffset = 0)
    {
        $xml = "<{$this->command}";

        if ($this->id) {
            $xml .= " id=\"{$this->id}\"";
        }

        if ($this->touserid) {
            $xml .= " a=\"{$this->touserid}\"";
        }

        if ($this->userid) {
            $xml .= " u=\"{$this->userid}\"";
        }

        if ($this->roomid) {
            $xml .= " r=\"{$this->roomid}\"";
        }

        if ($this->login) {
            $xml .= " l=\"{$this->login}\"";
        }

        if ($this->created) {
            $xml .= ' t="' . format_Timestamp($this->created, $tzoffset) . '"';
        }

        if ($this->txt) {
            $xml .= "><![CDATA[{$this->txt}]]></{$this->command}>";
        } else {
            $xml .= '>';
        }

        return $xml;
    }
}

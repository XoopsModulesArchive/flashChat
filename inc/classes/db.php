<?php

define('STATEMENT_SELECT', 'select');
define('STATEMENT_INSERT', 'insert');
define('STATEMENT_UPDATE', 'update');
define('STATEMENT_DELETE', 'delete');

class Statement
{
    public $queryArray;

    public $type = STATEMENT_SELECT;

    public function __construct($queryStr)
    {
        $this->queryArray = preg_split('\?', $queryStr);

        $this->type = mb_strtolower(mb_substr($queryStr, 0, 6));
    }

    public function process(/*...*/)
    {
        if (func_num_args() > 0) {
            $params = func_get_args();
        } else {
            $params = [];
        }

        if ($conn = mysql_pconnect($GLOBALS['config']['db']['host'], $GLOBALS['config']['db']['user'], $GLOBALS['config']['db']['pass'])) {
            if (mysqli_select_db($GLOBALS['xoopsDB']->conn, $GLOBALS['config']['db']['base'], $conn)) {
                $queryStr = '';

                for ($i = 0; $i < count($this->queryArray) - 1; $i++) {
                    $val = '';

                    switch (gettype($params[$i])) {
                        case 'object':
                            $val = "'" . $GLOBALS['xoopsDB']->escape($params[$i]->toString()) . "'";
                            break;
                        case 'array':
                            $val = "'" . $GLOBALS['xoopsDB']->escape(implode(',', $params[$i])) . "'";
                            break;
                        case 'boolean':
                            $val = ($params[$i]) ? -1 : 0;
                            break;
                        case 'NULL':
                            $val = 'NULL';
                            break;
                        default:
                            $val = "'" . $GLOBALS['xoopsDB']->escape($params[$i]) . "'";
                            break;
                    }

                    $queryStr .= $this->queryArray[$i] . $val;
                }

                $queryStr .= $this->queryArray[$i];

                if ($result = $GLOBALS['xoopsDB']->queryF($queryStr, $conn)) {
                    switch ($this->type) {
                        case STATEMENT_SELECT:
                            return new ResultSet($result);
                        case STATEMENT_INSERT:
                            return $GLOBALS['xoopsDB']->getInsertId($conn);
                        default:
                            return $GLOBALS['xoopsDB']->getAffectedRows($conn);
                    }
                }
            }
        }

        trigger_error('MySQL error ' . $GLOBALS['xoopsDB']->errno() . ' : ' . $GLOBALS['xoopsDB']->error());

        return null;
    }
}

class ResultSet
{
    public $result;

    public $numRows = 0;

    public $currRow = 0;

    public function __construct($result = null)
    {
        $this->result = $result;

        if ($result) {
            $this->numRows = $GLOBALS['xoopsDB']->getRowsNum($result);
        }
    }

    public function hasNext()
    {
        return ($this->result && $this->numRows > $this->currRow);
    }

    public function next()
    {
        if ($this->hasNext()) {
            $this->currRow++;

            return $GLOBALS['xoopsDB']->fetchArray($this->result);
        }

        return null;
    }
}

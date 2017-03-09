<?php

namespace Leon\Argument;

use InvalidArgumentException;

class ArgumentById extends Argument
{
    protected $tableName;

    public function __construct($argument)
    {
        global $db;
        parent::__construct($argument);
        if (!$db->getOneById($this->tableName, $argument)) {
            Throw new InvalidArgumentException();
        }
    }
}

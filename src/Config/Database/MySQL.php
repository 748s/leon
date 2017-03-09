<?php

namespace Leon\Config\Database;

class MySQL extends Database
{
    protected $type;
    protected $host;
    protected $database;
    protected $user;
    protected $password;

    public function __construct(array $database)
    {
        $this->type = $database['type'];
        $this->host = $database['host'];
        $this->database = $database['database'];
        $this->user = $database['user'];
        $this->password = $database['password'];
    }

    public function getType()
    {
        return $this->type;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function getDatabase()
    {
        return $this->database;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getPassword()
    {
        return $this->password;
    }
}

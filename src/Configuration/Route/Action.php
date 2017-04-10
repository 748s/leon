<?php

namespace Leon\Configuration\Route;

class Action
{
    protected $name;
    protected $params = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }
    
    public function getName()
    {
        return $this->name;
    }

    public function addParam(Param $param)
    {
        $this->params[] = $param;
    }
    
    public function getParams()
    {
        return $this->params;
    }
}

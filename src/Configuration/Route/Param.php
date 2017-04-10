<?php

namespace Leon\Configuration\Route;

class Param
{
    /**
     * @var string $name
     */
    protected $name;
    
    /**
     * @var string $class (FQCN)
     */
    protected $class;
    
    /**
     * @var bool $isRequired
     */
    protected $isRequired;

    public function __construct(string $name, string $class, bool $isRequired)
    {
        $this->name = $name;
        $this->class = $class;
        $this->isRequired = $isRequired;
    }
    
    public function getName()
    {
        return $this->getName;
    }
    
    public function getClass()
    {
        return $this->class;
    }
    
    public function getIsRequired()
    {
        return $this->isRequired;
    }
}

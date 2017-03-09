<?php

namespace Leon\Argument;

class Argument
{
    protected $argument;

    public function __construct($argument)
    {
        $this->argument = $argument;
    }

    public function __toString()
    {
        return (string) $this->argument;
    }
}

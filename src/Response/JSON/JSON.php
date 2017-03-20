<?php

namespace Leon\Response\JSON;

use Leon\Response\Response;

abstract class JSON extends Response
{
    public function __construct()
    {
        header('Content-Type: application/json');
    }
}

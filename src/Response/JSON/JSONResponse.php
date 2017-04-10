<?php

namespace Leon\Response\JSON;

use Leon\Response\Response;

abstract class JSONResponse extends Response
{
    public function __construct()
    {
        header('Content-Type: application/json');
    }
}

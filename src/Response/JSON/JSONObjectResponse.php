<?php

namespace Leon\Response\JSON;

class JSONObjectResponse extends JSONResponse
{
    public function __construct(array $array)
    {
        parent::__construct();
        echo json_encode($array);
    }
}

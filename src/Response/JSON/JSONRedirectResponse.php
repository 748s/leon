<?php

namespace Leon\Response\JSON;

class JSONRedirectResponse extends JSONResponse
{
    public function __construct(string $location)
    {
        parent::__construct();
        echo json_encode([
            'location' => $location,
        ]);
    }
}

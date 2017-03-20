<?php

namespace Leon\Response\JSON;

class RedirectResponse extends JSON
{
    public function __construct(string $location)
    {
        parent::__construct();
        echo json_encode([
            'location' => $location,
        ]);
    }
}
<?php

namespace Leon\File\Type;

class CSV extends Type
{
    protected $name = 'csv';
    protected $extension = 'csv';
    protected $mimeType = 'text/csv';
    protected $allowableInferredMimeTypes = [
        'text/csv',
        'text/plain',
    ];
    protected $allowableSubmittedMimeTypes = [
        'text/csv'
    ];
}

<?php

namespace Leon\File\Type;

class XLS extends Type
{
    protected $name = 'xls';
    protected $extension = 'xls';
    protected $mimeType = 'application/vnd.ms-excel';
    protected $allowableInferredMimeTypes = [
        'application/vnd.ms-excel',
    ];
    protected $allowableSubmittedMimeTypes = [
        'application/vnd.ms-excel',
    ];
}

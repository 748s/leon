<?php

namespace Leon\File\Type;

class DOC extends Type
{
    protected $name = 'doc';
    protected $extension = 'doc';
    protected $mimeType = 'application/msword';
    protected $allowableInferredMimeTypes = [
        'application/msword'
    ];
    protected $allowableSubmittedMimeTypes = [
        'application/msword'
    ];
}

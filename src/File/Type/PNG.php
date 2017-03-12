<?php

namespace Leon\File\Type;

class PNG extends Type
{
    protected $name = 'png';
    protected $extension = 'png';
    protected $mimeType = 'image/png';
    protected $allowableInferredMimeTypes = [
        'image/png'
    ];
    protected $allowableSubmittedMimeTypes = [
        'image/png'
    ];
}

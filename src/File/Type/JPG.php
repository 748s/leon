<?php

namespace Leon\File\Type;

class JPG extends Type
{
    protected $name = 'jpg';
    protected $extension = 'jpg';
    protected $mimeType = 'image/jpeg';
    protected $allowableInferredMimeTypes = [
        'image/jpeg',
    ];
    protected $allowableSubmittedMimeTypes = [
        'image/jpeg',
    ];
}

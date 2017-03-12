<?php

namespace Leon\File\Type;

class GIF extends Type
{
    protected $name = 'gif';
    protected $extension = 'gif';
    protected $mimeType = 'image/gif';
    protected $allowableInferredMimeTypes = [
        'image/gif'
    ];
    protected $allowableSubmittedMimeTypes = [
        'image/gif'
    ];
}

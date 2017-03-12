<?php

namespace Leon\File\Type;

class PDF extends Type
{
    protected $name = 'pdf';
    protected $extension = 'pdf';
    protected $mimeType = 'application/pdf';
    protected $allowableInferredMimeTypes = [
        'application/pdf'
    ];
    protected $allowableSubmittedMimeTypes = [
        'application/pdf'
    ];
}

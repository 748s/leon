<?php

namespace Leon\File\Type;

class DOCX extends Type
{
    protected $name = 'docx';
    protected $extension = 'docx';
    protected $mimeType = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
    protected $allowableInferredMimeTypes = [
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ];
    protected $allowableSubmittedMimeTypes = [
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ];
}

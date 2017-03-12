<?php

namespace Leon\Form\Element;

use Leon\File\Type\DOC;
use Leon\File\Type\DOCX;
use Leon\File\Type\PDF;

class Document extends File
{
    protected $type = 'document';
    protected $fileTypes = [
        'doc' => 'application/msword',
        'docm' => 'application/vnd.ms-word.document.macroenabled.12',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'dot' => 'application/msword',
        'dotm' => 'application/vnd.ms-word.template.macroenabled.12',
        'dotx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
        'pdf' => 'application/pdf',
    ];
}

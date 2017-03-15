<?php

namespace Leon\Form\Element;

use Leon\File\Type\DOC;
use Leon\File\Type\DOCX;
use Leon\File\Type\PDF;

class Document extends File
{
    protected $type = 'document';
    protected $fileTypes = [
        DOC::class,
        DOCX::class,
        PDF::class,
    ];
}

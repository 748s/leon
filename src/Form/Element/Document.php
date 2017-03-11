<?php

namespace Leon\Form\Element;

class Document extends File
{
    protected $type = 'document';
    protected $fileTypes = [
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx
        'application/pdf',
    ];
}

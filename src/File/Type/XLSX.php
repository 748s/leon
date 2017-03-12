<?php

namespace Leon\File\Type;

class XLSX extends Type
{
    protected $name = 'xlsx';
    protected $extension = 'xlsx';
    protected $mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
    protected $allowableInferredMimeTypes = [
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ];
    protected $allowableSubmittedMimeTypes = [
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ];
}

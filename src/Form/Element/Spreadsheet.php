<?php

namespace Leon\Form\Element;

use Leon\File\Type\CSV;
use Leon\File\Type\XLS;
use Leon\File\Type\XLSX;

class Spreadsheet extends File
{
    protected $type = 'image';
    protected $fileTypes = [
        CSV::class,
        XLS::class,
        XLSX::class,
    ];
}

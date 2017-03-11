<?php

namespace Leon\Form\Element;

class Spreadsheet extends File
{
    protected $type = 'image';
    protected $fileTypes = [
        'application/vnd.ms-office', // xls
        'application/vnd.ms-excel',
        'text/plain',
        'text/csv',
        'text/tsv',
        'text/comma-separated-values',
    ];
}

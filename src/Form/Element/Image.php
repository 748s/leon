<?php

namespace Leon\Form\Element;

class Image extends File
{
    protected $type = 'image';
    protected $fileTypes = [
        'image/png',
        'image/jpeg',
        'image/gif',
        'image/tiff',
    ];
}

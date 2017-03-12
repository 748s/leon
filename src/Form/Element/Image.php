<?php

namespace Leon\Form\Element;

use Leon\File\Type\GIF;
use Leon\File\Type\JPG;
use Leon\File\Type\PNG;

class Image extends File
{
    protected $type = 'image';
    protected $fileTypes = [
        GIF::class,
        JPG::class,
        PNG::class,
    ];
}

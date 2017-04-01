<?php

namespace Leon\File\Type;

class JPG extends Type
{
    const NAME = 'jpg';
    const EXTENSION = 'jpg';
    const MIME_TYPE = 'image/jpeg';
    const INFERRED_MIME_TYPES = [
        'image/jpeg',
    ];
    const SUBMITTED_MIME_TYPES = [
        'image/jpeg',
    ];
}

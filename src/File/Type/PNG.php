<?php

namespace Leon\File\Type;

class PNG extends Type
{
    const EXTENSION = 'png';
    const MIME_TYPE = 'image/png';
    const INFERRED_MIME_TYPES = [
        'image/png'
    ];
    const SUBMITTED_MIME_TYPES = [
        'image/png'
    ];
}

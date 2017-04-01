<?php

namespace Leon\File\Type;

class DOC extends Type
{
    const EXTENSION = 'doc';
    const MIME_TYPE = 'application/msword';
    const INFERRED_MIME_TYPES = [
        'application/msword'
    ];
    const SUBMITTED_MIME_TYPES = [
        'application/msword'
    ];
}

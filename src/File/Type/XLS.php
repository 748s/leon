<?php

namespace Leon\File\Type;

class XLS extends Type
{
    const EXTENSION = 'xls';
    const MIME_TYPE = 'application/vnd.ms-excel';
    const INFERRED_MIME_TYPES = [
        'application/vnd.ms-excel',
    ];
    const SUBMITTED_MIME_TYPES = [
        'application/vnd.ms-excel',
    ];
}

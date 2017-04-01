<?php

namespace Leon\File\Type;

class CSV extends Type
{
    const EXTENSION = 'csv';
    const MIME_TYPE = 'text/csv';
    const INFERRED_MIME_TYPES = [
        'text/csv',
        'text/plain',
    ];
    const SUBMITTED_MIME_TYPES = [
        'text/csv'
    ];
}

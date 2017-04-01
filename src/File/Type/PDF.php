<?php

namespace Leon\File\Type;

class PDF implements Type
{
    const MIME_TYPE = 'application/pdf';
    const INFERRED_MIME_TYPES = [
        'application/pdf'
    ];
    const SUBMITTED_MIME_TYPES = [
        'application/pdf'
    ];
}

<?php

namespace Leon\File\Type;

class DOCX extends Type
{
    const EXTENSION = 'docx';
    const MIME_TYPE = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
    const INFERRED_MIME_TYPES = [
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ];
    const SUBMITTED_MIME_TYPES = [
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ];
}

<?php

namespace Leon\File\Type;

class XLSX extends Type
{
    const $name = 'xlsx';
    const $extension = 'xlsx';
    const $mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
    const $allowableInferredMimeTypes = [
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ];
    const $allowableSubmittedMimeTypes = [
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ];
}

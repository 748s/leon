<?php

namespace Leon\File\Type;

abstract class Type
{
    protected $name;
    protected $extension;
    protected $mimeType;
    protected $allowableInferredMimeTypes = [];
    protected $allowableSubmittedMimeTypes = [];

    public function getName()
    {
        return $this->name;
    }

    public function getExtension()
    {
        return $this->extension;
    }

    public function getMimeType()
    {
        return $this->mimeType;
    }

    public function getAllowableInferredMimeTypes()
    {
        return $this->allowableInferredMimeTypes;
    }

    public function getAllowableSubmittedMimeTypes()
    {
        return $this->allowableSubmittedMimeTypes;
    }
}

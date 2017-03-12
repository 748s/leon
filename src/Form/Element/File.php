<?php

namespace Leon\Form\Element;

use Leon\Form\Element\Element;
use Leon\Form\Validator;
use Leon\Utility\Utility;

abstract class File extends Element
{
    protected $type = 'file';
    protected $template = '@Leon/form/element/file.html.twig';
    protected $multipartFormData = true;
    protected $numFiles = 1;
    protected $name;
    protected $label;
    protected $showLabel = true;
    protected $fileTypeNameList = [];
    protected $showFileTypeNameList = false;
    protected $fileTypeExtensionList = [];
    protected $showFileTypeExtensionList = true;
    protected $maxFileSize;
    protected $showMaxFileSize = true;

    public function __construct($name)
    {
        $this->name = $name;
        $this->label = Utility::getLabelFromName($name);
        $this->setMaxFileSize(ini_get('upload_max_filesize'));
        foreach ($this->fileTypes as $typeName) {
            $fileType = new $typeName();
            $this->fileTypeNameList[] = $fileType->getName();
            $this->fileTypeExtensionList[] = $fileType->getExtension();
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setShowLabel(bool $showLabel)
    {
        $this->showLabel = $showLabel;

        return $this;
    }

    public function getShowLabel()
    {
        return $this->showLabel;
    }

    public function getMultipartFormData()
    {
        return $this->multipartFormData;
    }

    public function setNumFiles(int $numFiles)
    {
        $this->numFiles = $numFiles;

        return $this;
    }

    public function getNumFiles()
    {
        return $this->numFiles;
    }

    public function setMaxFileSize(string $maxFileSize)
    {
        $this->maxFileSize = $maxFileSize;

        return $this;
    }

    public function getMaxFileSize()
    {
        return $this->maxFileSize;
    }

    public function validate(Validator $validator)
    {
        if (!isset($_FILES[$this->name]) && $this->getNumFiles()) {
            $validator->setError($this->name, "You must upload an $this->type for $this->label");
            return;
        }
        if ($_FILES[$this->name]['error']) {
            switch ($_FILES[$this->name]['error']) {
                case 1:
                    $errorMessage = "Your $this->type for $this->label is too large.";
                break;
                case 2:
                    $errorMessage = "Your $this->type for $this->label is too large.";
                break;
                case 3:
                    $errorMessage = "Your $this->type for $this->label was only partially uploaded";
                break;
                case 4:
                    $errorMessage = "You must upload an $this->type for $this->label";
                break;
                default:
                    $errorMessage = "A system error occurred for $this->label (" . $_FILES[$this->name]['error'] . ")";
                break;
            }
            $validator->setError($this->name, $errorMessage);
            return;
        }
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $inferredMimeType = finfo_file($finfo, $_FILES[$this->name]['tmp_name']);
        $mimeType = false;
        $extension = false;
        for ($i = 0; $i < count($this->fileTypes); $i++) {
            $type = new $this->fileTypes[$i]();
            if (in_array($_FILES[$this->name]['type'], $type->getAllowableSubmittedMimeTypes()) &&
                in_array($inferredMimeType, $type->getAllowableInferredMimeTypes())
                ) {
                $mimeType = $type->getMimeType();
                $extension = $type->getExtension();
                $i = count($this->fileTypes);
            }
        }
        if (!$mimeType || !$extension) {
            $validator->setError($this->name, "$this->label is not one of the allowed types of files.");
            return;
        }
        $validator->setData($this->name, [
            'name' => $_FILES[$this->name]['name'],
            'type' => $mimeType,
            'extension' => $extension,
            'location' => $_FILES[$this->name]['tmp_name'],
        ]);
    }

    public function getFileTypeExtensionList()
    {
        return $this->fileTypeExtensionList;
    }
}

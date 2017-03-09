<?php

namespace Leon\Form;

use Exception;
use Leon\Form\Element\Element;
use Leon\Form\Validator;

class Form
{
    protected $isAjax = false;
    protected $title;
    protected $elements = [];
    protected $entity = [];
    protected $errors = [];
    protected $validator;
    protected $alert;
    protected $multipartFormData = false;

    public function __construct()
    {

    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setIsAjax(bool $isAjax)
    {
        $this->isAjax = $isAjax;

        return $this;
    }

    public function getIsAjax()
    {
        return $this->isAjax;
    }

    public function addElement(Element $element)
    {
        $this->elements[] = $element;
        if (method_exists($element, 'getMultipartFormData') && $element->getMultipartFormData()) {
            $this->setMultipartFormData(true);
        }

        return $this;
    }

    public function getElements()
    {
        return $this->elements;
    }

    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function setErrors($errors)
    {
        $this->errors = $errors;

        return $this;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function validate()
    {
        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            $this->validator = new Validator();
            foreach ($this->elements as $element) {
                if (method_exists($element, 'validate')) {
                    $element->validate($this->validator);
                }
            }
            $this->entity = $this->validator->getData();
            if ($this->errors = $this->validator->getErrors()) {
                $this->setAlert($this->errors);
            }
            return true;
        } else {
            return false;
        }
    }

    public function getValidator()
    {
        return $this->validator;
    }

    public function setAlert($errors)
    {
        $message = '<ul>';
        foreach ($errors as $formInput) {
            $message .= "<li>$formInput</li>";
        }
        $message .= '</ul>';
        $this->alert = [
            'type' => 'danger',
            'message' => $message,
            'dismissable' => false,
        ];
    }

    public function getAlert()
    {
        return $this->alert;
    }

    protected function setMultipartFormData(bool $multipartFormData)
    {
        $this->multipartFormData = $multipartFormData;

        return $this;
    }

    public function getMultipartFormData()
    {
        return $this->multipartFormData;
    }
}

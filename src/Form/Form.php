<?php

namespace Leon\Form;

use Exception;
use Leon\Form\Element\Element;
use Leon\Form\Validator;

class Form
{
    protected $isAjax = false;
    protected $action;
    protected $title;
    protected $data = [];
    protected $errors = [];
    protected $elements = [];
    protected $alert;
    protected $multipartFormData = false;

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

    public function setAction(string $action)
    {
        $this->action = $action;
        
        return $this;
    }

    public function getAction()
    {
        return $this->action;
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

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setErrors($errors)
    {
        $this->errors = $errors;
        $this->setAlert($errors);

        return $this;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function validate()
    {
        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            $validator = new Validator();
            foreach ($this->elements as $element) {
                if (method_exists($element, 'validate')) {
                    $element->validate($validator);
                }
            }
            return $validator;
        } else {
            return false;
        }
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

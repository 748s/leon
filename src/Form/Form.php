<?php

namespace Leon\Form;

use Exception;
use Leon\Form\Element\Element;
use Leon\Form\Validator;
use Leon\Utility\Utility;

class Form
{
    protected $id;
    protected $classes = [];
    protected $isAjax = false;
    protected $action;
    protected $title;
    protected $data = [];
    protected $errors = [];
    protected $elements = [];
    protected $alert;
    protected $multipartFormData = false;
    protected $submitButtonText = 'Submit';
    protected $template = '@Leon/form/form.html.twig';
    protected $useJqueryValidate = true;

    public function __construct()
    {
        global $config;
        $this->id = str_replace(['\\', 'Controller'], '', $config->getController()) . 'Form';
        $this->addClass(str_replace(['\\', 'Controller'], '', $config->getController()) . 'Form');
        $this->action = $config->getQueryString();
    }

    public function setId(string $id)
    {
        $this->id = $id;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function addClass(string $class)
    {
        $this->classes[] = $class;

        return $this;
    }

    public function getClasses()
    {
        return $this->classes;
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
        if ($isAjax) {
            $this->addClass('ajaxForm');
        }

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
    
    public function addElement(string $class, array $configuration)
    {
        $element = Utility::instantiate($class, $configuration, true);
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

    public function setSubmitButtonText(string $submitButtonText)
    {
        $this->submitButtonText = $submitButtonText;

        return $this;
    }

    public function getSubmitButtonText()
    {
        return $this->submitButtonText;
    }

    public function setTemplate(string $template)
    {
        $this->template = $template;

        return $this;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function setUseJqueryValidate(bool $useJqueryValidate)
    {
        $this->useJqueryValidate = $useJqueryValidate;
        
        return $this;
    }

    public function getUseJqueryValidate()
    {
        return $this->useJqueryValidate;
    }
}

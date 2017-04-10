<?php

namespace Leon\Configuration\Route;

use Exception;
use InvalidArgumentException;
use Leon\Configuration\Configuration;

class Route
{
    protected $class;
    protected $path;
    protected $indexAction;
    protected $getAction;
    protected $formAction;
    protected $deleteAction;
    protected $ajaxAction;
    protected $requireLogin;

    public function __construct(string $class, string $path)
    {
        $this->class = $class;
        $this->path = $path;
    }

    public function getClass()
    {
        return $this->class;
    }
    
    public function getPath()
    {
        return $this->path;
    }
    
    public function setAction(string $name, Action $action)
    {
        switch ($name) {
            case 'indexAction':
                $this->indexAction = $action;
            break;
            case 'getAction':
                $this->getAction = $action;
            break;
            case 'formAction':
                $this->formAction = $action;
            break;
            case 'deleteAction':
                $this->deleteAction = $action;
            break;
            case 'ajaxAction':
                $this->ajaxAction = $action;
            break;
            default:
                Throw new InvalidArgumentException();
            break;
        }
    }
    
    public function getAction(string $name)
    {
        switch ($name) {
            case 'indexAction':
                return $this->indexAction;
            break;
            case 'getAction':
                return $this->getAction;
            break;
            case 'formAction':
                return $this->formAction;
            break;
            case 'deleteAction':
                return $this->deleteAction;
            break;
            case 'ajaxAction':
                return $this->ajaxAction;
            break;
            default:
                Throw new InvalidArgumentException();
            break;
        }
    }
    
    public function setIndexAction(Action $indexAction)
    {
        $this->indexAction = $indexAction;
    }
    
    public function getIndexAction()
    {
        return $this->indexAction;
    }
    
    public function setGetAction(Action $getAction)
    {
        $this->getAction = $getAction;
    }
    
    public function getGetAction()
    {
        return $this->getAction;
    }
    
    public function setFormAction(Action $formAction)
    {
        $this->formAction = $formAction;
    }
    
    public function getFormAction()
    {
        return $this->formAction;
    }
    
    public function setDeleteAction(Action $deleteAction)
    {
        $this->deleteAction = $deleteAction;
    }
    
    public function getDeleteAction()
    {
        return $this->deleteAction;
    }
    
    public function setRequireLogin(bool $requireLogin)
    {
        $this->requireLogin = $requireLogin;
    }
    
    public function getRequireLogin()
    {
        return $this->requireLogin;
    }
}

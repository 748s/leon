<?php

namespace Leon\Route;

use InvalidArgumentException;

/**
 * Router
 *
 * @Author Nick Wakeman <nick@thehiredgun.tech>
 * @since  1.0.0 (2017-02-20)
 */
class Router
{
    protected $defaultController = 'App\Controller\Controller';
    protected $config;
    protected $segments = [];
    protected $keywordsActions = [
        'ajax'      => 'ajaxAction',
        'add'       => 'formAction',
        'edit'      => 'formAction',
        'delete'    => 'deleteAction',
    ];
    protected $route = null;
    protected $action = null;
    protected $prefixArguments = [];
    protected $arguments = [];

    public function __construct()
    {
        global $config;
        $this->config = $config;
    }

    public function route()
    {
        // turn the URI into an array of segments
        $this->segmentRequest();
        // pull out the pathPrefixes
        if (!$this->getPrefixArguments()) {
            $this->notFoundAction();
            return;
        }
        // find the best controller path for the request
        $this->matchRoute();
        if (!$this->route) {
            $this->notFoundAction();
            return;
        }
        
        $this->matchAction();
        if (!isset($this->route->actions->{$this->action}) or !$this->route->actions->{$this->action}->isActive) {
            $this->notFoundAction();
            return;
        }
        
        if (!$this->setArguments()) {
            $this->notFoundAction();
            return;
        }

        $this->config->setController($this->route->FQCN);
        $controller = new $this->route->FQCN();
        if ($this->route->requireLogin && !$controller->getIsLoggedIn()) {
            $this->config->setAction('unauthorizedAction');
            $controller->unauthorizedAction();
            return;
        }

        if (method_exists($controller, 'getPermission') && !$controller->getPermission($this->action, $this->arguments)) {
            $this->config->setAction('forbiddenAction');
            $controller->forbiddenAction();
            return;
        }

        if ($this->prefixArguments) {
            try {
                call_user_func_array([$controller, 'setPathPrefix'], $this->prefixArguments);
            } catch (InvalidArgumentException $e) {
                $this->config->setAction('notFoundAction');
                $controller->notFoundAction();
                return;
            }
        }
        $this->config->setAction($this->action);
        $this->config->setPrefixArguments($this->prefixArguments);
        $this->config->setArguments($this->arguments);
        call_user_func_array([$controller, $this->action], $this->arguments);
    }

    protected function notFoundAction()
    {
        $this->config->setController($this->defaultController);
        $this->config->setAction('notFoundAction');
        $controller = new $this->defaultController();
        $controller->notFoundAction();
    }

    protected function getPrefixArguments()
    {
        foreach ($this->config->getPathPrefixes() as $pathPrefix) {
            try {
                $this->prefixArguments[] = new $pathPrefix->argumentClass(array_shift($this->segments));
            } catch (InvalidArgumentException $e) {
                return false;
            }
        }
        return true;
    }

    protected function setArguments()
    {
        foreach ($this->route->actions->{$this->action}->params as $param) {
            if ($param->isRequired) {
                if (empty($this->segments)) {
                    return false;
                } else {
                    try {
                        $this->arguments[] = new $param->argumentClass(array_shift($this->segments));
                    } catch (InvalidArgumentException $e) {
                        return false;
                    }
                }
            } else {
                if (count($this->segments)) {
                    try {
                        $this->arguments[] = new $param->argumentClass(array_shift($this->segments));
                    } catch (InvalidArgumentException $e) {
                        return false;
                    }
                }
            }
        }
        return (empty($this->segments));
    }

    protected function matchAction()
    {
        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            $this->action = 'formAction';
            $this->segments = array_diff($this->segments, ['add', 'edit']);
            return;
        }
        foreach ($this->keywordsActions as $keyword => $action) {
            if (in_array($keyword, $this->segments)) {
                $this->segments = array_diff($this->segments, [$keyword]);
                $this->action = $action;
            }
        }
        if (is_null($this->action)) {
            $this->action = (empty($this->segments)) ? 'indexAction' : 'getAction';
        }
    }

    protected function matchRoute()
    {
        $routes = $this->config->getRoutes();
        if (empty($this->segments)) {
            $this->route = $routes['/'];
            return;
        }
        foreach ($routes as $path => $route) {
            if ($path !== '/') {
                $requestString = '/' . implode('/', $this->segments) . '/';
                if (0 === strpos($requestString, $path)) {
                    $this->segments = explode('/', str_replace($path, '', $requestString));
                    $this->segments = array_filter($this->segments, function($value) { return $value !== ''; });
                    $this->route = $route;
                    return;
                }
            }
        }
    }

    protected function segmentRequest()
    {
        $request = explode('?', $_SERVER['REQUEST_URI'])[0];
        $requestArray = explode('/', $request);
        if (count($requestArray) > 0) {
            foreach($requestArray as $segment) {
                if ($segment) {
                    $this->segments[] = $segment;
                }
            }
        }
    }
}

<?php

namespace Leon\Configuration;

use InvalidArgumentException;
use Leon\Configuration\Configuration;
use Leon\Configuration\Route\Route;
use Leon\Configuration\Route\Action;
use Leon\Configuration\Route\Param;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use StdClass;

class Routing
{
    /**
     * @var Config $configuration
     */
    protected $configuration;
    
    /**
     * @var array $controllerActions
     *   an array of controller actions
     *   the keys refer to their setters in Config\Route\Route
     */
    protected $controllerActions = [
        'indexAction',
        'getAction',
        'formAction',
        'deleteAction',
        'ajaxAction',
    ];
    
    /**
     * @var string $appControllerDirectory
     */
    protected $appControllerDirectory = './App/Controller';
    
    /**
     * @var string $appControllerNamespace
     */
    protected $appControllerNamespace = 'App\Controller';

    protected $defaultController = 'App\Controller\Controller';
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
    protected $actionArguments = [];

    public function __construct()
    {
        global $configuration;
        $this->config = $configuration;
    }

    /**
     * setPathPrefixes
     * if the root project's controller has the method setPathPrefix,
     *  get information about the arguments it takes
     */
    public function setPathPrefixes()
    {
        $pathPrefixes = [];
        $FQCN = 'App\Controller\Controller';
        $rc = new ReflectionClass($FQCN);
        if ($rc->hasMethod('setPathPrefix')) {
            $rm = new ReflectionMethod($FQCN, 'setPathPrefix');
            if ($params = $rm->getParameters()) {
                foreach ($params as $param) {
                    $pathPrefix = new StdClass();
                    $pathPrefix->argumentClass = $param->getClass()->name;
                    $pathPrefix->isRequired = !$param->isOptional();
                    $pathPrefixes[] = $pathPrefix;
                }
            }
        }
        $this->config->setPathPrefixes($pathPrefixes);
    }

    /**
     * setRoutes()
     * get information about all of the valid routes within the application
     */
    public function setRoutes()
    {
        $routes = [];
        $controllerFQCNs = [];
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->appControllerDirectory));
        if (count($files)) {
            foreach ($files as $file) {
                if ('php' === strtolower(pathinfo($file, PATHINFO_EXTENSION))) {
                    $controllerFQCNs[] =
                        $this->appControllerNamespace .
                        str_replace(
                            [$this->appControllerDirectory, '/'],
                            ['', '\\'],
                            pathinfo($file, PATHINFO_DIRNAME)
                        ) .
                        '\\' .
                        pathinfo($file, PATHINFO_FILENAME)
                    ;
                }
            }
        }

        foreach ($controllerFQCNs as $FQCN) {
//        echo $FQCN . "<br />";
            try {
                $rc = new ReflectionClass($FQCN);
                if ($docComment = $rc->getDocComment()) {
                    $lines = explode("\n", $docComment);
                    $routeAnnotation = null;
                    $requireLoginAnnotation = null;
                    foreach ($lines as $line) {
                        if (strstr($line, '* @Route')) {
                            $routeAnnotation = $line;
                        } elseif (strstr($line, '* @RequireLogin')) {
                            $requireLoginAnnotation = $line;
                        }
                    }
                    if ($routeAnnotation) {
//                    echo 'hello';
                        $path = trim(ltrim($routeAnnotation, '* @Route'));
                        $path = '/' . trim($path, '/') . '/';
                        $path = ($path === '//') ? '/' : $path;
                        $route = new Route($FQCN, $path);
                        foreach ($this->controllerActions as $methodName) {
                            if ($rc->hasMethod($methodName)) {
                                $action = new Action($methodName);
                                $route->setAction($methodName, $action);
                                $rm = new ReflectionMethod($FQCN, $methodName);
                                if ($params = $rm->getParameters()) {
                                    foreach ($params as $param) {
                                        $action->addParam(new Param(
                                            $param->getName(),
                                            ($param->getClass()) ? $param->getClass()->name : '',
                                            !$param->isOptional()
                                        ));
                                    }
                                }
                            }
                        }
                        if ($requireLoginAnnotation) {
                            $route->setRequireLogin(true);
                        }
                        $routes[$path] = $route;
//                        print_r($route);
//                        echo '<br /><br />';
                    }
                }
            } catch (ReflectionException $e) {
                // @Todo log exception
            }
        }
        // order routes from longest to shortest
        $keys = array_map('strlen', array_keys($routes));
        array_multisort($keys, SORT_DESC, $routes);
        $this->config->setRoutes($routes);
    }

    public function findRoute()
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
        $this->config->setRoute($this->route);

        $this->matchAction();
        if (!$action = $this->route->getAction($this->action)) {
            $this->notFoundAction();
            return;
        }

        if (!$this->setActionArguments($action)) {
            $this->notFoundAction();
            return;
        }

        $this->config->setController($this->route->getClass());
        $this->config->setAction($this->action);
        $this->config->setPrefixArguments($this->prefixArguments);
        $this->config->setActionArguments($this->actionArguments);
        return;
    }

    protected function notFoundAction()
    {
        $this->config->setController($this->defaultController);
        $this->config->setAction('notFoundAction');
        return;
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

    protected function setActionArguments(Action $action)
    {
        foreach ($action->getParams() as $param) {
            if ($param->getIsRequired()) {
                if (empty($this->segments)) {
                    return false;
                } else {
                    try {
                        if ($argumentClass = $param->getClass()) {
                            $this->actionArguments[] = new $argumentClass(array_shift($this->segments));
                        } else {
                            $this->actionArguments[] = array_shift($this->segments);
                        }
                    } catch (InvalidArgumentException $e) {
                        return false;
                    }
                }
            } else {
                if (count($this->segments)) {
                    try {
                        if ($argumentClass = $param->getClass()) {
                            $this->actionArguments[] = new $argumentClass(array_shift($this->segments));
                        } else {
                            $this->actionArguments[] = array_shift($this->segments);
                        }
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
        $this->config->setQueryString($request);
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

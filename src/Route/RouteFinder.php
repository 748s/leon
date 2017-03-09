<?php

namespace Leon\Route;

use InvalidArgumentException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use StdClass;

/**
 * RouteFinder
 *
 * @author Nick Wakeman <nick@thehiredgun.tech>
 * @since  1.0.0 2017-02-19
 */
class RouteFinder
{
    protected $controllerActions = [
        'indexAction',
        'getAction',
        'formAction',
        'deleteAction',
        'ajaxAction',
    ];
    protected $appControllerDirectory = './App/Controller';
    protected $appControllerNamespace = 'App\Controller';

    public function getPathPrefixes()
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
        return $pathPrefixes;
    }

    public function getRoutes()
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
        $routeAnnotation = '* @Route';
        foreach ($controllerFQCNs as $FQCN) {
            try {
                $rc = new ReflectionClass($FQCN);
                if ($docComment = $rc->getDocComment()) {
                    $lines = explode("\n", $docComment);
                    foreach ($lines as $line) {
                        if (strstr($line, $routeAnnotation)) {
                            $path = trim(substr($line, strpos($line, $routeAnnotation) + mb_strlen($routeAnnotation)));
                            $path = '/' . trim($path, '/') . '/';
                            $path = ($path === '//') ? '/' : $path;
                            $route =  new StdClass();
                            $route->path = $path;
                            $route->FQCN = $FQCN;
                            $route->actions = new StdClass();
                            foreach ($this->controllerActions as $action) {
                                $route->actions->{$action} = new StdClass();
                                if (!$rc->hasMethod($action)) {
                                    $route->actions->{$action}->isActive = false;
                                } else {
                                    $route->actions->{$action}->isActive = true;
                                    $rm = new ReflectionMethod($FQCN, $action);
                                    $route->actions->{$action}->params = [];
                                    if ($params = $rm->getParameters()) {
                                        foreach ($params as $param) {
                                            $paramName = $param->getName();
                                            $route->actions->{$action}->params[$paramName] = new StdClass();
                                            $route->actions->{$action}->params[$paramName]->argumentClass = $param->getClass()->name;
                                            $route->actions->{$action}->params[$paramName]->isRequired = !$param->isOptional();
                                        }
                                    }
                                }
                            }
                            $route->requireLogin =
                                (false !== stripos($docComment, '@RequireLogin'))
                            ;
                            $routes[$path] = $route;
                        }
                    }
                }
            } catch (ReflectionException $e) {
                // @Todo log exception
            }
        }
        // order routes from longest to shortest
        $keys = array_map('strlen', array_keys($routes));
        array_multisort($keys, SORT_DESC, $routes);
        return $routes;
    }
}

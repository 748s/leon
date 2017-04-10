<?php

namespace Leon\Configuration;

use DateTimeZone;
use Exception;
use InvalidArgumentException;
use Leon\Configuration\Database\MySQL;
use Leon\Configuration\Route\Route;

/**
 * Configuration
 * Setup and validate configuration properties for the application and the request
 *
 * @author Nick Wakeman <nick@thehiredgun.tech>
 * @since  1.0.0 (2017-03-01)
 */
class Configuration
{
    protected $source;
    protected $startTime;
    protected $environment;
    protected $errorReporting;
    protected $displayErrors;
    protected $timeZone;
    protected $protocol;
    protected $domain;
    protected $database;
    protected $errorLog;
    protected $requestLog;
    protected $pathPrefixes;
    protected $routes;
    
    /**
     * @var Route $route
     * the route containing info about the selected controller
     */
    protected $route;

    /**
     * @var string $controller 
     * the controller which handles the request
     */
    protected $controller;

    /**
     * @var string $action
     * the action which handles the request
     */
    protected $action;
    protected $prefixArguments = [];
    protected $actionArguments = [];
    protected $queryString;
    protected $view;
    protected $permission;

    public function __construct()
    {
        $this->source = yaml_parse_file('./configuration.yml');
        $this->startTime = microtime(true);
        $this->setTimeZone();
        $this->setEnvironment();
        $this->setProtocol();
        $this->setDomain();
        $this->setDatabase();
        $this->setErrorLog();
        $this->setRequestLog();
        $this->setView();
        $this->setPermission();
    }

    protected function getSource($key)
    {
        if (!isset($this->source[$key])) {
            Throw new InvalidArgumentException('Key not found in config.yml');
        }
        return $this->source[$key];
    }

    public function getStartTime()
    {
        return $this->startTime;
    }

    protected function setEnvironment()
    {
        $environment = $this->getSource('environment');
        if (!in_array($environment, ['development', 'staging', 'production'])) {
            Throw new InvalidArgumentException("config->environment must be 'development', 'staging', or 'production'");
        }
        $this->environment = $environment;
        switch (strtolower($this->environment)) {
            case 'development':
                $this->errorReporting = -1;
                $this->displayErrors = true;
            break;
            case 'staging':
            case 'production':
                $this->errorReporting = E_WARNING;
                $this->displayErrors = false;
            break;
        }
    }

    public function getEnvironment()
    {
        return $this->environment;
    }

    public function getErrorReporting()
    {
        return $this->errorReporting;
    }

    public function getDisplayErrors()
    {
        return $this->displayErrors;
    }

    protected function setTimeZone()
    {
        $timeZone = $this->getSource('timeZone');
        try {
            new DateTimeZone($timeZone);
            $this->timeZone = $timeZone;
        } catch (Exception $e) {
            Throw new InvalidArgumentException("'$timeZone' is not a valid timeZone");
        }
    }

    public function getTimeZone()
    {
        return $this->timeZone;
    }

    protected function setProtocol()
    {
        $protocol = $this->getSource('protocol');
        if (!in_array($protocol, ['http', 'https'])) {
            Throw new InvalidArgumentException("'$protocol' is not a valid value for config->protocol. It must be either 'http' or 'https'");
        }

        $this->protocol = $protocol;
    }

    public function getProtocol()
    {
        return $this->protocol;
    }

    protected function setDomain()
    {
        $this->domain = $this->getSource('domain');
    }

    public function getDomain()
    {
        return $this->domain;
    }

    protected function setDatabase()
    {
        $database = $this->getSource('database');
        switch ($database['type']) {
            case 'mysql':
                $this->database = new MySQL($database);
            break;
            default:
                Throw new InvalidArgumentException("Database type '$database[type]' not recognized");
            break;
        }
    }

    public function getDatabase()
    {
        return $this->database;
    }

    protected function setErrorLog()
    {
        $this->errorLog = $this->getSource('errorLog');
    }

    public function getErrorLog()
    {
        return $this->errorLog;
    }

    protected function setRequestLog()
    {
        $this->requestLog = $this->getSource('requestLog');
    }

    public function getRequestLog()
    {
        return $this->requestLog;
    }

    public function setPathPrefixes($pathPrefixes)
    {
        $this->pathPrefixes = $pathPrefixes;

        return $this;
    }

    public function getPathPrefixes()
    {
        return $this->pathPrefixes;
    }

    public function setRoutes($routes)
    {
        $this->routes = $routes;

        return $this;
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function setRoute(Route $route)
    {
        $this->route = $route;
        
        return $this;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function setController($controller)
    {
        $this->controller = $controller;

        return $this;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function setPrefixArguments(array $prefixArguments = [])
    {
        $this->prefixArguments = $prefixArguments;
        
        return $this;
    }

    public function getPrefixArguments()
    {
        return $this->prefixArguments;
    }

    public function setActionArguments(array $actionArguments = [])
    {
        $this->actionArguments = $actionArguments;
        
        return $this;
    }

    public function getActionArguments()
    {
        return $this->actionArguments;
    }

    public function setQueryString(string $queryString)
    {
        $this->queryString = $queryString;

        return $this;
    }

    public function getQueryString()
    {
        return $this->queryString;
    }

    protected function setView()
    {
        $this->view = new class($this->getSource('view')['class']) {
            protected $class;

            public function __construct($class)
            {
                $this->class = $class;
            }

            public function getClass()
            {
                return $this->class;
            }
        };

        return $this;
    }

    public function getView()
    {
        return $this->view;
    }

    protected function setPermission()
    {
        $this->permission = new class($this->getSource('permission')['class']) {
            protected $class;

            public function __construct($class)
            {
                $this->class = $class;
            }

            public function getClass()
            {
                return $this->class;
            }
        };
        
        return $this;
    }

    public function getPermission()
    {
        return $this->permission;
    }
}

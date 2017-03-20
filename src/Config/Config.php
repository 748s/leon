<?php

namespace Leon\Config;

use DateTimeZone;
use InvalidArgumentException;
use Leon\Config\Database\MySQL;
use StdClass;

/**
 * Config
 * Setup and validate configuration properties for the rest of the application
 *
 * @author Nick Wakeman <nick@thehiredgun.tech>
 * @since  1.0.0 (2017-03-01)
 */
class Config
{
    private $source;
    protected $startTime;
    protected $environment;
    protected $timeZone;
    protected $protocol;
    protected $domain;
    protected $database;
    protected $errorLog;
    protected $requestLog;
    protected $pathPrefixes;
    protected $routes;
    protected $controller;
    protected $action;
    protected $prefixArguments = [];
    protected $arguments = [];
    protected $queryString;
    protected $viewFQCN = '\Leon\View';

    public function __construct()
    {
        $this->source = yaml_parse_file('./config.yml');
        $this->setTimeZone();
        $this->setStartTime();
        $this->setEnvironment();
        $this->setProtocol();
        $this->setDomain();
        $this->setWebsiteTitle();
        $this->setDatabase();
        $this->setErrorLog();
        $this->setRequestLog();
    }

    protected function getSource($key)
    {
        if (!isset($this->source[$key])) {
            Throw new InvalidArgumentException();
        }
        return $this->source[$key];
    }

    protected function setStartTime()
    {
        $this->startTime = microtime(true);
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
    }

    public function getEnvironment()
    {
        return $this->environment;
    }

    protected function setTimeZone()
    {
        $timeZone = $this->getSource('timeZone');
        date_default_timezone_set($timeZone);
        $this->timeZone = new DateTimeZone($timeZone);
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

    protected function setWebsiteTitle()
    {
        $this->websiteTitle = $this->getSource('websiteTitle');
    }

    public function getWebsiteTitle()
    {
        return $this->websiteTitle;
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

    public function setArguments(array $arguments = [])
    {
        $this->arguments = $arguments;
        
        return $this;
    }

    public function getArguments()
    {
        return $this->arguments;
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

    public function getViewFQCN()
    {
        return $this->viewFQCN;
    }
}

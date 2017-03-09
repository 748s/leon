<?php

namespace Leon;

class Log
{
    protected $db;
    protected $environment;
    protected $requestLog;
    protected $errorLog;

    public function __construct()
    {
        global $config, $db;
        $this->db = $db;
        $this->environment = $config->getEnvironment();
        $this->errorLog = $config->getErrorLog();
        $this->requestLog = $config->getRequestLog();
        switch (strtolower($this->environment)) {
            case 'development':
                $this->errorReporting = -1;
                $displayErrors = true;
            break;
            case 'staging':
            case 'production':
                $this->errorReporting = E_WARNING;
                $displayErrors = false;
            break;
        }

        ini_set('error_reporting', $this->errorReporting);
        ini_set('display_errors', $displayErrors);
        if ($this->errorLog) {
            set_error_handler([$this, 'logError'], $this->errorReporting);
        }
        if ($this->requestLog) {
            register_shutdown_function([$this, 'logShutdown']);
        }
    }

    public function logError($type, $message, $file, $line)
    {
        $this->db->insertOne($this->errorLog, [
            'type'          => $type,
            'message'       => $message,
            'file'          => $file,
            'line'          => $line,
            'uri'           => isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null,
            'post'          => 'POST' === $_SERVER['REQUEST_METHOD'] ? 1 : 0,
            'userId'        => isset($_SESSION['userId']) ? $_SESSION['userId'] : 0,
            'ipIddress'     => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null,
            'userAgent'     => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null,
            'referer'       => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null,
        ]);

        // if config->environment == 'development': also print errors to screen
        if ('development' === $this->environment) {
            return false;
        }
    }

    public function logRequest()
    {
        global $config;
        $this->db->insertOne($this->requestLog, [
            'uri'           => isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null,
            'controller'    => $config->getController(),
            'action'        => $config->getAction(),
            'post'          => 'POST' === $_SERVER['REQUEST_METHOD'] ? 1 : 0,
            'userId'        => isset($_SESSION['userId']) ? $_SESSION['userId'] : 0,
            'ipAddress'     => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null,
            'userAgent'     => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null,
            'referer'       => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null,
            'responseTime'  => microtime(true) - $config->getStartTime(),
            'numQueries'    => $this->db->getNumQueries(),
        ]);
    }

    public function logShutdown()
    {
        if ($error = error_get_last()) {
            if (in_array($error['type'], [1, 4])) {
                $this->logError($error['type'], $error['message'], $error['file'], $error['line']);
            }
        }
    }

}

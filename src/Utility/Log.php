<?php

namespace Leon\Utility;

use Leon\Configuration\Configuration;
use Leon\Database\Database;

class Log
{
    protected $db;
    protected $configuration;

    public function __construct()
    {
        global $configuration, $db;
        $this->db = $db;
        $this->configuration = $configuration;
        if ($this->configuration->getErrorLog()) {
            set_error_handler([$this, 'logError'], $this->configuration->getErrorReporting());
        }
        if ($this->configuration->getRequestLog()) {
            register_shutdown_function([$this, 'logShutdown']);
        }
    }

    public function logError($type, $message, $file, $line)
    {
        $this->db->insertOne($this->configuration->getErrorLog(), [
            'type'          => $type,
            'message'       => $message,
            'file'          => $file,
            'line'          => $line,
            'uri'           => isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null,
            'requestMethod' => $_SERVER['REQUEST_METHOD'],
            'userId'        => isset($_SESSION['userId']) ? $_SESSION['userId'] : 0,
            'ipIddress'     => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null,
            'userAgent'     => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null,
            'referer'       => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null,
        ]);

        // if config->environment == 'development': also print errors to screen
        if ('development' === $this->configuration->getEnvironment()) {
            return false;
        }
    }

    public function logRequest()
    {
        $this->db->insertOne($this->configuration->getRequestLog(), [
            'uri'           => isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null,
            'controller'    => $this->configuration->getController(),
            'action'        => $this->configuration->getAction(),
            'requestMethod' => $_SERVER['REQUEST_METHOD'],
            'userId'        => isset($_SESSION['userId']) ? $_SESSION['userId'] : 0,
            'ipAddress'     => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null,
            'userAgent'     => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null,
            'referer'       => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null,
            'responseTime'  => microtime(true) - $this->configuration->getStartTime(),
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

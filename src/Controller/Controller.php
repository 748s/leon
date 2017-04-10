<?php

namespace Leon\Controller;

use InvalidArgumentException;
use Leon\Response\HTMLResponse;
use ReflectionClass;

/**
 * @author Nick Wakeman <nick@thehiredgun.tech>
 * @since  2016-10-09
 *
 * @todo save request if not logged in,
 * @todo then upon login resubmit their request
 * @todo add twig function to get routes in template
 * @todo add authentication for API's???
 */
abstract class Controller
{
    protected $configuration;
    protected $db;
    protected $permission;
    protected $view;

    public function __construct()
    {
        global $configuration, $db;
        $this->configuration = $configuration;
        $this->db = $db;
        $permissionClass = $this->configuration->getPermission()->getClass();
        $this->permission = new $permissionClass();
    }

    public function getIsLoggedIn()
    {
        return $this->permission->getIsLoggedIn();
    }

    public function unauthorizedAction()
    {
        header("HTTP/1.0 401 Unauthorized");
        header("Location: /login");
    }

    public function forbiddenAction()
    {
        header("HTTP/1.0 403 Forbidden");
        return new HTMLResponse('@Leon/forbidden.index.html.twig');
    }

    public function notFoundAction()
    {
        header("HTTP/1.0 404 Not Found");
        return new HTMLResponse('@Leon/notFound.index.html.twig');
    }

    public function internalServerErrorAction()
    {
        header("HTTP/1.0 500 Internal Server Error");
        return new HTMLResponse('@Leon/internalServerError.index.html.twig');
    }

    public function setAlert($type, $message, $dismissable = true)
    {
        if (!in_array($type, [
            'success',
            'info',
            'warning',
            'danger'
        ])) {
            Throw new InvalidArgumentException('The class name for that alert is not valid');
        }

        $_SESSION['alert'] = [
            'type'          => $type,
            'message'       => $message,
            'dismissable'   => $dismissable
        ];
    }
}

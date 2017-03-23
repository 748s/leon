<?php

namespace Leon;

use Twig_Environment;
use Twig_Extension_Debug;
use Twig_Loader_Filesystem;
use Twig_SimpleFilter;

class View
{
    protected $twig;
    protected $globals = [];

    public function __construct()
    {
        global $config;
        $TwigLoader = new Twig_Loader_Filesystem($_SERVER['DOCUMENT_ROOT'] . '/App/Template');
        $TwigLoader->addPath('./vendor/thehiredgun/leon/src/Template', 'Leon');
        if ('production' !== $config->getEnvironment()) {
            $this->twig = new Twig_Environment($TwigLoader, ['debug' => true]);
        } else {
            $this->twig = new Twig_Environment($TwigLoader, ['debug' => false]);
        }
        $this->addGlobal(['config' => $config]);
        $this->addGlobal(['session' => $_SESSION]);
        $this->addGlobal(['websiteTitle' => $config->getWebsiteTitle()]);
        $this->addGlobal(['alert' => $this->getAlert()]);
    }

    protected function addGlobal($array)
    {
        $this->globals = array_merge($this->globals, $array);
    }

    public function render($template, $vars = [])
    {
        return $this->twig->render($template, $this->mergeVars($vars));
    }

    protected function mergeVars($vars)
    {
        return array_merge(
            $this->globals,
            $vars
        );
    }

    protected function getAlert()
    {
        $alert = (isset($_SESSION['alert'])) ? $_SESSION['alert'] : null;
        unset($_SESSION['alert']);
        return $alert;
    }
}

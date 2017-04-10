<?php

namespace Leon\Response;

class HTMLResponse extends Response
{
    public function __construct(string $template, array $vars = [])
    {
        global $configuration;
        $class = $configuration->getView()->getClass();
        $view = new $class();
        echo $view->render($template, $vars);
    }
}

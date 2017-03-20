<?php

namespace Leon\Response;

class HTMLResponse extends Response
{
    public function __construct(string $template, array $vars = [])
    {
        global $config;
        $viewFQCN = $config->getViewFQCN();
        $view = new $viewFQCN();
        echo $view->render($template, $vars);
    }
}
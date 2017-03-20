<?php

namespace Leon\Response\JSON;

class ReplaceByIdResponse extends JSON
{
    public function __construct(array $array)
    {
        parent::__construct();
        global $config;
        $viewFQCN = $config->getViewFQCN();
        $view = new $viewFQCN();
        $result = [
            'replaceById' => [],
        ];
        foreach ($array as $id => $templateAndVars) {
            $result['replaceById'][$id] = $view->render($templateAndVars['template'], $templateAndVars['vars']);
        }
        echo json_encode($result);
    }
}
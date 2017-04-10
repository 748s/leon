<?php

namespace Leon\Response\JSON;

class JSONReplaceByIdResponse extends JSONResponse
{
    public function __construct(array $array)
    {
        parent::__construct();
        global $configuration;
        $class = $configuration->getView()->getClass();
        $view = new $class();
        $result = [
            'replaceById' => [],
        ];
        foreach ($array as $id => $templateAndVars) {
            $result['replaceById'][$id] = $view->render($templateAndVars['template'], $templateAndVars['vars']);
        }
        echo json_encode($result);
    }
}

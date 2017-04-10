<?php

namespace Leon\Response;

use Leon\Form\Form;
use Leon\Response\JSON\JSONReplaceByIdResponse;

class FormResponse extends Response
{
    public function __construct(string $template, array $vars = [])
    {
        if (
            isset($vars['form']) && 
            is_a($vars['form'], Form::class) &&
            $vars['form']->getIsAjax() &&
            'POST' === $_SERVER['REQUEST_METHOD']
        ) {
            $form = $vars['form'];
            return new JSONReplaceByIdResponse([
                $form->getId() => [
                    'template' => $form->getTemplate(),
                    'vars'     => $vars,
                ],
            ]);
        } else {
            return new HTMLResponse($template, $vars);
        }
    }
}

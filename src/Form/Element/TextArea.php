<?php

namespace Leon\Form\Element;

class TextArea extends StringElement
{
    protected $type = 'textArea';
    protected $template = '@Leon/form/element/textArea.html.twig';
    protected $maxLength = 65535;
    protected $numRows = 5;
    protected $resize = true;

    public function setNumRows(int $numRows)
    {
        $this->numRows = $numRows;

        return $this;
    }

    public function getNumRows()
    {
        return $this->numRows;
    }

    public function setResize(bool $resize)
    {
        $this->resize = $resize;

        return $this;
    }

    public function getResize()
    {
        return $this->resize;
    }
}

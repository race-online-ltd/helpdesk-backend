<?php

namespace App\Logic;

class EmailTemplateFormatting
{

    public $template;
    public $data;
    public function __construct($template, $data)
    {
        $this->template = $template;
        $this->data = $data;
    }

    public static function replacePlaceholders($template, $data)
    {
        foreach ($data as $placeholder => $value) {
            $template = str_replace("[$placeholder]", $value, $template);
        }
        return $template;
    }
}

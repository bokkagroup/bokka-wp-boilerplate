<?php

namespace BokkaWP\MVC;
Class View {
    public function render($template = false, $data = array())
    {
        global $Handlebars  ;
        if ($template !== false && $data !== false) {
            apply_filters( 'filter_before_render', $data);
            $template = $Handlebars->render($template, $data);
        }
       return $template;
    }

    public function display($data = array())
    {
        if (isset($this->template)) {
            echo $this->render($this->template, $data);
        }
    }

    public function __construct(){
        $this->initialize();
    }
}
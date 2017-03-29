<?php

namespace BokkaWP\BDX\admin;

class Field{

    private $name;
    public $value;
    public $html;

    public function __construct($options = array()){

        foreach ($options as $key => $value) {
            $this->$key = $value;
        }
    }
}
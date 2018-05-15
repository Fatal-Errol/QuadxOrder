<?php

namespace QuadxModule\Mvc;

class Controller {
    protected $module;
    
    /**
     * Controller construct
     * 
     * @param \QuadxModule\Mvc\Module $module
     */
    public function __construct($module)
    {
        $this->module = $module;
    }
}

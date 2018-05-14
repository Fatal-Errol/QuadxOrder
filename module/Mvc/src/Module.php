<?php

namespace QuadxModule\Mvc;

abstract class Module {
    protected $controller = 'index';
    protected $action = 'index';

    protected $params = [];

    public function __construct($config = [])
    {
        if (!empty($config['subroute'])) {
            $routes = explode('/', $config['subroute']);

            switch (count($routes)) {
                case 3:
                    $this->params = array_slice($routes, 2);
                    //no break
                case 2:
                    $this->action = $routes[1];
                    //no break
                case 1:
                    $this->controller = $routes[0];
            }
        }
    }

    public function run()
    {
        //extract the class then execute controller then action
    }
}

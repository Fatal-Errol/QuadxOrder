<?php

namespace QuadxModule\Mvc;

use QuadxModule\Mvc\Helper;

abstract class Module {
    protected $controller = 'index';
    protected $action = 'index';

    public $route_options = [];
    public $config = [];

    /**
     * Module constructor
     * 
     * @param type $params
     */
    public function __construct($params = [])
    {
        if (!empty($params['subroute'])) {
            $routes = explode('/', $params['subroute']);

            switch (count($routes)) {
                case 3:
                    $this->route_options = array_slice($routes, 2);
                    //no break
                case 2:
                    $this->action = $routes[1];
                    //no break
                case 1:
                    $this->controller = $routes[0];
            }
        }
        
        if (!empty($params['config'])) {
            $this->config = $params['config'];
        }
    }

    /**
     * Executes module specific operations
     * 
     * @throws \Exception
     */
    public function run()
    {
        $module = substr(get_called_class(), 0, strrpos(get_called_class(), '\\'));
        
        $controller_class = $module.'\\Controller\\'. Helper::convertToCamelCase($this->controller).'Controller';
        
        if (!class_exists($controller_class)) {
            throw new \Exception('Invalid controller "'.$controller_class.'" specified');
        }
        
        $controllerObject = new $controller_class($this);
        
        $action = 'action'.Helper::convertToCamelCase($this->action);
        
        if (!method_exists($controllerObject, $action)) {
            throw new \Exception('Invalid action "'.$controller_class.'::'.$action.'" specified');
        }
        
        try {
            $viewObject = $controllerObject->{$action}($this->route_options);
        } catch (Exception $ex) {
            throw new \Exception('An error occured: '.$ex->getMessage());
        }
                
        $viewObject->render();
    }
}

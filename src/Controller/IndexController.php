<?php

namespace QuadxOrder\Controller;

use QuadxModule\Mvc\Controller;
use QuadxModule\Mvc\View;
use QuadxOrder\Adapter\Order as OrderAdapter;

class IndexController extends Controller {
    /**
     * Default action. Displays the orders in the screen
     * 
     * @return View
     */
    public function actionIndex()
    {
        $api_config = $this->module->config['quadx'];
        
        $adapter = new OrderAdapter($api_config['api_base_url'], $api_config['timezone']);
        $orders = $adapter->getOrders($api_config['default_products']);
                
        return new View('index/index', [
            'orders' => $orders
        ]);
    }
}

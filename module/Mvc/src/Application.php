<?php

namespace QuadxModule\Mvc;

use QuadxModule\Mvc\Helper;

class Application {
    /**
     * This will initialize the proper module and controllers to load
     * 
     * @param array $config
     * @return \QuadxModule\Mvc\Module
     * @throws \InvalidArgumentException
     */
    public static function init($config)
    {
        $app_params = [
            'config' => $config
        ];
        
        if (empty($config['modules'])) {
            throw new \InvalidArgumentException('No modules to load');
        }

        if (empty($config['active_route'])) {
            $current_module_class = $config['modules'][0].'\\Module';
            return new $current_module_class($app_params);
        }

        foreach ($config['modules'] as $module) {
            list($active_module, $params) = explode('/', $config['active_route'], 2);
            $active_module = Helper::convertToCamelCase($active_module);

            if ($active_module == $module) {
                $app_params['subroute'] = $params;
                $current_module_class = $module.'\\Module';
                return new $current_module_class($app_params);
            }
        }

        $current_module_class = $config['modules'][0].'\\Module';
        return new $current_module_class($app_params);
    }
}

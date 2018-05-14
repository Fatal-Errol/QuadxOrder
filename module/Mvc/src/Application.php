<?php

namespace QuadxModule\Mvc;

class Application {
    public static function init($config)
    {
        if (empty($config['modules'])) {
            throw new \InvalidArgumentException('No modules to load');
        }

        if (empty($config['active_route'])) {
            $current_module_class = $config['modules'][0].'/Module';
            return new $current_module_class();
        }

        foreach ($config['modules'] as $module) {
            list($active_module, $params) = explode('/', $config['active_route'], 2);
            $active_module = str_replace('-', '', ucwords(strtolower($active_module)));

            if ($active_module == $module) {
                $current_module_class = $module.'/Module';
                return new $current_module_class([
                    'subroute' => $params
                ]);
            }
        }

        $current_module_class = $config['modules'][0].'/Module';
        return new $current_module_class();
    }
}

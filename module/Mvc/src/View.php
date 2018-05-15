<?php

namespace QuadxModule\Mvc;

class View {
    protected $template;
    protected $params;
    
    /**
     * View constructor
     * 
     * @param string $template The file path relative to the /view/application directory
     * @param array $params Variables that will be exposed to the template
     */
    public function __construct($template, $params = [])
    {
        $this->template = $template;
        $this->params = $params;
    }
    
    /**
     * Renders the template to be displayed in the console or the browser.
     * This is just a simple implementation.
     */
    public function render()
    {
        //just echo for now and format for console
        ob_start();
        extract($this->params, EXTR_SKIP);
        include('view/application/'.$this->template.'.php');
        $output = ob_get_contents();
        ob_end_clean();
        
        if (php_sapi_name() == 'cli') {
            echo trim($output).PHP_EOL;
        } else {
            //this will practically be a layout file. Just print quick html for now
            echo '<html><head><title>QuadxOrder Sample</title></head><body><pre>';
            echo trim($output);
            echo '</pre></body></html>';
        }
        
    }
}

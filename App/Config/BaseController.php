<?php
/**
* @author : Puji Ermanto <pujiermanto@gmail.com>
* @return construct
**/

namespace App\Config;

class BaseController {
    protected $view;

    public function __construct() {
        $this->view = new View();
    }

    // Method untuk memuat model
    protected function loadModel($modelName) {
        require_once 'models/' . $modelName . '.php';
        return new $modelName();
    }
}



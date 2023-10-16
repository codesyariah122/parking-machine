<?php
/**
* @author : Puji Ermanto <pujiermanto@gmail.com>
* @return render view
**/

namespace App\Config;

class View {
    public function render($viewName, $data = []) {
        extract($data);
        require_once 'views/' . $viewName . '.php';
    }
}

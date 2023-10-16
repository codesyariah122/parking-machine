<?php

namespace App\Controllers;

class NotFoundController {
	
    public function errors(){
        
        header("HTTP/1.0 404 Not Found");
        echo "Page Not Found";
    }
}

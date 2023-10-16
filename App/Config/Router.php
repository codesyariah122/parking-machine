<?php

/**
* @author : Puji Ermanto <pujiermanto@gmail.com>
* @return :void
* @desc : File ini difungsikan untuk menangani request url dari client dan setiap method menentukan rute mana yang akan di gunakan dari request url di sisi client
**/

namespace App\Config;

use App\Controllers\NotFoundController;

class Router {

    private $routes = [], $notfound, $groupPrefix='';

    public function __construct()
    {
        $this->notfound = new NotFoundController;
    }
   
    private function addRoute($method, $route, $handler): void {
        $paramPattern = ($method === 'GET') ? '{param}' : '{dataParam}';
        $route = str_replace($paramPattern, '([^/]+)', $route);
        $this->routes[$route] = $handler;
    }

    public function group($prefix, $callback): void {
        $this->groupPrefix = $prefix;
        $callback($this);
        $this->groupPrefix = ''; 
    }

   
    public function get($route, $handler): void {
       $this->addRoute('GET', $this->groupPrefix . $route, $handler);
    }

    /**
     * Menambahkan route POST
     * 
     * @param string $route Route yang akan ditambahkan
     * @param string $handler Handler untuk route tersebut
     * @return void
     */
    public function post($route, $handler): void {
        $this->addRoute('POST', $route, $handler);
    }

    public function put($route, $handler): void {
        $this->addRoute('PUT', $route, $handler);
    }

    public function delete($route, $handler): void {
        $this->addRoute('DELETE', $route, $handler);
    }

    
    public function run(): void {
        $uri = $_SERVER['REQUEST_URI'];
        $uri = strtok($uri, '?');

        foreach ($this->routes as $route => $handler) {
            $pattern = '#^' . $route . '$#';
            if (preg_match($pattern, $uri, $matches)) {
                $handlerParts = explode('@', $handler);
                $controllerName = $handlerParts[0];
                $methodName = $handlerParts[1];

                $controllerNamespace = 'App\Controllers\\' . $controllerName;
                $controller = new $controllerNamespace();

                array_shift($matches);
                $dataParam = end($matches);

                call_user_func([$controller, $methodName], $dataParam);

                return;
            }
        }

        http_response_code(404);
        header("HTTP/1.0 404 Not Found");

        $this->notfound->errors();
    }
}

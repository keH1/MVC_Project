<?php

namespace Application\Core;


class Router
{

    protected $routes = [];
    protected $params = [];

    public function __construct()
    {
        $arr = require SITE_PATH . 'application/config/routes.php';

        foreach ($arr as $key => $val) {
            $this->add($key, $val);
        }
    }

    /**
     * Add route into Routes
     *
     * @param $route
     * @param $params
     */
    public function add($route, $params)
    {
        $route = '#^' . $route . '$#';
        $this->routes[$route] = $params;
    }

    /**
     * Matching current URL and app routes
     *
     * @return bool
     */
    public function match()
    {
        $url = $_SERVER['REQUEST_URI'];

        //Cut off get parameters
        if (strpos($_SERVER['REQUEST_URI'], '?') !== false)
            $url = strstr($url,'?', true);

        $url = trim(substr($url,strlen(SITE_DIR)), '/');

        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                $this->params = $params;
                return true;
            }
        }
        return false;
    }

    /**
     * Start Router
     */
    public function run()
    {
        if ($this->match()) {
            $path = 'application\controllers\\' . ucfirst($this->params['controller']) . 'Controller';

            if (class_exists($path)) {
                $action = $this->params['action'] . 'Action';
                if (method_exists($path, $action)) {
                    $controller = new $path($this->params);
                    $controller->$action();
                } else {
                    View::errorCode(404);
                }
            } else {
                View::errorCode(404);
            }
        } else {
            View::errorCode(404);
        }
    }

}
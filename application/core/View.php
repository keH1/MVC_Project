<?php


namespace Application\Core;


class View
{
    public $path;
    public $route;
    public $layout = 'default';

    public function __construct($route)
    {
        $this->route = $route;
        $this->path = $route['controller'] . '/' . $route['action'];
    }

    /**
     * Rendering View template
     *
     * @param $title
     * @param array $vars
     */
    public function render($title, $vars = [])
    {
        extract($vars);
        $path = 'application/views/' . $this->path . '.php';

        if (file_exists($path)) {
            ob_start();
            require $path;
            $content = ob_get_clean();
            require 'application/views/templates/' . $this->layout . '.php';
        }
    }

    /**
     * Redirecting method
     *
     * @param $url
     */
    public function redirect($url)
    {
        header('location: ' . $url);
        exit;
    }

    /**
     * Include errors pages and set response code
     *
     * @param $code
     */
    public static function errorCode($code)
    {
        http_response_code($code);
        $path = 'application/views/errors/' . $code . '.php';
        if (file_exists($path)) {
            require $path;
        }
        exit;
    }

    public function message($status, $message)
    {
        exit(json_encode(['status' => $status, 'message' => $message]));
    }

    public function location($url)
    {
        header('Location: ' . $url);
    }
}
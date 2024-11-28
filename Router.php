<?php

namespace MVC;

class Router
{
    public array $getRoutes = [];
    public array $postRoutes = [];

    public function get($url, $fn)
    {
        $this->getRoutes[$url] = $fn;
    }

    public function post($url, $fn)
    {
        $this->postRoutes[$url] = $fn;
    }

    public function comprobarRutas()
    {

        $url_actual = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            $fn = isset($this->getRoutes[$url_actual]) ? $this->getRoutes[$url_actual] : null;
        } else {
            $fn = isset($this->postRoutes[$url_actual]) ? $this->postRoutes[$url_actual] : null;
        }

        if ( $fn ) {
            call_user_func($fn, $this);
        } else {
            header('Location: /404');
        }
    }

    public function render($view, $datos = [])
    {
        foreach ($datos as $key => $value) {
            $$key = $value;
        }

        ob_start();

        include_once __DIR__ . "/views/$view.php";

        $contenido = ob_get_clean(); // Limpia el Buffer

        include_once __DIR__ . '/views/layout.php';

    }
}
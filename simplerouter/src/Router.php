<?php

namespace IvanHunko\SimpleRouter;

use IvanHunko\SimpleRouter\IndexController;

/**
 * Class Router
 * @package IvanHunko\SimpleRouter
 */
class Router
{
    /**
     *
     */
    const ROUTES = [
        '*' => [
            'controller' => IndexController::class,
            'action' => 'index',
        ]
    ];

    /**
     * @var Request
     */
    private $request;

    /**
     *
     */
    public function init()
    {
        $this->request = new Request();
        $this->request->collectInputs();
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        $path = isset($_SERVER["REQUEST_URI"]) ? parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) : '';
        if (!isset(self::ROUTES[$path])) {
            $path = '*';
        }
        $route = self::ROUTES[$path];
        $classInstance = new $route['controller']();
        $action = $route['action'];

        return $classInstance->$action($this->request);
    }

    /**
     * @param $data
     * @return false|string
     */
    public function sendResponse($data)
    {
        header("Content-type: application/json; charset=utf-8");

        echo json_encode($data, JSON_PRETTY_PRINT);
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: Walderlan Sena <senawalderlan@gmail.com>
 * Date: 16/06/18
 * Time: 18:53
 */

namespace MVS\Router;

use WS\DI\Resolver;

abstract class Bootstrap
{
    private $routes = [];
    private $params = [];
    private $controller;
    private $action;
    private $routeFound;

    public function __construct()
    {
        $this->initRoutes();
        $this->run($this->getUrl());
    }

    /**
     * Método abstrato para especificar as rotas do sistema
     * @return mixed
     */
    abstract protected function initRoutes();

    /**
     * @param $url
     * @throws \ReflectionException
     */
    private function run($url)
    {
        $request           = explode("/", $url);
        $this->params      = [];
        $countRouteRequest = count($request);

        foreach ($this->routes as $route) {

            $requestMethod      = $_SERVER['REQUEST_METHOD'];
            $requestMethodRoute = $route['method'];
            $routeBase          = explode('/', $route['route']);
            $countRouteBase     = count($routeBase);

            for ($i = 0; $i < $countRouteBase; $i++) {
                if ((strpos($routeBase[$i], '{') !== false) && ($countRouteBase == $countRouteRequest) &&
                    ($requestMethod == $requestMethodRoute)) {
                    $invalidCaracteres = ['{','}'];
                    $this->params[str_replace($invalidCaracteres, '',$routeBase[$i])] = $request[$i];
                    $routeBase[$i]  = $request[$i];
                }
                $route['route'] = implode($routeBase, '/');
            }

            $this->routeFound = false;

            if ($url === $route['route']) {
                $this->routeFound = true;
                $this->controller = $route['controller'];
                $this->action     = $route['action'];
                break;
            }
        }

        if ($this->routeFound) {
           $this->callMvc($this->controller, $this->action);
        }

    }

    public function callMvc(string $controller, string $action)
    {
        $resolver = new Resolver();

        try {
            $controllerResolve = $resolver->resolve($controller);
        } catch (\ReflectionException $reflectionException) {
            die($reflectionException->getMessage());
        }

        if (method_exists($controllerResolve, $action)) {
            return $controllerResolve->$action(HttpGetRequests::getRequests($this->params));
        }

        return false;
    }

    /**
     * Capturando rotas registradas na aplicação
     * @param array $routes
     */
    protected function setRoutes(array $routes)
    {
        $this->routes = $routes;
    }

    /**
     * @return mixed
     */
    protected function getUrl()
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }
}
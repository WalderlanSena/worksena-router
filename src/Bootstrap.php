<?php
/**
 * --- WorkSena - Micro Framework ---
 * Sistema de router HTTP WorkSena MicroFramework
 * @license https://github.com/WalderlanSena/worksena/blob/master/LICENSE (MIT License)
 *
 * @copyright © 2013-2018 - @author Walderlan Sena <walderlan@worksena.xyz>
 *
 */

namespace MVS\Router;

use MVS\Router\Service\Http\RequestService;
use WS\DI\Resolver;

abstract class Bootstrap
{
    private $routes = [];
    private $params = [];
    private $controller;
    private $action;
    private $routeFound;
    private $requestMethod;

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
     * @return bool|mixed
     */
    private function run($url)
    {
        $request           = explode("/", $url);
        $this->params      = [];
        $countRouteRequest = count($request);

        foreach ($this->routes as $route) {

            $this->requestMethod      = $_SERVER['REQUEST_METHOD'];
            $requestMethodRoute = $route['method'];
            $routeBase          = explode('/', $route['route']);
            $countRouteBase     = count($routeBase);

            for ($i = 0; $i < $countRouteBase; $i++) {
                if ((strpos($routeBase[$i], '{') !== false) && ($countRouteBase == $countRouteRequest) &&
                    ($this->requestMethod == $requestMethodRoute)) {
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
           return $this->callMvc($this->controller, $this->action);
        }

        return $this->pageNotFound();
    }

    public function callMvc(string $controller, string $action)
    {
        $resolver = new Resolver();

        $dirPathRouter = __DIR__ . '/../../../../config/services/';

        $iterator = new \FilesystemIterator($dirPathRouter);

        $services = [];

        foreach ($iterator as $fileinfo) {
            if ($iterator->getType() == 'file' && $iterator->getExtension() == 'php') {
                $services = include $dirPathRouter.$iterator->getFilename();
            }
        }

        try {
            $controllerResolve = $resolver->resolve($controller, $services);
        } catch (\ReflectionException $reflectionException) {
            die($reflectionException->getMessage());
        }

        if (method_exists($controllerResolve, $action)) {
            $serverRequest = new RequestService();
            $serverRequest->setParams($this->params);
            return $controllerResolve->$action($serverRequest);
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
    
    protected function pageNotFound()
    {
        return include_once __DIR__ . '/../../../../templates/errors/404.phtml';
    }
}
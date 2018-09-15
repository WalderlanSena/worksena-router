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

class Router extends Bootstrap
{
    /**
     * @return mixed|void
     */
    protected function initRoutes()
    {
        $router = [];

        $dirPathRouter = __DIR__ . '/../../../../config/routes/';

        $iterator = new \FilesystemIterator($dirPathRouter);

        foreach ($iterator as $fileinfo) {
            if ($iterator->getType() == 'file' && $iterator->getExtension() == 'php') {
                $routes = include $dirPathRouter.$iterator->getFilename();
                array_push($router, $routes);
            }
        }

        $router_final['routes'] = [];

        foreach ($router as $value) {
            foreach ($value as $oneRoute) {
                $router_final['routes'][] = $oneRoute;
            }
        }

        $this->setRoutes($router_final['routes']);
    }
}
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
        $route = include __DIR__ . '/../router.php';

        $this->setRoutes(array_filter($route['routes']));
    }
}
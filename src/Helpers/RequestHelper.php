<?php
/**
 * --- WorkSena - Micro Framework ---
 * Sistema de router HTTP WorkSena MicroFramework
 * @license https://github.com/WalderlanSena/worksena/blob/master/LICENSE (MIT License)
 *
 * @copyright © 2013-2018 - @author Walderlan Sena <walderlan@worksena.xyz>
 *
 */

namespace MVS\Router\Helpers;

class RequestHelper
{
    public static function getUrlPath()
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    public static function getTypeRequest()
    {
        return parse_url($_SERVER['REQUEST_METHOD'], PHP_URL_PATH);
    }

    public static function getContentType()
    {
        return $_SERVER['CONTENT_TYPE'];
    }
}
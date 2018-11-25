<?php
/**
 * --- WorkSena - Micro Framework ---
 * Sistema de router HTTP WorkSena MicroFramework
 * @license https://github.com/WalderlanSena/worksena/blob/master/LICENSE (MIT License)
 *
 * @copyright © 2013-2018 - @author Walderlan Sena <walderlan@worksena.xyz>
 *
 */

namespace MVS\Router\Service\Http;

use MVS\Router\Helpers\RequestHelper;
use MVS\Router\Helpers\RouteHelper;

class RequestService
{
    private $params;

    public function getDataRequest()
    {
        if ($this->contentTypeIsJson()) {
            return $this->getBodyJson();
        }

        if ($this->contentTypeIsFormData()) {
            return $this->getBodyFormData();
        }

        if ($this->contentTypeIsFormUrlencoded()) {
            return $this->getBodyFormUrlEncoded();
        }
    }

    public function getBodyPostRequest()
    {
        return $this->contentTypeIsJson() ? $this->getBodyJson() : $_POST;
    }

    private function getValuePhpInput()
    {
        return file_get_contents("php://input");
    }

    public function contentTypeIsJson()
    {
        return strpos(RequestHelper::getContentType(), 'json') !== false ? true : false;
    }

    public function contentTypeIsFormData()
    {
        return strpos(RequestHelper::getContentType(), 'form-data') !== false ? true : false;
    }

    public function contentTypeIsFormUrlencoded()
    {
        return strpos(RequestHelper::getContentType(), 'x-www-form-urlencoded') !== false ? true : false;
    }

    public function setParams(array $params)
    {
        $this->params = $params;
    }

    public function getParams(string $id)
    {
        return $this->params[$id];
    }
    
    public function get(string $id)
    {
        return $_GET[$id];
    }

    public function getBodyJson()
    {
        return json_decode($this->getValuePhpInput(), true);
    }

    public function getBodyFormData()
    {
        $array = [];

        preg_match_all('/"(.+)"+\s+(.*)/', $this->getValuePhpInput(), $matches);
        
        foreach ($matches[1] as $key => $match) {
            $matchKey   = RouteHelper::removeCaractersOfString($match, ['\'', "\""]);
            $matchValue = RouteHelper::removeCaractersOfString($matches[2][$key], ['\'', "\""]);
            $array[$matchKey] = $matchValue;
        }

        return $array;
    }

    public function getBodyFormUrlEncoded()
    {
        $content = $this->getValuePhpInput();

        if (!strpos($content, '&')) {
            $array = explode('=', $content);
            return [$array[0] => $array[1]];
        }

        $array = explode('&', $content);

        foreach ($array as $value) {
            $aux = explode('=', $value);
            $arrayFormated[$aux[0]] = $aux[1];
        }
        return $arrayFormated;
    }
}
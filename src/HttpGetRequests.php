<?php
/**
 * Created by PhpStorm.
 * User: Walderlan Sena <senawalderlan@gmail.com>
 * Date: 16/06/18
 * Time: 22:47
 */

namespace MVS\Router;

abstract class HttpGetRequests
{
    /**
     * @param null $get
     * @return bool|\stdClass
     */
    public static function getRequests($get = null)
    {
        /**
         *  @return Object
         *  Retorna um objeto com o índice GET, onde as requisições post são capturadas
         */
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && !is_null($get)) {
            $request    = [];
            $objRequest = new \stdClass();
            foreach ($get as $key => $value) {
                $request[$key] = $value;
            }
            $objRequest->get = $request;
            return $objRequest;
        }

        /**
         *  @return Object
         *  Retorna um objeto com o indice POST, onde as requisições post são capturadas
         */
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $request    = [];
            $objRequest = new \stdClass();
            foreach ($_POST as $key => $value) {
                $request[$key] = $value;
            }
            $objRequest->post = $request;
            return $objRequest;
        }

        return false;
    }//end newGetRequest
}
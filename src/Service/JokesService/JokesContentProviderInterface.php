<?php
/**
 * Created by PhpStorm.
 * User: asknn
 * Date: 06.06.2018
 * Time: 0:58
 */

namespace App\Service\JokesService;

interface JokesContentProviderInterface
{
    /**
     * Получить контент.
     * @param string $http_method
     * @param string $uri
     * @param array $options
     * @return array
     */
    public function getContent(string $http_method, string $uri, array $options = []) : array;
}
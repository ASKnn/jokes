<?php
/**
 * Created by PhpStorm.
 * User: asknn
 * Date: 06.06.2018
 * Time: 0:59
 */

namespace App\Service\JokesService;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Метод получения данных, реализация с помощью Guzzle.
 * Class JokesContentProviderGuzzle
 * @package App\Service\JokesService
 */
final class JokesContentProviderGuzzle implements JokesContentProviderInterface
{
    private $client;
    private $http_method;
    private $url;
    private $options;

    public function __construct()
    {
        /**
         * Не внедряем зависимость, а указываем реализацию конкретно.
         */
        $this->client = new Client();
    }

    public function getContent(string $http_method, string $url, array $options = []) : array
    {
        $this->http_method = $http_method;
        $this->url = $url;
        $this->options = $options;
        $contentsJson = $this->request();

        return $this->getPreparedData($contentsJson);
    }

    /**
     * Подготовить данные из JSON.
     * @param string $contentsJson
     * @return array
     */
    private function getPreparedData(string $contentsJson) : array
    {
        /**
         * Проверка на json.
         * @param $string
         * @return bool
         */
        $isJson = static function ($string) {
            json_decode($string);
            return (json_last_error() == JSON_ERROR_NONE);
        };

        if ($isJson($contentsJson)) {
            $contentArray = json_decode($contentsJson, 1);
            return $contentArray;
        }

        throw new Exception("Returned data isn't JSON");
    }

    /**
     * Вызов клиента с запросом.
     * @return string JSON-строка.
     */
    private function request()
    {
        try {
            $requestOptions = sizeof($this->options) ? ['query' => $this->options] : [];
            $request = $this->client->request($this->http_method, $this->url, $requestOptions);
            return $request->getBody()->getContents();

        } catch (GuzzleException $e) {
            throw new Exception($e->getMessage());
        }
    }
}
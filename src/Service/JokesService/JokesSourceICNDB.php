<?php

namespace App\Service\JokesService;

use Symfony\Component\Config\Definition\Exception\Exception;

final class JokesSourceICNDB implements JokesSourceInterface
{
    /**
     * @var JokesContentProviderInterface
     */
    private $contentProvider;

    /**
     * Источник данных
     * @var string
     */
    const SOURCE_URL = "https://api.icndb.com";

    /**
     * URI рандомных материалов
     * @var string
     */
    const RANDOM_URI = "/jokes/random";

    /**
     * URI категорий материалов
     * @var string
     */
    const ALL_CATEGORIES_URI = "/categories";

    /**
     * URI фильтрации по категорям материалов
     * @var string
     */
    const LIMIT_CATEGORIES_URI = "limitTo";

    /**
     * Опции и требования к контенту.
     * @var array
     */
    private $dataOptions = [];

    /**
     * JokesSourceICNDB constructor.
     * @param JokesContentProviderInterface $jokesContentProvider
     */
    public function __construct(JokesContentProviderInterface $jokesContentProvider)
    {
        $this->contentProvider = $jokesContentProvider;
    }

    public function getSpecificItem(int $id) : string
    {
        return "";
    }

    public function getRandomItemFromCategories(array $categories) : string
    {
        $this->setCategoriesFilter($categories);
        $fullUrl = $this->getUrlForRandomItem();
        return $this->getJokeString($fullUrl);
    }

    public function getAllCategories() : array
    {
        return $this->getListCategoriesArray();
    }

    /**
     * Получить список всех категорий материалов, преобразованный в массив.
     * @return array
     */
    private function getListCategoriesArray() : array
    {
        $url = $this->getUrlForAllCategories();
        $content = $this->contentProvider->getContent("get", $url);

        if (isset($content) && isset($content['value'])) {
            return $content['value'];
        }
        else {
            throw new Exception("Error.");
        }
    }

    /**
     * Получить строку с шуткой.
     * @param $url
     * @return string
     */
    private function getJokeString($url) : string
    {
        $content = $this->contentProvider->getContent("get", $url, $this->dataOptions);

        if (isset($content) && isset($content['value']) && isset($content['value']['joke'])) {
            return $content['value']['joke'];
        }
        else {
            throw new Exception("Error.");
        }
    }


    /**
     * URL для получения рандомного айтема.
     * @return string
     */
    private function getUrlForRandomItem()
    {
        return (string) self::SOURCE_URL . (string) self::RANDOM_URI;
    }

    /**
     * Полный УРЛ для получения списка категорий
     * @return string
     */
    private function getUrlForAllCategories()
    {
        return (string) self::SOURCE_URL . (string) self::ALL_CATEGORIES_URI;
    }

    /**
     * Определить запрос для фильтрации по конкретным категориям.
     * @param array $categories
     * @return void
     */
    private function setCategoriesFilter(array $categories)
    {
        $fromCategories = [];
        foreach ($categories as $category) {
            $fromCategories[] = $this->getEscapedCategory($category);
        }

        if (sizeof($fromCategories)) {
            $limitUrl = (string) self::LIMIT_CATEGORIES_URI;
            $this->createOrUpdateDataOption($limitUrl, implode(",", $categories));
        }
    }

    /**
     * Добавить опции и требования к контенту.
     * @param $key
     * @param $value
     */
    private function createOrUpdateDataOption(string $key, $value)
    {
        $this->dataOptions[$key] = $value;
    }

    /**
     * Экскейпит строку с категорией.
     * @param string $category
     * @return null|string|string[]
     */
    private function getEscapedCategory(string $category)
    {
        return preg_replace("/[^a-z]/i", "", $category);
    }
}
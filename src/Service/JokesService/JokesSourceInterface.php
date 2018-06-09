<?php
/**
 * Created by PhpStorm.
 * User: asknn
 * Date: 05.06.2018
 * Time: 23:37
 */

namespace App\Service\JokesService;


interface JokesSourceInterface
{
    /**
     * Получить конкретный материал.
     * @param int $id
     * @return string
     */
    public function getSpecificItem(int $id) : string;

    /**
     * Получить рандомный материал из указанных категорй.
     * @param array $categories
     * @return string
     */
    public function getRandomItemFromCategories(array $categories) : string;

    /**
     * Получить список всех категорий материалов.
     * @return array
     */
    public function getAllCategories() : array;
}
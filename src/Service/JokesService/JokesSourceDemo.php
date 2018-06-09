<?php

namespace App\Service\JokesService;

/**
 * Пример реализации из другого источника.
 * Class JokesSourceDemo
 * @package App\Service\JokesService
 */
class JokesSourceDemo implements JokesSourceInterface
{
    public function getSpecificItem(int $id) : string
    {
        // TODO: Implement getSpecificItem() method.
    }

    public function getRandomItemFromCategories(array $categories) : string
    {
        // TODO: Implement getRandomItem() method.
    }

    public function getAllCategories(): array
    {
        // TODO: Implement getListCategories() method.
    }
}
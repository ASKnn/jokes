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
        return "Joke.";
    }

    public function getRandomItemFromCategories(array $categories) : string
    {
        return "Some joke.";
    }

    public function getAllCategories(): array
    {
        return [
            0 => 'first category',
            1 => 'second category'
        ];
    }
}
<?php

namespace App\Tests;

use App\Service\JokesService\JokesContentProviderGuzzle;
use App\Service\JokesService\JokesSourceDemo;
use App\Service\JokesService\JokesSourceICNDB;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class JokeSourceServiceTest extends WebTestCase
{
    public function testGetAllCategoriesICNDB()
    {
        $contentProvider = new JokesContentProviderGuzzle();
        $sourceService = new JokesSourceICNDB($contentProvider);

        $allCategories = $sourceService->getAllCategories();

        $this->assertTrue(is_array($allCategories), 'Test categories is not array.');
        $this->assertTrue(count($allCategories) > 0, 'Test categories are empty.');
    }

    public function testGetAllCategoriesDemo()
    {
        $sourceService = new JokesSourceDemo();
        $allCategories = $sourceService->getAllCategories();
        $this->assertTrue(is_array($allCategories), 'Test categories of demo is not array.');
        $this->assertTrue(count($allCategories) > 0, 'Test categories of demo are empty.');
    }

    public function testGetRandomJokeICNDB()
    {
        $contentProvider = new JokesContentProviderGuzzle();
        $sourceService = new JokesSourceICNDB($contentProvider);

        $joke = $sourceService->getRandomItemFromCategories(['nerdy', 'explicit']);
        $this->assertTrue(is_string($joke), 'Test random joke is not a string.');
    }
}

<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class JokeControllerTest extends WebTestCase
{
    public function testIndexPage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', "/");

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testFormTemplate()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertTrue($crawler->filter("select")->count() > 0);
        $this->assertTrue($crawler->filter("[name=email]")->count() > 0);
    }
}

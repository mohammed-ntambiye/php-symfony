<?php

namespace Reviewer\ReviewBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertContains('Book Genres', $client->getResponse()->getContent());
    }
}

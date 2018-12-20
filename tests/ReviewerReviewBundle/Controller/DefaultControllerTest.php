<?php

namespace Reviewer\ReviewBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertContains('Book Genres', $client->getResponse()->getContent());


        $crawler = $client->request('GET', '/login');

        $this->assertContains('Login', $client->getResponse()->getContent());


        $crawler = $client->request('GET', '/Register');

        $this->assertContains('Register', $client->getResponse()->getContent());
    }

}

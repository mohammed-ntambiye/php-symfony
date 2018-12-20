<?php

namespace Reviewer\ReviewBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookReviewControllerTests extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertContains('Book Genres', $client->getResponse()->getContent());

    }

}

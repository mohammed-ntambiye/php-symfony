<?php

namespace Reviewer\ReviewBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReviewControllerTest extends WebTestCase
{

    public function testReviewRoute()
    {
        $client = static::createClient();

        $client->request('GET', '/view/review/3');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testReportReview(){

        //success test
        $client = static::createClient();
        $client->request('GET', '/report/67');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

}

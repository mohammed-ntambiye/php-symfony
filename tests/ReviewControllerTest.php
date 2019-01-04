<?php

namespace Reviewer\ReviewBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
         $client->request('GET', '/');
        $this->assertContains('Book Genres', $client->getResponse()->getContent());

    }

    public function testViewBookActionRoute()
    {
        $client = static::createClient();

        $client->request('GET', '/view/book/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testRegisterRoute()
    {
        $client = static::createClient();

        $client->request('GET', '/register/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }


    public function testLoginRoute()
    {
        $client = static::createClient();

        $client->request('GET', '/login');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testReviewRoute()
    {
        $client = static::createClient();

        $client->request('GET', '/view/review/3');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGenreRoute()
    {
        $client = static::createClient();

        $client->request('GET', 'book/genre/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testCreateBookWithoutLogin()
    {
        $client = static::createClient();
        $client->request('GET', '/create/book');
        $this->assertTrue($client->getResponse()->isRedirect());
    }

    public function testAdminWithoutLogin()
    {
        $client = static::createClient();
        $client->request('GET', '/admin');
        $this->assertTrue($client->getResponse()->isRedirect());
    }

    public function testLoginFunctionality()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Login')->form();
        $form->setValues(['_username' => 'test', '_password' => 'test']);
        $client->submit($form);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());


        //checking login user can create a book
        $client->request('GET', '/create/book');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testReportReview(){

        //success test
        $client = static::createClient();
        $client->request('GET', '/report/67');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function urlProvider()
    {
        yield ['/'];
        yield ['/create/book'];
        yield ['/login'];
        yield ['/register'];
    }
}

<?php

namespace Reviewer\ReviewBundle\Tests;

use Reviewer\ReviewBundle\Service\BookService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FilteringServiceTest extends WebTestCase
{

    private $filteringService;

    protected function setUp()
    {
        self::bootKernel();
        $this->filteringService = static::$kernel
            ->getContainer()
            ->get('filtering_service');
    }

     public function testSearchRoute(){

         $client = static::createClient();

         $client->request('GET', '/search/game');

         $this->assertEquals(200, $client->getResponse()->getStatusCode());
     }

    public function testSearchByAutor()
    {
        $result = $this->filteringService->searchBooks("Bill Gates");
        $this->assertNotNull($result);
    }


    public function testSearchByTitle()
    {
        $result = $this->filteringService->searchBooks("Game of thrones");
        $this->assertNotNull($result);
    }

    public function testSearchByIsbn()
    {
        $result = $this->filteringService->searchBooks("07");
        $this->assertNotNull($result);
    }



}
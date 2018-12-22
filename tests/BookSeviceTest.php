<?php

namespace Reviewer\ReviewBundle\Tests;

use Reviewer\ReviewBundle\Service\BookService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BookServiceTest extends KernelTestCase
{

    private $bookService;

    protected function setUp()
    {
        self::bootKernel();

        $this->bookService = static::$kernel
            ->getContainer()
            ->get('book_service');
    }


    public function testGenreGetNotNull()
    {
        $result = $this->bookService->getGenres();
        $this->assertNotNull($result);
    }

    public function testGetBookByIdNotNull()
    {
        $result = $this->bookService->getBookById(1);
        $this->assertNotNull($result);
    }

    public function testGetGenreByIdNotNull()
    {
        $result = $this->bookService->getGenreById(1);
        $this->assertNotNull($result);
    }

    public function testGetAllGenresNotNull()
    {
        $result = $this->bookService->getAllGenres();
        $this->assertNotNull($result);
    }

    public function testGetLatestReviewsNotNull()
    {
        $result = $this->bookService->getLatestReviews();
        $this->assertNotNull($result);
    }

    public function testGetBookIdByIsbnNotNull()
    {
        $result = $this->bookService->getBookIdByIsbn("0710273");
        $this->assertNotNull($result);
    }


    public function testGetBookByGenreNotNull()
    {
        $result = $this->bookService->getBookByGenre(1);
        $this->assertNotNull($result);
    }

    public function testGetReviewsByBookIdNotNull()
    {
        $result = $this->bookService->getReviewsByBookId(60);
        $this->assertNotNull($result);
    }

    public function testTextAnalyzerNotNull()
    {
        $result = $this->bookService->textAnalyzer("i love symfony");
        $this->assertEquals("pos",$result[0]);
    }
}
<?php

namespace Reviewer\ReviewBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
use Reviewer\ReviewBundle\Entity\Book;

class FilteringService
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * BookService constructor.
     *
     * @param $entityManager EntityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;

    }


    public function searchBooks($searchQuery)
    {
        $sqlQuery =
            'SELECT * from book where author LIKE ?' .
            ' UNION ' .
            'SELECT * from book where title LIKE ?' .
            ' UNION ' .
            'SELECT * from book where isbn LIKE ?' .
            ' UNION ' .
            'SELECT b.* from book as b INNER JOIN genre as g on b.genre_id = g.id AND b.isbn LIKE ? AND  g.genreName LIKE ? ';

        $statement = $this->getEntityManager()->getConnection()->prepare($sqlQuery);
        $statement->bindValue(1, '%' . $searchQuery . '%');
        $statement->bindValue(2, '%' . $searchQuery . '%');
        $statement->bindValue(3, '%' . $searchQuery . '%');
        $statement->bindValue(4, '%' . $searchQuery . '%');
        $statement->bindValue(5, '%' . $searchQuery . '%');

        $statement->execute();
        return $statement->fetchAll();
    }

    /**
     * @return EntityManager
     */
    private function getEntityManager()
    {
        return $this->entityManager;
    }
}
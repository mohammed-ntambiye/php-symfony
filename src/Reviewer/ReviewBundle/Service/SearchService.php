<?php

namespace AppBundle\Service;

use AppBundle\Entity\Book;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;

class SearchService
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * SearchService constructor.
     *
     * @param $entityManager EntityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function searchBooks($searchResult)
    {
        $sql =
            'SELECT * from book where title LIKE ?'.
                ' UNION '.
                'SELECT * from book where author LIKE ?'.
                ' UNION '.
                'SELECT b.* from book as b INNER JOIN genre as g on b.genre_id = g.id AND g.genre LIKE ?';

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->bindValue(1, '%'.$searchResult.'%');
        $stmt->bindValue(2, '%'.$searchResult.'%');
        $stmt->bindValue(3, '%'.$searchResult.'%');
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * @return EntityManager
     */
    private function getEntityManager()
    {
        return $this->entityManager;
    }
}
<?php

namespace Reviewer\ReviewBundle\Repository;

/**
 * BookReviewRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BookReviewRepository extends \Doctrine\ORM\EntityRepository
{

    public function getLatest($limit, $offset)
    {
        $queryBuilder = $this->createQueryBuilder('bookReview');
        $queryBuilder->orderBy('bookReview.timestamp', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit);
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

    public function checkForExistingReview($bookTitle, $bookAuthor)
    {
        var_dump($bookTitle);
        var_dump($bookAuthor);
//        $queryBuilder = $this->createQueryBuilder('entry');
//        $queryBuilder->select('entry.bookAuthor')->where(' entry.tile' == '$tile');
//        $query = $queryBuilder->getQuery();
//        return $query->getResult();
    }
}

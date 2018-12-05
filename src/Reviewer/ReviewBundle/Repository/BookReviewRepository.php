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
}
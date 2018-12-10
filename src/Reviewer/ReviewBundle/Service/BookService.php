<?php

namespace Reviewer\ReviewBundle\Service;

use \Reviewer\ReviewBundle\Entity\Genre;
use Doctrine\ORM\EntityManager;

class BookService
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

    /**
     * Returns all Books genres
     *
     * @return array
     */
    public function getGenres()
    {
        var_dump("here");
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            'SELECT g.id, g.genre FROM genre:Genre g');

        return $query->getResult();
    }

    /**
     * @param $id
     *
     * @return null|object
     */
    public function getGenreById($id)
    {
        $em = $this->getEntityManager();

        $result = $em->getRepository(Genre::class)->find($id);

        return $result;
    }

}
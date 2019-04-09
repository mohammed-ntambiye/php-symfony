<?php

namespace Reviewer\ReviewBundle\Controller;

use ApiBundle\ApiBundle;
use Reviewer\ReviewBundle\Entity\Review;
use Reviewer\ReviewBundle\Entity\User;
use Reviewer\ReviewBundle\Form\BookType;
use Reviewer\ReviewBundle\Form\ReviewType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Reviewer\ReviewBundle\Entity\Book;
use Reviewer\ReviewBundle\Service\BookService;

class DefaultController extends Controller
{

    public function indexAction()
    {
        $bookService = $this->container->get('book_service');
        $allGenres = $bookService->getAllGenres();
        $latestReviews = $bookService->getLatestReviews();
        return $this->render('ReviewerReviewBundle:Default:index.html.twig',
            ['genres' => $allGenres,
                'bookReviews' => $latestReviews
            ]
        );
    }


    public function apiClientAction()
    {
        $em = $this->getDoctrine()->getManager();

        $existingClient = $em->getRepository("ApiBundle:Client")->findOneBy(
            [ "user" => $this->getUser() ]
        );


        $clientId = null;
        $clientSecret = null;

        if (!$existingClient) {
            $clientManager = $this->container->get('fos_oauth_server.client_manager.default');
            $client = $clientManager->createClient();
            $client->setRedirectUris(array('http://localhost'));
            $client->setAllowedGrantTypes(array('password', 'refresh_token', 'token', 'authorization_code'));
            $client->setUser($this->getUser());
            $clientManager->updateClient($client);
            $clientId = $client->getPublicId();
            $clientSecret = $client->getSecret();
        } else {
            $clientId = $existingClient->getPublicId();
            $clientSecret = $existingClient->getSecret();
        }

        return $this->render('ReviewerReviewBundle:Default:apiclient.html.twig',
            [
                "client_id" => $clientId,
                "client_secret" => $clientSecret,
                "username" => $this->getUser()->getUsername()
            ]
        );
    }

    /**
     * @return EntityManager
     */
    private function getEntityManager()
    {
        return $this->container->get('doctrine.orm.default_entity_manager');
    }
}


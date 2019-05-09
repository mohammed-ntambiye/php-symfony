<?php

namespace Reviewer\ReviewBundle\Controller;

use ApiBundle\ApiBundle;
use FOS\RestBundle\Controller\Annotations\Post;
use Reviewer\ReviewBundle\Entity\Review;
use Reviewer\ReviewBundle\Entity\User;
use Reviewer\ReviewBundle\Form\BookType;
use Reviewer\ReviewBundle\Form\ReviewType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Reviewer\ReviewBundle\Entity\Book;
use Reviewer\ReviewBundle\Service\BookService;
use GuzzleHttp;
use Symfony\Component\HttpFoundation\Request;
use GuzzleHttp\Client;


class DefaultController extends Controller
{
    public function indexAction()
    {
        $bookService = $this->container->get('book_service');
        $allGenres = $bookService->getAllGenres();
        $latestReviews = $bookService->getLatestReviews();
        $bestSellers = $this->getNewYorkBestSeller();
        return $this->render('ReviewerReviewBundle:Default:index.html.twig',
            ['genres' => $allGenres,
                'bookReviews' => $latestReviews,
                'fiction' => $bestSellers["fiction"],
                'nonfiction' => $bestSellers["nonfiction"]
            ]
        );
    }

    public function apiClientAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $existingClient = $em->getRepository("ApiBundle:Client")->findOneBy(
            ["user" => $this->getUser()]
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

    public function generateTokenAction(Request $request)
    {
        $client = new Client();
        $password = $request->request->get('password');
        $em = $this->getDoctrine()->getManager();
        $existingClient = $em->getRepository("ApiBundle:Client")->findOneBy(
            ["user" => $this->getUser()]
        );

        $nytApi = new GuzzleHttp\Client(['base_uri' => 'https://api.nytimes.com/svc/books/v3/lists.json']);

//              $fiction = $nytApi->get("?list=combined-print-and-e-book-fiction&rank=1&api-key=A2wggy3cOa1WwMc6x5RnW5vnnGxrHIZb");
//        if ($fiction->getStatusCode() == 200) {
//            $book = json_decode((string)$fiction->getBody(), true);
//            var_dump($book)
//        }

        $clientId = $existingClient->getPublicId();
        $clientSecret = $existingClient->getSecret();
        $username = $this->getUser()->getUsername();

//        GuzzleHttp\RequestOptions::JSON => ['foo' => 'bar']

        $test = [
            "grant_type" => "password",
            "client_id" => "16_hezji88r2c088w00kk00c0kkk8ogwo0k4gsk8cokgw4cs0wgo",
            "client_secret" => "6dfqqckivkkc04o0okc0ksogc088kk0gwkkwwgkwowosww4404",
            "username" => "mohammed",
            "password" => "yellow"
        ];

        $test = GuzzleHttp\json_encode($test);

        $client = new GuzzleHttp\Client(['base_uri' => 'http://127.0.0.1:8000']);
        $response= $client->post('/oauth/v2/token', ["body" => $test]);
        var_dump($response);die;

        try {

            $result = json_decode((string)$token->getBody(), true);
            var_dump($result);

        } catch (\Exception $e) {

            var_dump($e);
        }
    }


    public function getNewYorkBestSeller()
    {
        $bookService = $this->container->get('book_service');
        $bestsellers = [];

        $nytApi = new GuzzleHttp\Client(['base_uri' => 'https://api.nytimes.com/svc/books/v3/lists.json']);
        try {
            $fiction = $nytApi->get("?list=combined-print-and-e-book-fiction&rank=1&api-key=A2wggy3cOa1WwMc6x5RnW5vnnGxrHIZb");
            if ($fiction->getStatusCode() == 200) {
                $book = json_decode((string)$fiction->getBody(), true);
                $isbn = $book["results"][0]["book_details"][0]["primary_isbn13"];
                if ($isbn == "9780735219090")
                    $isbn = "1472154630";
                $fictionBook = $bookService->fetchBookDetailsByIsbn($isbn);
                if ($fictionBook) {
                    $bestsellers["fiction"] = $fictionBook;
                }
            }

            $nonfiction = $nytApi->get("?list=combined-print-and-e-book-nonfiction&rank=1&api-key=A2wggy3cOa1WwMc6x5RnW5vnnGxrHIZb");
            if ($nonfiction->getStatusCode() == 200) {
                $book = json_decode((string)$nonfiction->getBody(), true);
                $isbn = $book["results"][0]["book_details"][0]["primary_isbn13"];
                $fictionBook = $bookService->fetchBookDetailsByIsbn($isbn);
                if ($fictionBook) {
                    $bestsellers["nonfiction"] = $fictionBook;
                }
            }
        } catch (\Exception $e) {
            $bestsellers = [];
        }
        return $bestsellers;
    }


}


<?php

namespace Reviewer\ReviewBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
class DefaultController extends Controller
{

    public function indexAction()
    {
        return $this->render('ReviewerReviewBundle:Default:index.html.twig');
    }
}


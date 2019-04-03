<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function IndexAction()
    {
        return $this->render('ApiBundle:Default:index.html.twig');
    }
}

<?php

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PageController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $blogEntries = $em->getRepository('BloggerBlogBundle:Entry')->getLatest(10, 0);

        return $this->render('BloggerBlogBundle:Page:index.html.twig',
            ['blogentries' => $blogEntries]);
    }

    public function aboutAction()
    {
        return $this->render('BloggerBlogBundle:Page:about.html.twig');
    }

}

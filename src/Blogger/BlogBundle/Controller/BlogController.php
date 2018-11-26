<?php

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Blogger\BlogBundle\Entity\Entry;
use Blogger\BlogBundle\Form\EntryType;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends Controller
{
    public function viewAction($id)
    {
        // Get the doctrine Entity manager
        $em = $this->getDoctrine()->getManager();
        // Use the entity manager to retrieve the Entry entity for the id
        // that has been passed
        $blogEntry = $em->getRepository('BloggerBlogBundle:Entry')->find($id);
        // Pass the entry entity to the view for displaying
        return $this->render('BloggerBlogBundle:Blog:view.html.twig',
            ['entry' => $blogEntry]);
    }

    public function createAction(Request $request)
    {
        // Create an new (empty) Entry entity
        $blogEntry = new Entry();
        // Create a form from the EntryType class to be validated
        // against the Entry entity and set the form action attribute
        // to the current URI
        $form = $this->createForm(EntryType::class, $blogEntry, [
            'action' => $request->getUri()
        ]);
        // If the request is post it will populate the form
        $form->handleRequest($request);
        // validates the form
        if ($form->isValid()) {
            // Retrieve the doctrine entity manager
            $em = $this->getDoctrine()->getManager();
            // manually set the author to the current user
            $blogEntry->setAuthor($this->getUser());
            // manually set the timestamp to a new DateTime object
            $blogEntry->setTimestamp(new \DateTime());
            // tell the entity manager we want to persist this entity
            $em->persist($blogEntry);
            // commit all changes
            $em->flush();
            return $this->redirect($this->generateUrl('view',
                ['id' => $blogEntry->getId()]));
        }
        // Render the view from the twig file and pass the form to the view
        return $this->render('BloggerBlogBundle:Blog:create.html.twig',
            ['form' => $form->createView()]);
    }

    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $blogEntry = $em->getRepository('BloggerBlogBundle:Entry')->find($id);
        $form = $this->createForm(EntryType::class, $blogEntry, [
            'action' => $request->getUri()
        ]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em->flush();
            return $this->redirect($this->generateUrl('view',
                ['id' => $blogEntry->getId()]));
        }
        return $this->render('BloggerBlogBundle:Blog:edit.html.twig',
            ['form' => $form->createView(),
                'entry' => $blogEntry]);
    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $blogEntry = $em->getRepository('BloggerBlogBundle:Entry')->find($id);
        $em->remove($blogEntry);
        $em->flush();
        return $this->redirect(
            $this->generateUrl('index'));

    }
}

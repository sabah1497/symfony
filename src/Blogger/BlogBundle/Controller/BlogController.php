<?php

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Blogger\BlogBundle\Entity\Review;
use Blogger\BlogBundle\Form\ReviewType;
use Blogger\BlogBundle\Security\ReviewVoter;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends Controller
{
//    public function viewAction($id)
//    {
//        // Get the doctrine Entity manager
//        $em = $this->getDoctrine()->getManager();
//
//        // Use the entity manager to retrieve the Entry entity for the id
//        // that has been passed
//        $blogReview = $em->getRepository('BloggerBlogBundle:Review')->find($id);
//
//        // Pass the review entity to the view for displaying
//        return $this->render('BloggerBlogBundle:Blog:view.html.twig',
//            ['reviews' => $blogReview]);
//
//    }

    public function createAction($id, Request $request)
    {
        // Create an new (empty) Entry entity
        $blogReview = new Review();

        // Create a form from the EntryType class to be validated
        // against the Entry entity and set the form action attribute
        //to the current URI
        $form = $this->createForm(ReviewType::class, $blogReview,['action' => $request->getUri()]);




        // If the request is post it will populate the form
        $form->handleRequest($request);

        // Validates the form
        if($form->isValid() and $form->isSubmitted())  {

            // Retrieve the doctrine entity manager
            $em = $this->getDoctrine()->getManager();

            $albums= $em->getRepository('BloggerBlogBundle:Album') ->find($id);

            // Manually set the author to the current user
            $blogReview->setReviewer($this->getUser());

            $blogReview->setReviewof($albums);

            // Manually set the timestamp to a new DateTime object
            $blogReview->setTimestamp(new \DateTime());

            // Tell the entity manager we want to persist this entity
            $em->persist($blogReview);

            // commit all changes
            $em->flush();
            $this->addFlash('success', 'Thank you! Your review has been added');

            return $this->redirect($this->generateUrl('blog_view',
                ['id' => $albums->getID()]));

        }

            // Render the view from the twig file and pass the form to the view
            return $this->render('BloggerBlogBundle:Blog:create.html.twig',
            ['form' => $form->createView()]);
    }

    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $blogReview = $em->getRepository('BloggerBlogBundle:Review')->find($id);
        if (!$this->isGranted(ReviewVoter::EDIT,$blogReview))
        {
            $this->addFlash('warning','you can not edit this album');
            return $this->redirectToRoute('blogger_index');
        }

        $form = $this->createForm(ReviewType::class, $blogReview, [
            'action' => $request->getUri()
            ]);

        $form ->handleRequest($request);

        if($form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Your review has been edited');


//            return $this->redirect($this->generateUrl('blogger_index',
//                ['id' => $blogReview->getID()]));
            return $this->redirect($this->generateUrl('blogger_more', ['id' => $blogReview->getReviewof()->getID()]));


        }

//        return $this->render('BloggerBlogBundle:Blog:edit.html.twig',
//            ['form' => $form->createView(),
//            'reviews' => $blogReview]);

        return $this->render('BloggerBlogBundle:Blog:edit.html.twig', array(
            'form' => $form->createView(), 'reviews' => $blogReview,
        ));

    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $blogReview = $em->getRepository('BloggerBlogBundle:Review')->find($id);



        if (!$this->isGranted(ReviewVoter::DELETE,$blogReview))

        {
            $this->addFlash('warning','You can not delete this review');
            return $this->redirect($this->generateUrl('blogger_index'));
        }

        $em->remove($blogReview);
        $em->flush();
        $this->addFlash('success', 'Your review has been deleted');

        return $this->redirect($this->generateUrl('blogger_index'));
    }

}

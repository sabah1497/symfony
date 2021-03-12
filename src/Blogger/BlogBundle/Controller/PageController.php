<?php

namespace Blogger\BlogBundle\Controller;

use Blogger\BlogBundle\BloggerBlogBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManagerInterface;

class PageController extends Controller
{
    public function indexAction()
    {

        $posts = $this->getDoctrine()->getRepository('Blogger\BlogBundle\Entity\Album')->findAll();

        dump($posts);

        return $this->render('BloggerBlogBundle:Page:index.html.twig'
            ,array('posts' => $posts));

    }

    public function aboutAction()
    {
        return $this->render('BloggerBlogBundle:Page:about.html.twig');
    }

    public function moreAction($id)
    {

//        $albums = $this->getDoctrine()->getRepository('Blogger\BlogBundle\Entity\Album')->findAll();
//
//        dump($albums);
//
//        return $this->render('BloggerBlogBundle:Page:more.html.twig'
//            ,array('albums' => $albums));

        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('Blogger\BlogBundle\Entity\Album')->find($id);
        $reviews = $em->getRepository('Blogger\BlogBundle\Entity\Review')->findAllReviewsby($id);

                return $this->render('BloggerBlogBundle:Page:more.html.twig'
            ,array('posts' => $posts, 'reviews' => $reviews));

                dump($reviews);

//        return $this->render('BloggerBlogBundle:Page:more.html.twig');

    }

}



<?php

namespace Blogger\BlogBundle\Controller;

use Blogger\BlogBundle\Entity\Album;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Blogger\BlogBundle\Form\AlbumType;
use Blogger\BlogBundle\Security\AlbumVoter;
use Blogger\BlogBundle\Service\FileUploader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
#


class AlbumController extends Controller
{
    public function viewAlbumAction ($id, Request $request)
    {
        // Get the doctrine Entity manager
        $em = $this->getDoctrine()->getManager();

        // Use the entity manager to retrieve the album entity for the id that has been passed
        $album = $em->getRepository('BloggerBlogBundle:Album')->find($id);

        // Gets the reviews query
        $reviewsQuery = $em->getRepository('BloggerBlogBundle:Review')->getQueryForReviews($id);


        // Pass the album entity to the view for displaying,
        return $this->render('BloggerBlogBundle:Page:more.html.twig', array(
            'album' => $album
        ));
    }

    public function createalbumAction(Request $request )
    {
        // Create an new (empty) album entity
        $album = new Album();

        $form = $this->createForm(AlbumType::class,$album,['action' => $request->getUri()]);
        $form->handleRequest($request);

        // I can delete isSubmitted
        if ($form->isSubmitted() and $form->isValid())
        {
            // Retrieve the doctrine entity manager
            $em = $this->getDoctrine()->getManager();

            // manually set the author to the current user
            $album->setWriter($this->getUser());

            // manually set the timestamp to a new DateTime object
            $album->setTimestamp(new \DateTime());

            $fileUploader = new FileUploader($this->getParameter('image_directory'));

            $image = $album->getImage();

            /**
             * $fileName = $this->generateUniqueFileName().'.'.$image->guessExtension();

            // Move the file to the directory where images are stored
             * $image->move($this->getParameter('image_directory'), $fileName);
             */
            $fileName = $fileUploader->upload($image);

            $album->setImage($fileName);

            // tell the entity manager we want to persist this entity
            $em->persist($album);
            // commit all changes
            $em->flush();
            // shows the flash
            $this->addFlash('success','you have added ' . $album->getTitle() . ' album to the library');
            return $this->redirect($this->generateUrl('blogger_more', ['id' => $album->getId()]));
        }
        return $this->render('BloggerBlogBundle:Album:createAlbum.html.twig', array(
            'form' => $form->createView(),
        ));
    }


    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }

    public function editalbumAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $album = $em->getRepository('BloggerBlogBundle:Album')->find($id);

        if (!$this->isGranted(AlbumVoter::EDIT,$album))
        {
            $this->addFlash('danger','you can not edit this album');
            return $this->redirectToRoute('blogger_index');
//            throw new LogicException('you cannot perform this action');
        }

        $album->setImage(new File($this->getParameter('image_directory').'/'.$album->getImage()));

        $form = $this->createForm(AlbumType::class, $album, ['action' => $request->getUri()]);
        $form->handleRequest($request);

        if($form->isValid() and $form->isSubmitted())
        {
            $fileUploader = new FileUploader($this->getParameter('image_directory'));
            $image = $album->getImage();

            /**
            $fileName = $this->generateUniqueFileName().'.'.$image->guessExtension();
                // Move the file to the directory where images are stored
                $image->move($this->getParameter('image_directory'), $fileName);
             */

            $fileName = $fileUploader->upload($image);
            $album->setImage($fileName);
            $em->flush();
            $this->addFlash('success','you have edited details of  ' . $album->getTitle());
            return $this->redirect($this->generateUrl('blogger_more', ['id' => $album->getId()]));
        }
        return $this->render('BloggerBlogBundle:Album:editAlbum.html.twig', array(
            'form' => $form->createView(), 'album' => $album,
        ));
    }

    public function deleteAlbumAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $album = $em->getRepository('BloggerBlogBundle:Album')->find($id);
        if (!$this->isGranted(AlbumVoter::DELETE,$album))
        {
            $this->addFlash('warning','you can not delete this album');
            return $this->redirectToRoute('blogger_index');
        }
        try {
            $image = $album->getImage();
            $em->remove($album);
            $em->flush();
           $path = $this->getParameter('image_directory').'/'.$image;
            $fileSystem = new Filesystem();
            $fileSystem->remove(array($path));
            $this->addFlash('success','you have successfully deleted  ' . $album->getTitle() . ' from the library');
        } catch (Exception $e)
        {
            $this->addFlash('danger','you have to delete the reviews before you can delete an image');
        }
        return $this->redirectToRoute('blogger_index');
    }


}
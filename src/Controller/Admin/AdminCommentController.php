<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comments", name="admin_comments_index")
     */
    public function index(CommentRepository $repo)
    {
        $all_comments = $repo->findAll();

        return $this->render('admin/comment/index.html.twig', [
            'comments'=>$all_comments
        ]);
    }

    /**
     * Edit a comment
     * 
     * @Route("admin/comment/{id}/edit", name = "admin_comment_edit")
     *
     * @param Comment $comment
     * @return Response
     */
    public function edit(Comment $comment, Request $request, EntityManagerInterface $manager){

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($comment);
            $manager->flush($comment);

            $this->addFlash(
                'success',
                "The comment n°{$comment->getId()} has been edited."
            );

            return $this->redirectToRoute('admin_comments_index');

        }

        return $this->render('admin/comment/edit.html.twig',[
            'form' => $form->createView(),
            'comment' => $comment
        ]);

    }

    /**
     * Deletes a comment
     * 
     * @Route("admin/comment/{id}/delete", name="admin_comment_delete")
     *
     * @return Response
     */
    public function delete(Comment $comment, EntityManagerInterface $manager){
        $manager->remove($comment);
        $manager->flush();

        $this->addFlash(
            'success',
            "The comment of <strong>{$comment->getAuthor()->getFullName()}</strong> on the ad {$comment->getAd()->getTitle()} has been deleted."
        );

        return $this->redirectToRoute('admin_comments_index');    
    }
}

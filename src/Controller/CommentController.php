<?php
/**
 * Created by PhpStorm.
 * User: manuel.renaudineau
 * Date: 20/12/17
 * Time: 13:49
 */

namespace App\Controller;


use App\AppAccess;
use App\Entity\Article;
use App\AppEvent;
use App\Entity\Comment;
use App\Entity\Training;
use App\Event\ArticleEvent;
use App\Event\CommentEvent;
use App\Form\CommentType;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @Route(path="/comment")
 */
class CommentController extends Controller
{

    /**
     * @Route(path="/{id}/new",name="app_comment_new")
     */
    public function newAction(Request $request, Training $training){

        $comment = $this->get(Comment::class);

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $event = $this->get(CommentEvent::class);
            $event->setComment($comment);
            $comment->setTraining($training);
            $dispatcher = $this->get("event_dispatcher");
            $dispatcher->dispatch(AppEvent::COMMENT_ADD, $event);

            return $this->redirectToRoute("welcome");
        }

        return $this->render("Comment/add.html.twig", ["form" => $form->createView()]);
    }
    /**
     * @Route(
     *     path="/",
     *     name="app_comment_index"
     * )
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $comments = $em->getRepository(Comment::class)->findAll();

        return $this->render('Comment/index.html.twig', ['comments' => $comments]);
    }

    /**
     * @Route(
     *     path="/{id}",
     *     name="app_comment_show"
     * )
     */
    public function showAction(Comment $id)
    {
        $em = $this->getDoctrine()->getManager();
        $comment = $em->getRepository(Comment::class)->findOneBy(array('id'=>$id));
        $em->persist($comment);
        $em->flush();
        return $this->render('Comment/show.html.twig', ['comment' => $comment]);
    }


    /**
     * @Route(path="/{id}/edit", name="app_comment_edit")
     *
     */
    public function editAction(Request $request, Comment $comment , AuthorizationCheckerInterface $authorizationChecker)
    {
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $event = $this->get(CommentEvent::class);
            $event->setComment($comment);
            $dispatcher = $this->get("event_dispatcher");
            $dispatcher->dispatch(AppEvent::COMMENT_EDIT, $event);

            return $this->redirectToRoute("welcome");
        }

        return $this->render("Comment/edit.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route(path="/{id}/delete", name="app_comment_delete")
     *
     */
    public function deleteAction(Comment $comment)
    {


        $event = $this->get(CommentEvent::class);
        $event->setComment($comment);
        $dispatcher = $this->get("event_dispatcher");
        $dispatcher->dispatch(AppEvent::COMMENT_DELETE, $event);

        return $this->redirectToRoute("welcome");

    }
}
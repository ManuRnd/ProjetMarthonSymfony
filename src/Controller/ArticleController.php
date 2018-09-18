<?php
/**
 * Created by PhpStorm.
 * User: manuel.renaudineau
 * Date: 20/12/17
 * Time: 09:25
 */

namespace App\Controller;


use App\AppAccess;
use App\AppEvent;
use App\Entity\Article;
use App\Entity\Media;
use App\Event\ArticleEvent;
use App\Event\MediaEvent;
use App\Form\ArticleType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Finder\Finder;
/**
 * @Route(path="/article")
 */
class ArticleController extends Controller
{


    /**
     * @Route(
     *     path="/option/{orderBy}",
     *     name="app_article_index",
     *     defaults={"orderBy"="updated_at"}
     * )
     */
    public function indexAction($orderBy)
    {
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository(Article::class)->findBy([],[$orderBy => "desc"]);

        return $this->render('Article/index.html.twig', ['articles' => $articles]);
    }

    /**
     * @Route(
     *     path="/show/{id}",
     *     name="app_article_show"
     * )
     */
    public function showAction(Article $id)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository(Article::class)->findOneBy(array('id'=>$id));
        $article->setHitcount(($article->getHitcount())+1);
        $em->persist($article);
        $em->flush();
        return $this->render('Article/show.html.twig', ['article' => $article]);
    }

    /**
     * @Route(
     *     path="/new",
     *     name="app_article_new"
     * )
     */
    public function newAction(Request $request)
    {
        $article = $this->get(\App\Entity\Article::class);
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $articleEvent = $this->get(ArticleEvent::class);

            $mediaEvent = $this->get(MediaEvent::class);
            $mediaEvent->setMedia($this->get(Media::class));
            $mediaEvent->setFile($form['media']->getData());

            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch(AppEvent::MEDIA_ADD, $mediaEvent);

            $article->setMedia($mediaEvent->getMedia());

            $articleEvent->setArticle($article);

            $dispatcher->dispatch(AppEvent::ARTICLE_ADD, $articleEvent);


            return $this->redirectToRoute("welcome");

        }

        return $this->render("Article/add.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route(path="/{id}/edit", name="app_article_edit")
     *
     */
    public function editAction(Request $request, Article $article, AuthorizationCheckerInterface $authorizationChecker)
    {

        if(false === $authorizationChecker->isGranted(AppAccess::ArticleEdit, $article)){
            $this->addFlash('error', 'access deny !');
            return $this->redirectToRoute("welcome");
        }

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $event = $this->get(ArticleEvent::class);
            $event->setArticle($article);
            $dispatcher = $this->get("event_dispatcher");
            $dispatcher->dispatch(AppEvent::ARTICLE_EDIT, $event);

            return $this->redirectToRoute("welcome");
        }

        return $this->render("Article/edit.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route(path="/{id}/delete", name="app_article_delete")
     *
     */
    public function deleteAction(Article $article, AuthorizationCheckerInterface $authorizationChecker)
    {
        if(false === $authorizationChecker->isGranted(AppAccess::ArticleEdit, $article)){
            $this->addFlash('error', 'access deny !');
            return $this->redirectToRoute("welcome");
        }

        $event = $this->get(ArticleEvent::class);
        $event->setArticle($article);
        $dispatcher = $this->get("event_dispatcher");
        $dispatcher->dispatch(AppEvent::ARTICLE_DELETE, $event);

        return $this->redirectToRoute("welcome");

    }

}
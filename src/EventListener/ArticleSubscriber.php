<?php

namespace App\EventListener;

use App\AppEvent;
use App\Entity\Article;
use App\Event\ArticleEvent;
use App\Event\UserEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ArticleSubscriber implements EventSubscriberInterface
{

    private $em;
    private $session;


    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session)
    {
        $this->em = $entityManager;
        $this->session = $session;

    }

    public static function getSubscribedEvents()
    {
        return [
            AppEvent::ARTICLE_ADD => array('add', 0),
            AppEvent::ARTICLE_EDIT => array('persist', 0),
            AppEvent::ARTICLE_DELETE => array('remove', 0),
            AppEvent::USER_DELETE => array('userDelete', 254)

        ];
    }


    public function add(ArticleEvent $articleEvent)
    {
        $entities = $this->em->getRepository(Article::class)->findBy(
            ['user' => $articleEvent->getArticle()->getUser()]
        );

            $this->persist($articleEvent);
    }

    public function persist(ArticleEvent $articleEvent)
    {
        $this->em->persist($articleEvent->getArticle());
        $this->em->flush();
    }

    public function remove(ArticleEvent $articleEvent)
    {
        $this->em->remove($articleEvent->getArticle());
        $this->em->flush();
    }

    public function userDelete(UserEvent $userEvent){
        $art=$this->em->getRepository(Article::class)->findBy(["user" => $userEvent->getUser()]);
        foreach ($art as $value){
            //commandes
            $this->em->remove($value);
        }

        $this->em->flush();

    }
}
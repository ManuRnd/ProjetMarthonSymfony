<?php
/**
 * Created by PhpStorm.
 * User: manuel.renaudineau
 * Date: 20/12/17
 * Time: 14:15
 */

namespace App\EventListener;


use App\Entity\Comment;
use App\AppEvent;
use App\Event\CommentEvent;
use App\Event\UserEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CommentSubscriber implements EventSubscriberInterface
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
            AppEvent::COMMENT_ADD=> array('add',0),
            AppEvent::COMMENT_EDIT =>array('persist',0),
            AppEvent::COMMENT_DELETE => array('remove',0),
            AppEvent::USER_DELETE => array('userDelete',254),


        ];
    }


    public function add(CommentEvent $commentEvent)
    {
        $entities = $this->em->getRepository(Comment::class)->findBy(
            ['user' => $commentEvent->getComment()->getUser()]
        );

        $this->persist($commentEvent);
    }

    public function persist(CommentEvent $commentEvent)
    {
        $this->em->persist($commentEvent->getComment());
        $this->em->flush();
    }

    public function remove(CommentEvent $commentEvent)
    {
        $this->em->remove($commentEvent->getComment());
        $this->em->flush();
    }

    public function userDelete(UserEvent $userEvent){
        $com=$this->em->getRepository(Comment::class)->findBy(["user" => $userEvent->getUser()]);
        foreach ($com as $value){
            //commandes
            $this->em->remove($value);
        }

        $this->em->flush();

    }
}

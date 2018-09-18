<?php
/**
 * Created by PhpStorm.
 * User: manuel.renaudineau
 * Date: 20/12/17
 * Time: 20:18
 */

namespace App\EventListener;

use App\Entity\User;
use App\AppEvent;
use App\Event\UserEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserSubscriber implements EventSubscriberInterface
{

    private $session;
    private $em;


    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session)
    {
        $this->em = $entityManager;
        $this->session = $session;

    }

    public static function getSubscribedEvents()
    {
        return [
            AppEvent::USER_EDIT =>array('persist',0),
            AppEvent::USER_DELETE => array('remove',0),
            AppEvent::USER_BLOCK => array('block',0),
        ];
    }


    public function persist(UserEvent $userEvent)
    {
        $this->em->persist($userEvent->getUser());
        $this->em->flush();
    }

    public function remove(UserEvent $userEvent)
    {
        $this->em->remove($userEvent->getUser());
        $this->em->flush();
    }

    public function block(UserEvent $userEvent) {
        $user = $userEvent->getUser();

        $salt = '$2y$13$' . substr(md5(uniqid(rand(), true)),0,21) . '$';

        $user->setPassword($salt);
        $this->em->flush();
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: manuel.renaudineau
 * Date: 20/12/17
 * Time: 17:20
 */

namespace App\EventListener;


use App\Entity\Vote;
use App\AppEvent;
use App\Event\UserEvent;
use App\Event\VoteEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class VoteSubscriber implements EventSubscriberInterface
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
            AppEvent::VOTE_ADD=>array('add',1),
            AppEvent::VOTE_EDIT =>array('persist',1),
            AppEvent::VOTE_DELETE => array('remove',1),
            AppEvent::USER_DELETE => array('deleteUser',254),


        ];
    }


    public function add(VoteEvent $voteEvent)
    {
        $entities = $this->em->getRepository(Vote::class)->findBy(
            ['user' => $voteEvent->getVote()->getUser()]
        );

        $this->persist($voteEvent);
    }

    public function persist(VoteEvent $voteEvent)
    {
        $this->em->persist($voteEvent->getVote());
        $this->em->flush();
    }

    public function remove( VoteEvent $voteEvent)
    {
        $this->em->remove($voteEvent->getVote());
        $this->em->flush();
    }

    public function deleteUser(UserEvent $userEvent){
        $vote=$this->em->getRepository(Vote::class)->findBy(["user" => $userEvent->getUser()]);
        foreach ($vote as $value){
            //commandes
            $this->em->remove($value);
        }

        $this->em->flush();

    }
}
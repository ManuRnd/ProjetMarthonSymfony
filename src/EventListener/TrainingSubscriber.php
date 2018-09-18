<?php
/**
 * Created by PhpStorm.
 * User: manuel.renaudineau
 * Date: 21/12/17
 * Time: 01:41
 */

namespace App\EventListener;


use App\Entity\Training;
use App\AppEvent;
use App\Event\TrainingEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class TrainingSubscriber implements EventSubscriberInterface
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
            AppEvent::TRAINING_ADD => array('persist', 0),
            AppEvent::TRAINING_EDIT => array('persist', 0),
            AppEvent::TRAINING_DELETE => array('remove', 0),

        ];
    }

    public function persist(TrainingEvent $trainingEvent)
    {
        $this->em->persist($trainingEvent->getTraining());
        $this->em->flush();
    }

    public function remove(TrainingEvent $trainingEvent)
    {
        $this->em->remove($trainingEvent->getTraining());
        $this->em->flush();
    }


}
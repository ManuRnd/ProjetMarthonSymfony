<?php
/**
 * Created by PhpStorm.
 * User: manuel.renaudineau
 * Date: 21/12/17
 * Time: 01:26
 */

namespace App\Controller;


use App\Entity\Media;
use App\Entity\Training;
use App\AppEvent;
use App\Entity\Vote;
use App\Event\MediaEvent;
use App\Event\TrainingEvent;
use App\Form\TrainingType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @Route (path="/training")
 */
class TrainingController extends Controller
{

    /**
     * @Route (path="/index/{orderBy}", name="app_training_index",defaults={"orderBy"="moyenne"})
     */
    public function indexAction($orderBy){
        if($orderBy == "moyenne") {
            $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery(
                'SELECT t.name, t.id, t.difficulty, t.training_time,AVG(v.value) as moyenne from App\Entity\Training t join t.votes v where t= v.training GROUP BY t.name ORDER By moyenne DESC'
            );

            $trainings = $query->getResult();
        }
        else if($orderBy=="updated_at"){
            $em = $this->getDoctrine()->getManager();
            $trainings = $em->getRepository(Training::class)->findBy([],[$orderBy => "desc"]);
        }

        return $this->render('Training/index.html.twig', ['trainings' => $trainings]);

    }

    /**
     * @Route(
     *     path="/show/{id}",
     *     name="app_training_show"
     * )
     */
    public function showAction(Training $id)
    {   $moy = $this->averageVote($id);
        $em = $this->getDoctrine()->getManager();
        $training = $em->getRepository(Training::class)->findOneBy(array('id'=>$id));
        $em->persist($training);
        $em->flush();
        return $this->render('Training/show.html.twig', ['training' => $training, "moyenne"=>$moy]);
    }

    /**
     * @Route(
     *     path="/new",
     *     name="app_training_new"
     * )
     */
    public function newAction(Request $request)
    {
        $training = $this->get(Training::class);
        $form = $this->createForm(TrainingType::class, $training);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $training->setCreatedAt(new \DateTime());
            $training->setUpdatedAt(new \DateTime());

            $trainingevent = $this->get(TrainingEvent::class);

            $mediaEvent = $this->get(MediaEvent::class);
            $mediaEvent->setMedia($this->get(Media::class));
            $mediaEvent->setFile($form['media']->getData());

            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch(AppEvent::MEDIA_ADD, $mediaEvent);

            $training->setMedia($mediaEvent->getMedia());

            $trainingevent->setTraining($training);

            $dispatcher->dispatch(AppEvent::TRAINING_ADD, $trainingevent);

            return $this->redirectToRoute("welcome");
        }

        return $this->render("Training/add.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route(path="/{id}/edit", name="app_training_edit")
     *
     */
    public function editAction(Request $request, Training $training, AuthorizationCheckerInterface $authorizationChecker)
    {

        $form = $this->createForm(TrainingType::class, $training);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $event = $this->get(TrainingEvent::class);
            $event->setTraining($training);
            $training->setUpdatedAt(new \DateTime());
            $dispatcher = $this->get("event_dispatcher");
            $dispatcher->dispatch(AppEvent::TRAINING_EDIT, $event);

            return $this->redirectToRoute("welcome");
        }

        return $this->render("Training/edit.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route(path="/{id}/delete", name="app_training_delete")
     *
     */
    public function deleteAction(Training $training)
    {

        $event = $this->get(TrainingEvent::class);
        $event->setTraining($training);
        $dispatcher = $this->get("event_dispatcher");
        $dispatcher->dispatch(AppEvent::TRAINING_DELETE, $event);
        return $this->redirectToRoute("welcome");
    }

    /**
     * @return float
     */
    public function averageVote(Training $id){
        $somme=0.0;
        $em = $this->getDoctrine()->getManager();
        $votes = $em->getRepository(Vote::class)->findBy(array('training'=>$id));
        foreach ($votes as $vote){
            $somme+=$vote->getValue();
        }

        return (floatval($somme/(sizeof($votes))));
    }
}
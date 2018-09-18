<?php
/**
 * Created by PhpStorm.
 * User: manuel.renaudineau
 * Date: 20/12/17
 * Time: 17:09
 */

namespace App\Controller;


use App\AppAccess;
use App\Entity\Training;
use App\Entity\Vote;

use App\AppEvent;
use App\Event\VoteEvent;
use App\Form\VoteType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Validator\Validation;

/**
 * @Route (path="/vote")
 */
class VoteController extends Controller
{
    /**
     * @Route (path="/{id}/new", name="app_vote_new")
     */
    public function newAction(Request $request, Training $training){

        $vote = $this->get(Vote::class);

        $form = $this->createForm(VoteType::class, $vote);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $validator = Validation::createValidator();
            $violations = $validator->validate($vote);

            if (0 !== count($violations)) {
                // there are errors, now you can show them
                foreach ($violations as $violation) {
                    echo $violation->getMessage().'<br>';
                }
            }
            else
            {
                $event = $this->get(VoteEvent::class);
                $event->setVote($vote);
                $vote->setTraining($training);
                $dispatcher = $this->get("event_dispatcher");
                $dispatcher->dispatch(AppEvent::VOTE_ADD, $event);

                return $this->redirectToRoute("welcome");
            }
        }

        return $this->render("Vote/add.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route(
     *     path="/",
     *     name="app_vote_index"
     * )
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $votes = $em->getRepository(Vote::class)->findAll();

        return $this->render('Vote/index.html.twig', ['votes' => $votes]);
    }

    /**
     * @Route(
     *     path="/{id}",
     *     name="app_vote_show"
     * )
     */
    public function showAction(Vote $id)
    {
        $em = $this->getDoctrine()->getManager();
        $vote = $em->getRepository(Vote::class)->findOneBy(array('id'=>$id));
        $em->persist($vote);
        $em->flush();
        return $this->render('Vote/show.html.twig', ['vote' => $vote]);
    }


    /**
     * @Route(path="/{id}/edit", name="app_vote_edit")
     *
     */
    public function editAction(Request $request, Vote $vote , AuthorizationCheckerInterface $authorizationChecker)
    {


        $form = $this->createForm(VoteType::class, $vote);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $event = $this->get(VoteEvent::class);
            $event->setVote($vote);
            $dispatcher = $this->get("event_dispatcher");
            $dispatcher->dispatch(AppEvent::VOTE_EDIT, $event);

            return $this->redirectToRoute("welcome");
        }

        return $this->render("Vote/edit.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route(path="/{id}/delete", name="app_vote_delete")
     *
     */
    public function deleteAction(Vote $vote, AuthorizationCheckerInterface $authorizationChecker)
    {


        $event = $this->get(VoteEvent::class);
        $event->setVote($vote);
        $dispatcher = $this->get("event_dispatcher");
        $dispatcher->dispatch(AppEvent::VOTE_DELETE, $event);

        return $this->redirectToRoute("welcome");

    }
}
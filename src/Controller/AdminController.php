<?php

namespace App\Controller;

use App\Entity\User;
use App\AppEvent;
use App\Entity\Vote;
use App\Event\ArticleEvent;
use App\Event\UserEvent;
use App\Event\VoteEvent;
use App\Form\AdminType;
use App\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)->findAll();
        return $this->render('admin/index.html.twig',['users'=>$users]);
    }

    /**
     * @Route("/admin/edit/{id}", name="app_admin_edit")
     */
    public function editAction(Request $request, User $user)
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $event = $this->get(UserEvent::class);
            $event->setUser($user);
            $dispatcher = $this->get("event_dispatcher");
            $dispatcher->dispatch(AppEvent::USER_EDIT, $event);

            return $this->redirectToRoute("welcome");
        }

        return $this->render('admin/edit.html.twig', ["form" => $form->createView()]);
    }

    /**
     * @Route(path="/admin/delete/{id}", name="app_admin_delete")
     *
     */
    public function deleteAction(User $user)
    {

        $event = $this->get(UserEvent::class);
        $event->setUser($user);
        $dispatcher = $this->get("event_dispatcher");
        $dispatcher->dispatch(AppEvent::USER_DELETE, $event);

        return $this->redirectToRoute("welcome");
    }

    /**
     * @Route(path="/admin/block/{id}", name="app_admin_block")
     *
     */
    public function blockAction(User $user) {
        $event = $this->get(UserEvent::class);
        $event->setUser($user);
        $dispatcher = $this->get("event_dispatcher");
        $dispatcher->dispatch(AppEvent::USER_BLOCK, $event);

        return $this->redirectToRoute("welcome");
    }
}

<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Training;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class WelcomeController extends AbstractController
{
    /**
     * @Route("/", name="welcome")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();

        $articles = $em->getRepository(Article::class)->findBy([],["updated_at" => "desc"]);

        $trainings = $em->getRepository(Training::class)->findBy([],["updated_at" => "desc"]);

        return $this->render('welcome/welcome.html.twig', ['articles' => $articles, 'trainings' => $trainings]);
    }
}

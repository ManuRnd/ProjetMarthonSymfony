<?php
/**
 * Created by PhpStorm.
 * User: quentin.geeraert
 * Date: 20/12/17
 * Time: 17:18
 */

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

class ExceptionController extends Controller
{
    public function showException(FlattenException $exception)
    {
        $code = $exception->getStatusCode();

        if ($code === 400) { $message = "La syntaxe de la requête est erronée."; }
        if ($code === 401) { $message = "Une authentification est nécessaire pour accéder à la ressource."; }
        if ($code === 403) { $message = "Le serveur a compris la requête, mais refuse de l'exécuter. Contrairement à l'erreur 401, s'authentifier ne fera aucune différence. Sur les serveurs où l'authentification est requise, cela signifie généralement que l'authentification a été acceptée mais que les droits d'accès ne permettent pas au client d'accéder à la ressource."; }
        if ($code === 404) { $message = "Ressource non trouvée."; }
        if ($code === 405) { $message = "Méthode de requête non autorisée."; }
        if ($code === 406) { $message = "La ressource demandée n'est pas disponible dans un format qui respecterait les en-têtes \"Accept\" de la requête."; }
        if ($code === 407) { $message = "Accès à la ressource autorisé par identification avec le proxy."; }
        if ($code === 408) { $message = "Temps d’attente d’une requête du client écoulé."; }
        if ($code === 409) { $message = "La requête ne peut être traitée en l’état actuel."; }
        if ($code === 410) { $message = "La ressource n'est plus disponible et aucune adresse de redirection n’est connue."; }
        if ($code === 413) { $message = "Traitement abandonné dû à une requête trop importante."; }
        if ($code === 415) { $message = "Format de requête non supporté pour une méthode et une ressource données."; }
        if ($code === 416) { $message = "Champs d’en-tête de requête « range » incorrect."; }
        if ($code === 417) { $message = "Comportement attendu et défini dans l’en-tête de la requête insatisfaisante."; }

        if ($code === 500) { $message = "Erreur interne du serveur."; }
        if ($code === 503) { $message = "Service temporairement indisponible ou en maintenance."; }
        if ($code === 504) { $message = "Temps d’attente d’une réponse d’un serveur à un serveur intermédiaire écoulé."; }

        return $this->render('Exception/error.html.twig', ['statusCode' => $code, 'message' => $message]);
    }
}
<?php

namespace App\Controller;

use App\Entity\Session;
use App\Repository\SessionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionController extends AbstractController
{
   


    #[Route('/session', name: 'app_session')]
    public function index(SessionRepository $sessionRepository): Response
    {
        $sessions = $sessionRepository->findBy([], ["endDate" => "ASC"]);
        return $this->render('session/index.html.twig', [
            // 'controller_name' => 'SessionController',
            'sessions' => $sessions
        ]);
    }



    #[Route('/session/{id}', name: 'show_session')]
    public function show(Session $session): Response {

        return $this->render('session/show.html.twig', [
            'session' => $session
        ]);
    }

}

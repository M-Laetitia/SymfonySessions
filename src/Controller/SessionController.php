<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\ModuleFormation;
use App\Entity\Programme;
use App\Form\SessionType;
use App\Form\ProgrammeType;
use App\Repository\SessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/session/new', name: 'new_session')]
    #[Route('/session/{id}/edit', name: 'edit_session')]
    public function new_edit(Session $session = null, Request $request, EntityManagerInterface $entityManager) : Response
    {

        if(!$session) {
            $session = new session();
        }

        $form = $this->createForm(SessionType::class, $session);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $session = $form->getData();
            $entityManager->persist($session);
            $entityManager->flush();

            return $this->redirectToRoute('app_session');
        }

        return $this->render('session/new.html.twig', [
            'formAddSession' => $form,
            'edit' => $session->getId()
        ]);

    }


    #[Route('/session/{id}/delete', name: 'delete_session')]
    public function delete(Session $session, EntityManagerInterface $entityManager) : Response {

        // remove: enlève de la collection l'employé ciblé
        // flush : fait la requête SQl auprès de la BD
        $entityManager->remove($session);
        $entityManager->flush();

        return $this->redirectToRoute('app_session');
    }


    #[Route('/session/{id}', name: 'show_session')]
    public function show(Session $session,  Request $request, EntityManagerInterface $entityManager): Response {
        
        $programme = new Programme();    
        $form = $this->createForm(ProgrammeType::class, $programme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $programme = $form->getData();
            $entityManager->persist($programme);
            $entityManager->flush();
            return$this->redirectToRoute('app_session');
        }

        return $this->render('session/show.html.twig', [
            'session' => $session,
            'formAddProgramme' => $form,
            'edit' => $programme->getId()
        ]);
    }

}

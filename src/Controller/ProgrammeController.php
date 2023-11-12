<?php

namespace App\Controller;

use App\Entity\Programme;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProgrammeController extends AbstractController
{
    #[Route('/programme', name: 'app_programme')]
    public function index(): Response
    {
        return $this->render('programme/index.html.twig', [
            'controller_name' => 'ProgrammeController',
        ]);
    }


    #[Route('/programme/{id}/delete', name: 'delete_programme')]
    public function delete(Programme $programme, EntityManagerInterface $entityManager): Response
    {
        // Get the associated Session
        $session = $programme->getSession();

        // Remove the Programme from the Session
        $session->removeProgramme($programme);

        // Persist and flush changes
        $entityManager->persist($session);
        $entityManager->flush();

        // Redirect back to the session or wherever you want
        return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    }

}

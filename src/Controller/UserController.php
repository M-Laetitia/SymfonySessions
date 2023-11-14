<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AvatarType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Vich\UploaderBundle\Handler\UploadHandler;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/user/{id}', name: 'show_user')]
    public function show(User $user = null, Security $security, Request $request, UploadHandler $uploadHandler, EntityManagerInterface $entityManager): Response {
    $user = $security->getUser();

    // Si l'utilisateur n'est pas connecté, redirigez-le vers la page d'accueil ou une autre page.
    if (!$user instanceof User) {
        return $this->redirectToRoute('app_home');
    }

    $user = new User();
    $form = $this->createForm(AvatarType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Gérez l'upload ici en utilisant le gestionnaire d'upload de VichUploaderBundle
        $uploadHandler->upload($user, 'avatarFile');

      
        $entityManager->flush();

        return $this->redirectToRoute('user_show', ['id' => $user->getId()]);
    }

    return $this->render('user/show.html.twig', [
        'user' => $user,
        'form' => $form->createView(),
    ]);


}
    

}

<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AvatarType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
    public function show(User $user = null, Security $security, Request $request, EntityManagerInterface $entityManager): Response {
    $user = $security->getUser();

    // Si l'utilisateur n'est pas connecté, redirection vers la page d'accueil ou une autre page.
    if (!$user instanceof User) {
        return $this->redirectToRoute('app_home');
    }


    $form = $this->createForm(AvatarType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
       
        $avatarFile = $form->get('avatar')->getData();
        // dd($avatarFile);
 
        // this condition is needed because the 'avatar' field is not required
            // so the image file must be processed only when a file is uploaded

            if ($avatarFile) {
                // Supprimer l'ancienne image si elle existe

                $oldAvatar = $user->getAvatar();
                if ($oldAvatar) {
                    $oldAvatarPath = $this->getParameter('avatars_directory').'/'.$oldAvatar;
                    if (file_exists($oldAvatarPath)) {
                        // remplacement dans la base de données
                        $user->setAvatar(null);
                        $entityManager->persist($user);
                        $entityManager->flush();
                    }
                }


                // si on veut aussi supprimer l'image dans le dossier d'image / ne fonctionne pas
                // $oldAvatar = $user->getAvatar();
                // if ($oldAvatar) {
                //     // Construire le chemin complet vers l'ancienne image en concaténant le répertoire de stockage des avatars ('avatars_directory') avec le nom du fichier de l'ancienne image.
                //     $oldAvatarPath = $this->getParameter('avatars_directory').'/'.$oldAvatar;
                //      // Vérifier si le fichier de l'ancienne image existe
                //     if (file_exists($oldAvatarPath)) {
                //         // Supprimer le fichier de l'ancienne image.  La fonction unlink est utilisée pour supprimer un fichier.
                //         unlink($oldAvatarPath);
                //     }
                // }



                $newFilename = uniqid().'.'.$avatarFile->guessExtension();

                // Move the file to the directory where avatars are stored
                try {
                    $avatarFile->move(
                        $this->getParameter('avatars_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'avatarFilename' property to store the PDF file name
                // instead of its contents
                $user->setAvatar($newFilename);
                $entityManager->flush();
            }

            // ... persist the $product variable or any other work


        return $this->redirectToRoute('show_user', ['id' => $user->getId()]);
    }

    return $this->render('user/show.html.twig', [
        'user' => $user,
        'form' => $form->createView(),
    ]);


}
    

}

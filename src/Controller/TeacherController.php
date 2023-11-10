<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\TeacherType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TeacherController extends AbstractController
{
    // #[Route('/teacher', name: 'app_teacher')]
    // public function index(): Response
    // {
    //     return $this->render('teacher/index.html.twig', [
    //         'controller_name' => 'TeacherController',
    //     ]);
    // }


    #[Route('/teacher', name: 'app_teacher')]
    public function index(UserRepository $userRepository): Response
    {
        // Utilisation de createQueryBuilder
        // https://www.doctrine-project.org/projects/doctrine-orm/en/2.16/reference/query-builder.html
        $teachers = $userRepository->createQueryBuilder('u')
            ->where("u.roles LIKE :role")
            ->setParameter('role', '%"ROLE_TEACHER"%')

            // ->where("u.roles LIKE :role_admin OR u.roles LIKE :role_user")
            // ->setParameters([
            //     'role_admin' => '%"ROLE_ADMIN"%',
            //     'role_user' => '%"ROLE_USER"%',
            // ])

            ->orderBy('u.lastName', 'ASC')
            ->getQuery()
            ->getResult();

        return $this->render('teacher/index.html.twig', [
            'controller_name' => 'TeacherController',
            'teachers' => $teachers,
        ]);
    }

    #[Route('/teacher/new', name: 'new_teacher')]
    #[Route('/teacher/{id}/edit', name: 'edit_teacher')]
    public function new_edit(User $user = null, Request $request, EntityManagerInterface $entityManager) : Response
    {

        if(!$user) {
            $user = new User();
        }

        $form = $this->createForm(TeacherType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_teacher');
        }

        return $this->render('teacher/new.html.twig', [
            'formAddTeacher' => $form,
            'edit' => $user->getId()
        ]);

    }

    #[Route('/teacher/{id}/delete', name: 'delete_teacher')]
    public function delete(User $user, EntityManagerInterface $entityManager) : Response {


        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_teacher');
    }

    
    #[Route('/teacher/{id}', name: 'show_teacher')]
    public function show(User $user): Response {

        return $this->render('teacher/show.html.twig', [
            'user' => $user
        ]);
    }
}




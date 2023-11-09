<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
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
}




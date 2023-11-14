<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Student;
use App\Form\StudentType;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StudentController extends AbstractController
{
    #[Route('/student', name: 'app_student')]
    public function index(StudentRepository $studentRepository): Response
    {
        $students = $studentRepository->findBy([], ["lastName" => "ASC"]);
        return $this->render('student/index.html.twig', [
            // 'controller_name' => 'StudentController',
            'students' => $students
        ]);
    }

    #[Route('/student/new', name: 'new_student')]
    #[Route('/student/{id}/edit', name: 'edit_student')]
    public function new_edit(Student $student = null, Session $session, Request $request, EntityManagerInterface $entityManager) : Response
    {

        if(!$student && $session->getNbPlaceRemaining() < 0 ) {
            $student = new Student();
        } elseif ($session->getNbPlaceRemaining() <= 0) {
            // ajouter msg erreur
            return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
        }

        $form = $this->createForm(StudentType::class, $student);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $student = $form->getData();
            $entityManager->persist($student);
            $entityManager->flush();

            return $this->redirectToRoute('app_student');
        }

        return $this->render('student/new.html.twig', [
            'formAddStudent' => $form,
            'edit' => $student->getId()
        ]);

    }

    #[Route('/student/{id}/delete', name: 'delete_student')]
    public function delete(Student $student, EntityManagerInterface $entityManager) : Response {

        // remove: enlève de la collection l'employé ciblé
        // flush : fait la requête SQl auprès de la BD
        $entityManager->remove($student);
        $entityManager->flush();

        return $this->redirectToRoute('app_student');
    }


    
     // on nomme l'id id pour utiliser le paramConverter - faire le lien avec l'object qu'on souhaite facilement
     #[Route('/student/{id}', name: 'show_student')]
     public function show(Student $student): Response {
 
         return $this->render('student/show.html.twig', [
             'student' => $student
         ]);
     }
}

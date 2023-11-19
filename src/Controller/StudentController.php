<?php

namespace App\Controller;

use Knp\Snappy\Pdf;
use App\Entity\Session;
use App\Entity\Student;
use App\Form\StudentType;
use App\Service\ValidationService;
use App\Repository\SessionRepository;
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
    public function new_edit(Student $student = null, Request $request, EntityManagerInterface $entityManager) : Response
    {

        if(!$student) {
            $student = new Student();
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
     public function show(SessionRepository $sessionRepository, Student $student = null): Response
    {

        $currentSessions = $sessionRepository->findCurrentSessions($student, null);
        $upcomingSessions = $sessionRepository->findUpcomingSessions($student, null);
        $pastSessions = $sessionRepository->findPastSessions($student, null);

        // dump($currentSessions, $upcomingSessions, $pastSessions);die;
        if (!$student) {
            // L'entité Student avec cet ID n'existe pas, redirigez vers la page d'accueil ou une autre page.
            return $this->redirectToRoute('app_home');
        }

        return $this->render('student/show.html.twig', [
            'student' => $student,
            'currentSessions' => $currentSessions,
            'upcomingSessions' => $upcomingSessions,
            'pastSessions' => $pastSessions,
        ]);
    }


    /**
     * Export to PDF
     *
     * @Route("/pdf", name="acme_demo_pdf")
     */
    public function pdfAction(Pdf $pdf): Response
    {
        $html = $this->renderView('student/show.html.twig');

        $filename = sprintf('test-%s.pdf', date('Y-m-d'));

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
            ]
        );
    }
}






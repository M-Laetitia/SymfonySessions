<?php
 namespace App\Controller;
 
use App\Entity\Session;
use App\Entity\Student;
use App\Entity\Programme;
use App\Form\SessionType;
use App\Form\StudentType;
use App\Form\ProgrammeType;
use App\Entity\ModuleFormation;
use App\Form\StudentSessionType;
use App\Repository\SessionRepository;
use App\Repository\StudentRepository;
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
    public function new_edit(Session $session = null, Request $request, EntityManagerInterface $entityManager): Response
    {
 
        if (!$session) {
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
    public function delete(Session $session, EntityManagerInterface $entityManager): Response
    {
 
        // remove: enlève de la collection l'employé ciblé
        // flush : fait la requête SQl auprès de la BD
        $entityManager->remove($session);
        $entityManager->flush();
 
        return $this->redirectToRoute('app_session');
    }
 
 
    #[Route('/session/{id}', name: 'show_session')]
    public function show(Session $session = null, Request $request, EntityManagerInterface $entityManager, SessionRepository $sr): Response
    {
 
        //^ Form to add Module
        $programme = new Programme();
        //  when a form  needs to be associated with a specific entity, use the setEntity method to set that entity on the form. 
        // Before creating the form, set the Session entity on the Programme entity using $programme->setSession($session); = "I'm creating a new Programme, and it is associated with this specific Session."
        $programme->setSession($session);
        $form = $this->createForm(ProgrammeType::class, $programme);
        $form->handleRequest($request);
 
        if ($form->isSubmitted() && $form->isValid()) {
 
            $programme = $form->getData();
            $entityManager->persist($programme);
            $entityManager->flush();
            return $this->redirectToRoute('show_session', ['id' => $programme->getSession()->getId()]);
        }
 
        //^ Form to add Student

        $programme->setSession($session);
        
        $formStudentSession = $this->createForm(StudentSessionType::class);
        $formStudentSession->handleRequest($request);
 
        if ($formStudentSession->isSubmitted() && $formStudentSession->isValid()) {
            $students = $formStudentSession->getData();

            // Add students to the session
            // get the data from the form. The form handles students, $students should now contain the selected students.
            foreach ($students as $student) {
                $session->addStudent($student);
            }

            $entityManager->persist($session);
            $entityManager->flush();
            return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
        }

        $noneRegisteredStudents = $sr->findNoneRegistered($session->getId());
 
        return $this->render('session/show.html.twig', [
            'session' => $session,
            'formAddProgramme' => $form,
            // 'formAddStudent' => $formStudentSession->createView(),
            'noneRegisteredStudents' => $noneRegisteredStudents,
            'formAddStudentSession' => $formStudentSession->createView(),
            'edit' => $programme->getId()
        ]);
    }


    #[Route('/session/{session}/delete/{student}', name: 'remove_student_from_session')]
    public function removeStudent(Session $session, Student $student, EntityManagerInterface $entityManager): Response
    {
        // $session = $entityManager->find(Session::class, $id);
        // $student = $entityManager->find(Student::class, $studentId);
    
       
        // if ($session && $student) {
            $session->removeStudent($student);
            $entityManager->flush();
        // }
    

    return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    }

}

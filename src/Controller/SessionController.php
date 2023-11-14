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

        $currentSessions = $sessionRepository->findCurrentSessions();
        $upcomingSessions = $sessionRepository->findUpcomingSessions();
        $pastSessions = $sessionRepository->findPastSessions();
        // dd($pastSessions);

        return $this->render('session/index.html.twig', [
            // 'controller_name' => 'SessionController',
            'sessions' => $sessions,
            'currentSessions' => $currentSessions,
            'upcomingSessions' => $upcomingSessions,
            'pastSessions' => $pastSessions,
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
        $programme->setSession($session);

        $noneAddedModules = $sr->findNoneAdded($session->getId());
 

        $form = $this->createForm(ProgrammeType::class, $programme, ['modules' => $noneAddedModules]);
        $form->handleRequest($request);
 
        if ($form->isSubmitted() && $form->isValid()) {
            $programme = $form->getData();
            $entityManager->persist($programme);
            $entityManager->flush();
            return $this->redirectToRoute('show_session', ['id' => $programme->getSession()->getId()]);
        }
 
        
        $noneRegisteredStudents = $sr->findNoneRegistered($session->getId());
        // dd($noneAddedModules);
        
 
        return $this->render('session/show.html.twig', [
            'session' => $session,
            'formAddProgramme' => $form,
            'noneRegisteredStudents' => $noneRegisteredStudents,
            'noneAddedModules' => $noneAddedModules,
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


    #[Route('/session/{session}/add/{student}', name: 'add_student_from_session')]
    public function addStudent(Session $session, Student $student, EntityManagerInterface $entityManager): Response
    {
        // $session = $entityManager->find(Session::class, $id);
        // $student = $entityManager->find(Student::class, $studentId);

        // if ($session && $student) {
            $session->addStudent($student);
            $entityManager->flush();
        // }
    

    return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    }

}

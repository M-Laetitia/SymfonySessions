<?php
 namespace App\Controller;

use App\Service\ValidationService;
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

// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;
 
 
class SessionController extends AbstractController
{
 




    #[Route('/session', name: 'app_session')]
    public function index(SessionRepository $sessionRepository,Request $request, PaginatorInterface $paginator): Response
    {
        $sessions = $sessionRepository->findBy([], ["endDate" => "ASC"]);

        $currentSessions = $sessionRepository->findCurrentSessions();
        $upcomingSessions = $sessionRepository->findUpcomingSessions();
        $pastSessions = $sessionRepository->findPastSessions();
        // dd($pastSessions);
        

        // Vérifier si la demande est une requête AJAX
        if ($request->isXmlHttpRequest()) {
            // Si oui, renvoyer uniquement les futures sessions en format JSON
            $pastSessionsData = [];
            foreach ($pastSessions as $session) {
                $pastSessionsData[] = [
                    'id' => $session->getId(),
                    // 'nom' => $session->getNom(),
                    
                ];
            }

            return new JsonResponse($pastSessionsData);
        }


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
    public function show($id, ValidationService $validationService, Session $session = null, Request $request, EntityManagerInterface $entityManager, SessionRepository $sr): Response
    {
 
        $entityClassName = "App\Entity\Session";

        if (!$validationService->isValidId($id, $entityClassName)) {
            // Redirection vers une autre page si l'ID n'est pas valide
            return $this->redirectToRoute('app_home'); 
        }


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

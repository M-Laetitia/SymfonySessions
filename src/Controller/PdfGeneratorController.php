<?php
 
namespace App\Controller;
 
use Dompdf\Dompdf;
use App\Entity\Student;
use App\Repository\SessionRepository;
use App\Repository\StudentRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
 
class PdfGeneratorController extends AbstractController
{
    #[Route('/pdf/generator/{id}', name: 'app_pdf_generator')]
    public function index(SessionRepository $sessionRepository, StudentRepository $studentRepository, Student $student = null): Response
    {
        // return $this->render('pdf_generator/index.html.twig', [
        //     'controller_name' => 'PdfGeneratorController',
        // ]);

        // $student = $studentRepository->find($id);

        $currentSessions = $sessionRepository->findCurrentSessions($student, null);
        $upcomingSessions = $sessionRepository->findUpcomingSessions($student, null);
        $pastSessions = $sessionRepository->findPastSessions($student, null);


        $html = $this->renderView('pdf_generator/index.html.twig', [
            'student' => $student,
            'currentSessions' => $currentSessions,
            'upcomingSessions' => $upcomingSessions,
            'pastSessions' => $pastSessions,
        ]);
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();
         
        return new Response (
            $dompdf->stream('resume', ["Attachment" => false]),
            Response::HTTP_OK,
            ['Content-Type' => 'application/pdf']
        );
    }
 
}
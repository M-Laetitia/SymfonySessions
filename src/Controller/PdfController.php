<?php

namespace App\Controller;

use Knp\Snappy\Pdf;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PdfController extends AbstractController
{
    
    /**
     * @Route("/pdf", name="pdf")
     */
    public function pdfAction(\Knp\Snappy\Pdf $knpSnappyPdf)
    {
 
 
        $knpSnappyPdf->generate('http://www.google.fr', '/path/to/the/file.pdf');
 
        $pageUrl = $this->generateUrl('homepage', array(), true); // use absolute path!
 
        return new PdfResponse(
            $knpSnappyPdf->getOutput($pageUrl),
            'file.pdf'
        );
    }

}



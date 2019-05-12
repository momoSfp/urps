<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Controller used to manage an view content.
 *
 *@Route("/tutor")
 *@IsGranted("ROLE_TUTOR")
 */

class TutorController extends AbstractController
{
    /**
     * @Route("/", name="tutor_index")
     */
    public function index()
    { 
        
        $tutor = $this->getUser()->getTutorRelation();

        return $this->render('tutor/index.html.twig', [
            'tutor' => $tutor,
        ]);
    }
}

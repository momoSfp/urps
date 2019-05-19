<?php

namespace App\Controller;

use App\Form\TutorType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
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

    /**
     * @Route("/edit", methods={"GET", "POST"}, name="tutor_edit")
     */
    public function edit(Request $request, ObjectManager $manager)
    { 
        $turor = $this->getUser()->getTutorRelation();

        $form = $this->createForm(TutorType::Class, $turor);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $manager->persist($turor);
            
            $manager->flush();

            $this->addFlash('success', 'La mise à jour a été effectuée avec succès');

            return $this->redirectToRoute('user_profile');
        }

        return $this->render('tutor/edit.html.twig', [
            'turor' => $turor,
            'form' => $form->createView(),
        ]);
    }


}

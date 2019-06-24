<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Tutor;
use App\Utils\Mailer;
use App\Form\TutorType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Controller used to manage and view user.
 *
 *@Route("/admin/tutors")
 *@IsGranted("ROLE_ADMIN")
 */
class AdminTutorController extends AbstractController
{
    /**
     * @Route("/", name="admin_tutors_index")
     */
    public function index(UserRepository $repo)
    {
        return $this->render('admin/tutor/index.html.twig', [
            'users' => $repo->findAllTutor()
        ]);
    }
  
    /**
     * @Route("/{id}/view", name="admin_tutors_view")
    */
    public function view(User $user)
    { 
        
        $tutor = $user->getTutorRelation();

        return $this->render('admin/tutor/view.html.twig', [
            'tutor' => $tutor,
        ]);
    }

    /**
     * create Tutor
     *
     * @Route("/new", name="admin_tutors_create")
     * 
     * @return Response
     */
    public function create(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder, Mailer $mailer)
    {        
        $tutor = new Tutor();

        $form = $this->createForm(TutorType::Class, $tutor);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {    
            // generate random password
            $password = $this->randomPassword();
            
            //set temporary password
            $tutor->setPlainTextPass($password);
            
            //encode password
            $passwordEncode = $encoder->encodePassword($tutor->getUserRelation(), $password);
            
            // set password
            $tutor->getUserRelation()->setPassword($passwordEncode);
            
            //set role
            $tutor->getUserRelation()->setRoles(["ROLE_TUTOR"]);

            $manager->persist($tutor);
            $manager->flush();

            $url = $this->generateUrl('home_index', [],UrlGeneratorInterface::ABSOLUTE_URL);


            $this->addFlash(
                'success',
                "Le tuteur <strong>{$tutor->getUserRelation()->getFullname()}</strong> a bien été enregistré !"            
            );
            
            $mailer->sendMail(
                $mailer->getMailSubjectWelcome(), 
                $mailer->getMailTutorBodyWelcome($tutor, $url),
                $tutor->getUserRelation()->getEmail()
            );
            
            return $this->redirectToRoute('admin_tutors_index');
        }

        return $this->render('admin/tutor/new.html.twig', [
            'form' => $form->createView()
        ]); 
    }

    /**
     * Edit Tutor
     *
     * @Route("/{id}/edit", name="admin_tutors_edit")
     * 
     * @return Response
     */
    public function edit(Tutor $tutor, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(TutorType::Class, $tutor);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {           
            $manager->persist($tutor);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications sur le tuteur <strong>{$tutor->getUserRelation()->getTutorFullname()}</strong> ont bien été enregistrées !"            
            );
            
            return $this->redirectToRoute('admin_tutors_index');
        }

        return $this->render('admin/tutor/edit.html.twig', [
            'form' => $form->createView(),
            'turor' => $tutor,
        ]);
    }

    /**
     * delete tutor
     *
     * @Route("/{id}/delete", name="admin_tutors_delete")
     * 
     * @return Response
     */
    public function delete(Tutor $tutor, ObjectManager $manager, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $tutor->getId(), $request->get('_token')))
        {

            $manager->remove($tutor);
            $manager->flush();
    
            $this->addFlash(
                'success',
                "Le tuteur <strong>{$tutor->getUserRelation()->getTutorFullname()}</strong> a bien été supprimé !"            
            );
        }

        return $this->redirectToRoute('admin_tutors_index');        
    }


    //////////////// function //////////

    function randomPassword() 
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
}

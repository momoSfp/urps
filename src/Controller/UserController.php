<?php

namespace App\Controller;

use App\Form\UserType;
use App\Form\AdminType;
use App\Form\ChangePasswordType;
use App\Entity\ParticipateContent;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Controller used to manage current user.
 *
 *@Route("/profile")
 *@IsGranted("ROLE_USER")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_profile")
     */
    public function profile()
    {
        $user = $this->getUser();

        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/edit", methods={"GET", "POST"}, name="user_edit")
     * @return Response
     */
    public function edit(Request $request, ObjectManager $manager)
    {
        $user = $this->getUser();

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            $form = $this->createForm(AdminType::class, $user);
        }
        else
        {
            $form = $this->createForm(UserType::class, $user);
        }
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $manager->persist($user);
            
            $manager->flush();

            $this->addFlash('success', 'La mise à jour a été effectuée avec succès');

            return $this->redirectToRoute('user_profile');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/update-password", methods={"GET", "POST"}, name="update_password")
     * @return Response
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder, ObjectManager $manager)
    {
        $user = $this->getUser();

        $form = $this->createForm(ChangePasswordType::class);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword($encoder->encodePassword($user, $form->get('newPassword')->getData()));

            $manager->persist($user);

            $manager->flush();

            $this->addFlash('success', 'Mot de passe mis à jour');
            
            return $this->redirectToRoute('user_profile');
        }

        return $this->render('user/update_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

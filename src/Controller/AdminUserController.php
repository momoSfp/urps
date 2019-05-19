<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Controller used to manage and view user.
 *
 *@Route("/admin/users")
 *@Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_TUTOR')")
 */
class AdminUserController extends AbstractController
{
    /**
     * @Route("/", name="admin_users_index")
     */
    public function index(UserRepository $repo)
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $repo->findAllPatient()
        ]);
    }

    /**
     * Edit user
     *
     * @Route("/{id}/edit", name="admin_users_edit")
     * 
     * @return Response
     */
    public function edit(User $user, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(UserType::Class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {           
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications sur l'utilisateur <strong>{$user->getfullname()}</strong> ont bien été enregistrées !"            
            );
            
            return $this->redirectToRoute('admin_users_index');
        }

        return $this->render('admin/user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * view user stats
     *
     * @Route("/{id}/view", name="admin_users_view")
     * 
     * @return Response
     */
    public function view(User $user)
    {
        return $this->render('admin/user/view.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * delete user
     *
     * @Route("/{id}/delete", name="admin_users_delete")
     * 
     * @return Response
     */
    public function delete(User $user, ObjectManager $manager, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->get('_token')))
        {
            $manager->remove($user);
            $manager->flush();
    
            $this->addFlash(
                'success',
                "L'utilisateur <strong>{$user->getfullname()}</strong> a bien été supprimé !"            
            );
        }

        return $this->redirectToRoute('admin_users_index');        
    }     
}

<?php

namespace App\Controller;

use App\Utils\Mailer;
use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use App\Repository\ContentRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $helper)
    {
        return $this->render('security/login.html.twig', [

            'last_username' => $helper->getLastUsername(),

            'error' => $helper->getLastAuthenticationError(),
        ]);
    }

    /**
     * This is the route the user can use to logout.
     * 
     * @Route("/logout", name="security_logout")
    */
    public function logout()
    {
        throw new \Exception('This should never be reached!');
    }

    /**
     * This is the route the user can registre.
     * 
     * @Route("/register", name="security_register")
    */
    public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder, ContentRepository $contentRepo, Mailer $mailer)
    {
        $user = new User();

        $contents = $contentRepo->findAllActive();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {    
            $countContents = count($contents);

            for ($i = 0; $i < $countContents; $i++) 
            {
                if ($request->request->get('question-'. strval($contents[$i]->getId())) === "true")
                {
                    $user->addRecommendedContent($contents[$i]);
                }                
            }

            $password = $encoder->encodePassword($user, $user->getPassword());

            $user->setPassword($password);

            $manager->persist($user);

            $manager->flush();
            
            $url = $this->generateUrl('home_index', [],UrlGeneratorInterface::ABSOLUTE_URL);
            

            $this->addFlash(
                'success',
                "votre compte a bien été crée !"
            );

            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->container->get('security.token_storage')->setToken($token);
            $this->container->get('session')->set('_security_main', serialize($token));

            $mailer->sendMail(
                $mailer->getMailSubjectWelcome(), 
                $mailer->getMailBodyWelcome($user, $url),
                $user->getEmail()
            );

            return $this->redirectToRoute('home_index');
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView(),
            'contents' => $contents
        ]);
    }

    /**
     * @Route("/forgottenPassword", name="security_forgotten_password")
     */
    public function forgottenPassword(Request $request, UserRepository $repoUser, ObjectManager $manager, UserPasswordEncoderInterface $encoder, Mailer $mailer, TokenGeneratorInterface $tokenGenerator)
    {
        if ($request->isMethod('POST')) {

            $email = $request->request->get('email');

            $user = $repoUser->findOneByEmail($email);

            // user not found

            if ($user === null) 
            {
                $this->addFlash('danger', 'Cette email est inconnu');
                return $this->redirectToRoute('security_forgotten_password');
            }

            // generate token

            $token = $tokenGenerator->generateToken();

            // Set token for this user
            try
            {
                $user->setResetToken($token);
                $manager->persist($user);
                $manager->flush();
            } 
            catch (\Exception $e) 
            {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('security_forgotten_password');
            }

            $url = $this->generateUrl('security_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);
            
            $mailer->sendMail(
                $mailer->getMailSubjectResetPassword(), 
                $mailer->getMailBodyResetPassword($url),
                $email
            );

            $this->addFlash('success', 'Vous allez recevoir dans quelques minute un email avec un lien pour réinitialisé pour votre de passe');

            return $this->redirectToRoute('security_login');
        }

        return $this->render('security/forgotten_password.html.twig');
    }


    /**
    * @Route("/reset_password/{token}", name="security_reset_password")
    */
   public function resetPassword(Request $request, UserRepository $userRepo, ObjectManager $manager, string $token, UserPasswordEncoderInterface $encoder)
   {

       if ($request->isMethod('POST')) 
       {

           $user = $userRepo->findOneByResetToken($token);

           if ($user === null) 
           {
               $this->addFlash('danger', 'Lien ne fonctionne pas');
               return $this->redirectToRoute('security_login');
            }

           $user->setResetToken(null);
           $user->setPassword($encoder->encodePassword($user, $request->request->get('password')));
           $manager->persist($user);
           $manager->flush();

           $this->addFlash('success', 'Mot de passe mis à jour');

           return $this->redirectToRoute('security_login');
       }
       else 
       {
           return $this->render('security/reset_password.html.twig', ['token' => $token]);
       }
   }
}

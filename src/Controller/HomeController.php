<?php

namespace App\Controller;

use App\Repository\ContentRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home_index")
     *@IsGranted("ROLE_USER")
     */
    public function index(ContentRepository $contentRepo, ObjectManager $manager)
    {

        $user = $this->getUser();
        $contents = $contentRepo->findAllActive();

        // update last connection
        $user->setUpdatedAt(new \DateTime());
        $manager->persist($user);
        $manager->flush();
        //

        return $this->render('home/index.html.twig', [
            'user' => $user,
            'contents' => $contents
        ]);
    }
}

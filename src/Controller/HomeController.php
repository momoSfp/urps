<?php

namespace App\Controller;

use App\Repository\ContentRepository;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home_index")
     *@IsGranted("ROLE_USER")
     */
    public function index(ContentRepository $contentRepo)
    {
        $user = $this->getUser();
        $contents = $contentRepo->findAllActive();

        return $this->render('home/index.html.twig', [
            'user' => $user,
            'contents' => $contents
        ]);
    }
}

<?php

namespace App\Controller;

use App\Repository\ContentRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Controller used to manage current user.
 *
 *@Route("/public")
 */
class PublicController extends AbstractController
{
    /**
     * Show public serious game
     * 
     * @Route("/", name="public_index")
    */
    public function index(ContentRepository $repo)
    {
        $contents = $repo->findAllActiveAndPublic();

        return $this->render('public/index.html.twig', 
        [
            'contents' => $contents,
        ]);
    } 
}

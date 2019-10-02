<?php

namespace App\Controller;

use App\Entity\Content;
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

    /**
     * Play game
     * 
     * @Route("/{slug}/play", name="public_play")
     * 
     * @return Response
     */
    public function play(Content $content)
    {
        $a = 0;
        $b = $_POST["cid"]; if (!$b) $b = 0;
        $c = $_POST["www"]; if (!$c) $c = "~";
        $d = $_POST["api"]; if (!$d) $d = "~";
        $SFP_DATA = implode("__", array($a, $b, $c, $d));

        return $this->render('content/play.html.twig', 
        [
            'content' => $content,
            'SFP_DATA' => $SFP_DATA
        ]);
    }    
}

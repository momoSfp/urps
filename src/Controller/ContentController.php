<?php

namespace App\Controller;

use App\Entity\Content;
use App\Form\ContentType;
use App\Utils\ArchiveZip;
use App\Entity\ParticipateContent;
use App\Repository\ContentRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Controller used to manage an view content.
 *
 *@Route("/contents")
 *@IsGranted("ROLE_USER")
 */
class ContentController extends AbstractController
{
    /**
     * @Route("/", name="contents_index")
     */
    public function index(ContentRepository $repo)
    {
        $contents = $repo->findAllActive();

        return $this->render('content/index.html.twig', 
        [
            'contents' => $contents,
        ]);
    }
    
    /**
     * Play game
     * 
     * @Route("/{slug}/play", name="contents_play")
     * 
     * @return Response
     */
    public function play(Content $content)
    {
        $a = $_POST["uid"]; if (!$a) $a = 0;
        $b = $_POST["cid"]; if (!$b) $b = 0;
        $c = $_POST["www"]; if (!$c) $c = "~";
        $d = $_POST["api"]; if (!$d) $d = "~";
        $e = 0;
        $SFP_DATA = implode("__", array($a, $b, $c, $d, $e));

        return $this->render('content/play.html.twig', 
        [
            'content' => $content,
            'SFP_DATA' => $SFP_DATA
        ]);
    }

    /**
     * Show one content
     * 
     * @Route("/{slug}", name="contents_show")
     * 
     * @return Response
     */
    public function show(Content $content, ParticipateContent $participateContent = null)
    {
        if (empty($ParticipateContent)) $ParticipateContent = null;

        return $this->render('content/show.html.twig', 
        [
            'content' => $content,
            'participateContent' => $participateContent
        ]);
    }

}

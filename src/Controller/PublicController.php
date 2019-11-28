<?php

namespace App\Controller;

use App\Entity\Content;
use App\Entity\PublicContentVisited;
use App\Repository\ContentRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PublicContentVisitedRepository;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

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
    public function index(ContentRepository $repo, PublicContentVisitedRepository $publicContentVisitedRepository, ObjectManager $manager)
    {
        $contents = $repo->findAllActiveAndPublic();

        //$lifetime = new NativeSessionStorage();
        //$lifetime->setOptions(array('cookie_lifetime' => 60));
        
        $publicContentVisited = $publicContentVisitedRepository->findByTodayDate();

        if (!isset($session))
        {
            $session = new Session();
            
            if (!$session->get('record'))
            {
                $session->set('record', 1);
                if (is_null($publicContentVisited))
                {
                    $publicContentVisited = new PublicContentVisited();
                    $publicContentVisited->setCountHomePage(1);
                }
                else
                    $publicContentVisited->setCountHomePage($publicContentVisited->getCountHomePage() + 1);
                    
                $manager->persist($publicContentVisited);
                $manager->flush();                    
            }
        }

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
    public function play(Content $content, PublicContentVisitedRepository $publicContentVisitedRepository, ObjectManager $manager)
    {
        $a = 0;

        if (!isset($_POST["cid"]))
        {
            $b = 0; $c = "~"; $d = "~";
        }
        else
        {
            $b = $_POST["cid"]; if (!$b) $b = 0; $c = $_POST["www"]; if (!$c) $c = "~"; $d = $_POST["api"]; if (!$d) $d = "~";
        }

        $SFP_DATA = implode("__", array($a, $b, $c, $d));
    
        $publicContentVisited = $publicContentVisitedRepository->findByTodayDate();
    
        if (is_null($publicContentVisited))
        {   
            $publicContentVisited = new PublicContentVisited();
            $publicContentVisited->setCountContentPage(1);
        }
        else
            $publicContentVisited->setCountContentPage($publicContentVisited->getCountContentPage() + 1);
        
        $manager->persist($publicContentVisited);
        $manager->flush();

        return $this->render('content/play.html.twig', 
        [
            'content' => $content,
            'SFP_DATA' => $SFP_DATA
        ]);
    }    
}

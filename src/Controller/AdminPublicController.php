<?php

namespace App\Controller;

use App\Service\StatsService;
use App\Repository\UserRepository;
use App\Repository\ContentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Controller used to manage an view content.
 *
 *@Route("/admin/public")
 *@IsGranted("ROLE_ADMIN")
 */

class AdminPublicController extends AbstractController
{
    /**
     * @Route("/", name="admin_public_index")
     */
    public function index(UserRepository $userRepo, ContentRepository $contentRepo, Request $request, StatsService $statsService)
    {
        $statsPublicPagesVisited = $statsService->generateStatsPublicPagesVisited();
        $statsPublicPagesGlobal = $statsPublicPagesVisited[0];
        $statsPublicPagesDetails = $statsPublicPagesVisited[1];

        //var_dump($statsPublicPagesVisited[1]);

        $year  = $request->query->get('year');
        $month = $request->query->get('month');

        if ($year == null)
        {
            $year = new \DateTime();
            $year = $year->format("Y");
        }

        if ($month == null)
        {
            $month = new \DateTime();
            $month = intval($month->format("m")) - 1;
        }
        
        return $this->render('admin/public/index.html.twig', [
            'year'                     => $year,
            'month'                    => $month,
            'statsPublicPagesGlobal'   => $statsPublicPagesGlobal,
            'statsPublicPagesDetails'  => $statsPublicPagesDetails,
        ]);
    }
}

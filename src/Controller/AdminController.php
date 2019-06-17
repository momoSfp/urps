<?php

namespace App\Controller;

use App\Service\StatsService;
use App\Repository\UserRepository;
use App\Repository\ContentRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Controller used to manage an view content.
 *
 *@Route("/admin")
 *@IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin_index")
     */
    public function index(UserRepository $userRepo, ContentRepository $contentRepo, Request $request, StatsService $statsService)
    {
        $nbTutors   = $statsService->getTutorsCount();
        $nbUsers    = $statsService->getUsersCount();
        $nbContents = $statsService->getContentsCount();

        $nbUsersActiveLastMonth  = $statsService->getNbActiveUsersLastPeriod(1);
        $nbTutorsActiveLastMonth = $statsService->getNbActiveTutorsLastPeriod(4);
        $nbContentsActive        = $statsService->getActiveContentsCount();

        $statsConnection         = $statsService->generateStatsUsersConnectionByYear();
        $statsPercentAge         = $statsService->gePercentAge($nbUsers);

        $year = $request->query->get('year');

        if ($year == null)
        {
            $year = new \DateTime();
            $year = $year->format("Y");
        }

        return $this->render('admin/index.html.twig', [
            'nbTutors'         => $nbTutors,
            'nbUsers'          => $nbUsers,
            'nbUsersActive'    => $nbUsersActiveLastMonth,
            'nbTutorsActive'   => $nbTutorsActiveLastMonth,
            'nbContents'       => $nbContents,
            'nbContentsActive' => $nbContentsActive,
            'statsConnection'  => $statsConnection,
            'year'             => $year,
            'statsPercentAge'  => $statsPercentAge
        ]);
    }
}

<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\ContentRepository;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(UserRepository $userRepo, ContentRepository $contentRepo, Request $request)
    {
        $tutors  = $userRepo->findAllTutor();
        $users   = $userRepo->findAllPatient();
        $contents = $contentRepo->findAll();

        $nbTutors   = count($tutors);
        $nbUsers    = count($users);
        $nbContents = count($contents);

        $nbUsersActiveLastMonth  = $this->getNbActiveUsersLastPeriod($users, 1);
        $nbTutorsActiveLastMonth = $this->getNbActiveUsersLastPeriod($tutors, 4);
        $nbContentsActive        = $contentRepo->getCountActive();

        $stats                   = $this->generateStatsUsersConnectionByYear($users);

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
            'stats'            => $stats,
            'year'             => $year

        ]);
    }

    private function getNbActiveUsersLastPeriod($users, $period)
    {
        $cp = 0;
        $Datenow = new \DateTime();
        $Datenow = $Datenow->modify( '-' . $period . ' month' ); 

        foreach( $users as $user )
        {
            $diff = date_diff($Datenow, $user->getUpdatedAt());
            if (intval($diff->format('%R%m')) > 0)
                $cp++;
        }

        return $cp;
    }

    private function generateStatsUsersConnectionByYear($users)
    {
        $stats = [];

        foreach( $users as $user )
        {
            $year  = $user->getCreatedAt()->format("Y");
            $month = $user->getCreatedAt()->format("n");

            if ( !isset($stats[$year]))
            {
                $stats[$year] = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            }
            
            $stats[$year][$month -1] ++;

        }
        
        return $stats;

    }
}

<?php

namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;

class StatsService {

    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function getUsersCount()
    {
        return $this->manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u WHERE u.roles Not LIKE :admin and u.roles Not LIKE :tutor')
                            ->setParameter('admin', '%"ROLE_ADMIN"%')
                            ->setParameter('tutor', '%"ROLE_TUTOR"%')            
                            ->getSingleScalarResult();
    }

    public function getTutorsCount()
    {
        return $this->manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u WHERE u.roles LIKE :tutor')
                            ->setParameter('tutor', '%"ROLE_TUTOR"%')            
                            ->getSingleScalarResult();
    }

    public function getContentsCount()
    {
        return $this->manager->createQuery('SELECT COUNT(c) FROM App\Entity\Content c')
                            ->getSingleScalarResult();
    }

    public function getActiveContentsCount()
    {
        return $this->manager->createQuery('SELECT COUNT(c) FROM App\Entity\Content c WHERE c.active = true')
                            ->getSingleScalarResult();
    }

    public function getNbActiveUsersLastPeriod($period)
    {
        return $this->manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u WHERE u.roles Not LIKE :admin AND u.roles Not LIKE :tutor AND u.updatedAt > :date')
                            ->setParameter('admin', '%"ROLE_ADMIN"%')
                            ->setParameter('tutor', '%"ROLE_TUTOR"%')            
                            ->setParameter('date', new \DateTime('-' . $period . ' month'))
                            ->getSingleScalarResult();
    }

    public function getNbActiveTutorsLastPeriod($period)
    {
        return $this->manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u WHERE u.roles LIKE :tutor AND u.updatedAt > :date')
                            ->setParameter('tutor', '%"ROLE_TUTOR"%')            
                            ->setParameter('date', new \DateTime('-' . $period . ' month'))
                            ->getSingleScalarResult();
    }

    public function generateStatsUsersConnectionByYear()
    {
        $stats = [];
        $distinctDate = $this->manager->createQuery('SELECT (DATE_FORMAT(u.createdAt, :mY)) FROM App\Entity\User u WHERE u.roles Not LIKE :admin AND u.roles Not LIKE :tutor')
                            ->setParameter('admin', '%"ROLE_ADMIN"%')
                            ->setParameter('tutor', '%"ROLE_TUTOR"%')                                   
                            ->setParameter('mY', "%m - %Y")
                            ->getResult();


        foreach( $distinctDate as $date )
        {
            $year  = explode(" - ", $date[1])[1];
            $month = explode(" - ", $date[1])[0];

            if ( !isset($stats[$year]))
            {
                $stats[$year] = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            }
            
            $stats[$year][$month -1] ++;

        }
        
        return $stats;

    }    
}

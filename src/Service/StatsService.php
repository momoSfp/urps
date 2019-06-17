<?php

namespace App\Service;

use App\Entity\ParticipateContent;
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

    function gePercentAge($nbUsers)
    {
        $stats = [];

        $countAge = $this->manager->createQuery('SELECT COUNT(u.id), u.age FROM App\Entity\User u WHERE u.roles Not LIKE :admin AND u.roles Not LIKE :tutor GROUP BY u.age ORDER BY u.age')
                            ->setParameter('admin', '%"ROLE_ADMIN"%')
                            ->setParameter('tutor', '%"ROLE_TUTOR"%')                                   
                            ->getResult();
        
        foreach ($countAge as $value) 
        {
            if ($value["age"] == null) $value["age"] = "Non renseigné";
            $stats["label"][] = $value["age"];
            $stats["value"][] = round(($value[1] / $nbUsers) * 100, 2);
        }

        return $stats;
    }

    function gePercentAgeByContent($content)
    {
        $stats = [];
        $nbUsers = 0;
        $countAge = $this->manager->createQuery('SELECT COUNT(u.id), u.age
                                                    FROM App\Entity\User u
                                                    JOIN App\Entity\participateContent pc 
                                                    WHERE pc.content = :content AND pc.user = u.id
                                                    GROUP BY u.age ORDER BY u.age'
                                                )
                            ->setParameter('content', $content)
                            ->getResult();

        foreach ($countAge as $value) 
        {
            $nbUsers += $value[1];
        }
        
        foreach ($countAge as $value) 
        {
            if ($value["age"] == null) $value["age"] = "Non renseigné";
            $stats["label"][] = $value["age"];
            $stats["value"][] = round(($value[1] / $nbUsers) * 100, 2);
        }

        return $stats;
    }    
    
    function getAvgDurationParticiapteContentByContent($content)
    {

        $avgDuration = $this->manager->createQuery('SELECT AVG(pc.duration)
                                                    FROM App\Entity\participateContent pc 
                                                    WHERE pc.content = :content and pc.duration IS NOT NULL'
                                                )
                                    ->setParameter('content', $content)
                                    ->getSingleScalarResult();

        $hours = gmdate("H", $avgDuration);
        $minutes = gmdate("i", $avgDuration);
        $seconds = gmdate("s", $avgDuration);

        //return gmdate("H:i:s", $avgDuration);
        return $hours . " heures, " . $minutes . " minutes et " . $seconds . " secondes";
    }

    function getAvgDiffBetweenStartEndDatePcByContent($content)
    {

        $dates = $this->manager->createQuery('SELECT pc.completedAt, pc.createdAt
                                                    FROM App\Entity\participateContent pc 
                                                    WHERE pc.content = :content AND pc.completedAt IS NOT NULL'
                                                )
                            ->setParameter('content', $content)
                            ->getResult();

        $sumDiff = 0;

        foreach ($dates as $date)
        {
            $diff = $date["completedAt"]->getTimestamp() - $date["createdAt"]->getTimestamp();
            $sumDiff += $diff;
        }

        if (count($dates) > 0)
        {
            return $this->convertSecondsToDate(round($sumDiff/ (count($dates))));
        }
        else
            return '0 jours, 0 heures, 0 minutes et 0 secondes';
    }

    private function convertSecondsToDate($seconds) 
    {
        $dt1 = new \DateTime("@0");
        $dt2 = new \DateTime("@$seconds");
        return $dt1->diff($dt2)->format('%a jours, %h heures, %i minutes et %s secondes');
    }
}

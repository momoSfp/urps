<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Content;
use App\Entity\ParticipateContent;
use App\Repository\UserRepository;
use App\Repository\ContentRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\ParticipateContentRepository;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Api controller
 * @Route("/api", name="api_")
 */
class ApiController extends FOSRestController
{
  /**
   * @Rest\Get("/participate/", name="get")
   *
   * @return Response
   */
  public function getParticipateData(ParticipateContentRepository $participateContentRepo, UserRepository $userRepo,
   ContentRepository $contentRepo, ObjectManager $manager, Request $request)
  {

    $userId    = $request->query->get('uid');
    $contentId = $request->query->get('cid');
    $user = $userRepo->find($userId);

    $participateContent = $participateContentRepo->findParticipateContentByUserIdAndContentId($userId, $contentId);
    
    if (empty($participateContent))
    {
      $participateContent = new ParticipateContent();

      $content = $contentRepo->find($contentId);

      if (!is_null($content))
      {
        $participateContent->setUser($user)         
                            ->setContent($content);

        $manager->persist($participateContent);
        $manager->flush();
      }
      else
      {
        //todo
        return $this->handleView($this->view([]));
      }
      
    }

    $result = ["firstname" => $user->getFirstname(), "lastname" => $user->getLastname(), "data" => $participateContent->getResult()];

    return $this->handleView($this->view($result));
  }

  /**
   * @Rest\Post("/participate/")
   *
   * @return Response
   */
  public function postParticipateData(Request $request, ParticipateContentRepository $participateContentRepo, ObjectManager $manager)
  {
    
    $data = json_decode($request->getContent(), true);

    $participateContent = $participateContentRepo->findParticipateContentByUserIdAndContentId($data["uid"], $data["cid"]);

    if (!is_null($participateContent))
    {
      if ($data["completed"])
      {
        $participateContent->setCompletedAt(new \DateTime());
      }

      $participateContent->setDuration($participateContent->getDuration() + $data["duration"]);
      $participateContent->setResult($data["data"]);

      $manager->persist($participateContent);
      $manager->flush();

      return $this->handleView($this->view(['success' => 'true'], Response::HTTP_CREATED));
    }
    else
    {
      return $this->handleView($this->view(['success' => 'false']));
    }
  }
}
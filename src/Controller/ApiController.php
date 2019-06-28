<?php

namespace App\Controller;

use App\Entity\User;
use App\Utils\Mailer;
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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
  public function postParticipateData(UserRepository $userRepo, ContentRepository $contentRepo, Request $request, ParticipateContentRepository $participateContentRepo, ObjectManager $manager, Mailer $mailer)
  {
    
    $data = json_decode($request->getContent(), true);

    $participateContent = $participateContentRepo->findParticipateContentByUserIdAndContentId($data["uid"], $data["cid"]);

    if (!is_null($participateContent))
    {
      if ($data["completed"])
      {
        $participateContent->setCompletedAt(new \DateTime());

        $user = $userRepo->find($data["uid"]);
        $content = $contentRepo->find($data["cid"]);
        $url = "http://127.0.0.1:8000/api/rating/";

        $mailer->sendMail(
            $mailer->getMailSubjectEndGame(), 
            $mailer->getMailBodyEndGame($user, $content, $url),
            $user->getEmail()
        );

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

  /**
   * @Rest\Get("/rating/", name="rating_update")
   *
   * @return Response
   */
  public function updateRating(ParticipateContentRepository $participateContentRepo, UserRepository $userRepo,
   ContentRepository $contentRepo, ObjectManager $manager, Request $request)
  {
    
    $userId    = ($request->query->get('u') / 75);
    $contentId = ($request->query->get('c') / 59);
    $rating = ($request->query->get('r') / 13);

    $user = $userRepo->find($userId);

    $participateContent = $participateContentRepo->findParticipateContentByUserIdAndContentId($userId, $contentId);
    
    if (empty($participateContent))
    {
        $this->addFlash(
          'danger',
          "Une erreur est survenue pendant le processus de notation."
        );

        return $this->redirectToRoute('home_index');
    }
    else
    {
      if ($rating > 5)
      {

        $this->addFlash(
          'danger',
          "Une erreur est survenue pendant le processus de notation."
        );

        return $this->redirectToRoute('home_index');
      }
      else
      {
        $participateContent->setRating($rating);
        $manager->persist($participateContent);
        $manager->flush();

        $this->addFlash(
          'success',
          "Merci d'avoir noté le jeu sérieux"
        );
        return $this->redirectToRoute('home_index');

      }
    }
  }

}
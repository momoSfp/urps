<?php

namespace App\Controller;

use App\Entity\Content;
use App\Entity\ParticipateContent;
use App\Form\ContentType;
use App\Utils\ArchiveZip;
use App\Repository\ContentRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ParticipateContentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Controller used to manage an view content.
 *
 *@Route("/admin/contents")
 *@IsGranted("ROLE_ADMIN")
 */
class AdminContentController extends AbstractController
{
    /**
     * @Route("/", name="admin_contents_index")
     */
    public function index(ContentRepository $repo)
    {
        return $this->render('admin/content/index.html.twig', [
            'contents' => $repo->findAll()
        ]);
    }

    /**
     * create content
     *
     * @Route("/new", name="admin_contents_create")
     * 
     * @return Response
     */
    public function create(Request $request, ObjectManager $manager, ArchiveZip $archive)
    {        
        $content = new Content();

        $form = $this->createForm(ContentType::Class, $content);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {    
            
            if ($content->getGameFile() !== null)
            {
                var_dump($archive->unzipFile($content->getGameFile()->getClientOriginalName()));
                if ($archive->unzipFile($content->getGameFile()->getClientOriginalName()))
                {
                    // TODO GET LINK 
                    $content->setLink('http://localhost/urps/public/games/extract/gluciboat/index.html');
                }
            }

            foreach ($content->getImages() as $image) {
                $image->setContent($content);
                $manager->persist($image);
            }
            
            $manager->persist($content);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le serious game <strong>{$content->getTitle()}</strong> a bien été enregistré !"            
            );
            
            return $this->redirectToRoute('admin_contents_index');
        }

        return $this->render('admin/content/new.html.twig', [
            'form' => $form->createView()
        ]); 
    }

    /**
     * Edit content
     *
     * @Route("/{id}/edit", name="admin_contents_edit")
     * 
     * @return Response
     */
    public function edit(Content $content, Request $request, ObjectManager $manager, ArchiveZip $archive)
    {
        $form = $this->createForm(ContentType::Class, $content);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {           
            
            if ($content->getGameFile() !== null)
            {
                if ($archive->unzipFile($content->getGameFile()->getClientOriginalName()))
                {
                    // TODO GET LINK 
                    $content->setLink('http://localhost/urps/public/games/extract/gluciboat/index.html');
                }
            }

            foreach ($content->getImages() as $image) {
                $image->setContent($content);
                $manager->persist($image);
            }

            $manager->persist($content);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications du serious game <strong>{$content->getTitle()}</strong> ont bien été enregistrées !"            
            );
            
            return $this->redirectToRoute('admin_contents_index');
        }

        return $this->render('admin/content/edit.html.twig', [
            'form' => $form->createView(),
            'content' => $content
        ]);
    }

    /**
     * Edit content
     *
     * @Route("/{id}/stats", name="admin_contents_stats")
     * 
     * @return Response
     */
    public function stats(Content $content, ParticipateContentRepository $participateContentRepo)
    {
        $participateContents = $participateContentRepo->findBy(['content' => $content]);

        $nbParticipateContents = count($participateContents);

        $percentCompletedGame = $this->gePercentCompletedGame($participateContents, $nbParticipateContents);

        return $this->render('admin/content/stats.html.twig', [
            'content'               => $content,
            'nbParticipateContents' => $nbParticipateContents,
            'participateContents'   => $participateContents,
            'percentCompletedGame'  => $percentCompletedGame,
        ]);
    }

    /**
     * delete content
     *
     * @Route("/{id}/delete", name="admin_contents_delete")
     * 
     * @return Response
     */
    public function delete(Content $content, ObjectManager $manager, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $content->getId(), $request->get('_token')))
        {
            $manager->remove($content);
            $manager->flush();
    
            $this->addFlash(
                'success',
                "Le serious game <strong>{$content->getTitle()}</strong> a été supprimé !"            
            );
        }

        return $this->redirectToRoute('admin_contents_index');        
    }

    ////////////////////////////// FUNCTION //////////////////////////////

    function gePercentCompletedGame($participateContents, $nbParticipateContents)
    {
        $nbCompletedContent = 0;

        foreach ($participateContents as $participateContent)
        {
            if ($participateContent->getCompletedAt() != null)
            {
                $nbCompletedContent++;
            }
        }

        $percentCompleted = ($nbCompletedContent / $nbParticipateContents) * 100;
        $percentIncompleted = 100 - $percentCompleted;
        
        return [$percentIncompleted, $percentCompleted];
    }
}

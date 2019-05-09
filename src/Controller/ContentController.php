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
     * create content
     *
     * @Route("/new", name="contents_create")
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
            
            return $this->redirectToRoute('contents_show', [
                'slug' => $content->getSlug()
            ]);
        }

        return $this->render('content/new.html.twig', [
            'form' => $form->createView()
        ]); 
    }

    /**
     * Edit content
     *
     * @Route("/{slug}/edit", name="contents_edit")
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
                    var_dump("ici");
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
            
            return $this->redirectToRoute('contents_show', [
                'slug' => $content->getSlug()
            ]);
        }

        return $this->render('content/edit.html.twig', [
            'form' => $form->createView(),
            'content' => $content
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
        return $this->render('content/play.html.twig', 
        [
            'content' => $content,
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

    /**
     * Delete one content
     * 
     * @Route("/{slug}/delete", name="contents_delete")
     * @Security("is_granted('ROLE_ADMIN')")
     * @return Response
    */
    public function delete(Content $content, ObjectManager $manager)
    {
        $manager->remove($content);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le serious game <strong>{$content->getTitle()}</strong> ont bien été supprimé !"            
        );

        return $this->redirectToRoute('contents_index');
    }
}

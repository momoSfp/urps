<?php

namespace App\Controller;

use App\Entity\Content;
use App\Form\ContentType;
use App\Repository\ContentRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContentController extends AbstractController
{
    /**
     * @Route("/contents", name="contents_index")
     */
    public function index(ContentRepository $repo)
    {
        $contents = $repo->findAll();

        return $this->render('content/index.html.twig', 
        [
            'contents' => $contents,
        ]);
    }

    /**
     * create content
     *
     * @Route("/contents/new", name="contents_create")
     * 
     * @return Response
     */
    public function create(Request $request, ObjectManager $manager)
    {        
        $content = new Content();

        $form = $this->createForm(ContentType::Class, $content);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
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
     * @Route("/contents/{slug}/edit", name="contents_edit")
     * 
     * @return Response
     */
    public function edit(Content $content, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(ContentType::Class, $content);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
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
     * Show one content
     * 
     * @Route("/contents/{slug}", name="contents_show")
     * 
     * @return Response
     */
    public function show(Content $content)
    {
        return $this->render('content/show.html.twig', 
        [
            'content' => $content,
        ]);

    }
}

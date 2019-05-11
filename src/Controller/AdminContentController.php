<?php

namespace App\Controller;

use App\Entity\Content;
use App\Form\ContentType;
use App\Utils\ArchiveZip;
use App\Repository\ContentRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
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
            
            return $this->redirectToRoute('admin_contents_index', [
                'slug' => $content->getSlug()
            ]);
        }

        return $this->render('admin/content/edit.html.twig', [
            'form' => $form->createView(),
            'content' => $content
        ]);
    }

    /**
     * Edit content
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
                "Le serious game <strong>{$content->getTitle()}</strong> ont bien été supprimé !"            
            );
        }

        return $this->redirectToRoute('admin_contents_index');        
    }     
}

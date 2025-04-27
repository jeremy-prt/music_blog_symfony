<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Commentaire;
use App\Form\ArticleType;
use App\Form\CommentaireType;
use App\Repository\ArticleRepository;
use App\Service\DeezerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Filesystem\Filesystem;
use Dompdf\Dompdf;
use App\Message\ExportPdf;
use Symfony\Component\Messenger\MessageBusInterface;

#[Route('/article')]
final class ArticleController extends AbstractController
{
    #[Route(name: 'app_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository, DeezerService $deezerService): Response
    {
        $articles = $articleRepository->findAll();
        $articlesWithImages = [];

        foreach ($articles as $article) {
            $artistInfo = $deezerService->getArtistInfo($article->getArtiste());
            $articlesWithImages[] = [
                'article' => $article,
                'image' => $artistInfo['image'] ?? null,
            ];
        }

        return $this->render('article/index.html.twig', [
            'articles' => $articlesWithImages,
        ]);
    }

    #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_access_denied');
        }

        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setDatePublication(new \DateTimeImmutable()); // Définit automatiquement la date
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('app_article_index');
        }

        return $this->render('article/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_article_show', methods: ['GET', 'POST'])]
    public function show(Request $request, Article $article, EntityManagerInterface $em, DeezerService $deezerService): Response
    {
        $commentaire = new Commentaire();
        $form = null;

        if ($this->getUser()) {
            $form = $this->createForm(CommentaireType::class, $commentaire);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $commentaire->setArticle($article);
                $commentaire->setAuteur($this->getUser());
                $commentaire->setCreatedAt(new \DateTimeImmutable());

                $em->persist($commentaire);
                $em->flush();

                return $this->redirectToRoute('app_article_show', ['id' => $article->getId()]);
            }
        }

        $deezerArtist = $deezerService->getArtistInfo($article->getArtiste());

        $pdfPath = '/exports/article_' . $article->getId() . '.pdf';
        $projectDir = $this->getParameter('kernel.project_dir');
        $fullPath = $projectDir . '/public' . $pdfPath;
        $pdfExists = file_exists($fullPath);

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'commentaireForm' => $form?->createView(),
            'deezerArtist' => $deezerArtist,
            'pdfPath' => $pdfExists ? $pdfPath : null,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, EntityManagerInterface $em): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_access_denied');
        }

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_article_index');
        }

        return $this->render('article/edit.html.twig', [
            'form' => $form,
            'article' => $article,
        ]);
    }

    #[Route('/{id}', name: 'app_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, EntityManagerInterface $em): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_access_denied');
        }

        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($article);
            $em->flush();
        }

        return $this->redirectToRoute('app_article_index');
    }
    
    #[Route('/{id}/export', name: 'app_article_export_pdf', methods: ['GET'])]
    public function export(Article $article, MessageBusInterface $bus): Response
    {
        $bus->dispatch(new ExportPdf($article->getId()));

        $this->addFlash('success', 'Export en cours. Le PDF sera disponible sous peu.');
        return $this->redirectToRoute('app_article_show', ['id' => $article->getId()]);
    }
}
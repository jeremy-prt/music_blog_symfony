<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class ApiArticleController extends AbstractController
{
    #[Route('/api/articles', name: 'api_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): JsonResponse
    {
        $articles = $articleRepository->findAll();
        $data = [];

        foreach ($articles as $article) {
            $data[] = [
                'id' => $article->getId(),
                'titre' => $article->getTitre(),
                'slug' => $article->getSlug(),
                'contenu' => $article->getContenu(),
                'artiste' => $article->getArtiste(),
                'datePublication' => $article->getDatePublication()?->format('Y-m-d H:i:s'),
            ];
        }

        return $this->json($data);
    }

    #[Route('/api/articles/{id}', name: 'api_article_show', methods: ['GET'])]
    public function show(int $id, ArticleRepository $articleRepository): JsonResponse
    {
        $article = $articleRepository->find($id);
        if (!$article) {
            return $this->json(['error' => 'Article non trouvé.'], 404);
        }

        $data = [
            'id' => $article->getId(),
            'titre' => $article->getTitre(),
            'slug' => $article->getSlug(),
            'contenu' => $article->getContenu(),
            'artiste' => $article->getArtiste(),
            'datePublication' => $article->getDatePublication()?->format('Y-m-d H:i:s'),
        ];

        return $this->json($data);
    }

    #[Route('/api/articles', name: 'api_article_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $article = new Article();
        $article->setTitre($data['titre'] ?? '');
        $article->setSlug($data['slug'] ?? '');
        $article->setContenu($data['contenu'] ?? '');
        $article->setArtiste($data['artiste'] ?? '');
        $article->setDatePublication(new \DateTimeImmutable($data['datePublication'] ?? 'now'));

        $em->persist($article);
        $em->flush();

        return $this->json(['message' => 'Article créé avec succès.'], 201);
    }

    #[Route('/api/articles/{id}', name: 'api_article_update', methods: ['PUT', 'PATCH'])]
    public function update(int $id, Request $request, ArticleRepository $articleRepository, EntityManagerInterface $em): JsonResponse
    {
        $article = $articleRepository->find($id);
        if (!$article) {
            return $this->json(['error' => 'Article non trouvé.'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $article->setTitre($data['titre'] ?? $article->getTitre());
        $article->setSlug($data['slug'] ?? $article->getSlug());
        $article->setContenu($data['contenu'] ?? $article->getContenu());
        $article->setArtiste($data['artiste'] ?? $article->getArtiste());
        if (isset($data['datePublication'])) {
            $article->setDatePublication(new \DateTimeImmutable($data['datePublication']));
        }

        $em->flush();

        return $this->json(['message' => 'Article mis à jour.']);
    }

    #[Route('/api/articles/{id}', name: 'api_article_delete', methods: ['DELETE'])]
    public function delete(int $id, ArticleRepository $articleRepository, EntityManagerInterface $em): JsonResponse
    {
        $article = $articleRepository->find($id);
        if (!$article) {
            return $this->json(['error' => 'Article non trouvé.'], 404);
        }

        $em->remove($article);
        $em->flush();

        return $this->json(['message' => 'Article supprimé.']);
    }
}
<?php

namespace App\MessageHandler;

use App\Message\ExportPdf;
use App\Repository\ArticleRepository;
use Dompdf\Dompdf;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Twig\Environment;
use Symfony\Component\Filesystem\Filesystem;

#[AsMessageHandler]
final class ExportPdfMessageHandler
{
    public function __construct(
        private ArticleRepository $articleRepository,
        private Environment $twig
    ) {}

    public function __invoke(ExportPdf $message)
    {
        $article = $this->articleRepository->find($message->getArticleId());
        if (!$article) return;

        $html = $this->twig->render('pdf/article.html.twig', [
            'article' => $article,
        ]);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();

        $output = $dompdf->output();
        $filesystem = new Filesystem();
        $filesystem->dumpFile('public/exports/article_'.$article->getId().'.pdf', $output);
    }
}
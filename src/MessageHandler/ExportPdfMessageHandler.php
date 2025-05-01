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

        $logoPath = __DIR__ . '/../../public/images/logo.png';
        $logoBase64 = null;

        if (file_exists($logoPath)) {
            $logoData = base64_encode(file_get_contents($logoPath));
            $logoMime = mime_content_type($logoPath);
            $logoBase64 = 'data:' . $logoMime . ';base64,' . $logoData;
        }

        $html = $this->twig->render('pdf/article.html.twig', [
            'article' => $article,
            'logoBase64' => $logoBase64,
        ]);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();

        $output = $dompdf->output();
        $filesystem = new Filesystem();
        $filesystem->dumpFile('public/exports/article_'.$article->getId().'.pdf', $output);
    }
}
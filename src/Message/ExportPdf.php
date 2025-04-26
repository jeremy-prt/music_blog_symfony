<?php

namespace App\Message;

final class ExportPdf
{
    public function __construct(
        private int $articleId
    ) {}

    public function getArticleId(): int
    {
        return $this->articleId;
    }
}
<?php

declare(strict_types=1);

namespace App\DTO;

/**
 * Data transfer object (DTO) representing an article.
 */
class ArticleDto
{
    /**
     * @var string The title of the article.
     */
    public string $title;

    /**
     * @var string The main text content of the article.
     */
    public string $text;

    /**
     * @var string The URL where the article is located.
     */
    public string $url;

    /**
     * @var float The size of the article in kilobytes (KB).
     */
    public float $sizeKb;

    /**
     * @var int The number of words in the article.
     */
    public int $wordCount;
}

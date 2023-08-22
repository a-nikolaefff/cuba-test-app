<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\ArticleDto;
use Illuminate\Support\Facades\Http;

/**
 * Service class for interacting with the Wikipedia API to fetch article data.
 */
class WikipediaApiService
{
    /**
     * Fetch an article data transfer object (DTO) from Wikipedia API.
     *
     * @param string $title The title of the article to fetch.
     *
     * @return ArticleDto|null The data transfer object representing the fetched article.
     * Null if the article is not found.
     */
    public function fetchArticleDto(string $title): ?ArticleDto
    {
        $wikipediaData = Http::get('https://ru.wikipedia.org/w/api.php', [
            'action' => 'query',
            'format' => 'json',
            'titles' => $title,
            'prop' => 'info|extracts',
            'inprop' => 'url',
            'explaintext' => true,
            'redirects' => true,
        ])->json();

        $pageData = reset($wikipediaData['query']['pages']);

        if (!isset($pageData['extract'])) {
            return null;
        }

        $articleData = new ArticleDto();
        $articleData->title = $pageData['title'];
        $articleData->text = $pageData['extract'];
        $articleData->url = urldecode($pageData['fullurl']);
        $articleData->sizeKb = round(($pageData['length'] ?? 0) / 1024, 2);

        return $articleData;
    }
}

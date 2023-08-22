<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\ArticleDto;
use App\DTO\SearchArticlesDto;
use App\Models\Article;

/**
 * Service class for managing articles and related operations.
 */
class ArticleService
{
    /**
     * Store an article in the database.
     *
     * @param ArticleDto $articleDto The data transfer object representing the article.
     *
     * @return Article The created article model instance.
     */
    public function storeArticle(ArticleDto $articleDto): Article
    {
        $articleData = [
            'title' => $articleDto->title,
            'text' => $articleDto->text,
            'url' => $articleDto->url,
            'size_kb' => $articleDto->sizeKb,
            'word_count' => $articleDto->wordCount,
        ];

        return Article::create($articleData);
    }

    /**
     * Search articles by a specific word and calculate total occurrences.
     *
     * @param string $word The word to search for.
     *
     * @return SearchArticlesDto The data transfer object containing search results and total occurrences.
     */
    public function searchByWord(string $word): SearchArticlesDto {
        $articles = Article::withWordOccurrences($word)->get();

        $totalOccurrences = 0;
        foreach ($articles as $article) {
            $totalOccurrences += $article->occurrences;
        }

        $searchArticlesDto = new SearchArticlesDto();
        $searchArticlesDto->articles = $articles;
        $searchArticlesDto->totalOccurrences = $totalOccurrences;

        return $searchArticlesDto;
    }
}

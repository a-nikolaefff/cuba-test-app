<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\SearchArticleRequest;
use App\Http\Requests\ImportArticleRequest;
use App\Models\Article;
use App\Rules\ExistDatabaseArticleRule;
use App\Rules\NotExistWikipediaArticleRule;
use App\Services\ArticleService;
use App\Services\TextWordOccurrenceService;
use App\Services\WikipediaApiService;
use App\Services\WordService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Controller for managing articles and related actions.
 */
class ArticleController extends Controller
{
    /**
     * Display a listing of the articles.
     *
     * * @return Response The Inertia response for rendering articles index page.
     */
    public function index(): Response
    {
        $articles = Article::allWithoutText()->get();
        return Inertia::render('Articles/Index', ['articles' => $articles]);
    }

    /**
     * Import an article from Wikipedia to database
     *
     * @param ImportArticleRequest      $request                   The request containing article import data.
     * @param WikipediaApiService       $wikipediaApiService       The service for interacting with Wikipedia API.
     * @param TextWordOccurrenceService $textWordOccurrenceService The service for extracting text word occurrences.
     * @param ArticleService            $articleService            The service for managing articles.
     * @param WordService               $wordService               The service for managing words.
     *
     * @return JsonResponse The JSON response containing the imported article data.
     */
    public function import(
        ImportArticleRequest $request,
        WikipediaApiService $wikipediaApiService,
        TextWordOccurrenceService $textWordOccurrenceService,
        ArticleService $articleService,
        WordService $wordService
    ): JsonResponse {
        $inputData = $request->validated();
        $articleDto = $wikipediaApiService->fetchArticleDto(
            $inputData['title']
        );

        Validator::make($inputData, [
            'title' => [
                new NotExistWikipediaArticleRule($articleDto),
                new ExistDatabaseArticleRule($articleDto)
            ]
        ])->validate();

        $textWordOccurrenceDto = $textWordOccurrenceService
            ->extractWordsAndOccurrences($articleDto->text);
        $articleDto->wordCount = $textWordOccurrenceDto->wordCount;

        $article = $articleService->storeArticle($articleDto);
        $wordService->storeWordsAndWordCounts(
            $article->id,
            $textWordOccurrenceDto->wordOccurrences
        );

        return response()->json(['article' => $article]);
    }

    /**
     * Display a search page.
     *
     * @return Response The Inertia response for rendering the search page.
     */
    public function showSearchPage(): Response
    {
        return Inertia::render('Articles/Search');
    }

    /**
     * Display a search page.
     *
     * @return JsonResponse The Inertia response for rendering the search page.
     */
    public function search(
        SearchArticleRequest $request,
        ArticleService $articleService
    ): JsonResponse {
        $inputData = $request->validated();

        $searchArticlesDto = $articleService->searchByWord($inputData['word']);

        return response()->json(
            [
                'articles' => $searchArticlesDto->articles,
                'totalOccurrences' => $searchArticlesDto->totalOccurrences
            ]
        );
    }

    /**
     * Get an article by ID.
     *
     *  @param int $articleId The ID of the article to get.
     *
     * @return JsonResponse The JSON response containing the article text.
     */
    public function get(int $articleId): JsonResponse
    {
        $text = Article::findOrFail($articleId)->text;
        return response()->json(['text' => $text]);
    }

}

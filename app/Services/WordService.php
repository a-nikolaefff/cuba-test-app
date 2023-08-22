<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ArticleWord;
use App\Models\Word;
use Illuminate\Support\Facades\DB;

/**
 * Service class for managing words and their occurrences in articles.
 */
class WordService
{
    /**
     * Store words and their occurrences for a specific article.
     *
     * @param int   $articleId       The ID of the article for which to store words.
     * @param array $wordOccurrences The associative array containing word occurrences. Keys are words and values are the number of occurrences.
     *
     * @return void
     */
    public function storeWordsAndWordCounts(
        int $articleId,
        array $wordOccurrences
    ): void {
        DB::transaction(function () use ($articleId, $wordOccurrences) {
            foreach ($wordOccurrences as $word => $occurrence) {
                $wordModel = Word::firstOrCreate(['name' => $word]);

                ArticleWord::updateOrCreate(
                    ['article_id' => $articleId, 'word_id' => $wordModel->id],
                    ['occurrences' => DB::raw('occurrences + ' . $occurrence)]
                );
            }
        });
    }
}

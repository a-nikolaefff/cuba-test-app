<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Model representing an article.
 */
class Article extends Model
{
    use HasFactory;

    /**
     * The name of the table in the database
     *
     * @var string
     */
    protected $table = 'articles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable
        = [
            'title',
            'text',
            'url',
            'size_kb',
            'word_count',
        ];


    /**
     * Define a many-to-many relationship with the Word model.
     *
     * @return BelongsToMany The relationship between Article and Word models.
     */
    public function words(): BelongsToMany
    {
        return $this->belongsToMany(Word::class);
    }


    /**
     * Scope to retrieve all articles without the text.
     *
     * @param Builder $query The query builder instance.
     *
     * @return void
     */
    public function scopeAllWithoutText(Builder $query): void
    {
        $query->select(
            'title',
            'url',
            'size_kb',
            'word_count',
            'created_at',
        )
            ->orderByDesc('created_at');
    }

    /**
     * Scope to retrieve articles along with word occurrences for a specific word.
     *
     * @param Builder $query The query builder instance.
     * @param string  $word  The word for which to retrieve occurrences.
     *
     * @return void
     */
    public function scopeWithWordOccurrences(Builder $query, string $word): void
    {
        $query
            ->select(
                'articles.id',
                'articles.title',
                'articles_words.occurrences',
            )->join(
                'articles_words',
                'articles.id',
                '=',
                'articles_words.article_id'
            )
            ->join('words', 'articles_words.word_id', '=', 'words.id')
            ->where('words.name', $word)
            ->orderByDesc('articles_words.occurrences');
    }


    /**
     * Scope to retrieve the text of a specific article.
     *
     * @param Builder $query     The query builder instance.
     * @param int     $articleId The ID of the article to retrieve the text for.
     *
     * @return void
     */
    public function scopeGetText(Builder $query, int $articleId): void
    {
        $query->select('text')
            ->firstWhere('id', $articleId);
    }
}

<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ArticleWord
 *
 * Model representing the relationship between articles and words,
 * along with the occurrences of words in articles.
 */
class ArticleWord extends Model
{
    /**
     * The name of the table in the database
     *
     * @var string
     */
    protected $table = 'articles_words';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'article_id',
        'word_id',
        'occurrences',
    ];
}

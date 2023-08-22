<?php

declare(strict_types=1);

namespace App\Rules;

use App\DTO\ArticleDto;
use App\Models\Article;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

/**
 * Validation rule to check if an article with a given title exists in the database.
 */
class ExistDatabaseArticleRule implements ValidationRule
{
    /**
     * The article data DTO to check against.
     *
     * @var ArticleDto|null
     */
    private ?ArticleDto $articleDataDto;

    /**
     * Create a new rule instance.
     *
     * @param ArticleDto|null $articleDataDto The article data DTO to check against.
     */
    public function __construct(?ArticleDto $articleDataDto)
    {
        $this->articleDataDto = $articleDataDto;
    }

    /**
     * Validate the title.
     *
     * @param string                                       $attribute The attribute name.
     * @param mixed                                        $value     The attribute value.
     * @param Closure(string): PotentiallyTranslatedString $fail      The fail callback.
     *
     * @return void
     */
    public function validate(
        string $attribute,
        mixed $value,
        Closure $fail
    ): void {
        if (isset($this->articleDataDto)
            && Article::where('title', $this->articleDataDto->title)->exists()
        ) {
            $fail('validation.custom.title.exist-in-database')
                ->translate(['title' => $this->articleDataDto->title]);
        }
    }
}

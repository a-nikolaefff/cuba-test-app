<?php

declare(strict_types=1);

namespace App\DTO;

use Illuminate\Database\Eloquent\Collection;

/**
 * Data transfer object (DTO) representing a search result for articles.
 */
class SearchArticlesDto
{
    /**
     * @var Collection The collection of articles in the search result.
     */
    public Collection $articles;

    /**
     * @var int The total number of occurrences matching the search criteria.
     */
    public int $totalOccurrences;
}

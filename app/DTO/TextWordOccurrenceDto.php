<?php

declare(strict_types=1);

namespace App\DTO;

/**
 * Data transfer object (DTO) representing word occurrences within a text.
 */
class TextWordOccurrenceDto
{
    /**
     * @var int The total number of words in the text.
     */
    public int $wordCount;

    /**
     * @var array An associative array representing word occurrences in the text.
     *            Keys are words and values are the number of occurrences.
     */
    public array $wordOccurrences;
}

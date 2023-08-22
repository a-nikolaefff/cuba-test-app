<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\TextWordOccurrenceDto;

/**
 * Service class for extracting word occurrences from text.
 */
class TextWordOccurrenceService
{
    /**
     * Extract words and their occurrences from the given text.
     *
     * @param string $text The text from which to extract word occurrences.
     *
     * @return TextWordOccurrenceDto The data transfer object containing word occurrences.
     */
    public function extractWordsAndOccurrences(string $text
    ): TextWordOccurrenceDto {
        $words = $this->extractWordsFromText($text);
        $wordOccurrences = $this->countWordOccurrences($words);

        $textWordOccurrenceDto = new TextWordOccurrenceDto();
        $textWordOccurrenceDto->wordCount = count($words);
        $textWordOccurrenceDto->wordOccurrences = $wordOccurrences;

        return $textWordOccurrenceDto;
    }

    /**
     * Extract words from the given text.
     *
     * @param string $text The text from which to extract words.
     *
     * @return array The array containing extracted words.
     */
    private function extractWordsFromText(string $text): array
    {
        $text = mb_strtolower($text);
        return preg_split(
            "/[^\p{L}\d]+/u",
            $text,
            -1,
            PREG_SPLIT_NO_EMPTY
        );
    }

    /**
     * Count occurrences of words in the given array.
     *
     * @param array $words The array of words for which to count occurrences.
     *
     * @return array The associative array containing word occurrences.
     */
    private function countWordOccurrences($words): array
    {
        $wordCount = [];

        foreach ($words as $word) {
            $wordCount[$word] = isset($wordCount[$word])
                ? ++$wordCount[$word]
                : 1;
        }

        return $wordCount;
    }
}

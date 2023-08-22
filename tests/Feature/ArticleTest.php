<?php

namespace Tests\Feature;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_main_page_returns_a_redirect(): void
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }


    public function test_import_page_can_be_displayed(): void
    {
        $response = $this->get(route('articles.index'));

        $response->assertStatus(200);
    }

    public function test_search_page_can_be_displayed(): void
    {
        $response = $this->get(route('article.show-search-page'));

        $response->assertStatus(200);
    }

    public function test_a_wikipedia_article_can_be_imported_by_title(): void
    {
        $response = $this->post(
            route('articles.import'),
            ['title' => 'Липецк']
        );

        $response->assertStatus(200);
    }

    public function test_a_wikipedia_article_cannot_be_imported_by_empty_title(
    ): void
    {
        $response = $this->post(route('articles.import'), ['title' => '']);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'title' => __(
                'validation.required',
                ['attribute' => __('validation.attributes.title')]
            )
        ]);
    }

    public function test_a_wikipedia_article_cannot_be_imported_by_wrong_title(
    ): void
    {
        $response = $this->post(
            route('articles.import'),
            ['title' => 'Липецк123']
        );

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'title' => __('validation.custom.title.not-exist-in-wikipedia')
        ]);
    }

    public function test_the_article_that_already_exist_cannot_be_imported_again(
    ): void
    {
        $this->post(route('articles.import'), ['title' => 'Липецк']);

        $response = $this->post(
            route('articles.import'),
            ['title' => 'Липецк']
        );

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'title' => __(
                'validation.custom.title.exist-in-database',
                ['title' => 'Липецк']
            )
        ]);
    }

    public function test_article_search_response_contains_articles_and_total_occurrences(
    ): void
    {
        $this->post(route('articles.import'), ['title' => 'Липецк']);

        $response = $this->get(route('articles.search', ['word' => 'липецк']));
        $responseData = $response->json();

        $response->assertJsonStructure([
            'articles' => ['*' => ['id', 'title', 'occurrences']],
            'totalOccurrences',
        ]);
        $this->assertIsArray($responseData['articles']);
        $this->assertIsInt($responseData['totalOccurrences']);
    }

    public function test_article_search_response_returns_correct_error_by_wrong_keyword(
    ): void
    {
        $this->post(route('articles.import'), ['title' => 'Липецк']);

        $response = $this->get(
            route('articles.search', ['word' => 'qwertyytrewq'])
        );

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'word' => __('validation.custom.word.exists')
        ]);
    }

    public function test_article_search_response_returns_correct_error_by_empty_keyword(
    ): void
    {
        $this->post(route('articles.import'), ['title' => 'Липецк']);

        $response = $this->get(route('articles.search', ['word' => '']));

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'word' => __(
                'validation.required',
                ['attribute' => __('validation.attributes.word')]
            )
        ]);
    }

    public function test_get_article_text_response_returns_correct_text_by_article_id(
    ): void
    {
        $testText = 'Test article text';
        $article = Article::factory()->create(['text' => $testText]);

        $response = $this->get(
            route('articles.get', ['id' => $article->id])
        );
        $responseData = $response->json();

        $response->assertStatus(200);
        $this->assertArrayHasKey(
            'text',
            $responseData
        );
        $this->assertEquals(
            $testText,
            $responseData['text']
        );
    }

    public function test_get_article_text_response_returns_correct_error_by_article_id_that_does_not_exist(
    ): void
    {

        $response = $this->get(
            route('articles.get', ['id' => 1])
        );

        $response->assertStatus(404);
    }
}

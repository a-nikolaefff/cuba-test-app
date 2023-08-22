<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class GetArticleTextRequest
 *
 * Request class for retrieving the text of a specific article.
 */
class GetArticleTextRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     *  @return bool True if the user is authorized to make the request, false otherwise.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:articles,id'],
        ];
    }
}

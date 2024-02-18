<?php

namespace App\Http\Requests\URLShortner;

use App\Models\ShortURL;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'redirect_url' => ['required','url:http,https'],
            'is_active' => ['boolean'],
            'single_use' => ['boolean'],
            'track_visits' => ['boolean'],
            'code' => ['required', 'unique:short_urls,code'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'redirect_url.required' => 'The url is required.',
            'redirect_url.url' => 'The url must be a valid URL.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        do {
            $code = Str::random(16);
        } while (ShortURL::where('code', $code)->exists());

        $this->merge([
            'code' => $code,
        ]);
    }
}

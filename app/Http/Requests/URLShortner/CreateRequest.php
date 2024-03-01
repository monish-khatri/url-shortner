<?php

namespace App\Http\Requests\URLShortner;

use App\Models\ShortURL;
use Carbon\Carbon;
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
        dd($this->all());
        $rules = [
            'is_active' => ['boolean'],
            'single_use' => ['boolean'],
            'track_visits' => ['boolean'],
            'redirect_url' => ['required','url:http,https'],
            'activated_at' => ['sometimes', 'date', 'after_or_equal:now'],
            // 'deactivated_at' => ['required', 'date', 'after_or_equal:activated_at'],
            'user_id' => ['required'],
        ];

        // Check if it's a create request
        if ($this->isCreateRequest()) {
            // Add 'code' rule for create requests
            $rules['code'] = ['required', 'unique:short_urls,code'];
        }

        return $rules;

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
        if($this->isCreateRequest()) {
            do {
                $code = Str::random(16);
            } while (ShortURL::where('code', $code)->exists());

        } else {
            $code = $this->code;
        }

        $this->merge([
            'code' => $code,
            'user_id' => auth()->user()->id,
            'activated_at' => Carbon::parse($this->activated_at)->utc(),
            // 'deactivated_at' => Carbon::parse($this->deactivated_at)->utc(),
        ]);
    }

    /**
     * Check if the request is a create request.
     */
    private function isCreateRequest(): bool
    {
        return $this->route()->named('short-urls.store');
    }
}

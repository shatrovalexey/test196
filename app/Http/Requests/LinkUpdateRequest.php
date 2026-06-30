<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LinkUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Пользователь уже авторизован через middleware
        return true;
    }

    public function rules(): array
    {
        return [
            'href' => [
                'required',
                'string',
                'url',
                'max:2048',
                Rule::unique('links', 'href')
                    ->where('user_id', $this->user()->id)
                    ->ignore($this->route('link')),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'href.required' => 'URL ссылки обязателен для заполнения',
            'href.url' => 'Пожалуйста, введите корректный URL',
            'href.max' => 'URL не может быть длиннее 2048 символов',
            'href.unique' => 'Вы уже добавили эту ссылку',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('href')) {
            $this->merge([
                'href' => $this->normalizeUrl($this->href),
            ]);
        }
    }

    private function normalizeUrl(string $url): string
    {
        $url = trim($url);
        
        if (!preg_match('/^https?:\/\//', $url)) {
            $url = 'https://' . $url;
        }
        
        return $url;
    }
}
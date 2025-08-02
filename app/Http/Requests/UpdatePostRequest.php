<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $post = $this->route('post');
        return auth()->check() && (auth()->user()->id === $post->user_id || auth()->user()->is_admin ?? false);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9\s\-_.,!?()]+$/'
            ],
            'content' => [
                'required',
                'string',
                'min:10',
                'max:100000'
            ],
            'category_id' => [
                'required',
                'integer',
                'exists:categories,id'
            ],
            'status' => [
                'required',
                'in:draft,published'
            ],
            'published_at' => [
                'nullable',
                'date'
            ],
            'image' => [
                'nullable',
                'string',
                'regex:/^\/storage\/images\/[a-zA-Z0-9]+\.(jpg|jpeg|png|gif|webp)$/'
            ]
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Post title is required.',
            'title.regex' => 'Post title contains invalid characters.',
            'content.required' => 'Post content is required.',
            'content.min' => 'Post content must be at least 10 characters.',
            'content.max' => 'Post content cannot exceed 100,000 characters.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'Selected category does not exist.',
            'status.in' => 'Invalid post status.',
            'image.regex' => 'Invalid image format or path.'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Clean up the title
        if ($this->has('title')) {
            $this->merge([
                'title' => trim($this->title)
            ]);
        }
    }
}

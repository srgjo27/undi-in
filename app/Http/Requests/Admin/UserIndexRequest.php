<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'role' => [
                'nullable',
                'string',
                Rule::in(['admin', 'seller', 'buyer'])
            ],
            'search' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9\s@._-]*$/'
            ],
            'page' => [
                'nullable',
                'integer',
                'min:1',
                'max:1000'
            ],
            'per_page' => [
                'nullable',
                'integer',
                'min:10',
                'max:100'
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'role.in' => 'Invalid role selected.',
            'search.regex' => 'Search contains invalid characters.',
            'search.max' => 'Search term is too long.',
            'page.max' => 'Page number is too high.',
            'per_page.max' => 'Items per page cannot exceed 100.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        if ($this->hasInvalidCharacters()) {
            Log::warning('Potential attack detected in user search', [
                'user_id' => Auth::id(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'input' => request()->all(),
                'errors' => $validator->errors()->toArray()
            ]);
        }

        parent::failedValidation($validator);
    }

    /**
     * Check if input contains potentially malicious characters.
     */
    private function hasInvalidCharacters(): bool
    {
        $search = request()->input('search', '');
        $role = request()->input('role', '');

        $maliciousPatterns = [
            // SQL Injection patterns
            '/(\bunion\s+select\b)/i',
            '/(\bselect\s+.*\bfrom\b)/i',
            '/(\binsert\s+into\b)/i',
            '/(\bdelete\s+from\b)/i',
            '/(\bdrop\s+table\b)/i',
            '/(\bupdate\s+.*\bset\b)/i',
            '/(\bor\s+1\s*=\s*1\b)/i',
            '/(\band\s+1\s*=\s*1\b)/i',

            // Script injection patterns
            '/<script[^>]*>/i',
            '/<\/script>/i',
            '/javascript:/i',
            '/on\w+\s*=/i',

            // Path traversal patterns
            '/\.\.\//',
            '/\.\.\\\\/',

            // Command injection patterns
            '/;\s*(rm|del|format|shutdown)/i',
            '/\|\s*(nc|netcat|curl|wget)/i',
        ];

        foreach ($maliciousPatterns as $pattern) {
            if (preg_match($pattern, $search) || preg_match($pattern, $role)) {
                return true;
            }
        }

        return false;
    }
}

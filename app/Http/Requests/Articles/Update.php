<?php

namespace App\Http\Requests\Articles;

use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|required',
            'category_id' => 'sometimes|required|exists:categories,id',
            'tags.*' => 'sometimes|required|exists:tags,id',
            'user_id' => 'sometimes|required|exists:users,id',
            'full_text' => 'sometimes|required',
            'image' => 'sometimes|image'
        ];
    }
}

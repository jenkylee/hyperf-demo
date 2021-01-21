<?php

declare(strict_types=1);

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;

class ArticleRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'caller' => 'required|max:20',
            'time_stamp' => 'required|integer',
            'id' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'caller.required' => '参数错误',
            'caller.max' => '参数错误',
            'time_stamp.required' => '参数错误',
            'time_stamp.string' => '参数错误',
            'trace_id.string' => '参数错误',
            'id.required' => '参数错误'
        ];
    }
}

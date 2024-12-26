<?php

namespace App\Http\Requests;

use App\Enums\TaskStatus;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class TasksListRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'page' => ['nullable'],
            'per_page' => ['nullable'],
            'direction' => ['nullable', Rule::in('asc', 'desc')],
            'status' => ['nullable', Rule::in(array_keys(TaskStatus::forSelect()))],
            'title' => ['string', 'nullable'],
            'team' => ['nullable'],
            'user' => ['nullable'],
            'created_at' => ['date', 'date_format:Y-m-d', 'nullable'],
            'updated_at' => ['date', 'date_format:Y-m-d', 'nullable']
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}

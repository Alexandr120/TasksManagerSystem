<?php

namespace App\Http\Requests;

use App\Enums\TaskStatus;
use App\Models\Team;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class TaskFormRequest extends FormRequest
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
        $rules = [
            'title' => ['required', 'string', 'min:2', 'max:255'],
            'description' => ['required', 'string', 'min:2', 'max:255'],
            'status' => ['required', Rule::in(array_keys(TaskStatus::forSelect()))],
            'team_id' => ['nullable', Rule::in((new Team())->repository()->getTeamsListForSelect()->keys())],
            'user_id' => ['nullable']
        ];

        if ($this->team_id) {
            $team = Team::findOrFail($this->team_id);
            $rules['user_id'][] = Rule::in($team->repository()->usersList()->keys());
        }

        return $rules;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}

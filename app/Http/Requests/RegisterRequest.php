<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class RegisterRequest extends FormRequest
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
            'name' => ['required', 'string','min:2', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:6']
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('Insert name!'),
            'name.string' => __('Invalid format! Must be a string!'),
            'name.min' => __('Invalid format! Must be at least 2 characters!'),
            'name.max' => __('Invalid format! Must be No more than 255 characters!'),
            'email.required' => __('Insert email!'),
            'email.string' => __('Invalid format! Must be a string!'),
            'email.email' => __('Invalid email!'),
            'email.max' => __('Invalid format! Must be No more than 255 characters!'),
            'email.unique' => __('User with this email already exist!'),
            'password.required' => __('Insert password!'),
            'password.string' => __('Invalid format! Must be a string!'),
            'password.min' => __('Invalid password! Must be at least 6 characters!'),
            'password.confirmed' => __('Password confirmation does not match!'),
            'password_confirmation.required' => __('Confirm your password!'),
            'password_confirmation.string' => __('Password confirmation does not match!'),
            'password_confirmation.min' => __('Password confirmation does not match!'),
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}

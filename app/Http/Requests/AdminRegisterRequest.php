<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminRegisterRequest extends FormRequest
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
            'name' =>'required|string|max:255',
            'email' =>'required|string|email|unique:admins',
            'password' => 'required|string|min:8|same:password_confirm',
            'password_confirm' => 'required|same:password',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nom est obligatoire',
            'email.required' => 'Email est obligatoire',
            'email.email' => 'Email doit être une adresse email valide',
            'email.unique' => 'Email déjà utilisé',
            'password.required' => 'Mot de passe est obligatoire',
            'password.min' => 'Mot de passe doit contenir au moins 8 caractères',
            'password.same' => 'La confirmation du mot de passe n\'est pas correcte',
            'password_confirm.required' => 'Veuillez confirmer le mot de passe',
            'password_confirm.same' => 'Mot de passe ne correspond pas',
        ];
    }

}

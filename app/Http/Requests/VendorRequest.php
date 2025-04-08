<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorRequest extends FormRequest
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
            'name' => 'required',
            'prenom' => 'required',
            'dateNaiss' => 'required',
            'contact' => 'required|numeric',
            'email' => 'required|email|unique:vendors,email',
            'commune' => 'required',
            'role' => 'required'
        ];
    }

    public function messages(){
        return [
            'name.required' => 'Le nom est obligatoire',
            'prenom.required' => 'Le prénom est obligatoire',
            'dateNaiss.required' => 'La date de naissance est obligatoire',
            'contact.required' => 'Le numéro de téléphone est obligatoire',
            'contact.numeric' => 'Le numéro de téléphone doit être de type numérique',
            'email.required' => 'L\'adresse mail est obligatoire',
            'email.email' => 'L\'adresse mail doit être un mail',
            'email.unique' => 'cette adresse mail existe déjà',
            'commune.required' => 'La commune est obligatoire',
            'role.required' => 'Le rôle est obligatoire',
        ];
    }
}

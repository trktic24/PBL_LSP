<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // [PERBAIKAN 1] Ganti 'name' menjadi 'username'
            'username' => [
                'required', 
                'string', 
                'max:255',
                // [PERBAIKAN 2] ignore() membutuhkan 2 parameter karena PK Anda bukan 'id'
                // ignore(ID_Value, Nama_Kolom_PK)
                Rule::unique(User::class)->ignore($this->user()->id_user, 'id_user'),
            ],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                // [PERBAIKAN 3] Sesuaikan ignore untuk email juga
                Rule::unique(User::class)->ignore($this->user()->id_user, 'id_user'),
            ],
        ];
    }
}
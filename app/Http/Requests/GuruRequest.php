<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuruRequest extends FormRequest
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
            "nip" => "required|numeric|unique:gurus",
            "nama" => "required",
            "email" => "required|email|unique:gurus",
            "sekolahs" => "required",
        ];
    }

    public function messages(): array
    {
        return [
            "nip.required" => "NIP harus diisi.",
            "nip.unique" => "NIP tersebut dudah dipakai. Coba yang lain.",
            "nip.numeric" => "NIP harus berupa angka.",
            "nama.required" => "Nama harus diisi.",
            "email.required" => "Email harus diisi.",
            "email.unique" => "Email tersebut dudah dipakai. Coba yang lain.",
            "nip.email" => "Format email tidak sesuai.",
            "sekolah" => "Jangan lupa, memilih lembaga",
        ];
    }
}

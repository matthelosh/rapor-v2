<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TpRequest extends FormRequest
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
            'mapel_id' => 'required',
            'kode' => 'required|unique:tps',
            'teks' => 'required',
            'tingkat' => 'required',
            'semester' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'kode.unique' => 'Kode Sudah dipakai TP lain. Coba lagi'
        ];
    }
}

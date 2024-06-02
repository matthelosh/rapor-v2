<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RombelRequest extends FormRequest
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
            'tapel' => 'required',
            'pararel' => 'required',
            'kode' => 'required|unique:rombels',
            'label' => 'required',
            'fase' => 'required|alpha',
            'tingkat' => 'required|numeric',
            'sekolah_id' => 'required',
            'guru_id' => 'required',
        ];
    }

    public function messages(): array {
        return [
            'tapel.required' => 'Tahun Pelajaran harus terisi',
            'kode.required' => 'Kode Rrombel harus diisi',
            'kode.unique' => 'Kode sudah dipakai rombel lain',
            'guru_id.required' => 'Pilih Wali Kelas'
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SiswaRequest extends FormRequest
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
            "nisn" => "required|numeric|unique:siswas",
            "nis" => "numeric",
            "nik" => "numeric",
            "nama" => "required",
            "email" => "required|email|unique:siswas",
            "jk" => "required",
            "alamat" => "required",
            "agama" => "required",
            "sekolah_id" => "required|numeric",
        ];
    }

    public function messages(): array
    {
        return [
            "nisn.required" => "NISN wajib diisi",
            "nisn.numeric" => "NISN wajib berupa angka",
            "nisn.unique" =>
                "NISN tersebut sudah dimiliki siswa lain. Cek lagi",
            "nis.numeric" => "NIS harus berupa angka",
            "nik.numeric" => "NIK harus berupa angka",
            "nama.required" => "Nama harus diisi",
            "email.required" => "Email harus diisi",
            "email.email" => "Format email tidak sesuai",
            "email.unique" => "Email sudah dipakai. Coba ganti",
            "jk.required" => "Jenis Kelamin harus diisi",
            "alamat.required" => "Alamat siswa harus diisi",
            "agama.required" => "Agama siswa harus diisi",
            "sekolah_id.required" => "Lembaga siswa harus diisi dengan NPSN",
        ];
    }
}

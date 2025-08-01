<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AsesmenRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return \auth()->user()->can("add_asesmen");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "kode" => "unique:asesmens",
            "nama" => "required",
            "deskripsi" => "required",
            "tanggal" => "required",
            "mulai" => "required",
            "selesai" => "required",
            "jenis" => "required",
            "sekolah_id" => "required",
            "semester" => "required",
            "tapel" => "required",
        ];
    }
}

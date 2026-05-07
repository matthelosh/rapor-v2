<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class DapodikHelper
{
    public static function users()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer QteRgcGaC8TGojF',
            'Content-Type' => 'application/json',
        ])->get('http://192.168.1.16:5774/WebService/getPengguna?npsn=20518848');

        if ($response->successful()) {
            return $response['rows'];
        } else {
            return $response->status();
        }
    }
    public static function sekolah()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer QteRgcGaC8TGojF',
            'Content-Type' => 'application/json',
        ])->get('http://192.168.1.16:5774/WebService/getSekolah?npsn=20518848');

        if ($response->successful()) {
            return $response['rows'];
        } else {
            return $response->status();
        }
    }
    public static function ptks()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer QteRgcGaC8TGojF',
            'Content-Type' => 'application/json',
        ])->get('http://192.168.1.16:5774/WebService/getPTK?npsn=20518848');

        if ($response->successful()) {
            return $response['rows'];
        } else {
            return $response->status();
        }
    }
    public static function rombels()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer QteRgcGaC8TGojF',
            'Content-Type' => 'application/json',
        ])->get('http://192.168.1.16:5774/WebService/getRombonganBelajar?npsn=20518848');

        if ($response->successful()) {
            return $response['rows'];
        } else {
            return $response->status();
        }
    }
    public static function siswas($data)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer QteRgcGaC8TGojF',
            'Content-Type' => 'application/json',
        ])->get("http://" . $data['ipDapodik'] . ":5774/WebService/getPesertaDidik?npsn=" . $data['npsn']);

        if ($response->successful()) {
            return $response['rows'];
        } else {
            return $response->status();
        }
    }
}

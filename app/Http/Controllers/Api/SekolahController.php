<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sekolah;
use App\Http\Resources\SekolahResource;
use App\Services\SekolahService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


class SekolahController extends Controller
{
    public function index(Request $request, SekolahService $sekolahService)
    {
        $sekolahs = $sekolahService->index($request->user());

        return new SekolahResource(true, 'Data Sekolah', $sekolahs);
    }

    public function store(Request $request, SekolahService $sekolahService)
    {
        try {
            $validator = Validator::make($request->all(), [
                'npsn' => 'required|numeric|unique:sekolahs',
                'nama' => 'required',
                'alamat' => 'required',
                'desa' => 'required',
                'nama_ks' => 'required',

            ]);
            if ($validator->fails())  {
                // dd($validator->errors());
                return response(['success' => false, 'errors' => $validator->errors()],422);
            }
            $sekolah = $sekolahService->store($request);
            // dd($sekolah);
            $resource = new SekolahResource(true, 'Data Sekolah Disimpan', $sekolah);
            return $resource->response();
        } catch (\Throwable $th)
        {
            return new SekolahResource(false, $th->getMessage(), null);
        }
    }

    public function showBySubdomain($subdomain)
    {
        $sekolah = Sekolah::where('subdomain', $subdomain)->with([
            'ks',
            'posts' => function($q) {
                $q->where('status', 'published')->orderBy('created_at', 'DESC');
                $q->with('author.userable');
            },
            'gurus',
            'rombels' => function($r) {
                $r->with([
                    'wali_kelas',
                    'siswas',
                    'gurus'
                ]);
            }
            ])->first();
        return response()->json([
            'success' => true,
            'message' => 'Data Sekolah ditemukan',
            'data' => $sekolah
        ]);
        // return new SekolahResource(true, 'Data Sekolah '.$sekolah->nama, $sekolah);
    }

    public function show($id) {
        $sekolah = Sekolah::findOrFail($id);

        return new SekolahResource(true, 'Data Sekolah '.$sekolah->nama, $sekolah);
    }

    public function update(Request $request, $id)
    {
        try {
        dd($id);
        $sekolah = Sekolah::findOrFail($id);
        $update = $sekolah->update(['nama' => $request->nama]);
        $resource = new SekolahResource(true, 'Data Sekolah Diperbarui', $update);
        return $resource->response();
        } catch(\Exception $e)
        {
            dd($e);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
        $delete = Sekolah::destroy($id);
            return response()->json(['success' => true, 'message' => 'Data Sekolah dihapus', 'data' => $delete]);
        } catch(\Exception $e)
        {
            dd($e);
        }
    }
}

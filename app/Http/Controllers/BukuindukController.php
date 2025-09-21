<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

use App\Models\Sekolah;
use App\Models\Siswa;
use App\Models\BukuInduk;

class BukuindukController extends Controller
{
    public function home(Request $request)
    {
        $ops = $request->user()->userable;
        $sekolah = Sekolah::whereHas("ops", function ($o) use ($ops) {
            $o->where("nip", $ops->nip);
        })->first();

        $query = BukuInduk::with(["siswa"])->whereHas("siswa", function (
            $q,
        ) use ($sekolah) {
            $q->where("sekolah_id", $sekolah->npsn);
        });

        // Filter berdasarkan status
        if ($request->status && $request->status !== "all") {
            $query->where("status", $request->status);
        }

        // Search
        if ($request->search) {
            $search = $request->search;
            $query->whereHas("siswa", function ($q) use ($search) {
                $q->where("nama", "like", "%{$search}%")
                    ->orWhere("nisn", "like", "%{$search}%")
                    ->orWhere("nis", "like", "%{$search}%");
            });
        }

        $bukuInduks = $query->orderBy("created_at", "desc")->paginate(20);

        return Inertia::render("Dash/BukuInduk", [
            "bukuInduks" => $bukuInduks,
            "sekolah" => $sekolah,
            "filters" => $request->only(["status", "search"]),
        ]);
    }

    public function create(Request $request)
    {
        $ops = $request->user()->userable;
        $sekolah = Sekolah::whereHas("ops", function ($o) use ($ops) {
            $o->where("nip", $ops->nip);
        })->first();

        // Ambil siswa yang belum memiliki buku induk
        $siswasWithoutBukuInduk = Siswa::where("sekolah_id", $sekolah->npsn)
            ->whereDoesntHave("bukuInduk")
            ->orderBy("nama")
            ->get();

        return Inertia::render("Dash/BukuInduk/Create", [
            "siswas" => $siswasWithoutBukuInduk,
            "sekolah" => $sekolah,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "siswa_id" => "required|string|exists:siswas,nisn",
            "no_induk" => "nullable|string|max:20",
            "tanggal_masuk" => "required|date",
            "asal_sekolah" => "nullable|string|max:100",
            "status" => "required|in:aktif,lulus,pindah,keluar",
            "tanggal_keluar" => "nullable|date|after_or_equal:tanggal_masuk",
            "alasan_keluar" => "nullable|string|max:100",
            "sekolah_tujuan" => "nullable|string|max:100",
            "keterangan" => "nullable|string",
        ]);

        // Cek apakah siswa sudah memiliki buku induk
        $existingBukuInduk = BukuInduk::where(
            "siswa_id",
            $validated["siswa_id"],
        )->first();
        if ($existingBukuInduk) {
            return back()->withErrors([
                "siswa_id" => "Siswa sudah memiliki buku induk.",
            ]);
        }

        BukuInduk::create($validated);

        return redirect()
            ->route("dashboard.bukuinduk.home")
            ->with("success", "Data buku induk berhasil ditambahkan.");
    }

    public function show($id)
    {
        $bukuInduk = BukuInduk::with([
            "siswa.ortus",
            "siswa.rombels.tapel",
        ])->findOrFail($id);

        return Inertia::render("Dash/BukuInduk/Show", [
            "bukuInduk" => $bukuInduk,
        ]);
    }

    public function edit($id)
    {
        $bukuInduk = BukuInduk::with("siswa")->findOrFail($id);

        return Inertia::render("Dash/BukuInduk/Edit", [
            "bukuInduk" => $bukuInduk,
        ]);
    }

    public function update(Request $request, $id)
    {
        $bukuInduk = BukuInduk::findOrFail($id);

        $validated = $request->validate([
            "no_induk" => "nullable|string|max:20",
            "tanggal_masuk" => "required|date",
            "asal_sekolah" => "nullable|string|max:100",
            "status" => "required|in:aktif,lulus,pindah,keluar",
            "tanggal_keluar" => "nullable|date|after_or_equal:tanggal_masuk",
            "alasan_keluar" => "nullable|string|max:100",
            "sekolah_tujuan" => "nullable|string|max:100",
            "keterangan" => "nullable|string",
        ]);

        $bukuInduk->update($validated);

        return redirect()
            ->route("dashboard.bukuinduk.home")
            ->with("success", "Data buku induk berhasil diperbarui.");
    }

    public function destroy($id)
    {
        $bukuInduk = BukuInduk::findOrFail($id);
        $bukuInduk->delete();

        return redirect()
            ->route("dashboard.bukuinduk.home")
            ->with("success", "Data buku induk berhasil dihapus.");
    }

    public function print(Request $request)
    {
        $ops = $request->user()->userable;
        $sekolah = Sekolah::whereHas("ops", function ($o) use ($ops) {
            $o->where("nip", $ops->nip);
        })->first();

        $query = BukuInduk::with(["siswa"])->whereHas("siswa", function (
            $q,
        ) use ($sekolah) {
            $q->where("sekolah_id", $sekolah->npsn);
        });

        // Filter berdasarkan status
        if ($request->status && $request->status !== "all") {
            $query->where("status", $request->status);
        }

        $bukuInduks = $query->orderBy("created_at", "desc")->get();

        return Inertia::render("Dash/BukuInduk/Print", [
            "bukuInduks" => $bukuInduks,
            "sekolah" => $sekolah,
            "filters" => $request->only(["status"]),
        ]);
    }

    // public function export(Request $request)
    // {
    //     $ops = $request->user()->userable;
    //     $sekolah = Sekolah::whereHas("ops", function ($o) use ($ops) {
    //         $o->where("nip", $ops->nip);
    //     })->first();

    //     $query = BukuInduk::with(["siswa"])->whereHas("siswa", function (
    //         $q,
    //     ) use ($sekolah) {
    //         $q->where("sekolah_id", $sekolah->npsn);
    //     });

    //     // Filter berdasarkan status
    //     if ($request->status && $request->status !== "all") {
    //         $query->where("status", $request->status);
    //     }

    //     $bukuInduks = $query->orderBy("created_at", "desc")->get();

    //     // Generate Excel file
    //     $filename =
    //         "buku_induk_" . $sekolah->nama . "_" . date("Y-m-d") . ".xlsx";

    //     // Return as download response (implementation depends on your Excel package)
    //     return response()->json([
    //         "message" => "Export berhasil",
    //         "filename" => $filename,
    //     ]);
    // }
    public function export(Request $request)
    {
        $ops = $request->user()->userable;
        $sekolah = Sekolah::whereHas("ops", function ($o) use ($ops) {
            $o->where("nip", $ops->nip);
        })->first();

        $query = BukuInduk::with(["siswa"])->whereHas("siswa", function (
            $q,
        ) use ($sekolah) {
            $q->where("sekolah_id", $sekolah->npsn);
        });

        // Filter berdasarkan status
        if ($request->status && $request->status !== "all") {
            $query->where("status", $request->status);
        }

        $bukuInduks = $query->orderBy("created_at", "desc")->get();

        // Generate Excel file using service
        $excelService = new \App\Services\ExcelExportService();
        $export = $excelService->exportBukuInduk($bukuInduks, $sekolah);

        // Return as download
        return response()
            ->download($export["path"], $export["filename"])
            ->deleteFileAfterSend(true);
    }
    public function generateIndex(Request $request)
    {
        $ops = $request->user()->userable;
        $sekolah = Sekolah::whereHas("ops", function ($o) use ($ops) {
            $o->where("nip", $ops->nip);
        })->first();

        // Hitung siswa yang belum memiliki buku induk
        $siswaTanpaBukuInduk = Siswa::where("sekolah_id", $sekolah->npsn)
            ->whereDoesntHave("bukuInduk")
            ->where("status", "aktif")
            ->count();

        // Ambil preview data
        $generateService = new \App\Services\BukuIndukGenerateService();
        $preview = $generateService->getPreview($sekolah->npsn, 5);

        return Inertia::render("Dash/BukuInduk/Generate", [
            "sekolah" => $sekolah,
            "siswaTanpaBukuInduk" => $siswaTanpaBukuInduk,
            "preview" => $preview,
        ]);
    }

    public function generateBulk(Request $request)
    {
        $validated = $request->validate([
            "tanggal_masuk" => "nullable|date",
            "asal_sekolah" => "nullable|string|max:100",
            "status" => "required|in:aktif,lulus,pindah,keluar",
            "use_custom_no_induk" => "boolean",
            "keterangan" => "nullable|string",
            "confirm" => "required|boolean|accepted",
        ]);

        $ops = $request->user()->userable;
        $sekolah = Sekolah::whereHas("ops", function ($o) use ($ops) {
            $o->where("nip", $ops->nip);
        })->first();

        try {
            $generateService = new \App\Services\BukuIndukGenerateService();
            $results = $generateService->generateForSekolah($sekolah->npsn, [
                "tanggal_masuk" => $validated["tanggal_masuk"],
                "asal_sekolah" => $validated["asal_sekolah"],
                "status" => $validated["status"],
                "use_custom_no_induk" =>
                    $validated["use_custom_no_induk"] ?? false,
                "keterangan" => $validated["keterangan"],
            ]);

            return redirect()
                ->route("dashboard.bukuinduk.home")
                ->with(
                    "success",
                    "Generate berhasil! {$results["success"]} buku induk dibuat, {$results["skipped"]} dilewati, {$results["errors"]} error.",
                );
        } catch (\Exception $e) {
            return back()->withErrors([
                "generate" => "Error saat generate: " . $e->getMessage(),
            ]);
        }
    }

    public function generateSingle(Request $request, $siswa_nisn)
    {
        $validated = $request->validate([
            "tanggal_masuk" => "nullable|date",
            "asal_sekolah" => "nullable|string|max:100",
            "status" => "required|in:aktif,lulus,pindah,keluar",
            "use_custom_no_induk" => "boolean",
            "keterangan" => "nullable|string",
        ]);

        try {
            $generateService = new \App\Services\BukuIndukGenerateService();
            $bukuInduk = $generateService->generateForSiswa(
                $siswa_nisn,
                $validated,
            );

            return response()->json([
                "success" => true,
                "message" => "Buku induk berhasil dibuat",
                "data" => $bukuInduk,
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    "success" => false,
                    "message" => $e->getMessage(),
                ],
                400,
            );
        }
    }

    public function getPreview(Request $request)
    {
        $ops = $request->user()->userable;
        $sekolah = Sekolah::whereHas("ops", function ($o) use ($ops) {
            $o->where("nip", $ops->nip);
        })->first();

        $generateService = new \App\Services\BukuIndukGenerateService();
        $preview = $generateService->getPreview(
            $sekolah->npsn,
            $request->limit ?? 10,
        );

        return response()->json([
            "preview" => $preview,
            "total_siswa" => Siswa::where("sekolah_id", $sekolah->npsn)
                ->whereDoesntHave("bukuInduk")
                ->where("status", "aktif")
                ->count(),
        ]);
    }
}

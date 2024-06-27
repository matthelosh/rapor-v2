<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Inertia\Inertia;
use Spatie\DbDumper\Databases\MySql;

class BackupController extends Controller
{
    public function home(Request $request)
    {
        return Inertia::render('Dash/Backup', []);
    }

    public function store(Request $request)
    {
        $sekolahId = $request->sekolahId;
        try {
            if ($request->sekolahId) {
                dd($request->sekolahId);
            } else {
                // $backup = Artisan::call('database:backup');
                $name = 'backup-' . Carbon::now()->format('Ymd-His') . '.sql';
                $backup = MySql::create()
                    ->setDumpBinaryPath('/Users/Shared/DBngin/mysql/8.2/bin')
                    ->setDbName(env('DB_DATABASE'))
                    ->setUserName(env('DB_USERNAME'))
                    ->setPassword(env('DB_PASSWORD'))
                    ->dumpToFile(\storage_path() . '/app/public/backups/' . $name);
                if ($backup == null) {
                    return back()->with('message', 'Backup berhasil');
                }
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function tes(Request $request)
    {
        try {
            $snappy = App::make('snappy.pdf');
            $html = '<h1>Halo</h1>';
            $snappy->generateFromHtml($html, storage_path() . '/app/public/backups/testing.pdf');
            $snappy->generate('http://github.com', storage_path() . '/app/public/backups/github.pdf');

            return back()->wiht('message', 'Testing pdf selesai');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

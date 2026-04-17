<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class DatabaseController extends Controller
{
    public function reset()
    {
        try {
            // Hapus semua tabel dan jalankan ulang migrasi serta seeder
            Artisan::call('migrate:fresh', ['--seed' => true]);
            return response()->json(['message' => 'Database berhasil direset.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function backup()
    {
        $filename = "backup-" . date('Y-m-d-H-i-s') . ".sql";
        $path = storage_path('app/backups/' . $filename);

        $user = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $database = env('DB_DATABASE');

        // Menjalankan mysqldump via command line
        $command = "mysqldump --user={$user} --password={$password} {$database} > {$path}";
        exec($command);

        return response()->download($path);
    }

    public function restore(Request $request)
    {
        $request->validate([
            'sql_file' => 'required|file|mimes:sql,txt'
        ]);

        try {
            $sql = file_get_contents($request->file('sql_file')->getRealPath());

            // Disable foreign key checks sementara agar tidak error saat drop table
            DB::unprepared('SET FOREIGN_KEY_CHECKS=0;');
            DB::unprepared($sql);
            DB::unprepared('SET FOREIGN_KEY_CHECKS=1;');

            return response()->json(['message' => 'Database berhasil direstore.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal merestore database: ' . $e->getMessage()], 500);
        }
    }

    public function changeConnection(Request $request)
    {
        $request->validate([
            'db_host' => 'required',
            'db_name' => 'required',
            'db_user' => 'required',
            'db_pass' => 'nullable',
        ]);

        // Logika untuk mengubah file .env
        $path = base_path('.env');
        if (file_exists($path)) {
            file_put_contents($path, str_replace(
                'DB_DATABASE=' . env('DB_DATABASE'),
                'DB_DATABASE=' . $request->db_name,
                file_get_contents($path)
            ));
            file_put_contents($path, str_replace(
                'DB_HOST=' . env('DB_HOST'),
                'DB_HOST=' . $request->db_host,
                file_get_contents($path)
            ));
            file_put_contents($path, str_replace(
                'DB_USERNAME=' . env('DB_USERNAME'),
                'DB_USERNAME=' . $request->db_user,
                file_get_contents($path)
            ));
            file_put_contents($path, str_replace(
                'DB_PASSWORD=' . env('DB_PASSWORD'),
                'DB_PASSWORD=' . $request->db_pass,
                file_get_contents($path)
            ));
        }

        // Clear cache agar konfigurasi baru terbaca
        Artisan::call('config:clear');

        return response()->json(['message' => 'Koneksi berhasil diubah.']);
    }
}

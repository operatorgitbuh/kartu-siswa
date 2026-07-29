<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\DbDumper\Databases\MySql;
use Carbon\Carbon;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
// use App\Models\Student; 
// use App\Models\MapelPilihanMember; 
// use App\Models\Holiday; 

class BackupController extends Controller
{
    private string $backupDir = 'backups';

    /**
     * Tampilkan daftar file backup (Index)
     */
    public function index(Request $request)
{
    // Pastikan nama direktori relatif terhadap storage/app (contoh: 'backups')
    $dir = trim($this->backupDir ?? 'backups', '/');

    // 1. Buat folder jika belum ada
    if (!Storage::disk('local')->exists($dir)) {
        Storage::disk('local')->makeDirectory($dir);
    }

    // 2. Ambil semua file dari disk local
    $files = Storage::disk('local')->files($dir);
    $backups = [];

    foreach ($files as $file) {
        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        
        // Filter file .sql dan .gz
        if (in_array($extension, ['sql', 'gz'])) {
            $backups[] = [
                'name'     => basename($file),
                'size'     => round(Storage::disk('local')->size($file) / 1024 / 1024, 2) . ' MB',
                'date'     => Carbon::createFromTimestamp(Storage::disk('local')->lastModified($file))->format('d M Y H:i:s'),
                'raw_date' => Storage::disk('local')->lastModified($file)
            ];
        }
    }

    // 3. Urutkan dari file backup terbaru
    usort($backups, fn($a, $b) => $b['raw_date'] <=> $a['raw_date']);

    // 4. Manual Pagination
    $perPage = (int) $request->input('per_page', 5);
    $currentPage = LengthAwarePaginator::resolveCurrentPage();
    $collection = collect($backups);

    $currentPageItems = $collection->slice(($currentPage - 1) * $perPage, $perPage)->values();

    $paginatedBackups = new LengthAwarePaginator(
        $currentPageItems,
        $collection->count(),
        $perPage,
        $currentPage,
        [
            'path'  => LengthAwarePaginator::resolveCurrentPath(),
            'query' => $request->query()
        ]
    );

    return view('pages.database.index', [
        'backups' => $paginatedBackups,
        'perPage' => $perPage
    ]);
}
    /**
     * Buat file backup database baru
     */
    public function create()
    {
        try {
            if (!Storage::exists($this->backupDir)) {
                Storage::makeDirectory($this->backupDir);
            }

            $filename = 'backup-' . Carbon::now()->format('Y-m-d_H-i-s') . '.sql';
            $filePath = storage_path("app/{$this->backupDir}/{$filename}");

            // Deteksi path mysqldump di Laragon
            $mysqldumpExe = '';
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $laragonPaths = array_merge(
                    glob('C:/laragon/bin/mysql/*/bin/mysqldump.exe'),
                    glob('C:/laragon/bin/mariadb/*/bin/mysqldump.exe'),
                    glob('D:/laragon/bin/mysql/*/bin/mysqldump.exe'),
                    glob('D:/laragon/bin/mariadb/*/bin/mysqldump.exe')
                );

                if (!empty($laragonPaths)) {
                    $mysqldumpExe = $laragonPaths[0];
                } else {
                    throw new Exception("mysqldump.exe tidak ditemukan di folder Laragon.");
                }
            } else {
                $mysqldumpExe = 'mysqldump';
            }

            // Ambil data dari .env
            $dbName = config('database.connections.mysql.database');
            $dbUser = config('database.connections.mysql.username') ?? 'root';
            $dbPass = config('database.connections.mysql.password');
            $dbHost = config('database.connections.mysql.host') ?? '127.0.0.1';
            $dbPort = config('database.connections.mysql.port') ?? '3306';

            // Format path agar aman di Windows
            $mysqldumpExe = str_replace('/', '\\', $mysqldumpExe);
            $filePathNorm = str_replace('/', '\\', $filePath);

            // Susun parameter password
            $passArg = !empty($dbPass) ? "--password=\"{$dbPass}\"" : "";

            // Susun perintah command line
            // Menambahkan --protocol=tcp untuk mengatasi socket error
            $command = "\"{$mysqldumpExe}\" --host={$dbHost} --port={$dbPort} --user={$dbUser} {$passArg} --protocol=tcp {$dbName} > \"{$filePathNorm}\" 2>&1";

            // Eksekusi
            exec($command, $output, $returnVar);

            // Cek jika eksekusi gagal atau file 0 MB
            if ($returnVar !== 0 || !file_exists($filePath) || filesize($filePath) === 0) {
                $errorLog = file_exists($filePath) ? file_get_contents($filePath) : implode("\n", $output);
                if (file_exists($filePath)) {
                    unlink($filePath); // Hapus file gagal
                }
                throw new Exception("Gagal membuat dump database. Log: " . $errorLog);
            }

            return redirect()->back()->with('success', 'Backup database ' . $dbName . ' berhasil dibuat: ' . $filename);

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuat backup: ' . $e->getMessage());
        }
    }

// Bagian Ubuntu/Linux


// public function create()
// {
//     try {
//         if (!Storage::exists($this->backupDir)) {
//             Storage::makeDirectory($this->backupDir);
//         }

//         $filename = 'backup-' . Carbon::now()->format('Y-m-d_H-i-s') . '.sql';
//         $filePath = storage_path("app/{$this->backupDir}/{$filename}");

//         $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
//         $mysqldumpExe = '';

//         // 1. Deteksi executable mysqldump berdasarkan OS
//         if ($isWindows) {
//             $laragonPaths = array_merge(
//                 glob('C:/laragon/bin/mysql/*/bin/mysqldump.exe'),
//                 glob('C:/laragon/bin/mariadb/*/bin/mysqldump.exe'),
//                 glob('D:/laragon/bin/mysql/*/bin/mysqldump.exe'),
//                 glob('D:/laragon/bin/mariadb/*/bin/mysqldump.exe')
//             );

//             if (!empty($laragonPaths)) {
//                 $mysqldumpExe = str_replace('/', '\\', $laragonPaths[0]);
//             } else {
//                 throw new Exception("mysqldump.exe tidak ditemukan di folder Laragon.");
//             }
//         } else {
//             // Di Ubuntu/Linux: Cari lokasi binary mysqldump atau mariadb-dump
//             if (file_exists('/usr/bin/mysqldump')) {
//                 $mysqldumpExe = '/usr/bin/mysqldump';
//             } elseif (file_exists('/usr/bin/mariadb-dump')) {
//                 $mysqldumpExe = '/usr/bin/mariadb-dump';
//             } else {
//                 $mysqldumpExe = trim(shell_exec('which mysqldump') ?? 'mysqldump');
//             }
//         }

//         // 2. Ambil data database dari config Laravel
//         $dbName = config('database.connections.mysql.database');
//         $dbUser = config('database.connections.mysql.username') ?? 'root';
//         $dbPass = config('database.connections.mysql.password');
//         $dbHost = config('database.connections.mysql.host') ?? '127.0.0.1';
//         $dbPort = config('database.connections.mysql.port') ?? '3306';

//         // Format path output file sesuai OS
//         $filePathNorm = $isWindows ? str_replace('/', '\\', $filePath) : $filePath;

//         // 3. Susun Command CLI yang aman dari warning dan error tablespaces
//         if ($isWindows) {
//             $passArg = !empty($dbPass) ? "--password=" . escapeshellarg($dbPass) : "";
//             $command = "\"{$mysqldumpExe}\" --no-tablespaces --host={$dbHost} --port={$dbPort} --user={$dbUser} {$passArg} --protocol=tcp " . escapeshellarg($dbName) . " > \"{$filePathNorm}\" 2>&1";
//         } else {
//             // Di Ubuntu/Linux: Gunakan export MYSQL_PWD agar bersih dari warning password CLI
//             $passwordEnv = !empty($dbPass) ? "export MYSQL_PWD=" . escapeshellarg($dbPass) . "; " : "";
//             $command = "{$passwordEnv}\"{$mysqldumpExe}\" --no-tablespaces --host={$dbHost} --port={$dbPort} --user={$dbUser} --protocol=tcp " . escapeshellarg($dbName) . " > \"{$filePathNorm}\" 2>&1";
//         }

//         // 4. Eksekusi
//         exec($command, $output, $returnVar);

//         // 5. Cek Hasil Dump
//         if ($returnVar !== 0 || !file_exists($filePath) || filesize($filePath) === 0) {
//             $errorLog = file_exists($filePath) ? file_get_contents($filePath) : implode("\n", $output);
            
//             if (file_exists($filePath)) {
//                 unlink($filePath); // Hapus file kosong/corrupt
//             }
            
//             throw new Exception("Gagal membuat dump database. Log error: " . trim($errorLog));
//         }

//         return redirect()->back()->with('success', 'Backup database ' . $dbName . ' berhasil dibuat: ' . $filename);

//     } catch (Exception $e) {
//         return redirect()->back()->with('error', 'Gagal membuat backup: ' . $e->getMessage());
//     }
// }

    /**
     * Unduh file backup
     */
    public function download($filename)
    {
        try {
            // 1. Bersihkan path & nama file
            $dir = trim($this->backupDir ?? 'backups', '/');
            $cleanFilename = basename($filename); // Ambil nama file murni saja

            $relativePath = "{$dir}/{$cleanFilename}";

            // 2. Cek keberadaan file di disk local (storage/app)
            if (Storage::disk('local')->exists($relativePath)) {
                // 3. Download file langsung lewat Storage Facade
                return Storage::disk('local')->download($relativePath, $cleanFilename, [
                    'Content-Type' => 'application/x-sql',
                ]);
            }

            return redirect()->back()->with('error', 'File backup tidak ditemukan.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengunduh file: ' . $e->getMessage());
        }
    }

    /**
     * Restore database dari file yang dipilih
     */
    public function restore($filename)
{
    try {
        $filePath = storage_path("app/{$this->backupDir}/{$filename}");

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File backup tidak ditemukan.');
        }

        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        // 1. Ekstraksi otomatis jika file backup berupa .gz
        if ($extension === 'gz') {
            $decompressedPath = storage_path("app/{$this->backupDir}/temp_restore_" . time() . '.sql');
            
            $gz = gzopen($filePath, 'rb');
            $out = fopen($decompressedPath, 'wb');
            while (!gzeof($gz)) {
                fwrite($out, gzread($gz, 4096));
            }
            fclose($out);
            gzclose($gz);

            $filePath = $decompressedPath;
        }

        // 2. Auto-Scan lokasi mysql.exe di Laragon (Cek Drive C, D, E)
        $mysqlPath = null;
        $laragonDrives = ['C', 'D', 'E'];

        foreach ($laragonDrives as $drive) {
            $basePath = "{$drive}:\laragon\bin\mysql";
            if (is_dir($basePath)) {
                $folders = scandir($basePath);
                foreach ($folders as $folder) {
                    if ($folder !== '.' && $folder !== '..') {
                        $executable = "{$basePath}\\{$folder}\\bin\\mysql.exe";
                        if (file_exists($executable)) {
                            $mysqlPath = $executable;
                            break 2;
                        }
                    }
                }
            }
        }

        // 3. OPSI A: Restore via CLI jika mysql.exe Laragon ditemukan
        if ($mysqlPath && file_exists($mysqlPath)) {
            $dbHost = config('database.connections.mysql.host');
            $dbPort = config('database.connections.mysql.port', '3306');
            $dbName = config('database.connections.mysql.database');
            $dbUser = config('database.connections.mysql.username');
            $dbPass = config('database.connections.mysql.password');

            $passwordCmd = !empty($dbPass) ? "--password=" . escapeshellarg($dbPass) : "";
            $command = "\"{$mysqlPath}\" --host=" . escapeshellarg($dbHost) . " --port=" . escapeshellarg($dbPort) . " --user=" . escapeshellarg($dbUser) . " {$passwordCmd} " . escapeshellarg($dbName) . " < " . escapeshellarg($filePath) . " 2>&1";

            exec($command, $output, $returnVar);

            // Bersihkan file temp jika tadi dari .gz
            if (isset($decompressedPath) && file_exists($decompressedPath)) {
                unlink($decompressedPath);
            }

            if ($returnVar === 0) {
                return redirect()->back()->with('success', 'Database berhasil di-restore dari ' . $filename);
            }
        }

        // 4. OPSI B: Fallback Pure PHP (PDO) jika CLI gagal / tidak ditemukan
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::unprepared(file_get_contents($filePath));
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Bersihkan file temp jika tadi dari .gz
        if (isset($decompressedPath) && file_exists($decompressedPath)) {
            unlink($decompressedPath);
        }

        return redirect()->back()->with('success', 'Database berhasil di-restore dari ' . $filename);

    } catch (\Exception $e) {
        // Pastikan FK check dinyalakan kembali jika error
        try { \DB::statement('SET FOREIGN_KEY_CHECKS=1;'); } catch (\Exception $ex) {}

        if (isset($decompressedPath) && file_exists($decompressedPath)) {
            unlink($decompressedPath);
        }

        return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    }
}

// Untuk Ubuntu/Linux,

// public function restore($filename)
// {
//     $decompressedPath = null;

//     try {
//         $filePath = storage_path("app/{$this->backupDir}/{$filename}");

//         if (!file_exists($filePath)) {
//             return redirect()->back()->with('error', 'File backup tidak ditemukan.');
//         }

//         $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

//         // 1. Ekstraksi otomatis jika file backup berupa .gz
//         if ($extension === 'gz') {
//             $decompressedPath = storage_path("app/{$this->backupDir}/temp_restore_" . time() . '.sql');
            
//             $gz = gzopen($filePath, 'rb');
//             $out = fopen($decompressedPath, 'wb');
//             while (!gzeof($gz)) {
//                 fwrite($out, gzread($gz, 4096));
//             }
//             fclose($out);
//             gzclose($gz);

//             $filePath = $decompressedPath;
//         }

//         $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
//         $mysqlPath = null;

//         // 2. Deteksi Executable Client MySQL berdasarkan OS
//         if ($isWindows) {
//             $laragonDrives = ['C', 'D', 'E'];
//             foreach ($laragonDrives as $drive) {
//                 $basePath = "{$drive}:\laragon\bin\mysql";
//                 if (is_dir($basePath)) {
//                     $folders = scandir($basePath);
//                     foreach ($folders as $folder) {
//                         if ($folder !== '.' && $folder !== '..') {
//                             $executable = "{$basePath}\\{$folder}\\bin\\mysql.exe";
//                             if (file_exists($executable)) {
//                                 $mysqlPath = $executable;
//                                 break 2;
//                             }
//                         }
//                     }
//                 }
//             }
//         } else {
//             // Di Ubuntu / Linux: Cari binary mysql atau mariadb
//             if (file_exists('/usr/bin/mysql')) {
//                 $mysqlPath = '/usr/bin/mysql';
//             } elseif (file_exists('/usr/bin/mariadb')) {
//                 $mysqlPath = '/usr/bin/mariadb';
//             } else {
//                 $mysqlPath = trim(shell_exec('which mysql') ?? 'mysql');
//             }
//         }

//         // 3. OPSI A: Restore via CLI (Rekomendasi Utama)
//         if ($mysqlPath && ($isWindows ? file_exists($mysqlPath) : true)) {
//             $dbHost = config('database.connections.mysql.host') ?? '127.0.0.1';
//             $dbPort = config('database.connections.mysql.port', '3306');
//             $dbName = config('database.connections.mysql.database');
//             $dbUser = config('database.connections.mysql.username') ?? 'root';
//             $dbPass = config('database.connections.mysql.password');

//             $filePathNorm = $isWindows ? str_replace('/', '\\', $filePath) : $filePath;
//             $passwordCmd = !empty($dbPass) ? "--password=" . escapeshellarg($dbPass) : "";

//             // Menambahkan --protocol=tcp untuk mencegah socket error di Linux
//             $command = "\"{$mysqlPath}\" --host=" . escapeshellarg($dbHost) . " --port=" . escapeshellarg($dbPort) . " --user=" . escapeshellarg($dbUser) . " {$passwordCmd} --protocol=tcp " . escapeshellarg($dbName) . " < " . escapeshellarg($filePathNorm) . " 2>&1";

//             exec($command, $output, $returnVar);

//             // Hapus file temporary .gz jika ada
//             if ($decompressedPath && file_exists($decompressedPath)) {
//                 unlink($decompressedPath);
//             }

//             if ($returnVar === 0) {
//                 return redirect()->back()->with('success', 'Database berhasil di-restore dari ' . $filename);
//             }

//             // Jika CLI gagal di Ubuntu, lempar exception lengkap dengan log-nya
//             $errorMsg = !empty($output) ? implode("\n", $output) : "Exit code: {$returnVar}";
//             throw new \Exception("Gagal restore via CLI: " . $errorMsg);
//         }

//         // 4. OPSI B: Fallback Pure PHP (PDO) hanya jika binary CLI benar-benar tidak ditemukan
//         \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
//         \DB::unprepared(file_get_contents($filePath));
//         \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

//         if ($decompressedPath && file_exists($decompressedPath)) {
//             unlink($decompressedPath);
//         }

//         return redirect()->back()->with('success', 'Database berhasil di-restore dari ' . $filename);

//     } catch (\Exception $e) {
//         try { \DB::statement('SET FOREIGN_KEY_CHECKS=1;'); } catch (\Exception $ex) {}

//         if ($decompressedPath && file_exists($decompressedPath)) {
//             unlink($decompressedPath);
//         }

//         return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
//     }
// }



    public function restoreFromUploadedFile(Request $request)
{
    $request->validate([
        'backup_file' => 'required|file|mimes:sql,gz,txt|max:101200', // max 100MB
    ]);

    try {
        $file = $request->file('backup_file');
        $filePath = $file->getRealPath();
        $extension = strtolower($file->getClientOriginalExtension());

        // 1. Ekstraksi otomatis jika file berupa .gz
        if ($extension === 'gz') {
            $decompressedPath = storage_path('app/temp_restore_' . time() . '.sql');
            
            $gz = gzopen($filePath, 'rb');
            $out = fopen($decompressedPath, 'wb');
            while (!gzeof($gz)) {
                fwrite($out, gzread($gz, 4096));
            }
            fclose($out);
            gzclose($gz);

            $filePath = $decompressedPath;
        }

        // 2. Cari otomatis path mysql.exe di Laragon (Cek Drive C, D, E)
        $mysqlPath = null;
        $laragonDrives = ['C', 'D', 'E'];

        foreach ($laragonDrives as $drive) {
            $basePath = "{$drive}:\laragon\bin\mysql";
            if (is_dir($basePath)) {
                // Scan folder versi mysql di dalam laragon/bin/mysql/
                $folders = scandir($basePath);
                foreach ($folders as $folder) {
                    if ($folder !== '.' && $folder !== '..') {
                        $executable = "{$basePath}\\{$folder}\\bin\\mysql.exe";
                        if (file_exists($executable)) {
                            $mysqlPath = $executable;
                            break 2;
                        }
                    }
                }
            }
        }

        // 3. OPSI A: Restore via CLI jika mysql.exe ditemukan di Laragon
        if ($mysqlPath && file_exists($mysqlPath)) {
            $dbName = config('database.connections.mysql.database');
            $dbUser = config('database.connections.mysql.username');
            $dbHost = config('database.connections.mysql.host');
            $dbPort = config('database.connections.mysql.port', '3306');
            $dbPassword = config('database.connections.mysql.password');

            $passwordCmd = !empty($dbPassword) ? "--password=" . escapeshellarg($dbPassword) : "";
            $command = "\"{$mysqlPath}\" --host=" . escapeshellarg($dbHost) . " --port=" . escapeshellarg($dbPort) . " --user=" . escapeshellarg($dbUser) . " {$passwordCmd} " . escapeshellarg($dbName) . " < " . escapeshellarg($filePath) . " 2>&1";

            exec($command, $output, $returnVar);

            if (isset($decompressedPath) && file_exists($decompressedPath)) {
                unlink($decompressedPath);
            }

            if ($returnVar === 0) {
                return redirect()->back()->with('success', 'Database berhasil di-restore!');
            }
        }

        // 4. OPSI B: Fallback Pure PHP (PDO) jika CLI tidak ditemukan
        // Menonaktifkan foreign key checks sementara agar restore lancar
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::unprepared(file_get_contents($filePath));
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        if (isset($decompressedPath) && file_exists($decompressedPath)) {
            unlink($decompressedPath);
        }

        return redirect()->back()->with('success', 'Database berhasil di-restore!');

    } catch (\Exception $e) {
        // Nyalakan kembali foreign key check jika terjadi error
        try { \DB::statement('SET FOREIGN_KEY_CHECKS=1;'); } catch (\Exception $ex) {}

        if (isset($decompressedPath) && file_exists($decompressedPath)) {
            unlink($decompressedPath);
        }

        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

//Untuk Ubuntu

// public function restoreFromUploadedFile(Request $request)
// {
//     $request->validate([
//         'backup_file' => 'required|file|mimes:sql,gz,txt|max:101200', // max 100MB
//     ]);

//     $decompressedPath = null;

//     try {
//         $file = $request->file('backup_file');
//         $filePath = $file->getRealPath();
//         $extension = strtolower($file->getClientOriginalExtension());

//         // 1. Ekstraksi otomatis jika file berupa .gz
//         if ($extension === 'gz') {
//             $decompressedPath = storage_path('app/temp_restore_' . time() . '.sql');
            
//             $gz = gzopen($filePath, 'rb');
//             $out = fopen($decompressedPath, 'wb');
//             while (!gzeof($gz)) {
//                 fwrite($out, gzread($gz, 4096));
//             }
//             fclose($out);
//             gzclose($gz);

//             $filePath = $decompressedPath;
//         }

//         $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
//         $mysqlPath = null;

//         // 2. Deteksi Executable Client MySQL berdasarkan OS
//         if ($isWindows) {
//             $laragonDrives = ['C', 'D', 'E'];
//             foreach ($laragonDrives as $drive) {
//                 $basePath = "{$drive}:\laragon\bin\mysql";
//                 if (is_dir($basePath)) {
//                     $folders = scandir($basePath);
//                     foreach ($folders as $folder) {
//                         if ($folder !== '.' && $folder !== '..') {
//                             $executable = "{$basePath}\\{$folder}\\bin\\mysql.exe";
//                             if (file_exists($executable)) {
//                                 $mysqlPath = $executable;
//                                 break 2;
//                             }
//                         }
//                     }
//                 }
//             }
//         } else {
//             // Di Ubuntu / Linux: Cari binary mysql atau mariadb
//             if (file_exists('/usr/bin/mysql')) {
//                 $mysqlPath = '/usr/bin/mysql';
//             } elseif (file_exists('/usr/bin/mariadb')) {
//                 $mysqlPath = '/usr/bin/mariadb';
//             } else {
//                 $mysqlPath = trim(shell_exec('which mysql') ?? 'mysql');
//             }
//         }

//         // 3. OPSI A: Restore via CLI (Mendukung Windows & Ubuntu)
//         if ($mysqlPath && ($isWindows ? file_exists($mysqlPath) : true)) {
//             $dbName = config('database.connections.mysql.database');
//             $dbUser = config('database.connections.mysql.username') ?? 'root';
//             $dbHost = config('database.connections.mysql.host') ?? '127.0.0.1';
//             $dbPort = config('database.connections.mysql.port', '3306');
//             $dbPassword = config('database.connections.mysql.password');

//             if ($isWindows) {
//                 // Perintah untuk Windows (Laragon)
//                 $passwordCmd = !empty($dbPassword) ? "--password=" . escapeshellarg($dbPassword) : "";
//                 $command = "\"{$mysqlPath}\" --host=" . escapeshellarg($dbHost) . " --port=" . escapeshellarg($dbPort) . " --user=" . escapeshellarg($dbUser) . " {$passwordCmd} --protocol=tcp " . escapeshellarg($dbName) . " < " . escapeshellarg($filePath) . " 2>&1";
//             } else {
//                 // Perintah untuk Ubuntu / Linux (Menggunakan MYSQL_PWD agar aman dari warning)
//                 $passwordEnv = !empty($dbPassword) ? "export MYSQL_PWD=" . escapeshellarg($dbPassword) . "; " : "";
//                 $command = "{$passwordEnv}\"{$mysqlPath}\" --host=" . escapeshellarg($dbHost) . " --port=" . escapeshellarg($dbPort) . " --user=" . escapeshellarg($dbUser) . " --protocol=tcp " . escapeshellarg($dbName) . " < " . escapeshellarg($filePath) . " 2>&1";
//             }

//             exec($command, $output, $returnVar);

//             if ($decompressedPath && file_exists($decompressedPath)) {
//                 unlink($decompressedPath);
//             }

//             if ($returnVar === 0) {
//                 return redirect()->back()->with('success', 'Database berhasil di-restore via CLI!');
//             }

//             // Jika CLI gagal, tangkap log error-nya
//             $errorMsg = !empty($output) ? implode("\n", $output) : "Exit code: {$returnVar}";
//             throw new \Exception("Gagal restore via CLI: " . $errorMsg);
//         }

//         // 4. OPSI B: Fallback Pure PHP (PDO) jika binary CLI benar-benar tidak ditemukan
//         \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
//         \DB::unprepared(file_get_contents($filePath));
//         \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

//         if ($decompressedPath && file_exists($decompressedPath)) {
//             unlink($decompressedPath);
//         }

//         return redirect()->back()->with('success', 'Database berhasil di-restore via PHP!');

//     } catch (\Exception $e) {
//         try { \DB::statement('SET FOREIGN_KEY_CHECKS=1;'); } catch (\Exception $ex) {}

//         if ($decompressedPath && file_exists($decompressedPath)) {
//             unlink($decompressedPath);
//         }

//         return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
//     }
// }

    // public function destroy($filename)
    // {
    //     $path = "{$this->backupDir}/{$filename}";

    //     if (Storage::exists($path)) {
    //         Storage::delete($path);
    //         return redirect()->back()->with('success', 'File backup berhasil dihapus.');
    //     }

    //     return redirect()->back()->with('error', 'File tidak ditemukan.');
    // }
    public function destroy($filename)
{
    try {
        // 1. Bersihkan nama direktori dan filename agar tidak ada double slash atau path traversal
        $dir = trim($this->backupDir ?? 'backups', '/');
        $cleanFilename = basename($filename); // Keamanan: hindari '../'

        // 2. Susun path relatif terhadap storage/app
        $relativePath = "{$dir}/{$cleanFilename}";

        // 3. Cek keberadaan file di disk local (storage/app)
        if (Storage::disk('local')->exists($relativePath)) {
            Storage::disk('local')->delete($relativePath);

            return redirect()->back()->with('success', 'File backup ' . $cleanFilename . ' berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'File backup tidak ditemukan di path: ' . $relativePath);

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal menghapus file: ' . $e->getMessage());
    }
}
}

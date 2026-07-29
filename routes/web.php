<?php

use App\Http\Controllers\BackgroundController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('dashboard');
// });



Route::resource('/', LandingController::class);
Route::get('/check-card/{nisn}', [LandingController::class, 'checkStatus']);

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});


Route::middleware('auth')->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


    Route::middleware(['role:ADMIN'])->group(function () {

        Route::resource('/dashboard', DashboardController::class);

        Route::post('/classrooms/imports', [ClassroomController::class, 'imports'])->name('classrooms.imports');
        Route::resource('/classrooms', ClassroomController::class);

        Route::post('/students/imports', [StudentController::class, 'imports'])->name('students.imports');
        Route::resource('/students', StudentController::class);
        Route::post('/students/naik-kelas', [StudentController::class, 'naikKelas'])->name('students.naik-kelas');
        Route::post('/students/process-ploting', [StudentController::class, 'processPloting'])->name('students.process-ploting');

        Route::resource('schools', SchoolController::class)->only(['index', 'update']);

        Route::resource('/backgrounds', BackgroundController::class);

        Route::get('/card-students/download-bulk', [CardController::class, 'downloadBulk'])->name('card-students.download-bulk');
        Route::get('/card-students/{id}/downloadPDF', [App\Http\Controllers\CardController::class, 'downloadPDF'])->name('card-students.downloadPDF');
        Route::resource('/card-students', CardController::class);
        
        Route::get('/users/my-profile', [UserController::class, 'IndexProfile'])->name('users.indexProfile');
        Route::put('/users/update', [UserController::class, 'updateProfile'])->name('users.updateProfile');
        
        Route::resource('/users', UserController::class);

        Route::resource('/roles', RoleController::class);
        Route::resource('/permissions', PermissionController::class);

        Route::get('/backup-restore-database', [BackupController::class, 'index'])->name('backup-restore-database.index');
        Route::post('/backup-restore-database/create', [BackupController::class, 'create'])->name('backup-restore-database.create');
        Route::get('/download/{filename}', [BackupController::class, 'download'])->name('backup-restore-database.download');
        Route::post('/backup-restore-database/restore/{filename}', [BackupController::class, 'restore'])
        ->name('backup-restore-database.restore')
        ->where('filename', '.*');
        Route::post('/backup-restore-database/restore-file', [BackupController::class, 'restoreFromUploadedFile'])->name('backup-restore-database.restore-file');
        Route::delete('/delete/{filename}', [BackupController::class, 'destroy'])->name('backup-restore-database.destroy');
    });
    
    Route::middleware(['role:WALI_KELAS'])->group(function () {
        Route::get('/wali-kelas/dashboard', [DashboardController::class, 'indexWakel'])->name('wali-kelas.dashboard');
        Route::get('/wali-kelas/students', [StudentController::class, 'indexWakel'])->name('wali-kelas.students');
        Route::put('/wali-kelas/students/{student}', [StudentController::class, 'updateWakel'])
        ->name('wali-kelas.students.update');
        Route::get('/wali-kelas/card-students', [CardController::class, 'indexWakel'])->name('wali-kelas.card-students');
        Route::get('/wali-kelas/card-students/download-bulk', [CardController::class, 'WakelBulk'])->name('wali-kelas.card-students.download-bulk');
        Route::get('/wali-kelas/card-students/{id}/WakelPDF', [App\Http\Controllers\CardController::class, 'WakelPDF'])->name('wali-kelas.card-students.WakelPDF');
        Route::get('/wali_kelas/users', [UserController::class,'profile'])->name('wali-kelas.users');
        Route::put('/wali_kelas/users/{id}', [UserController::class,'updateWakel'])->name('wali-kelas.users.updateWakel');
    });
});

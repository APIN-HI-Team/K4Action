<?php

use App\Http\Livewire\Pages;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => ['auth']], function () {

    Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
    ])->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');
    });

    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');

    Route::get('/performance', [App\Http\Controllers\HomeController::class, 'performance'])->name('performance');

    Route::get('monitoring/treatment_dashboard', [App\Http\Controllers\MonitoringController::class,'treatmentDashboard'])->name('treatment');



    Route::get('/reports/vl/vl_cascade', [App\Http\Controllers\ReportController::class,'vlcascade'])->name('vlcascade');

    Route::get('monitoring/biometrics_report', [App\Http\Controllers\MonitoringController::class,'pbsDashboard'])->name('pbs');

    Route::get('monitoring/quality_of_care', [App\Http\Controllers\MonitoringController::class,'regimenDashboard'])->name('regimen');

    Route::get('monitoring/mortality', [App\Http\Controllers\MonitoringController::class,'mortality'])->name('mortality');

    Route::get('monitoring/ahd', [App\Http\Controllers\MonitoringController::class,'ahd'])->name('ahd');
    Route::get('monitoring/dsd', [App\Http\Controllers\MonitoringController::class,'dsd'])->name('dsd');

    Route::get('monitoring/appointment_dashboard', [App\Http\Controllers\MonitoringController::class,'appointmentDashboard'])->name('appointment');
    Route::get('/upload', [App\Http\Controllers\UploadController::class,'index']);
    Route::get('/progress', [App\Http\Controllers\UploadController::class,'progress']);
    Route::post('/upload/file', [App\Http\Controllers\UploadController::class,'uploadFile'])->name('processFile');
    Route::get('/progress/data', [App\Http\Controllers\UploadController::class,'progressUpload'])->name('csvStoreProcess');

    Route::get('monitoring/vl', [App\Http\Controllers\MonitoringController::class,'vlDashboard'])->name('vldashboard');


});



Route::get('phpinfo', fn () => phpinfo());





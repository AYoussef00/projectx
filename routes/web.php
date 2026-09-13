<?php

use App\Http\Controllers\CertificateDashboardController;
use App\Support\CertificateSettings;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('certificates.clearance-web', [
        'certificate' => CertificateSettings::all(),
    ]);
})->name('home');

Route::view('/sample/certificate', 'certificates.clearance-sample')
    ->name('sample.certificate');

Route::get('/sample/certificate.pdf', function () {
    $path = public_path('certificates/sample-clearance.pdf');

    abort_unless(is_file($path), 404);

    return response()->file($path, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="شهادة-مخالصة-سامبل.pdf"',
    ]);
})->name('sample.certificate.pdf');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [CertificateDashboardController::class, 'edit'])->name('dashboard');
    Route::put('dashboard', [CertificateDashboardController::class, 'update'])->name('dashboard.update');
});

require __DIR__.'/settings.php';

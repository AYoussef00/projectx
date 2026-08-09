<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'certificates.clearance-web')->name('home');

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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';

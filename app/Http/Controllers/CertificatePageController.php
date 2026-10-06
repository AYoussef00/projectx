<?php

namespace App\Http\Controllers;

use App\Support\CertificateSettings;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CertificatePageController extends Controller
{
    public function show(string $code): View
    {
        abort_unless(CertificateSettings::exists($code), 404);

        return view('certificates.clearance-web', [
            'code' => $code,
            'certificate' => CertificateSettings::for($code),
        ]);
    }

    public function pdf(string $code): View
    {
        abort_unless(CertificateSettings::exists($code) && CertificateSettings::resolvedPdfPath($code), 404);

        return view('certificates.pdf-viewer', [
            'code' => $code,
        ]);
    }

    public function pdfFile(string $code): BinaryFileResponse
    {
        $path = CertificateSettings::resolvedPdfPath($code);

        abort_unless(CertificateSettings::exists($code) && $path, 404);

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="وزارة-العمل-'.$code.'.pdf"',
        ]);
    }
}

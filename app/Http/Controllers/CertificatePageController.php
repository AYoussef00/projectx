<?php

namespace App\Http\Controllers;

use App\Support\CertificateSettings;
use Illuminate\View\View;

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
}

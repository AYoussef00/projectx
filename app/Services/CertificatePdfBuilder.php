<?php

namespace App\Services;

use App\Support\CertificateSettings;
use RuntimeException;
use Symfony\Component\Process\Process;

class CertificatePdfBuilder
{
    public function rebuild(): void
    {
        $python = base_path('.venv-pdf/bin/python');
        $script = base_path('scripts/build_certificate_pdf.py');
        $settingsPath = CertificateSettings::path();

        if (! is_file($python)) {
            throw new RuntimeException('Python venv not found at .venv-pdf/bin/python');
        }

        if (! is_file($script)) {
            throw new RuntimeException('PDF build script not found.');
        }

        $process = new Process([
            $python,
            $script,
            '--settings',
            $settingsPath,
        ], base_path());

        $process->setTimeout(120);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new RuntimeException(
                'Failed to rebuild certificate PDF: '.$process->getErrorOutput().$process->getOutput()
            );
        }
    }
}

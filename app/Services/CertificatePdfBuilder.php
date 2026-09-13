<?php

namespace App\Services;

use App\Support\CertificateSettings;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class CertificatePdfBuilder
{
    /**
     * @return array{ok: bool, message: string}
     */
    public function rebuild(): array
    {
        $python = $this->pythonBinary();
        $script = base_path('scripts/build_certificate_pdf.py');
        $settingsPath = CertificateSettings::path();

        if ($python === null) {
            return [
                'ok' => false,
                'message' => 'Python غير متوفر على السيرفر (.venv-pdf). تم حفظ بيانات الصفحة فقط.',
            ];
        }

        if (! is_file($script)) {
            return [
                'ok' => false,
                'message' => 'سكربت بناء الـ PDF غير موجود. تم حفظ بيانات الصفحة فقط.',
            ];
        }

        if (! is_file(storage_path('app/sample-ref/source.pdf'))) {
            return [
                'ok' => false,
                'message' => 'ملف المصدر source.pdf غير موجود. تم حفظ بيانات الصفحة فقط.',
            ];
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
            $details = trim($process->getErrorOutput().' '.$process->getOutput());
            Log::error('Certificate PDF rebuild failed', ['output' => $details]);

            return [
                'ok' => false,
                'message' => 'تم حفظ الصفحة الرئيسية، لكن تحديث الـ PDF فشل. راجع لوج السيرفر.',
            ];
        }

        return [
            'ok' => true,
            'message' => 'تم تحديث بيانات الشهادة في الصفحة الرئيسية وملف الـ PDF.',
        ];
    }

    private function pythonBinary(): ?string
    {
        $candidates = [
            base_path('.venv-pdf/bin/python'),
            base_path('.venv-pdf/bin/python3'),
            '/usr/bin/python3',
        ];

        foreach ($candidates as $candidate) {
            if (is_file($candidate) && is_executable($candidate)) {
                return $candidate;
            }
        }

        return null;
    }
}

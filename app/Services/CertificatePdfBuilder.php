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
        $sourcePath = storage_path('app/sample-ref/source.pdf');
        $outputPath = public_path('certificates/sample-clearance.pdf');

        if ($python === null) {
            return [
                'ok' => false,
                'message' => 'Python غير متوفر. نفّذ على السيرفر: python3 -m venv .venv-pdf && .venv-pdf/bin/pip install pymupdf arabic-reshaper python-bidi qrcode pillow',
            ];
        }

        if (! is_file($script)) {
            return [
                'ok' => false,
                'message' => 'سكربت بناء الـ PDF غير موجود: scripts/build_certificate_pdf.py',
            ];
        }

        if (! is_file($sourcePath)) {
            return [
                'ok' => false,
                'message' => 'ملف المصدر غير موجود: storage/app/sample-ref/source.pdf — ارفعه مع المشروع ثم أعد المحاولة.',
            ];
        }

        if (! is_dir(dirname($outputPath)) || ! is_writable(dirname($outputPath))) {
            return [
                'ok' => false,
                'message' => 'مجلد public/certificates غير قابل للكتابة لمستخدم الويب (www-data).',
            ];
        }

        $before = is_file($outputPath) ? filemtime($outputPath) : 0;

        $process = new Process([
            $python,
            $script,
            '--settings',
            $settingsPath,
        ], base_path(), [
            'PATH' => getenv('PATH') ?: '/usr/local/bin:/usr/bin:/bin',
            'HOME' => sys_get_temp_dir(),
            'LANG' => 'C.UTF-8',
        ]);

        $process->setTimeout(180);
        $process->run();

        $details = trim($process->getErrorOutput()."\n".$process->getOutput());

        if (! $process->isSuccessful()) {
            Log::error('Certificate PDF rebuild failed', [
                'python' => $python,
                'output' => $details,
            ]);

            $short = mb_substr(preg_replace('/\s+/', ' ', $details) ?: 'unknown error', 0, 300);

            return [
                'ok' => false,
                'message' => 'فشل تحديث الـ PDF: '.$short,
            ];
        }

        clearstatcache(true, $outputPath);
        $after = is_file($outputPath) ? filemtime($outputPath) : 0;

        if ($after <= $before) {
            return [
                'ok' => false,
                'message' => 'سكربت الـ PDF اشتغل لكن الملف لم يتحدث. تحقق من صلاحيات public/certificates.',
            ];
        }

        return [
            'ok' => true,
            'message' => 'تم تحديث الصفحة الرئيسية وملف الـ PDF بنجاح.',
        ];
    }

    private function pythonBinary(): ?string
    {
        $candidates = [
            base_path('.venv-pdf/bin/python'),
            base_path('.venv-pdf/bin/python3'),
        ];

        foreach ($candidates as $candidate) {
            if (is_file($candidate) && is_executable($candidate)) {
                return $candidate;
            }
        }

        return null;
    }
}

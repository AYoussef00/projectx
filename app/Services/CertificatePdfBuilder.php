<?php

namespace App\Services;

use App\Support\CertificateSettings;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class CertificatePdfBuilder
{
    /**
     * @return array{ok: bool, message: string}
     */
    public function rebuild(string $code): array
    {
        $python = $this->pythonBinary();
        $script = base_path('scripts/build_certificate_pdf.py');
        $sourcePath = storage_path('app/sample-ref/source.pdf');
        $outputPath = CertificateSettings::pdfPath($code);
        $settingsPath = CertificateSettings::file($code);

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

        if (! is_file($settingsPath)) {
            return [
                'ok' => false,
                'message' => 'بيانات الصفحة غير موجودة.',
            ];
        }

        if (! is_dir(dirname($outputPath)) || ! is_writable(dirname($outputPath))) {
            return [
                'ok' => false,
                'message' => 'مجلد public/certificates غير قابل للكتابة لمستخدم الويب (www-data).',
            ];
        }

        CertificateSettings::update($code, [
            'qr_url' => CertificateSettings::publicUrl($code),
        ]);

        $process = new Process([
            $python,
            $script,
            '--settings',
            $settingsPath,
            '--output',
            $outputPath,
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
                'code' => $code,
                'output' => $details,
            ]);

            $short = mb_substr(preg_replace('/\s+/', ' ', $details) ?: 'unknown error', 0, 300);

            return [
                'ok' => false,
                'message' => 'فشل تحديث الـ PDF: '.$short,
            ];
        }

        clearstatcache(true, $outputPath);

        if (! is_file($outputPath)) {
            return [
                'ok' => false,
                'message' => 'سكربت الـ PDF اشتغل لكن الملف لم يُنشأ. تحقق من صلاحيات public/certificates.',
            ];
        }

        if ($code === CertificateSettings::DEFAULT_CODE) {
            File::copy($outputPath, public_path('certificates/sample-clearance.pdf'));
            $qrPath = CertificateSettings::qrPath($code);
            if (is_file($qrPath)) {
                File::copy($qrPath, public_path('certificates/qr.png'));
            }
        }

        return [
            'ok' => true,
            'message' => 'تم تحديث الصفحة /'.$code.' وملف الـ PDF بنجاح.',
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

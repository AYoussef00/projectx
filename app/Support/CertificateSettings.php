<?php

namespace App\Support;

use Illuminate\Support\Facades\File;

class CertificateSettings
{
    public const DEFAULT_CODE = '19028001';

    public static function directory(): string
    {
        return storage_path('app/certificates');
    }

    public static function indexPath(): string
    {
        return self::directory().'/index.json';
    }

    public static function file(string $code): string
    {
        return self::directory().'/'.$code.'.json';
    }

    public static function pdfPath(string $code): string
    {
        return storage_path('app/certificates/'.$code.'.pdf');
    }

    public static function resolvedPdfPath(string $code): ?string
    {
        foreach ([self::pdfPath($code), public_path('certificates/'.$code.'.pdf')] as $path) {
            if (is_file($path)) {
                return $path;
            }
        }

        return null;
    }

    public static function qrPath(string $code): string
    {
        return public_path('certificates/'.$code.'-qr.png');
    }

    /**
     * @return array<string, string>
     */
    public static function defaults(): array
    {
        return [
            'contractor_name' => 'شركة الحسام للمقاولات العامة والتوريدات',
            'operation_description' => 'أعمال الطرق لذمة زيادة القدرة التصريفية لخور وقناة مفيض توشكي ( قناة المفيض التكميلية) من محطة 13+250 الي محطة 13+500 القطاع الثاني',
            'works_label' => 'اعمال/',
            'works_code' => '15740',
            'contract_number' => '261008004796',
            'extract_code' => '1523469',
            'extract_value' => '8442850',
            'extract_type' => 'أول وختامي',
            'extract_start_date' => '24-02-2026',
            'extract_end_date' => '24-04-2026',
            'receipt_no' => '20260818436122',
            'amount' => '25413',
            'tafqeet' => 'فقط خمسة وعشرون الفا وربعمائة وثلاثة عشر جنيها مصري لا غير',
            'ministry_code' => '421165',
            'clearance_number' => '2253461',
            'password' => 'F22z5s941e',
            'approval_date' => '24-08-2026',
            'qr_url' => 'https://inform.menpowerr-eg.co/'.self::DEFAULT_CODE,
            'footer_url' => 'https://inform.manpower.gov.eg/',
        ];
    }

    public static function boot(): void
    {
        if (File::exists(self::indexPath())) {
            return;
        }

        $data = self::defaults();
        $legacy = storage_path('app/certificate-settings.json');

        if (File::exists($legacy)) {
            /** @var array<string, mixed> $decoded */
            $decoded = json_decode(File::get($legacy), true) ?: [];
            $data = array_merge($data, array_map('strval', $decoded));
        }

        $data['qr_url'] = self::publicUrl(self::DEFAULT_CODE);
        self::write(self::DEFAULT_CODE, $data);
        self::writeIndex([self::DEFAULT_CODE]);
        self::ensurePdfCopy(self::DEFAULT_CODE, public_path('certificates/sample-clearance.pdf'));
    }

    /**
     * @return list<string>
     */
    public static function codes(): array
    {
        self::boot();

        /** @var list<string> $codes */
        $codes = json_decode(File::get(self::indexPath()), true) ?: [];

        return array_values(array_filter($codes, fn (string $code) => self::validCode($code)));
    }

    public static function exists(string $code): bool
    {
        return self::validCode($code) && in_array($code, self::codes(), true);
    }

    public static function defaultCode(): string
    {
        $codes = self::codes();

        if (in_array(self::DEFAULT_CODE, $codes, true)) {
            return self::DEFAULT_CODE;
        }

        return $codes[0] ?? self::DEFAULT_CODE;
    }

    /**
     * @return array<string, string>
     */
    public static function for(string $code): array
    {
        self::boot();

        if (! self::exists($code)) {
            $code = self::defaultCode();
        }

        /** @var array<string, mixed> $data */
        $data = json_decode(File::get(self::file($code)), true) ?: [];

        return array_merge(self::defaults(), array_map('strval', $data), [
            'qr_url' => self::publicUrl($code),
        ]);
    }

    /**
     * @param  array<string, string>  $values
     * @return array<string, string>
     */
    public static function update(string $code, array $values): array
    {
        $settings = array_merge(self::for($code), $values, [
            'qr_url' => self::publicUrl($code),
        ]);
        self::write($code, $settings);

        if ($code === self::DEFAULT_CODE) {
            File::put(
                storage_path('app/certificate-settings.json'),
                json_encode($settings, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)."\n",
            );
        }

        return $settings;
    }

    public static function duplicate(string $from, string $to): void
    {
        $data = self::for($from);
        $data['qr_url'] = self::publicUrl($to);
        self::write($to, $data);

        $codes = self::codes();
        $codes[] = $to;
        self::writeIndex($codes);

        $sourcePdf = self::pdfPath($from);
        if (! is_file($sourcePdf) && $from === self::DEFAULT_CODE) {
            $sourcePdf = public_path('certificates/sample-clearance.pdf');
        }

        self::ensurePdfCopy($to, $sourcePdf);
    }

    public static function delete(string $code): void
    {
        $codes = array_values(array_filter(
            self::codes(),
            fn (string $existing) => $existing !== $code,
        ));

        self::writeIndex($codes);
        File::delete(self::file($code));
        File::delete(self::pdfPath($code));
        File::delete(self::qrPath($code));
    }

    public static function publicUrl(string $code): string
    {
        return 'https://inform.menpowerr-eg.co/'.$code;
    }

    public static function validCode(string $code): bool
    {
        return (bool) preg_match('/^[0-9]{4,12}$/', $code);
    }

    /**
     * @param  array<string, string>  $settings
     */
    public static function write(string $code, array $settings): void
    {
        File::ensureDirectoryExists(self::directory());
        File::put(
            self::file($code),
            json_encode($settings, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)."\n",
        );
    }

    /**
     * @param  list<string>  $codes
     */
    public static function writeIndex(array $codes): void
    {
        File::ensureDirectoryExists(self::directory());
        File::put(
            self::indexPath(),
            json_encode(array_values($codes), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)."\n",
        );
    }

    public static function ensurePdfCopy(string $code, string $sourcePdf): void
    {
        if (! is_file($sourcePdf) || is_file(self::pdfPath($code))) {
            return;
        }

        File::ensureDirectoryExists(dirname(self::pdfPath($code)));
        File::copy($sourcePdf, self::pdfPath($code));
    }
}

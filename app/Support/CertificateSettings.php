<?php

namespace App\Support;

use Illuminate\Support\Facades\File;

class CertificateSettings
{
    public static function path(): string
    {
        return storage_path('app/certificate-settings.json');
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
            'password' => 'F22z5s941e',
            'approval_date' => '24-08-2026',
            'qr_url' => 'https://inform.menpowerr-eg.co/',
            'footer_url' => 'https://inform.manpower.gov.eg/',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function all(): array
    {
        if (! File::exists(self::path())) {
            self::save(self::defaults());
        }

        /** @var array<string, mixed> $data */
        $data = json_decode(File::get(self::path()), true) ?: [];

        return array_merge(self::defaults(), array_map('strval', $data));
    }

    public static function get(string $key, ?string $default = null): string
    {
        return self::all()[$key] ?? $default ?? '';
    }

    /**
     * @param  array<string, string>  $values
     */
    public static function update(array $values): array
    {
        $settings = array_merge(self::all(), $values);
        self::save($settings);

        return $settings;
    }

    /**
     * @param  array<string, string>  $settings
     */
    public static function save(array $settings): void
    {
        File::ensureDirectoryExists(dirname(self::path()));
        File::put(
            self::path(),
            json_encode($settings, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)."\n",
        );
    }
}

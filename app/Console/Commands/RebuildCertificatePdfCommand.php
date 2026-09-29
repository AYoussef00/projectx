<?php

namespace App\Console\Commands;

use App\Services\CertificatePdfBuilder;
use App\Support\CertificateSettings;
use Illuminate\Console\Command;

class RebuildCertificatePdfCommand extends Command
{
    protected $signature = 'certificate:rebuild-pdf';

    protected $description = 'Rebuild public/certificates/sample-clearance.pdf from settings';

    public function handle(CertificatePdfBuilder $builder): int
    {
        $failed = false;

        foreach (CertificateSettings::codes() as $code) {
            $this->info('Rebuilding '.$code.'...');
            $result = $builder->rebuild($code);
            $result['ok'] ? $this->info($result['message']) : $this->error($result['message']);
            $failed = $failed || ! $result['ok'];
        }

        return $failed ? self::FAILURE : self::SUCCESS;
    }
}

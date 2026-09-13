<?php

namespace App\Console\Commands;

use App\Services\CertificatePdfBuilder;
use Illuminate\Console\Command;

class RebuildCertificatePdfCommand extends Command
{
    protected $signature = 'certificate:rebuild-pdf';

    protected $description = 'Rebuild public/certificates/sample-clearance.pdf from settings';

    public function handle(CertificatePdfBuilder $builder): int
    {
        $this->info('Rebuilding certificate PDF...');
        $result = $builder->rebuild();

        if ($result['ok']) {
            $this->info($result['message']);

            return self::SUCCESS;
        }

        $this->error($result['message']);

        return self::FAILURE;
    }
}

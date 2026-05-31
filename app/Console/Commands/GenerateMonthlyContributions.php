<?php

namespace App\Console\Commands;

use App\Services\ContributionAutomationService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:generate-monthly-contributions')]
#[Description('Command description')]

class GenerateMonthlyContributions extends Command
{

    protected $signature = 'chama:generate-contributions';

    protected $description = 'Generate monthly contribution expectations for all members';
    
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $service = new ContributionAutomationService();
        $service->generateMonthlyContributions();

        $this->info('Monthly contributions generated successfully.');
    }
}

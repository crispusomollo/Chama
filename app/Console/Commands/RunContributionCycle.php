<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:run-contribution-cycle')]
#[Description('Command description')]
class RunContributionCycle extends Command
{

	protected $signature = 'chama:run-cycle';

	protected $description = 'Generate and update monthly contribution cycles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $service = new ContributionService();

        $service->generateMonthlyCycle();
        $service->markOverdue();

        $this->info("Contribution cycle executed successfully.");
    }
}

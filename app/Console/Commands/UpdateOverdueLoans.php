<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Loan;

class UpdateOverdueLoans extends Command
{
    /**
     * Command signature.
     */
    protected $signature = 'loans:update-overdue';

    /**
     * Command description.
     */
    protected $description =
        'Automatically mark overdue loans';

    /**
     * Execute command.
     */
    public function handle()
    {
        /*
        |------------------------------------------------------------------
        | FIND EXPIRED ACTIVE LOANS
        |------------------------------------------------------------------
        */

        $loans = Loan::whereIn('loan_status', [

                'active',
                'ongoing',

            ])
            ->whereDate('due_date', '<', now())
            ->where('balance', '>', 0)
            ->get();

        $updated = 0;

        foreach ($loans as $loan) {

            $loan->update([

                'loan_status' => 'overdue',

            ]);

            $updated++;
        }

        $this->info(

            "{$updated} loan(s) marked overdue."

        );

        return Command::SUCCESS;
    }
}

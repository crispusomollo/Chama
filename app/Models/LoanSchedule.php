<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanSchedule extends Model
{
    protected $fillable = [

        'loan_id',

        'installment_number',

        'due_date',

        'principal_amount',

        'interest_amount',

        'installment_amount',

        'balance_after',

        'status',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}

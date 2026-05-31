<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemRule extends Model
{
    protected $fillable = [

        'effective_from',

        'monthly_contribution',

        'shares_allocation',

        'insurance_allocation',

        'penalty_amount',

        'grace_days',

        'interest_rate',

        'loan_multiplier',

        'max_repayment_months',

        'active',

        'notes',
    ];

    protected $casts = [

        'effective_from' => 'date',

        'active' => 'boolean',
    ];


    public function contributions()
    {
        return $this->hasMany(
            Contribution::class,
            'rule_id'
        );
    }
}

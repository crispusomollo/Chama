<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContributionSchedule extends Model
{
    protected $fillable = [

        'borrower_id',
        'chama_id',

        'rule_id',

        'period',
        'expected_amount',
        'paid_amount',
        'balance',
        'penalty',
        'due_date',
        'status',
    ];

    public function borrower()
    {
        return $this->belongsTo(Borrower::class);
    }

    public function allocations()
    {
        return $this->hasMany(
            ContributionAllocation::class
        );
    }

    public function rule()
    {
        return $this->belongsTo(SystemRule::class);
    }
}
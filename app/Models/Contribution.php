<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contribution extends Model
{
    protected $fillable = [

        'borrower_id',
        'receipt_no',
        'amount',
        'shares_amount',
        'insurance_amount',
        'contribution_date',
        'period',
        'status',
        'type',
        'notes',
        'chama_id',
        'rule_id',

    ];

    public function borrower()
    {
        return $this->belongsTo(Borrower::class);
    }

    public function chama()
    {
        return $this->belongsTo(Chama::class);
    }

    public function allocations()
    {
        return $this->hasMany(
            ContributionAllocation::class
        );
    }

    public function shareLedger()
    {
        return $this->hasOne(ShareLedger::class);
    }

    public function insuranceLedger()
    {
        return $this->hasOne(InsuranceLedger::class);
    }

    public function rule()
	{
    	return $this->belongsTo(SystemRule::class);
	}
}

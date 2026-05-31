<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShareLedger extends Model
{
    protected $fillable = [

        'borrower_id',
        'contribution_id',
        'transaction_type',
        'description',
        'credit',
        'debit',
        'balance',
        'transaction_date',
    ];

    public function borrower()
    {
        return $this->belongsTo(Borrower::class);
    }

    public function contribution()
    {
        return $this->belongsTo(Contribution::class);
    }
}

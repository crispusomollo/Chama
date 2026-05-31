<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repayment extends Model
{
    protected $fillable = [

        'loan_id',

        'reference_number',

        'amount',

        'payment_method',

        'transaction_code',

        'status',

        'payment_date',

        'notes',

        'received_by',
    ];

    /*
    |----------------------------------------------------------------------
    | RELATIONSHIP
    |----------------------------------------------------------------------
    */

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    /*
    |----------------------------------------------------------------------
    | AUTO GENERATE REFERENCE
    |----------------------------------------------------------------------
    */

    protected static function booted()
    {
    static::creating(function ($repayment) {

        /*
        |--------------------------------------------------------------------------
        | PAYMENT REFERENCE
        |--------------------------------------------------------------------------
        */

        $repayment->reference_number =
            'PAY-' . now()->format('YmdHis') . '-' . rand(100, 999);

        /*
        |--------------------------------------------------------------------------
        | RECEIPT NUMBER
        |--------------------------------------------------------------------------
        */

        $repayment->receipt_number =
            'RCPT-' . now()->format('Ymd') . '-' . rand(1000, 9999);

      });
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contribution extends Model
{
    protected $fillable = [
        'borrower_id',
        'amount',
        'contribution_date',
        'month',
        'notes'
    ];

    public function borrower()
    {
    return $this->belongsTo(Borrower::class);
    }

    public function chama()
    {
    return $this->belongsTo(Chama::class);
    }
}

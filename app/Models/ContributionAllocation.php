<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContributionAllocation extends Model
{
    protected $fillable = [

        'contribution_id',
        'contribution_schedule_id',
        'amount',

    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function contribution()
    {
        return $this->belongsTo(Contribution::class);
    }

    public function schedule()
    {
        return $this->belongsTo(
            ContributionSchedule::class,
            'contribution_schedule_id'
        );
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chama extends Model
{
    protected $fillable = [
        'name',
        'monthly_contribution',
        'description',
    ];

    public function borrowers()
    {
        return $this->hasMany(Borrower::class);
    }

    public function contributions()
    {
        return $this->hasMany(Contribution::class);
    }
}

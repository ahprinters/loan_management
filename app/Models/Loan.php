<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'interest_rate',
        'term_months',
        'status',
        'disbursed_at',
        'due_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

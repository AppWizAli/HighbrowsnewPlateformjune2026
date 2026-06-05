<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyFeeDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'monthly_fee_id', 'description', 'amount',
    ];

    public function monthlyFee()
    {
        return $this->belongsTo(MonthlyFee::class);
    }
}

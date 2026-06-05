<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    use HasFactory;
    protected $fillable = ['amount', 'due_date', 'status', 'receipt', 'fee_id'];

    public function fee()
    {
        return $this->belongsTo(Fee::class);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyFee extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id','date', 'receipt_no',
        'receiving_date', 'fee_month', 'discount', 'discounted_tuition_fee',
        'total_amount', 'amount_words', 'receiver_name','status','recepit','user_id','generated_by',
    ];

    public function details()
    {
        return $this->hasMany(MonthlyFeeDetail::class);
    }
    public function monthlyfee()
    {
        return $this->hasMany(MonthlyFee::class, ['user_id','student_id']);
    }

    public function student()
    {
        return $this->belongsTo(Admission::class,'student_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // adjust 'user_id' if needed
    }
    public function updatePaymentStatus()
    {
        $hasMissingReceipts = $this->whereNull('receipt')->exists();

        $this->update([
            'status' => $hasMissingReceipts ? 'pending' : 'paid'
        ]);
    }
}

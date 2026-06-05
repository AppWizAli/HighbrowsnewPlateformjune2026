<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id', 'total_fee', 'advance', 'installments', 'due_date', 'status', 'payment_proof'
    ];
    public function student(){
        return $this->belongsTo(Admission::class);
    }
    public function studentsWithoutAdmission()
{
    return $this->hasMany(Student::class)
        ->doesntHave('admission');
}
    public function installment(){
        return $this->hasMany(Installment::class);
    }


    public function checkIfFullyPaid()
    {
        if ($this->installment()->whereNull('receipt')->doesntExist()) {
            $this->update(['status' => 'Paid']);
        }
    }
    public function updatePaymentStatus()
{
    $hasMissingReceipts = $this->installment()->whereNull('receipt')->exists();

    $this->update([
        'status' => $hasMissingReceipts ? 'pending' : 'Paid'
    ]);
}
}

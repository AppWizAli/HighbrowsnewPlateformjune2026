<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmissionCadetCollege extends Model
{
    use HasFactory;
    protected $fillable = ['admission_id', 'cadet_college_id'];

    /**
     * Relationship with Admission model.
     */
    public function admission()
    {
        return $this->belongsTo(Admission::class, 'admission_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class College extends Model
{
    use HasFactory;
    protected $fillable=['college_name','college_fee'];
    

    public function admissions()
    {
        return $this->belongsToMany(Admission::class, 'admission_cadet_colleges', 'college_id', 'admission_id');
    }

}

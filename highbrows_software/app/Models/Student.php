<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name',
        'father_name',
        'mother_name',
        'father_cnic',
        'mother_cnic',
        'student_cnic',
        'guardian_name',
        'religion',
        'sect',
        'dob',
        'gender',
        'contact_number',
        'domicile_district',
    ];
    protected $guareded=[];
    public function admission(){
        return $this->hasOne(Admission::class);
    }
    public function documents(){
        return $this->hasMany(Document::class);
    }
}

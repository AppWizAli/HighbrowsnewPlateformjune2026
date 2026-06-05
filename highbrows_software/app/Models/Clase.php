<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clase extends Model
{
    use HasFactory;

    protected $table = 'classes'; 
    protected $fillable = ['name', 'note','teacher_id'];

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'classes_subjects', 'class_id', 'subject_id');
    }
    public function teacher()
{
    return $this->belongsTo(Teacher::class);
}
public function admissions()
{
    return $this->hasMany(Admission::class, 'grade_applied_for', 'id');
}

}


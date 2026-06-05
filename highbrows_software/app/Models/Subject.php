<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $table = 'subjects'; 
    protected $fillable = ['subj_name', 'type', 'pass_marks', 'total_marks'];

    public function classes()
    {
        return $this->belongsToMany(Clase::class, 'classes_subjects', 'subject_id', 'class_id');
    }
}


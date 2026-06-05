<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ClassesSubject extends Model
{
    use HasFactory;

    protected $table = 'class_subject';
    protected $fillable = ['class_id', 'subject_id'];

    public function class()
    {
        return $this->belongsTo(Clase::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}


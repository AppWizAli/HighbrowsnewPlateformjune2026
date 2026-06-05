<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;
    public function student(){
        return $this->belongsTo(Admission::class);
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class ,'subject_id');
    }
    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }
    public function clase()
    {
        return $this->belongsTo(Clase::class);
    }
}

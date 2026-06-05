<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;
    protected $fillable=['name','note','date'];
    public function examSchedules()
    {
        return $this->hasMany(ExamSchedule::class, 'exam_id');
    }
}

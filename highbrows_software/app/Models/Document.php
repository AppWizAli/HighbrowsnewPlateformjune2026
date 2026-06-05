<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
    protected $fillable =[
        'student_id',
        'file_path',
        'file_type',
        'file_name'

    ];
    protected $guarded=[];
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}

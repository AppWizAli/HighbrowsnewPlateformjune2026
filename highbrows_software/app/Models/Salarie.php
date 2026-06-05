<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salarie extends Model
{
    use HasFactory;
protected $fillable=['date','teacher_id','status'];
public function teacher()
{
    return $this->belongsTo(Teacher::class);
}

}

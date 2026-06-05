<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentCondition extends Model
{
    use HasFactory;
    protected $fillable=['rules'];
}

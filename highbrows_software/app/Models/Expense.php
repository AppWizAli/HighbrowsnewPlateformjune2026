<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    // Table name (optional if it matches 'expenses')
    protected $table = 'expenses';

    // Fillable fields for mass assignment
    protected $fillable = [
        'details',     // JSON array of individual expense descriptions
        'total',       // Total amount of the expense batch
        'date',
        'image',       // Date of the expense
    ];

    // Casting 'details' to array to simplify usage
    protected $casts = [
        'details' => 'array',
        'date' => 'date',
    ];
}


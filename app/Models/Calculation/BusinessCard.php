<?php

namespace App\Models\Calculation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessCard extends Model
{
    use HasFactory;

    protected $fillable = ['price'];
    protected $hidden = ['created_at', 'updated_at', 'id'];
}

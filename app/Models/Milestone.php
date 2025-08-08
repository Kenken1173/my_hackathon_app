<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'goal_id',
        'name',
        'description',
        'startDate',
        'endDate',
        'achieved'
    ];
}
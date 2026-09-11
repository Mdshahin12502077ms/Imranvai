<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EveryconditionTerm extends Model
{
    use HasFactory;

    protected $table = 'everycondition_terms';

    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'image',
        'status',
    ];
}

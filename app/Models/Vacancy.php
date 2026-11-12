<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vacancy extends Model
{
    protected $fillable = [
        'position',
        'company',
        'city',
        'salary_from',
        'employment_type',
        'posted_at',
    ];

    protected function casts(): array
    {
        return [
            'salary_from' => 'integer',
            'posted_at' => 'date',
        ];
    }
}

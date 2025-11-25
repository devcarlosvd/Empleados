<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'is_president'];

    protected $casts = [
        'is_president' => 'boolean',
    ];

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_position')
            ->withPivot('start_date', 'end_date', 'is_current')
            ->withTimestamps();
    }
}

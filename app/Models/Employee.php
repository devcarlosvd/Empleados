<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'identification',
        'address',
        'phone',
        'birth_city_id',
        'supervisor_id',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relación con ciudad de nacimiento
    public function birthCity()
    {
        return $this->belongsTo(City::class, 'birth_city_id');
    }

    // Relación con cargos (muchos a muchos)
    public function positions()
    {
        return $this->belongsToMany(Position::class, 'employee_position')
            ->withPivot('start_date', 'end_date', 'is_current')
            ->withTimestamps();
    }

    // Relación con supervisor/jefe
    public function supervisor()
    {
        return $this->belongsTo(Employee::class, 'supervisor_id');
    }

    // Relación con colaboradores (empleados que supervisa)
    public function subordinates()
    {
        return $this->hasMany(Employee::class, 'supervisor_id');
    }

    // Verificar si es presidente
    public function isPresident()
    {
        return $this->positions()
            ->where('is_president', true)
            ->wherePivot('is_current', true)
            ->exists();
    }

    // Obtener nombre completo
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // Obtener cargos actuales
    public function currentPositions()
    {
        return $this->positions()->wherePivot('is_current', true);
    }
}

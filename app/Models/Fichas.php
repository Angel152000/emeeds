<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fichas extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_ficha',
        'id_atencion',
        'id_paciente',
        'rut_paciente',
        'id_especialista',
        'id_especialidad',
        'fecha_atencion',
        'nota',
    ];

}

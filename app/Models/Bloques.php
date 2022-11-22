<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Bloques extends Model
{
    use HasFactory;

    protected $table = 'bloques';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_bloque',
        'id_horario',
        'numero_bloque',
        'hora_bloque',
        'bloque_desc'
    ];

    public function getHorarioByAtencion($id_horario,$fecha)
    {
        $bloque = DB::table('atenciones')->where('id_horario',$id_horario)->where('fecha',$fecha)->get();
        return $bloque;
    }
}

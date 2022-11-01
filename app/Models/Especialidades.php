<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Especialidades extends Model
{
    use HasFactory;

    protected $table = 'especialidades';

    public function getEspecialidades()
    {
        $especialidad = DB::table($this->table)->get();
        return $especialidad;
    }

    public function grabarEspecialidad($data)
    {
        $especialidad = DB::table($this->table)->insert($data);
        return $especialidad;
    }

    public function eliminarEspecialidad($id)
    {
        $especialidad = DB::table($this->table)->where('id', $id)->delete();
        return $especialidad;
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Especialidades extends Model
{
    use HasFactory;

    protected $table = 'especialidades';

    protected $fillable = [
        'id',
        'codigo',
        'nombre',
        'descripcion'
    ];

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

    public function editarEspecialidad($id,$data)
    {
        $especialidad = DB::table($this->table)->where('id', $id)->update($data);
        return $especialidad;
    }

    public function getEspecialidadesById($id)
    {
        $especialidad = DB::table($this->table)->select('nombre')->where('id',$id)->first();
        return $especialidad;
    }

}

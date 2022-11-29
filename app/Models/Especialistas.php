<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Especialistas extends Model
{
    use HasFactory;
    protected $table = 'especialistas';

    protected $fillable = [
        'id',
        'id_user',
        'id_especialidad',
        'tipo_atencion',
        'rut',
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'fecha_nacimiento',
        'sexo',
        'email',
        'telefono'
    ];

    public function getEspecialistas()
    {
        $especialistas = DB::table($this->table)->get();
        return $especialistas;
    }

    public function grabarEspecialistas($data)
    {
        $especialistas = DB::table($this->table)->insert($data);
        return $especialistas;
    }

    public function eliminarEspecialistas($id)
    {
        $especialistas = DB::table($this->table)->where('id', $id)->delete();
        return $especialistas;
    }

    public function editarEspecialistas($data,$id)
    {
        $especialistas = DB::table($this->table)->where('id', $id)->update($data);
        return $especialistas;
    }

    public function getEspecialistasById($id)
    {
        $especialistas = DB::table($this->table)->where('id',$id)->first();
        return $especialistas;
    }
}

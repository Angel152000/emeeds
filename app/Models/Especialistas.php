<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Especialistas extends Model
{
    use HasFactory;
    protected $table = 'especialistas';

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

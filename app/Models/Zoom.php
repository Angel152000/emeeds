<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Zoom extends Model
{
    use HasFactory;

    protected $table = 'zoom';

    protected $fillable = [
        'id',
        'id_user',
        'id_zoom',
        'user_zoom',
        'email_zoom',
        'code',
        'access_token',
        'token_type',
        'refresh_token',
        'expires_in',
        'scope',
        'estado'
    ];
    
    public function getUser($id_user)
    {
        return DB::table($this->table)
            ->where("id_user",$id_user)
            ->where("estado","activado")
            ->first();
    }

    public function insertUserByZoom($data)
    {
        return DB::table($this->table)->insert($data);
    }

    public function desvincularCuenta($id_user)
    {
        return DB::table($this->table)->where("id_user",$id_user)->delete();
    }

    public function updateUserByZoom($id_user,$data)
    {
        return DB::table($this->table)->where("id_user",$id_user)->update($data);
    }

}

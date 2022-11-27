<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AtencionZoom extends Model
{
    use HasFactory;
    
    protected $table = 'atenciones_zoom';

    protected $fillable = [
        'id',
        'id_atencion',
        'codigo_atencion',
        'id_reunion_zoom',
        'password_zoom',
        'link_atencion',
    ];
}

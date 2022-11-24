<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagos extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_pago',
        'metodo_pago',
        'payment_id',
        'estado_mercado_pago',
        'merchant_order_id',
        'id_atencion',
        'codigo_atencion',
        'estado',
        'monto_pago',
        'fecha_pago'
    ];
}

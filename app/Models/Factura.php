<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;

    // Tabla asociada
    protected $table = 'facturas';

    // Campos que pueden ser llenados masivamente
    protected $fillable = [
        'id_pedido',
        'numero_factura',
        'fecha_emision',
        'total',
        'estado'
    ];

    // Relación con el modelo Pedido
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido');
    }
}

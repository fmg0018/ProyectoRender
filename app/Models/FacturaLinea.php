<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacturaLinea extends Model
{
    protected $table = 'factura_lineas';

    protected $fillable = [
        'factura_id',
        'descripcion',
        'cantidad',
        'precio_unitario',
        'impuesto',
        'total_linea',
    ];

    public function factura()
    {
        return $this->belongsTo(FacturaModelo::class, 'factura_id');
    }
}

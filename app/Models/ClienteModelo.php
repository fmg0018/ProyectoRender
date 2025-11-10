<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteModelo extends Model
{
    use HasFactory;

    protected $table = 'clientes';
    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'empresa',
        'direccion',
        'ciudad',
        'pais',
        'estado',
        'notas',
    ];

    public function incidencias()
    {
        return $this->hasMany(IncidenciaModelo::class, 'cliente_id');
    }

    public function facturas()
    {
        return $this->hasMany(FacturaModelo::class, 'cliente_id');
    }
}

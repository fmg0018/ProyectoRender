<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class IncidenciaModelo extends Model
{
    use HasFactory;

    protected $table = 'incidencias';
    protected $fillable = [
        'titulo',
        'descripcion',
        'cliente_id',
        'user_id',
        'prioridad',
        'estado',
        'fecha_reporte',
        'fecha_resolucion',
        'solucion',
    ];

    protected $casts = [
        'fecha_reporte' => 'date',
        'fecha_resolucion' => 'date',
    ];

    public function cliente()
    {
        return $this->belongsTo(ClienteModelo::class, 'cliente_id');
    }

    public function responsable()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

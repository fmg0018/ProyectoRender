<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\ClienteModelo;
use Illuminate\Support\Facades\DB;

class FacturaModelo extends Model
{
    protected $table = 'facturas';

    protected $fillable = [
        'numero_factura',
        'cliente_id',
        'fecha_emision',
        'fecha_vencimiento',
        'subtotal',
        'impuestos',
        'total',
        'estado',
        'descripcion',
    ];

    protected $casts = [
        'fecha_emision' => 'date',
        'fecha_vencimiento' => 'date',
        'subtotal' => 'decimal:2',
        'impuestos' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Relación con cliente
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(ClienteModelo::class, 'cliente_id');
    }

    public function lineas()
    {
        return $this->hasMany(FacturaLinea::class, 'factura_id');
    }

    /**
     * Recalcula subtotal, impuestos y total basándose en las lineas
     */
    public function recalcularTotales()
    {
        $subtotal = 0;
        $impuestos = 0;
        foreach ($this->lineas as $linea) {
            $lineSubtotal = ($linea->cantidad * $linea->precio_unitario);
            // Interpretar 'impuesto' como porcentaje (por ejemplo 10 => 10%)
            $lineImpuesto = 0;
            if (!is_null($linea->impuesto) && $linea->impuesto != 0) {
                $lineImpuesto = $lineSubtotal * (floatval($linea->impuesto) / 100.0);
            }

            // Actualizar total_linea para que coincida con el nuevo cálculo
            $linea->total_linea = round($lineSubtotal + $lineImpuesto, 2);
            // Guardar cambios ligeros en la línea
            $linea->save();

            $subtotal += $lineSubtotal;
            $impuestos += $lineImpuesto;
        }

        $this->subtotal = round($subtotal, 2);
        $this->impuestos = round($impuestos, 2);
        $this->total = round($this->subtotal + $this->impuestos, 2);
        $this->save();
    }

    /**
     * Genera y asigna el número de factura si no existe.
     */
    public function asignarNumeroSiNecesario()
    {
        if ($this->numero_factura) {
            return $this->numero_factura;
        }

        $this->numero_factura = self::generarNumero();
        $this->save();

        return $this->numero_factura;
    }

    /**
     * Genera el siguiente número seguro por año: FCT-{YEAR}-{XXXX}
     */
    public static function generarNumero()
    {
        $year = date('Y');

        return DB::transaction(function () use ($year) {
            $counter = DB::table('factura_counters')->where('year', $year)->lockForUpdate()->first();

            if (!$counter) {
                $id = DB::table('factura_counters')->insertGetId(['year' => $year, 'counter' => 1, 'created_at' => now(), 'updated_at' => now()]);
                $counterValue = 1;
            } else {
                $counterValue = $counter->counter + 1;
                DB::table('factura_counters')->where('id', $counter->id)->update(['counter' => $counterValue, 'updated_at' => now()]);
            }

            return sprintf('FCT-%s-%04d', $year, $counterValue);
        });
    }

    /**
     * Formatea total en euros
     */
    protected function totalFormatted(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => number_format($this->total, 2, ',', '.') . ' €'
        );
    }
}

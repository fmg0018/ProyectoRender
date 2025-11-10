<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\FacturaModelo;

class FacturaEnviada extends Mailable
{
    use Queueable, SerializesModels;

    public $factura;

    public function __construct(FacturaModelo $factura)
    {
        $this->factura = $factura;
    }

    public function build()
    {
        $mail = $this->subject('Factura ' . ($this->factura->numero ?? $this->factura->numero_factura ?? ''))
            ->view('emails.factura_enviada')
            ->with(['factura' => $this->factura]);

        // adjuntar PDF si dompdf está disponible
        // Intentar adjuntar PDF de forma segura
        try {
            if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('facturas.pdf', ['factura' => $this->factura]);
                $mail->attachData($pdf->output(), ($this->factura->numero ?? $this->factura->numero_factura ?? 'factura') . '.pdf', [
                    'mime' => 'application/pdf'
                ]);
            }
        } catch (\Exception $e) {
            // Registrar y continuar sin adjunto
            \Log::error('No se pudo generar el PDF para el email de factura: ' . $e->getMessage());
        }

        return $mail;
    }
}

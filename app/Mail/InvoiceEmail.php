<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


use Barryvdh\DomPDF\Facade\Pdf;  // Importa la clase de PDF


class InvoiceEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $customerName;
    public $invoiceDetails;
    public $totalInvoice;

    public $numeroFactura;
    public $fechaEmision;
    public $empleadoNombre;
    public $farmaciaNombre;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($customerName, $invoiceDetails, $totalInvoice, $numeroFactura, $fechaEmision, $empleadoNombre, $farmaciaNombre)
{
    $this->customerName = $customerName;
    $this->invoiceDetails = $invoiceDetails;
    $this->totalInvoice = $totalInvoice;
    $this->numeroFactura = $numeroFactura;
    $this->fechaEmision = $fechaEmision;
    $this->empleadoNombre = $empleadoNombre;
    $this->farmaciaNombre = $farmaciaNombre;
}


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Genera el PDF a partir de la vista 'emails.invoice'
        $pdf = Pdf::loadView('emails.invoice', [
            'customerName' => $this->customerName,
            'invoiceDetails' => $this->invoiceDetails,
            'totalInvoice' => $this->totalInvoice,
            'numeroFactura' => $this->numeroFactura,
            'fechaEmision' => $this->fechaEmision,
            'empleadoNombre' => $this->empleadoNombre,
            'farmaciaNombre' => $this->farmaciaNombre,
        ]);

        // Devuelve el correo con el PDF adjunto
        return $this->subject('Factura de tu compra')
                    ->markdown('emails.invoice')
                    ->attachData($pdf->output(), 'factura_'.$this->numeroFactura.'.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}

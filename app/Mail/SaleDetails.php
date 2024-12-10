<?php

namespace App\Mail;

use App\Models\Sale;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SaleDetails extends Mailable
{
    use Queueable, SerializesModels;

    public $sale;
    public $additionalMessage;

    public function __construct(Sale $sale, $additionalMessage = null)
    {
        $this->sale = $sale;
        $this->additionalMessage = $additionalMessage;
    }

    public function build()
    {
        return $this->subject('Detalhes da Venda #' . str_pad($this->sale->id, 6, '0', STR_PAD_LEFT))
                    ->markdown('emails.sales.details');
    }
}

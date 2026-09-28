<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order->loadMissing('items.product', 'user');
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $refCode = $this->order->reference_code ?? ('#' . $this->order->id);

        return $this->subject("Siparişiniz Alındı ({$refCode}) - Afi Bilişim")
                    ->view('emails.order_received');
    }
}

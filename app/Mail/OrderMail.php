<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderMail extends Mailable
{
    use Queueable, SerializesModels;
    // public $data;
    protected $pdf;
    protected $invoice_no;
    protected $payment_type;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pdf,$invoice_no,$payment_type)
    {
        $this->pdf = $pdf;
        $this->invoice_no = $invoice_no;
        $this->payment_type = $payment_type;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $pdf = $this->pdf;
        $invoice_no = $this->invoice_no;
        $payment_type = $this->payment_type;

        return $this->from(env('MAIL_FROM_ADDRESS'))
        ->subject('Nithitex Order')
        ->view(($payment_type=='cod'? 'mail.orderdelivered' :'mail.orderplaced'),compact('pdf'))
        ->attachData($this->pdf->output(), $invoice_no.'.pdf');
    }
}

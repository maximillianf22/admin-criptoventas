<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class codeConfirmed extends Mailable
{
    use Queueable, SerializesModels;
    public $subject = 'Bienvenid@ a Favores.co!';

    public $msj;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->msj = $msj;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.codeConfirmation');
    }
}

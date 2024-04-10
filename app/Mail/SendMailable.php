<?php

namespace App\Mail;

use Illuminate\Bus\Queueable; 
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
 
class SendMailable extends Mailable
{

    public $subject = 'Confirmación de Servicio' ;
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this 
     */
    public function build() 
    {
        //echo 'Prueba'; 
        return $this->view('correoPreuab');
    }
}
  
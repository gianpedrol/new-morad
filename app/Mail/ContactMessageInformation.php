<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMessageInformation extends Mailable
{
    use Queueable, SerializesModels;

    public $contact;

    public function __construct($contact)
    {
        $this->contact = $contact;
    }

    public function build()
    {
        return $this->view('contact_message')
            ->subject('NOVO LEAD DA APB, PARA VOCÊ! ')
            ->with([
                'contact' => $this->contact,
            ]);
    }
}

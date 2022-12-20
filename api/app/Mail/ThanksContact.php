<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Contact;

class ThanksContact extends Mailable
{
    use Queueable, SerializesModels;

    private $contact;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject("Novo Contato Recebido" );
        $this->to($this->contact->email, $this->contact->name);

        return $this->markdown('mail.thanks-contact', [
            'contact' => $this->contact,
            'email_confirmation_image' => asset("/api/contacts/email-confirmation/{$this->contact->id}"),
            'site' => asset("/solucoes?contacts-email-confirmation={$this->contact->id}")
        ]);
    }
}

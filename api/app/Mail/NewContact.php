<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Notifiable;
use App\Models\Contact;

class NewContact extends Mailable
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
        $this->subject("Novo Contato | {$this->contact->subject}" );

        foreach (Notifiable::all()->toArray() as $contact) {
            $this->to($contact['email'], $contact['name']);
        }
        
        return $this->markdown('mail.new-contact', [
            'contact' => (object) array_merge(
                $this->contact->toArray(),
                ["phone" => Contact::formatBRPhoneNumber($this->contact->phone)]
            )
        ]);
    }
}

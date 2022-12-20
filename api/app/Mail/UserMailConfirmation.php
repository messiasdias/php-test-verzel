<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class UserMailConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    private $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject("Confimar Endereço de Email" );
        $this->to($this->user->email, $this->user->name);
        $hash = $this->user->getConfirmationCode();
        $link = "/api/users/confirm-mail/{$this->user->id}/{$hash}";

        return $this->markdown('mail.user-confirm-mail', [
            'user' => $this->user, 
            "link" => asset($link)
        ]);
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $registrationDate;

    /**
     * Buat instance pesan.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function __construct($user, $registrationDate)
    {
        $this->user = $user;
        $this->registrationDate = $registrationDate;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Welcome to Our Application')
            ->view('emails.welcome');
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable implements shouldQueue
{
    use Queueable, SerializesModels;

    public $password;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct($user,$password)
    {
        $this->user = $user;
        $this->password = $password;
    }

/**
     * Build the message.
     */
    public function build(){
        return $this->view('emails.Password')
            ->subject('Welcome to the MaPverty System')
            ->with([
                'name' => $this->user->name,
                'password' => $this->password,
            ]);
    }
}

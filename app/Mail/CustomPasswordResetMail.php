<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomPasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $user;

    public function __construct($token, $user)
    {
        $this->token = $token;
        $this->user = $user;
    }

    public function build()
    {
        // Generate the reset URL - make sure it's just the relative path
        $resetUrl = url('/reset-password/' . $this->token . '?email=' . urlencode($this->user->email));
        
        // Alternative using route() - but ensure it's correct
        // $resetUrl = route('password.reset', [
        //     'token' => $this->token,
        //     'email' => $this->user->email,
        // ]);

        return $this->subject('Reset Your Outfit 818 Password')
            ->view('emails.custom-password-reset')
            ->with([
                'resetUrl' => $resetUrl,
                'user' => $this->user,
            ]);
    }
}
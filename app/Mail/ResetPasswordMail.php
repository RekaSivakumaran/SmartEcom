<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $customer;
    public $resetLink;

    public function __construct($customer, $resetLink)
    {
        $this->customer  = $customer;
        $this->resetLink = $resetLink;
    }

    public function build()
    {
        return $this->subject('Reset Your Password')
                    ->view('emails.reset-password');
    }
}
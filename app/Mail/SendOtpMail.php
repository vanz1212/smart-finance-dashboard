<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $type;
    public $otpCode;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($otpCode, $type = 'reset')
    {
        $this->otpCode = $otpCode;
        $this->type = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Kode OTP Anda - Nexio Smart Finance')
                    ->view('emails.otp');
    }
}

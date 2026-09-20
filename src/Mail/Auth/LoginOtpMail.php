<?php

namespace Pagelyne\Identity\Mail\Auth;

use Pagelyne\Identity\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoginOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public int $otp,
        public int $expiryMinutes,
    ) {
    }

    public function build(): static
    {
        return $this
            ->subject('BillDesk Login Verification Code')
            ->view('identity::emails.auth.login-otp');
    }
}
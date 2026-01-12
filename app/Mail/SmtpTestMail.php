<?php

n<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SmtpTestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function build()
    {
        return $this->subject('SMTP Test Email')
                    ->html('
                        <h2>SMTP Test Successful</h2>
                        <p>Your Laravel application has successfully sent an email using SMTP.</p>
                    ');
    }
}

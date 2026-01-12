<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CallScheduled extends Mailable
{
    use Queueable, SerializesModels;

    public $record;

    /**
     * Create a new message instance.
     */
    public function __construct($record)
    {
        $this->record = $record;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Scheduled Call: ' . ($this->record->room_name ?? 'Call'))
                    ->view('emails.call-scheduled')
                    ->with(['record' => $this->record]);
    }
}

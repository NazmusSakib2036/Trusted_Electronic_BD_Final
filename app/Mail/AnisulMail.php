<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AnisulMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $body;

    /**
     * Create a new message instance.
     */
    public function __construct($name, $body)
    {
        $this->name = $name;
        $this->body = $body;
    }

    /**
     * Get the message envelope.
     */
    public function envelope()
    {
        return new \Illuminate\Mail\Mailables\Envelope(
            subject: 'New'
        );
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->view('mail')->with([
            'name' => $this-> name,
            'body' => $this->body,
        ]);
    }
}

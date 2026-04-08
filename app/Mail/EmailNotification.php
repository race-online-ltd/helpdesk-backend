<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class EmailNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    // public $name;
    // public $subjectText;

    public $name;
    public $subjectText;
    public $fromAddress;
    public $fromName;

    /**
     * Create a new message instance.
     */
    // public function __construct($subject, $name)
    // {
    //     $this->subjectText = $subject;
    //     $this->name = $name;
    // }

    public function __construct($subject, $name, $fromAddress, $fromName)
    {
        $this->subjectText = $subject;
        $this->name = $name;
        $this->fromAddress = $fromAddress;
        $this->fromName = $fromName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address($this->fromAddress, $this->fromName),
            subject: $this->subjectText,
        );
    }

    /**
     * Get the message content definition.
     */

    public function content(): Content
    {
        return new Content(
            view: 'email.email', // Define the email view here
            with: [
                'name' => $this->name,
                'subject' => $this->subjectText
            ],
        );
    }


    public function build()
    {
        return $this->from($this->fromAddress, $this->fromName)
                    ->subject($this->subjectText)
                    ->view('email.email', [
                        'name' => $this->name,
                        'subject' => $this->subjectText,
                    ]);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

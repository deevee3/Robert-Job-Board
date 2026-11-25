<?php

namespace App\Mail;

use App\Models\DigestSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class DigestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public DigestSubscriber $subscriber,
        public Collection $jobs,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Robert digest — '.$this->jobs->count().' fresh startup jobs',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.digest',
        );
    }
}

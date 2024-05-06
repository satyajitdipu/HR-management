<?php

namespace App\Mail;

use App\Jobs\SendEmail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Jobapply extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Jobapply',
        );
    }
    public function someMethod()
{
    $user = User::find(1); // Example user
    SendEmail::dispatch($user);
}

    /**
     * Get the message content definition.
     */
    public function build()
    {
        return $this->view('emails.job_apply')
                    ->with(['apply_job' => $this->Applyjob]);
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

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewApplication extends Mailable
{
    use Queueable, SerializesModels;

    protected $mailData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailData)
    {
        $this->mailData = $mailData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $address = 'rustam190401@mail.ru'/*$this->mailData['email']*/;
        $subject = 'Новая заявка';
        $name = $this->mailData['fullname'];
        $from = env('MAIL_USERNAME');
        return $this->view('email.laraemail')
            ->replyTo($from, $name)
            ->subject($subject)
            ->with(['mailMessage' => $this->mailData]);
    }
}

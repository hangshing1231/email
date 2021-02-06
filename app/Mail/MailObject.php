<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailObject extends Mailable
{
    use Queueable, SerializesModels;
    public $subject = '';
    public $body = '';
    public $files = '';

    /**
     * Create a new message instance.
     *
     * @param string subject
     * @param string body
     * @param array attachements
     * @return void
     */
    public function __construct($subject, $body, $attachements=[])
    {
        $this->subject = $subject;
        $this->body = $body;
        if (!empty($attachements)) {
            $this->files = $attachements;
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this->subject($this->subject)
                    ->view('mail')
                    ->with('body', $this->body);

        if (!empty($this->files)) {
            foreach ($this->files as $value) {
                $attach = explode(',', $value['file']);
                $type = explode(':', explode(';', $attach[0])[0])[1];
                $mail->attachData(
                    base64_decode($attach[1]),
                    $value['name'],
                    ['mime' => $type]
                );
            }
        }
        
        return $mail;
    }
}

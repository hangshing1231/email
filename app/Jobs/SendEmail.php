<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailObject;
use App\Models\MailSent;
use App\Models\Attachment;
use Log;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $mail;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mail)
    {
        $this->mail = $mail;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->mail['to'])->send(
            new MailObject(
                $this->mail['subject'],
                $this->mail['body'],
                empty($this->mail['attachments'])? []: $this->mail['attachments']
            )
        );
       
        $mail = MailSent::create([
            'to' => $this->mail['to'],
            'subject' => $this->mail['subject'],
            'body' => $this->mail['body']
        ]);

        if (!empty($this->mail['attachments'])) {
            foreach ($this->mail['attachments'] as $data) {
                $attachment = Attachment::create([
                    'mail_sent_id' => $mail->id,
                    'name' => $data['name'],
                    'content' => $data['file']
                ]);
            }
        }

        Log::info('Email sent');
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $remark;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $remark)
    {
        $this->data = $data;
        $this->remark = $remark;
    }

    /**
     * Build the message.
     *
     * @return $this
     */

    public function build()
    {
        if ($this->remark == 'test_mail') {
            return $this->from('bridgeforvendor@ympi.co.id', 'PT. Yamaha Musical Products Indonesia')->subject('Test Mail')->view('mails.test_mail');
        }
    }
}

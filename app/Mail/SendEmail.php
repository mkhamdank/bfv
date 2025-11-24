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

        if ($this->remark == 'wpos') {
            return $this->from('mis@ympi.co.id', 'PT. Yamaha Musical Products Indonesia')
                ->subject('Work Permit With Enviromental & Safety Analysis')
                ->view('mails.wpos');
        }

        if ($this->remark == 'send_po_notification') {
            return $this->from('bridgeforvendor@ympi.co.id', 'PT. Yamaha Musical Products Indonesia')
                ->subject($this->data['subject'])
                ->view('raw_material.po_notification');
        }

        if ($this->remark == 'molding_approval') {
            return $this->from('mis@ympi.co.id', 'PT. Yamaha Musical Products Indonesia')
                ->subject('Molding Approval')
                ->view('molding.mails.mold_approval');
        }

        if ($this->remark == 'reminder_shot_molding') {
            return $this->from('mis@ympi.co.id', 'PT. Yamaha Musical Products Indonesia')
                ->subject('Reminder Pengisian Shot Molding')
                ->view('molding.mails.reminder_shot_molding');
        }

        if ($this->remark == 'reminder_form_molding') {
            return $this->from('mis@ympi.co.id', 'PT. Yamaha Musical Products Indonesia')
                ->subject('Reminder Diagnosa Molding')
                ->view('molding.mails.reminder_form_molding');
        }

        if ($this->remark == 'summary_molding') {
            return $this->from('mis@ympi.co.id', 'PT. Yamaha Musical Products Indonesia')
                ->subject('Summary Molding')
                ->view('molding.mails.summary_molding');
        }

    }
}

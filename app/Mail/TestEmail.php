<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TestEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        $address = 'support@wilipay.com';
        //'You have successfully add a valid phone number to your account';
        return $this->markdown('vendor.notifications.email', [
            'level' => 'success',
            'introLines' => ['Welcome to Wilipay', $this->data['message']],
            'outroLines' => ['Thank you']
        ])
            ->from($address, 'Wilipay support')
            ->subject($this->data['subject']);
    }
}
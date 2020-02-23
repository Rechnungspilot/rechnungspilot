<?php

namespace App\Mail;

use App\Company;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TeamInvite extends Mailable
{
    use Queueable, SerializesModels;

    public $sender;
    public $receiver;
    public $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $sender, User $receiver, string $url)
    {
        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.team.invite');
    }
}

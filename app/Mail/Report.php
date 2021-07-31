<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Report extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $events;
    public $group;
    public $total;
    public $now;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($eventsCollection, $groupDisplayArray, $totalTime, $now)
    {
        $this->events = $eventsCollection;
        $this->group = $groupDisplayArray;
        $this->total = $totalTime;
        $this->now = $now;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->from('test@test.test', 'Test the Test');
        $this->subject('Daily Hours for '.$this->now->format('l F j, Y'));
        $this->to(auth()->user()->email, auth()->user()->name);
        // Composes a plain text email.
        // return $this->text('emails.report_plain');
        // Composes a formatted (HTML) email.
        return $this->view('emails.report_html');
    }
}

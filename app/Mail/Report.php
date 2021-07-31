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

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($eventsCollection, $groupDisplayArray, $totalTime)
    {
        $this->events = $eventsCollection;
        $this->group = $groupDisplayArray;
        $this->total = $totalTime;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->from('test@test.test', 'Test the Test');
        // Composes a plain teext email.
        // return $this->text('emails.report_plain');
        // Composes a formatted (HTML) email.
        return $this->view('emails.report_html');
    }
}
